<?php

require_once "conexion.php";

class ModeloCompetencia{


	/*=============================================
	RECUPERAR DATOS CANDIDATO
	=============================================*/

	static public function mdlRecuperarCAndidato($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT id_candidato, nombre, latitud, longitud, id_cadena, id_estado FROM $tabla WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}


	/*=============================================
	MOSTRAR COMPETENCIA PARA TABLA
	=============================================*/

	static public function mdlMostrarCompetencia($tabla, $item, $valor1, $valor2, $valor3){
		
		$max_lat = $valor2 + 0.01;
		$min_lat = $valor2 - 0.01;
		$max_lng = $valor3 + 0.01;
		$min_lng = $valor3 - 0.01;			

		$stmt = Conexion::conectar()->prepare("SELECT id_competencia, nombre, (round(6371000 * ACOS( 
                    SIN(RADIANS(pc.latitud)) * SIN(RADIANS($valor2)) 
                    + COS(RADIANS(pc.longitud - $valor3)) 
                    * COS(RADIANS(pc.latitud)) * COS(RADIANS($valor2))),2)) AS distance
                     FROM $tabla pc
                     WHERE (pc.latitud BETWEEN $min_lat AND $max_lat)
                     AND (pc.longitud BETWEEN $min_lng AND $max_lng)
                     HAVING distance  < 1000                                      
                     ORDER BY distance ASC 
                     LIMIT 25");


		$stmt -> bindParam(":".$item, $valor1, PDO::PARAM_STR);	
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA
	=============================================*/

	static public function mdlMostrarCompetenciaMapa($tabla, $item, $valor1, $valor2, $valor3){
		
		$max_lat = $valor2 + 0.05;
		$min_lat = $valor2 - 0.05;
		$max_lng = $valor3 + 0.05;
		$min_lng = $valor3 - 0.05;			

		$stmt = Conexion::conectar()->prepare("SELECT pc.id_competencia, pc.nombre, pc.id_cadena, pc.latitud, pc.longitud, cc.competencia_nombre
                     FROM $tabla pc, sp_competencia_cadena cc
                     WHERE (pc.latitud BETWEEN $min_lat AND $max_lat)
					 AND (pc.longitud BETWEEN $min_lng AND $max_lng)
					 AND pc.id_cadena=cc.id_competencia_cadena");
		$stmt -> bindParam(":".$item, $valor1, PDO::PARAM_STR);	
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

}