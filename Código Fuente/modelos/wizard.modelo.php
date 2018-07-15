<?php

require_once "conexion.php";

class ModeloWizard{

	static public function mdlActualizarEstado($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_estado = :valor WHERE id_candidato = :item"); 
        $stmt -> bindParam(":item", $item, PDO::PARAM_STR);
        $stmt -> bindParam(":valor", $valor, PDO::PARAM_STR);        
		if($stmt->execute()){
			return "ok";	
		}else{
			return "error";	
		}
		$stmt->close();	
		$stmt = null;
	
	}

}