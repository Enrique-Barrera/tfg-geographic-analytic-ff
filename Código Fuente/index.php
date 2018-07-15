<?php
require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/restaurantes.controlador.php";
require_once "controladores/nuevolocal.controlador.php";
require_once "controladores/competencia.controlador.php";
require_once "controladores/areaprimaria.controlador.php";
require_once "controladores/atractores.controlador.php";
require_once "controladores/resultado.controlador.php";
require_once "controladores/inicio.controlador.php";
require_once "controladores/atractoresgestion.controlador.php";
require_once "controladores/wizard.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/restaurantes.modelo.php";
require_once "modelos/nuevolocal.modelo.php";
require_once "modelos/competencia.modelo.php";
require_once "modelos/areaprimaria.modelo.php";
require_once "modelos/atractores.modelo.php";
require_once "modelos/resultado.modelo.php";
require_once "modelos/inicio.modelo.php";
require_once "modelos/atractoresgestion.modelo.php";
require_once "modelos/wizard.modelo.php";
$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();

?>