<?php
   set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
   require_once 'CalendarioAcademico.php';
   include_once "seguridadNivel2.php";
   
   $calendario = new CalendarioAcademico();
  
   $arr_datosturno = [];
   $cantidad_llamados = $inscripcion_activa = $inscripcion_asociada = 0;

   if (empty($arr_datosturno)) {
      $arr_datosturno = $calendario->getEventoActivoByCodigo(1001);
      $cantidad_llamados = 2;
   };
   if (empty($arr_datosturno)) {
      $arr_datosturno = $calendario->getEventoActivoByCodigo(1002);
      $cantidad_llamados = 1;
   };
   if (empty($arr_datosturno)) {
      $arr_datosturno = $calendario->getEventoActivoByCodigo(1003);
      $cantidad_llamados = 2;
   };
   if (empty($arr_datosturno)) {
      $arr_datosturno = $calendario->getEventoActivoByCodigo(1004);
      $cantidad_llamados = 1;
   };
   
   $turno_id = isset($arr_datosturno[0]["id"])?$arr_datosturno[0]["id"]:0;
   $arr_ultima_inscripcion = $calendario->getLastInscripcionExamen();
   
   //var_dump($arr_ultima_inscripcion);exit;
   if ($arr_ultima_inscripcion[0]["codigo"]==1005) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = $inscripcion_activa5;
   } else if ($arr_ultima_inscripcion[0]["codigo"]==1006) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = $inscripcion_activa;
   } else if ($arr_ultima_inscripcion[0]["codigo"]==1007) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = $inscripcion_activa;
   } else if ($arr_ultima_inscripcion[0]["codigo"]==1008) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = $inscripcion_activa;
   } else if ($arr_ultima_inscripcion[0]["codigo"]==1009) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = 1005; // sacar el id
   } else if ($arr_ultima_inscripcion[0]["codigo"]==1010) {
      $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
      $inscripcion_asociada = 1007; // sacar el id
   };

   //var_dump($inscripcion_activa);exit;
   //$inscripcion_activa = $arr_datosturno['inscripcion_activa'];
   //$inscripcion_asociada = $arr_datosturno['inscripcion_asociada'];
   //$cantidad_llamados = $arr_datosturno['cantidad_llamados'];

   $id_pagina = 'finales';

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
    <?php include("navbar.php");?>
  </header>

  <article>
    <div id="breadcrumb">
      
      <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Carreras</li>
                </ol>
      </nav>

      <h1><i>&nbsp;Carreras</i></h1>
      <h3>&nbsp;Gestionar Examenes Finales de Alumnos</h3>

    </div>
  </article>

  <article class="container-fluid">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
           <div class="row" id="resultado">

                  <div class="col-md-4">
                        <div class="card" style="width: 18rem;">
                                  <img src="../public/img/logo_n.jpg" class="card-img-top">
                                  <div class="card-body">
                                      <h5 class="card-title"><img src="../public/img/icons/add2_icon.png" width="23">&nbsp;Nueva vinculación a Carrera</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"></h6>
                                      <p class="card-text"></p>
                                      <button class="btn btn-primary btn-block" onclick="vincularCarrera(`+idProfesor+`)">Vincularme</button>
                                  </div>
                        </div>
                  </div>


           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php
      include_once('../app/views/footer.html');
  ?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>


<!-- JAVASCRIPT CUSTOM -->
<script>
let calendario_id = <?=$inscripcion_asociada;?>;
let llamado1_habilitado = "";
let llamado2_habilitado = "";
let carrera_nombre = '';
let carrera_id = '';
var profesor_id = <?=$_SESSION['idProfesor'];?>;
var materia_nombre = '';
var materia_id = '';
var opcion = 'regularidades';
let llamado_numero = '';
function expired() {
  location.href = "./logout.php";
}



$(function () {
    /*$.get("./html/modalEliminar.html",function(data){
      $("#modalEliminar").html(data);
    })*/
    cargarCarreras(profesor_id);
});


//setTimeout(expired, 60000*20);


//************************************************** 
// NOS PERMITE BUSCAR UNA ENTIDAD POR ID       
//************************************************** 
function entidadObtenerPorId(entidad, entidad_id){
    let url = "funciones/"+entidad+"ObtenerPorId.php";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"id":entidad_id}, function (data) {
          resultado = data;
    },"json")
    return resultado;
};

