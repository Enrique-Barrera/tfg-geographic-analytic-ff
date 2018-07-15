<?php

class ControladorInicio{

	/*=============================================
	MOSTRAR CANDIDATOS INICIO
	=============================================*/

	static public function ctrMostrarCandidatosInicio($item, $valor){
		$tabla = "poi_candidato";
		$respuesta = ModeloInicio::MdlMostrarCandidatosInicio($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	CRUD - BORRAR NUEVO LOCAL (PAGINA DE INICIO)
	=============================================*/

	static public function ctrBorrarCandidatoInicio(){
		if(isset($_GET["Borrar"])){

			$tabla ="poi_candidato";
			$item = "id_candidato";
			$valor = $_GET['idCandidato'];

			$respuesta = ModeloInicio::mdlBorrarCandidatoInicio($tabla, $item, $valor);

			if($respuesta == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "El Local Candidato ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
								if (result.value) {
								window.location = "inicio";
								}
							})
				</script>
				window.location = "index.php?ruta=inicio";';
			}
			else{
				echo'<script>
				swal({
					  type: "success",
					  title: "Error en el Borrado del Candidato",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
								if (result.value) {
								window.location = "inicio";
								}
							})
				</script>
				window.location = "index.php?ruta=inicio";';
			}
			
		}
	}

	/*========================================================
	MOSTRAR CANDIDATOS SEGUN MES DE ALTA Y TIPO DE RESTAURANTE
	========================================================*/

	static public function ctrRecuperarHistoricoCandidatos($item, $valor){
		$tabla ="poi_candidato";
		$respuesta = ModeloInicio::mdlRecuperarHistoricoCandidatos($tabla, $item, $valor);
		return $respuesta;
	}


	/*========================================================
	MOSTRAR CANDIDATOS SEGUN ESTADO Y TIPO DE RESTAURANTE
	========================================================*/

	static public function ctrRecuperarEstadoCandidatos($item, $valor){
		$tabla ="poi_candidato";
		$respuesta = ModeloInicio::mdlRecuperarEstadoCandidatos($tabla, $item, $valor);
		return $respuesta;	
	}
}
	


