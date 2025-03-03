//***********************************************************************************************************************************/
//***********************************************************************************************************************************/
//*************************************************** GESTIONAR CARRERAS ************************************************************/
//***********************************************************************************************************************************/
//***********************************************************************************************************************************/



function estaActivoExamenes(ev) {
   resultado = false;
   for (i = 0; i < carga_regularidades_activo.datos.length; i++) {
      if (carga_regularidades_activo.datos[i].codigo==ev) {
         resultado = true;
         break;
      }
    } 
   return resultado; 
}

//***********************************************************
// RETORNA EL EVENTO CARGA REGULARIDADES (ACTIVO) CON SUS DATOS 
//***********************************************************
function getCargaRegularidadesActivo() {
   let datos_cursado;
   $.ajax({
      url:"../API/findEventoExamenesActivo.php?token="+token,
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_cursado = datos;
      }
});
  return datos_cursado;
} 

//***********************************************
// RETORNA EL NOMBRE DE UNA CARRERA 
//***********************************************
function getCarreras() {
   let carreras
   $.ajax({
      url:"../API/findAllCarreras.php?token="+token,
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(resultado){
         carreras = resultado.datos;
      }
   });
   return carreras;
}


//***********************************************
// RETORNA TODAS LAS MATERIAS
//***********************************************
function getMaterias() {
   let materias
   $.ajax({
      url:"../API/findAllMaterias.php?token="+token,
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(resultado){
         materias = resultado.datos;
      }
   });
   return materias;
}

let arr_carreras = getCarreras();
let arr_carga_regularidades_activo = getCargaRegularidadesActivo();
let arr_materias = getMaterias();

// SACA TODAS LAS MATERIAS DE UNA CARRERA
function getCarreraPorId(carrera_id) {
   let carrera;
   arr_carreras.forEach(function(currentValue, index, arr){
      if (currentValue['id']==carrera_id) {
          carrera = currentValue;
      }; 
      
   })
   return carrera;
}

// SACA TODOS LOS DATOS DE UNA MATERIA
function getMateriaPorId(materia_id) {
   let materia;
   arr_materias.forEach(function(currentValue, index, arr){
      if (currentValue['id']==materia_id) {
          materia = currentValue;
      }; 
      
   })
   return materia;
}

//**********************************************************************************
// FUNCION QUE OBTIENE LAS CARRERAS EN LAS QUE DICTA CLASES UN PROFESOR ESPECIFICO
//**********************************************************************************
function cargarCarreras(profesor_id) {
   //Activo del Menu la opcion Alumnos
   let titulo;
   let subtitulo;
   let bread;
  
   $(".nav-item-alumnos").removeClass("active");
   $(".nav-item-regularidades").removeClass("active");
   $(".nav-item-examenes").addClass("active");
   //$("#controles").html('');
   subtitulo = 'Gestión de los Exámenes de los Alumnos';
  
   bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="./home.php?token=`+token+`">Home</a></li>
                   <li class="breadcrumb-item active" aria-current="page">Carreras</li>
                 </ol>
            </nav>`;
   titulo = `
                  <div class="alert alert-dark">
                     <h3>Gestión de los Exámenes Finales de Alumnos</h3>
                     <h4>Lista de Carreras</h4>
                  </div>
            `;
   $("#breadcrumb").html(bread+titulo);
   //Remuevo la class que me deshabilita
   if (disabledVerCarreras=="") {
       $("#resultado").removeClass('disabledbutton');
   } else {
       $("#resultado").addClass('disabledbutton');
   }
   let parametros = {'profesor':profesor_id};
   $.post( "../API/findAllCarrerasPorProfesor.php?token="+token, parametros, function( response ) {
                   
                   $("#resultado").html("");
                   if (response.codigo==200) {
                           response.datos.forEach(carrera => {
                                       let linkClick = '';
                                       linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarLlamados(`+carrera.id+`,`+profesor_id+`)" >Ingresar</a>`;
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
                   } else {
                           let resul = `<div class="col-md-4">
                                             <div class="card" style="width: 18rem;">
                                                      <img src="../public/img/logo_n.jpg" class="card-img-top">
                                                      <div class="card-body">
                                                         <h5 class="card-title"><img src="../public/img/icons/add2_icon.png" width="23">&nbsp;No tiene una Carrera</h5>
                                                               <h6 class="card-subtitle mb-2 text-muted"></h6>
                                                         <p class="card-text"></p>
                                                      </div>
                                             </div>
                                       </div>`;
                           $("#resultado").append(resul);
                   }
   },'json');
 }

