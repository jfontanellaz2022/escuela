//**********************************************************************************************************
//OBTIENE EL LISTADO DE LOS ALUMNOS DE UNA MATERIA DADA DEL PROFESOR EN FUNCION DE LA OPCION SELECCIONADA
//**********************************************************************************************************
$("body").on("click",".btnNotaExamen", function(){
   let alumno = $(this).data('idalumno');
   let materia = $(this).data('idmateria');
   let llamado = $(this).data('llamado');
   let datos_alumnos = sacaNombreAlumnoPorId(alumno);
   let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarCarreras(`+profesor_id+`)">Carreras</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarMateriaPorCarrera(`+carrera_id+`,`+profesor_id+`)">`+carrera_nombre+`</a></li>
                     <li class="breadcrumb-item"><a href="#" onclick="cargarAlumnosPorMateriaExamenes(`+materia+`,'`+materia_nombre+`',`+llamado+`)">`+materia_nombre+`</a></li>
                     <li class="breadcrumb-item">Editar Nota <img src="../assets/img/icons/edit_icon.png" width="15"></li>
                   </ol>
                 </nav>`;
   let titulo = '<h1><i>'+sacaNombreMateriaPorId(materia)+' <strong>('+materia+')</strong></i></h1><h3><strong>Alumnos</strong> (Cargar Notas de Finales)</h3><hr>';              
   $("#breadcrumb").html(nav);
   $("#titulo").html(titulo);              
   $.get("html/cargarNotaFinalAlumno.html", function( data ) {
      $("#resultado").html(data);
      $("#inputDatosAlumno").val(sacaNombreAlumnoPorId(alumno));
      $("#inputIdAlumno").val(alumno);
   });
   
})