<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Página de Inicio 
      <small>Candidatos Analizados</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Candidatos</li>
    </ol>
  </section>
  <section class="content">

    <div class="row" >
      <div class="col-md-7">
        <div class="box box-primary">
          <div class="box-header with-border ">
            <table id="tableCabecera" width='100%'>
              <td>
              <th width='75%' align:"left"><h3 class="box-title">Histórico de Candidatos Analizados</h3></th>
              <th width='25%' align:"right">
                <button  class="btn btn-primary pull-right btnCandidato">Agregar Candidato</button></th>
              </td>
            </table>
          </div>
          <div class="box-body">           
           <table id="tableCandidatos" class="table table-bordered table-striped dt-responsive tablas" width: 100%>           
            <thead>
             <tr>               
               <th>#</th>
               <th>Nombre Establecimiento</th>
               <th>Estado</th>
               <th>Fecha Modificación</th>
               <th>Acciones</th>
             </tr> 
            </thead>
            <tbody>
              <?php
              $item = null;
              $valor = null;
              $candidatos = ControladorInicio::ctrMostrarCandidatosInicio($item, $valor);
              foreach ($candidatos as $key => $value){
              
                echo ' <tr>
                <td>'.$value["ID_CANDIDATO"].'</td>
                <td>'.$value["NOMBRE"].'</td>';

                switch ($value["ID_ESTADO"]) {
                  case "2":
                  echo '<td><button class="btn btn-danger btn-xs btnNavegar" idCandidato="'.$value["ID_CANDIDATO"].'" idEstado=2>2.- Competencia</button></td>';
                  break;
                  case "3":
                  echo '<td><button class="btn btn-danger btn-xs btnNavegar" idCandidato="'.$value["ID_CANDIDATO"].'" idEstado=3>3.- Atracción</button></td>';
                  break;
                  case "4":
                  echo '<td><button class="btn btn-danger btn-xs btnNavegar" idCandidato="'.$value["ID_CANDIDATO"].'" idEstado=4>4.- Captación</button></td>';
                  break;
                  case "5":
                  echo '<td><button class="btn btn-success btn-xs btnNavegar" idCandidato="'.$value["ID_CANDIDATO"].'" idEstado=5>5.- Evaluación</button></td>';
                  break;
                  default:
                  echo '<td><button class="btn btn-danger btn-xs btnNavegar" idCandidato="'.$value["ID_CANDIDATO"].'" idEstado=1>1.- Nueva Ubicación</button></td>';

                };

                echo '<td>'.$value["FECHA_UPDATE"].'</td>
                <td>
                  <div class="btn-group">  
                    <button type="button" class="btn btn-danger btnEliminarCandidatoInicio" idCandidato="'.$value["ID_CANDIDATO"].'"><i class="fa fa-times fa-lg"></i></button>
                  </div>  
                </td>
                </tr>';
              }
              ?> 
              <?php
                $borrarUsuario = new ControladorInicio();
                $borrarUsuario -> ctrBorrarCandidatoInicio();
              ?> 
            </tbody>
           </table>
          </div><!--- box-body -->
        </div><!--- box-primary-->
      </div> <!--- col-md-7 -->

      <div class="col-md-5">
          <?php
          include "graficos/grafico-candidatos.php";
          include "graficos/grafico-estados.php";
          ?>
      </div> <!--- col-md-5 -->       
             
    </div> <!--- class-row -->
  </section>
</div>



