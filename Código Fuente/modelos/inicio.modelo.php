<?php

require_once "conexion.php";

class ModeloInicio{

	/*=============================================
	MOSTRAR CANDIDATOS GRID INICIO
	=============================================*/

	static public function mdlMostrarCandidatosInicio($tabla, $item, $valor){
		if($item != null){
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item WHERE fecha_baja IS NULL");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_baja IS NULL");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	CRUD - BORRAR NUEVO LOCAL (PAGINA DE INICIO)
	=============================================*/

	static public function mdlBorrarCandidatoInicio($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fecha_baja = now() WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);
		if($stmt -> execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt -> close();
		$stmt = null;
	}

	/*========================================================
	MOSTRAR CANDIDATOS SEGUN MES DE ALTA Y TIPO DE RESTAURANTE
	========================================================*/

	static public function mdlRecuperarHistoricoCandidatos($tabla, $item, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM
			(
			SELECT MONTH(FECHA_ALTA) AS MES, YEAR(FECHA_ALTA) AS ANYO, 
			COUNT(CASE WHEN ID_CANDIDATO_TIPO = 'Restaurante Freestander' THEN 1 END) AS FRE,
			COUNT(CASE WHEN ID_CANDIDATO_TIPO = 'Restaurante Instore' THEN 1 END) AS INS,
			COUNT(*) AS TOTAL
			FROM $tabla
			WHERE FECHA_BAJA IS NULL
			GROUP BY YEAR(FECHA_ALTA), MONTH(FECHA_ALTA)
			ORDER BY ANYO DESC, MES DESC 
			LIMIT 8
			) AS MAIC
			ORDER BY ANYO, MES");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	/*========================================================
	MOSTRAR CANDIDATOS SEGUN ESTADO Y TIPO DE RESTAURANTE
	========================================================*/

	static public function mdlRecuperarEstadoCandidatos($tabla, $item, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT ID_ESTADO,
			COUNT(CASE WHEN ID_CANDIDATO_TIPO = 'Restaurante Freestander' THEN 1 END) AS FRE,
			COUNT(CASE WHEN ID_CANDIDATO_TIPO = 'Restaurante Instore' THEN 1 END) AS INS,
			COUNT(*) AS TOTAL
			FROM $tabla
			WHERE FECHA_BAJA IS NULL
			GROUP BY ID_ESTADO
			ORDER BY ID_ESTADO");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

}