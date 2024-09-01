//**********************************************************************
//CARGA LOS LLAMADOS DE UN TURNO PROFESOR SEGUN LA CARRERA SELECCIONADA
//**********************************************************************
function cargarLlamados(carrera,profesor,opcion) {
   let parametros = {"action":"Listar",'carrera':carrera, 'profesor':profesor};
   let cantidad_llamados;
   let carrera_nombre = getCarreraNombrePorId(carrera);
   let turno_activo = getTurnoActivo();
   let llamado_activo = getLlamadoActivo();
   let titulo = '';
   let llamados;
   let llamado1_habilitado = "disabledbutton";
   let llamado2_habilitado = "disabledbutton";
   //Remuevo la class que me deshabilita
   $("#resultado").removeClass("disabledbutton");
   titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Llamados</strong> (Llamados del Turno)</h3>';
   
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('`+opcion+`',`+profesor+`)">Carreras</a></li>
                   <li class="breadcrumb-item">`+carrera_nombre+`</li>
                 </ol>
               </nav>`;
   $("#breadcrumb").html(bread);
   $("#titulo").html(titulo);
   
   if (turno_activo.cantidad_llamados==1) {

      
      if (llamado_activo.evento_codigo == 1020) {
         llamado1_habilitado = "";
      };
      llamados=`<div class="card `+llamado1_habilitado+`" style="width:400px">
            <div class="card-body">
            <h4 class="card-title">1er Llamado</h4>
            <p class="card-text"></p>
            <button type="button" name="button" onclick="cargarMateriaPorCarreraFinales(`+carrera+`,`+profesor+`,`+`1)" class="btn btn-info">Seleccionarr</button>
            </div>
            </div>`;
            $("#resultado").html(llamados);//alert('siiiiiiii');
   } else {
      if (llamado_activo.evento_codigo == 1020) {
         llamado1_habilitado = "";
      };
      if (llamado_activo.evento_codigo == 1021) {
         llamado2_habilitado = "";
      };
      llamados=`<div class="card `+llamado1_habilitado+`" style="width:400px">
            <div class="card-body">
            <h4 class="card-title">1er Llamado</h4>
            <p class="card-text"></p>
            <button type="button" name="button" onclick="cargarMateriaPorCarreraFinales(`+carrera+`,`+profesor+`,`+`1)" class="btn btn-info">Seleccionar</button>
            </div>
            </div> 
            &nbsp;&nbsp;
            <div class="card `+llamado2_habilitado+`" style="width:400px">
            <div class="card-body">
               <h4 class="card-title">2do Llamado</h4>
               <p class="card-text"></p>
               <button type="button" name="button" onclick="cargarMateriaPorCarreraFinales(`+carrera+`,`+profesor+`,`+`2)" class="btn btn-info">Seleccionar</button>
            </div>
            </div>`;
      $("#resultado").html(llamados);      
   }
   
};

//****************************************************************
//CARGA LAS MATERIAS DEL PROFESOR SEGUN LA CARRERA SELECCIONADA
//***************************************************************
function cargarMateriaPorCarreraFinales(carrera,profesor,llamado) {
    
   let turno_activo = getTurnoActivo();
   let habilitar_materia;
   let parametros = {"action":"Listar",'carrera':carrera, 'profesor':profesor};
   let carrera_nombre = getCarreraNombrePorId(carrera);
   console.info('parametros: '+parametros)
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Notas Examenes)</h3>';
   let bread = `<nav aria-label="breadcrumb" role="navigation">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('`+opcion+`',`+profesor+`)">Carreras</a></li>
                   <li class="breadcrumb-item"><a href="#" onclick="cargarLlamados('`+carrera+`',`+profesor+`,'examenes')">`+carrera_nombre+`</a></li>
                   <li class="breadcrumb-item">Llamado `+llamado+`</li>
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
                   //alert(turno_activo.calendario_id_inscripcion_asociada);
                   habilitar_materia = getAlumnosRindiendoPorMateriaId(turno_activo.calendario_id_inscripcion_asociada,materia.id,llamado); 
                   let fecha_examen = getFechasExamenMateria(turno_activo.calendario_id_inscripcion_asociada,materia.id,llamado);

                   if (habilitar_materia=='Si') {     
                        filas += `
                                    <tr><td>`+materia.nombre+` <strong>(`+materia.id+`) </strong><br><strong>Fecha Exámen: </strong>`+fecha_examen.fecha+`</td><td>`+materia.anio+`</td>`+
                                    `<td>`+materia.descripcion_formato+`</td>`+
                                    `<td>`+materia.descripcion_cursado+`</td>`+
                                    `<td>`+
                                    `      <a href="#" class="linkAlumnos" onclick="cargarAlumnosExamen(`+carrera+`,`+materia.id+`,`+profesor+`,`+llamado+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
                                    `</td></tr>
                                    `;
                  } else {
                        filas += `
                              <tr class="disabledbutton"><td>`+materia.nombre+` <strong>(`+materia.id+`)**</strong></td><td>`+materia.anio+`</td>`+
                              `<td>`+materia.descripcion_formato+`</td>`+
                              `<td>`+materia.descripcion_cursado+`</td>`+
                              `<td>`+
                              `      <a href="#" class="linkAlumnos" data-idcursado= "`+materia.idCursado+
                                    `" data-idprofesor="`+profesor+`" data-idcarrera="`+carrera+
                                    `" data-idmateria="`+materia.id+`" data-nombremateria="`+materia.nombre+
                                    `" onclick="cargarAlumnosExamen(`+carrera+`,`+materia.id+`,`+profesor+`,`+llamado+`)" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
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

//**********************************************************************************************************
//OBTIENE EL LISTADO DE LOS ALUMNOS DE UNA MATERIA DADA DEL PROFESOR EN FUNCION DE LA OPCION SELECCIONADA
//**********************************************************************************************************

function cargarAlumnosExamen(idCarrera, idMateria, idProfesor, llamado) {
   let datos_materia;
   let materia_nombre;
   let materia_cursado;
   //let materia_formato;
   datos_materia = sacaDatosMateriaPorId(idMateria);
   if (datos_materia.codigo==100) {
      materia_cursado = datos_materia.data[0].cursado_codigo;
      materia_nombre = datos_materia.data[0].nombre;
      //materia_formato = datos_materia.data[0].formato_codigo;
   } else {
      console.log('ERROR: Hubo un error con los datos de la Materia');
   }
  
      cargarAlumnosPorMateriaExamenes(idCarrera,idMateria,materia_nombre,idProfesor,materia_cursado,llamado);
};


//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA FINALES
//************************************************************************
function cargarAlumnosPorMateriaExamenes(carrera_id, materia_id,materia_nombre,profesor_id,materia_cursado,llamado) {
   let datos_evento;
   let evento_codigo;
   let evento_habilitado = 'No';
   let calendario_id;
   let llamado_nro = llamado;
   let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong> (Ingresar Notas de Ex&aacute;men Final) - Llamado '+llamado_nro+'</h3>';
   let boton_agregar_alumno = '';  
   let accion_editar = '<a href="#" class="disabledbutton" title="Editar Datos del Final"><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
   let carrera_nombre = getCarreraNombrePorId(carrera_id);
 
   // Determina cual Evento de Examenes esta habilitado o no.
   datos_evento = getTurnoActivo();
   if (datos_evento.habilitado=='Si') {
      evento_habilitado = 'Si';
      evento_codigo = datos_evento.evento_codigo;
      calendario_id = datos_evento.calendario_id_inscripcion_asociada;
   } else {
      evento_habilitado = 'No'
   }
    
   let parametros = {"materia":materia_id, "calendario":calendario_id, "llamado":llamado_nro};
   $.post( "../funciones/getAlumnosRindiendoPorMateria.php", parametros, function( data ) {
        let obj = JSON.parse(data);
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
                   <table class="table table-striped">
                      <thead class="thead-green">
                         <tr>
                            <th>APELLIDO Y NOMBRE</th><th>EMAIL</th>
                            <th>DNI</th>
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
      
      boton_agregar_notas = '<button class="btn btn-info" id="btnCargarNota" data-idcarrera="'+carrera_id+'" data-idmateria="'+materia_id+'">Cargar Nota</button>';
      
      if (obj.codigo==100) {
                  obj.data.forEach(alumno => {
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
                                      <td>`+alumno.apellido+', '+alumno.nombre+` <strong>(`+alumno.id+`)</strong></td>`+
                                      `<td><a href="mailto:`+alumno.email+`">`+alumno.email+`</a></td>`+
                                      `<td>`+alumno.dni+`</td>`+
                                      `<td><span class='badge badge-info'>`+nota_examen_final+`</span></td>`+
                                      `<td><span class="badge `+color+`">`+alumno.estado_final+`</span></td>`+
                                  `</tr>
                                  `;
                  });
         };/* else {
            nofilas = `
                        <div class="alert alert-dark" role="alert" id="nofilas">
                        <b><img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;"><i>No Existen Alumnos en la Materia.</i></span>
                        </div>
                     `;
         };*/

         
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
                                                <button type="button" class="btn btn-success btn-sm" onclick="finalesAgregarNotaAlumno(`+materia_id+`,`+carrera_id+`,`+calendario_id+`,`+llamado_nro+`,`+profesor_id+`)">
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
         /*if (nofilas!='') {
            $("#resultado_carga").removeClass('d-none'); 
            $("#resultado_carga").html(nofilas);
         };   */
         
         //CARGA EL SELECT2 CON LOS ALUMNOS DE LA MATERIA
         $.post( "../funciones/getAlumnosRindiendoPorMateria.php",parametros, function( data_alumnos ) {
            let obj = JSON.parse(data_alumnos);
            if (obj.codigo==100) {
                obj.data.forEach(alumno => {
                      $("#inputFinalesAltaAlumno").append($('<option/>', {
                            text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                            value: alumno.id,
                      }));
                });
            };     
         });   
         $('#inputFinalesAltaAlumno').select2();
         //CARGA EL SELECT2 CON LOS DATOS DE NOTA Y ESTADO FINAL
         $('#inputFinalesAltaNota').select2();
         $('#inputFinalesAltaEstadoFinal').select2();
         if (evento_habilitado=='No') {
            $("#resultado").addClass("disabledbutton");
         }
   });
 }
 


//****************************************************************************
//CARGA LA NOTA DE UN EXAMEN A UN ALUMNO EN UNA MATERIA ESPECIFICADA
//****************************************************************************
function finalesAgregarNotaAlumno(idMateria,idCarrera,idCalendario,llamado,idProfesor) {
   let alumno = $('#inputFinalesAltaAlumno').val();
   let nota = $('#inputFinalesAltaNota').val()
   let estado_final = $('#inputFinalesAltaEstadoFinal').val()
   let parametros = {"materia":idMateria, "alumno":alumno, "calendario":idCalendario, "llamado":llamado, "nota":nota, "estadoFinal": estado_final};
   $.post( "../funciones/setNotasExamenFinal.php", parametros, function( data ) {
            let obj = JSON.parse(data);
            $("#resultado_carga").removeClass("d-none");
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                                           <b><img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+obj.data+`</i></span>
                                            </div>`);
                  cargarAlumnosExamen(idCarrera, idMateria, idProfesor, llamado);
            } else {
                $("#resultado_carga").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+obj.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div>`);
            }
    });
}
