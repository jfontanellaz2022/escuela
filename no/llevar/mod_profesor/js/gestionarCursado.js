//***********************************************************************************************************************************/
//***********************************************************************************************************************************/
//*************************************************** GESTIONAR CARRERAS ************************************************************/
//***********************************************************************************************************************************/
//***********************************************************************************************************************************/

let arr_carreras = getCarreras();
let cursado_activo = getCursadoActivo();

   let carrera_nombre = '';
   let carrera_id = '';
   
   var materia_nombre = '';
   var materia_id = '';
   //var opcion = 'cursado';
   let llamado_numero = '';

   function expired() {
      location.href = "./logout.php";
   }

$(function () {
    cargarCarreras(profesor_id);
});


//***********************************************
// RETORNA EL NOMBRE DE UNA CARRERA 
//***********************************************
function getCarreras() {
   let carreras
   $.ajax({
      url:"../API/findAllCarreras.php",
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(resultado){
         carreras = resultado.datos;
      }
});
//console.info(arr_carreras);
return carreras;
}


function getCarreraPorId(carrera_id) {
   let carrera;
   //getCarreras();
   
   arr_carreras.forEach(function(currentValue, index, arr){
      if (currentValue['id']==carrera_id) {
          carrera = currentValue;
      }; 
      
   })
   return carrera;
}

//***********************************************************
// RETORNA EL EVENTO ARMADO DE LISTAS (CURSADO) CON SUS DATOS 
//***********************************************************
function getCursadoActivo() {
   let datos_cursado;
   $.ajax({
      url:"../API/findCursadoActivo.php",
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_cursado = datos;
      }
});
  return datos_cursado;
} 


//**********************************************************************************
// CARGA LAS CARRERAS QUE TIENE ASIGNADA EL PROFESOR EN CUESTIÓN
//**********************************************************************************

function vincularCarrera(idProfesor) {
  let bread = `<nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item" aria-current="page">
                        <a href="#" onclick="cargarCarreras(`+idProfesor+`)">Carreras</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Vincularme a una Carrera</li>
                </ol>
              </nav>`;
  let titulo = `<h1><i>&nbsp;Carreras</i></h1>
                <h3>&nbsp;Vincularme a Carrera</h3>`;

  $("#breadcrumb").html(bread);
  $("#titulo").html(titulo);                

  $.get("./html/cargarCarrera.html",function(datos){
    $("#resultado").html(datos);
        $.post("../API/findAllCarreras.php", function(datos_carreras) {
          if (datos_carreras.codigo==200) {
              let obj = datos_carreras.datos; 
              obj.forEach(carrera => {
                    $("#inputAltaCarrera").append($('<option/>', {
                          text: carrera.descripcion+' ('+carrera.id+')',
                          value: carrera.id,
                    }));
              });
          }
        },"json");
        $("#inputAltaCarreraProfesor").val(idProfesor);
        $("#inputAltaCarrera").select2();
  });
}


