<?php
require_once '../../config/config.php';

// Obtener datos de las tablas relacionadas con el módulo VIH
$accesibilidad_calidad = $conn->query("SELECT accesibilidad_servicios, actitud_personal, tarifas_ocultas, factores_mejora, disponibilidad_herramientas FROM accesibilidad_calidad")->fetch_all(MYSQLI_ASSOC);
$percepcion_servicios = $conn->query("SELECT calidad_servicio, servicios_mejorar, cambios_recientes FROM percepcion_servicios")->fetch_all(MYSQLI_ASSOC);

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
    }
    .chart-container {
        width: 100%;
        height: 400px;
        margin: 20px auto;
    }
</style>
';

// Consolidar datos
function consolidarDatos($data, $columnas) {
    $resultados = [];
    foreach ($columnas as $columna) {
        $frecuencias = array_count_values(array_column($data, $columna));
        ksort($frecuencias);
        $resultados[$columna] = $frecuencias;
    }
    return $resultados;
}

$accesibilidadConsolidada = consolidarDatos($accesibilidad_calidad, ['accesibilidad_servicios', 'actitud_personal', 'tarifas_ocultas', 'factores_mejora', 'disponibilidad_herramientas']);
$percepcionConsolidada = consolidarDatos($percepcion_servicios, ['calidad_servicio', 'servicios_mejorar', 'cambios_recientes']);

// Generar HTML
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte VIH Interactivo</title>
    ' . $css . '
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Reporte VIH Interactivo</h1>
    <div class="chart-container"><canvas id="accesibilidadChart"></canvas></div>
    <div class="chart-container"><canvas id="percepcionChart"></canvas></div>

    <script>
        const accesibilidadData = ' . json_encode($accesibilidadConsolidada) . ';
        const percepcionData = ' . json_encode($percepcionConsolidada) . ';

        function generateChart(ctx, data, title) {
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: "Frecuencia",
                        data: Object.values(data),
                        backgroundColor: "rgba(75, 192, 192, 0.6)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: title
                        }
                    }
                }
            });
        }

        window.onload = function() {
            generateChart(document.getElementById("accesibilidadChart"), accesibilidadData, "Accesibilidad y Calidad");
            generateChart(document.getElementById("percepcionChart"), percepcionData, "Percepción de Servicios");
        };
    </script>
</body>
</html>
';

// Guardar el archivo HTML
$filePath = '../../temp/reporte_vih_interactivo.html';
file_put_contents($filePath, $html);

// Redirigir al archivo generado
header("Location: " . $filePath);
?>
