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
                <img src="../public/img/icons/configuraciones_icon.png" width="20"> Configuración 
              </a>
              <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="menuCalendario.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Calendario de Eventos<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuFechasExamenes.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Fechas de Exámenes<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuCorrelativasRendir.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Correlativas Para Rendir<span class="sr-only">(current)</span></a>
                <a class="dropdown-item" href="menuCorrelativasCursado.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Correlativas Para Cursar<span class="sr-only">(current)</span></a>
              </div>
        </li>

        <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../public/img/icons/gestion_icon.png" width="20"> Gestión
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item" href="menuAlumno.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Alumnos<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuProfesor.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Docentes<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuMateria.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Materias<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuCarrera.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Carreras<span class="sr-only">(current)</span></a>
        </div>
  </li>

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <img src="../public/img/icons/verificado_icon.png" width="20"> Homologación
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item" href="menuHomologacionMateriaRegularizada.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Regularidad<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuHomologacionMateriaAprobada.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Aprobación<span class="sr-only">(current)</span></a>
        </div>
  </li>

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../public/img/icons/proceso_icon.png" width="20"> Procesos
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item disabled" href="menuPromociones.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Procesar Alumnos Promocionados<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuProcesoActualizarExamenes.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Procesar Exámenes Aprobados<span class="sr-only">(current)</span></a>
        </div>
        
  </li>

  

  <li class="nav-item dropdown">
        <a class="nav-link i dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../public/img/icons/reporte_icon.png" width="20"> Reportes
        </a>
        <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdownMenuLink">
           <a class="dropdown-item" href="menuActasCursado.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Actas Cursado<span class="sr-only">(current)</span></a>
           <a class="dropdown-item disabled" href="menuActasPromociones.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Actas Promociones<span class="sr-only">(current)</span></a>
           <a class="dropdown-item" href="menuActasExamenes.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Actas Exámenes<span class="sr-only">(current)</span></a>
           <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="menuAlumnosPorCarrera.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Alumnos Por Carrera<span class="sr-only">(current)</span></a>
           <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="menuActasExamenesPendientes.php?token=<?=$_SESSION['token'];?>"><img src="../public/img/icons/item1_icon.png" width="20"> Actas Exámenes Pendientes<span class="sr-only">(current)</span></a>
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
        <a class="dropdown-item disabled" href="#"><img src='../public/img/icons/chuser_icon.png' width='20'>&nbsp;Cambiar Nombre Usuario</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../logout.php"><img src='../public/img/icons/exit_icon.png' width='18'>&nbsp;Salir</a>
        </div>
  </li>
</ul>

  </div>
</nav>
