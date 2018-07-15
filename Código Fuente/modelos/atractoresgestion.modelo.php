<?php

require_once "conexion.php";

class ModeloAtractoresGestion{


	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA TODO MADRID
	=============================================*/

	static public function mdlMostrarAtractoresMapaTodo($tabla){
		
		$stmt = Conexion::conectar()->prepare("SELECT pa.id_atractor, pa.nombre, pa.latitud, pa.longitud, 
					pa.reviews, pa.direccion1, saf.atractor_familia_nombre, spa.atractor_nombre
                    FROM $tabla pa, sp_atractor spa, sp_atractor_familia saf 
					where pa.id_atractor_actividad = spa.id_atractor_actividad 
					and spa.id_atractor_familia = saf.id_atractor_familia 
					and spa.id_atractor_familia = 'Hoteles'");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	CRUD - CREAR ATRACTOR
	=============================================*/

	static public function mdlCrearAtractor($tabla, $datos){

		$stmt1 = Conexion::conectar()->prepare("Select MAX(CONVERT(id_atractor, SIGNED INTEGER))+1 AS idAtractor from poi_atractor;");
		$stmt1 -> execute();
		$idAtractor = $stmt1 -> fetch();
		$Atractor = $idAtractor['idAtractor'];

		$stmt2 = Conexion::conectar()->prepare("SELECT id_atractor_actividad from sp_atractor  where id_atractor_familia = :idFamilia LIMIT 1");
		$stmt2->bindParam(":idFamilia", $datos["familia"], PDO::PARAM_STR);
		$stmt2 -> execute();
		$idActividad= $stmt2 -> fetch();
		$Actividad = $idActividad['id_atractor_actividad'];

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_atractor, direccion1, direccion2, latitud, longitud, nombre,
					 reviews, id_atractor_actividad) 
					 VALUES ($Atractor, :direccion1, :direccion2, :latitud, :longitud, :nombre, :reviews,'".$Actividad."')");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion2", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":latitud", $datos["latitud"], PDO::PARAM_STR);
		$stmt->bindParam(":longitud", $datos["longitud"], PDO::PARAM_STR);
		$stmt->bindParam(":reviews", $datos["reviews"], PDO::PARAM_STR);

		if($stmt->execute()){
			return $Candidato;	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	}


	/*=============================================
	CRUD - ACTUALIZAR ATRACTOR
	=============================================*/

	static public function mdlActualizarAtractor($tabla, $datos){
	
		$stmt2 = Conexion::conectar()->prepare("SELECT id_atractor_actividad from sp_atractor  where id_atractor_familia = :idFamilia LIMIT 1");
		$stmt2->bindParam(":idFamilia", $datos["familia"], PDO::PARAM_STR);
		$stmt2 -> execute();
		$idActividad= $stmt2 -> fetch();
		$Actividad = $idActividad['id_atractor_actividad'];

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, direccion1 = :direccion1, 
				reviews = :reviews, id_atractor_actividad ='".$Actividad."' WHERE id_atractor = :idAtractor");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":reviews", $datos["reviews"], PDO::PARAM_STR);
		$stmt->bindParam(":idAtractor", $datos["idAtractor"], PDO::PARAM_STR);

		if($stmt->execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	}

	/*=============================================
	CRUD - BORRAR ATRACTOR
	=============================================*/

	static public function mdlBorrarAtractor($tabla, $item, $valor){

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