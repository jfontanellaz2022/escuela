//************************************************************************
// CARGA LISTADO DE ALUMNOS CON DATOS DE LA FINALES
//************************************************************************
function cargarAlumnosPorMateriaExamenes(carrera_id, materia_id,materia_nombre,profesor_id,materia_cursado) {
   let datos_evento;
   let evento_codigo;
   let evento_habilitado = 'No';
   let calendario_id;
   let llamado_nro;
   let titulo = '<h1><i>'+materia_nombre+'</h1></i><h3><strong>Alumnos</strong> (Ingresar Notas de Ex&aacute;men Final)</h3>';
   let boton_agregar_alumno = '';  
   let accion_editar = '<a href="#" class="disabledbutton" title="Editar Datos del Final"><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
   let carrera_nombre = getCarreraNombrePorId(carrera_id);
 
   /*if (materia_cursado == '01') {
      evento_codigo = 1008;
   } else if (materia_cursado == '02' || materia_cursado == '03') {
      evento_codigo = 1009;
   };*/

   // Determina cual Evento de Examenes esta habilitado o no.
   datos_evento = getTurnoActivo();
   if (datos_evento.habilitado=='Si') {
      evento_habilitado = 'Si';
      evento_codigo = datos_evento.evento_codigo;
      calendario_id = datos_evento.calendario_id;
   } else {
      evento_habilitado = 'No'
   }
   alert('siii');
   llamado_nro = 1; // HARDCODEADOOOOOO
   let parametros = {"materia":materia_id, "calendario":calendario_id, "llamado":llamado_nro};
   $.post( "../funciones/getAlumnosRindiendoPorMateria.php", parametros, function( data ) {
        let obj = JSON.parse(data);
        let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras('examenes',`+profesor_id+`)">Carreras</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`,'examenes')">`+carrera_nombre+`</a></li>
                      <li class="breadcrumb-item">`+materia_nombre+`</li>
                    </ol>
                  </nav>`;
 
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      $("#resultado").html("");
      // ENCABEZADO DE LA TABLA QUE SE VA A CREAR
      let tabla_comienzo = `
                   <table class="table table-striped">
                      <thead>
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
      let nofilas = ``;
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
         } else {
            nofilas = `
                        <div class="alert alert-dark" role="alert" id="nofilas">
                        <b><img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;"><i>No Existen Alumnos en la Materia.</i></span>
                        </div>
                     `;
         };

         
         let tabla_agregar_alumno = `  
                                    <table class="table bg-info">
                                        <tr>
                                          <td width="40%">
                                             <select id="inputFinalesAltaAlumno" class="form-control">
                                                <option value="">-- Alumno --</option>
                                             </select>
                                          </td>
                                          <td width="20%">
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
                                          </td>
                                          <td width="20%">
                                             <select id="inputFinalesAltaEstadoFinal" class="form-control">
                                                <option value="">-- Estado --</option>
                                                <option value="Ausente">Ausente</option>
                                                <option value="Aprobo">Aprobo</option>
                                                <option value="Desaprobo">NO Aprobo</option>
                                             </select>
                                          </td>
                                          <td width="20%">
                                             <button type="button" class="btn btn-sm" onclick="finalesAgregarNotaAlumno(`+materia_id+`,`+carrera_id+`,`+calendario_id+`,`+llamado_nro+`,`+profesor_id+`)">
                                                <img src="../assets/img/icons/save_icon.png" width="22">&nbsp; Guardar
                                             </button>
                                          </td>
                                        </tr>  
                                    </table>
                                    <div id="resultado_carga" class="col-xs-12 col-md-12 bg-light d-none"> 
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
   //alert(alumno+','+nota+','+estado_final);
   let parametros = {"materia":idMateria, "alumno":alumno, "calendario":idCalendario, "llamado":llamado, "nota":nota, "estadoFinal": estado_final};
   $.post( "../funciones/setNotasExamenFinal.php", parametros, function( data ) {
            let obj = JSON.parse(data);
            $("#resultado_carga").removeClass("d-none");
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                                           <b><img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+obj.data+`</i></span>
                                            </div>`);
                //let datos_alumno = sacaDatosAlumnoParaCursadoPorId(alumno,idMateria); 
                //if (datos_alumno.codigo==100) {
                  //console.log(datos_alumno.data[0].apellido);
                  cargarAlumnos(idCarrera, idMateria, idProfesor, 'examenes'); 
                 
               /* } else {
                  $("#resultado_carga").html(`<div class="alert alert-secondary" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                  No se pudo recuperar los datos del Alumno.</span></i>
                   </div>`);
                }*/                                            
            } else {
                $("#resultado_carga").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+obj.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div>`);
            }
    });
}
