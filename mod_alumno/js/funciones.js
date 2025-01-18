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
console.info(evento)
return evento;
}


//***********************************************************
// RETORNA EL TURNO DE EXAMENES ACTIVO O EN CURSO SI LO HAY 
//***********************************************************
function getTurnoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let fecha_inicio = "";
   let fecha_final = "";
   let datos;
   for (i=1005;i<=1010;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               fecha_inicio = datos_evento.data[0].fechaInicioEvento;
               fecha_final = datos_evento.data[0].fechaFinalEvento;
               if (i==1005 || i==1007) {
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

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados, "fecha_inicio":fecha_inicio, "fecha_final":fecha_final};
   return datos;
}


//************************************************************************
// RETORNA SI O NO UN ALUMNO TIENE MESA ESPECIAL HABILITADA EN LA CARRERA 
//************************************************************************
function getMesaEspecialHabilitada(idCarrera,idAlumno) {
   var datos = {"carrera":idCarrera, "alumno":idAlumno};
   var mesa_especial_habilitada;
   $.ajax({
      url:"./funciones/getMesaEspecialHabilitada.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         if (datos.codigo==100) {
            mesa_especial_habilitada = datos.data;
         } else {
            mesa_especial_habilitada = "No";
         }
      }
});
return mesa_especial_habilitada;
}

//*************************************************************************************************************************
// RETORNA EL IDCALENDARIO DE INSCRIPCION A UN TURNO A EXAMENES CON 2 LLAMADOS PARA LA INSCRIPCION INTERMEDIA POR CODIGO
//*************************************************************************************************************************

function getCalendario(codigo) {
   var datos = {"codigo":codigo};
   var datos_calendario;
   $.ajax({
      url:"./funciones/getCalendarioPorCodigoEvento.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         if (datos.codigo==100) {
            datos_calendario = datos.data;
         } else {
            datos_calendario = "";
         }
      }
});
return datos_calendario;
};