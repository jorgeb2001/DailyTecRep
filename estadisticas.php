<?php
    include(__DIR__ . '/auth.php');
    include(__DIR__ . '/conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<div id="div1">
        <a class="#div1 letras" href="index.php"><span class="iconoflecha" ><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Asistencia</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #2c2c2c;
            color: white;
        }

        .container {
            text-align: center;
            background-color: #3b3b3b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 800px;
            margin-bottom: 20px;
        }

        h1 {
            color: #ffa500;
            margin-top: 0;
        }

        .chart-container {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }

        .chart-container canvas {
            width: 100%;
            height: 300px;
        }

        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #3b3b3b;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ffffff;
        }

        th {
            background-color: #ffa500;
            color: white;
        }

        .export-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffa500;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .export-btn:hover {
            background-color: #e59400;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Estadísticas de Asistencia</h1>
        <div class="chart-container">
            <canvas id="asistenciaChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="carreraChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="asistenciaPorCarreraChart"></canvas>
        </div>
        <table id="alumnosTable">
            <thead>
                <tr>
                    <th>No. de Control</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button class="export-btn" onclick="exportTableToCSV('alumnos.csv')">Exportar a CSV</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('fetch_statistics.php?idActividad=<?php echo $_GET['idActividad']; ?>')
                .then(response => response.json())
                .then(data => {
                    console.log('Parsed data:', data);

                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    var ctx1 = document.getElementById('asistenciaChart').getContext('2d');
                    var asistenciaChart = new Chart(ctx1, {
                        type: 'pie',
                        data: {
                            labels: ['Asistieron', 'No Asistieron'],
                            datasets: [{
                                label: 'Asistencia',
                                data: [data.asistieron, data.noAsistieron],
                                backgroundColor: ['#36a2eb', '#ff6384']
                            }]
                        }
                    });

                    var ctx2 = document.getElementById('carreraChart').getContext('2d');
                    var carreraChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: data.carreras.labels,
                            datasets: [{
                                label: 'Asistencias por Carrera',
                                data: data.carreras.data,
                                backgroundColor: '#36a2eb'
                            }]
                        }
                    });

                    var ctx3 = document.getElementById('asistenciaPorCarreraChart').getContext('2d');
                    var asistenciaPorCarreraChart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels: data.carreras.labels,
                            datasets: [{
                                label: 'Porcentaje de Asistencia por Carrera',
                                data: data.porcentajeAsistenciaPorCarrera,
                                borderColor: '#ff6384',
                                fill: false
                            }]
                        }
                    });

                    // Poblar la tabla con los datos de los alumnos
                    var alumnosTableBody = document.getElementById('alumnosTable').querySelector('tbody');
                    data.alumnos.forEach(function(alumno) {
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${alumno.no_control}</td>
                            <td>${alumno.nombre}</td>
                            <td>${alumno.apellido_paterno}</td>
                            <td>${alumno.apellido_materno}</td>
                            <td>${alumno.correo}</td>
                        `;
                        alumnosTableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching the data:', error));
        });

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            csvFile = new Blob([csv], {type: 'text/csv'});

            downloadLink = document.createElement('a');
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll('table tr');

            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');

                for (var j = 0; j < cols.length; j++) {
                    row.push(cols[j].innerText);
                }

                csv.push(row.join(','));
            }

            downloadCSV(csv.join('\n'), filename);
        }
    </script>
</body>
</html>
