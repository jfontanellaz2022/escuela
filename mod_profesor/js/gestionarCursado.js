//***********************************************************************************************************************************/
//***********************************************************************************************************************************/
//*************************************************** GESTIONAR CARRERAS ************************************************************/
//***********************************************************************************************************************************/
//***********************************************************************************************************************************/

let arr_carreras = getCarreras();
let arr_materias = getMaterias();

function expired() {
      location.href = "./logout.php";
}



//***********************************************
// RETORNA TODAS LAS CARRERAS
//***********************************************
function getCarreras() {
   let carreras
   $.ajax({
      url:"../API/serviceProfesor.php?token="+token,
      type:"POST",
      data: {"opcion":"CARRERAS"},
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
         url:"../API/serviceProfesor.php?token="+token,
         type:"POST",
         data: {"opcion":"MATERIAS"},
         dataType : 'json',
         async: false,
         success: function(resultado){
            materias = resultado.datos;
         }
   });
   return materias;
   }


function getCarreraPorId(carrera_id) {
   let carrera;
  
   arr_carreras.forEach(function(currentValue, index, arr){
      if (currentValue['id']==carrera_id) {
          carrera = currentValue;
      }; 
      
   })
   return carrera;
}


function getMateriaPorId(materia_id) {
      let materia;
     
      arr_materias.forEach(function(currentValue, index, arr){
         if (currentValue['id']==materia_id) {
             materia = currentValue;
         }; 
         
      })
      return materia;
   }

