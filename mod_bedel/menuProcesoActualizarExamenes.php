<?php 
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Constantes.php';

$objCalendario = new CalendarioAcademico();
$ARRAY_INSCRIPCION = $ARRAY_TURNO = [];
$idCalendario = "";
$disabledButton = "disabledbutton";
$ARRAY_TURNO = $objCalendario->getLastTurnoExamen();




$hoy = date("Y-m-d");
if (strtotime($hoy)>=strtotime($ARRAY_TURNO['fecha_inicio']) && 
    strtotime($hoy)<=strtotime($ARRAY_TURNO['fecha_final'])) {
        if ( $ARRAY_TURNO['codigo']==Constantes::CODIGO_PRIMER_TURNO || 
             $ARRAY_TURNO['codigo']==Constantes::CODIGO_TERCER_TURNO )
             {
                $disabledButton = "";
                $ARRAY_INSCRIPCION = $objCalendario->getLastInscripcionExamen();
                $idCalendario = $ARRAY_INSCRIPCION['id'];
                //var_dump($ARRAY_INSCRIPCION);exit;
             }
} 


?>
<!doctype html>
<html lang="es">
<head>
<?php
    include_once('../app/views/header.html');
?>  
</head>
<body>
 

 
 <!-- NAVBAR -->
<header>
    <?php include("navbar.php"); ?>
</header>

<article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Procesar Exámenes Aprobados</li>
          </ol>
      </nav>
    </div>
</article>


<article class="container-fluid">
    <h3>Proceso de Actualización de Exámenes Finales</h3>
    <h4>Inscripción Activa: <font color="blue"><?=$idCalendario;?></font></h4>
    <p class="font-weight-normal">Éste proceso elimina las inscripciones del segundo llamado que tuvieron aprobaciones en el primer llamado.</p>
    
</article>
  
<article  class="container-fluid">
    <section id="principal">
    <div class="form-row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-primary btn-block" onclick='actualizarInscripciones(<?=$idCalendario;?>)'>Actualizar</button>
        </div>
    </div>
    </section>  
    <section id="resultado_accion">
    </section>         
</article>

<span id="modalEliminar">

</span>  

 
<!-- FOOTER -->
<?php
    include_once('../app/views/footer.html');
?>

<!-- JAVASCRIPT LIBRARIES-->
<?php 
    include("../app/views/script_jquery.html");
?>

<!-- JAVASCRIPT CUSTOM -->
<script>

    function actualizarInscripciones(calendario) {
        //alert('');
        
        let parametros = { "calendario":calendario };
        $.ajax({
            url: '../API/procesoActualizarExamenesAprobados.php?token=<?=$_SESSION['token'];?>',
            data: parametros,
            dataType: 'json',
            method: 'POST',
            beforeSend: function () {
                $("#resultado_accion").fadeIn(200).html("<img src='../public/img/icons/load_icon.png' width='50' >");  
            },
            success: function (response) {
                $("#resultado_accion").fadeIn(200).html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                                             <div class="alert alert-`+response.class+` alert-dismissible fade show" role="alert">
                                            `+response.mensaje+`</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
                $(".btn").attr('disabled','disabled');
                
            }
        });
    }
</script>

</body>
</html>