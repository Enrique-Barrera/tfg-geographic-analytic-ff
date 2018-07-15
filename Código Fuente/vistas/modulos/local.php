<div class="content-wrapper">
  <section class="content-header">  
    <h1>
      1.- Información Nuevo Local
    </h1>
    <ol class="breadcrumb">    
      <li><a href="#"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active" class="fa fa-home">Nuevo Local</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row" >
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Ubicar Punto en el Mapa</h3>
          </div>
          <div class="box-body" id="map" style='height:520px' data-mode="">
            <input type="hidden" data-map-markers="" value="" name="map-geojson-data" />

            <?php            
                $item = 'id_candidato';
                $valor1 = $_GET['idCandidato'];
                $ubicacion = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
                $valor2 = $ubicacion['latitud'];
                $valor3 = $ubicacion['longitud'];
            ?>           
            <script>
              var $lat = <?php echo $valor2; ?>;
              var $long = <?php echo $valor3; ?>;  
              var map = L.map('map').
                 setView([$lat, $long], 12);
              L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>contributors',
                maxZoom: 15
              }).addTo(map);
              L.marker([$lat, $long]).addTo(map);
              L.control.scale().addTo(map);  
            </script>
          </div>  <!-- /.box-body -->  <!-- /.box-header --> 
        </div> <!-- /.box-primary -->
      </div><!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Información del Candidato a Apertura</h3>
            </div> <!-- /.box-header --> 
          </div><!-- /.boxprimary -->  
            <?php
                $item = 'id_candidato';
                $valor = $_GET['idCandidato'];
                $candidato = ControladorNuevoLocal::ctrMostrarNuevoLocal($item, $valor);
            ?> 
          <div class="box-body">
          <table>
            <td>
            <form class="form-horizontal" method="post"  name="FormEditarLocal" id="FormEditarLocal">
              <div class="box-body">
                <div class="form-group">
                  <label for="editarCandidato" class="col-sm-2 control-label">Id</label>
                  <div class="col-sm-2">            
                    <input type="txt" class="form-control input" id="editarCandidato" name="editarCandidato" value="<?= $candidato['id_candidato'];?>" readonly>
                  </div>
                  <label for="editarDireccion" class="col-sm-2 control-label">Dirección</label>
                  <div class="col-sm-6">            
                    <input type="txt" class="form-control input" id="editarDireccion" name="editarDireccion" value="<?= $candidato['direccion1'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="editarLatitud" class="col-sm-2 control-label">Latitud</label>
                  <div class="col-sm-4">
                    <input type="float" class="form-control" name="editarLatitud" id="editarLatitud" value="<?= $candidato['latitud'];?>" required readonly>
                  </div>
                  <label for="editarLongitud" class="col-sm-2 control-label">Longitud</label>
                  <div class="col-sm-4">
                    <input type="float" class="form-control" id="editarLongitud" name="editarLongitud" value="<?= $candidato['longitud'];?>" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editarNombre" class="col-sm-2 control-label">Nombre </label>
                  <div class="col-sm-10">
                    <input type="txt" class="form-control" id="editarNombre" name="editarNombre" value="<?= $candidato['nombre'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editarCadena" class="col-sm-2 control-label">Cadena</label>
                  <div class="col-sm-10">
                    <select class="form-control input" name="editarCadena" required>
                      <option value="TAR">Cadena Target</option>
                    </select>  
                  </div>
                </div> 
                <div class="form-group">
                  <label for="editarIdTipo" class="col-sm-2 control-label">Tipo Local</label>
                  <div class="col-sm-10">
                    <select class="form-control input"  name="editarIdTipo" required>
                      <option value="<?= $candidato['id_candidato_tipo'];?>" name="editarIdTipo"><?= $candidato['id_candidato_tipo'];?></option>
                      <option value="Restaurante Freestander">Restaurante Freestander</option>
                      <option value="Restaurante Instore">Restaurante Instore</option>
                    </select>  
                  </div>
                </div>              
                <div class="form-group">
                  <label for="editarSuperficie" class="col-sm-2 control-label">Superficie</label>
                  <div class="col-sm-10">
                    <input type="int" class="form-control" id="editarSuperficie"  name="editarSuperficie" value="<?= $candidato['superficie'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editarMesas" class="col-sm-2 control-label">Número de Mesas</label>
                  <div class="col-sm-4">
                    <input type="int" class="form-control" id="editarMesas" name="editarMesas" value="<?= $candidato['numeromesas'];?>" required>
                  </div>
                  <label for="editarCajas" class="col-sm-2 control-label">Número de Cajas</label>
                  <div class="col-sm-4">
                    <input type="int" class="form-control" id="editarCajas" name="editarCajas" value="<?= $candidato['numerocajas'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editarInfantil" class="col-sm-2 control-label">Zona Juegos</label>
                  <div class="col-sm-4">
                    <select class="form-control input" name="editarInfantil" required>
                      <option value="<?= $candidato['zonainfantil'];?>" id="editarInfantil"><?= $candidato['zonainfantil'];?></option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                    </select>  
                  </div>            
                  <label for="editarParking" class="col-sm-2 control-label">Parking</label>
                  <div class="col-sm-4">
                    <input type="int" class="form-control" name="editarParking" id="editarParking" value="<?= $candidato['numeroparking'];?>">
                  </div>
                </div>

              <div class="box-footer">
                <button style="width=150px" type="button" class="btn btn-primary btnIrInicio" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">Salir</button>
                <button type="submit" class="btn btn-info pull-right btnModificarNuevoLocal" idCandidato="<?php $_GET['idCandidato']; ?>" idEstado="<?php $_GET['idEstado']; ?>">Modificar</button>
              </div><!-- /.box-footer --> 
                <?php
                    $actualizarLocal = new ControladorNuevoLocal();
                    $actualizarLocal -> ctrActualizarNuevoLocal();
                ?> 
            </form>
            </td>
            </table>
           </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box-footer">
          <form class="form-inline" onsubmit="openModal()" method="post" id="formCompetencia">
            <input type="hidden" name="Estado2" value="Estado2"></input>
            <button style='width:19.6%;' type="button" class="btn btn-success" disabled>1.- Ubicación</button>
            
            <button style='width:19.6%;' type="submit" class="btn <?php if ($_GET['idEstado'] == '1') {
                                                      echo ' btn-danger btnIrCompetencia"';
                                                    }
                                                    else {
                                                      echo ' btn-success btnIrCompetencia"';
                                                    }
                                             ?>           
             idCandidato="<?php  echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>" >2.- Competencia</button> 
            <button style='width:19.6%;' type="button" <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2') {
                                                      echo ' class="btn btn-primary btnIrAtractoresBack" disabled';
                                                    }
                                                    else {
                                                      echo ' class="btn btn-success btnIrAtractoresBack"';
                                                    }
                                             ?>  idCandidato="<?php  echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">3.- Atracción</button>
            <button style='width:19.6%;' type="button" <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2'or $_GET['idEstado'] == '3') {
                                                      echo ' class="btn btn-primary btnIrAreaPrimariaBack" disabled';
                                                    }
                                                    else {
                                                      echo ' class="btn btn-success btnIrAreaPrimariaBack"';
                                                    }
                                             ?>  idCandidato="<?php  echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">4.- Captación</button>
            <button style='width:19.6%;' type="button" <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2'or $_GET['idEstado'] == '3' or $_GET['idEstado'] == '4') {
                                                      echo ' class="btn btn-primary btnIrResultadoBack" disabled';
                                                    }
                                                    else {
                                                      echo ' class="btn btn-success btnIrResultadoBack"';
                                                    }
                                             ?>  idCandidato="<?php  echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">5.- Valoración</button>
          </form>    
        </div>
         <?php
            $ejecutarPython = new ControladorWizard();
            $ejecutarPython -> ctrEjecutarWizard1();
         ?> 
      </div><!-- /.col-md-6 -->
    </div><!-- /.row-->
  </section>
</div><!-- /.content-wrapper -->


<!-- Modal -->
<div style="top:25%" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Procesando Datos de Nueva Ubicación</h4>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>Por Favor Espere....</h4>
      </div>
      <div class="modal-body center-block">
        <p>Grado de Avance:</p>
        <div class="progress">
          <div class="progress-bar bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">   
          </div>
        </div>
        <div id="mensajehtml"><b>Iniciando Proceso de Actualización</b></div>  
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
