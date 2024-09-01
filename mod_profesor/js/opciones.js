//*******************************************************************
// CARGA LOS BOTONES DE LAS OPCIONES PARA UNA CARRERA SELECCIONADA
//*******************************************************************
$("body").on("click",".carrera", function() {
   let carrera = $(this).data('idcarrera');
   let profesor = $(this).data('idprofesor');
   cargarOpciones(carrera,profesor);
});

//*******************************************************************************
// FUNCION QUE CARGA LOS BOTONES DE LAS OPCIONES PARA UNA CARRERA SELECCIONADA
//*******************************************************************************
function cargarOpciones(carrera,profesor) {
    $.post( "ajax/getCarreraPorId.php", {'carrera':carrera}, function( data ) {
       let obj = JSON.parse(data);
       let nombre_carrera = obj.data[0].descripcion;
       carrera_nombre = obj.data[0].descripcion;
       carrera_id = carrera;
       profesor_id = profesor;
       let titulo = '<h1><i>'+nombre_carrera+'</i></h1><h3>Seleccionar Opcion</h3><hr>';
       let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                     <li class="breadcrumb-item">`+carrera_nombre+`</li>
                   </ol>
                 </nav>`;
       let cuerpo = `
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                     <div class = "col-xs-12 col-sm-6 col-md-6"><button class="btn btn-info" id="btnAlumnos" style="width:237px;" data-opcion="alumnos" data-idcarrera="`+carrera_id+`" data-idprofesor="`+profesor_id+`"><img src="../assets/img/icons/item_icon.png" width="30">&nbsp;Gestionar Materias&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></div>
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                     <div class = "col-xs-12 col-sm-6 col-md-6"><button class="btn btn-info" id="btnRegularidades" style="width:237px;" data-opcion="regularidades" data-idcarrera="`+carrera_id+`" data-idprofesor="`+profesor_id+`"><img src="../assets/img/icons/item_icon.png" width="30">&nbsp;Gestionar Regularidades</button></div>
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                     <div class = "col-xs-12 col-sm-6 col-md-6"><button class="btn btn-info" id="btnExamenes" style="width:237px;" data-opcion="examenes" data-idcarrera="`+carrera_id+`" data-idprofesor="`+profesor_id+`"><img src="../assets/img/icons/item_icon.png" width="30">&nbsp;Gestionar Examenes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></div>
                     <div class = "col-xs-12 col-sm-3 col-md-3"></div>
                    `;
       $("#breadcrumb").html(nav);
       $("#titulo").html(titulo);
       $("#resultado").html(cuerpo);
    });
}

//***************************************************************************************
// CUANDO SE SELECCIONA LA OPCION PARA GESTIONAR ALUMNOS PARA UNA CARRERA SELECCIONADA
//***************************************************************************************
$("body").on("click","#btnAlumnos",function(){
   opcion = $(this).data('opcion');
   cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})

//********************************************************************************************
// CUANDO SE SELECCIONA LA OPCION PARA GESTIONAR REGULARIDADES PARA UNA CARRERA SELECCIONADA
//********************************************************************************************
$("body").on("click","#btnRegularidades",function(){
   opcion = $(this).data('opcion');
   cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})

//********************************************************************************************
// CUANDO SE SELECCIONA LA OPCION PARA GESTIONAR EXAMENES PARA UNA CARRERA SELECCIONADA
//********************************************************************************************
$("body").on("click","#btnExamenes",function(){
   opcion = $(this).data('opcion');
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Turno de Ex&aacute;men</h3><hr>';
   $.get( "html/cargarTurnosExamenes.html", function( data ) {
       $("#resultado").html(data);
       $.post( "ajax/getTurnosExamenesPorCodigo.php", {'codigo':1005}, function( data_turno ) {
           let obj = JSON.parse(data_turno);
           if (obj.codigo == 100 && obj.habilitado == 'Si') {
               $("#btnPrimerTurno").prop("disabled",false);
           } else {
               $("#btnPrimerTurno").prop("disabled",true);
           }
       });
       $.post( "ajax/getTurnosExamenesPorCodigo.php", {'codigo':1006}, function( data_turno ) {
           let obj = JSON.parse(data_turno);
           if (obj.codigo == 100 && obj.habilitado == 'Si') {
               $("#btnSegundoTurno").prop("disabled",false);
           } else {
               $("#btnSegundoTurno").prop("disabled",true);
           }
       });
       $.post( "ajax/getTurnosExamenesPorCodigo.php", {'codigo':1007}, function( data_turno ) {
           let obj = JSON.parse(data_turno);
           if (obj.codigo == 100 && obj.habilitado == 'Si') {
               $("#btnTercerTurno").prop("disabled",false);
           } else {
               $("#btnTercerTurno").prop("disabled",true);
           }
       });
   });
    $("#titulo").html(titulo);
   //cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})

//*****************************************************************************************************
//****************** CARGA LOS LLAMADOS DE LOS TURNO **************************************************
//*****************************************************************************************************
$("body").on("click","#btnPrimerTurno",function(){
   opcion = $(this).data('opcion');
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Llamado del Turno de Ex&aacute;men</h3><hr>';
   $.get( "html/cargarLlamadosTurno.html", function( data ) {
       $("#resultado").html(data);
       $("#btnPrimerLlamado").prop("disabled",false);
       $("#btnSegundoLlamado").prop("disabled",false);
   });
    $("#titulo").html(titulo);
   //cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})

$("body").on("click","#btnSegundoTurno",function(){
   opcion = $(this).data('opcion');
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Llamado del Turno de Ex&aacute;men</h3><hr>';
   $.get( "html/cargarLlamadosTurno.html", function( data ) {
       $("#resultado").html(data);
       $("#btnPrimerLlamado").prop("disabled",false);
       $("#btnSegundoLlamado").prop("disabled",true);
   });
    $("#titulo").html(titulo);
   //cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})

$("body").on("click","#btnTercerTurno",function(){
   opcion = $(this).data('opcion');
   let titulo = '<h1><i>'+carrera_nombre+'</i></h1><h3>Seleccionar Llamado del Turno de Ex&aacute;men</h3><hr>';
   $.get( "html/cargarLlamadosTurno.html", function( data ) {
       $("#resultado").html(data);
       $("#btnPrimerLlamado").prop("disabled",false);
       $("#btnSegundoLlamado").prop("disabled",false);
   });
    $("#titulo").html(titulo);
   //cargarMateriaPorCarrera($(this).data('idcarrera'),$(this).data('idprofesor'))
})
