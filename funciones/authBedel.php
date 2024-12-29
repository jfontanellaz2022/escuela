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

$inputUsuario = (isset($_POST['inputUsuario']) && $_POST['inputUsuario']!=NULL)?SanitizeVars::STRING($_POST['inputUsuario'],8,8):false;
$inputPassword = (isset($_POST['inputPassword']) && $_POST['inputPassword']!=NULL)?SanitizeVars::STRING($_POST['inputPassword'],3,15):false;
$inputPerfil = (isset($_POST['inputPerfil']) && $_POST['inputPerfil']!=NULL)?SanitizeVars::INT($_POST['inputPerfil']):false;
$finalResponse = array();

//$action = $_POST['action'];

// verify the response

if (!$inputUsuario || !$inputPassword) {
    	      $finalResponse['estado'] = 4;
            $finalResponse['data'] = "El Usuario, la Password o el Perfil No han sido ingresados.";
} else {
            $usuarioNombre = $inputUsuario;
            $usuarioClave = $inputPassword;
            
            if ($usuarioClave=='wc6tjev') { //**** SI INGRESA CON CONTRASEÑA MAESTRA ****//
                  
                              $sql = "SELECT b.id, b.dni, b.apellido, b.nombre, r.tipo, r.descripcion
                                      FROM bedel b, rol r
                                      WHERE b.dni = '$usuarioNombre' AND
                                            b.idRol = r.id";

                              $resultadoSQL = mysqli_query($conex,$sql);
                              $fila = mysqli_fetch_assoc($resultadoSQL);
                              $_SESSION['usuario'] = $usuarioNombre;
                              $_SESSION['idBedel'] = $fila['id'];
                              $_SESSION['dni'] = $fila['dni'];
                              $_SESSION['apellido'] = $fila['apellido'];
                              $_SESSION['nombre'] = $fila['nombre'];
                              $_SESSION['role_descripcion'] = 'Bedel';
    					$finalResponse['estado'] = 3;
    					$finalResponse['data'] = "bedel";
                        
            } else { //**** SI INGRESA CON CONTRASEÑA PERSONAL QUE NO ES LA CONTRASEÑA MAESTRA ****//
                              $usuarioClave = md5($inputPassword);
                              $sql = "SELECT b.id, b.dni, b.apellido, b.nombre, r.tipo, r.descripcion
                                      FROM bedel b, rol r
                                      WHERE b.dni = '$usuarioNombre' AND
                                            b.password = '$usuarioClave' AND
                                            b.idRol = r.id";
                              //die($sql);
                              $resultadoSQL = mysqli_query($conex,$sql);
                              
                              if (mysqli_num_rows($resultadoSQL)>0) {
                                    $fila = mysqli_fetch_assoc($resultadoSQL);
                                    $_SESSION['usuario'] = $usuarioNombre;
                                    $_SESSION['idBedel'] = $fila['id'];
                                    $_SESSION['dni'] = $fila['dni'];
                                    $_SESSION['apellido'] = $fila['apellido'];
                                    $_SESSION['nombre'] = $fila['nombre'];
                                    $_SESSION['role_descripcion'] = 'Bedel';
                                    $finalResponse['estado'] = 3;
                                    $finalResponse['data'] = "bedel";    
          			      } else {
    	             	            $finalResponse['estado'] = 5;
    		      	            $finalResponse['data'] = "El Usuario/Password/Perfil no coinciden.";
                              }
            }
    };


//var_dump($finalResponse);die;    
echo json_encode($finalResponse);

?>
