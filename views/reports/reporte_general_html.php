<?php
require_once '../../config/config.php';

// Obtener datos de las tablas asociadas al Módulo General
$indicadores_uso = $conn->query("SELECT nivel_actividad, frecuencia_recomendaciones, calidad_uso FROM indicadores_uso")->fetch_all(MYSQLI_ASSOC);
$participacion_comunitaria = $conn->query("SELECT nivel_participacion, grupos_comprometidos, estrategias_mejora FROM participacion_comunitaria")->fetch_all(MYSQLI_ASSOC);
$eventos_salud = $conn->query("SELECT nombre_evento, descripcion, acciones FROM eventos_salud")->fetch_all(MYSQLI_ASSOC);
$necesidades_comunitarias = $conn->query("SELECT descripcion, acciones, area_prioritaria FROM necesidades_comunitarias")->fetch_all(MYSQLI_ASSOC);

// Estilos CSS
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
    .chart-container {
        width: 100%;
        height: 400px;
        margin: 20px auto;
    }
        .btn {
        padding: 10px 20px;
        font-size: 16px;
        margin: 10px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn:hover {
        background-color: #0056b3;
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

$indicadoresConsolidados = consolidarDatos($indicadores_uso, ['nivel_actividad', 'frecuencia_recomendaciones', 'calidad_uso']);
$participacionConsolidada = consolidarDatos($participacion_comunitaria, ['nivel_participacion', 'grupos_comprometidos', 'estrategias_mejora']);
$eventosConsolidados = consolidarDatos($eventos_salud, ['nombre_evento', 'descripcion', 'acciones']);
$necesidadesConsolidadas = consolidarDatos($necesidades_comunitarias, ['descripcion', 'acciones', 'area_prioritaria']);

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Interactivo</title>
    ' . $css . '
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Reporte General Interactivo</h1>
    <div class="chart-container"><canvas id="indicadoresUsoChart"></canvas></div>
    <div class="chart-container"><canvas id="participacionChart"></canvas></div>
    <div class="chart-container"><canvas id="eventosChart"></canvas></div>
    <div class="chart-container"><canvas id="necesidadesChart"></canvas></div>

    <script>
    // Datos para los gráficos
    const indicadoresUsoData = ' . json_encode($indicadoresConsolidados) . ';
    const participacionData = ' . json_encode($participacionConsolidada) . ';
    const eventosData = ' . json_encode($eventosConsolidados) . ';
    const necesidadesData = ' . json_encode($necesidadesConsolidadas) . ';

    function generateChart(ctx, data, title) {
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: "Frecuencia",
                    data: Object.values(data),
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
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
        generateChart(document.getElementById("indicadoresUsoChart"), indicadoresUsoData, "Indicadores de Uso");
        generateChart(document.getElementById("participacionChart"), participacionData, "Participación Comunitaria");
        generateChart(document.getElementById("eventosChart"), eventosData, "Eventos de Salud");
        generateChart(document.getElementById("necesidadesChart"), necesidadesData, "Necesidades Comunitarias");
    };
    </script>
</body>
</html>
';

// Guardar el archivo HTML
$filePath = '../../temp/reporte_general_interactivo.html';
file_put_contents($filePath, $html);

// Redirigir al archivo generado
header("Location: " . $filePath);
