<?php
require_once '../../config/config.php';

// Consolidar los datos necesarios
$reportes = [
    'general' => '/aplicacionmonitoreo/temp/reporte_general_interactivo.html',
    'vih' => '/aplicacionmonitoreo/temp/reporte_vih_interactivo.html',
    'tb' => 'EN_CONSTRUCCION',
    'malaria' => 'EN_CONSTRUCCION',
    'pandemias' => 'EN_CONSTRUCCION'
];

// Construcción del HTML
$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Interactivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            background-color: #f1f1f1;
        }
        .tabs button {
            flex: 1;
            padding: 15px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            text-align: center;
        }
        .tabs button.active {
            background-color: white;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
        .construction {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 50px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("modulo") || "general";

            const tabs = document.querySelectorAll(".tabs button");
            const contents = document.querySelectorAll(".content");

            tabs.forEach((tab, index) => {
                const moduleName = tab.dataset.module;
                tab.addEventListener("click", () => {
                    tabs.forEach(t => t.classList.remove("active"));
                    contents.forEach(content => content.style.display = "none");

                    tab.classList.add("active");
                    contents[index].style.display = "block";
                });

                // Activar la pestaña según el parámetro en la URL
                if (moduleName === activeTab) {
                    tab.click();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Reportes Interactivos</h1>
        </header>
        <div class="tabs">
            <button class="active" data-module="general">Reporte General</button>
            <button class="active" data-module="vih">Reporte VIH</button>
            <button data-module="tb">Reporte TB</button>
            <button data-module="malaria">Reporte Malaria</button>
            <button data-module="pandemias">Reporte Pandemias</button>
        </div>
        <div class="content">
            <iframe src="' . $reportes['general'] . '"></iframe>
        </div>
        <div class="content" style="display: none;">
            <iframe src="' . $reportes['vih'] . '"></iframe>
        </div>
        <div class="content" style="display: none;">
            <p class="construction">El reporte de TB está en construcción.</p>
        </div>
        <div class="content" style="display: none;">
            <p class="construction">El reporte de Malaria está en construcción.</p>
        </div>
        <div class="content" style="display: none;">
            <p class="construction">El reporte de Pandemias está en construcción.</p>
        </div>
    </div>
</body>
</html>';

// Guardar el archivo HTML
$filePath = '../../temp/reportes_interactivos.html';
file_put_contents($filePath, $html);

// Redirigir al archivo generado
header("Location: " . $filePath);
