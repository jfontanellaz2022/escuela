//***********************************************************************
// OBTIENE LAS CARRERAS EN LAS QUE DICTA CLASES UN PROFESOR ESPECIFICO
//***********************************************************************
/*function load() {
  let usuario = '24912834';
    $.post( "../funciones/getProfesorPorUsuario.php", {'usuario':usuario}, function( datos ) {
        let obj = JSON.parse(datos);
        let idProfesor = obj.data[0].id;
        cargarCarreras(idProfesor);
    });
} */

//**********************************************************************************
// FUNCION QUE OBTIENE LAS CARRERAS EN LAS QUE DICTA CLASES UN PROFESOR ESPECIFICO
//**********************************************************************************
function cargarCarreras(opcion, idProfesor) {
  //Activo del Menu la opcion Alumnos
  let titulo;
  let subtitulo;
  let bread;
  if (opcion=='cursado') {
      $(".nav-item-alumnos").addClass("active");
      $(".nav-item-regularidades").removeClass("active");
      $(".nav-item-examenes").removeClass("active");
      subtitulo = 'Seleccion Carrera para la gestion de Lista de Materias/Alumnos';
  } else if (opcion=='regularidades') {
      $(".nav-item-alumnos").removeClass("active");
      $(".nav-item-regularidades").addClass("active");
      $(".nav-item-examenes").removeClass("active");
      subtitulo = 'Gestionar Regularidades de Alumnos';
  } else if (opcion=='examenes') {
      $(".nav-item-alumnos").removeClass("active");
      $(".nav-item-regularidades").removeClass("active");
      $(".nav-item-examenes").addClass("active");
      subtitulo = 'Gestionar Examenes Finales de Alumnos';

}

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
  let parametros = {"action":"Listar",'profesor':idProfesor};
  $.post( "../funciones/getCarrerasPorIdProfesor.php", parametros, function( data ) {
    let obj = JSON.parse(data);
    

    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $("#resultado").html("");
    if (opcion=='cursado') {
        let agrega_carrera = `<div class="col-md-4">
                          <div class="card" style="width: 18rem;">
                                  <img src="../assets/img/logo_n.jpg" class="card-img-top">
                                  <div class="card-body">
                                      <h5 class="card-title"><img src="../assets/img/icons/add2_icon.png" width="23">&nbsp;Nueva vinculación a Carrera</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"></h6>
                                      <p class="card-text"></p>
                                      <button class="btn btn-primary btn-block" onclick="vincularCarrera(`+idProfesor+`)">Vincularme</button>
                                  </div>
                                </div>
                          </div>`;
        $("#resultado").html(agrega_carrera);
    }
    obj.data.forEach(carrera => {
      let linkClick = '';
       if (opcion=='examenes') {
          linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarLlamados(`+carrera.id+`,`+idProfesor+`,'`+opcion+`')" >Ingresar</a>`;
       } else if (opcion=='regularidades') {
          linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarMateriaPorCarreraRegularidades(`+carrera.id+`,`+idProfesor+`)" >Ingresar</a>`;
       } else if (opcion=='cursado') {
          linkClick = `<a href="#" class="carrera btn btn-primary btn-block" onclick="cargarMateriaPorCarreraCursado(`+carrera.id+`,`+idProfesor+`,'`+opcion+`')" >Ingresar</a>`+
                      ` &nbsp;&nbsp;<a href="#" class="btn btn-danger btn-block" onclick="desvincularCarrera(`+carrera.id+`,`+idProfesor+`)">Desvincularme</a>`;
       };
       let resul = `<div class="col-md-4">
                      <div class="card" style="width: 18rem;">
                              <img src="../assets/img/`+carrera.imagen+`" class="card-img-top">
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
  });
}


function vincularCarrera(idProfesor) {
  let bread = `<nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item" aria-current="page">
                        <a href="#" onclick="cargarCarreras('cursado',`+idProfesor+`)">Carreras</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Vincularme a una Carrera</li>
                </ol>
              </nav>`;
  let titulo = `<h1><i>&nbsp;Carreras</i></h1>
                <h3>&nbsp;Vincularme a Carrera</h3>`;

  $("#breadcrumb").html(bread);
  $("#titulo").html(titulo);                

  $.get("./html/cargarCarrera.html",function(datos){
    $("#resultado").html(datos);
        $.post("../funciones/getAllCarreras.php", function(datos_carreras) {
          if (datos_carreras.codigo==100) {
              let obj = datos_carreras.data; 
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

//*************************************************************
//DESVINCULA EL PROFESOR DE UNA CARRERA ESPECIFICADA ESPECIFICA
//*************************************************************
function desvincularCarrera(idCarrera,idProfesor) {
  let parametros = {"carrera":idCarrera, "profesor":idProfesor};
  if (confirm("Desea desvincularse de la Carrera?")) {
      $.post("../funciones/deleteCarreraProfesor.php",parametros,function (datos) {
         if (datos.codigo==100) {
            cargarCarreras("cursado", idProfesor)
         } else {
            $("#controles").html(`<div class="alert alert-dark" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
            `+datos.data+`</span></i>
             </div>`);
         }
      },"json");
  };

}

//*************************************************************
//VINCULA EL PROFESOR A UNA CARRERA ESPECIFICADA ESPECIFICA
//*************************************************************
function cursadoAgregarCarrera() {
  let idCarrera = $("#inputAltaCarrera").val();
  let idProfesor = $("#inputAltaCarreraProfesor").val();
  let parametros;

  if (idCarrera && idProfesor) {
     parametros = {"profesor":idProfesor,"carrera":idCarrera};
     $.post("../funciones/setProfesorEnCarrera.php",parametros, function(datos){
          if (datos.codigo==100) {
            cargarCarreras("cursado", idProfesor);
          } else {
            $("#resultado_carga").html(`<div class="alert alert-dark" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
            `+datos.data+`</span></i>
                                </div>`);
          }
     },"json");
  } else {
     $("#resultado_carga").html(`<div class="alert alert-dark" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                  No ha seleccionado una carrera.</span></i>
                                </div>`);
  }
  
}

