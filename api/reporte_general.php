<?php
require_once '../vendor/autoload.php';
require_once '../config/config.php';

use Dompdf\Dompdf;

// Generar contenido HTML
ob_start();
include '../views/reports/reporte_general.php';
$html = ob_get_clean();

// Crear instancia de Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Configurar tamaño de papel y orientación
$dompdf->setPaper('A4', 'landscape');

// Renderizar el HTML como PDF
$dompdf->render();

// Enviar el archivo PDF al navegador
$dompdf->stream("reporte_general.pdf", ["Attachment" => false]);
