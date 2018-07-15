
<?php

class ControladorAreaPrimaria{

	/*=============================================
	MOSTRAR SECCIONES AREA CAPTACION PARA MAPA
	=============================================*/

	static public function ctrMostrarSeccionesMapa($item, $valor){
		$tabla = "poi_candidato_area";
		$respuesta = ModeloAreaPrimaria::MdlMostrarSeccionesMapa($tabla, $item, $valor);
		return $respuesta;
	}



	/*=============================================
	RECUPERAR PORCENAJE WIZARD4
	=============================================*/

	static public function ctrRecuperarPorcentajeW4($valor){
		$tabla = "procesos_python";
		$valor2 = 5;
		$valor1 = $valor;
		$respuesta = ModeloAreaPrimaria::MdlRecuperarPorcentajeW4($tabla, $valor1, $valor2);
		return $respuesta;
	}

}
