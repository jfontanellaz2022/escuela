//*************************************************************************************
//********************* CARGA LAS CARRERAS QUE TIENE EL ALUMNO ************************
//*************************************************************************************
let myPieChart;

function obtenerMateriasPorCarrera(idCarrera) {
  let parametros = {"carrera":idCarrera};
  let arr_materias;
  $.ajax({
    url: "../API/findAllMateriasPorCarrera.php?token=<?=$_SESSION['token'];?>",
    type: 'POST',
    data: parametros,
    dataType: "json",
    async: false,
    success: function (response) {
        if (response.codigo==200) {
          arr_materias = response.datos;
        }
    }
});
return arr_materias;
}

function obtenerMateriasAprobadasPorAlumnoPorCarrera(idAlumno, idCarrera) {
  let parametros = {"alumno":idAlumno,"carrera":idCarrera};
  let arr_materias;
  $.ajax({
    url: "../API/findMateriasAprobadasPorAlumnoPorCarrera.php?token=<?=$_SESSION['token'];?>",
    type: 'POST',
    data: parametros,
    dataType: "json",
    async: false,
    success: function (response) {
        if (response.codigo==200) {
          arr_materias = response.datos;
        }
    }
});
return arr_materias;
}



function cargarCarrerasHistoria(idAlumno) {
    //Activo del Menu la opcion Alumnos
    let titulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="menuEscritorio.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Historial Academico</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Historia Acad&eacute;mica</i></h1>
                  <h3>Seleccione Carrera</h3>`;

  //Remuevo la class que me deshabilita
  $("#grafica").addClass('d-none');
  $("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "../API/findAllCarrerasPorAlumno.php?token=<?=$_SESSION['token'];?>", parametros, function( data ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $("#resultado").html("");
    data.datos.forEach(carrera => {
       let resul = `<div class="col-md-4">
             <div class="card" style="width: 18rem;">
                     <img src="../public/img/`+carrera.imagen+`" class="card-img-top">
                     <div class="card-body">
                         <h2 class="card-title">`+carrera.descripcion+`</h2>
                             <h6 class="card-subtitle mb-2 text-muted"></h6>
                         <p class="card-text"></p>
                            <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarHistoriaPorCarrera(`+carrera.id+`,`+idAlumno+`,'`+carrera.descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar</a>
                     </div>
                   </div>
             </div>`;
        $("#resultado").append(resul);
     });
  },"json");
}


//*********************************************************************************************
//***************** CARGA LA HISTORIA ACADEMICA DEL ALUMNO PARA ESA CARRERA *******************
//*********************************************************************************************
function cargarHistoriaPorCarrera(carrera,idAlumno,descripcion) {
      let idCarrera = carrera;
      let titulo;
      let bread;
      let arr_materia_de_carrera = obtenerMateriasPorCarrera(carrera);
      let arr_materia_aprobada_alumno_en_carrera = obtenerMateriasAprobadasPorAlumnoPorCarrera(idAlumno,carrera);
      let cant_materia_carrera = arr_materia_de_carrera.length;
      let cant_materia_aprobada_carrera = arr_materia_aprobada_alumno_en_carrera.length;
      let diferencia = cant_materia_carrera-cant_materia_aprobada_carrera;
      let arr_grap = [{"descripcion":"Sin Aprobar","existencia":diferencia},{"descripcion":"Aprobadas","existencia":cant_materia_aprobada_carrera}];
      bread = `<nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="menuEscritorio.php">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarrerasHistoria(`+idAlumno+`)">Historial Academico</a></li>
                      <li class="breadcrumb-item active" aria-current="page">`+descripcion+`</li>
                      </ol>
                  </nav>`;
      titulo = `<h1><i>Historia Acad&eacute;mica</i></h1>
                    <h3>`+descripcion+` - Materias</h3>`;
      
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      
      if (document.getElementById("avance")) {
        
                $("#grafica").removeClass('d-none');
                $("#carrera_descripcion").html(descripcion);
                
                if (cant_materia_aprobada_carrera > 0) {
                    var nombre = [];
                    var cantidad = [];
                    for (var i = 0; i < arr_grap.length; i++) {
                        nombre.push(arr_grap[i]['descripcion']);
                        cantidad.push(arr_grap[i]['existencia']);
                    }
                    
                    var ctx = document.getElementById("avance");
                    if (myPieChart) {
                      myPieChart.destroy();
                    }
                    myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: nombre,
                            datasets: [{
                                data: cantidad,
                                backgroundColor: ['#EF280F', '#26D854'],
                            }],
                        },
                    });
                    
                  }
    
      parametros = {'idCarrera':idCarrera};
      $.ajax({
        url:'./funciones/getHistoriaAcademicaPorCarrera.php',
        method: "POST",
        data: parametros,
        success:function(data){
          $("#resultado").html(data).fadeIn('slow');
        }
      });
}
}   