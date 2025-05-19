
<nav class="navbar navbar-expand-sm navbar-light" style="background-color:#e3e1e2" id="myNavbar">
<a href="#" class="navbar-brand"><img src="../public/img/logo_50x50.jpg">&nbsp;Gesti&oacute;n del Alumno</a>
 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
 </button>

 <div class="collapse navbar-collapse" id="mainNav">

 <ul class="navbar-nav">

   <li class="nav-item <?php echo ($id_url=='menu_home')?'active':'';?>">
    <a class="nav-link" href="home.php?token=<?=$_SESSION['token'];?>">
      <img src="../public/img/icons/home_icon.png" width="23">
      <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_historia')?'active':'';?>">
       <a class="nav-link" href="menuHistoria.php?token=<?=$_SESSION['token'];?>">
         Historia Academica
         <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_examenes')?'active':'';?>">
       <a class="nav-link" href="menuExamenes.php?token=<?=$_SESSION['token'];?>">
         Examenes
         <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?php echo ($id_url=='menu_cursado')?'active':'';?>">
       <a class="nav-link" href="menuCursado.php?token=<?=$_SESSION['token'];?>">
         Cursado
         <span class="sr-only">(current)</span></a>
   </li>
</ul>
<ul class="navbar-nav ml-auto">

<li class="nav-item px-4 dropdown">
      <a class="nav-link dropdown-toggle text-white" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <font color='black'><strong><?=$_SESSION['arreglo_datos_usuario']['usuario_nombre']?></strong></font> <img src="../public/img/icons/user_icon.png" width="22">
      </a>
      <div class="dropdown-menu dropdown-menu-right bg-info" aria-labelledby="servicesDropdown">
       <a href="#" class="dropdown-item" data-toggle="modal" data-target="#idCambioPwd"><img src="../public/img/icons/chpassword_icon.png" width="23">&nbsp;Cambiar Contraseña</a>
       <a href="#" class="dropdown-item" data-toggle="modal" data-target="#idCambioUsuario"><img src="../public/img/icons/chuser_icon.png" width="22">&nbsp;Cambiar Nombre de Usuario</a>
       <a class="dropdown-item" href="../logout.php"><img src="../public/img/icons/exit_icon.png" width="19">&nbsp;Salir</a>
   </div>
 </li>
</ul>

 </div>
</nav>