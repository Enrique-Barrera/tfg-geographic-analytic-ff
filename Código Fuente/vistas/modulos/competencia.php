  

<div class="content-wrapper">
  <section class="content-header">   
    <h1>2.- Análisis de Competencia</h1>
    <ol class="breadcrumb">    
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Análisis de Competencia</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Distribución de Restaurantes de Comida Rápida</h3>
            </div>
            <div class="box-body" id="map" style='height:520px' data-mode="">
              <?php             
                  $item = 'id_candidato';
                  $valor1 = $_GET['idCandidato'];
                  $candidato = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
                  $valor2 = $candidato['latitud'];
                  $valor3 = $candidato['longitud'];
                  $geojson = array(
                    'type' => 'FeatureCollection',
                    'features'  => array()
                  );  
                  $competenciaMapa = ControladorCompetencia::ctrMostrarCompetenciaMapa($item, $valor1, $valor2, $valor3);    
                  foreach ($competenciaMapa as $key => $value){
                    $feature = array(
                      'type' => 'Feature',
                      'geometry' => array(
                        'type' => 'Point',
                        'coordinates' => array($value['longitud'], $value['latitud'])
                      ),
                      'properties' => array(
                        'id' => $value['id_competencia'],
                        'nombre' => $value['nombre'],
                        'cadena' => $value['competencia_nombre'],                        
                        'id_cadena' => $value['id_cadena']
                      )
                    );
                    array_push($geojson['features'], $feature);
                  }
              ?>
              <script>   
              var icons = {
                  'Bocatta':  L.icon({  
                    iconUrl: 'vistas/dist/img/boc-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Burger King': L.icon({ 
                    iconUrl: 'vistas/dist/img/bur-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Dominos':  L.icon({ 
                    iconUrl: 'vistas/dist/img/dom-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Fast Food':  L.icon({ 
                    iconUrl: 'vistas/dist/img/fas-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Foster':  L.icon({ 
                    iconUrl: 'vistas/dist/img/fos-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Lizarran': L.icon({ 
                    iconUrl: 'vistas/dist/img/liz-icono.png', markerColor: 'red', iconSize: [25,38]}),                                                           
                  'Mc Donalds': L.icon({ 
                    iconUrl: 'vistas/dist/img/mcd-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  '100 Montaditos':  L.icon({ 
                    iconUrl: 'vistas/dist/img/mon-icono.png', markerColor: 'red', iconSize: [25,38]}),                   
                  'Pans And Company':  L.icon({ 
                    iconUrl: 'vistas/dist/img/pan-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Rodilla': L.icon({ 
                    iconUrl: 'vistas/dist/img/rod-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Subway':  L.icon({ 
                    iconUrl: 'vistas/dist/img/sub-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Cadena Target':  L.icon({ 
                    iconUrl: 'vistas/dist/img/tar-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'Telepizza':  L.icon({ 
                    iconUrl: 'vistas/dist/img/tel-icono.png', markerColor: 'red', iconSize: [25,38]}),
                  'VIPS':  L.icon({ 
                    iconUrl: 'vistas/dist/img/vip-icono.png', markerColor: 'red', iconSize: [25,38]}),                                                                                                                                                                                          
              };

              var cStyle = { 
                color: '#3A92C8',
                fillColor: '#ffffff',
              };

                var iconotarget = L.icon({ 
                    iconUrl: 'vistas/dist/img/tar-icono.png', markerColor: 'red', iconSize: [45,66]});
                var $lat = <?php echo $valor2; ?>;
                var $long = <?php echo $valor3; ?>;  
                var competencia = <?php echo json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
                var map = L.map('map').
                setView([$lat, $long], 15);
                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                  maxZoom: 18
                }).addTo(map); 

                new L.geoJSON(competencia, {
                  pointToLayer: function (feature, latlng) {
                  marker = L.marker(latlng, {
                      icon: icons[feature.properties.id_cadena]
                  }).bindPopup("<h5><b>Fast Food</b></h5><h5>"+feature.properties.nombre+"</h5><h5>"+feature.properties.cadena+"</h5>");
                  marker.on("popupopen", onPopupOpen);  
                  console.log("Marker", marker);
                  return marker;
                } 

                }).addTo(map);
                L.circle([$lat, $long], 300, cStyle).addTo(map);
                L.marker([$lat, $long], {icon: iconotarget}).addTo(map);
                L.control.scale().addTo(map); 

                function onPopupOpen() {
                  var tempMarker = this;
                  //var tempMarkerGeoJSON = this.toGeoJSON();
                  //var lID = tempMarker._leaflet_id; // Getting Leaflet ID of this marker
                  // To remove marker on click of delete
                  $(".btnLimpiarRestaurante:visible").click(function () {
                      map.removeLayer(tempMarker);
                  });
              }
              </script>
            </div>    <!-- /.box-body -->
        </div><!-- /.box-primary-->
      </div> <!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Restaurantes Más Cercanos</h3>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed table-hover dt-responsive  tablas">
              <thead> 
                <tr>
                  <th style="width: 40px">#</th>
                  <th>Restaurante</th>
                  <th>Distancia</th>
                  <th>Metros</th>
                </tr>   
              </thead> 
              <tbody>             
                    <?php
                    $item = 'id_candidato';
                    $valor1 = $_GET['idCandidato'];
                    $candidato = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
                    $valor2 = $candidato['latitud'];
                    $valor3 = $candidato['longitud'];
                    $competencia = ControladorCompetencia::ctrMostrarCompetencia($item, $valor1, $valor2, $valor3);                          
                    foreach ($competencia as $key => $value){
                      $ratio =  $value["distance"]/10;                        echo '<tr>
                              <td>'.$value["id_competencia"].'</td>
                              <td>'.$value["nombre"].'</td>
                              <td><div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-blue" style="width:'.$ratio.'%"</div></div></td>
                              <td align="right" ><span class="badge bg-blue">'.round($value["distance"],0).' m</span></td>
                            </tr>';
                    }     
                    ?> 
              </tbody>
            </table>
          </div><!-- /.box-body -->  
        </div><!-- /.box -->
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Indice de Competencia</h3>
            </div>
          </div> <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th style="width: 10px">#</th>
                <th>Indice</th>
                <th>Valor</th>
                <th style="width: 40px">Label</th>
              </tr>
              </tr>
                  <?php
                  $item = 'id_candidato';
                  $valor = $_GET['idCandidato'];
                  $indices = ControladorAtractores::ctrMostrarIndices($item, $valor);  
                  $indicesMaximos = ControladorAtractores::ctrMostrarIndicesMaximos($item, $valor);              
                  $indiceCompetencia = round(100* $indices["A1_COMPETENCIA"]/ $indicesMaximos["A1_INDICECOMPETENCIAMAX"],2);                                                                                                           
                    echo '<tr>
                            <td>1.</td>
                            <td>Competencia</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-blue" style="width:'.$indiceCompetencia.'%"s></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceCompetencia.'%</span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->        
        <div class="box-footer">
          <form class="form-inline" method="post" id="formAtractores">
              <input type="hidden" name="Estado3" value="Estado3"></input> 
              <button style='width:19.6%;' type="button" class="btn btn-success  btnIrLocal" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">1.- Ubicación</button>        
              <button style='width:19.6%;' type="button" class="btn btn-success" disabled>2.- Competencia</button>            
              <button style='width:19.6%;' type="submit" class="btn <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2' ) {
                                                      echo ' btn-danger ';
                                                    }
                                                    else {
                                                      echo ' btn-success ';
                                                    }
                                             ?>    
              btnIrAtractores" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">3.- Atracción</button>    
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
          </form>      
        </div>                           
        </div>
         <?php
            $ejecutarWizard2 = new ControladorWizard();
            $ejecutarWizard2 -> ctrEjecutarWizard2();
         ?>         
      </div> <!-- /.col-md-6 -->
    </div> 
  </section>
</div>
