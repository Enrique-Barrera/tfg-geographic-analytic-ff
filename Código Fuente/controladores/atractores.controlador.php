<?php

class ControladorAtractores{

	/*=============================================
	MOSTRAR INDICES DE ATRACCION
	=============================================*/

	static public function ctrMostrarIndices($item, $valor){
		$tabla = "poi_candidato_agregados";
		$respuesta = ModeloAtractores::MdlMostrarIndices($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR INDICES DE ATRACCION MAXIMOS
	=============================================*/

	static public function ctrMostrarIndicesMaximos($item, $valor){
		$tabla = "poi_competencia_agregados";
		$respuesta = ModeloAtractores::MdlMostrarIndicesMaximos($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR ATRACTORES PARA MAPA
	=============================================*/

	static public function ctrMostrarAtractoresMapa($item, $valor1, $valor2, $valor3){
		$tabla = "poi_atractor";
		$respuesta = ModeloAtractores::MdlMostrarAtractoresMapa($tabla, $item, $valor1, $valor2, $valor3);
		return $respuesta;
	}

}
	




