<?php

class ControladorResultado{

	/*=============================================
	MOSTRAR CANDIDATOS DATOS MODELO
	=============================================*/

	static public function ctrCandidatoModelo($item, $valor){
		$tabla = "poi_candidato_agregado";
		$respuesta = ModeloResultado::MdlCandidatoModelo($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR COMPETIDOR DATOS MODELO
	=============================================*/

	static public function ctrCompetidorModelo($item, $valor){
		$tabla = "poi_competencia";
		$respuesta = ModeloResultado::MdlCompetidorModelo($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	RECUPERAR DATOS MODELO VALORACIÓN
	=============================================*/

	static public function ctrCandidatoValoracion($item, $valor){
		$tabla = "poi_candidato_valoracion";
		$respuesta = ModeloResultado::MdlCandidatoValoracion($tabla, $item, $valor);
		return $respuesta;
	}
}
	


