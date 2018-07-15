<?php

require_once "../controladores/nuevolocal.controlador.php";
require_once "../modelos/nuevolocal.modelo.php";

class AjaxNuevoLocal{


	/*=============================================
	RECUPERAR PORCENTAJE WIZARD1
	=============================================*/	

	public $idCandidato;

	public function ajaxRecuperarPorcentaje(){

		$valor = $_POST['idCandidato'];
		$respuesta = ControladorNuevoLocal::ctrRecuperarPorcentaje($valor);
		echo json_encode($respuesta);

	}

}

/*=============================================
EDITAR USUARIO
=============================================*/

if(isset($_POST["idCandidato"])){

	$editar = new AjaxNuevoLocal();
	$editar -> idCandidato = $_POST["idCandidato"];
	$editar -> ajaxRecuperarPorcentaje();

}
