<?php

class ControladorCompetencia{


	/*=============================================
	RECUPERAR DATOS CANDIDATO
	=============================================*/

	static public function ctrRecuperarCandidato($item, $valor){
		$tabla = "poi_candidato";
		$respuesta = ModeloCompetencia::MdlRecuperarCandidato($tabla, $item, $valor);
		return $respuesta;
	}


	/*=============================================
	MOSTRAR COMPETENCIA PARA TABLA
	=============================================*/

	static public function ctrMostrarCompetencia($item, $valor1, $valor2, $valor3){
		$tabla = "poi_competencia";
		$respuesta = ModeloCompetencia::MdlMostrarCompetencia($tabla, $item, $valor1, $valor2, $valor3);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA
	=============================================*/

	static public function ctrMostrarCompetenciaMapa($item, $valor1, $valor2, $valor3){
		$tabla = "poi_competencia";
		$respuesta = ModeloCompetencia::MdlMostrarCompetenciaMapa($tabla, $item, $valor1, $valor2, $valor3);
		return $respuesta;
	}

}
	

