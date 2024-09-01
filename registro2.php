<?php
session_start();
session_destroy();
$id_pagina = 'login';
 ?>

<!doctype html>
<html lang="es">
<head>
  <title>Escuela Normal Superior 40 - Gestion de Alumnado</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="./public/img/favicon.ico">
  <!-- css custom bootstrap theme -->
  <link rel="stylesheet" href="./public/css/custom.css" type="text/css">
  <!-- assets fonts -->
  <link rel="stylesheet" href="https://www.santafe.gob.ar/assets/standard/css/fonts.css" type="text/css">
  <style>

  /* fallback */
  @font-face {
    font-family: 'Material Icons';
    font-style: normal;
    font-weight: 400;
    src: url(./public/assets/material-design-icons/MaterialIcons-Regular.eot); /* For IE6-8 */
    src: local('Material Icons'),
    local('MaterialIcons-Regular'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.woff2) format('woff2'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.woff) format('woff'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.ttf) format('truetype');
  }

  .material-icons {
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -moz-font-feature-settings: 'liga';
    -moz-osx-font-smoothing: grayscale;
  }

  
  </style>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=6Ld6Y6oaAAAAAMqMNwMcdEBjkJugmHZ6Nu6Cpc5T"></script>
</head>


<body class="imag">
    <?php include_once('navBar.php') ?>


  <main role="main" class="">
    <div class="row row-top">

      <div class="col-lg-12">



      </div>
    </div>

    <div class="row justify-content-center mt-5 ml-5">
      <div class="col-lg-6">
        <div class=" form2 mb-12">
          <div class="card-header"><b><h1 class="h1v1">Consulta de Capacitaciones Realizadas</h1></b></div>
          <form name="formAcceder" id="formAcceder" action='ajax/autenticar.php'>
          <div class="modal-body">
             
          <div class="form-row">
                                   <div class="form-group col-md-6">
                                     <label for="inputCarrera"><b>Carrera a la que se Inscribe <font color='red'>*</font></b></label>
                                     <select id="inputCarrera" name="inputCarrera" class="form-control" required>
                                         <option selected>Selecione Carrera</option>
                                         <?php
                                                echo $opciones;
                                          ?>
                                     </select>
                                   </div>
                               </div>  

                             <div class="form-row">
                                 <div class="form-group col-md-6">
                                     <label for="inputApellido"><b>Apellido <font color='red'>*</font></b></label>
                                     <input type="text" class="form-control" id="inputApellido" name="inputApellido" maxlength="35" required>
                                 </div>
                                 <div class="form-group col-md-6">
                                     <label for="inputNombres"><b>Nombres  <font color='red'>*</font></b></label>
                                     <input type="text" class="form-control" id="inputNombres" name="inputNombres" maxlength="35" required>
                                 </div>
                             </div>

                             <div class="form-row">
                                 <div class="form-group col-md-6">
                                     <label for="inputDni"><b>DNI (Sin puntos) <font color='red'>*</font></b></label>
                                     <input type="text" class="form-control" id="inputDni" name="inputDni" maxlength="8" required>
                                 </div>
                                 <div class="form-group col-md-6">
                                     <label for="inputFechaNacimiento"><b>Fecha Nacimiento <font color='red'>*</font></b></label>
                                     <input type="text" class="form-control" id="inputFechaNacimiento" name="inputFechaNacimiento" required>
                                 </div>
                             </div>

                            <div class="form-row">
                                   <div class="form-group col-md-6">
                                       <label for="inputCity"><b>Localidad <font color='red'>*</font></b></label>
                                       <!-- <input type="text" class="form-control" id="inputLocalidad" name="inputLocalidad" maxlength="45" placeholder="San Cristobal"> -->
                                       <select id="inputLocalidad" class="form-control select2" placeholder="Localidad" required>
                                           <option>Seleccione Localidad</option>
                                       </select>
                                   </div>
                             </div>
                             
                             <label for="inputCelular"><b>Celular (Sin el 0 del codigo de area y sin el 15)<font color='red'>*</font></b></label> 
                             <div class="form-row">
                                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <strong>&nbsp;0&nbsp;</strong>
                                      </div>
                                    </div> 
                                    <input id="inputCaracteristicaTelefono" inputmode="tel" type="text" class="form-control" minlength="2" maxlength="4"  onKeyPress="return soloNumeros(event)" onKeyUp="pierdeFoco(this)"  required>
                                  </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <strong>15</strong>
                                      </div>
                                    </div> 
                                    <input id="inputNumeroTelefono" inputmode="tel" type="text" class="form-control" minlength="6" maxlength="9"  onkeypress="return valideKey(event);"  required>
                                  </div>
                                </div>
                              </div>
            
                            <div class="form-row">&nbsp;</div>    
                                
                             <div class="form-row">
                                 <div class="form-group col-md-6">
                                     <label for="inputGenero"><b>Género <font color='red'>*</font></b></label>
                                     <select id="inputGenero" name="inputGenero" class="form-control" required>
                                         <option selected>Seleccione Género</option>
                                         <option value="M">Masculino</option>
                                         <option value="F">Femenino</option>
                                         <option value="O">Prefiero no decirlo</option>
                                     </select>
                                 </div>
                             </div>

                               <div class="form-row">
                                   <div class="form-group col-md-6">
                                       <label for="inputEmail"><b>Email <font color='red'>*</font></b></label>
                                       <input type="email" class="form-control" id="inputEmail" name="inputEmail" maxlength="45" placeholder="Email" required>
                                   </div>
                               </div>

                               <div class="form-group">
                                   <label for="inputDomicilio"><b>Domicilio <font color='red'>*</font></b></label>
                                   <input type="text" class="form-control" id="inputDomicilio" name="inputDomicilio" maxlength="45" placeholder="San Martin 1122" required>
                               </div>

                               <div class="form-row">
                                   <div class="form-group col-md-6">
                                     <label for="inputEstadoCivil"><b>Estado Civil <font color='red'>*</font></b></label>
                                     <select id="inputEstadoCivil" name="inputEstadoCivil" class="form-control" required>
                                         <option selected value='1'>Soltero/a</option>
                                         <option value='2'>Casado/a</option>
                                         <option value='3'>Unión libre o unión de hecho</option>
                                         <option value='4'>Divorciado/a</option>
                                         <option value='5'>Separado/a</option>
                                         <option value='6'>Viudo/a</option>
                                     </select>
                                   </div>

                                   <div class="form-group col-md-6">
                                       <label for="inputOcupacion"><b>Ocupación</b></label>
                                       <input type="text" id="inputOcupacion" name="inputOcupacion" class="form-control" required>
                                   </div>
                               </div>


                               <div class="form-row">
                               <div class="form-group col-md-6">
                                       <label for="inputTitulo"><b>Titulo Secundario</b></label>
                                       <input type="text" id="inputTitulo" name="inputTitulo" class="form-control" required>
                                   </div>

                                   <div class="form-group col-md-6">
                                       <label for="inputEscuela"><b>Titulo Expedido por la escuela</b></label>
                                       <input type="text" id="inputEscuela" name="inputEscuela" class="form-control" required>
                                   </div>
                               </div>

            <div class="form-row">
                <div class="form-group col-xs-12 col-sm-12 col-md-12" id="resultado"></div>
            </div>
        </div>
        </form>
      </div>  
    </div>
   </div>
  </div>


    <div class="row">
      <div class="col-12">
        <p class="float-right">
          <a href="#"><i class="material-icons">keyboard_arrow_up</i></a>
        </p>
      </div>
    </div>

  </main>

  <div class="clearfix"><br></div>

