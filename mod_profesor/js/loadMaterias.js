

/*
function cargarMateriaPorCarreraFinales(carrera,profesor) {
   let turno_activo = getTurnoActivo();
   let habilitar_materia;

   let parametros = {"action":"Listar",'carrera':carrera, 'profesor':profesor};
   let carrera_nombre = getCarreraNombrePorId(carrera);
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3><strong>Materias</strong> (Cargar Notas Examenes)</h3>';
   $.post( "../funciones/getMateriasPorCarrera.php", parametros, function( data ) {
      let obj = JSON.parse(data);
      $("#resultado").html("");
      let tabla_comienzo = `
                   <table class="table table-striped">
                      <thead>
                         <tr><th>MATERIA</th><th>AÑO</th><th>FORMATO</th><th>CURSADO</th><th>ACCIONES</th></tr>
                      </thead>
                      <tbody>
                    `;
      let filas = ``;
      let tabla_final  = `   </tbody>
                         </table>`;
      if (obj.codigo==100) {
              obj.data.forEach(materia => {
                   habilitar_materia = getAlumnosRindiendoPorMateriaId(turno_activo.calendario_id,materia.id,1); // llamado harcodeado
                   
                   if (habilitar_materia=='Si') {     
                        filas += `
                                    <tr><td>`+materia.nombre+` <strong>(`+materia.id+`)</strong></td><td>`+materia.anio+`</td>`+
                                    `<td>`+materia.descripcion_formato+`</td>`+
                                    `<td>`+materia.descripcion_cursado+`</td>`+
                                    `<td>`+
                                    `      <a href="#" class="linkAlumnos" data-idcursado= "`+materia.idCursado+
                                          `" data-idprofesor="`+profesor+`" data-idcarrera="`+carrera+
                                          `" data-idmateria="`+materia.id+`" data-nombremateria="`+materia.nombre+
                                          `" onclick="cargarAlumnos(`+carrera+`,`+materia.id+`,`+profesor+`,'examenes')" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
                                    `</td></tr>
                                    `;
                  } else {
                        filas += `
                              <tr class="disabledbutton"><td>`+materia.nombre+` <strong>(`+materia.id+`)</strong></td><td>`+materia.anio+`</td>`+
                              `<td>`+materia.descripcion_formato+`</td>`+
                              `<td>`+materia.descripcion_cursado+`</td>`+
                              `<td>`+
                              `      <a href="#" class="linkAlumnos" data-idcursado= "`+materia.idCursado+
                                    `" data-idprofesor="`+profesor+`" data-idcarrera="`+carrera+
                                    `" data-idmateria="`+materia.id+`" data-nombremateria="`+materia.nombre+
                                    `" onclick="cargarAlumnos(`+carrera+`,`+materia.id+`,`+profesor+`,'`+opcion+`')" data-toggle="tooltip" data-placement="bottom" title="Listado de Alumnos"><img src="../assets/img/icons/listado_icon.png" width="25"></a>`+
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

}*/