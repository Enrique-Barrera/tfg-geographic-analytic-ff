<?php

require_once "conexion.php";

class ModeloNuevoLocal{

	/*=============================================
	RECUPERAR PORCENTAJE WIZARD1
	=============================================*/

	static public function mdlRecuperarPorcentaje($tabla, $valor1, $valor2){

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

	/*=============================================
	RECUPERAR DATOS NUEVO LOCAL COMPLETOS
	=============================================*/

	static public function mdlMostrarNuevoLocal($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("SELECT id_candidato, direccion1, nombre, latitud, longitud, id_cadena, id_estado, superficie, numeromesas, numerocajas, zonainfantil, numeroparking, id_candidato_tipo FROM $tabla  WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	CRUD - CREAR NUEVO LOCAL
	=============================================*/

	static public function mdlCrearNuevoLocal($tabla, $datos){

		$stmt1 = Conexion::conectar()->prepare("Select MAX(CONVERT(id_candidato, SIGNED INTEGER))+1 AS idCandidato from poi_candidato;");
		$stmt1 -> execute();
		$idCandidato = $stmt1 -> fetch();
		//$Candidato = current($idCandidato);
		$Candidato = $idCandidato['idCandidato'];
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_candidato, direccion1, direccion2, latitud, longitud, nombre,
		 id_cadena, superficie, numeromesas, numerocajas, zonainfantil, numeroparking, fecha_alta, id_estado, id_candidato_tipo) 
		 VALUES ($Candidato, :direccion1, :direccion2, :latitud, :longitud, :nombre, :idCadena, :superficie, :numeromesas, :numerocajas, :zonainfantil, :numeroparking, now(),'1', :idCandidatoTipo)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion2", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":latitud", $datos["latitud"], PDO::PARAM_STR);
		$stmt->bindParam(":longitud", $datos["longitud"], PDO::PARAM_STR);
		$stmt->bindParam(":idCadena", $datos["idCadena"], PDO::PARAM_STR);
		$stmt->bindParam(":superficie", $datos["superficie"], PDO::PARAM_STR);
		$stmt->bindParam(":numeromesas", $datos["numeromesas"], PDO::PARAM_STR);
		$stmt->bindParam(":numerocajas", $datos["numerocajas"], PDO::PARAM_STR);
		$stmt->bindParam(":zonainfantil", $datos["zonainfantil"], PDO::PARAM_STR);
		$stmt->bindParam(":numeroparking", $datos["numeroparking"], PDO::PARAM_STR);
		$stmt->bindParam(":idCandidatoTipo", $datos["idCandidatoTipo"], PDO::PARAM_STR);

		if($stmt->execute()){
			return $Candidato;	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	}
	
	/*=============================================
	CRUD - ACTUALIZAR NUEVO LOCAL
	=============================================*/

	static public function mdlActualizarNuevoLocal($tabla, $datos){

		$Candidato = $datos["idCandidato"];
		$idCandidato = $datos["idCandidato"];
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, direccion1 = :direccion1, id_cadena = :idCadena, superficie = :superficie, numeromesas = :numeromesas, numerocajas = :numerocajas, zonainfantil = :zonainfantil, numeroparking = :numeroparking, id_candidato_tipo = :idCandidatoTipo WHERE id_candidato = :idCandidato"); 

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion1", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":idCadena", $datos["idCadena"], PDO::PARAM_STR);
		$stmt->bindParam(":superficie", $datos["superficie"], PDO::PARAM_STR);
		$stmt->bindParam(":numeromesas", $datos["numeromesas"], PDO::PARAM_STR);
		$stmt->bindParam(":numerocajas", $datos["numerocajas"], PDO::PARAM_STR);
		$stmt->bindParam(":zonainfantil", $datos["zonainfantil"], PDO::PARAM_STR);
		$stmt->bindParam(":numeroparking", $datos["numeroparking"], PDO::PARAM_STR);
		$stmt->bindParam(":idCandidatoTipo", $datos["idCandidatoTipo"], PDO::PARAM_STR);
		$stmt->bindParam(":idCandidato", $datos["idCandidato"], PDO::PARAM_STR);

		if($stmt->execute()){
			return $idCandidato;	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	
	}

}