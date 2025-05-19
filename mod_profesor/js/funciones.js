//***********************************************
//OBTIENE EL DATOS DE UNA MATERIA SEGUN SU ID
//***********************************************
/*function sacaDatosMateriaPorId(idMateria) {
   var datos = {"materia":idMateria};
   var datos_materia;
   $.ajax({
      url:"../API/findMateriaPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_materia = datos;
      }
});
return datos_materia;
}*/


//***********************************************
//OBTIENE EL NOMBRE DE UNA MATERIA SEGUN SU ID
//***********************************************
function sacaNombreMateriaPorId(idMateria) {
    var datos = {"materia":idMateria};
    var nombre_materia;
    $.ajax({
       url:"./funciones/getMateriaPorId.php",
       type:"POST",
       data: datos,
       dataType : 'json',
       async: false,
       success: function(datos){
          nombre_materia = datos.data[0].nombre;
       }
 });
 return nombre_materia;
}

//*****************************************************
//OBTIENE EL NOMBRE COMPLETO DE UN ALUMNO SEGUN SU ID
//*****************************************************
function sacaNombreAlumnoPorId(idAlumno) {
   var datos = {"alumno":idAlumno};
   var nombre_alumno;
   $.ajax({
      url:"./funciones/getAlumnoPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         nombre_alumno = datos.data[0].apellido+', '+datos.data[0].nombre+' ('+datos.data[0].dni+')';
      }
});
return nombre_alumno;
}

//***********************************************************************
//OBTIENE LOS DATOS NECESARIOS DE UN ALUMNO PARA EL LISTADO DEL CURSADO
//***********************************************************************
function sacaDatosAlumnoParaCursadoPorId(idAlumno,idMateria) {
   var datos = {"alumno":idAlumno, "materia":idMateria};
   var datos_alumno;
   $.ajax({
      url:"./funciones/getAlumnoCursandoPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_alumno = datos;
      }
});
return datos_alumno;
}

//***********************************************************************
//OBTIENE LOS DATOS NECESARIOS DE UN ALUMNO PARA EL LISTADO DEL EXAMEN
//***********************************************************************
function sacaDatosAlumnoParaExamenPorId(idAlumno,idMateria) {
   var datos = {"alumno":idAlumno, "materia":idMateria};
   var datos_alumno;
   $.ajax({
      url:"./funciones/getAlumnoCursandoPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_alumno = datos;
      }
});
return datos_alumno;
}

//***********************************************
// DETERMINA SI UN EVENTO ESTA O NO HABILITADO
//***********************************************
function getEventoHabilitadoPorCodigo(codigo) {
   var datos = {"codigo":codigo};
   var habilitado;
   $.ajax({
      url:"./funciones/getFechasEventoPorCodigo.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         habilitado = datos.habilitado;
      }
});
return habilitado;
}

//***********************************************
// RETORNA EL EVENTO CON TODOS SUS DATOS
//***********************************************
function getDatosEventoPorCodigo(codigo) {
   var datos = {"codigo":codigo};
   var evento;
   $.ajax({
      url:"./funciones/getFechasEventoPorCodigo.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
});
return evento;
}


//***********************************************
// RETORNA EL NOMBRE DE UNA CARRERA 
//***********************************************
function getCarreraNombrePorId(idCarrera) {
   var datos = {"carrera":idCarrera};
   var nombre;
   $.ajax({
      url:"./funciones/getCarrerasPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         nombre = datos.data[0].descripcion;
      }
});
return nombre;
}


//****************************************************
// RETORNA EL SI UNA MATERIA TIENE ALUMNOS QUE RINDAN 
//****************************************************
function getAlumnosRindiendoPorMateriaId(idTurno,idMateria,llamado_nro) {
   var datos = {"materia":idMateria, "calendario":idTurno, "llamado":llamado_nro};
   var hay_alumnos;
   $.ajax({
      url:"./funciones/getAlumnosRindiendoPorMateria.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         if (datos.codigo==100) {
            hay_alumnos = "Si";
         } else {
            hay_alumnos = "No";
         }
      }
});
return hay_alumnos;
}

//****************************************************
// RETORNA EL SI UNA MATERIA TIENE ALUMNOS QUE RINDAN 
//****************************************************
function getFechasExamenMateria(idTurno,idMateria,llamado_nro) {
   var datos = {"materia":idMateria, "calendario":idTurno, "llamado":llamado_nro};
   var hay_alumnos;
   $.ajax({
      url:"./funciones/getFechaExamenMateriaPorIdTurnoLlamado.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         if (datos.codigo==100) {
            fecha = {'fecha':datos.data[0].fechaExamen};
            //console.log(datos.data[0].fechaExamen);
         } else {
            fecha = {'fecha':null};
         }
      }
});

return fecha;
}

//***********************************************************
// RETORNA EL TURNO DE EXAMENES ACTIVO O EN CURSO SI LO HAY 
//***********************************************************
function getTurnoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let ultima_inscripcion_id = "";
   let datos;
   for (i=1001;i<=1004;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               ultima_inscripcion_id = datos_evento.id_ultima_inscripcion;
               if (i==1001 || i==1003) {
                  cantidad_llamados = 2;
               } else {
                  cantidad_llamados = 1;
               }
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados,"calendario_id_inscripcion_asociada":ultima_inscripcion_id};
   console.log(datos);
   return datos;
}


//***********************************************************
// RETORNA EL LLAMADO ACTIVO DE UN TURNO EXAMENES SI LO HAY 
//***********************************************************
function getLlamadoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let datos;
   for (i=1020;i<=1021;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados};
   return datos;
}

//***********************************************************
// RETORNA EL EVENTO ARMADO DE LISTAS (CURSADO) CON SUS DATOS 
//***********************************************************
function getCursadoActivo() {
   let datos_cursado;
   $.ajax({
      url:"./funciones/getCursadoActivo.php",
      type:"GET",
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_cursado = datos;
      }
});
  return datos_cursado;
} 

function getEstaEnCursadoActivo(datos_cursado,materiaCodigoCursado) {
   let habilitar = 'No';
   datos_cursado.forEach(item => {
         //console.log(item);
         if (item.codigo==materiaCodigoCursado) {
            habilitar = 'Si';
         };
   });
   return habilitar;
   
} 


//***********************************************************
// RETORNA EL EVENTO DE PRESENTACION REGULARIDADES ACTIVO O EN CURSO SI LO HAY 
//***********************************************************
function getTurnoEntregaRegularidadActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id;
   let datos;
   for (i=1011;i<=1012;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               break;
            } else {
               evento_habilitado = 'No';
            }
      };
   };
   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id};
   return datos;
}