<?php

class ControladorNuevoLocal{

	/*=============================================
	RECUPERAR PORCENAJE WIZARD1
	=============================================*/

	static public function ctrRecuperarPorcentaje($valor){
		$tabla = "procesos_python";
		$valor2 = 1;
		$valor1 = $valor;
		$respuesta = ModeloNuevoLocal::MdlRecuperarPorcentaje($tabla, $valor1, $valor2);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR DATOS NUEVO LOCAL
	=============================================*/

	static public function ctrMostrarNuevoLocal($item, $valor){
		$tabla = "poi_candidato";
		$respuesta = ModeloNuevoLocal::MdlMostrarNuevoLocal($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	CRUD - CREAR NUEVO LOCAL
	=============================================*/

	static public function ctrCrearNuevoLocal(){			
		if(isset($_POST["inputParking"])){

				$tabla = "poi_candidato";

				$datos = array("direccion" => $_POST["inputDireccion"],
					           "latitud" => $_POST["inputLatitud"],
					           "longitud" => $_POST["inputLongitud"],
					           "nombre" => $_POST["inputNombre"],				
					           "idCadena" => $_POST["inputCadena"],				  
					           "superficie" => $_POST["inputSuperficie"],			
					           "numeromesas" => $_POST["inputMesas"],		   
					           "numerocajas" => $_POST["inputCajas"],
					           "zonainfantil" => $_POST["inputInfantil"],
					       		"idCandidatoTipo" => $_POST["inputIdTipo"],
					           "numeroparking" => $_POST["inputParking"]);

				$respuesta = ModeloNuevoLocal::mdlCrearNuevoLocal($tabla, $datos);
			
				if($respuesta != "error"){
					echo '<script>
					swal({
						type: "success",
						title: "¡El Nuevo Local ha sido guardado correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					}).then((result)=>{
						if(result.value){					
							window.location =  "index.php?ruta=local&idCandidato='.$respuesta.'&idEstado=1";
						}
					});			
					</script>';
				}
				else{
					echo '<script>
					swal({
						type: "warning",
						title: "¡Error al guardar el nuevo local!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					}).then((result)=>{
						if(result.value){					
							window.location =  "index.php?ruta=nuevolocal&idEstado=1";
						}
					});			
					</script>';

					

				}	
		}
	}

	/*=============================================
	CRUD - ACTUALIZAR NUEVO LOCAL
	=============================================*/

	static public function ctrActualizarNuevoLocal(){	
		if(isset($_POST["editarCandidato"])){

				$tabla = "poi_candidato";

				$datos = array("idCandidato" => $_POST["editarCandidato"],
								"direccion" => $_POST["editarDireccion"],
					           "latitud" => $_POST["editarLatitud"],
					           "longitud" => $_POST["editarLongitud"],
					           "nombre" => $_POST["editarNombre"],				
					           "idCadena" => $_POST["editarCadena"],				  
					           "superficie" => $_POST["editarSuperficie"],			
					           "numeromesas" => $_POST["editarMesas"],		   
					           "numerocajas" => $_POST["editarCajas"],
					           "zonainfantil" => $_POST["editarInfantil"],
					       	   "idCandidatoTipo" => $_POST["editarIdTipo"],
					           "numeroparking" => $_POST["editarParking"]);

				$respuesta = ModeloNuevoLocal::mdlActualizarNuevoLocal($tabla, $datos);
			
				if($respuesta != "error"){
					echo '<script>
					swal({
						type: "success",
						title: "¡Los datos han sido actualizados correctamente! Registro '.$respuesta.'",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					}).then((result)=>{
						if(result.value){					
							window.location =  "index.php?ruta=local&idCandidato='.$respuesta.'&idEstado=1";
						}
					});			
					</script>';
				}
				else {
					echo '<script>
					swal({
						type: "warning",
						title: "¡Error de Actualización!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					}).then((result)=>{
						if(result.value){					
							window.location =  "index.php?ruta=nuevolocal&idEstado=1";
						}
					});	
					</script>';		
				}	
		}
	}

}
	




