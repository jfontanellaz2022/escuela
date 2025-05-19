//*************************************************************************************
//********************* CARGA LAS CARRERAS QUE TIENE EL ALUMNO ************************
//*************************************************************************************

function cargarCarrerasExamenes(idAlumno) {
    //Activo del Menu la opcion Alumnos
    let titulo;
    let subtitulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Examenes</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>
                        <h3>Seleccione Carrera</h3>`;

  //Remuevo la class que me deshabilita
  $("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "../API/findAllCarrerasPorAlumno.php", parametros, function( response ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $("#resultado").html("");
    response.datos.forEach(carrera => {
    let resul = `<div class="col-md-4">
             <div class="card" style="width: 18rem;">
                     <img src="../assets/img/`+carrera.imagen+`" class="card-img-top">
                     <div class="card-body">
                         <h3 class="card-title">`+carrera.descripcion+`</h3>
                             <h6 class="card-subtitle mb-2 text-muted"></h6>
                             <p class="card-text"></p>
                             <a href="#" class="btn btn-primary btn-block" onclick="cargarInscripcionPorCarrera(`+carrera.id+`,`+idAlumno+`,'`+carrera.descripcion+`')" ><i class="fa fa-check">&nbsp;Ingresar a Inscribirme</i></a>
                     </div>
                   </div>
             </div>`;
        $("#resultado").append(resul);
     });
  },"json");
}


//*********************************************************************************************
//***************** CARGA LA HISTORIA ACADEMICA DEL ALUMNO PARA ESA CARRERA *******************
//*********************************************************************************************
function cargarInscripcionPorCarrera(idCarrera,idAlumno,descripcion) {
  let turno_activo;
      let habilitar_inscripcion_primer_turno = "disabledbutton";
      let habilitar_inscripcion_segundo_turno = "disabledbutton";
      let habilitar_inscripcion_tercer_turno = "disabledbutton";
      let habilitar_inscripcion_mesa_especial_turno = "disabledbutton";
      let habilitar_inscripcion_intermedia_primer_turno = "disabledbutton";
      let habilitar_inscripcion_intermedia_tercer_turno = "disabledbutton";
      let tiene_mesa_especial = "No";
      let bread;
      let subtitulo;
      let titulo;
      let calendario;
      let idCalendario;
      let cantidadLlamados;

      // En el caso que el evento sea mesa especial controlar que el id de alumno en la carrera 
      // permita mesa especial
      tiene_mesa_especial = getMesaEspecialHabilitada(idCarrera,idAlumno);

      bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="menuExamenes.php">Examenes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">`+descripcion+`</li>
                    </ol>
                </nav>`;
      titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>
                        <h3>Inscripciones Activas</h3>`;

      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);                  
       
      turno_activo = getTurnoActivo();
      console.log(turno_activo);
      idCalendario = turno_activo.calendario_id;
      cantidadLlamados = turno_activo.cantidad_llamados;

      if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1005') {
           habilitar_inscripcion_primer_turno = "";
      } else if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1006') {
           habilitar_inscripcion_segundo_turno = "";
      } else if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1007') {
           habilitar_inscripcion_tercer_turno = "";
      } else if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1008' && tiene_mesa_especial=='Si') {
           habilitar_inscripcion_mesa_especial_turno = "";
      } else if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1009') {
           habilitar_inscripcion_intermedia_primer_turno = "";
           
           calendario = getCalendario(1005);
           idCalendario = calendario[0].id;
           cantidadLlamados = 2;
      } else if (turno_activo.habilitado == 'Si' && turno_activo.evento_codigo=='1010') {
           habilitar_inscripcion_intermedia_tercer_turno = "";
           calendario = getCalendario(1007);
           idCalendario = calendario[0].id;
           cantidadLlamados = 2;
      }

      let resul = `<div class="col-md-4 `+habilitar_inscripcion_primer_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion 1er Turno de Ex&aacute;menes</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   <div class="col-md-4 `+habilitar_inscripcion_intermedia_primer_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion Intermedia 1er Turno Ex&aacute;menes</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   <div class="col-md-4 `+habilitar_inscripcion_segundo_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion 2do Turno de Ex&aacute;menes</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   <div class="col-md-4 `+habilitar_inscripcion_tercer_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion 3er Turno de Ex&aacute;menes</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   <div class="col-md-4 `+habilitar_inscripcion_intermedia_tercer_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion Intermedia 3er Turno Ex&aacute;menes</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   <div class="col-md-4 `+habilitar_inscripcion_mesa_especial_turno+`">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3 class="card-title">Inscripcion Mesa de Ex&aacute;men Especial</h3>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>
                                <p class="card-text"><strong>Fechas de Inscripciones:</strong><br> `+turno_activo.fecha_inicio+` al `+turno_activo.fecha_final+`</p>
                                    <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarInscripcionMaterias(`+idCarrera+`,`+idAlumno+`,`+idCalendario+`,`+cantidadLlamados+`,`+turno_activo.evento_codigo+`,'`+descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar a Inscribirme</a>
                            </div>
                          </div>
                   </div>
                   `;
            $("#resultado").html(resul);

}   


function cargarInscripcionMaterias(idCarrera,idAlumno,idCalendario,cantidadLlamados,eventoCodigo,descripcion) {
    let titulo;
    let subtitulo;
    let bread;
    let boton;
    let turno_descripcion;
    let persistir;
    if (eventoCodigo=='1005') {
        turno_descripcion = "Inscripción 1er Turno de Exámenes";
    } else if (eventoCodigo=='1006') {
        turno_descripcion = "Inscripción 2do Turno de Exámenes";
    } else if (eventoCodigo=='1007') {
        turno_descripcion = "Inscripción 3er Turno de Exámenes";
    } else if (eventoCodigo=='1008') {
        turno_descripcion = "Inscripción a Mesa Especial de Exámenes";
    } else if (eventoCodigo=='1009') {
        turno_descripcion = "Inscripción Intermedia 1er Turno Exámenes";
    } else if (eventoCodigo=='1010') {
        turno_descripcion = "Inscripción Intermedia 3er Turno Exámenes";
    };
    subtitulo = turno_descripcion;
    
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="menuExamenes.php">Examenes</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#" onclick="cargarInscripcionPorCarrera(`+idCarrera+`,`+idAlumno+`,'`+descripcion+`')">`+descripcion+`</a></li>
                    <li class="breadcrumb-item active" aria-current="page">`+turno_descripcion+`</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>
                        <h3>Seleccion de Materias para `+subtitulo+`</h3>`;
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);                    
    $.post("./funciones/getMateriasParaRendirPorIdCarrera.php",{"carrera":idCarrera,"alumno":idAlumno,"calendario":idCalendario,"cantidadLlamados":cantidadLlamados}, function(datos) {
            $("#resultado").html(datos);
    })
}


function persistirInscripciones() {
    let parametros = $("#formulario_inscripciones").serialize();
    $.post("./funciones/persistirInscripcion.php",parametros, function(datos) {
        if (datos.codigo==100 || datos.codigo==101) {
            $("#resultado").append(`<div class="alert alert-success col-xs-12 col-sm-12 col-md-12 col-lg-12" role="alert">
                                    <img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;&nbsp;`+datos.mensaje+`
                                </div>`);
            $("#tabla_materias").addClass("disabledbutton");    
        } else {
            $("#resultado").append(`<div class="alert alert-success col-xs-12 col-sm-12 col-md-12 col-lg-12" role="alert">
                                    <img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;`+datos.mensaje+`
                                </div>`);
        }
    }, "json");

}
