<?php
require_once '../../config/config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Obtener datos de las tablas
$indicadores_uso = $conn->query("SELECT nivel_actividad, frecuencia_recomendaciones, calidad_uso FROM indicadores_uso")->fetch_all(MYSQLI_ASSOC);
$participacion_comunitaria = $conn->query("SELECT nivel_participacion, grupos_comprometidos, estrategias_mejora FROM participacion_comunitaria")->fetch_all(MYSQLI_ASSOC);
$eventos_salud = $conn->query("SELECT nombre_evento, descripcion, acciones FROM eventos_salud")->fetch_all(MYSQLI_ASSOC);
$necesidades_comunitarias = $conn->query("SELECT descripcion, acciones, area_prioritaria FROM necesidades_comunitarias")->fetch_all(MYSQLI_ASSOC);

// Estilos CSS optimizados para PDF
$css = '
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        color: #333;
    }
    h1, h2 {
        text-align: center;
        color: #007BFF;
        margin-bottom: 10px;
    }
    h2 {
        
        margin-top: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }
    th {
        background-color: #007BFF;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    .table-container {
        margin-bottom: 20px;
    }
    .no-data {
        text-align: center;
        font-size: 14px;
        margin: 10px 0;
        color: #888;
    }
    .totals-row {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>
';

// Función para consolidar datos y calcular totales y porcentajes
function consolidarDatos($data, $columnas) {
    $resultados = [];
    $totales = [];
    foreach ($columnas as $columna) {
        $frecuencias = array_count_values(array_column($data, $columna));
        ksort($frecuencias);
        $total = array_sum($frecuencias);
        $resultados[$columna] = [
            'frecuencias' => $frecuencias,
            'total' => $total
        ];
        $totales[$columna] = $total;
    }
    return [$resultados, $totales];
}

// Consolidar datos
list($indicadoresConsolidados, $totalesIndicadores) = consolidarDatos($indicadores_uso, ['nivel_actividad', 'frecuencia_recomendaciones', 'calidad_uso']);
list($participacionConsolidada, $totalesParticipacion) = consolidarDatos($participacion_comunitaria, ['nivel_participacion', 'grupos_comprometidos', 'estrategias_mejora']);
list($eventosConsolidados, $totalesEventos) = consolidarDatos($eventos_salud, ['nombre_evento', 'descripcion', 'acciones']);
list($necesidadesConsolidadas, $totalesNecesidades) = consolidarDatos($necesidades_comunitarias, ['descripcion', 'acciones', 'area_prioritaria']);

// Construir HTML del PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General Consolidado</title>
    ' . $css . '
</head>
<body>
    <h1>Reporte General Consolidado</h1>
    ';

// Función para generar tablas con totales, subtotales y saltos de página
function generarTabla($titulo, $datosConsolidados, $esPrimeraTabla = false) {
    // Agregar un salto de página antes de cada tabla excepto la primera
    $pageBreak = $esPrimeraTabla ? '' : 'page-break-before: always;';

    $html = "<h2 style='{$pageBreak}'>{$titulo}</h2>";
    if (!empty($datosConsolidados)) {
        foreach ($datosConsolidados as $indicador => $datos) {
            $totalGeneral = $datos['total'];
            $html .= "<div class='table-container'><table>
                <thead>
                    <tr>
                        <th>Indicador: {$indicador}</th>
                        <th>Valor</th>
                        <th>Frecuencia</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>";
            foreach ($datos['frecuencias'] as $valor => $frecuencia) {
                $porcentaje = $totalGeneral > 0 ? round(($frecuencia / $totalGeneral) * 100, 2) : 0;
                $html .= "<tr><td></td><td>{$valor}</td><td>{$frecuencia}</td><td>{$porcentaje}%</td></tr>";
            }
            // Subtotales
            $html .= "<tr class='totals-row'>
                <td colspan='2'>Total</td>
                <td>{$totalGeneral}</td>
                <td>100%</td>
            </tr>";
            $html .= "</tbody></table></div>";
        }
    } else {
        $html .= '<p class="no-data">No hay datos disponibles para este reporte.</p>';
    }
    return $html;
}

// Indicadores de Uso (Primera tabla, sin salto de página)
$html .= generarTabla('Indicadores de Uso', $indicadoresConsolidados, true);

// Participación Comunitaria
$html .= generarTabla('Participación Comunitaria', $participacionConsolidada);

// Eventos de Salud
$html .= generarTabla('Eventos de Salud', $eventosConsolidados);

// Necesidades Comunitarias
$html .= generarTabla('Necesidades Comunitarias', $necesidadesConsolidadas);

$html .= '</body></html>';

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Guardar el PDF
$pdfFilePath = '../../temp/reporte_general_consolidado.pdf';
file_put_contents($pdfFilePath, $dompdf->output());

// Navegación
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Generado</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        .btn { padding: 10px 20px; margin: 10px; text-decoration: none; color: white; border-radius: 5px; }
        .btn-primary { background-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Reporte generado con éxito</h1>
    <a href="' . $pdfFilePath . '" class="btn btn-primary" target="_blank">Ver PDF</a>
    <a href="../modulo_general.php" class="btn btn-primary">Volver al Módulo General</a>
</body>
</html>';
?>
