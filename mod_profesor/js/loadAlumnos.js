//**********************************************************************************************************
//OBTIENE EL LISTADO DE LOS ALUMNOS DE UNA MATERIA DADA DEL PROFESOR EN FUNCION DE LA OPCION SELECCIONADA
//**********************************************************************************************************

function cargarAlumnos(idCarrera, idMateria, idProfesor, opcion) {
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
   if (opcion=='cursado') {
      cargarAlumnosPorMateriaCursado(idCarrera,idMateria,materia_nombre,idProfesor,materia_cursado);
   } else if (opcion=='regularidades') {
      //cargarAlumnosPorMateriaRegularidad(idMateria,materia_nombre);
      cargarAlumnosPorMateriaRegularidad(idCarrera,idMateria,materia_nombre,idProfesor,materia_cursado);
   } else if (opcion=='examenes') {
      cargarAlumnosPorMateriaExamenes(idCarrera,idMateria,materia_nombre,idProfesor,materia_cursado);
   };
}


//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA REGULARIDAD
//************************************************************************
/*
function cargarAlumnosPorMateriaRegularidad(materia,nombremateria) {
  carrera = carrera_id;
  profesor = profesor_id;
  materia_id = materia;
  materia_nombre = nombremateria;
  let titulo = '<h1><i>'+materia_nombre+'</i></h1><h3><strong>Alumnos</strong> (Cargar Regularidades)</h3><hr>';
  if (duracion_cursado_materia == 1) {
      codigo = 1008;
  } else if (duracion_cursado_materia == 2 || duracion_cursado_materia == 3) {
      codigo = 1009;
  };

  url = '../funciones/getFechasEventoListadoMateriasPorCodigo.php';
    $.post( url, {'codigo':codigo}, function( data ) {
        let obj = JSON.parse(data);
        $("#control_habilitado").html('<h3>HABILITADO: <span id="agregar_notas" data-habilitado="'+obj.habilitado+'">'+obj.habilitado+'</span></h3>');
    });

  let parametros = {"action":"Listar",'materia':materia_id};
     $.post( "../funciones/getAlumnosCursandoPorMateria.php", parametros, function( data ) {
       let obj = JSON.parse(data);
       let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                     <li class="breadcrumb-item">`+materia_nombre+`</li>
                   </ol>
                 </nav>`;

     $("#breadcrumb").html(nav);
     $("#titulo").html(titulo);
     $("#resultado").html("");
     // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
     let tabla_comienzo = `
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>ID</th><th>APELLIDO Y NOMBRE</th>
                           <th>DNI</th><th>CURSADO</th>
                           <th>NOTA</th><th>ESTADO</th>
                           <th>ACCIONES</th>
                        </tr>
                     </thead>
                     <tbody>
                   `;
     // FILAS DE RESULTADO DE LA TABLA
     let filas = ``;
     // FIN DE LA TABLA
     let tabla_final  = `   </tbody>
                        </table>`;
     let boton_cargar_nota_alumno = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';

     if (opcion=='regularidades') {
            accion_editar = '<a href="#" title="Editar Datos de la Regularidad"><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
            boton_agregar_notas = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';
     };

     if (obj.codigo==100) {
                 obj.data.forEach(alumno => {
                       let nota_cursado = 0;
                       if (alumno.cursado=='Libre') {
                          nota_cursado = '*Sin Nota*';
                       } else {
                         nota_cursado = alumno.nota;
                       }
                       let color = '';
                       if (alumno.estado_final=='Libre') {
                          color = 'badge-danger';
                       } else if (alumno.estado_final=='Aprobo' || alumno.estado_final=='Promociono') {
                          color = 'badge-success';
                       } else if (alumno.estado_final=='Regularizo') {
                          color = 'badge-warning';
                       };
                       filas += `
                                 <tr><td>`+alumno.id+`</td>`+
                                     `<td>`+alumno.apellido+', '+alumno.nombre+`</td>`+
                                     `<td>`+alumno.dni+`</td>`+
                                     `<td>`+alumno.cursado+`</td>`+
                                     `<td><span class='badge badge-secondary'>`+nota_cursado+`</span></td>`+
                                     `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                     `<td>`+accion_editar+`</td>`+
                                 `</tr>
                                 `;
                 });
                 if ($("body #agregar_notas").data("habilitado")=='Si') {
                    $("#resultado").html(tabla_comienzo+filas+tabla_final+boton_cargar_nota_alumno);
                 } else {
                   $("#resultado").html(tabla_comienzo+filas+tabla_final);
                 }
        } else {
           $("#resultado").html(`<div class="alert alert-danger" role="alert">
                                  <p class="text-dark"><img src="../assets/img/icons/error_icon.png" width="20">&nbsp;<strong>Atenci&oacute;n:</strong>
                                  &nbsp; <i>No existen Alumnos en &eacute;sta Materia.</i></p>
                                </div>`);
        }

  });
}*/

