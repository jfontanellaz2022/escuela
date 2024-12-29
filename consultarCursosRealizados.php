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
                <div class="form-group col-xs-12 col-sm-4 col-md-4">
                    <label class="text-white" for="inputDni"><strong>DNI</strong></label>
                    <input type="text" class="form-control" name="inputDni" id="inputDni" maxlength="8" placeholder="Ingrese DNI"  onkeypress='return validaNumericos(event)' inputmode="numeric" required>
                </div>
             </div>  

             <div class="form-row">
                   <div class="form-group col-xs-12 col-sm-4 col-md-4 ">
                       <button  type="submit" class="btn btn-primary ">Aceptar</button>
                   </div>
                   <div class="form-group col-xs-12 col-sm-4 col-md-4 "></div>
                   <div class="form-group col-xs-12 col-sm-4 col-md-4 d-flex justify-content-end">
                     <a href="index.php" class="btn btn-danger">Cancelar</a>
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
