<?php
session_start();
session_destroy();
set_include_path("./conexion/");
require_once('conexion.php');


$fechaActual = date("d-m-Y");
//resto 17 años
$fechaNamientoValida = date("d-m-Y",strtotime($fechaActual. " - 17 year"));

$diaValido = substr($fechaNamientoValida,0,2);
$mesValido = substr($fechaNamientoValida,3,2);
$anioValido = substr($fechaNamientoValida,6,4);

function generaArregloCarreras($conex) {
   $sqlCarrera="select b.id, b.descripcion
                      from carrera b WHERE habilitacion_registro = 'Si'";
   //echo $sqlAlumnoCarrera;
   $resultadoSqlAlumnoCarrera=  mysqli_query($conex, $sqlCarrera);

   $option = "";
   while ($filaAlumnoCarrera=  mysqli_fetch_assoc($resultadoSqlAlumnoCarrera)) {
       $arregloCarreras['id']=$filaAlumnoCarrera['id'];
       $arregloCarreras['nombre']=$filaAlumnoCarrera['descripcion'];
       $option.= "<option value='".$filaAlumnoCarrera['id']."'>".$filaAlumnoCarrera['descripcion']."</option>";

   };

   //var_dump($_SESSION['ARRAY_CARRERAS']);
   return $option;
};

$opciones = generaArregloCarreras($conex);
//var_dump($opciones);die;

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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="./public/assets/css/select2.min.css">
  <link rel="stylesheet" href="./public/assets/css/select2-bootstrap.css">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6Ld6Y6oaAAAAAMqMNwMcdEBjkJugmHZ6Nu6Cpc5T"></script>
</head>


<body>
  <?php include_once('navBar.php') ?>
  

  <main role="main" class="container">


       <div class="row row-top">

           <div class="col-lg-12">
               <h1>Registro de Ingresante</h1>
               <p>Escuela Normal Superior 40 "Mariano Moreno"</p>
                           </div>
       </div>

                   <div class="row">
               <nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Registro del Ingresante</li>
                                           </ol>
               </nav>
           </div>

       <div class="row">
           <div class="col-lg-12">
                                   <div class="card bg-light mb-12">
                       <div class="card-header"><b>FORMULARIO DE INSCRIPCIÓN A CARRERAS</b><br><b><font color='red'>*</font> Datos Obligatorios</b></div>


                       <div class="card-body">

                           <form id="form_ingresante">

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

                               <div>
                                   <button type="submit" class="btn btn-primary btn-block" id="btnAceptar" name="btnAceptar">Aceptar</button>
                               </div>
                               
                               <div>
                                   &nbsp;
                               </div>
                               
                               <div>
                                   <button type="button" class="btn btn-primary btn-block" onclick="location.href='index.php'">Volver</button>
                               </div>

                           </form>
                       </div>

                   </div>

                           </div>
       </div>

        <div class="row">
              <div class="col-12" id="resultado">
                   
               </div>
        </div>


   </main>

  <div class="clearfix"><br></div>

  <footer class="footer text-muted">
    <div class="container-fluid">
      <div class="row">

        <div class="offset-md-2 col-md-3">
          <div class="sociales">
            <p><a href="/index.php/web/content/view/full/117678">RSS / SUSCRIPCIÓN A NOTICIAS</a></p>
            <ul class="list-inline footer-ul">
                <li class="list-inline-item"><a target="_blank" href="https://www.facebook.com/ens40marianomoreno/"><i
                  class="icon-footerfacebook"></i></a>
                </li>
                <li class="list-inline-item"><a target="_blank" href="http://www.youtube.com/GobSantaFe"><i
                    class="icon-footeryoutube"></i></a></li>
                <li class="list-inline-item"><a target="_blank" href="https://instagram.com/ens40_nivelsuperior"><i
                      class="icon-footerinstagram"></i></a></li>
                      
                </ul>
                      </div>

                    </div>

                    <div class="col-md-3">

                      <div class="marca">
                        <img src="https://www.santafe.gob.ar/assets/app/portal/imgs/marca-footer.png" alt="Santa Fe">
                        <p>Atención telefónica: 0800-777-0801 </p>
                        <p>Lunes a viernes de 8 a 18 hs </p>
                        <p><span class="cc">c</span> Atribución-CompartirIgual 2.5 Argentina</p>
                      </div>


                    </div>

                    <div class="col-md-3">

                      <div class="stg-logos-contenedor">
                        <div class="stg-logos">
                          <a href="https://www.santafe.gob.ar/tecnologias" target="_blank"><span
                            class="stg-logos-logo-tec-stg"></span></a>
                          </div>
                        </div>

                      </div>

                    </div>

                  </div>
                </footer>

                <!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="./public/assets/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="./public/assets/bootstrap/bootstrap-4.0.0-beta.2/js/bootstrap.min.js"></script>
<script src="./public/assets/js/select2.min.js"></script>
<script src="./public/assets/js/sweetalert2.all.min.js"></script>
<script>

               $('#inputApellido').focus();



                     
               $(function() {
                      $( "#inputFechaNacimiento" ).datepicker(
                        {
                            dateFormat: "yy-mm-dd",
                            dayNames: [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
                            // Dias cortos traducido
                            dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
                            monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
                            // Nombres cortos de los meses traducido
                            monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],
                            //showOn: "button",
                            //buttonImage: "public/img/calendar.png",
                            maxDate: new Date(<?=$anioValido?>, <?=$mesValido?>, <?=$diaValido?>)
                        }
                      );
                      
                      
                       let url_select2_obtener = "ajax/localidadObtener.php";

                       $('#inputLocalidad').select2({
                                theme: "bootstrap",
                                placeholder: "Buscar Localidad",
                                language: {
                                            noResults: function() {
                                              return "No hay resultado";        
                                            },
                                            searching: function() {
                                              return "Buscando..";
                                            }
                                          },
                                ajax: {
                                    url: url_select2_obtener,
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (data) {
                                        return {
                                            searchTerm: data.term // search term
                                        };
                                    },
                                    processResults: function (response) {
                                        return {
                                            results:response
                                        };
                                    },
                                    cache: true
                                }
                        });

               });
               
function valideKey(evt){
    
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}          



function valideKeySoloNumerosSinPrimerCero(evt,val){
    var inputValue = val;
    //alert(val);
    // Verificar si el primer carácter es cero
    if (inputValue.length === 0 && evt.which === 48) {
        evt.preventDefault();
        return false;
    }
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57 && inputValue.charAt(0)!== '0') { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}

function soloNumeros(e) {
   var key = window.Event ? e.which : e.keyCode;
   return ((key >= 48 && key <= 57) ||(key==8))
 }
 
 function pierdeFoco(e){
    var valor = e.value.replace(/^0*/, '');
    e.value = valor;
 }

</script>
<script src="./public/assets/custom/verify.min2.js"></script>
</body>
</html>
