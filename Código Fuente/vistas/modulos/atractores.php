<div class="content-wrapper">
  <section class="content-header">  
    <h1>3.- Análisis de Atractores</h1>
    <ol class="breadcrumb">     
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Atractores</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row" >
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Área de Atracción Primaria</h3>
          </div>
          <div class="box-body" id="map" style='height:520px' data-mode="">
          <input type="hidden" data-map-markers="" value="" name="map-geojson-data" />
            <?php
              $item = 'id_candidato';
              $valor1 = $_GET['idCandidato'];
              $candidato = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
              $valor2 = $candidato['latitud'];
              $valor3 = $candidato['longitud'];

              $geojson1 = array(
                'type' => 'FeatureCollection',
                'features'  => array()
              );  

              $atractoresMapa = ControladorAtractores::ctrMostrarAtractoresMapa($item, $valor1, $valor2, $valor3);  

              foreach ($atractoresMapa as $key => $row){
              $feature = array(
                'type' => 'Feature',
                'geometry' => array(
                  'type' => 'Point',
                  'coordinates' => array($row['longitud'], $row['latitud'])
                ),
                'properties' => array(
                  'id' => $row['id_atractor'],
                  'nombre' => $row['nombre'],
                  'familia' => $row['atractor_familia_nombre'],
                  'cadena' => $row['id_atractor_actividad']
                )
              );
              array_push($geojson1['features'], $feature);
              }

            ?>
            <script> 

              var atractores = <?php echo json_encode($geojson1,JSON_NUMERIC_CHECK); ?>;             
              
              function redondeoDecimales(numero,decimales)
              {
                var original=parseFloat(numero);
                return numero.toFixed(decimales);
              }

              var icons = {
                  'Comercio': L.AwesomeMarkers.icon({ 
                    icon: 'shopping-basket', markerColor: 'darkpurple', prefix: 'fa', iconColor: 'white'}),
                  'Fast Food': L.AwesomeMarkers.icon({
                    icon: 'coffee', markerColor: 'darkred', prefix: 'fa', iconColor: 'white'}),
                  'Global': L.AwesomeMarkers.icon({
                    icon: 'sync', markerColor: 'darkgreen',  prefix: 'fa', iconColor: 'white'}),
                  'Gran Superficie': L.AwesomeMarkers.icon({
                    icon: 'cart-plus', markerColor: 'orange',  prefix: 'fa', iconColor: 'white'}),
                  'Hoteles': L.AwesomeMarkers.icon({
                    icon: 'h-square', markerColor: 'purple',  prefix: 'fa', iconColor: 'white'}),
                  'Ocio': L.AwesomeMarkers.icon({
                    icon: 'music', markerColor: 'cadetblue', prefix: 'fa', iconColor: 'white'}),                                                          
                  'Restauracion y Bares': L.AwesomeMarkers.icon({ 
                    icon: 'cutlery', markerColor: 'red', prefix: 'fa', iconColor: 'white'}),
                  'Salud': L.AwesomeMarkers.icon({
                    icon: 'medkit', markerColor: 'green', prefix: 'fa', iconColor: 'white'}),
                  'Turismo': L.AwesomeMarkers.icon({
                    icon: 'paper-plane', markerColor: 'blue',  prefix: 'fa', iconColor: 'white'}),                                                                                                                                                                                        
              }
              
              var cStyle = { 
                color: '#3A92C8',
                fillColor: '#ffffff',
              };

              var $lat = <?php echo $valor2; ?>;
              var $long = <?php echo $valor3; ?>;  
              var map = L.map('map').
              setView([$lat, $long], 16);
              L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                maxZoom: 18
              }).addTo(map);
              L.circle([$lat, $long], 300, cStyle).addTo(map);
              new L.geoJSON(atractores, {
                pointToLayer: function (feature, latlng) {
                  marker = L.marker(latlng, { 
                      icon: icons[feature.properties.familia], weight: 1, opacity: 1, fillOpacity:0.8
                  }).bindPopup("<h5><b>Atractor</b></h5><h5>"+feature.properties.nombre+"</h5><h5>"+feature.properties.familia+"</h5>");
                  marker.on("popupopen", onPopupOpen);  
                  console.log("Marker", marker);
                  return marker;
                } 
              }).addTo(map);   
              L.marker([$lat, $long]).addTo(map);
              L.control.scale().addTo(map);  

              function onPopupOpen() {
                  var tempMarker = this;
                  //var tempMarkerGeoJSON = this.toGeoJSON();
                  //var lID = tempMarker._leaflet_id; // Getting Leaflet ID of this marker
                  // To remove marker on click of delete
                  $(".btnLimpiarAtractor:visible").click(function () {
                      map.removeLayer(tempMarker);
                  });
              }
            </script>
          </div> <!-- /.box-body -->
        </div><!-- /.box-primary-->
      </div> <!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Indices de Atracción</h3>
            </div>  
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th style="width: 10px">#</th>
                <th>Índice</th>
                <th>Valor</th>
                <th style="width: 40px">Label</th>
              </tr>
                <?php
                  $item = 'id_candidato';
                  $valor = $_GET['idCandidato'];
                  $indices = ControladorAtractores::ctrMostrarIndices($item, $valor);  
                  $indicesMaximos = ControladorAtractores::ctrMostrarIndicesMaximos($item, $valor);              
                  $indiceOcio = round(100* $indices["A1_INDICEOCIO"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceComercio = round(100* $indices["A1_INDICECOMERCIO"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceSalud = round(100* $indices["A1_INDICESALUD"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceHoteles = round(100* $indices["A1_INDICEHOTELES"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceRestaurantes = round(100* $indices["A1_INDICERESTAURANTES"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceTurismo = round(100* $indices["A1_INDICETURISMO"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceGranSuperficie = round(100* $indices["A1_INDICEGRANSUPERFICIE"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);
                  $indiceGlobal = round(100* $indices["A1_INDICEOCIO"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);                                                                                                           
                    echo '<tr>
                            <td>1.</td>
                            <td>Ocio</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceOcio.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceOcio.'%</span></td>
                          </tr>
                          <tr>
                            <td>2.</td>
                            <td>Comercio</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceComercio.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceComercio.'%</span></td>
                          </tr>
                          <tr>
                            <td>3.</td>
                            <td>Salud</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceSalud.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceSalud.'%</span></td>
                          </tr>
                          <tr>
                            <td>4.</td>
                            <td>Hoteles</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceHoteles.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceHoteles.'%</span></td>
                          </tr>
                          <tr>
                            <td>5.</td>
                            <td>Restauración</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceRestaurantes.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceRestaurantes.'%</span></td>
                          </tr>
                          <tr>
                            <td>6.</td>
                            <td>Turismo</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width: '.$indiceTurismo.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceTurismo.'%</span></td>
                          </tr>
                          <tr>
                            <td>7.</td>
                            <td>Grandes Superficies</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width: '.$indiceGranSuperficie.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceGranSuperficie.'%</span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Indice de Atracción Global</h3>
            </div>
          </div>
          <!-- /.box-header -->
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
                  $indiceGlobal = round(100* $indices["A1_INDICEOCIO"]/ $indicesMaximos["A1_INDICEOCIOMAX"],2);                                                                                                           
                    echo '<tr>
                            <td>8.</td>
                            <td>Global</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceGlobal.'%"s></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceGlobal.'%</span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box-footer">
          <form class="form-inline" method="post" id="formAreaPrimaria">   
            <input type="hidden" name="Estado4" value="Estado4"></input> 
              <button style='width:19.6%;' type="button" class="btn btn-success  btnIrLocal" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">1.- Ubicación</button>        
              <button style='width:19.6%;' type="button" class="btn btn-success  btnIrCompetenciaBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">2.- Competencia</button>   
              <button style='width:19.6%;' type="button" class="btn btn-success" disabled>3.- Atracción</button>    
              <button style='width:19.6%;' type="submit" class="btn <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2' or $_GET['idEstado'] == '3') {
                                                        echo ' btn-danger ';
                                                      }
                                                      else {
                                                        echo ' btn-success ';
                                                      }
                                              ?>   
              btnIrAreaPrimaria" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">4.- Areas Captación</button>
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
            $ejecutarWizard3 = new ControladorWizard();
            $ejecutarWizard3 -> ctrEjecutarWizard3();
         ?>           
      </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
  </section>
</div><!-- /.content-wrapper -->