//************************************************************************
// CARGA LISTADO DE ALUMNOS PARA LA MATERIA QUE RINDE
//************************************************************************
/*
function cargarAlumnosPorMateriaExamenes(materia,nombremateria,llamado) {
  carrera = carrera_id;
  profesor = profesor_id;
  materia_id = materia;
  materia_nombre = nombremateria;
  let titulo = '<h1><i>'+materia_nombre+' <strong>('+materia_id+')</strong></i></h1><h3><strong>Alumnos</strong> (Cargar Notas de Finales)</h3><hr>';
  if (duracion_cursado_materia == 1) {
      codigo = 1008;
  } else if (duracion_cursado_materia == 2 || duracion_cursado_materia == 3) {
      codigo = 1009;
  };

  url = '../funciones/getFechasEventoListadoMateriasPorCodigo.php';
    $.post( url, {'codigo':codigo}, function( data ) {
        let obj = JSON.parse(data);
        $("#control_habilitado").html('<h3>HABILITADO: <span id="agregar_notas" data-habilitado="'+obj.habilitado+'">'+obj.habilitado+'</span></h3>');
    });
  
  let parametros = {"action":"Listar",'materia':materia_id, 'llamado':llamado_numero};
     $.post( "../funciones/getAlumnosRindiendoPorMateria.php", parametros, function( data ) {
       let obj = JSON.parse(data);
       let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                     <li class="breadcrumb-item">`+materia_nombre+`</li>
                   </ol>
                 </nav>`;

     $("#breadcrumb").html(nav);
     $("#titulo").html(titulo);
     $("#resultado").html("");
     // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
     let tabla_comienzo = `
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>APELLIDO Y NOMBRE</th>
                           <th>DNI</th><th>CONDICION</th>
                           <th>NOTA</th><th>ESTADO</th>
                           <th>ACCIONES</th>
                        </tr>
                     </thead>
                     <tbody>
                   `;
     // FILAS DE RESULTADO DE LA TABLA
     let filas = ``;
     // FIN DE LA TABLA
     let tabla_final  = `   </tbody>
                        </table>`;
     let boton_cargar_nota_alumno = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';

     if (obj.codigo==100) {
                 obj.data.forEach(alumno => {
                       let nota = 0;
                       if (opcion=='examenes') {
                        accion_editar = '<a href="#" class="btnNotaExamen" title="Editar Datos del Examen" data-idalumno="'+alumno.id+'" data-idmateria="'+materia_id+'" data-llamado="'+llamado_numero+'"><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
                        //boton_agregar_notas = '<button class="btn btn-info" id="btnCargarNotaExamen" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';
                       };
                       //if (alumno.cursado=='Libre') {
                       //   nota_cursado = '*Sin Nota*';
                       //} else {
                        // nota_cursado = alumno.nota;
                       //}
                       let color = '';
                       if (alumno.estado_final=='Libre') {
                          color = 'badge-danger';
                       } else if (alumno.estado_final=='Aprobo' || alumno.estado_final=='Promociono') {
                          color = 'badge-success';
                       } else if (alumno.estado_final=='Regularizo') {
                          color = 'badge-warning';
                       };
                       filas += `
                                 <tr>`+
                                     `<td>`+alumno.apellido+', '+alumno.nombre+` <strong>(`+alumno.id+`)</strong></td>`+
                                     `<td>`+alumno.dni+`</td>`+
                                     `<td>`+alumno.condicion+`</td>`+
                                     `<td><span class='badge badge-primary'>`+nota+`</span></td>`+
                                     `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                     `<td>`+accion_editar+`</td>`+
                                 `</tr>
                                 `;
                 });
                 $("#titulo").append('<h4><strong>Llamado:</strong> '+obj.llamado+'</h4>'); 
                 //$("#resultado").html(tabla_comienzo+filas+tabla_final+boton_cargar_nota_alumno);
                 if ($("body #agregar_notas").data("habilitado")=='Si') {
                    $("#resultado").html(tabla_comienzo+filas+tabla_final);
                 } else {
                   $("#resultado").html(tabla_comienzo+filas+tabla_final);
                 }
        } else {
           $("#resultado").html(`<div class="alert alert-danger" role="alert">
                                  <p class="text-dark"><img src="../assets/img/icons/error_icon.png" width="20">&nbsp;<strong>Atenci&oacute;n:</strong>
                                  &nbsp; <i>No existen Alumnos en &eacute;sta Materia.</i></p>
                                </div>`);
        }

  });
}

*/