<?php

class ControladorAtractoresGestion{


	/*=============================================
	MOSTRAR ATRACTORESA PARA MAPA TODO MADRID
	=============================================*/

	static public function ctrMostrarAtractoresMapaTodo(){
		$tabla = "poi_atractor";
		$respuesta = ModeloAtractoresGestion::MdlMostrarAtractoresMapaTodo($tabla);
		return $respuesta;
	}

	/*=============================================
	CRUD - CREAR ATRACTOR
	=============================================*/

	static public function ctrCrearAtractor(){
		if(isset($_POST["CrearAtractor"])){		
			$tabla = "poi_atractor";

			$datos = array("nombre" => $_POST["crearNombre"],
							"direccion" => $_POST["crearDireccion"],
							"longitud" => $_POST["crearLongitud"],
							"latitud" => $_POST["crearLatitud"],				
							"reviews" => $_POST["crearReviews"],				  
							"familia" => $_POST["crearFamilia"]);

			$respuesta = ModeloAtractoresGestion::MdlCrearAtractor($tabla, $datos);

			if($respuesta != "error"){
				echo '<script>
				swal({
					type: "success",
					title: "¡El Nuevo Atractor ha sido guardado correctamente!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=atractoresgestion";
					}
				});			
				</script>';
			}
			else{
				echo '<script>
				swal({
					type: "warning",
					title: "¡Error al guardar el nuevo atractor!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=atractoresgestion";
					}
				});			
				</script>';
			}	
		}
	}

	/*=============================================
	CRUD - UPDATE ATRACTOR
	=============================================*/

	static public function ctrActualizarAtractor(){

		if(isset($_POST["ActualizarAtractor"])){

			$tabla = "poi_atractor";

			$datos = array(	"idAtractor" => $_POST["editarId"],
							"nombre" => $_POST["editarNombre"],
							"direccion" => $_POST["editarDireccion"],
							"longitud" => $_POST["editarLatitud"],
							"latitud" => $_POST["editarLongitud"],				
							"reviews" => $_POST["editarReviews"],				  
							"familia" => $_POST["editarFamilia"]);			

			$respuesta = ModeloAtractoresGestion::MdlActualizarAtractor($tabla, $datos);

			if($respuesta != "error"){
				echo '<script>
				swal({
					type: "success",
					title: "¡Los datos han sido actualizados correctamente!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then((result)=>{
					if(result.value){					
						window.location =  "index.php?ruta=atractoresgestion";
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
						window.location =  "index.php?ruta=atractoresgestion";
					}
				});	
				</script>';		
			}	
		}
	}


	/*=============================================
	CRUD - BORRAR ATRACTOR
	=============================================*/

	static public function ctrBorrarAtractor(){
		if(isset($_POST["BorrarAtractor"])){
			$tabla = "poi_atractor";
			$item = "id_atractor";
			$valor = $_POST["borrarId"];

			$respuesta = ModeloAtractoresGestion::MdlBorrarAtractor($tabla, $item, $valor);
			
			if($respuesta == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "El Atractor ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
						if (result.value) {
							window.location = "index.php?ruta=atractoresgestion";
						}
					});
				</script>';
			}
			else{
				echo'<script>
				swal({
					  type: "success",
					  title: "Error en el Borrado del Atractor",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
						if (result.value) {
								window.location = "index.php?ruta=atractoresgestion";
						}
					  });
				</script>';
			}
		}
	}

}
