<?php

require_once "conexion.php";

class ModeloAreaPrimaria{

	/*=============================================
	MOSTRAR SECCIONES AREA CAPTACION PARA MAPA
	=============================================*/

	static public function mdlMostrarSeccionesMapa($tabla, $item, $valor){

		
		$stmt = Conexion::conectar()->prepare("SELECT ID_SSCC, AREA, AsWKB(SSS.GEOMETRY) AS wkb FROM SP_SSCC_SPATIAL SSS, $tabla WHERE SSS.GEOCODIGO = SUBSTRING(ID_SSCC, 3, 8) AND $item = :$item");


		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	RECUPERAR PORCENTAJE WIZARD4
	=============================================*/

	static public function mdlRecuperarPorcentajeW4($tabla, $valor1, $valor2){

		$stmt = Conexion::conectar()->prepare("SELECT MAX(PROGRESO_PORCENTAJE) as porcentaje, 
			PROGRESO_DESCRIPCION as mensaje FROM $tabla  WHERE id_candidato = :$valor1 AND id_proceso =  :$valor2 
			and PROGRESO_PORCENTAJE = (
    			SELECT MAX(PROGRESO_PORCENTAJE) FROM  $tabla 
				where id_candidato = :$valor1 AND id_proceso =  :$valor2)");
		$stmt -> bindParam(":".$valor1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$valor2, $valor2, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}


}
