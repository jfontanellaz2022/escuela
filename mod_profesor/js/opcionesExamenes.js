$("body").on("click","#btnPrimerLlamado",function(){
   //alert('Si');
   opcion = $(this).data('opcion');
   opcion = 'examenes';
   llamado_numero = 1;
   console.log(opcion);
   /*let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Llamado del Turno de Ex&aacute;men</h3><hr>';
   $.get( "html/cargarLlamadosTurno.html", function( data ) {
       $("#resultado").html(data);
       $("#btnPrimerLlamado").prop("disabled",false);
       $("#btnSegundoLlamado").prop("disabled",false);
   });*/
    //$("#titulo").html(titulo);
   cargarMateriaPorCarrera(carrera_id,profesor_id)
})

$("body").on("click","#btnSegundoLlamado",function(){
    opcion = $(this).data('opcion');
    opcion = 'examenes';
    llamado_numero = 2;
    /*let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Llamado del Turno de Ex&aacute;men</h3><hr>';
    $.get( "html/cargarLlamadosTurno.html", function( data ) {
        $("#resultado").html(data);
        $("#btnPrimerLlamado").prop("disabled",false);
        $("#btnSegundoLlamado").prop("disabled",false);
    });*/
     //$("#titulo").html(titulo);
    cargarMateriaPorCarrera(carrera_id,profesor_id)
 })
