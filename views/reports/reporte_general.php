<?php
require_once '../../config/config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Obtener datos de las tablas asociadas al Módulo General
$indicadores_uso = $conn->query("SELECT * FROM indicadores_uso")->fetch_all(MYSQLI_ASSOC);
$participacion_comunitaria = $conn->query("SELECT * FROM participacion_comunitaria")->fetch_all(MYSQLI_ASSOC);
$eventos_salud = $conn->query("SELECT * FROM eventos_salud")->fetch_all(MYSQLI_ASSOC);
$necesidades_comunitarias = $conn->query("SELECT * FROM necesidades_comunitarias")->fetch_all(MYSQLI_ASSOC);

// Estilos CSS para el reporte
$css = '
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            color: #007BFF;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
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
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
        }
    </style>
';

// Construir HTML del reporte
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General</title>
    ' . $css . '
</head>
<body>
    <div class="logo">
        <img src="../../assets/images/logo.png" alt="Logo">
    </div>
    <h1>Reporte General</h1>
';

// Indicadores de Uso
$html .= '<h2>Indicadores de Uso</h2>';
if (!empty($indicadores_uso)) {
    $html .= '<table><thead><tr><th>ID</th><th>Número de Usuarios</th><th>Nivel de Actividad</th><th>Frecuencia Recomendaciones</th><th>Calidad Uso</th></tr></thead><tbody>';
    foreach ($indicadores_uso as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['numero_usuarios']}</td><td>{$row['nivel_actividad']}</td><td>{$row['frecuencia_recomendaciones']}</td><td>{$row['calidad_uso']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para indicadores de uso.</p>';
}

// Participación Comunitaria
$html .= '<h2>Participación Comunitaria</h2>';
if (!empty($participacion_comunitaria)) {
    $html .= '<table><thead><tr><th>ID</th><th>Nivel de Participación</th><th>Grupos Comprometidos</th><th>Estrategias Mejora</th></tr></thead><tbody>';
    foreach ($participacion_comunitaria as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['nivel_participacion']}</td><td>{$row['grupos_comprometidos']}</td><td>{$row['estrategias_mejora']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para participación comunitaria.</p>';
}

// Eventos de Salud
$html .= '<h2>Eventos de Salud</h2>';
if (!empty($eventos_salud)) {
    $html .= '<table><thead><tr><th>ID</th><th>Nombre del Evento</th><th>Descripción</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody>';
    foreach ($eventos_salud as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['nombre_evento']}</td><td>{$row['descripcion']}</td><td>{$row['fecha']}</td><td>{$row['acciones']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para eventos de salud.</p>';
}

// Necesidades Comunitarias
$html .= '<h2>Necesidades Comunitarias</h2>';
if (!empty($necesidades_comunitarias)) {
    $html .= '<table><thead><tr><th>ID</th><th>Descripción</th><th>Acciones</th><th>Área Prioritaria</th></tr></thead><tbody>';
    foreach ($necesidades_comunitarias as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['descripcion']}</td><td>{$row['acciones']}</td><td>{$row['area_prioritaria']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para necesidades comunitarias.</p>';
}

$html .= '</body></html>';

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();


// Guardar el PDF en un archivo temporal
$pdfFilePath = '../../temp/reporte_general.pdf';
file_put_contents($pdfFilePath, $dompdf->output());

// Opciones de navegación
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones del Reporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>El reporte ha sido generado con éxito.</h1>
    <p>Seleccione una opción:</p>
    <a href="' . $pdfFilePath . '" class="btn btn-primary" target="_blank">Ver/Imprimir PDF</a>
    <a href="../modulo_general.php" class="btn btn-secondary">Volver al Módulo General</a>
</body>
</html>';
?>
