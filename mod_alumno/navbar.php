
<nav class="navbar navbar-expand-sm navbar-light" style="background-color:#e3e1e2" id="myNavbar">
<a href="#" class="navbar-brand"><img src="../public/img/logo.png" width="60">&nbsp;Gesti&oacute;n del Alumno</a>
 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
 </button>

 <div class="collapse navbar-collapse" id="mainNav">

 <ul class="navbar-nav">

   <li class="nav-item <?php echo ($id_url=='menu_home')?'active':'';?>">
    <a class="nav-link" href="home.php">
      <img src="../public/img/icons/home_icon.png" width="23">
      <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_historia')?'active':'';?>">
       <a class="nav-link" href="menuHistoria.php">
         Historia Academica
         <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_examenes')?'active':'';?>">
       <a class="nav-link" href="menuExamenes.php">
         Examenes
         <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_cursado')?'active':'';?>">
       <a class="nav-link" href="menuCursado.php">
         Cursado
         <span class="sr-only">(current)</span></a>
   </li>

</ul>

<ul class="navbar-nav ml-auto">



<li class="nav-item px-4 dropdown">
      <a class="nav-link dropdown-toggle text-white" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <font color='black'><strong><?=$_SESSION['usuario']?></strong></font> <img src="../public/img/icons/user_icon.png" width="22">
      </a>
      <div class="dropdown-menu dropdown-menu-right bg-info" aria-labelledby="servicesDropdown">
      <a class="dropdown-item" href="menuCambiarPassword.php"><img src="../public/img/icons/chpassword_icon.png" width="25"> Cambiar Contrase&ntilde;a</a>
       <a class="dropdown-item" href="../logout.php"><img src="../public/img/icons/exit_icon.png" width="22"> Salir</a>
   </div>
 </li>
</ul>

 </div>
</nav>