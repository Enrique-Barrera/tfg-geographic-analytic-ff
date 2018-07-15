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
            <!-- Load Esri Leaflet Geocoder from CDN -->
            <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.9/dist/esri-leaflet-geocoder.css">
            <?php
             if(isset($_GET['idCandidato'])){              
                $item = 'id_candidato';
                $valor1 = $_GET['idCandidato'];
                $candidato = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
                $valor2 = $candidato['latitud'];
                $valor3 = $candidato['longitud'];
              }
              else{
                $valor2 = 40.418889;
                $valor3 = -3.691944;
              } 
            ?>           

            <script>

              function redondeoDecimales(numero,decimales)
              {
                var original=parseFloat(numero);
                return numero.toFixed(decimales);
              }

              var $lat = <?php echo $valor2; ?>;
              var $long = <?php echo $valor3; ?>;  
              var map = L.map('map').
                 setView([$lat, $long], 12);
              L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>contributors',
                maxZoom: 17
              }).addTo(map);

              var searchControl = L.esri.Geocoding.geosearch().addTo(map);

              var results = L.layerGroup().addTo(map);

              searchControl.on('results', function(data){
                results.clearLayers();
                for (var i = data.results.length - 1; i >= 0; i--) {
                  //results.addLayer(L.marker(data.results[i].latlng));
                  var direccion = data.results[i];
                }
                onMapClick(direccion);
              });
              L.control.scale().addTo(map);  

              // attaching function on map click
              map.on('click', onMapClick);

              // Script for adding marker on map click
              function onMapClick(e) {

                  var geojsonFeature = {
                      "type": "Feature",
                          "properties": {},
                          "geometry": {
                              "type": "Point",
                              "coordinates": [e.latlng.lat, e.latlng.lng]
                      }
                  }

                  var marker;

                  L.geoJson(geojsonFeature, {        
                      pointToLayer: function(feature, latlng){                   
                          marker = L.marker(e.latlng, {                      
                              title: "Ubicación Candidato",
                              alt: "Ubicación Candidato",
                              riseOnHover: true,
                              draggable: false,
                          }).bindPopup("<h5><b>Nuevo Local</b></h5><h6>Latitud: "+redondeoDecimales(e.latlng.lat,6)+"</h6><h6>Longitud: "+redondeoDecimales(e.latlng.lng,6)+"</h6><input type='button' value='Copiar Coordenadas' class='btn btn-primary btn-xs btnGuardarCandidato'/><input type='button' value='Borrar Ubicación' class='btn btn-danger btn-xs btnBorrarCandidato'/>");
                          marker.on("popupopen", onPopupOpen);                               
                          return marker;
                      }
                  }).addTo(map);
              }

              // Function to handle delete as well as other events on marker popup open
              function onPopupOpen() {
                  var tempMarker = this;
                  //var tempMarkerGeoJSON = this.toGeoJSON();
                  //var lID = tempMarker._leaflet_id; // Getting Leaflet ID of this marker
                  $(".btnGuardarCandidato:visible").click(function () {
                      $('#inputLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#inputLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      console.log("TempMarker", tempMarker);
                      console.log("#inputLatitud",inputLatitud);
                  });
                  // To remove marker on click of delete
                  $(".btnBorrarCandidato:visible").click(function () {
                      map.removeLayer(tempMarker);
                  });
              }

            </script>
          </div>  <!-- /.box-body -->  <!-- /.box-header --> 
        </div> <!-- /.box-primary -->
      </div><!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Información del Nuevo Candidato a Apertura</h3>
          </div> <!-- /.box-header -->
          <!-- form start -->
          <form class="form-horizontal" method="post" name="FormNuevoLocal" id="FormNuevoLocal">
            <div class="box-body">
              <div class="form-group">
                <label for="inputDireccion" class="col-sm-2 control-label">Dirección</label>
                <div class="col-sm-10">            
                  <input type="txt" class="form-control input" name="inputDireccion" placeholder="Direccion Completa" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputLatitud" class="col-sm-2 control-label">Latitud</label>
                <div class="col-sm-4">
                  <input type="number" step="any" value="" class="form-control" id="inputLatitud" name="inputLatitud"  required>
                </div>
                <label for="inputLongitud" class="col-sm-2 control-label">Longitud</label>
                <div class="col-sm-4">
                  <input type="number" step="any" class="form-control" id="inputLongitud" name="inputLongitud" placeholder="Longitud"value="" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputNombre" class="col-sm-2 control-label">Nombre </label>
                <div class="col-sm-10">
                  <input type="txt" class="form-control" name="inputNombre" placeholder="Nombre Comercial" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputCadena" class="col-sm-2 control-label">Cadena</label>
                <div class="col-sm-10">
                  <select class="form-control input" name="inputCadena" required>
                    <option value="Cadena Target">Cadena Target</option>
                  </select>  
                </div>
              </div> 
              <div class="form-group">
                <label for="inputIdTipo" class="col-sm-2 control-label">Tipo Local</label>
                <div class="col-sm-10">
                  <select class="form-control input" name="inputIdTipo" required>
                    <option value="Restaurante Freestander">Restaurante Freestander</option>
                    <option value="Restaurante Instore">Restaurante Instore</option>
                  </select>  
                </div>
              </div>
              <div class="form-group">
                <label for="inputSuperficie" class="col-sm-2 control-label">Superficie</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="inputSuperficie" placeholder="Superficie en metros cuadrados" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputMesas" class="col-sm-2 control-label">Número de Mesas</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" name="inputMesas" placeholder="Nº Mesas" required>
                </div>
                <label for="inputCajas" class="col-sm-2 control-label">Número de Cajas</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" name="inputCajas" placeholder="Nº Cajas" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputInfantil" class="col-sm-2 control-label">Zona Juegos</label>
                <div class="col-sm-4">
                  <select class="form-control input" name="inputInfantil" required>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                  </select>  
                </div>            
                <label for="inputParking" class="col-sm-2 control-label">Parking</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" name="inputParking" placeholder="Nº Plazas de Parking" required>
                </div>
              </div>

            <div class="box-footer">
              <button type="reset" class="btn btn-danger pull-left btnIrInicio">Limpiar</button>
              <button type="submit" class="btn btn-info pull-right btnGuardarLocal">Guardar</button>
            </div><!-- /.box-footer -->
           <?php
              $crearNuevoLocal = new ControladorNuevoLocal();
              $crearNuevoLocal -> ctrCrearNuevoLocal();
            ?>    
          </form>
        </div>
      </div><!-- /.col-md-6 -->
    </div><!-- /.row-->
  </section>
</div><!-- /.content-wrapper -->