//************************************************** 
// NOS PERMITE BUSCAR UNA ENTIDAD POR CODIGO      
//************************************************** 
function entidadObtenerPorCodigo(entidad, entidad_codigo){
    let url = "funciones/"+entidad+"ObtenerPorCodigo.php";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"codigo":entidad_codigo}, function (data) {
          resultado = data;
    },"json")
    return resultado;
};


//************************************************** 
// NOS PERMITE BUSCAR UNA ENTIDAD POR CODIGO      
//************************************************** 
function entidadObtenerAlumnosPorMateria(calendario_id,materia_id,llamado){
    let url = "funciones/AlumnoRindeMateriaObtenerAlumnos.php";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"calendario_id":calendario_id,"materia_id":materia_id,"llamado":llamado}, function (data) {
          resultado = data;
    },"json")
    return resultado;
};


//**********************************************************************************
// FUNCION QUE OBTIENE LAS CARRERAS EN LAS QUE DICTA CLASES UN PROFESOR ESPECIFICO
//**********************************************************************************
function cargarCarreras(idProfesor) {
  //Activo del Menu la opcion Alumnos
  let titulo;
  let subtitulo;
  let bread;
  let resul;
 
  $(".nav-item-alumnos").removeClass("active");
  $(".nav-item-regularidades").removeClass("active");
  $(".nav-item-examenes").addClass("active");
  subtitulo = 'Gestionar Examenes de Alumnos';
 
  bread = `<nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Carreras</li>
                </ol>
              </nav>`;
  titulo = `<h1><i>&nbsp;Carreras</i></h1>
                <h3>&nbsp;`+subtitulo+`</h3>`;
  //Remuevo la class que me deshabilita
  $("#resultado").removeClass("disabledbutton");
  let parametros = {"action":"Listar",'profesor_id':idProfesor};
  $.post( "./funciones/carreraObtenerPorIdProfesor.php", parametros, function( data ) {
                  $("#breadcrumb").html(bread);
                  $("#titulo").html(titulo);
                  $("#resultado").html("");
                  data.datos.forEach(carrera => {
                        if (carrera.habilitada=='Si') {
                           linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarLlamados(`+carrera.carrera_id+`,`+idProfesor+`)" >Ingresar</a>`;
                           resul = `<div class="col-md-4">
                                             <div class="card" style="width: 18rem;">
                                                         <img src="../public/img/`+carrera.imagen+`" class="card-img-top">
                                                         <div class="card-body">
                                                         <h5 class="card-title">`+carrera.descripcion+`</h5>
                                                               <h6 class="card-subtitle mb-2 text-muted"></h6>
                                                         <p class="card-text"></p>
                                                         `+linkClick+`
                                                         </div>
                                                   </div>
                                             </div>`;
                     } else {
                           resul = `<div class="col-md-4">
                                             <div class="card" style="width: 15rem;">
                                                         <img src="../public/img/no.png" class="card-img-top">
                                                         <div class="card-body">
                                                         <h5 class="card-title">Sin Carrera Asignada</h5>
                                                               <h6 class="card-subtitle mb-2 text-muted"></h6>
                                                         <p class="card-text text-center"></p>
                                                         </div>
                                                   </div>
                                             </div>`;
                          
                     } 
                     $("#resultado").append(resul);
                  });
  },'json');
}


