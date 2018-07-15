<?php

require_once "conexion.php";

class ModeloRestaurantes{


	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA TODO MADRID
	=============================================*/

	static public function mdlMostrarCompetenciaMapaTodo($tabla){
		
		$stmt = Conexion::conectar()->prepare("SELECT pc.id_competencia, pc.nombre, pc.direccion1, pc.id_cadena, pc.latitud, pc.longitud, pc.reviews, pc.id_candidato_tipo, cc.competencia_nombre 
		FROM $tabla pc, sp_competencia_cadena cc where pc.id_cadena = cc.id_competencia_cadena");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}


	/*=============================================
	CRUD - CREAR RESTAURANTE
	=============================================*/

	static public function mdlCrearRestaurante($tabla, $datos){

		$stmt1 = Conexion::conectar()->prepare("Select MAX(CONVERT(id_competencia, SIGNED INTEGER))+1 AS idCompetencia from poi_competencia;");
		$stmt1 -> execute();
		$idCompetencia = $stmt1 -> fetch();
		$Competencia = $idCompetencia['idCompetencia'];		
		
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_competencia, direccion1, direccion2, latitud, longitud, nombre,
					 reviews, id_cadena, id_candidato_tipo) 
					 VALUES ($Competencia, :direccion1, :direccion2, :latitud, :longitud, :nombre, :reviews, :cadena, :tipo)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion2", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":latitud", $datos["latitud"], PDO::PARAM_STR);
		$stmt->bindParam(":longitud", $datos["longitud"], PDO::PARAM_STR);
		$stmt->bindParam(":reviews", $datos["reviews"], PDO::PARAM_STR);	
		$stmt->bindParam(":cadena", $datos["cadena"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);							 

		if($stmt->execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	}

	/*=============================================
	CRUD - ACTUALIZAR RESTAURANTE
	=============================================*/

	static public function mdlActualizarRestaurante($tabla, $datos){
		
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, direccion1 = :direccion1, 
				reviews = :reviews, id_cadena = :cadena, id_candidato_tipo = :tipo WHERE id_competencia = :idCompetencia");

		$stmt->bindParam(":idCompetencia", $datos["idCompetencia"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":reviews", $datos["reviews"], PDO::PARAM_STR);	
		$stmt->bindParam(":cadena", $datos["cadena"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);	

		if($stmt->execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	}

	/*=============================================
	CRUD - BORRAR RESTAURANTE
	=============================================*/

	static public function mdlBorrarRestaurante($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);
		
		if($stmt -> execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt -> close();
		$stmt = null;
	}

}