<?php include_once('footer.php'); ?>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="./public/assets/jquery/jquery-3.2.1.slim/jquery-3.2.1.min.js"></script>
  <script src="./public/assets/popper.js/1.12.3/umd/popper.min.js"></script>
  <script src="./public/assets/bootstrap/bootstrap-4.0.0-beta.2/js/bootstrap.min.js"></script>
  <script src="./public/assets/custom/verify.min2.js"></script>
  <script>
  function validaNumericos(event) {
    if(event.charCode >= 48 && event.charCode <= 57){
      return true;
     }
     return false;        
   }

   $("#btnAceptar").on("click",function(e) {
      let dni = $("#inputDni").val();
      $("#resultado").html("<table class='table table-sm table-dark'>");     
      $.post("ajax/buscarCursosPorDni.php",{"dni":dni},function(data){
          if (data.estado==100) {
             $("#resultado").append("<thead><tr><th width='76%' align='center'>Jornada</th><th width='14%' align='center'>Fecha</th><th align='center'>Descargar</th></tr></thead><tbody>");
               for (var val of data.datos) 
                {
                  $("#resultado").append("<tr><td><small>"+val.nombre+"</small></td><td><small>"+val.fecha+"</small></td><td align='center'><a href='#' target='_blank' onclick=\"window.open('./acreditacion/certificado.php?dni="+val.dni+"&id="+val.id+"&hash="+data.hash+"','','_blank')\"><img src='./public/img/pdf_icon.png' width='20'></a></td></tr>");
                }
                $("#resultado").append("</tbody>");

          } else {
             $("#resultado").append("<tr><th>No tiene cursos realizados.</th></tr>"); 
          }
             
      },"json")
      $("#resultado").append("</table>");      
   })
</script>
</body>
</html>
