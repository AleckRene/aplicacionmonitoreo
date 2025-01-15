<?php
require_once '../../config/config.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Obtener datos de las tablas asociadas al Módulo VIH
$accesibilidad_calidad = $conn->query("SELECT * FROM accesibilidad_calidad")->fetch_all(MYSQLI_ASSOC);
$percepcion_servicios = $conn->query("SELECT * FROM percepcion_servicios")->fetch_all(MYSQLI_ASSOC);

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
    <title>Reporte VIH</title>
    ' . $css . '
</head>
<body>
    <div class="logo">
        <img src="../../assets/images/logo.png" alt="Logo">
    </div>
    <h1>Reporte VIH</h1>
';

// Accesibilidad y Calidad
$html .= '<h2>Accesibilidad y Calidad</h2>';
if (!empty($accesibilidad_calidad)) {
    $html .= '<table><thead><tr><th>ID</th><th>Accesibilidad</th><th>Actitud Personal</th><th>Tarifas Ocultas</th><th>Factores de Mejora</th><th>Disponibilidad de Herramientas</th></tr></thead><tbody>';
    foreach ($accesibilidad_calidad as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['accesibilidad_servicios']}</td><td>{$row['actitud_personal']}</td><td>{$row['tarifas_ocultas']}</td><td>{$row['factores_mejora']}</td><td>{$row['disponibilidad_herramientas']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para accesibilidad y calidad.</p>';
}

// Percepción de Servicios
$html .= '<h2>Percepción de Servicios</h2>';
if (!empty($percepcion_servicios)) {
    $html .= '<table><thead><tr><th>ID</th><th>Calidad del Servicio</th><th>Servicios por Mejorar</th><th>Cambios Recientes</th></tr></thead><tbody>';
    foreach ($percepcion_servicios as $row) {
        $html .= "<tr><td>{$row['usuario_id']}</td><td>{$row['calidad_servicio']}</td><td>{$row['servicios_mejorar']}</td><td>{$row['cambios_recientes']}</td></tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay datos disponibles para percepción de servicios.</p>';
}

$html .= '</body></html>';

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Guardar el PDF en un archivo temporal
$pdfFilePath = '../../temp/reporte_vih.pdf';
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
    <a href="../modulo_vih.php" class="btn btn-secondary">Volver al Módulo VIH</a>
</body>
</html>';
?>
