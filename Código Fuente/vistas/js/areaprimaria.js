

/*=============================================
FUNCION ACTUALIZAR PROGRESS BAR
=============================================*/

$('#Wizard4').on('shown.bs.modal', function () {


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
			$('#Wizard4').modal('hide');
			$bar.width(0);
			window.location = "index.php?ruta=resultado&idCandidato=" + idCandidato + "&idEstado=5";
		} else {
			var datos = new FormData();
			datos.append("idCandidato", idCandidato);
			$.ajax({

			url: "ajax/areaprimaria.ajax.php",
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