//****************************************************************************************** 
// NOS PERMITE BUSCAR LOS ALUMNOS INSCRIPTOS EN EL CURSADO DE LA MATERIA 
//****************************************************************************************** 
function entidadObtenerAlumnosPorMateria(materia_id){
   let url = "../API/serviceProfesor.php?token="+token;
   let resultado;
   let anio_actual = new Date().getFullYear();

   
   $.ajaxSetup({
       async: false
     });
   $.post(url, {"opcion":"MATERIA_ALUMNOS_CURSADO","materia_id":materia_id,"anio":anio_actual}, function (data) {
         resultado = data;
   },"json")
   return resultado;
};


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
        $.post("../API/findAllCarreras.php?token="+token, function(datos_carreras) {
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
  let parametros = {'opcion':'CARRERAS_POR_PROFESOR','profesor':idProfesor};
  $.post( "../API/serviceProfesor.php?token="+token, parametros, function( data ) {
                  let obj = data;
                  $("#breadcrumb").html(bread);
                  $("#titulo").html(titulo);
                  $("#resultado").html("");
                        let agrega_carrera = `<div class="col-md-4">
                                    <div class="card" style="width: 18rem;">
                                          <img src="../public/img/logo_150x150.jpg" class="card-img-top">
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
  let parametros = {"opcion":"DESVINCULAR_PROFESOR_CARRERA","carrera_id":idCarrera, "profesor_id":idProfesor};
  if (confirm("Desea desvincularse de la Carrera?")) {
      $.post("../API/serviceProfesor.php?token="+token,parametros,function (response) {

            $("#controles").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.class+`">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atenci&oacute;n:</strong> `+response.mensaje+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
            if (response.codigo==200) { 
                  Swal.fire({
                                    title: "Actualización Realizada!",
                                    text: "Se ha Desvinculado a la Carrera.",
                                    icon: "success"
                              });
            } else {
                  Swal.fire({
                        title: "No se pudo Realizar la Operación!",
                        text: "No se ha podido Desvincular a la Carrera.",
                        icon: "danger"
                  });
            }

            cargarCarreras(idProfesor);

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

  parametros = {"opcion":"VINCULAR_PROFESOR_CARRERA","profesor_id":idProfesor,"carrera_id":idCarrera};
  $.post("../API/serviceProfesor.php?token="+token,parametros, function(response){
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
            if (response.codigo==200) { 
                                                      Swal.fire({
                                                                        title: "Actualización Realizada!",
                                                                        text: "Se ha Vinculado a la Carrera.",
                                                                        icon: "success"
                                                                  });
            } else {
                                                      Swal.fire({
                                                            title: "No se pudo Realizar la Operación!",
                                                            text: "No se ha podido Vincular a la Carrera.",
                                                            icon: "danger"
                                                      });
            }
            cargarCarreras(idProfesor);
         
  },"json");

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
   
   let carrera_nombre = getCarreraPorId(id_carrera).descripcion;
   let titulo = '';
   //Remuevo la class que me deshabilita
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
  
        $.post( "../API/serviceProfesor.php?token="+token, {'opcion':'MATERIAS_CARRERA_PROFESOR','carrera_id':id_carrera, 'profesor_id':id_profesor}, function( data ) {
          let obj = data;
          $("#resultado").html("");
          let tabla_comienzo = `<div class="col-xs-12 col-sm-12 col-md-12" id='panel_materia'style="background-color: #E9D5B4;border-radius: 10px;">
                                    <div class="row disabled" style="padding: 10px;"> 
                                          <div class="col-xs-12 col-sm-12 col-md-8">
                                             <select name="inputAltaMateria" id="inputAltaMateria" class="form-control">
                                                <option value="">-- Materia --</option>
                                             </select>
                                          </div>

                                          <div class="col-xs-12 col-sm-12 col-md-4">
                                             <button type="button" class="btn btn-primary btn-block " onclick="cursadoAgregarMateria(`+id_carrera+`)">
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
               let cantidad = 0;
               let entidad = "";
               let budge = "";
               obj.datos.forEach(materia => {
               entidad = entidadObtenerAlumnosPorMateria(materia.materia_id);
               cantidad = entidad.datos.length;
                    
                      if (cantidad!=0) {
                        budge = `<span class="badge badge-primary">`+cantidad+`</span>`;
                        habilitar_materia = '';
                      } else {
                        budge = '';
                        habilitar_materia = 'disabledbutton';
                      }

                       if (materia.cursado_codigo=='2001') {
                          habilitar_materia = '';  
                       } else if (materia.cursado_codigo=='2002') {
                          habilitar_materia = '';  
                       } else if (materia.cursado_codigo=='2003') {
                          habilitar_materia = '';  
                       } else {
                          habilitar_materia = 'disabledbutton';
                       };
                       

                       filas += `
                                  <tr class=`+habilitar_materia+` id="tr_`+materia.materia_id+`"><td>`+materia.materia_nombre+` <strong>(`+materia.materia_id+`)</strong><a href="#" class="disabledbutton" ><img src="../../public/img/icons/documento-legal.png" width="35"></a></td><td>`+materia.materia_anio+`</td>`+
                                     `<td>`+materia.formato_nombre+`</td>`+
                                     `<td>`+materia.cursado_nombre + `</td>`+
                                     `<td>`+
                                     `      <a href="#" class="`+habilitar_materia+`" onclick="cargarAlumnos(`+id_carrera+`,`+materia.materia_id+`,`+id_profesor+`)" data-toggle="tooltip" data-placement="bottom" title="Ver Listado de Alumnos"><img src="../public/img/icons/icon_list.png" width="27">`+budge+`</a>`+
                                     `&nbsp;&nbsp;<a href="#" class="link" onclick="cursadoDesvincularMateria(`+id_profesor+`,`+materia.materia_id+`,`+id_carrera+`)"><u>Desvincularme</u></a></td></tr>
                                   `;
                   });

                   
          } else {

                 filas = `
                          <tr><td colspan="5">No Existen Materias Asociadas.</td></tr>
                         `;
                 }
           $("#resultado").html(tabla_comienzo+filas+tabla_final);

           $.post( "../API/serviceProfesor.php?token="+token, {"opcion":"MATERIAS_POR_CARRERA","carrera_id":id_carrera}, function( data_materias ) {
               if (data_materias.codigo==200) {
                     let obj = data_materias.datos; 
                     obj.forEach(materia => {
                           $("#inputAltaMateria").append($('<option/>', {
                                 text: materia.nombre+' ('+materia.id+') - ' +materia.anio + ' Año',
                                 value: materia.id+'-'+profesor_id,
                           }));
                     });
               }
            },"json");   
         $('#inputAltaMateria').select2();
      },"json");
}




// ***************************************************************** VINCULA UNA MATERIA CON EL PROFESOR QUE LA DICTA ****************************************************
// ***********************************************************************************************************************************************************************
function cursadoAgregarMateria(carrera_id) {
   let valor = ($("body #inputAltaMateria").val()).split('-');
   let materia_id = valor[0];
   let profesor_id = valor[1];
   let parametros = {"opcion":"VINCULAR_PROFESOR_MATERIA","materia_id":materia_id, "profesor_id":profesor_id};
   
   $.post("../API/serviceProfesor.php?token="+token,parametros,function(response){
      if (response.codigo==200) {
                        $("#controles").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atención:</strong> `+response.mensaje+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
                                                Swal.fire({
                                                   title: "Actualización Realizada!",
                                                   text: "Se ha Registrado a la Materia.",
                                                   icon: "success"
                                               });
                } else {
                        Swal.fire({
                           title: "No se pudo Realizar la Operación!",
                           text: "No se ha podido registrar a la Materia.",
                           icon: "danger"
                     });
                }
         $("#resultado").html("");
         cargarMaterias(carrera_id,profesor_id);
   },"json");
}



// ************************************************************** DESVINCULA UNA MATERIA CON EL PROFESOR QUE LA DICTA ****************************************************
// ***********************************************************************************************************************************************************************
function cursadoDesvincularMateria(idProfesor,idMateria,idCarrera) {
   if (confirm('Desea desvincularse de la Materia?')) {
         let materia = idMateria;
         let profesor = idProfesor;
         let param = {"opcion":"DESVINCULAR_PROFESOR_MATERIA","profesor_id":profesor,"materia_id":materia};
         $.post( "../API/serviceProfesor.php?token="+token,param,function(response) {
                  $("#controles").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.class+`">
                                       <span style="color: #000000;">
                                             <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                   &nbsp;<strong>Atención:</strong> `+response.mensaje+`
                                             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                             </button>   
                                       </span>    
                                 </div>`);
                  if (response.codigo==200) {
                       Swal.fire({
                        title: "Actualización Realizada!",
                        text: "Se ha Desvinculado de la Materia.",
                        icon: "success"
                        });
                        //$("#tr_"+materia).remove();
                       //$("#resultado").html("");
                       cargarMaterias(idCarrera,idProfesor);
                  };
         },"json");
   };   
}


function cargarAlumnos(carrera_id,materia_id,profesor_id) {
    let carrera_nombre = getCarreraPorId(carrera_id).descripcion;
    let materia_nombre = getMateriaPorId(materia_id).nombre;
    let anio_actual = new Date().getFullYear();
    let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong></h3><hr>';
    $.post( "../API/serviceProfesor.php?token="+token, {"opcion":"MATERIA_ALUMNOS_CURSADO", 'materia_id':materia_id,"anio":anio_actual}, function( response ) {
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
      if (response.codigo==200) {
            response.datos.forEach(alumno => {
                 
                  accion_eliminar = `<a href="#" class="btn btn-light" title="Eliminar Alumno del Cursado" onclick="cursadoEliminarAlumno(`+alumno.id+`,`+materia_id+`,`+carrera_id+`,`+profesor_id+`)">
                                         <img src="../public/img/icons/delete_icon.png" width="21">
                                     </a>`;              
                  filas += `
                            <tr><td>`+alumno.apellido+', '+alumno.nombre+` <strong>(`+alumno.id+`)</strong></td>`+
                                `<td><a href="mailto:`+alumno.email+`">`+alumno.email+`</a></td>`+
                                `<td> <a href="https://api.whatsapp.com/send/?phone=549`+alumno.telefono_caracteristica+alumno.telefono_numero+`&text=Hola&type=phone_number&app_absent=0" target="_blank">
                                      <img src="../public/img/icons/wsp_icon.png" width="20"></a> 
                                <strong>(`+alumno.telefono_caracteristica+`) `+ alumno.telefono_numero +`</strong></td>`+
                                `<td>`+alumno.dni+`</td>`+
                                `<td><span class="badge badge-info">`+alumno.nombre_cursado+`</span></td>`+
                                `<td>&nbsp;&nbsp;</td>
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
    
    $("#resultado").append(tabla_comienzo+filas+tabla_final);                       
    if (nofilas!='') {
      $("#resultado_carga").removeClass('d-none'); 
      $("#resultado_carga").html(nofilas);
    };
   
   },"json");
 }

