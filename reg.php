

<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
</style>
<link href="css/diseñoReg.css" rel="stylesheet" type="text/css">
<div>&nbsp;</div>
<div><img src="images/WhatsApp Image 2024-02-27 at 11.04.44 PM.jpeg" width="200" height="184" alt=""/>&nbsp;</div>
<div>&nbsp;</div>

<div id="divInicio">!Bienvenido alumno del ITT¡&nbsp;&nbsp; </div>
<div class="container">
        <div class="row">

          <div class="col-md-8 col-md-offset-2 col-xl-12">
                  <legend class="text-center" id="divtxt">Ingrese los datos solicitados para su registro</legend>
			  
<form action="conexionReg.php" method="POST" onsubmit="validarCombo()">
<fieldset>
<div class="form-group col-md-6 offset-xl-3">
	<div style="color: orange; text-align: left;"> Nombre</div>
	<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)"></div>

                        <div class="form-group col-md-6 offset-xl-3">
                            <div style="color: orange; text-align: left;" for="last_name">Apellido Paterno&nbsp;</div>
                            <input type="text" class="form-control" name="apellidoP" id="apellidoP" placeholder="Apellido Paterno" required>
                        </div>
						
						<div class="form-group col-md-6 offset-xl-3">
                            <div for="last_name" style="color: orange; text-align: left;">Apellido Materno&nbsp;</div>
                            <input type="text" class="form-control" name="apellidoM" id="apellidoM" placeholder="Apellido Materno" required>
                      </div> 
	
		  <div class="form-group col-md-6 offset-xl-3">
                            <div for="last_name" style="color: orange; text-align: left;">Correo Institucional&nbsp;</div>
                            <input type="text" class="form-control" name="correo" id="correo" placeholder="@ittepic.edu.mx" required>
                      </div> 
<div class="form-group col-md-12 col-xl-6 offset-xl-3">
            <div for="" style="color: orange; text-align: left; max-width: none;">Número de control&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Carrera&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
<div class="button-container">
   
   <input type="hidden" name="carreraIndex" id="carreraIndex">

  <input type="text" class="form-control2" name="noCtl" id="noCtl" placeholder="xxxxxx" required> 
		<select name ="comboCarrera" Style = "color: black;" class = "comboCarrera" id="comboCarrera" onchange="updateIndex()">
		  <option value="opcion7" Style = "color: black;">Seleccione una opción</option>
		  <option value="opcion1" Style = "color: black;">Arquitectura</option>
		  <option value="opcion2" Style = "color: black;">Ingeniería Bioquímica</option>
		  <option value="opcion3" Style = "color: black;">Ingeniería Civil</option>
		  <option value="opcion4" Style = "color: black;">Ingeniería Eléctrica</option>
		  <option value="opcion5" Style = "color: black;">Ingeniería en Gestión Empresarial</option>
		  <option value="opcion6" Style = "color: black;">Ingeniería en Sistemas Computacionales</option>
		  <option value="opcion7" Style = "color: black;">Ingeniería Industrial</option>
		  <option value="opcion8" Style = "color: black;">Ingeniería Mecatrónica</option>
		  <option value="opcion9" Style = "color: black;">Ingeniería Química</option>
		  <option value="opcion10" Style = "color: black;">Licenciatura en Administración</option>
		</select>
	&nbsp; </div>
							
		  </div>

          <div class="form-group col-md-6 offset-xl-3">
                            <div for="password" style="color: orange; text-align: left; max-width: 65px; max-height: inherit;">Contraseña</div>
                            <input type="password" class="form-control" name="contra" id="contra" placeholder="Contraseña" required>
                        </div>

                        <div class="form-group col-md-6 offset-xl-3">
                            <div for="confirm_password" style="color: orange; text-align: left;">Confirmar Contraseña&nbsp;</div>
                            <input type="password" class="form-control" name="confirmar contraseña" id="confirmar contraseña" placeholder="Confirmar Contraseña" required>
                        </div>
        </fieldset>
	
<div class="form-group">
            <div class="col-md-12">
							
							
                            <div class="checkbox">
                                <label>
									<style>
								/* Estilo para los enlaces */
								a {
									color: orange; /* Cambia este color por el que desees */
									text-decoration: none; /* Quita el subrayado del enlace si no lo deseas */
								}
									</style>
									
                                    <input type="checkbox" value="" id="">
                                    Aceptar los <a href="#">términos y condiciones&nbsp;</a>.
                                </label>
                            </div>
        </div>
            </div>

                    <div class="form-group">
						<style>
							/* Estilo para el botón */
							.btn-custom {
								background-color: orange; /* Cambia este color por el que desees */
								color: black; /* Color del texto del botón */
								padding: 10px 15px; /* Ajusta el relleno según necesites */
								border-radius: 5px; /* Añade bordes redondeados si lo deseas */
								cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
								font-size: 17px;
								font-family: "Helvetica";
							}
						</style>
                        <div class="col-md-12">
                          <button type="submit" class="btn-custom" name="reg", id="reg">Registrarse
                            </button>
			
                          <a href="Login.php">¿Ya tienes una cuenta?</a>
                          <div>&nbsp;</div>
                          <div>&nbsp;</div>
                          <div>©DailyTec 2024. Derechos reservados. Tepic, Nayarit. MÉXICO.</div>
                        </div>
                    </div> 
 </form>  
			  
			  
	
	<script>
        function updateIndex() {
            // Obtener el índice seleccionado
            var index = document.getElementById("comboCarrera").selectedIndex;
            // Asignar el valor del índice al campo oculto
            document.getElementById("carreraIndex").value = index;
        }
		
    </script>
			  
  
			
			</div>

        </div>
	
    </div>






	
	
