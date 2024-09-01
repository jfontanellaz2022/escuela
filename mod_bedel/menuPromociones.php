<?php
set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./'.PATH_SEPARATOR.'./funciones/');

require_once 'seguridad.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';

//require_once 'own_recuperarEventoTurnoExamenCalendarioAcademico.php';
//include_once 'own_arregloMateriasRegularesAprobadas.php';
include_once 'arregloCorrelativasParaRendir.php';


/*
 * Este Proceso saca todos los Promocionados de la tabla 'alumno_cursa_materia', pero que 
 * todavia no se les paso la aprobacion a la tabla 'alumno_rinde_materia'
 * VERIFICA QUE LOS APROBADOS/PROMOCIONADOS CUMPLAN CON LAS CORRELATIVAS Y LOS METE EN UN ARREGLO DE SESSION
 * $_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas']
 * 
 */

$bandExisteTurnoActivo = true;
$anioActual = date('Y');
$sqlPromocionados = "SELECT a.idAlumno, a.idMateria, b.carrera, b.nombre as nombremateria, c.dni, 
                             c.apellido, c.nombre as nombrealumno, a.nota as nota
                      FROM alumno_cursa_materia a, materia b, alumno c
                      WHERE a.anioCursado='{$anioActual}' and (a.estado_final='Promociono' or a.estado_final='Aprobo') and 
                              a.idMateria=b.id and a.idAlumno=c.id
                      ORDER BY b.carrera, a.idMateria, c.apellido, c.nombre";
