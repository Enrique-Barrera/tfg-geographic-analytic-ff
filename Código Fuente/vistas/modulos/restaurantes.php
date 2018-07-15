<div class="content-wrapper">
  <section class="content-header">   
    <h1>Gestión de Restaurantes</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Gestión de Restaurantes Fast Food</li>
    </ol>
  </section>
 
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Distribución de Restaurantes Fast Food</h3>
            </div>
            <div class="box-body" id="map" style='height:520px' data-mode="">
            <input type="hidden" data-map-markers="" value="" name="map-geojson-data" />
            <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.9/dist/esri-leaflet-geocoder.css">
            <?php             
              $geojson = array(
                'type' => 'FeatureCollection',
                'features'  => array()
              );  
              $competenciaMapa = ControladorRestaurantes::ctrMostrarCompetenciaMapaTodo();    
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
                    'tipo' => $value['id_candidato_tipo'],
                    'reviews' => $value['reviews'],                    
                    'direccion' => $value['direccion1'],  
                    'id_cadena' => $value['id_cadena']                 
                  )
                );
                array_push($geojson['features'], $feature);
              }
            ?> 
            <script>          
              
              var competencia = <?php echo json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
              console.log("Competencia", competencia);
              function redondeoDecimales(numero,decimales)
              {
                var original=parseFloat(numero);
                return numero.toFixed(decimales);
              }
              var icons = {
                  'Bocatta':  L.icon({  
                    iconUrl: 'vistas/dist/img/boc-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Burger King': L.icon({ 
                    iconUrl: 'vistas/dist/img/bur-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Dominos':  L.icon({ 
                    iconUrl: 'vistas/dist/img/dom-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Fast Food':  L.icon({ 
                    iconUrl: 'vistas/dist/img/fas-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Foster':  L.icon({ 
                    iconUrl: 'vistas/dist/img/fos-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Lizarran': L.icon({ 
                    iconUrl: 'vistas/dist/img/liz-icono.png', markerColor: 'red', iconSize: [30,44]}),                                                           
                  'Mc Donalds': L.icon({ 
                    iconUrl: 'vistas/dist/img/mcd-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  '100 Montaditos':  L.icon({ 
                    iconUrl: 'vistas/dist/img/mon-icono.png', markerColor: 'red', iconSize: [30,44]}),                   
                  'Pans And Company':  L.icon({ 
                    iconUrl: 'vistas/dist/img/pan-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Rodilla': L.icon({ 
                    iconUrl: 'vistas/dist/img/rod-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Subway':  L.icon({ 
                    iconUrl: 'vistas/dist/img/sub-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Cadena Target':  L.icon({ 
                    iconUrl: 'vistas/dist/img/tar-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'Telepizza':  L.icon({ 
                    iconUrl: 'vistas/dist/img/tel-icono.png', markerColor: 'red', iconSize: [30,44]}),
                  'VIPS':  L.icon({ 
                    iconUrl: 'vistas/dist/img/vip-icono.png', markerColor: 'red', iconSize: [30,44]}),                                                                                                                                                                                          
              };

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

              new L.geoJSON(competencia, {
                pointToLayer: function (feature, latlng) {
                  marker = L.marker(latlng, {
                      icon: icons[feature.properties.id_cadena]
                  }).bindPopup("<h5><b>Restaurante Existente</b></h5><h5>"+feature.properties.nombre+"</h5><h5>"+feature.properties.cadena+"</h5><h5>"+feature.properties.tipo+"</h5><input type='button' value='Editar' class='btn btn-primary btn-xs btnEditarRestaurante'/><input type='button' value='Eliminar' class='btn btn-danger btn-xs btnBorrarRestaurante'/>");
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
                             
                              title: "Ubicación Restaurante",
                              alt: "Ubicación Restaurante",
                              riseOnHover: true,
                              draggable: false,
                          }).bindPopup("<h5><b>Nuevo Restaurante</b></h5><h6></h6><h6>Latitud: "+redondeoDecimales(e.latlng.lat,6)+"</h6><h6>Longitud: "+redondeoDecimales(e.latlng.lng,6)+"</h6><input type='button' value='Guardar Ubicación' class='btn btn-primary btn-xs btnCrearRestaurante'/><input type='button' value='Limpiar' class='btn btn-danger btn-xs btnLimpiarRestaurante'/>");
                          marker2.on("popupopen", onPopupOpen);               
                          console.log("Marker", marker);
                          return marker2;
                      }
                  }).addTo(map);
              }


              function onPopupOpen() {
                  var tempMarker = this;
                  //var tempMarkerGeoJSON = this.toGeoJSON();
                  //var lID = tempMarker._leaflet_id; // Getting Leaflet ID of this marker
                  $(".btnBorrarRestaurante:visible").click(function () {
                      $('#modalBorrarRestaurante').modal('show');
                      $('#borrarId').val(tempMarker['feature'].properties.id);                     
                      $('#borrarNombre').val(tempMarker['feature'].properties.nombre);
                      $('#borrarLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#borrarLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      $('#borrarDireccion').val(tempMarker['feature'].properties.direccion);                         
                      $('#borrarReviews').val(tempMarker['feature'].properties.reviews);
                      $('#borrarCadena').val(tempMarker['feature'].properties.cadena);
                      $('#borrarTipo').val(tempMarker['feature'].properties.tipo);        
                      $('#borrarCadena').html(tempMarker['feature'].properties.cadena);
                      $('#borrarTipo').html(tempMarker['feature'].properties.tipo);                                                                                                                                  
                      console.log("TempMarker", tempMarker);
                      console.log("TempMarker Id: ", tempMarker['feature'].properties.id);
                  });
                  $(".btnEditarRestaurante:visible").click(function () {
                    console.log(competencia)
                      $('#modalEditarRestaurante').modal('show');
                      $('#editarId').val(tempMarker['feature'].properties.id);                        
                      $('#editarNombre').val(tempMarker['feature'].properties.nombre);   
                      $('#editarDireccion').val(tempMarker['feature'].properties.direccion);                                           
                      $('#editarLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#editarLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      $('#editarReviews').val(tempMarker['feature'].properties.reviews);
                      $('#editarCadena').val(tempMarker['feature'].properties.cadena);
                      $('#editarTipo').val(tempMarker['feature'].properties.tipo);        
                      $('#editarCadena').html(tempMarker['feature'].properties.cadena);
                      $('#editarTipo').html(tempMarker['feature'].properties.tipo);                        
                      console.log("TempMarker", tempMarker);                 
                      console.log("TempMarker Reviews", tempMarker['feature'].properties.reviews);
                  });
                  $(".btnCrearRestaurante:visible").click(function () {
                      $('#modalCrearRestaurante').modal('show');
                      $('#crearLatitud').val(redondeoDecimales(tempMarker._latlng.lat,7));
                      $('#crearLongitud').val(redondeoDecimales(tempMarker._latlng.lng,7));
                      console.log("TempMarker: ", tempMarker.properties);
                  });
                  // To remove marker on click of delete
                  $(".btnLimpiarRestaurante:visible").click(function () {
                      map.removeLayer(tempMarker);
                  });
              }
   
            </script>
      </div> <!-- /.col-md-6 -->
    </div> 
  </section>
</div>
  <!-- /.content-wrapper -->

<!--=====================================
MODAL CREAR RESTAURANTE
======================================-->
<div id="modalCrearRestaurante" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Alta de Nuevo Fast Food</h4>
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
              <label for="crearCadena" class="col-sm-2 control-label">Cadena</label>
              <div class="col-sm-10">
                <select class="form-control input" name="crearCadena" required>
                  <option value="Cadena Target">Cadena Target</option>
                  <option value="Bocatta">Bocatta</option>
                  <option value="Burger King">Burger King</option>
                  <option value="Dominos">Dominos</option>
                  <option value="Fast Food">Fast Food</option>
                  <option value="Foster">Foster</option>
                  <option value="Lizarran">Lizarran</option>
                  <option value="Mc Donalds">Mc Donalds</option>
                  <option value="100 Montaditos">100 Montaditos</option>
                  <option value="Pans And Company">Pans And Company</option>
                  <option value="Rodilla">Rodilla</option>
                  <option value="Subway">Subway</option>
                  <option value="Telepizza">Telepizza</option>
                  <option value="VIPS">VIPS</option>            
                </select>  
              </div>
            </div>  
          </div> 
          <div class="box-body">           
            <div class="form-group">
              <label for="crearTipo" class="col-sm-2 control-label">Tipo Local</label>
              <div class="col-sm-10">
                <select class="form-control input" name="crearTipo" required>
                  <option value="Restaurante Freestander">Restaurante Freestander</option>
                  <option value="Restaurante Instore">Restaurante Instore</option>
                </select>  
              </div>
            </div>                
          </div>    
          <div class="box-footer">
              <input type="hidden" name="CrearRestaurante" value="Si"></input>            
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Guardar</button>
          </div><!-- /.box-footer -->
          <?php
            $crearRestaurante= new ControladorRestaurantes();
            $crearRestaurante -> ctrCrearRestaurante();
          ?>                       
        </div>
      </form>
    </div> 
  </div>
</div>

<!--=====================================
MODAL EDITAR RESTAURANTE
======================================-->

<div id="modalEditarRestaurante" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modificar Datos del Restaurante</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">         
            <div class="form-group">
              <label for="editarNombre" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control" name="editarNombre" id="editarNombre" value=""  required>
              </div>
            </div>  
          </div>   
          <div class="box-body">         
            <div class="form-group">
              <label for="editarDireccion" class="col-sm-2 control-label">Dirección</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control" name="editarDireccion" id="editarDireccion" value=""  required>
              </div>
            </div>  
          </div>   
          <div class="box-body">          
            <div class="form-group">
              <label for="editarLatitud" class="col-sm-2 control-label">Latitud</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" name="editarLatitud" id="editarLatitud" value="" required readonly>
              </div>
              <label for="editarLongitud" class="col-sm-2 control-label">Longitud</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" id="editarLongitud" name="editarLongitud" value="" required readonly>
              </div>
            </div>
          </div>
           <div class="box-body">           
            <div class="form-group">
              <label for="editarReviews" class="col-sm-5 control-label">Número de Menciones Internet</label>
              <div class="col-sm-7">
                <input type="number" class="form-control" name="editarReviews" id="editarReviews" required>
              </div>
            </div>                
          </div> 
          <div class="box-body">    
            <div class="form-group">
              <label for="editarCadena" class="col-sm-2 control-label">Cadena</label>
              <div class="col-sm-10">
                <select class="form-control input" name="editarCadena" required>
                  <option value="" id="editarCadena"></option>
                  <option value="Cadena Target">Cadena Target</option>
                  <option value="Bocatta">Bocatta</option>
                  <option value="Burger King">Burger King</option>
                  <option value="Dominos">Dominos</option>
                  <option value="Fast Food">Fast Food</option>
                  <option value="Foster">Foster</option>
                  <option value="Lizarran">Lizarran</option>
                  <option value="Mc Donalds">Mc Donalds</option>
                  <option value="100 Montaditos">100 Montaditos</option>
                  <option value="Pans And Company">Pans And Company</option>
                  <option value="Rodilla">Rodilla</option>
                  <option value="Subway">Subway</option>
                  <option value="Telepizza">Telepizza</option>
                  <option value="VIPS">VIPS</option>            
                </select>  
              </div>
            </div>  
          </div> 
          <div class="box-body">           
            <div class="form-group">
              <label for="editarTipo" class="col-sm-2 control-label">Tipo Local</label>
              <div class="col-sm-10">
                <select class="form-control input" name="editarTipo" required>
                  <option value="" id="editarTipo"></option>
                  <option value="Restaurante Freestander">Restaurante Freestander</option>
                  <option value="Restaurante Instore">Restaurante Instore</option>
                </select>  
              </div>
            </div>                
          </div>    
          <div class="box-footer">
              <input type="hidden" name="ActualizarRestaurante" value="Si"></input>
              <input type="hidden" name="editarId" id="editarId"></input>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Modificar</button>
          </div><!-- /.box-footer -->
          <?php
            $EditarRestaurante = new ControladorRestaurantes();
            $EditarRestaurante -> ctrActualizarRestaurante();
          ?>                       
        </div>
      </form>
    </div> 
  </div>
</div>

<!--=====================================
MODAL BORRAR RESTAURANTE
======================================-->
<div id="modalBorrarRestaurante" class="modal fade" role="dialog"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Eliminar Restaurante Fast Food</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">         
            <div class="form-group">
              <label for="borrarNombre" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="float" class="form-control" name="borrarNombre" id="borrarNombre" value="" placeholder="Nombre Comercial" required readonly>
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
                <input type="number" class="form-control" name="borrarLatitud" id="borrarLatitud" value="" required readonly>
              </div>
              <label for="borrarLongitud" class="col-sm-2 control-label">Longitud</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" id="borrarLongitud" name="borrarLongitud" value="" required readonly>
              </div>
            </div>
          </div>
           <div class="box-body">           
            <div class="form-group">
              <label for="borrarReviews" class="col-sm-5 control-label">Número de Menciones Internet</label>
              <div class="col-sm-7">
                <input type="number" class="form-control" id="borrarReviews" name="borrarReviews"  required readonly>
              </div>
            </div>                
          </div> 
          <div class="box-body">    
            <div class="form-group">
              <label for="borrarCadena" class="col-sm-2 control-label">Cadena</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control input" id="borrarCadena" name="borrarCadena" required readonly>
              </div>
            </div>  
          </div> 
          <div class="box-body">           
            <div class="form-group">
              <label for="borrarTipo" class="col-sm-2 control-label">Tipo Local</label>
              <div class="col-sm-10">
                <input type="txt" class="form-control input" id="borrarTipo" name="borrarTipo" required readonly>
              </div>
            </div>                
          </div>    
          <div class="box-footer">
              <input type="hidden" name="BorrarRestaurante" value="Si"></input>
              <input type="hidden" id="borrarId" name="borrarId"></input>              
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-info pull-right btnCrearAtractor">Eliminar</button>
          </div><!-- /.box-footer -->
          <?php
            $BorrarRestaurante = new ControladorRestaurantes();
            $BorrarRestaurante -> ctrBorrarRestaurante();
          ?>                       
        </div>
      </form>
    </div> 
  </div>
</div>