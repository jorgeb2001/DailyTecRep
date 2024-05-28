<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>RegistroAsistencia</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario</title>
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  @import url("css/style.min.css");
  @import url("style.css");
  </style>
s
  
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
	<div class="container">
    <div class="form">
      <label for="control">No. Control</label>
      <input type="text" id="control" placeholder="Ingresa tu número de control">
    </div>
    <button class="orange-btn">Registrar E/S</button>
		<table class="data-table">
      <thead>
        <tr>
          <th>Índice</th>
          <th >No. Control</th>
          <th>Nombre</th>
          <th>Entrada</th>
          <th>Salida</th>
        </tr>
      </thead>
      <tbody>
		 <tr class="empty-row">
          <td>&nbsp;</td>
		  <td style="width: 150px;">&nbsp;</td>
	      <td style="width: 300px;">&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
        </tr>
        <tr class="empty-row">
          <td>&nbsp;</td>
		  <td>&nbsp;</td>
	      <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
        </tr>
        <tr class="empty-row">
          <td>&nbsp;</td>
		  <td>&nbsp;</td>
	      <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
        </tr> 
        <!-- Aquí se mostrarán los datos ingresados -->
      </tbody>
    </table>
  </div>
</body>
</html>
