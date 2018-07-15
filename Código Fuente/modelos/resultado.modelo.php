<?php

require_once "conexion.php";

class ModeloResultado{

	/*=============================================
	MOSTRAR DATOS MODELO PARA UN CANDIDATO
	=============================================*/

	static public function mdlCandidatoModelo($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT PC.NOMBRE, PC.DIRECCION1, PC.ID_CANDIDATO_TIPO, PCA.ESTIMACIONVENTAS, 
		PCA.SEGMENTO, PCA.SIMILAR1, PCA.SIMILAR2, PCA.SIMILAR3, PCA.ESTIMACION_SIMILITUD
		from poi_candidato pc, poi_candidato_agregados pca
		where pc.id_candidato = pca.id_candidato AND pc.id_candidato = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
	}

	/*=============================================
	MOSTRAR DATOS MODELO COMPETIDOR
	=============================================*/

	static public function mdlCompetidorModelo($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT PC.NOMBRE, PC.DIRECCION1, PC.ID_CANDIDATO_TIPO, PCV.VENTAS 
		from poi_competencia pc, poi_competencia_ventas pcv
		where pc.id_competencia = pcv.id_competencia and pcv.id_anyo='2017' AND pc.id_competencia = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
	}

	/*=============================================
	RECUPERAR DATOS MODELO VALORACION
	=============================================*/

	static public function mdlCandidatoValoracion($tabla, $item, $valor){
		$stmt = Conexion::conectar()->prepare("SELECT A2_POBLACION, A2_VIVIENDASSECUNDARIAS, 
		A2_TRABAJADORES, P1_POBLACIONREAL, P2_POBLACIONFLOTANTE, P3_COMERCIO, P4_ATRACCION, 
		P5_EMPLEADOS, P6_EXCLUSIVIDAD, A1_INDICETURISMO*100 as A1_INDICETURISMO, 
		A1_INDICEHOTELES*100 as A1_INDICEHOTELES, A1_INDICECOMERCIO * 100 as A1_INDICECOMERCIO,
		A1_INDICEGRANSUPERFICIE*100 as A1_INDICEGRANSUPERFICIE, A1_INDICEOCIO*100 as A1_INDICEOCIO,
		A1_INDICESALUD*100 as A1_INDICESALUD, A1_INDICERESTAURANTES*100 as A1_INDICERESTAURACION 
		from $tabla
		where $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
	}
}