//**********************************************************************
//CARGA LOS LLAMADOS DE UN TURNO PROFESOR SEGUN LA CARRERA SELECCIONADA
//**********************************************************************
function cargarLlamados(carrera_id,profesor_id) {
   let parametros = {"action":"Listar",'carrera':carrera_id, 'profesor':profesor_id};
   let cantidad_llamados;
   let entidad_carrera = entidadObtenerPorId("carrera", carrera_id)
   
   let carrera_nombre = entidad_carrera.datos.descripcion;
   let titulo = '';
   let llamados;
   
   
   let entidad_calendario_llamado_1 = entidadObtenerPorCodigo("calendario", 1020);
   let entidad_calendario_llamado_2 = entidadObtenerPorCodigo("calendario", 1021);

   if (entidad_calendario_llamado_1.datos.length==0) {
      llamado1_habilitado = "disabledbutton";
   };
   if (entidad_calendario_llamado_2.datos.length==0) {
      llamado2_habilitado = "disabledbutton";
   };
   
   
   //Remuevo la class que me deshabilita
   $("#resultado").removeClass("disabledbutton");
   titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Llamados</strong> (Llamados del Turno)</h3>';
   
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                   <li class="breadcrumb-item">`+carrera_nombre+`</li>
                 </ol>
               </nav>`;
   $("#breadcrumb").html(bread);
   $("#titulo").html(titulo);
   llamados=`<div class="card `+llamado1_habilitado+`" style="width:400px">
            <div class="card-body">
            <h4 class="card-title">1er Llamado</h4>
            <p class="card-text"></p>
            <button type="button" name="button" onclick="cargarMateriaPorCarreraFinales(`+carrera_id+`,'`+carrera_nombre+`',`+profesor_id+`,1)" class="btn btn-info">Seleccionar</button>
            </div>
            </div> 
            &nbsp;&nbsp;
            <div class="card `+llamado2_habilitado+`" style="width:400px">
            <div class="card-body">
               <h4 class="card-title">2do Llamado</h4>
               <p class="card-text"></p>
               <button type="button" name="button" onclick="cargarMateriaPorCarreraFinales(`+carrera_id+`,'`+carrera_nombre+`',`+profesor_id+`,2)" class="btn btn-info">Seleccionar</button>
            </div>
            </div>`;
      $("#resultado").html(llamados);      
};


//****************************************************************
//CARGA LAS MATERIAS DEL PROFESOR SEGUN LA CARRERA SELECCIONADA
//***************************************************************

function cargarMateriaPorCarreraFinales(carrera,carrera_nombre,profesor,llamado) {
    let turno_activo = <?=$inscripcion_asociada?>;

    //alert(turno_activo);

    let habilitar_materia;
    let parametros = {"action":"Listar",'carrera':carrera};
    
    let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Notas Examenes)</h3>';
    let bread = `<nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('`+opcion+`',`+profesor+`)">Carreras</a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="cargarLlamados('`+carrera+`',`+profesor+`)">`+carrera_nombre+`</a></li>
                    <li class="breadcrumb-item">Llamado `+llamado+`</li>
                  </ol>
                </nav>`;
    $("#breadcrumb").html(bread);
    
    $.post( "./funciones/materiaObtenerPorIdCarrera.php", parametros, function( data ) {
       $("#resultado").html("");
       let tabla_comienzo = `
                    <table class="table table-striped">
                       <thead class="thead-red">
                          <tr><th>MATERIA</th><th>AÑO</th><th>FORMATO</th><th>CURSADO</th><th></th></tr>
                       </thead>
                       <tbody>
                     `;
       let filas = ``;
       let tabla_final  = `   </tbody>
                          </table>`;
       if (data.codigo==100) {
               data.datos.forEach(materia => {
                    disabled = "disabledbutton";
                    cantidad_inscriptos = "";
                    entidad = entidadObtenerAlumnosPorMateria(turno_activo,materia.materia_id,llamado)
                    if (entidad.datos.length!=0) {
                        disabled = "";
                        cantidad_inscriptos =  `<button type="button" class="btn btn-primary" onclick="cargarAlumnosExamen(`+carrera+`,'`+carrera_nombre+`',`+materia.materia_id+`,'`+materia.materia_nombre+`',`+profesor+`,`+llamado+`)">
                                                Inscriptos <span class="badge badge-light">`+entidad.datos.length+`</span>
                                                </button> `
                    }
                    filas += `
                               <tr class="`+disabled+`"><td>`+materia.materia_nombre+` <strong>(`+materia.materia_id+`)</strong></td><td>`+materia.materia_anio+`</td>`+
                               `<td>`+materia.descripcion_formato+`</td>`+
                               `<td>`+materia.descripcion_cursado+`</td>`+
                               `<td>`+cantidad_inscriptos+`</td></tr>
                               `;
                 });
       } else {
              filas = `
                       <tr><td>No Existen Materias Asociadas.</td></tr>
                      `;
              }
        $("#resultado").html(tabla_comienzo+filas+tabla_final);
     },"json"); 
 
 }

 
 
//**********************************************************************************************************
//OBTIENE EL LISTADO DE LOS ALUMNOS DE UNA MATERIA DADA DEL PROFESOR EN FUNCION DE LA OPCION SELECCIONADA
//**********************************************************************************************************

function cargarAlumnosExamen(idCarrera,carrera_nombre,idMateria,materia_nombre,idProfesor, llamado) {
   cargarAlumnosPorMateriaExamenes(idCarrera,carrera_nombre,idMateria,materia_nombre,idProfesor,llamado);
};

//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA FINALES PARA UNA MATERIA
//************************************************************************
function cargarAlumnosPorMateriaExamenes(carrera_id, carrera_nombre, materia_id,materia_nombre,profesor_id,llamado) {
   let datos_evento;
   let evento_codigo;
   let evento_habilitado = 'No';
   let llamado_nro = llamado;
   let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong> (Ingresar Notas de Ex&aacute;men Final) - Llamado '+llamado_nro+'</h3>';
   let boton_agregar_alumno = '';  
   let accion_editar = '<a href="#" class="disabledbutton" title="Editar Datos del Final"><img src="../public/img/icons/edit_icon.png" width="23"></a>';


   if (llamado==1) {
      evento_habilitado = llamado1_habilitado
   };

   if (llamado==2) {
      evento_habilitado = llamado2_habilitado
   };

   let parametros = {"materia_id":materia_id, "calendario_id":calendario_id, "llamado":llamado_nro};

   $.post( "./funciones/AlumnoRindeMateriaObtenerAlumnos.php", parametros, function( data ) {
      let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('examenes',`+profesor_id+`)">Carreras</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarLlamados('`+carrera_id+`',`+profesor_id+`,'examenes')">`+carrera_nombre+`</a></li>
                      <li class="breadcrumb-item">`+materia_nombre+` (Llamado `+llamado_nro+`)</li>
                    </ol>
                  </nav>`;
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      $("#resultado").html("");
      // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
      let tabla_comienzo = `
                   <table class="table">
                      <thead class="thead-green">
                         <tr>
                            <th>APELLIDO Y NOMBRE</th><th>DNI</th>
                            <th>CURSADO</th>
                            <th>NOTA</th><th>ESTADO</th>
                         </tr>
                      </thead>
                      <tbody>
                    `;
      // FILAS DE RESULTADO DE LA TABLA
      let filas = ``;
      //let nofilas = ``;
      // FIN DE LA TABLA
      let tabla_final  = `   </tbody>
                         </table>`;
      
      //boton_agregar_notas = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Notaaa</button>';
      
      if (data.codigo==100) {
                  data.datos.forEach(alumno => {
                        let nota_examen_final = 0;
                        if (alumno.nota=='-1.00') {
                           nota_examen_final = '*Sin Nota*';
                        } else {
                           nota_examen_final = alumno.nota;
                        };
                      
                        let color = '';
                        if (alumno.estado_final=='Desaprobo') {
                           color = 'badge-danger';
                        } else if (alumno.estado_final=='Aprobo') {
                           color = 'badge-success';
                        } else if (alumno.estado_final=='Ausente') {
                           color = 'badge-warning';
                        };
                        filas += `
                                  <tr>
                                      <td><a href="mailto:`+alumno.email+`">`+alumno.apellido+', '+alumno.nombre+`</a> <strong>(`+alumno.id+`)</strong></td>`+
                                      `<td>`+alumno.dni+`</td>`+
                                      `<td><span class='badge badge-primary'>`+alumno.condicion+`</span></td>`+
                                      `<td><span class='badge badge-info'>`+nota_examen_final+`</span></td>`+
                                      `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                  `</tr>
                                  `;
                  });
         };
         
         let tabla_agregar_alumno = `  
                                    <div class="col-xs-12 col-sm-12 col-md-12" style="background-color: #E9D5B4;border-radius: 10px;">
                                       <div class="row" style="padding: 10px;"> 
                                             <div class="col-xs-12 col-sm-4 col-md-4 col-md-4">
                                                <select id="inputFinalesAltaAlumno" class="form-control">
                                                      <option value="">-- Alumno --</option>
												            </select>
                                             </div>

                                             <div class="col-xs-12 col-sm-3 col-md-3">
                                                <select id="inputFinalesAltaNota" class="form-control">
                                                   <option value="">-- Nota --</option>
                                                   <option value="-1">Sin Nota</option>
                                                   <option value="1">1 (Uno)</option>
                                                   <option value="2">2 (Dos)</option>
                                                   <option value="3">3 (Tres)</option>
                                                   <option value="4">4 (Cuatro)</option>
                                                   <option value="5">5 (Cinco)</option>
                                                   <option value="6">6 (Seis)</option>
                                                   <option value="7">7 (Siete)</option>
                                                   <option value="8">8 (Ocho)</option>
                                                   <option value="9">9 (Nueve)</option>
                                                   <option value="10">10 (Diez)</option>
                                                </select>
                                             </div>
												
												         <div class="col-xs-12 col-sm-3 col-md-3">
                                                <select id="inputFinalesAltaEstadoFinal" class="form-control">
                                                   <option value="">-- Estado --</option>
                                                   <option value="Ausente">Ausente</option>
                                                   <option value="Aprobo">Aprobo</option>
                                                   <option value="Desaprobo">NO Aprobo</option>
                                                </select>
                                             </div>
												
                                             <div class="col-xs-12 col-sm-2 col-md-2">
                                                <button type="button" class="btn btn-success btn-sm" onclick="finalesAgregarNotaAlumno(`+materia_id+`,'`+materia_nombre+`',`+carrera_id+`,'`+carrera_nombre+`',`+calendario_id+`,`+llamado_nro+`,`+profesor_id+`)">
                                                   Guardar
                                                </button>
                                             </div>
                                             
                                          </div>
                                          <div class="row">
                                             <div id="resultado_carga" class="col-xs-12 col-md-12 d-none"> 
                                          </div>
                                       </div>
                                    </div>
                                    `;      
                                                     
         $("#resultado").html(tabla_agregar_alumno+'<br>');    
         $("#resultado").append(tabla_comienzo+filas+tabla_final);
                 
         //CARGA EL SELECT2 CON LOS ALUMNOS DE LA MATERIA
         $.post( "./funciones/AlumnoRindeMateriaObtenerAlumnos.php",parametros, function( data_alumnos ) {
            if (data_alumnos.codigo==100) {
                data_alumnos.datos.forEach(alumno => {
                      $("#inputFinalesAltaAlumno").append($('<option/>', {
                            text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                            value: alumno.id,
                      }));
                });
            };     
         },"json");   

         $('#inputFinalesAltaAlumno').select2();
         //CARGA EL SELECT2 CON LOS DATOS DE NOTA Y ESTADO FINAL
         $('#inputFinalesAltaNota').select2();
         $('#inputFinalesAltaEstadoFinal').select2();
         $("#resultado").addClass(evento_habilitado);
      }, "json");     
}
 
//****************************************************************************
//CARGA LA NOTA DE UN EXAMEN A UN ALUMNO EN UNA MATERIA ESPECIFICADA
//****************************************************************************
function finalesAgregarNotaAlumno(idMateria,materia_nombre,idCarrera,carrera_nombre,idCalendario,llamado,idProfesor) {
   let alumno = $('#inputFinalesAltaAlumno').val();
   let nota = $('#inputFinalesAltaNota').val()
   let estado_final = $('#inputFinalesAltaEstadoFinal').val()
   let parametros = {"materia":idMateria, "alumno":alumno, "calendario":idCalendario, "llamado":llamado, "nota":nota, "estadoFinal": estado_final};
   $.post( "./funciones/setNotasExamenFinal.php", parametros, function( data ) {
            let obj = JSON.parse(data);
            $("#resultado_carga").removeClass("d-none");
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                                           <b><img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+obj.data+`</i></span>
                                            </div>`);
                  cargarAlumnosPorMateriaExamenes(idCarrera, carrera_nombre, idMateria,materia_nombre,idProfesor,llamado)
            } else {
                $("#resultado_carga").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+obj.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div>`);
            }
    });
}


</script>
<script src="./js/funciones.js"></script>
</body>
</html>