function cargarCarreras(idProfesor) {
  //Activo del Menu la opcion Alumnos
  let titulo;
  let subtitulo;
  let bread;
 
  $(".nav-item-alumnos").addClass("active");
  $(".nav-item-regularidades").removeClass("active");
  $(".nav-item-examenes").removeClass("active");
  //$("#controles").html('');
  subtitulo = 'Seleccion Carrera para la gestion de Lista de Materias/Alumnos';
 
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
  let parametros = {'profesor':idProfesor};
  $.post( "../API/findAllCarrerasPorProfesor.php", parametros, function( data ) {
                  let obj = data;
                  $("#breadcrumb").html(bread);
                  $("#titulo").html(titulo);
                  $("#resultado").html("");
                        let agrega_carrera = `<div class="col-md-4">
                                    <div class="card" style="width: 18rem;">
                                          <img src="../public/img/logo_n.jpg" class="card-img-top">
                                          <div class="card-body">
                                                <h5 class="card-title"><img src="../public/img/icons/add2_icon.png" width="23">&nbsp;Nueva vinculación a Carrera</h5>
                                                      <h6 class="card-subtitle mb-2 text-muted"></h6>
                                                <p class="card-text"></p>
                                                <button class="btn btn-primary btn-block" onclick="vincularCarrera(`+idProfesor+`)">Vincularme</button>
                                          </div>
                                          </div>
                                    </div>`;
                        $("#resultado").html(agrega_carrera);
                        //**** */
                  obj.datos.forEach(carrera => {
                              let linkClick = '';
                              linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarMaterias(`+carrera.id+`,`+idProfesor+`)" >Ingresar</a>`+
                                          ` &nbsp;&nbsp;<a href="#" class="btn btn-danger btn-block" onclick="desvincularCarrera(`+carrera.id+`,`+idProfesor+`)">Desvincularme</a>`;
                              let resul = `<div class="col-md-4">
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
                              $("#resultado").append(resul);
                  });
  },'json');
}


//*************************************************************
//DESVINCULA EL PROFESOR DE UNA CARRERA ESPECIFICADA ESPECIFICA
//*************************************************************
function desvincularCarrera(idCarrera,idProfesor) {
  let parametros = {"carrera":idCarrera, "profesor":idProfesor};
  if (confirm("Desea desvincularse de la Carrera?")) {
      $.post("../API/removeProfesorCarrera.php",parametros,function (response) {
            $("#controles").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.class+`">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atenci&oacute;n:</strong> `+response.datos+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
            cargarCarreras(idProfesor)
      },"json");
  };

}


//*************************************************************
//VINCULA EL PROFESOR A UNA CARRERA ESPECIFICADA ESPECIFICA
//*************************************************************
function cursadoAgregarCarrera() {
  let idCarrera = $("#inputAltaCarrera").val();
  let idProfesor = profesor_id;
  let parametros;

  if (idCarrera && idProfesor) {
     parametros = {"profesor":idProfesor,"carrera":idCarrera};
     $.post("../API/insertProfesorCarrera.php",parametros, function(response){
            $("#controles").html(`
                                                   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.class+`">
                                                      <span style="color: #000000;">
                                                      <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                         &nbsp;<strong>Atención:</strong> `+response.mensaje+`
                                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                               <span aria-hidden="true">&times;</span>
                                                         </button>   
                                                      </span>    
                                                   </div>`);
            cargarCarreras(idProfesor);
         
     },"json");
  } else {
         $("#controles").html(`
                                                   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                      <span style="color: #000000;">
                                                      <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                         &nbsp;<strong>Atención:</strong> No ha seleccionado una carrera.
                                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                               <span aria-hidden="true">&times;</span>
                                                         </button>   
                                                      </span>    
                                                   </div>`);                          
  }
}


//***********************************************************************************************************************************/
//***********************************************************************************************************************************/
//*************************************************** GESTIONAR MATERIAS ************************************************************/
//***********************************************************************************************************************************/
//***********************************************************************************************************************************/

//****************************************************************
//CARGA LAS MATERIAS DEL PROFESOR SEGUN LA CARRERA SELECCIONADA
//***************************************************************
function cargarMaterias(id_carrera,id_profesor) {
   let parametros = {'carrera':id_carrera, 'profesor':id_profesor};
   let carrera_nombre = getCarreraPorId(id_carrera).descripcion;
   let titulo = '';
   let habilitar_materia;
   //Remuevo la class que me deshabilita
   //console.info(cursado_activo);
   $("#resultado").removeClass("disabledbutton");
   titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Listado Alumnos)</h3>';
   
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+id_profesor+`)">Carreras</a></li>
                   <li class="breadcrumb-item">`+carrera_nombre+`</li>
                 </ol>
               </nav>`;
   $("#breadcrumb").html(bread);
   $("#titulo").html(titulo);
        $.post( "../API/findAllMateriasPorCarreraPorProfesor.php", parametros, function( data ) {
          let obj = data;
          $("#resultado").html("");
          let tabla_comienzo = `<div class="col-xs-12 col-sm-12 col-md-12" style="background-color: #E9D5B4;border-radius: 10px;">
                           <div class="row" style="padding: 10px;"> 
                                 <div class="col-xs-12 col-sm-12 col-md-8">
                                    <select name="inputAltaMateria" id="inputAltaMateria" class="form-control">
                                       <option value="">-- Materia --</option>
                                 </select>
                                 </div>

                                 <div class="col-xs-12 col-sm-12 col-md-4">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cursadoAgregarMateria(`+id_carrera+`)">
                                       Agregar
                                    </button>
                                 </div>
                                 
                           </div>
</div>
                       <table id="tabla_materias" class="table table-striped">
                        <thead class="thead-red">
                             <tr><th>MATERIA</th><th>AÑO</th><th>FORMATO</th><th>CURSADO</th><th>ACCIONES</th></tr>
                          </thead>
                          <tbody>
                        `;
          let filas = ``;
          let tabla_final  = ` </tbody>
                                 </table>`;
          if (obj.codigo==200) {
               obj.datos.forEach(materia => {
                       /*if (materia.codigoCursado=='01' && getEstaEnCursadoActivo(cursado_activo.data,1014)=='Si') {
                          habilitar_materia = '';  
                       } else if (materia.codigoCursado=='02' && getEstaEnCursadoActivo(cursado_activo.data,1015)=='Si') {
                          habilitar_materia = '';  
                       } else if (materia.codigoCursado=='03' && getEstaEnCursadoActivo(cursado_activo.data,1016)=='Si') {
                          habilitar_materia = '';  
                       } else {
                          habilitar_materia = 'disabledbutton';
                       };*/
                       habilitar_materia = "";
                       filas += `
                                  <tr id="tr_`+materia.materia_id+`"><td>`+materia.materia_nombre+` <strong>(`+materia.materia_id+`)</strong></td><td>`+materia.materia_anio+`</td>`+
                                     `<td>`+materia.formato_nombre+`</td>`+
                                     `<td>`+materia.cursado_nombre+`</td>`+
                                     `<td>`+
                                     `      <a href="#" class="`+habilitar_materia+`" onclick="cargarAlumnos(`+id_carrera+`,`+materia.materia_id+`,`+id_profesor+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../public/img/icons/listado_icon.png" width="25"></a>`+
                                     `&nbsp;&nbsp;<a href="#" class="link" onclick="cursadoDesvincularMateria(`+id_profesor+`,`+materia.materia_id+`,`+id_carrera+`)"><u>Desvincularme</u></a></td></tr>
                                   `;
                   });

                   
          } else {

                 filas = `
                          <tr><td colspan="5">No Existen Materias Asociadas.</td></tr>
                         `;
                 }
           $("#resultado").html(tabla_comienzo+filas+tabla_final);

           $.post( "./funciones/getMateriasPorIdCarrera.php", parametros, function( data_materias ) {
               if (data_materias.codigo==200) {
                     let obj = data_materias.datos; 
                     obj.forEach(materia => {
                           $("#inputAltaMateria").append($('<option/>', {
                                 text: materia.nombre+' ('+materia.materia_id+') - ' +materia.anio + ' Año',
                                 value: materia.materia_id+'-'+profesor_id,
                           }));
                     });
               }
            },"json");   
         $('#inputAltaMateria').select2();
      },"json");
}


function cursadoAgregarMateria(carrera_id) {
   //alert($("#inputAltaMateria").val());
   let valor = ($("body #inputAltaMateria").val()).split('-');
   let materia_id = valor[0];
   let profesor_id = valor[1];
   let parametros = {"materia":materia_id, "profesor":profesor_id};
   
   $.post("./funciones/setProfesorEnMateria.php",parametros,function(data){
      if (data.codigo==200) {
                        $("#controles").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atención:</strong> `+data.data+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
                } else {
                        $("#controles").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atención:</strong> `+data.data+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
                }
         $("#resultado").html("");
         cargarMaterias(carrera_id,profesor_id);
   },"json");
}



function cursadoDesvincularMateria(idProfesor,idMateria,idCarrera) {
   if (confirm('Desea desvincularse de la Materia?')) {
         let carrera = idCarrera;
         let materia = idMateria;
         let profesor = idProfesor;
         $.post( "./funciones/delMateriaProfesor.php",{"profesor":profesor,"materia":materia},function( data ) {
                  if (data.codigo==100) {
                        $("#controles").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                       <span style="color: #000000;">
                                             <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                   &nbsp;<strong>Atención:</strong> `+data.data+`
                                             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                             </button>   
                                       </span>    
                                 </div>`);
                       $("#tr_"+materia).remove();
                  };
         },"json")
   };   
}


