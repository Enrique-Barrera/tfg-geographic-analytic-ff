<?php

class ControladorRestaurantes{


	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA TODO MADRID
	=============================================*/

	static public function ctrMostrarCompetenciaMapaTodo(){
		$tabla = "poi_competencia";
		$respuesta = ModeloRestaurantes::MdlMostrarCompetenciaMapaTodo($tabla);
		return $respuesta;
	}

	/*=============================================
	CRUD - CREAR RESTAURANTE
	=============================================*/

	static public function ctrCrearRestaurante(){
		if(isset($_POST["CrearRestaurante"])){

			$tabla = "poi_competencia";

			$datos = array("nombre" => $_POST["crearNombre"],
							"direccion" => $_POST["crearDireccion"],
							"longitud" => $_POST["crearLongitud"],
							"latitud" => $_POST["crearLatitud"],				
							"reviews" => $_POST["crearReviews"],				  
							"cadena" => $_POST["crearCadena"],
							"tipo" => $_POST["crearTipo"]);

			$respuesta = ModeloRestaurantes::MdlCrearRestaurante($tabla, $datos);

			if($respuesta == "ok"){
				echo '<script>
				swal({
					type: "success",
					title: "¡El Nuevo Restaurante ha sido guardado correctamente!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=restaurantes";
					}
				});			
				</script>';
			}
			else{
				echo '<script>
				swal({
					type: "warning",
					title: "¡Error al guardar el nuevo restaurante!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=restaurantes";
					}
				});			
				</script>';
			}	
		}
	}

	/*=============================================
	CRUD - UPDATE RESTAURANTE
	=============================================*/

	static public function ctrActualizarRestaurante(){
		if(isset($_POST["ActualizarRestaurante"])){

			$tabla = "poi_competencia";

			$datos = array("nombre" => $_POST["editarNombre"],
							"direccion" => $_POST["editarDireccion"],
							"longitud" => $_POST["editarLongitud"],
							"latitud" => $_POST["editarLatitud"],				
							"reviews" => $_POST["editarReviews"],				  
							"cadena" => $_POST["editarCadena"],
							"tipo" => $_POST["editarTipo"],
							"idCompetencia" =>$_POST['editarId']);

			$respuesta = ModeloRestaurantes::MdlActualizarRestaurante($tabla, $datos);

			if($respuesta == "ok"){
				echo '<script>
				swal({
					type: "success",
					title: "¡Los datos han sido actualizados correctamente!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=restaurantes";
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
						window.location =  "index.php?ruta=restaurantes";
					}
				});	
				</script>';		
			}	
		}			
	}

	/*=============================================
	CRUD - BORRAR RESTAURANTE
	=============================================*/

	static public function ctrBorrarRestaurante(){
		if(isset($_POST["BorrarRestaurante"])){
			$tabla = "poi_competencia";
			$item = "id_competencia";
			$valor = $_POST["borrarId"];

			$respuesta = ModeloRestaurantes::MdlBorrarRestaurante($tabla, $item, $valor);
						
			if($respuesta == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "El Restaurante ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
						if (result.value) {
							window.location = "index.php?ruta=restaurantes";
						}
					});
				</script>';
			}
			else{
				echo'<script>
				swal({
					  type: "success",
					  title: "Error en el Borrado del Restaurante",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
						if (result.value) {
								window.location = "index.php?ruta=restaurantes";
						}
					  });
				</script>';
			}
		}
	}
}

