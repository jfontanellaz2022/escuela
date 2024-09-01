//****************************************************************************
//CARGA LA PLANTILLA PARA LUEGO CARGAR LOS ALUMNOS A UNA MATERIA ESPECIFICA
//****************************************************************************
$("body").on("click","#btnCargarAlumno", function() {
    let carrera = $(this).data('idcarrera');
    let materia = $(this).data('idmateria');
    let profesor = $(this).data('idmateria');
    $.get( "html/cargarAlumno.html", function( data ) {
        $("#resultado").html(data);
        $.post( "ajax/getAlumnosPorCarrera.php", {'carrera':carrera_id}, function( data_alumnos ) {
            let obj = JSON.parse(data_alumnos);
            obj.data.forEach(alumno => {
                  $("#inputAltaAlumno").append($('<option/>', {
                        text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre+', '+alumno.dni,
                        value: alumno.id,
                    }));
        });

             let titulo = '<h1><i>'+materia_nombre+'</i></h1><h3><strong>Alumno</strong> (Nuevo Alumno)</h3><hr>';
             let nav = `<nav aria-label="breadcrumb" role="navigation">
                         <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                           <li class="breadcrumb-item"><a href="#" onclick="cargarAlumnosPorMateria(`+materia_id+`,'`+materia_nombre+`')">`+materia_nombre+`</a></li>
                           <li class="breadcrumb-item">Alta Alumno</li>
                         </ol>
                       </nav>`;

              $("#breadcrumb").html(nav);
              $("#titulo").html(titulo);
        });
        $('#inputAltaAlumno').select2();

        $.post( "ajax/getCursadoForma.php", function( data_cursado ) {
            let obj = JSON.parse(data_cursado);
            obj.data.forEach( cursado_forma => {
                  $("#inputAltaCursado").append($('<option/>', {
                        text: cursado_forma.nombre,
                        value: cursado_forma.codigo,
                    }));
             });
        });
        $('#inputAltaCursado').select2();
    });
})

//******************************************************
// GUARDA UN NUEVO ALUMNO EN UNA MATERIA ESPECIFICADA
//******************************************************
$("body").on("click","#btnAgregar", function() {
    let alumno = $('#inputAltaAlumno').val();
    let cursado_forma = $('#inputAltaCursado').val()
    $.post( "ajax/setAlumnoEnMateria.php", {'materia':materia_id, 'alumno':alumno, 'cursado':cursado_forma}, function( data ) {
            let obj = JSON.parse(data);
            if (obj.codigo==100) {
                $("#resultado_carga").html(`<div class="alert alert-success" role="alert">
                                           <b>Atencion:&nbsp;</b><i>`+obj.data+`</i>
                                            </div>`)
            } else {
                $("#resultado_carga").html(`<div class="alert alert-danger" role="alert"><img src="../assets/img/icons/error_icon.png" width="20">&nbsp;<b>Error:&nbsp;</b><i>
                                           `+obj.data+`</i>
                                            </div>`)
            }
    });
});