//***********************************************************************************************
//***********************************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS GENERALES PARA ARMADO DE LISTAS EN UNA MATERIA ESPECIFICA
//***********************************************************************************************
//***********************************************************************************************
function cargarAlumnos(idCarrera, idMateria, idProfesor) {
   let datos_materia;
   let materia_nombre;
   let materia_cursado;
   let materia_formato;
   datos_materia = sacaDatosMateriaPorId(idMateria);
   if (datos_materia.codigo==100) {
      materia_cursado = datos_materia.data[0].cursado_codigo;
      materia_nombre = datos_materia.data[0].nombre;
      materia_formato = datos_materia.data[0].formato_codigo;
   } else {
      console.log('ERROR: Hubo un error con los datos de la Materia');
   }
   cargarAlumnosPorMateriaCursado(idCarrera,idMateria,materia_nombre,idProfesor,materia_cursado);
}


function cargarAlumnosPorMateriaCursado(carrera_id, materia_id,materia_nombre,profesor_id,materia_cursado) {
    //carrera = carrera_id;
    //profesor = profesor_id;
    let datos_evento;
    let evento_codigo;
    let evento_habilitado;
    let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong> (Crear Listado)</h3><hr>';
    let boton_agregar_alumno = '';  
    let carrera_nombre = getCarreraNombrePorId(carrera_id);
    if (materia_cursado == '01') { // 1er Cuatrimestre
       evento_codigo = 1014;
    } else if (materia_cursado == '02') { // 2do Cuatrimestre
       evento_codigo = 1015;
    } else if (materia_cursado == '03') { // Anual
       evento_codigo = 1016;
    };
    
    // Determina si el Evento Armado de Lista de Materias de un Cuatrimestre dado esta habilitado o no.
    datos_evento = getDatosEventoPorCodigo(evento_codigo);
    if (datos_evento.codigo==100) {
       if (datos_evento.habilitado=='Si') {
          evento_habilitado = 'Si';
          boton_agregar_alumno = '<button class="btn btn-primary" id="btnCargarAlumno" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Agregar Alumno</button>';
       } else {
          evento_habilitado = 'No';
       }
    } else {
       evento_habilitado = 'No';
       console.log('ERROR: Hubo un error con los datos del Evento '+evento_codigo);
    };
    
    let parametros = {"action":"Listar",'materia':materia_id};
    $.post( "./funciones/getAlumnosCursandoPorMateria.php", parametros, function( data ) {
        let obj = JSON.parse(data);
        let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarMaterias(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                      <li class="breadcrumb-item">`+materia_nombre+`</li>
                    </ol>
                  </nav>`;
 
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      $("#resultado").html("");
      // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
      let tabla_comienzo = `
                   <table id="tabla_alumnos_cursando" class="table table-striped">
                     <thead class="thead-green">
                         <tr>
                            <th>APELLIDO Y NOMBRE</th>
                            <th>EMAIL</th><th>TELEFONO</th>
                            <th>DNI</th><th>CURSADO</th>
                            <th>ACCIONES</th>
                         </tr>
                      </thead>
                      <tbody>
                    `;
      // FILAS DE RESULTADO DE LA TABLA
      let filas = ``;
      let nofilas = ``;
      // FIN DE LA TABLA
      let tabla_final  = `   </tbody>
                         </table>`;
      let accion_eliminar;
 
      if (obj.codigo==100) {
            obj.data.forEach(alumno => {
                  accion_eliminar = `<a href="#" class="btn btn-light" title="Eliminar Alumno del Cursado" onclick="cursadoEliminarAlumno(`+alumno.id+`,`+materia_id+`,`+carrera_id+`,`+profesor_id+`)">
                                         <img src="../public/img/icons/delete_icon.png" width="21">
                                     </a>`;              
                  filas += `
                            <tr><td>`+alumno.apellido+', '+alumno.nombre+` <strong>(`+alumno.id+`)</strong></td>`+
                                `<td>`+alumno.email+`</td>`+
                                `<td>`+alumno.telefono+`</td>`+
                                `<td>`+alumno.dni+`</td>`+
                                `<td><span class="badge badge-info">`+alumno.cursado+`</span></td>`+
                                `<td>&nbsp;`+accion_eliminar+`&nbsp;</td>
                            </tr>
                            `;
            });
       } else {
               nofilas = `
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                    <span style="color: #000000;">
                                          <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                &nbsp;<strong>Atenci&oacute;n:</strong> No Existen Alumnos en la Materia.
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                          </button>   
                                    </span>    
                              </div>` ;
         


       };
    
    let tabla_agregar_alumno = `    <div class="col-xs-12 col-sm-12 col-md-12" style="background-color: #E9D5B4;border-radius: 10px;">
                                       <div class="row" style="padding: 10px;"> 
                                             <div class="col-xs-12 col-sm-4 col-md-4 col-md-4">
                                                <select name="inputAltaAlumno" id="inputAltaAlumno" class="form-control">
                                                   <option value="">-- Alumno --</option>
                                                </select>
                                             </div>
                                             <div class="col-xs-12 col-sm-4 col-md-4">
                                                <select name="inputAltaCursado" id="inputAltaCursado" class="form-control">
                                                   <option value="">-- Cursado --</option>
                                                </select>
                                                </div>
                                             <div class="col-xs-12 col-sm-4 col-md-4">
                                                <button type="button" id="btnAgregar" class="btn btn-primary btn-sm" id="btnAplicar" onclick="cursadoAgregarAlumno(`+materia_id+`,`+carrera_id+`,`+profesor_id+`)">
                                                   Agregar
                                                </button>
                                             </div>
                                             
                                       </div>
                                       <div class="row">
                                          <div id="resultado_carga" class="col-xs-12 col-md-12 d-none"> 
                                          </div>
                                       </div>
                                    </div>
                                  `; 
    $("#resultado").html(tabla_agregar_alumno);    
    $("#resultado").append(tabla_comienzo+filas+tabla_final);                       
    if (nofilas!='') {
      $("#resultado_carga").removeClass('d-none'); 
      $("#resultado_carga").html(nofilas);
    };
   //CARGA EL SELECT2 CON LOS ALUMNOS DE LA CARRERA
    $.post( "../API/findAllAlumnosPorCarrera.php", {'carrera':carrera_id}, function( data_alumnos ) {
       let obj = data_alumnos;
       console.info(data_alumnos);
       obj.datos.forEach(alumno => {
             $("#inputAltaAlumno").append($('<option/>', {
                   text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                   value: alumno.id,
             }));
       });
    },"json");   
    $('#inputAltaAlumno').select2();
 
    //CARGA EL SELECT2 CON LOS DATOS DEL CURSADO
    $.post( "../API/findAllAlumnoTiposCursado.php", function( data_cursado ) {
       let obj = data_cursado;
       //console.log(data_cursado);
       obj.datos.forEach( cursado_forma => {
             $("#inputAltaCursado").append($('<option/>', {
                   text: cursado_forma.nombre,
                   value: cursado_forma.id+'-'+cursado_forma.codigo,
             }));
       });
    },"json");
    $('#inputAltaCursado').select2();
 
    //SI SE VERIFICA QUE NO ESTA HABILITADO ENTONCES SE DESHABILITA TODO EL LISTADO
    if (evento_habilitado=='No') {
       $("#resultado").addClass("disabledbutton");
    }
   });
 }


//****************************************************************************
//FUNCION PARA CARGAR UN ALUMNO A UNA MATERIA ESPECIFICADA
//****************************************************************************
function cursadoAgregarAlumno(idMateria,idCarrera,idProfesor) {
    let alumno = $('#inputAltaAlumno').val();
    let cursado_forma = $('#inputAltaCursado').val();

    if (alumno && cursado_forma) {
         let parametros = {'materia':idMateria, 'alumno':alumno, 'cursado':cursado_forma};
         $.post( "./funciones/setAlumnoEnMateria.php", parametros, function( datos ) {
                  let obj = datos;
                  $("#resultado_carga").removeClass("d-none");
                  
                  if (obj.codigo==100) {
                     $("#resultado_carga").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                      <span style="color: #000000;">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                                  &nbsp;<strong>Atención:</strong> `+obj.data+`.
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                            </button>   
                                                      </span>    
                                                </div>`);
                     let datos_alumno = sacaDatosAlumnoParaCursadoPorId(alumno,idMateria); 
                     if (datos_alumno.codigo==100) {
                        let accion_eliminar = `<a href="#" class="btn btn-light" title="Eliminar Alumno del Cursado" onclick="cursadoEliminarAlumno(`+alumno+`,`+idMateria+`,`+idCarrera+`,`+idProfesor+`)">
                                                   <img src="../public/img/icons/delete_icon.png" width="21">
                                                </a>`;
                        let fila = `<tr>
                                       <td>`+datos_alumno.data[0].apellido+`, `+datos_alumno.data[0].nombre+` <strong>(`+datos_alumno.data[0].id+`)</strong></td>
                                       <td>`+datos_alumno.data[0].email+`</td>
                                       <td>`+datos_alumno.data[0].telefono+`</td>
                                       <td>`+datos_alumno.data[0].dni+`</td>
                                       <td><span class="badge badge-info">`+datos_alumno.data[0].cursado+`</span></td>
                                       <td>&nbsp;`+accion_eliminar+`&nbsp;</td>
                                    `;
                        $("#tabla_alumnos_cursando>tbody").prepend(fila);
                     } else {
                        $("#resultado_carga").html(`<div class="alert alert-dark" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                        No se pudo recuperar los datos del Alumno.</span></i>
                        </div>`);
                     }                                            
                  } else {
                     $("#resultado_carga").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                      <span style="color: #000000;">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                                  &nbsp;<strong>Atención:</strong> `+obj.data+`.
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                            </button>   
                                                      </span>    
                                                </div>`);
                  };
         },"json");
   } else {
         $("body #resultado_carga").removeClass('d-none'); 
         $("body #resultado_carga").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                      <span style="color: #000000;">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                                  &nbsp;<strong>Atención:</strong> Faltan completar datos obligatorios.
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                            </button>   
                                                      </span>    
                                                </div>`);
   }
 }



