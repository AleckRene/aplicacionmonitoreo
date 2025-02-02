<?php
require_once '../../config/config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Consultas para obtener los datos
$accesibilidad_calidad = $conn->query("SELECT * FROM accesibilidad_calidad")->fetch_all(MYSQLI_ASSOC);
$percepcion_servicios = $conn->query("SELECT * FROM percepcion_servicios")->fetch_all(MYSQLI_ASSOC);

// Estilos CSS
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
    foreach ($columnas as $columna) {
        $frecuencias = array_count_values(array_column($data, $columna));
        ksort($frecuencias);
        $total = array_sum($frecuencias);
        $resultados[$columna] = [
            'frecuencias' => $frecuencias,
            'total' => $total,
        ];
    }
    return $resultados;
}

// Consolidar datos
$consolidadoAccesibilidad = consolidarDatos($accesibilidad_calidad, [
    'accesibilidad_servicios',
    'actitud_personal',
    'tarifas_ocultas',
    'factores_mejora',
    'disponibilidad_herramientas',
]);
$consolidadoPercepcion = consolidarDatos($percepcion_servicios, [
    'calidad_servicio',
    'servicios_mejorar',
    'cambios_recientes',
]);

// Construir HTML del PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Consolidado de VIH</title>
    ' . $css . '
</head>
<body>
    <h1>Reporte Consolidado de VIH</h1>
';

// Función para generar tablas
function generarTabla($titulo, $datosConsolidados) {
    $html = "<h2>{$titulo}</h2>";
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
                $html .= "<tr>
                    <td></td>
                    <td>{$valor}</td>
                    <td>{$frecuencia}</td>
                    <td>{$porcentaje}%</td>
                </tr>";
            }
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

// Accesibilidad y Calidad (Primera tabla)
$html .= generarTabla('Accesibilidad y Calidad', $consolidadoAccesibilidad);

// Percepción de Servicios (Con salto de página)
$html .= generarTabla('Percepción de Servicios', $consolidadoPercepcion);

$html .= '</body></html>';

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Guardar el PDF
$pdfFilePath = '../../temp/reporte_vih_consolidado.pdf';
file_put_contents($pdfFilePath, $dompdf->output());

// Opciones de navegación
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte VIH</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        .btn { padding: 10px 20px; margin: 10px; text-decoration: none; color: white; border-radius: 5px; }
        .btn-primary { background-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Reporte de VIH Generado</h1>
    <a href="' . $pdfFilePath . '" class="btn btn-primary" target="_blank">Ver PDF</a>
    <a href="../modulo_vih.php" class="btn btn-primary">Volver al Módulo VIH</a>
</body>
</html>';
?>
