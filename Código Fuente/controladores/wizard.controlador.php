<?php

class ControladorWizard{

	/*=============================================
	PROCESO DE PYTHON - WIZARD PASO 1
	=============================================*/
	
	static public function ctrEjecutarWizard1(){	
		if(isset($_POST['Estado2'])){
			if($_GET['idEstado'] == '1'){
				$id_candidato = $_GET['idCandidato'];
				$tabla = "poi_candidato";
                $item = $_GET['idCandidato'];
                $valor = "1";
				$respuesta = ModeloWizard::mdlActualizarEstado($tabla, $item, $valor);	
				$id_Estado = "2";
				$modal =   "<script>$(document).ready(function(){
   					     	 $('#myModal').modal({backdrop: 'static', keyboard: false}).modal('show')
							  });
							</script>";
				$id_candidato = $_GET['idCandidato'];	
				$descriptorspec = array(
					0 => array("pipe", "r"),
					1 => array("pipe", "w"),
					2 => array("pipe", "w")   
				);

				$command ="python c:\\xampp\\htdocs\\pfg\\vistas\\modulos\\python\\WizardAgregados.py ".$id_candidato;
				$process = proc_open($command, $descriptorspec, $pipes);
				echo $modal;
			
			}
			else{
                $id_candidato = $_GET['idCandidato'];  
				$id_Estado = $_GET['idEstado']; 
				 echo '<script>window.location="index.php?ruta=competencia&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
			}	
		}
    }	
    
    /*===============================================
	PROCESO DE PYTHON - WIZARD PASO 2 COMPETENCIA
	================================================*/
	
	static public function ctrEjecutarWizard2(){	
		if(isset($_POST['Estado3'])){
			if($_GET['idEstado'] == '2'){
				$id_candidato = $_GET['idCandidato'];
				$tabla = "poi_candidato";
                $item = $_GET['idCandidato'];
                $valor = "3";
				$respuesta = ModeloWizard::mdlActualizarEstado($tabla, $item, $valor);	
				$id_Estado = "3";
				echo '<script>window.location="index.php?ruta=atractores&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
			}
			else{
                $id_candidato = $_GET['idCandidato'];   
                $id_Estado = $_GET['idEstado'];              
                echo '<script>window.location="index.php?ruta=atractores&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
			}	
		}
	}	

    /*===============================================
	PROCESO DE PYTHON - WIZARD PASO 3 ATRACTORES
	================================================*/
	
	static public function ctrEjecutarWizard3(){	
		if(isset($_POST['Estado4'])){
			if($_GET['idEstado'] == '3'){
				$id_candidato = $_GET['idCandidato'];
				$tabla = "poi_candidato";
                $item = $_GET['idCandidato'];
                $valor = "4";
				$respuesta = ModeloWizard::mdlActualizarEstado($tabla, $item, $valor);	
				$id_Estado = "4";
                echo '<script>window.location="index.php?ruta=areaprimaria&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
			}
			else{
                $id_candidato = $_GET['idCandidato'];  
                $id_Estado = $_GET['idEstado'];               
                echo '<script>window.location="index.php?ruta=areaprimaria&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
			}	
		}
	}	

/*=============================================
	PROCESO DE PYTHON - WIZARD PASO 4 AREA PRIMARIA
	=============================================*/
	
	static public function ctrEjecutarWizard4(){	
		if(isset($_POST['Estado5'])){
			if($_GET['idEstado'] == '4'){
				$id_candidato = $_GET['idCandidato'];
				$tabla = "poi_candidato";
                $item = $_GET['idCandidato'];
                $valor = "5";
				$respuesta = ModeloWizard::mdlActualizarEstado($tabla, $item, $valor);	
				$id_Estado = "5";
				$modal =   "<script>$(document).ready(function(){
   					     	 $('#Wizard4').modal({backdrop: 'static', keyboard: false}).modal('show')
							  });
							</script>";	
				$id_candidato = $_GET['idCandidato'];	
				$descriptorspec = array(
					0 => array("pipe", "r"),
					1 => array("pipe", "w"),
					2 => array("pipe", "w")   
				);
				$command ="python c:\\xampp\\htdocs\\pfg\\vistas\\modulos\\python\\WizardModelos.py ".$id_candidato;
				$process = proc_open($command, $descriptorspec, $pipes);	
				echo $modal;
			}
			else{
                $id_candidato = $_GET['idCandidato'];
                $id_Estado = $_GET['idEstado'];  
                echo '<script>window.location="index.php?ruta=resultado&idCandidato='.$id_candidato.'&idEstado='.$id_Estado.'";</script>'; 
   
			}	
		}
    }	

}
	




