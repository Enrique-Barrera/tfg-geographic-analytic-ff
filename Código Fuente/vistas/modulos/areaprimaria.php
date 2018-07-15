<div class="content-wrapper">
  <section class="content-header">   
    <h1>
      4.- Análisis Areas de Atracción Comercial
    </h1>
    <ol class="breadcrumb">     
      <li><a href="#"><i class="fa fa-home"></i> Inicio</a></li>      
      <li class="active">Area Primaria de Atracción Comercial</li>    
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row" >
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Áreas de Captación Secundaria y Terciaria</h3>
          </div>
          <div class="box-body" id="map" style='height:520px' data-mode="">
              <?php
                include_once 'geoPHP/geoPHP.inc';
                function wkb_to_json($wkb) {
                  $geom = geoPHP::load($wkb,'wkb');
                  return $geom->out('json');
                }             
                $item = 'id_candidato';
                $valor1 = $_GET['idCandidato'];
                $candidato = ControladorCompetencia::ctrRecuperarCandidato($item, $valor1);
                $valor2 = $candidato['latitud'];
                $valor3 = $candidato['longitud'];

                $geojson = array(
                'type'      => 'FeatureCollection',
                'features'  => array()
                );
                

                // VERSION UTILIZANDO EL CONTROLADOR Y LA CONEXION GENERICA ( NO FUNCIONA problemas utf-8¿?)
                /*
                $competenciaMapa = ControladorAreaPrimaria::ctrMostrarSeccionesMapa($item, $valor1);

                foreach ($competenciaMapa as $key => $value){
                  $properties = $value;
                  unset($properties['wkb']);
                  unset($properties['GEOMETRY']);
                  $feature = array(
                  'type' => 'Feature',
                  'geometry' => json_decode(wkb_to_json($value['wkb'])),
                  'properties' => $properties
                );
                  array_push($geojson['features'], $feature);              
                }
                */

                // VERSION UTILIZANDO CONEXION CON MYSQLI (HAY QUE ELIMINARLA)
                $con = mysqli_connect("localhost","root","", "pfg");
                if (mysqli_connect_errno())  {
                  echo "Failed to connect to MySQL:".mysqli_connect_error();   
                }
                mysqli_set_charset($con,"utf8");
                $sql = 'SELECT PCA.ID_SSCC, PCA.AREA, AsWKB(SSS.GEOMETRY) AS wkb FROM SP_SSCC_SPATIAL SSS, POI_CANDIDATO_AREA PCA WHERE SSS.GEOCODIGO = SUBSTRING(PCA.ID_SSCC, 3, 8) AND PCA.ID_CANDIDATO ="'.$valor1.'"';
                $result = mysqli_query($con, $sql) or print ("Can't select entries from table php_blog.<br>".$sql."<br>".mysqli_error()); 
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                  $properties = $row;
                    # Remove wkb and geometry fields from properties
                  unset($properties['wkb']);
                  unset($properties['GEOMETRY']);
                  $feature = array(
                  'type' => 'Feature',
                  'geometry' => json_decode(wkb_to_json($row['wkb'])),
                  'properties' => $properties
                );
                  array_push($geojson['features'], $feature);              
                }
              
              ?>
              
              <script>
                var area = <?php echo json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
                function getColor(d) {
                  return d =="A2" ? 'yellow' : 
                  d == "A3" ? 'red' :  
                  '#000000'; 
                }
                var cStyle = { 
                  color: '#3A92C8',
                  fillColor: '#000000',
                  fillOpacity: 0.5
                };
              
                function style(feature) { 
                  return { 
                    fillColor: getColor(feature.properties.AREA),
                    weight: 0.5, 
                    opacity: 1, 
                    color: 'black', 
                    dashArray: '1', 
                    fillOpacity: 0.4
                  }; 
                }

                var $lat = <?php echo $valor2; ?>;
                var $long = <?php echo $valor3; ?>;   
                var map = L.map('map').
                setView([$lat, $long], 13);
                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                  maxZoom: 18
                }).addTo(map);
                L.geoJson(area, {style: style}).addTo(map); 
                new L.marker([$lat, $long]).addTo(map);
                L.circle([$lat, $long], 300, cStyle).addTo(map);
                L.control.scale().addTo(map);  
              </script>
          </div> <!-- /.box-body -->
        </div><!-- /.box-primary-->
      </div> <!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Distribución de Público Objetivo</h3>
            </div><!-- /.box-header -->
          </div><!-- /.box-primary -->

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
                  $a2poblacion = round(100* $indices["A2_POBLACION"]/ $indicesMaximos["A2_POBLACIONMAX"],2);
                  $a2empleados = round(100* $indices["A2_TRABAJADORES"]/ $indicesMaximos["A2_TRABAJADORESMAX"],2);
                  $a2viviendas = round(100* $indices["A2_VIVIENDASSECUNDARIAS"]/ $indicesMaximos["A2_VIVIENDASMAX"],2);
                  $a2hogares = round(100* $indices["A2_HOGARESTOTAL"]/ $indicesMaximos["A2_HOGARESTOTALMAX"],2);
                  $a3poblacion = round(100* $indices["A3_POBLACION"]/ $indicesMaximos["A3_POBLACIONMAX"],2);
                  $a3viviendas = round(100* $indices["A3_VIVIENDASTOTAL"]/ $indicesMaximos["A3_VIVIENDASTOTALMAX"],2);
                  $a3hogares = round(100* $indices["A3_HOGARES"]/ $indicesMaximos["A3_HOGARESTOTALMAX"],2);
                                                                                                         
                    echo '<tr>
                            <td>1. </td>
                            <td>Población A2</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$a2poblacion.'%"s></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a2poblacion.'%</span></td>
                          </tr>
                          <tr>
                            <td>2.</td>
                            <td>Empleados A2</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$a2empleados.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a2empleados.'%</span></td>
                          </tr>
                          <tr>
                            <td>3.</td>
                            <td>Viviendas A2</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width:'.$a2viviendas.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a2viviendas.'%</span></td>
                          </tr>
                          <tr>
                            <td>4.</td>
                            <td>Hogares Totales A2</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width:'.$a2hogares.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a2hogares.'%</span></td>
                          </tr>
                          <tr>
                            <td>5.</td>
                            <td>Población A3</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$a3poblacion.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a3poblacion.'%</span></td>
                          </tr>
                          <tr>
                            <td>6.</td>
                            <td>Viviendas Totales A3</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width: '.$a3viviendas.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a3viviendas.'%</span></td>
                          </tr>
                          <tr>
                            <td>7.</td>
                            <td>Hogares Totales A3</td>
                            <td>
                              <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-primary" style="width: '.$a3hogares.'%"></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$a3hogares.'%</span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Datos del Municipio</h3>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th style="width: 10px">#</th>
                <th>Concepto</th>
                <th>Valor</th>
                <th style="width: 40px">Label</th>
              </tr>
              </tr>
                  <?php
                  $item = 'id_candidato';
                  $valor = $_GET['idCandidato'];
                  $indices = ControladorAtractores::ctrMostrarIndices($item, $valor);  
                  $indicesMaximos = ControladorAtractores::ctrMostrarIndicesMaximos($item, $valor);

                  $indiceRenta = round(100* $indices["A3_RENTAMEDIA"]/ $indicesMaximos["A3_RENTAMEDIAMAX"],2);                                                                 
                  $indiceParo = round($indices["A3_TASAPARO"],2);                                           
                    echo '<tr>
                            <td>8.</td>
                            <td>Renta Bruta Media</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceRenta.'%"s></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceRenta.'%</span></td>
                          </tr>
                          <tr>
                            <td>9.</td>
                            <td>Tasa de Paro</td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width:'.$indiceParo.'%"s></div>
                              </div>
                            </td>
                            <td align="right" ><span class="badge bg-blue">'.$indiceParo.'%</span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box-footer">
          <form class="form-inline" onsubmit="openModal()" method="post" id="formResultado">        
            <input type="hidden" name="Estado5" value="Estado5"></input>
            <button style='width:19.6%;' type="button" class="btn btn-success  btnIrLocal" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">1.- Ubicación</button>        
            <button style='width:19.6%;' type="button" class="btn btn-success  btnIrCompetenciaBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">2.- Competencia</button>   
            <button style='width:19.6%;' type="button" class="btn btn-success  btnIrAtractoresBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">3.- Atracción</button>   
            <button style='width:19.6%;' type="button" class="btn btn-success" disabled>4.- Captación</button>  
            <button style='width:19.6%;' type="submit" class="btn <?php if ($_GET['idEstado'] == '1' or $_GET['idEstado'] == '2' or $_GET['idEstado'] == '3' or $_GET['idEstado'] == '4') {
                                                        echo ' btn-danger ';
                                                      }
                                                      else {
                                                        echo ' btn-success ';
                                                      }
                                              ?>  
            btnIrResultado" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">5.- Valoración Ubicación</button>                                                      
         </form>
         <?php
            $ejecutarWizard4 = new ControladorWizard();
            $ejecutarWizard4 -> ctrEjecutarWizard4();
         ?>          
        </div>
      </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
  </section>
</div><!-- /.content-wrapper -->

<div style="top:25%; outline: none; overflow:hidden;" class="modal fade" id="Wizard4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ejecutando Modelos Predictivos</h4>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>Por Favor Espere....</h4>
      </div>
      <div class="modal-body center-block">
        <p>Grado de Avance:</p>
        <div class="progress">
          <div class="progress-bar bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">   
          </div>
        </div>
        <div id="mensajehtml"><b>Iniciando Proceso de Ejecución de Modelos</b></div>  
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->