//*********************************************************************************
// CARGA LA PLANTILLA PARA LUEGO CARGAR LAS NOTAS DE ALUMNOS PARA LA REGULARIDAD
//*********************************************************************************
$("body").on("click","#btnCargarNota", function() {
    let carrera = $(this).data('idcarrera');
    //let materia = $(this).data('idmateria');
    let profesor = $(this).data('idmateria');
    $.get( "html/cargarNotaAlumno.html", function( data ) {
        $("#resultado").html(data);
        $.post( "ajax/getAlumnosRegularesCursandoPorMateria.php", {'materia':materia_id}, function( data_alumnos ) {
            let obj = JSON.parse(data_alumnos);
            if (obj.codigo==100) {
                  obj.data.forEach(alumno => {
                        $("#inputAltaAlumno").append($('<option/>', {
                              text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                              value: alumno.id,
                          }));
                   });
            };

             let titulo = '<h1><i>'+materia_nombre+'</i></h1><h3><strong>Alumno</strong> (Nueva Carga de Nota)</h3><hr>';
             let nav = `<nav aria-label="breadcrumb" role="navigation">
                         <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarAlumnosPorMateriaRegularidad(`+materia_id+`,'`+materia_nombre+`')">`+materia_nombre+`</a></li>
                           <li class="breadcrumb-item">Cargar Nota Alumno</li>
                         </ol>
                       </nav>`;

              $("#breadcrumb").html(nav);
              $("#titulo").html(titulo);

        });
        $('#inputAltaAlumno').select2();
        $('#inputAltaNota').select2();
        $('#inputAltaEstadoFinal').select2();
    });
})

//**************************************************************************************
// GUARDA LA NOTA Y ESTADO FINAL DEL CURSADO DE UN ALUMNO EN UNA MATERIA ESPECIFICADA
//**************************************************************************************
$("body").on("click","#btnGuardarNota", function() {
    let alumno = $('#inputAltaAlumno').val();
    let nota = $('#inputAltaNota').val();
    let estadoFinal = $('#inputAltaEstadoFinal').val();
    let parametros =  {'materia':materia_id, 'alumno':alumno, 'nota':nota, 'estadoFinal':estadoFinal};
    $.post( "ajax/setNotasCursado.php", {'materia':materia_id, 'alumno':alumno, 'nota':nota, 'estadoFinal':estadoFinal}, function( data ) {
            let obj = JSON.parse(data);
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-success" role="alert">
                                           <b>Atencion:&nbsp;</b><i>`+obj.data+`</i>
                                            </div>`)
            } else {
                $("#resultado_carga").html(`<div class="alert alert-danger" role="alert"><img src="../assets/img/icons/error_icon.png" width="20">&nbsp;<strong>Error:&nbsp;</strong><i>
                                           `+obj.data+`</i>
                                            </div>`)
            }
    });
});
