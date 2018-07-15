

/*=============================================
FUNCION ACTUALIZAR PROGRESS BAR
=============================================*/

$('#myModal').on('shown.bs.modal', function () {


	var progress = setInterval(function () {
		var query = window.location.search.substring(1);
		var vars = query.split("&");
		var idcandidato = "0";
		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split("=");
			if (pair[0] == "idCandidato") {
				idCandidato = pair[1];
			}
		}
		var $bar = $('.bar');
		if ($bar.width() == 500) {
			clearInterval(progress);
			$('.progress').removeClass('active');
			$('#myModal').modal('hide');
			$bar.width(0);
			window.location = "index.php?ruta=competencia&idCandidato=" + idCandidato + "&idEstado=2";
		} else {
			var datos = new FormData();
			datos.append("idCandidato", idCandidato);
			$.ajax({
			url: "ajax/nuevolocal.ajax.php",
				method: "POST",
					data: datos,
					cache: false,
					contentType: false,
					processData: false,
					dataType: "json",
					success: function(respuesta) {
						console.log("respuesta", respuesta);
						porcentaje = respuesta["porcentaje"];
						mensaje = respuesta["mensaje"];
						console.log("mensaje", mensaje);
						$("#mensajehtml").html(mensaje);
					}
		});
			$bar.width(porcentaje * 5);
			$bar.text(porcentaje + "%");
		}
	}, 800);


})


/*=============================================
BOTON EDITAR NUEVO LOCAL
=============================================*/
//$("#tableFooterNuevoLocal").on("click", ".btnIrNuevoLocal", function(){
$(".btnIrNuevoLocal").click(function () {
	var idCandidato = $(this).attr("idCandidato");
	var idEstado = $(this).attr("idEstado");
	console.log("Nuevo Local - ID Candidato: ", idCandidato);
	console.log("Nuevo Local - ID Estado: ", idEstado);
	var datos = new FormData();
	datos.append("idCandidato", idCandidato);
    console.log("Variable datos Entrada: ");  
	datos.forEach((value, key) => {
		console.log(key + " " + value)
	});
	$.ajax({
	
		url:"ajax/nuevolocal.ajax.php",
		method: "POST",
		data: datos, idCandidato,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log("respuesta", respuesta);
			$("#editarCandidato").val(respuesta["id_candidato"]);
			$("#editarDireccion").val(respuesta["direccion1"]);
			$("#editarLatitud").val(respuesta["latitud"]);
			$("#editarLongitud").val(respuesta["longitud"]);
			$("#editarNombre").val(respuesta["nombre"]);
			$("#editarCadena").html(respuesta["id_cadena"]);
			$("#editarCadena").val(respuesta["id_cadena"]);
			$("#editarSuperficie").val(respuesta["superficie"]);			
			$("#editarMesas").val(respuesta["numeromesas"]);			
			$("#editarCajas").val(respuesta["numerocajas"]);
			$("#editarInfantil").html(respuesta["zonainfantil"]);
			$("#editarInfantil").val(respuesta["zonainfantil"]);
			$("#editarParking").val(respuesta["numeroparking"]);	
			$("#editarIdTipo").html(respuesta["id_candidato_tipo"]);	
			$("#editarIdTipo").val(respuesta["id_candidato_tipo"]);	
			idCandidato = 	respuesta["id_candidato"];		
			console.log("Id Candidato Post: ", idCandidato);				
		}
	});
	window.location = "index.php?ruta=local&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})
