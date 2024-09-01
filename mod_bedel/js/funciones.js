//***********************************************************
// RETORNA EL EVENTO ARMADO DE LISTAS (CURSADO) CON SUS DATOS 
//***********************************************************
function getCalendarioPorId(idCalendario) {
   let calendario_id = idCalendario;
   let datos_calendario;
   let parametros = {"calendario":calendario_id};
   $.ajax({
         url:"./funciones/getCalendarioPorId.php",
         type:"POST",
         data: parametros,
         dataType : 'json',
         async: false,
         success: function(datos){
            datos_calendario = datos;
         }
   });
  return datos_calendario;
} 
