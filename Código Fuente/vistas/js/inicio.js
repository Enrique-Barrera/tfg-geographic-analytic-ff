
/*=============================================
ELIMINAR USUARIO
=============================================*/
$("#tableCandidatos").on("click", ".btnEliminarCandidatoInicio", function(){

  var idCandidato = $(this).attr("idCandidato");

  swal({
    title: '¿Está seguro de borrar el punto candidato?',
    text: "¡Si no lo está puede cancelar la accíón!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar Candidato!'
  }).then((result)=>{
    if(result.value){
      window.location = "index.php?ruta=inicio&idCandidato=" + idCandidato + "&Borrar=Si";

    }

  })

})

/*=============================================
NAVEGACION DE MENU
=============================================*/

$("#tableCandidatos").on("click", ".btnNavegar", function () {

  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");

  switch (idEstado) {
    case "2":
      window.location = "index.php?ruta=competencia&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
      break;
    case "3":
      window.location = "index.php?ruta=atractores&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
      break;
    case "4":
      window.location = "index.php?ruta=areaprimaria&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
      break;
    case "5":
      window.location = "index.php?ruta=resultado&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
      break;
    default:
      window.location = "index.php?ruta=local&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
  }
})

/*=============================================
NAVEGACION A NUEVO LOCAL BOTON AGREGAR CANDIDATO
=============================================*/

$('#tableCabecera').on('click', '.btnCandidato', function () {
  window.location = "index.php?ruta=nuevolocal";
})

/*=============================================
NAVEGACION A INICIO BOTON WIZARD
=============================================*/

$(".btnIrInicio").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=inicio";
})

/*=============================================
NAVEGACION A LOCAL BOTON WIZARD
=============================================*/

$(".btnIrLocal").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=local&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})

/*=============================================
NAVEGACION A COMPETENCIA DESDE ATRACTORES BOTON WIZARD
=============================================*/

$(".btnIrCompetenciaBack").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=competencia&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})

/*=============================================
NAVEGACION A ATRACTORES DESDE AREA PRIMARIA BOTON WIZARD
=============================================*/

$(".btnIrAtractoresBack").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=atractores&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})

/*=============================================
NAVEGACION A AREA PRIMARIA DESDE RESULTADO BOTON WIZARD
=============================================*/

$(".btnIrAreaPrimariaBack").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=areaprimaria&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})

/*=============================================
NAVEGACION A AREA PRIMARIA DESDE RESULTADO BOTON WIZARD
=============================================*/

$(".btnIrResultadoBack").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=resultado&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})

/*=============================================
NAVEGACION A ATRACTORES BOTON WIZARD
=============================================

$(".btnIrAtractores").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=atractores&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})
*/

/*=============================================
NAVEGACION A AREA PRIMARIA BOTON WIZARD
=============================================

$(".btnIrAreaPrimaria").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=areaprimaria&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})
*/
/*=============================================
NAVEGACION A RESULTADO BOTON WIZARD
=============================================

$(".btnIrResultado").click(function () {
  var idCandidato = $(this).attr("idCandidato");
  var idEstado = $(this).attr("idEstado");
  window.location = "index.php?ruta=resultado&idCandidato=" + idCandidato + "&idEstado=" + idEstado;
})
*/



