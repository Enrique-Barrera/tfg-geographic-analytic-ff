
<!--=====================================
MENU LATERAL
======================================-->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" >
        <li class="header">MENU PRINCIPAL</li>
        <li class="active">
          <a href="inicio">
            <i class="fa fa-home"></i> <span>Inicio</span>
          </a>
        <li class="treeview">
          <a href="estimacion">
            <i class="fa fa-database"></i>
            <span>Información</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="restaurantes"><i class="fa fa-coffee"></i><span> Restaurantes </span></a></li>
            <li><a href="atractoresgestion"><i class="fa fa-building"></i><span> Atractores </span></a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="estimacion">
            <i class="fa fa-money"></i>
            <span>Estimación</span>
            <span class="pull-right-container">
            	<i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php 
              if(isset($_GET['idCandidato'],  $_GET['idEstado'])){   
                $idCandidato = $_GET['idCandidato'];    
                $idEstado = $_GET['idEstado'];      
                echo'<li><a href="index.php?ruta=local&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 1. Nuevo Local </span></a></li>';
              }
              else{
                  echo'<li><a href="index.php?ruta=nuevolocal&idEstado=1"><i class="fa fa-angle-double-right"></i><span> 1. Nuevo Local </span></a></li>';
              } 

              if(isset($_GET['idCandidato'],  $_GET['idEstado'])){
                $idCandidato = $_GET['idCandidato'];
                $estadoUsuario= $_GET['idEstado'];
                if($_GET['idEstado'] =="2"){
                   echo '<li><a href="index.php?ruta=competencia&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 2. Competencia </span></a></li>';
                }
                if($_GET['idEstado'] =="3"){
                   echo '<li><a href="index.php?ruta=competencia&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 2. Competencia </span></a></li>
                        <li><a href="index.php?ruta=atractores&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 3. Area Atracción </span></a></li>';
                }
                if($_GET['idEstado'] =="4"){
                   echo '<li><a href="index.php?ruta=competencia&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 2. Competencia </span></a></li>
                         <li><a href="index.php?ruta=atractores&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 3. Area Atracción </span></a></li>
                         <li><a href="index.php?ruta=areaprimaria&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 4. Area Captación </span></a></li>';
                }
                if($_GET['idEstado'] =="5"){
                   echo '<li><a href="index.php?ruta=competencia&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 2. Competencia </span></a></li>
                         <li><a href="index.php?ruta=atractores&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 3. Area Atracción </span></a></li>
                         <li><a href="index.php?ruta=areaprimaria&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 4. Area Captación </span></a></li>
                         <li><a href="index.php?ruta=resultado&idCandidato='.$idCandidato.'&idEstado='.$idEstado.'"><i class="fa fa-angle-double-right"></i><span> 5. Valoración </span></a></li>';
                }
              }
            ?>         
          </ul>
        </li>
        <li class="treeview">
          <a href="estimacion">
            <i class="fa fa-users"></i>
            <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="usuarios"><i class="fa fa-user"></i><span> Gestión Usuarios </span></a></li>
          </ul>
        </li>        

<!--- Módulo de Informes no incluido en MVP

        <li class="treeview">
          <a href="informes">
            <i class="fa fa-dashboard"></i>
            <span>Informes</span>
            <span class="pull-right-container">
            	<i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="sector"><i class="fa fa-area-chart"></i><span>  Sector </span></li>
            <li><a href="modelo"><i class="fa fa-pie-chart"></i><span>  Modelos </span></a></li>
          </ul>
        </li>
--->
	</section>
</aside>