//****************************************************************************
// FUNCION PARA DESVINCULAR UN ALUMNO DE UNA MATERIA ESPECIFICADA
//****************************************************************************
 function cursadoEliminarAlumno(idAlumno,idMateria,idCarrera,idProfesor) {
     let alumno_nombre = sacaNombreAlumnoPorId(idAlumno);
     if(confirm("Desvincular de la materia a "+alumno_nombre+' ?')) {
        
        $.post('./funciones/deleteAlumnoEnMateria.php', {"materia":idMateria,"alumno":idAlumno}, function(data){
            let obj = JSON.parse(data);
            if (obj.codigo==100) {
             cargarAlumnos(idCarrera, idMateria, idProfesor, 'cursado');
             $("body #resultado_carga").removeClass('d-none');
             $("#controles").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                      <span style="color: #000000;">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                                  &nbsp;<strong>Atención:</strong> El Alumno <b>`+alumno_nombre+`</b> fue desvinculado de la Materia.
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                            </button>   
                                                      </span>    
                                                </div>`);

            } else {
                $("#resultado_carga").html(`<div class="alert alert-dark" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                `+obj.data+`</span></i>
                </div>`);
                $("#controles").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                      <span style="color: #000000;">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                                  &nbsp;<strong>Atención:</strong> <b>`+obj.data+`</b>
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                            </button>   
                                                      </span>    
                                                </div>`);
            }
        })
     }
 }
 