//**********************************************************************
//CARGA LOS LLAMADOS DE UN TURNO PROFESOR SEGUN LA CARRERA SELECCIONADA
//**********************************************************************
function cargarLlamados(carrera_id,profesor_id) {
   let carrera_nombre = getCarreraPorId(carrera_id).descripcion;
   let titulo = '';
   titulo = `
                  <div class="alert alert-dark">
                     <h3>`+carrera_nombre+`</h3>
                     <h4>Llamados del Turno</h4>
                  </div>
            `;
   
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="./home.php?token=`+token+`">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                   <li class="breadcrumb-item">`+carrera_nombre+`</li>
                 </ol>
               </nav>`;
   $("#breadcrumb").html(bread+titulo);
   llamados=`<div class="col-md-4">
             <div class="card `+disabledVerLlamado1+`" >
               <div class="card-body">
                  <h3 class="card-title">Primer Llamado</h3>
                  <p class="card-text">Fecha: <strong>`+fecha_primer_llamado_desde+`</strong> hasta <strong>` + fecha_primer_llamado_hasta+`</strong> </p>
                  <button type="button" name="button" onclick="cargarMaterias(`+carrera_id+`,`+profesor_id+`,1)" class="btn btn-primary btn-block">Seleccionar</button>
               </div>
            </div> 
            &nbsp;&nbsp;
            <div class="card `+disabledVerLlamado2+`" >
               <div class="card-body">
                  <h3 class="card-title">Segundo Llamado</h3>
                  <p class="card-text">Fecha: <strong>`+fecha_segundo_llamado_desde+`</strong> hasta <strong>` + fecha_segundo_llamado_hasta+`</strong> </p>
                  <button type="button" name="button" onclick="cargarMaterias(`+carrera_id+`,`+profesor_id+`,2)" class="btn btn-primary btn-block">Seleccionar</button>
               </div>
            </div>
            </div>`;
            
      $("#resultado").html(llamados);      
};




//****************************************************************************************** 
// NOS PERMITE BUSCAR LOS ALUMNOS INSCRIPTOS EN EL LLAMADO DE UN TURNO EN UNA MATERIA DADA 
//****************************************************************************************** 
function entidadObtenerAlumnosPorMateria(calendario_id,materia_id,llamado){
   let url = "../API/findAllAlumnosInscriptosPorMateria.php?token="+token;
   let resultado;
   
   $.ajaxSetup({
       async: false
     });
   $.post(url, {"calendario_id":calendario_id,"materia_id":materia_id,"llamado":llamado}, function (data) {
         resultado = data;
   },"json")
   return resultado;
};


//****************************************************************
//CARGA LAS MATERIAS DEL PROFESOR SEGUN LA CARRERA SELECCIONADA
//***************************************************************
function cargarMaterias(carrera_id,profesor_id,llamado) {
   let parametros = {'carrera':carrera_id, 'profesor':profesor_id};
   let carrera_nombre = getCarreraPorId(carrera_id).descripcion;
   let habilitar_materia = 'disabledbutton';
   

   //let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Notas Examenes)</h3>';
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="./home.php?token=`+token+`">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarLlamados(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+ `</a></li>
                   <li class="breadcrumb-item">Llamado `+llamado+`</li>
                 </ol>
               </nav>`;
   let titulo = `
               <div class="alert alert-dark">
                  <h3>`+carrera_nombre+`</h3>
                  <h4>Lista de Materias</h4>
               </div>
            `;
   $("#breadcrumb").html(bread+titulo);

   $.post( "../API/findAllMateriasPorCarreraPorProfesor.php?token="+token, parametros, function( response ) {
      $("#resultado").html("");
      let tabla_comienzo = `
                   <table class="table table-striped">
                      <thead class="thead-red">
                         <tr><th>MATERIA</th><th>AÑO | FORMATO</th><th>FECHA EXAMEN</th><th>CURSADO</th><th>ACCIONES</th></tr>
                      </thead>
                      <tbody>
                    `;
      let filas = ``;
      let tabla_final  = `   </tbody>
                         </table>`;
      
      if (response.codigo==200) {
              let cantidad = 0;
              let entidad = "";
              let budge = "";

              response.datos.forEach(materia => {
                   entidad = entidadObtenerAlumnosPorMateria(inscripcion_activa,materia.materia_id,llamado);
                   cantidad = entidad.datos.length;
                   
                   if (cantidad!=0) {
                     budge = `<span class="badge badge-primary">`+cantidad+`</span>`;
                     habilitar_materia = '';
                   } else {
                     budge = '';
                     habilitar_materia = 'disabledbutton';
                   }

                   filas += `
                                    <tr class='`+habilitar_materia+`'><td>`+materia.materia_nombre+` <strong>(`+materia.materia_id+`)</strong></td><td>`+materia.materia_anio+ ` | ` +materia.formato_nombre + `</td>`+
                                    `<td>`+materia.fecha_examen+`</td>`+
                                    `<td>`+materia.cursado_nombre+`</td>`+
                                    `<td>`+
                                    `      <a href="#" class="linkAlumnos" onclick="cargarAlumnosPorMateriaExamenes(`+carrera_id+`,'`+carrera_nombre+`',`+materia.materia_id+`,'`+materia.materia_nombre+`',`+profesor_id+`,`+llamado+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../public/img/icons/listado_icon.png" width="25">`+budge+`</a>`+
                                    `</td></tr>
                                    `;
               });
      } else {
             filas = `
                      <tr><td colspan=5>No Existen Materias Asociadas.</td></tr>
                     `;
             }
       $("#resultado").html(tabla_comienzo+filas+tabla_final);
    },"json"); 

}

//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA REGULARIDAD
//************************************************************************
//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA FINALES PARA UNA MATERIA
//************************************************************************
function cargarAlumnosPorMateriaExamenes(carrera_id, carrera_nombre, materia_id,materia_nombre,profesor_id,llamado) {
   let evento_habilitado = 'No';
   let calendario_id = inscripcion_activa;
   let llamado_nro = llamado;
   let titulo = `
                        <div class="alert alert-dark">
                           <h3>`+materia_nombre+`</h3>
                           <h4><strong>Lista de Alumnos</strong> (Ingresar Notas de Exámen Final) - Llamado `+llamado_nro+`</h4>
                        </div>
                  `;
   let accion_editar = '<a href="#" class="disabledbutton" title="Editar Datos del Final"><img src="../public/img/icons/edit_icon.png" width="23"></a>';
   let disabledPonerNotasAlumnosLlamado = "";
   if (llamado==1) {
      disabledPonerNotasAlumnosLlamado = disabledPonerNotasAlumnosLlamado1;
   } else if (llamado==2) {
      disabledPonerNotasAlumnosLlamado = disabledPonerNotasAlumnosLlamado2;
   } else {
      disabledPonerNotasAlumnosLlamado = "disabledbutton";
   }
 
   let parametros = {"materia_id":materia_id, "calendario_id":inscripcion_activa, "llamado":llamado_nro};

   $.post("../API/findAllAlumnosInscriptosPorMateria.php?token="+token, parametros, function( data ) {
      let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="./home.php?token=`+token+`">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('examenes',`+profesor_id+`)">Carreras</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarLlamados(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarMaterias(`+carrera_id+`,`+profesor_id+`,`+llamado_nro+`,)">Llamado `+llamado_nro+`</a></li>
                      <li class="breadcrumb-item">`+materia_nombre+`</li>
                    </ol>
                  </nav>`;
      $("#breadcrumb").html(bread+titulo);
      $("#resultado").html("");
      // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
      let tabla_comienzo = `
                   <table class="table">
                      <thead class="thead-green">
                         <tr>
                            <th>APELLIDO Y NOMBRE</th>
                            <th>DNI</th>
                            <th>CELULAR</th>
                            <th>CURSADO</th>
                            <th>NOTA</th>
                            <th>ESTADO</th>
                         </tr>
                      </thead>
                      <tbody>
                    `;
      // FILAS DE RESULTADO DE LA TABLA
      let filas = ``;
      // FIN DE LA TABLA
      let tabla_final  = `   </tbody>
                         </table>`;
      
      if (data.codigo==200) {
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
                                      <td><a href="mailto:`+alumno.email+`"><img src="../public/img/icons/email_icon.png" width="18"></a>&nbsp;`+alumno.apellido+', '+alumno.nombre+`<strong>(`+alumno.id+`)</strong></td>`+
                                      `<td>`+alumno.dni+`</td>`+
                                      `<td> <a href="https://api.whatsapp.com/send/?phone=549`+alumno.telefono_caracteristica+alumno.telefono_numero+`&text=Hola&type=phone_number&app_absent=0" target="_blank">
                                      <img src="../public/img/icons/wsp_icon.png" width="22"></a> 
                                <strong>(`+alumno.telefono_caracteristica+`) `+ alumno.telefono_numero +`</strong></td>` +
                                      `<td><span class='badge badge-primary'>`+alumno.condicion+`</span></td>`+
                                      `<td><span class='badge badge-info'>`+nota_examen_final+`</span></td>`+
                                      `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                  `</tr>
                                  `;
                  });
         };
         
         let tabla_agregar_alumno = `  
                                    <div class="col-xs-12 col-sm-12 col-md-12 `+disabledPonerNotasAlumnosLlamado+`" style="background-color: #E9D5B4;border-radius: 10px;">
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
                                                <button type="button" class="btn btn-primary btn-block" onclick="finalesAgregarNotaAlumno(`+materia_id+`,'`+materia_nombre+`',`+carrera_id+`,'`+carrera_nombre+`',`+calendario_id+`,`+llamado_nro+`,`+profesor_id+`)">
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
         $.post( "../API/findAllAlumnosInscriptosPorMateria.php?token="+token,parametros, function( data_alumnos ) {
            if (data_alumnos.codigo==200) {
                data_alumnos.datos.forEach(alumno => {
                      $("#inputFinalesAltaAlumno").append($('<option/>', {
                            text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                            value: alumno.id,
                      }));
                });
            };     
         },"json");   
         
         $('#inputFinalesAltaAlumno').select2({
            theme: "bootstrap",
         });
         $('#inputFinalesAltaNota').select2({
            theme: "bootstrap",
         });
         $('#inputFinalesAltaEstadoFinal').select2({
            theme: "bootstrap",
         });
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
   $.post( "../API/setNotaExamenFinal.php?token="+token, parametros, function( response ) {
            $("#resultado_carga").removeClass("d-none");
            if (response.codigo==200) {
                     Swal.fire({
                                title: "Actualización Realizada!",
                                text: "La Nota se ha Registrado.",
                                icon: "success"
                     });
                  cargarAlumnosPorMateriaExamenes(idCarrera, carrera_nombre, idMateria,materia_nombre,idProfesor,llamado)
            } else {
                  Swal.fire({
                        title: "Actualización Realizada!",
                        text: "La Nota No se ha Registrado.",
                        icon: "error"
               });
            }
    },"json");
}
