<nav class="navbar navbar-expand-md navbar-light fixed-top">
  <a class="navbar-brand" href="index.php"><img
    src="./public/img/logo.png" width="100"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php echo ($id_pagina == 'home')?'active':'';?>">
        <a class="nav-link" href="index.php" ><i class="material-icons">home</i><span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item <?php echo ($id_pagina == 'login')?'active':'';?>">
        <a class="nav-link" href="login.php">Acceder al Sistema<span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item dropdown <?php echo ($id_pagina == 'inscripcion')?'active':'';?>">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Inscripciones a las Carreras
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="registro.php">Registrarse</a>
          <a class="dropdown-item" href="buscarInscripcion.php">Descargar Inscripcion</a>
        </div>
      </li>

      <!--<li class="nav-item">
        <a class="nav-link disabled" href="#">Eventos<span class="sr-only">(current)</span></a>
      </li>-->

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
           Capacitación
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item disabled" href="#">Inscripción</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="./consultarCursosRealizados.php" >Capacitaciones Realizadas</a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="https://ens40-sfe.infd.edu.ar/sitio/" target="_blank">Institucional<span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="https://ens40-sfe.infd.edu.ar/aula/acceso.cgi" target="_blank">Campus Virtual<span class="sr-only">(current)</span></a>
      </li>


    </ul>

  </div>
</nav>
