<?php

require_once "conexion.php";

class ModeloAtractores{

	/*=============================================
	MOSTRAR INDICES DE ATRACCION
	=============================================*/

	static public function mdlMostrarIndices($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	MOSTRAR INDICES MAXIMOS DE ATRACCION
	=============================================*/

	static public function mdlMostrarIndicesMaximos($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT MAX(A1_COMPETENCIA) AS A1_INDICECOMPETENCIAMAX,
			MAX(A1_INDICEOCIO) AS A1_INDICEOCIOMAX, 
			MAX(A1_INDICECOMERCIO) AS A1_INDICECOMERCIOMAX, 
			MAX(A1_INDICESALUD) AS A1_INDICESALUDMAX, 
			MAX(A1_INDICEHOTELES) AS A1_INDICEHOTELESMAX, 
			MAX(A1_INDICERESTAURANTES) AS A1_INDICERESTAURANTESMAX, 
			MAX(A1_INDICETURISMO) AS A1_INDICETURISMOMAX,
			MAX(A1_INDICEGRANSUPERFICIE) AS A1_INDICEGRANSUPERFICIEMAX,
			MAX(A1_INDICEGLOBAL) AS A1_INDICEGLOBALMAX,
			MAX(A2_POBLACION) AS A2_POBLACIONMAX,
			MAX(A2_TRABAJADORES) AS A2_TRABAJADORESMAX,
			MAX(A2_VIVIENDASSECUNDARIAS) AS A2_VIVIENDASMAX,
			MAX(A2_HOGARESTOTAL) AS A2_HOGARESTOTALMAX,
			MAX(A3_POBLACION) AS A3_POBLACIONMAX,
			MAX(A3_VIVIENDASTOTAL) AS A3_VIVIENDASTOTALMAX,
			MAX(A3_HOGARES) AS A3_HOGARESTOTALMAX,
			MAX(A3_TASAPARO / A3_POBLACIONMUNICIPIO) AS A3_TASAPAROMAX,
			MAX(A3_RENTAMEDIA) AS A3_RENTAMEDIAMAX
			from $tabla");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	MOSTRAR COMPETENCIA PARA MAPA
	=============================================*/

	static public function mdlMostrarAtractoresMapa($tabla, $item, $valor1, $valor2, $valor3){
		
		$max_lat = $valor2 + 0.017;
		$min_lat = $valor2 - 0.017;
		$max_lng = $valor3 + 0.017;
		$min_lng = $valor3 - 0.017;			
		$stmt = Conexion::conectar()->prepare("SELECT pa.id_atractor, pa.nombre, pa.id_atractor_actividad,pa.latitud, pa.longitud, saf.atractor_familia_nombre 
                     FROM $tabla pa, sp_atractor spa, sp_atractor_familia saf 
                     WHERE (latitud BETWEEN $min_lat AND $max_lat)
					 AND (longitud BETWEEN $min_lng AND $max_lng)
					 AND pa.id_atractor_actividad = spa.id_atractor_actividad 
					 AND spa.id_atractor_familia = saf.id_atractor_familia");
		$stmt -> bindParam(":".$item, $valor1, PDO::PARAM_STR);	
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

}
