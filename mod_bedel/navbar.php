<nav class="navbar navbar-expand-md navbar-light bg-warning"  >
  <a class="navbar-brand" href="index.php"><img  src="../public/img/logo_50x50.jpg"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>


  <div class="collapse navbar-collapse" id="navbarCollapse">

    <ul class="navbar-nav mr-auto">
      
        <li class="nav-item nav-item-home">
            <a class="nav-link" href="home.php?token=<?=$_SESSION['token'];?>">
              <img src="../public/img/icons/home_icon.png" width="23">
              <span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item dropdown ">
              <a class="nav-link i dropdown-toggle " href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Configuracion 
              </a>
              <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="menuCalendario.php?token=<?=$_SESSION['token'];?>">Calendario de Eventos<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuFechasExamenes.php?token=<?=$_SESSION['token'];?>">Fechas de Exámenes<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuCorrelativasRendir.php?token=<?=$_SESSION['token'];?>">Correlativas Para Rendir<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuCorrelativasCursado.php?token=<?=$_SESSION['token'];?>">Correlativas Para Cursar<span class="sr-only">(current)</span></a>
              </div>
        </li>

        <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Gestión
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item" href="menuAlumno.php?token=<?=$_SESSION['token'];?>">Alumnos<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuProfesor.php?token=<?=$_SESSION['token'];?>">Docentes<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuMateria.php?token=<?=$_SESSION['token'];?>">Materias<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuCarrera.php?token=<?=$_SESSION['token'];?>">Carreras<span class="sr-only">(current)</span></a>
        </div>
  </li>

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Homologación
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item" href="menuHomologacionMateriaRegularizada.php?token=<?=$_SESSION['token'];?>">Regularidad<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuHomologacionMateriaAprobada.php?token=<?=$_SESSION['token'];?>">Aprobación<span class="sr-only">(current)</span></a>
        </div>
  </li>

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Procesos
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item disabled" href="menuPromociones.php?token=<?=$_SESSION['token'];?>">Procesar Alumnos Promocionados<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuProcesoActualizarExamenes.php?token=<?=$_SESSION['token'];?>">Procesar Exámenes Aprobados<span class="sr-only">(current)</span></a>
        </div>
        
  </li>

  

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Reportes
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item disabled" href="menuActasCursado.php?token=<?=$_SESSION['token'];?>">Actas Cursado<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuActasPromociones.php?token=<?=$_SESSION['token'];?>">Actas Promociones<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuActasExamenes.php?token=<?=$_SESSION['token'];?>">Actas Exámenes<span class="sr-only">(current)</span></a>
           <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="menuAlumnosPorCarrera.php?token=<?=$_SESSION['token'];?>">Alumnos Por Carrera<span class="sr-only">(current)</span></a>
           <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="menuActasExamenesPendientes.php?token=<?=$_SESSION['token'];?>">Actas Exámenes Pendientes<span class="sr-only">(current)</span>&nbsp;<img src='../public/img/icons/nuevo.png' width='33'></a>
          </div>
  </li>

</ul>

<ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="../public/img/icons/user_icon.png" width="22">
        </a>
        <div class="dropdown-menu dropdown-menu-right bg-light" aria-labelledby="servicesDropdown">
        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#idCambioPwd"><img src="../public/img/icons/chpassword_icon.png" width="22">&nbsp;Cambiar password</a>
        <a class="dropdown-item disabled" href="#"><img src='../public/img/icons/pngwing.png' width='20'>&nbsp;Cambiar Nombre Usuario</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../logout.php"><img src='../public/img/icons/exit_icon.png' width='18'>&nbsp;Salir</a>
        </div>
  </li>
</ul>

  </div>
</nav>
