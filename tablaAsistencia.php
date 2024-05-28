<?php
    include('auth.php');
    include('conexion.php');
?>

<!doctype html>
<html lang="es"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            max-width: 1000px;
        }

        .form {
            display: inline-block;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input {
            width: 250px;
            height: 35px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        button.orange-btn {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.orange-btn:hover {
            background-color: darkorange;
        }

        .table-container {
            max-height: 300px;
            overflow-y: scroll;
            margin-top: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #444;
        }

        .data-table th, .data-table td {
            border: 1px solid #555;
            padding: 8px;
            text-align: center;
        }

        .data-table th {
            background-color: #555;
            color: white;
        }

        .data-table tr:nth-child(even) {
            background-color: #3c3c3c;
        }

        .data-table tr:nth-child(odd) {
            background-color: #2c2c2c;
        }

        .data-table .empty-row {
            background-color: #2c2c2c;
        }
    </style>
</head>

<div id="div1">
        <a class="#div1 letras" href="index.php"><span class="iconoflecha" ><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div><body>
    <div class="container">
        <div class="form">
            <label for="control">No. Control</label>
            <input type="text" id="control" placeholder="Ingresa tu número de control">
        </div>
        <button class="orange-btn" onclick="registrar('entrada')">Entrada</button>
        <button class="orange-btn" onclick="registrar('salida')">Salida</button>
        <div id="message" style="color: red; margin-top: 10px;"></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Índice</th>
                        <th>No. Control</th>
                        <th>Nombre</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                    </tr>
                </thead>
                <tbody id="tabla-asistencia">
                    <!-- Filas de asistencia se agregarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function registrar(tipo) {
            var control = document.getElementById('control').value;

            if (control === '') {
                document.getElementById('message').innerText = 'Por favor, ingresa un número de control';
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'conexionAsistencia.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('message').style.color = 'green';
                        document.getElementById('message').innerText = response.message;
                        actualizarTabla(response.data);
                    } else {
                        document.getElementById('message').style.color = 'red';
                        document.getElementById('message').innerText = response.message;
                    }
                } else {
                    document.getElementById('message').innerText = 'Error al registrar la asistencia';
                }
            };
            xhr.send('control=' + control + '&tipo=' + tipo + '&fk_actividad=<?php echo $_GET['idActividad']; ?>');
        }

        function actualizarTabla(data) {
            var tbody = document.getElementById('tabla-asistencia');
            tbody.innerHTML = ''; // Limpiar la tabla existente

            data.forEach(function(row, index) {
                var tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row.control}</td>
                    <td>${row.nombre}</td>
                    <td>${row.entrada}</td>
                    <td>${row.salida}</td>
                `;

                tbody.appendChild(tr);
            });
        }
    </script>
</body>
</html>
