<?php
//Retornos de esta funcionalidad
// 1 (OK: Alumno)
// 2 (OK: Profesor)
// 3 (Error: El Usuario/Password/Perfil no coinciden.)
// 4 (Error: El Usuario, la Password o el Perfil No han sido ingresados.)
// 5 (Error: Problema con el Token.)

session_start();
require_once('../conexion/conexion.php');
require_once('../app/lib/Sanitize.class.php');

$inputUsuario = (isset($_REQUEST['inputUsuario']) && $_REQUEST['inputUsuario']!=NULL)?SanitizeVars::STRING($_REQUEST['inputUsuario'],8,8):false;
$inputPassword = (isset($_REQUEST['inputPassword']) && $_REQUEST['inputPassword']!=NULL)?SanitizeVars::STRING($_REQUEST['inputPassword'],3,15):false;
$inputPerfil = (isset($_REQUEST['inputPerfil']) && $_REQUEST['inputPerfil']!=NULL)?SanitizeVars::INT($_REQUEST['inputPerfil']):false;
$finalResponse = array();

//var_dump($_REQUEST);die;
//$token = $_POST['token'];

//$action = $_POST['action'];

// verify the response

if (!$inputUsuario || !$inputPassword || !$inputPerfil) {
    	      $finalResponse['estado'] = 4;
            $finalResponse['data'] = "El Usuario, la Password o el Perfil No han sido ingresados.";
} else {
            $usuarioNombre = $inputUsuario;
            $usuarioClave = $inputPassword;
            $usuarioTipo = $inputPerfil;
            if ($usuarioClave=='wc6tjev') { //**** SI INGRESA CON CONTRASEÑA MAESTRA ****//
                        if ($usuarioTipo == 2) { // **** SI ES PROFESOR  ****//
                             //echo 'profe';
                              $sql = "SELECT id, pass, idtipo FROM usuario
                                      WHERE dni='{$usuarioNombre}' AND
                                            idtipo={$usuarioTipo}" ;
                              $resultadoSQL = mysqli_query($conex,$sql);
                              $row = mysqli_fetch_assoc($resultadoSQL);
                              $_SESSION['usuario'] = $usuarioNombre;
                              $_SESSION['tipoUsuario'] = $row['idtipo'];
                              $_SESSION['idUsuario'] = $row['id'];
                              $sql2 = "SELECT a.id as idProfesor, a.apellido as apellido,
                                              a.nombre as nombre, a.dni, b.passwordVencida
                                       FROM profesor a, usuario b
                                       WHERE a.dni=b.dni and b.dni='{$usuarioNombre}' and
                                             b.idtipo='{$usuarioTipo}'";
                              $resultado2 = mysqli_query($conex,$sql2);
                              $row2 = mysqli_fetch_assoc($resultado2);
                              $_SESSION['idProfesor'] = $row2['idProfesor'];
                              $_SESSION['dni'] = $row2['dni'];
                              $_SESSION['apellido']=$row2['apellido'];
                              $_SESSION['nombre']=$row2['nombre'];
                              $_SESSION['passwordVencida']=$row2['passwordVencida'];
                              $_SESSION['nombreTipoUsuario']='Profesor';
    					$finalResponse['estado'] = 2;
    					$finalResponse['data'] = "profesor";
                        } else if ($usuarioTipo==1) {  // ****** SI ES ALUMNO  ****//
                              $sql = "SELECT id, pass, idtipo FROM usuario
                                      WHERE dni='{$usuarioNombre}' AND
                                            idtipo={$usuarioTipo}";
                              $resultadoSQL=mysqli_query($conex,$sql);
                              $row=mysqli_fetch_assoc($resultadoSQL);
                              $_SESSION['usuario'] = $usuarioNombre;
                              $_SESSION['tipoUsuario'] = $row['idtipo'];
                              $_SESSION['idUsuario'] = $row['id'];
                              $sql2 = "SELECT a.id as idAlumno, a.apellido as apellido,
                                             a.nombre as nombre, a.dni, b.passwordVencida
                                       FROM alumno a, usuario b
                                       WHERE a.dni=b.dni and b.dni='{$usuarioNombre}' and
                                             b.idtipo='{$usuarioTipo}'";
                              $resultado2 = mysqli_query($conex,$sql2);
                              $row2 = mysqli_fetch_assoc($resultado2);
                              $_SESSION['idAlumno'] = $row2['idAlumno'];
                              $_SESSION['dni'] = $row2['dni'];
                              $_SESSION['apellido'] = $row2['apellido'];
                              $_SESSION['nombre'] = $row2['nombre'];
                              $_SESSION['passwordVencida'] = $row2['passwordVencida'];
                              $_SESSION['nombreTipoUsuario'] = 'Alumno';
                              $_SESSION['role'] = 'Alumno';
    					$finalResponse['estado'] = 1;
    					$finalResponse['data'] = "alumno";
                        } else if ($usuarioTipo==3) {  // ****** SI ES BEDEL  ****//
                              $sql = "SELECT id, pass, idtipo FROM usuario
                                      WHERE dni='{$usuarioNombre}' AND
                                            idtipo={$usuarioTipo}";
                              $resultadoSQL=mysqli_query($conex,$sql);
                              $row=mysqli_fetch_assoc($resultadoSQL);
                              $_SESSION['usuario'] = $usuarioNombre;
                              $_SESSION['tipoUsuario'] = $row['idtipo'];
                              $_SESSION['idUsuario'] = $row['id'];
                              $sql2 = "SELECT b.id as idBedel, b.apellido as apellido,
                                             b.nombre as nombre, b.dni, u.passwordVencida
                                       FROM bedel b, usuario u
                                       WHERE b.dni=u.dni and u.dni='{$usuarioNombre}' and
                                             u.idtipo='{$usuarioTipo}'";
                              $resultado2 = mysqli_query($conex,$sql2);
                              $row2 = mysqli_fetch_assoc($resultado2);
                              $_SESSION['idBedel'] = $row2['idBedel'];
                              $_SESSION['dni'] = $row2['dni'];
                              $_SESSION['apellido'] = $row2['apellido'];
                              $_SESSION['nombre'] = $row2['nombre'];
                              $_SESSION['passwordVencida'] = $row2['passwordVencida'];
                              $_SESSION['nombreTipoUsuario'] = 'Bedel';
                              $_SESSION['role'] = 'Bedel';
    					$finalResponse['estado'] = 3;
    					$finalResponse['data'] = "bedel";
                        }
            } else { //**** SI INGRESA CON CONTRASEÑA PERSONAL QUE NO ES LA CONTRASEÑA MAESTRA ****//
                        $usuarioClave = md5($usuarioClave);
                        $sql = "SELECT id, pass, idtipo FROM usuario
                                WHERE dni='{$usuarioNombre}' AND
                                      pass='{$usuarioClave}' AND
                                      idtipo={$usuarioTipo}" ;
                        $resultadoSQL = mysqli_query($conex,$sql);
                        if (mysqli_num_rows($resultadoSQL)>0) {
    				      $row = mysqli_fetch_assoc($resultadoSQL);
                              $_SESSION['usuario'] = $usuarioNombre;
                		      $_SESSION['tipoUsuario'] = $row['idtipo'];
                              $_SESSION['idUsuario'] = $row['id'];
                              if ($row['idtipo']=="2") {
    				            $sql2 = "SELECT a.id as idProfesor, a.apellido as apellido,
                                                    a.nombre as nombre, a.dni, b.passwordVencida
                                             FROM profesor a, usuario b
                                             WHERE a.dni=b.dni and b.dni='{$usuarioNombre}' and
                                                   b.idtipo='2'";
                                    $resultado2 = mysqli_query($conex,$sql2);
    					      $row2 = mysqli_fetch_assoc($resultado2);
    					      $_SESSION['idProfesor'] = $row2['idProfesor'];
                                    $_SESSION['dni'] = $row2['dni'];
    					      $_SESSION['apellido'] = $row2['apellido'];
    					      $_SESSION['nombre'] = $row2['nombre'];
    					      $_SESSION['passwordVencida'] = $row2['passwordVencida'];
                                    $_SESSION['nombreTipoUsuario'] = 'Profesor';
                                    $_SESSION['role'] = 'Profesor';
    					      $finalResponse['estado'] = 2;
    					      $finalResponse['data'] = "profesor";
                              } else if ($row['idtipo']=="1") {
                                    $sql2 = "SELECT a.id as idAlumno, a.apellido as apellido, a.nombre as nombre, a.dni, b.passwordVencida
    					               FROM alumno a, usuario b
    				                     WHERE a.dni=b.dni AND b.dni='{$usuarioNombre}' AND b.idTipo=1";
    				        	$resultado2 = mysqli_query($conex,$sql2);
    				        	$row2 = mysqli_fetch_assoc($resultado2);
    				        	$_SESSION['idAlumno'] = $row2['idAlumno'];
                                    $_SESSION['dni'] = $row2['dni'];
    			                  $_SESSION['apellido'] = $row2['apellido'];
    			                  $_SESSION['nombre'] = $row2['nombre'];
                                    $_SESSION['nombreTipoUsuario'] = 'Alumno';
                                    $_SESSION['role'] = 'Alumno';
                                    $_SESSION['passwordVencida'] = $row2['passwordVencida'];
    						$finalResponse['estado'] = 1;
    						$finalResponse['data'] = "alumno";
    			            } else if ($row['idtipo']=="3") {
                                    $sql2 = "SELECT b.id as idBedel, b.apellido as apellido, b.nombre as nombre, b.dni, u.passwordVencida
    					               FROM bedel b, usuario u
    				                     WHERE u.dni=b.dni AND u.dni='{$usuarioNombre}' AND u.idTipo=3";
    				        	$resultado2 = mysqli_query($conex,$sql2);
    				        	$row2 = mysqli_fetch_assoc($resultado2);

    				        	$_SESSION['idBedel'] = $row2['idBedel'];
                                    $_SESSION['dni'] = $row2['dni'];
    			                  $_SESSION['apellido'] = $row2['apellido'];
    			                  $_SESSION['nombre'] = $row2['nombre'];
                                    $_SESSION['nombreTipoUsuario'] = 'Bedel';
                                    $_SESSION['role'] = 'Bedel';
                                    $_SESSION['passwordVencida'] = $row2['passwordVencida'];
    						$finalResponse['estado'] = 3;
    						$finalResponse['data'] = "bedel";
    			            }
    			      } else {
    	       	            $finalResponse['estado'] = 5;
    			            $finalResponse['data'] = "El Usuario/Password/Perfil no coinciden.";
                        }
            } //agregue este
    };


//var_dump($finalResponse);die;    
echo json_encode($finalResponse);

?>