$resultadoPromocionados = mysqli_query($conex, $sqlPromocionados);
$_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'] = array();
while ($filaProm = mysqli_fetch_assoc($resultadoPromocionados)) {
    $sqlPromocionadosAprobados = "SELECT *
                               FROM alumno_rinde_materia a
                               WHERE a.idAlumno={$filaProm['idAlumno']} and 
                                     a.idMateria={$filaProm['idMateria']} and 
                                     a.estado_final='Aprobo'";
    //echo $sqlPromocionadosAprobados;die('acaaa');  
    $resultadoPromocionadosAprobados = mysqli_query($conex, $sqlPromocionadosAprobados);
    if (mysqli_num_rows($resultadoPromocionadosAprobados) == 0) {
        $arreglo = array();
        $varBool = verificaCorrelatividadesParaRendir($filaProm['idAlumno'], $filaProm['idMateria'], $conex);
        array_push($arreglo, $filaProm['idAlumno'], $filaProm['idMateria'], $filaProm['carrera'], $filaProm['nombremateria'], $filaProm['dni'], $filaProm['apellido'], $filaProm['nombrealumno'], $filaProm['nota'], $varBool);
        array_push($_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'], $arreglo);
    };
};
$arregloAprobadosPromocionados = $_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'];
//var_dump($arregloAprobadosPromocionados);die;
//persistirAprobacionesPromociones($arregloAprobadosPromocionados);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('componente_header.html'); ?>
   <?php include("componente_script_jquery.html"); ?>
  
  
    <style>
    .dropdown-item:hover{
          background-color: #CFC290;
        }        
     footer.nb-footer {
        background: #222;
        border-top: 4px solid #b78c33; }
    footer.nb-footer .about {
        margin: 0 auto;
        margin-top: 30px;
        max-width: 1170px;
        text-align: center; }
    footer.nb-footer .about p {
        font-size: 13px;
        color: #999;
        margin-top: 30px; }
    footer.nb-footer .about .social-media {
        margin-top: 15px; }
    footer.nb-footer .about .social-media ul li a {
        display: inline-block;
        width: 45px;
        height: 45px;
        line-height: 45px;
        border-radius: 50%;
        font-size: 16px;
        color: #b78c33;
        border: 1px solid rgba(255, 255, 255, 0.3); }
    footer.nb-footer .about .social-media ul li a:hover {
        background: #b78c33;
        color: #fff;
        border-color: #b78c33; }
    footer.nb-footer .footer-info-single {
        margin-top: 30px; }
    footer.nb-footer .footer-info-single .title {
        color: #aaa;
        text-transform: uppercase;
        font-size: 16px;
        border-left: 4px solid #b78c33;
        padding-left: 5px; }
    footer.nb-footer .footer-info-single ul li a {
        display: block;
        color: #aaa;
        padding: 2px 0; }
    footer.nb-footer .footer-info-single ul li a:hover {
        color: #b78c33; }
    footer.nb-footer .footer-info-single p {
        font-size: 13px;
        line-height: 20px;
        color: #aaa; }
    footer.nb-footer .copyright {
        margin-top: 15px;
        background: #111;
        padding: 7px 0;
        color: #999; }
    footer.nb-footer .copyright p {
        margin: 0;
        padding: 0; }
    .thead-green {
        background-color: rgb(0, 99, 71);
        color: white;
    }
    .disabledbutton {
          pointer-events: none;
          opacity: 0.5;
      }

    .input-form {
          border: 1px solid black;
          border-radius: 2px;
          height: 36px !important;
    }
    
    
   </style>    
</head>
<body>
 

 
 <!-- NAVBAR -->
 <header>
    <?php include("componente_navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item" aria-current="page">Promociones</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
  </article>

  <article class="container">
       <section>
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado">

            <?php
                                        echo "<table width=100% border=1 class='table table-bordered'>";
                                        echo "<tr><th>#</th><th>Carrera</th><th>Materia</th><th>Nombre</th><th>Nota</th>" . 
                                             "<th>Correlativas</th><th>Accion</th></tr>";
                                        $c = 0;     
                                        foreach ($_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'] as $valor) {
                                            $c++;
                                            echo "<tr><td><small><strong>$c</strong></small></td><td><small>$valor[2]</small></td><td><small>$valor[3]<strong>($valor[1])</strong></small></td>" .
                                                 "<td><small>$valor[5], $valor[6]<strong>($valor[0])</strong> - DNI:$valor[4]</small></td><td><small><strong>$valor[7]</strong></small></td>";
                                            //arma_arreglos_regulares_aprobadas($valor[0], $conex);
// su tira error de mysqli es porque hay un alumno que no tiene carrera vinculada
//                        if ($valor[0]==187) {
//                             var_dump($_SESSION['arregloMateriasAprobadasTodasCarreras']);die('hasta aca');
//                        };
                                            if ($valor[8]==TRUE)
                                                echo "<td><font color='blue'><b>Cumple</b></font></td>"
                                                . "<td align='center'>"
                                                . "&nbsp;&nbsp;<img src='../public/img/icons/ok_icon.png' width='30' title='Permitir'><br>"
                                                . "</td></tr>";
                                            else
                                                echo "<td><font color='red'><b>No Cumple</b></font></td>"
                                                . "<td align='center'>"
                                                . "&nbsp;&nbsp;<a href='#' onclick=\"PonerRegular(".$valor[0].",".$valor[1].",".$valor[7].")\">"
                                                . "<img src='../public/img/icons/error_icon1.png' width='30' title='Denegar'>"
                                                . "</a><br>"
                                                . "</td></tr>";

                                        }
                                        echo "</table>";
                                        ?>
                                        <br><br>
                                        <input type="button" class="btn btn-success" value="Aplicar Promociones" onclick="confirmarPersistir()" >
                                        <br><br>
                                       

            </div><!-- Cierra Row-->
            <div class="row" id="resultado_accion"></div><!-- Cierra Row-->
        </section>
  </article>

  <article class="container">
    <div></div>
  </article>
  

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>




<!-- JAVASCRIPT CUSTOM -->
<script>
function confirmarPersistir() {
        if (confirm("Esta seguro que Autoriza las Promociones/Aprobaciones validas.?") == true) {
            //location.href = 'own_persisteAprobacionesPromociones.php';
            $.get("./funciones/persisteAprobacionesPromociones.php",function(data){
                $("#resultado_accion").html(data);
            });
        }
    }


</script>

</body>
</html>