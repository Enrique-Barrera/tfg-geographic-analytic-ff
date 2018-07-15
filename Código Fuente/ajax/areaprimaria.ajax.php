<?php

require_once "../controladores/areaprimaria.controlador.php";
require_once "../modelos/areaprimaria.modelo.php";

class AjaxAreaPrimaria{


	/*=============================================
	RECUPERAR PORCENTAJE WIZARD1
	=============================================*/	

	public $idCandidato;

	public function ajaxRecuperarPorcentajeW4(){

		$valor = $_POST['idCandidato'];
		$respuesta = ControladorAreaPrimaria::ctrRecuperarPorcentajeW4($valor);
		echo json_encode($respuesta);

	}

}

/*=============================================
EDITAR USUARIO
=============================================*/

if(isset($_POST["idCandidato"])){

	$editar = new AjaxAreaPrimaria();
	$editar -> idCandidato = $_POST["idCandidato"];
	$editar -> ajaxRecuperarPorcentajeW4();

}
