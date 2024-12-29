<nav class="navbar navbar-expand-sm navbar-light" style="background-color:#e3e1e2" id="myNavbar">
<a href="#" class="navbar-brand"><img src="../public/img/logo_n.jpg" width="40">&nbsp;SiGeAl</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

 <div class="collapse navbar-collapse" id="mainNav">

 <ul class="navbar-nav">

   <li class="nav-item nav-item-home <?php echo ($id_pagina == 'home')?'active':'';?>">
    <a href="home.php" class="nav-link">
      <img src="../public/img/icons/home_icon.png" width="23">
      <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item nav-item-alumnos <?php echo ($id_pagina == 'carreras')?'active':'';?>">
    <a href="menuGestionarCarrerasMateriasAlumnos.php" class="nav-link">
      Carreras/Materias/Alumnos
      <span class="sr-only">(current)</span></a>
   </li>

  <li class="nav-item nav-item-regularidades <?php echo ($id_pagina == 'regularidades')?'active':'';?>">
    <a href="menuGestionarRegularidadesPromociones.php" class="nav-link">
      Regularidades
      <span class="sr-only">(current)</span></a>
  </li>

  <li class="nav-item nav-item-examenes <?php echo ($id_pagina == 'finales')?'active':'';?>">
    <a href="menuGestionarExamenesFinales.php" class="nav-link">
      Exámenes
      <span class="sr-only">(current)</span></a>
  </li>
</ul>

<ul class="navbar-nav ml-auto">
<li class="nav-item px-4 dropdown">
      <a class="nav-link dropdown-toggle text-white" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../public/img/icons/user_icon.png" width="22">
      </a>
      <div class="dropdown-menu dropdown-menu-right bg-info" aria-labelledby="servicesDropdown">
      <a class="dropdown-item" href="#" onclick="cambiarPassword()">Cambiar Contraseña</a>
          <div class="dropdown-divider"></div> 
       <a class="dropdown-item" href="../logout.php">Salir</a>
      </div>
 </li>
</ul>

 </div>
</nav>
