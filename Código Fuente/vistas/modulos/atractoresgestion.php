<div class="content-wrapper">
  <section class="content-header">   
    <h1>Gestión de Atractores</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Gestión de Atractores</li>
    </ol>
  </section>
 
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Distribución de Atractores</h3>
            </div>
            <div class="box-body" id="map" style='height:520px' data-mode="">
            <input type="hidden" data-map-markers="" value="" name="map-geojson-data" />
            <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.9/dist/esri-leaflet-geocoder.css">
            <?php             
              $geojson = array(
                'type' => 'FeatureCollection',
                'features'  => array()
              );  
              $atractoresMapa = ControladorAtractoresGestion::ctrMostrarAtractoresMapaTodo();    
              foreach ($atractoresMapa as $key => $value){
                $feature = array(
                  'type' => 'Feature',
                  'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array($value['longitud'], $value['latitud'])
                  ),
                  'properties' => array(
                    'id' => $value['id_atractor'],
                    'nombre' => $value['nombre'],
                    'familia'=> $value['atractor_familia_nombre'],
                    'tipo' => $value['atractor_nombre'],
                    'reviews' => $value['reviews'],
                    'direccion' => $value['direccion1']                   
                  )
                );
                array_push($geojson['features'], $feature);
              }
            ?> 
            <script>          
              
              var atractores = <?php echo json_encode($geojson,JSON_NUMERIC_CHECK); ?>;

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

              var osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>',
                  thunLink = '<a href="http://thunderforest.com/">Thunderforest</a>',
                  EsriLink = '<a href="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/">Esri World Map</a>',
                  MtbLink = '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
              
              var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                  osmAttrib = '&copy; ' + osmLink + ' Contributors',
                  landUrl = 'http://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png',
                  thunAttrib = '&copy; '+osmLink+' Contributors & '+thunLink,
                  EsriUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                  EsriAttrib = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                  MtbUrl ='http://tile.mtbmap.cz/mtbmap_tiles/{z}/{x}/{y}.png',
                  MtbAttrib = '&copy; '+ MtbLink +' USGS'; 

              var osmMap = L.tileLayer(osmUrl, {attribution: osmAttrib}),
                  landMap = L.tileLayer(landUrl, {attribution: thunAttrib}),  
                  esriMap = L.tileLayer(EsriUrl, {attribution: EsriAttrib}), 
                  MtbMap = L.tileLayer(MtbUrl, {attribution: MtbAttrib});         

              var geojsonMarkerOptions = {
                    radius: 8,
                    fillColor: "#ff7800",
                    color: "#000",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                  };

              var map = L.map('map', {
                layers: [osmMap] // only add one!
              })
              .setView([40.418889, -3.691944], 15);

              var baseLayers = {
                "OSM Mapnik": osmMap,
                "Landscape": landMap,
                "Esri World": esriMap,
                "MtbMap" : MtbMap
              };

              new L.geoJSON(atractores, {
                pointToLayer: function (feature, latlng) {
                  marker = L.marker(latlng, { 
                      icon: icons[feature.properties.familia], weight: 1, opacity: 1, fillOpacity:0.8
                  }).bindPopup("<h5><b>Atractor Existente</b></h5><h5>"+feature.properties.nombre+"</h5><h5>"+feature.properties.familia+"</h5><input type='button' value='Editar' class='btn btn-primary btn-xs btnEditarAtractor'/><input type='button' value='Eliminar' class='btn btn-danger btn-xs btnBorrarAtractor'/>");
                  marker.on("popupopen", onPopupOpen);  
                  return marker;
                } 
              }).addTo(map);      
              L.control.layers(baseLayers).addTo(map);            
              L.control.scale().addTo(map);  
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
                          marker2 = L.marker(e.latlng, {                            
                              title: "Ubicación Atractor",
                              alt: "Ubicación Atractor",
                              riseOnHover: true,
                              draggable: false
                          }).bindPopup("<h5><b>Nuevo Atractor</b></h5><h6></h6><h6>Latitud: "+redondeoDecimales(e.latlng.lat,6)+"</h6><h6>Longitud: "+redondeoDecimales(e.latlng.lng,6)+"</h6><input type='button' value='Guardar Ubicación' class='btn btn-primary btn-xs btnCrearAtractor'/><input type='button' value='Limpiar' class='btn btn-danger btn-xs btnLimpiarAtractor'/>");
                          marker2.on("popupopen", onPopupOpen);               
                          return marker2;
                      }
                  }).addTo(map);
              }

              



              function onPopupOpen() {
                  var tempMarker = this;
                  //var tempMarkerGeoJSON = this.toGeoJSON();
                  //var lID = tempMarker._leaflet_id; // Getting Leaflet ID of this marker
                  $(".btnBorrarAtractor:visible").click(function () {
                      $('#modalBorrarAtractor').modal('show');
                      $('#borrarId').val(tempMarker['feature'].properties.id);
                      $('#borrarNombre').val(tempMarker['feature'].properties.nombre);   
                      $('#borrarDireccion').val(tempMarker['feature'].properties.direccion);                                           
                      $('#borrarLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#borrarLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      $('#borrarReviews').val(tempMarker['feature'].properties.reviews); 
                      $('#borrarFamilia').val(tempMarker['feature'].properties.familia);                                             
                      console.log("TempMarker", tempMarker);
                  });
                  $(".btnEditarAtractor:visible").click(function () {
                      $('#modalEditarAtractor').modal('show');
                      $('#editarId').val(tempMarker['feature'].properties.id);
                      $('#editarNombre').val(tempMarker['feature'].properties.nombre);   
                      $('#editarDireccion').val(tempMarker['feature'].properties.direccion);                                           
                      $('#editarLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#editarLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      $('#editarReviews').val(tempMarker['feature'].properties.reviews); 
                      $('#editarFamilia').val(tempMarker['feature'].properties.familia);        
                      console.log("TempMarker", tempMarker);
                      console.log("TempMarker iD", tempMarker['feature'].properties.id);
                  });
                  $(".btnCrearAtractor:visible").click(function () {
                      $('#modalCrearAtractor').modal('show');
                      $('#crearLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#crearLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      console.log("TempMarker", tempMarker);
                  });
                  // To remove marker on click of delete
                  $(".btnLimpiarAtractor:visible").click(function () {
                      map.removeLayer(tempMarker);
                  });
              }
            </script>
      </div> <!--- col-md-6 -->
    </div> <!--- row -->
  </section>
</div>
  <!-- /.content-wrapper -->


<!--=====================================
MODAL CREAR ATRACTOR
======================================-->

<div id="modalCrearAtractor" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Crear Atractor</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">         
            <div class="form-group">
              <label for="crearNombre" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="float" class="form-control" name="crearNombre" id="crearNombre" value="" placeholder="Nombre Comercial" required>
              </div>
            </div>  
          </div>   
          <div class="box-body">         
            <div class="form-group">
              <label for="crearDireccion" class="col-sm-2 control-label">Dirección</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control" name="crearDireccion" id="crearDireccion" value="" placeholder="Dirección Completa" required>
              </div>
            </div>  
          </div>   
          <div class="box-body">          
            <div class="form-group">
              <label for="crearLatitud" class="col-sm-2 control-label">Latitud</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" name="crearLatitud" id="crearLatitud" value="" required readonly>
              </div>
              <label for="crearLongitud" class="col-sm-2 control-label">Longitud</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" id="crearLongitud" name="crearLongitud" value="" required readonly>
              </div>
            </div>
          </div>
           <div class="box-body">           
            <div class="form-group">
              <label for="crearReviews" class="col-sm-5 control-label">Número de Menciones Internet</label>
              <div class="col-sm-7">
                <input type="number" class="form-control" name="crearReviews" placeholder="Menciones en Google Maps" required>
              </div>
            </div>                
          </div> 
          <div class="box-body">    
            <div class="form-group">
              <label for="crearFamilia" class="col-sm-2 control-label">Tipo Atractor</label>
              <div class="col-sm-10">
                <select class="form-control input" name="crearFamilia" required>
                  <option value="Comercio">Comercio</option>
                  <option value="Fast Food">Fast Food</option>
                  <option value="Global">Genérico</option>
                  <option value="Gran Superficie">Gran Superficie</option>
                  <option value="Hoteles">Hoteles</option>
                  <option value="Ocio">Ocio</option>
                  <option value="Restauracion y Bares">Restauración y Bares</option>
                  <option value="Salud">Salud</option>
                  <option value="Turismo">Turismo</option>           
                </select>  
              </div>
            </div>  
          </div>     
          <div class="box-footer">
              <input type="hidden" name="CrearAtractor" value="Si"></input>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Guardar</button>
          </div><!-- /.box-footer -->
          <?php
            $crearAtractor = new ControladorAtractoresGestion();
            $crearAtractor -> ctrCrearAtractor();
          ?>                       
        </div>
      </form>
    </div> 
  </div>
</div>

<!--=====================================
MODAL EDITAR ATRACTOR
======================================-->

<div id="modalEditarAtractor" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modificar Datos del Atractor</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">         
            <div class="form-group">
              <label for="editarNombre" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="float" class="form-control" name="editarNombre" id="editarNombre" value="" placeholder="Nombre Comercial" required>
              </div>
            </div>  
          </div>
          <div class="box-body">         
            <div class="form-group">
              <label for="editarDireccion" class="col-sm-2 control-label">Dirección</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control" name="editarDireccion" id="editarDireccion" value="" placeholder="Dirección Completa" required>
              </div>
            </div>  
          </div>                  
          <div class="box-body">          
            <div class="form-group">
              <label for="editarLatitud" class="col-sm-2 control-label">Latitud</label>
              <div class="col-sm-4">
                <input type="float" class="form-control" name="editarLatitud" id="editarLatitud" value="" required readonly>
              </div>
              <label for="editarLongitud" class="col-sm-2 control-label">Longitud</label>
              <div class="col-sm-4">
                <input type="float" class="form-control" id="editarLongitud" name="editarLongitud" value="" required readonly>
              </div>
            </div>   
          </div> 
          <div class="box-body">           
            <div class="form-group">
              <label for="editarReviews" class="col-sm-5 control-label">Número de Menciones Internet</label>
              <div class="col-sm-7">
                <input type="number" class="form-control" name="editarReviews" id="editarReviews" placeholder="Menciones en Google Maps" required>
              </div>
            </div>                
          </div> 
          <div class="box-body">    
            <div class="form-group">
              <label for="editarFamilia" class="col-sm-2 control-label">Tipo Atractor</label>
              <div class="col-sm-10">
                <select class="form-control input" id="editarFamilia" name="editarFamilia" required>
                  <option value="" id="editarFamilia" ></option>
                  <option value="Comercio">Comercio</option>
                  <option value="Fast Food">Fast Food</option>
                  <option value="Gran Superficie">Gran Superficie</option>
                  <option value="Hoteles">Hoteles</option>
                  <option value="Ocio">Ocio</option>
                  <option value="Restauracion y Bares">Restauración y Bares</option>
                  <option value="Salud">Salud</option>
                  <option value="Turismo">Turismo</option>           
                </select>  
              </div>
            </div>  
          </div>  
          <div class="box-footer">
              <input type="hidden" name="ActualizarAtractor" value="Si"></input>
              <input type="hidden" name="editarId" id="editarId"></input>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Modificar</button>
          </div><!-- /.box-footer -->
          <?php
            $EditarAtractor = new ControladorAtractoresGestion();
            $EditarAtractor -> ctrActualizarAtractor();      
          ?>       
        </div>
      </form>
    </div> 
  </div>
</div>
 
<!--=====================================
MODAL BORRAR ATRACTOR
======================================-->
<div id="modalBorrarAtractor" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Eliminar Atractor</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">         
            <div class="form-group">
              <label for="borrarNombre" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="float" class="form-control" name="borrarNombre" id="borrarNombre" value="" required readonly>
              </div>
            </div>  
          </div>
          <div class="box-body">         
            <div class="form-group">
              <label for="borrarDireccion" class="col-sm-2 control-label">Dirección</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control" name="borrarDireccion" id="borrarDireccion" value="" placeholder="Dirección Completa" required readonly>
              </div>
            </div>  
          </div>  
          <div class="box-body">                     
            <div class="form-group">
              <label for="borrarLatitud" class="col-sm-2 control-label">Latitud</label>
              <div class="col-sm-4">
                <input type="float" class="form-control" name="borrarLatitud" id="borrarLatitud" value="" required readonly>
              </div>
              <label for="borrarLongitud" class="col-sm-2 control-label">Longitud</label>
              <div class="col-sm-4">
                <input type="float" class="form-control" id="borrarLongitud" name="borrarLongitud" value="" required readonly>
              </div>
            </div> 
          </div>
          <div class="box-body">          
            <div class="form-group">
              <label for="borrarReviews" class="col-sm-5 control-label">Número de Menciones Internet</label>
              <div class="col-sm-7">
                <input type="number" class="form-control" id="borrarReviews" name="borrarReviews" required readonly>
              </div>
            </div>                
          </div> 
          <div class="box-body">    
            <div class="form-group">
              <label for="borrarFamilia" class="col-sm-2 control-label">Tipo Atractor</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control input" id="borrarFamilia" name="borrarFamilia" required readonly>              
              </div>
            </div>  
          </div>  
          <div class="box-footer">
              <input type="hidden" name="BorrarAtractor" value="Si"></input>
              <input type="hidden" name="borrarId" id="borrarId"></input>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Modificar</button>
          </div><!-- /.box-footer -->
          <?php
            $BorrarAtractor = new ControladorAtractoresGestion();
            $BorrarAtractor -> ctrBorrarAtractor();      
          ?>       
        </div>
      </form>
    </div> 
  </div>s
</div>

