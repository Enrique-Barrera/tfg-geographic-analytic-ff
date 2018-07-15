<?php

class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=pfg;charset=utf8",
			            "root",
			            "");

		$link->exec("SET NAMES 'utf8'");

		return $link;

	}

}