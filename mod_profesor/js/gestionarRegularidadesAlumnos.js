//****************************************************************
//CARGA LAS MATERIAS DEL PROFESOR SEGUN LA CARRERA SELECCIONADA
//***************************************************************
function cargarMateriaPorCarreraRegularidades(carrera,profesor) {
   let entrega_regularidades = getTurnoEntregaRegularidadActivo();
   let habilitar_materia;
   let evento_habilitado = entrega_regularidades.habilitado;
   let evento_codigo = entrega_regularidades.evento_codigo;

   let parametros = {"action":"Listar",'carrera':carrera, 'profesor':profesor};
   let carrera_nombre = getCarreraNombrePorId(carrera);
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Notas Examenes)</h3>';
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('regularidades',`+profesor+`)">Carreras</a></li>
                   <li class="breadcrumb-item">`+carrera_nombre+`</li>
                 </ol>
               </nav>`;
   $("#breadcrumb").html(bread);
   $.post( "../funciones/getMateriasPorCarrera.php", parametros, function( data ) {
      let obj = JSON.parse(data);
      $("#resultado").html("");
      let tabla_comienzo = `
                   <table class="table table-striped">
                      <thead class="thead-red">
                         <tr><th>MATERIA</th><th>AÑO</th><th>FORMATO</th><th>CURSADO</th><th>ACCIONES</th></tr>
                      </thead>
                      <tbody>
                    `;
      let filas = ``;
      let tabla_final  = `   </tbody>
                         </table>`;
      if (obj.codigo==100) {
              obj.data.forEach(materia => {
                   if (evento_habilitado=='Si' && evento_codigo=='1011' && materia.codigoCursado=='01') {     
                        filas += `
                                    <tr><td>`+materia.nombre+` <strong>(`+materia.id+`)</strong></td><td>`+materia.anio+`</td>`+
                                    `<td>`+materia.descripcion_formato+`</td>`+
                                    `<td>`+materia.descripcion_cursado+`</td>`+
                                    `<td>`+
                                    `      <a href="#" class="linkAlumnos" onclick="cargarAlumnosPorMateriaRegularidad(`+carrera+`,`+materia.id+`,'`+materia.nombre+`',`+profesor+`,`+evento_codigo+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
                                    `</td></tr>
                                    `;
                  } else if (evento_habilitado=='Si' && evento_codigo=='1012' && (materia.codigoCursado=='02' || materia.codigoCursado=='03')) {     
                        filas += `
                                    <tr><td>`+materia.nombre+` <strong>(`+materia.id+`)</strong></td><td>`+materia.anio+`</td>`+
                                    `<td>`+materia.descripcion_formato+`</td>`+
                                    `<td>`+materia.descripcion_cursado+`</td>`+
                                    `<td>`+
                                    `      <a href="#" class="linkAlumnos" onclick="cargarAlumnosPorMateriaRegularidad(`+carrera+`,`+materia.id+`,'`+materia.nombre+`',`+profesor+`,`+evento_codigo+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
                                    `</td></tr>
                                    `;
                  } else {
                        filas += `
                              <tr class="disabledbutton"><td>`+materia.nombre+` <strong>(`+materia.id+`)</strong></td><td>`+materia.anio+`</td>`+
                              `<td>`+materia.descripcion_formato+`</td>`+
                              `<td>`+materia.descripcion_cursado+`</td>`+
                              `<td>`+
                              `      <a href="#" class="linkAlumnos" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
                              `</td></tr>
                              `;

                  }             
               });
      } else {
             filas = `
                      <tr><td>No Existen Materias Asociadas.</td></tr>
                     `;
             }
       $("#resultado").html(tabla_comienzo+filas+tabla_final);
    }); 

}


//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA REGULARIDAD
//************************************************************************
function cargarAlumnosPorMateriaRegularidad(carrera_id,materia_id,materia_nombre,profesor_id,materia_cursado,evento_codigo) {
   /*carrera = carrera_id;
   profesor = profesor_id;
   materia_id = materia;
   materia_nombre = nombremateria;
   let titulo = '<h1><i>'+materia_nombre+'</i></h1><h3><strong>Alumnos</strong> (Cargar Regularidades)</h3><hr>';
*/
   let datos_evento;
   //let evento_codigo=evento_codigo;
   let evento_habilitado;
   let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong> (Crear Regularidades)</h3>';
   let boton_agregar_alumno = '';  
   let accion_editar = '<a href="#" title="Editar Datos de la Regularidad"><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
   let carrera_nombre = getCarreraNombrePorId(carrera_id);
 
   /*if (materia_cursado == '01') {
      evento_codigo = 1009;
   } else if (materia_cursado == '02' || materia_cursado == '03') {
      evento_codigo = 1010;
   };*/

   // Determina si el Evento de Carga de Regularidades de Materias de un Cuatrimestre dado esta habilitado o no.
   datos_evento = getDatosEventoPorCodigo(evento_codigo);
   if (datos_evento.codigo==100) {
      if (datos_evento.habilitado=='Si') {
         evento_habilitado = 'Si';
      } else {
         evento_habilitado = 'No';
      }
   } else {
      console.log('ERROR: Hubo un error con los datos del Evento '+evento_codigo);
   };
 
   let parametros = {"action":"Listar",'materia':materia_id};
      $.post( "../funciones/getAlumnosCursandoPorMateria.php", parametros, function( data ) {
        let obj = JSON.parse(data);
        let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('regularidades',`+profesor_id+`)">Carreras</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarreraRegularidades(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                      <li class="breadcrumb-item">`+materia_nombre+`</li>
                    </ol>
                  </nav>`;
 
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      $("#resultado").html("");
      // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
      let tabla_comienzo = `
                   <table class="table table-striped">
                      <thead class="thead-green">
                         <tr>
                            <th>APELLIDO Y NOMBRE</th><th>EMAIL</th>
                            <th>DNI</th><th>CURSADO</th>
                            <th>NOTA</th><th>ESTADO</th>
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
      
      boton_agregar_notas = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';
      
      if (obj.codigo==100) {
                  obj.data.forEach(alumno => {
                        let nota_cursado = 0;
                        if (alumno.cursado=='Libre') {
                              nota_cursado = '*Sin Nota*';
                        } else {
                              if (alumno.nota=='-1') {
                                    nota_cursado = '*Sin Nota*';
                              } else {
                                    nota_cursado = alumno.nota;
                              };
                          
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
                                  <tr>
                                      <td>`+alumno.apellido+', '+alumno.nombre+` <strong>(`+alumno.id+`)</strong></td>`+
									  `<td><a href="mailto:`+alumno.email+`">`+alumno.email+`</a></td>`+
                                      `<td>`+alumno.dni+`</td>`+
                                      `<td>`+alumno.cursado+`</td>`+
                                      `<td><span class='badge badge-secondary'>`+nota_cursado+`</span></td>`+
                                      `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                  `</tr>
                                  `;
                  });
         } else {
            nofilas = `
                        <div class="alert alert-dark" role="alert" id="nofilas">
                        <b><img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;"><i>No Existen Alumnos en la Materia.</i></span>
                        </div>
                     `;
         };

         
         let tabla_agregar_alumno = `  
                                    <div class="col-xs-12 col-sm-12 col-md-12" style="background-color: #E9D5B4;border-radius: 10px;">
                                       <div class="row" style="padding: 10px;"> 
                                          <div class="col-xs-12 col-sm-4 col-md-4 col-md-4">
                                             <select id="inputRegularidadesAltaAlumno" class="form-control">
                                                <option value="">-- Alumno --</option>
                                             </select>
                                          </div>
                                          <div class="col-xs-12 col-sm-3 col-md-3">
                                                <select id="inputRegularidadesAltaNota" class="form-control">
                                                   <option value="">-- Nota --</option>
                                                   <option value="-1">Ausente</option>
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
                                                <select id="inputRegularidadesAltaEstadoFinal" class="form-control">
                                                   <option value="">-- Estado --</option>
                                                   <option value="Cursando" disabled>Cursando</option>
                                                   <option value="Regularizo">Regularizo</option>
                                                   <option value="Promociono">Promociono</option>
                                                   <option value="Aprobo">Aprobo</option>
                                                   <option value="Libre">Libre</option>
                                                   <option value="Suspenso" disabled>Suspenso</option>
                                                </select>
                                          </div>
                                       
                                          <div class="col-xs-12 col-sm-2 col-md-2">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="regularidadesAgregarNotaAlumno(`+materia_id+`,`+carrera_id+`,`+profesor_id+`)">
                                                    Guardar
                                                </button>
                                          </div>
                                                
                                       </div>
                                       <div class="row">
                                          <div id="resultado_carga" class="col-xs-12 col-md-12 d-none"> </div>
                                       </div>
                                    </div>
                                    `;                       
         $("#resultado").html(tabla_agregar_alumno+'<br>');    
         $("#resultado").append(tabla_comienzo+filas+tabla_final);
         if (nofilas!='') {
            $("#resultado_carga").removeClass('d-none'); 
            $("#resultado_carga").html(nofilas);
         };   
         
         //CARGA EL SELECT2 CON LOS ALUMNOS DE LA MATERIA
         let p = {"materia":materia_id,"sinLibres":true};
         console.log(p);
         $.post( "../funciones/getAlumnosCursandoPorMateria.php", {"materia":materia_id,"sinLibres":true}, function( data_alumnos ) {
            data_alumnos.data.forEach(alumno => {
                  $("#inputRegularidadesAltaAlumno").append($('<option/>', {
                        text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                        value: alumno.id,
                  }));
            });
         }, "json");   
         $('#inputRegularidadesAltaAlumno').select2();
         //CARGA EL SELECT2 CON LOS DATOS DE NOTA Y ESTADO FINAL
         $('#inputRegularidadesAltaNota').select2();
         $('#inputRegularidadesAltaEstadoFinal').select2();
         if (evento_habilitado=='No') {
            $("#resultado").addClass("disabledbutton");
         }
   });
 }
 

 //****************************************************************************
//CARGA LA NOTA A UN ALUMNO EN UNA MATERIA ESPECIFICADA
//****************************************************************************
function regularidadesAgregarNotaAlumno(idMateria,idCarrera,idProfesor) {
   let alumno = $('#inputRegularidadesAltaAlumno').val();
   let nota = $('#inputRegularidadesAltaNota').val()
   let estado_final = $('#inputRegularidadesAltaEstadoFinal').val()
   $.post( "../funciones/setNotasCursado.php", {"materia":idMateria, "alumno":alumno, "nota":nota, "estadoFinal": estado_final}, function( data ) {
            let obj = JSON.parse(data);
            $("#resultado_carga").removeClass("d-none");
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                                           <b><img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+obj.data+`</i></span>
                                            </div>`);
                let datos_alumno = sacaDatosAlumnoParaCursadoPorId(alumno,idMateria); 
                if (datos_alumno.codigo==100) {
                  cargarAlumnos(idCarrera, idMateria, idProfesor, 'regularidades'); 
                 
                } else {
                  $("#resultado_carga").html(`<div class="alert alert-secondary" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                  No se pudo recuperar los datos del Alumno.</span></i>
                   </div>`);
                }                                            
            } else {
                $("#resultado_carga").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+obj.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div>`);
            }
    });
}
