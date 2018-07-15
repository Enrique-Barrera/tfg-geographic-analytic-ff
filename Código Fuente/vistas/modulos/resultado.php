<div class="content-wrapper">
  <section class="content-header">   
    <h1>
      5.- Evaluación de la Ubicación
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

       <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Restaurante Analizado</h3>
            </div><!-- /.box-header -->
          </div><!-- /.box-primary -->

          <div class="box-body">
            <table class="table table-condensed">
                <?php
                  $item = 'id_candidato';
                  $valor = $_GET['idCandidato'];
                  $modelo = ControladorResultado::ctrCandidatoModelo($item, $valor);              
                    echo '<tr>
                            <td><b>Nombre del Restaurante</b></td>
                            <td style="color:#ff0000"><b>'.$modelo['NOMBRE'].'</b></td>
                          </tr>
                          <tr>
                            <td><b>Dirección</b></td>
                            <td style="color:#ff0000"><b>'.$modelo['DIRECCION1'].'</b></td>
                          </tr>
                          <tr>
                            <td><b>Tipo de Restaurante</b></td>
                            <td style="color:#ff0000"><b>'.$modelo['ID_CANDIDATO_TIPO'].'</b></td>
                          </tr>'
                ?>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->      

        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Ubicación Analizada y Áreas de Análisis</h3>
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
                

                // VERSION UTILIZANDO EL CONTROLADOR Y LA CONEXION GENERICA ( NO FUNCIONA)
                /*$competenciaMapa = ControladorAreaPrimaria::ctrMostrarSeccionesMapa($item, $valor1);

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
                ?>*/

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
              <h3 class="box-title">Predicción de Ventas: Modelo Matemático</h3>
            </div><!-- /.box-header -->
          </div><!-- /.box-primary -->

          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th>Estimación de Ventas</th>
                <th>Valor</th>
                <th style="width: 40px">Score</th>
              </tr>
                <?php           
                  if($modelo['ESTIMACIONVENTAS'] == NULL){
                    $ventas = 0;
                  }
                  else{
                    $ventas = $modelo['ESTIMACIONVENTAS'];
                  }
                  $ventasp = round(100* $ventas/ 2000000,2);
                    echo '<tr>
                            <td>Ventas Primer Año</td>
                            <td>'.number_format($ventas, 0, ",", "." ).' Euros</td>
                            <td><span class="badge bg-red">'.$ventasp.'%</span></td>
                          </tr>'
                ?>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Predicción de Ventas: Modelo de Similitud</h3>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th style="width: 8%">#</th>
                <th style="width: 42%">Restaurante</th>
                <th style="width: 15%">Ventas</th>
                <th style="width: 20%"></th>
                <th style="width: 10%">Score</th>
              </tr>
                  <?php  
                  $valor = $modelo['SIMILAR1'];
                  $segmento = ControladorResultado::ctrCompetidorModelo($item, $valor);  
                  if($segmento['VENTAS'] == NULL){
                    $ventas1 = 0;
                  }
                  else{
                    $ventas1 = $segmento['VENTAS'];
                  }
                  $ventasp1 = round(100* $ventas1/ 2000000,2);
                  $nombre1 =  $segmento['NOMBRE'];
                  
                  $valor = $modelo['SIMILAR2'];
                  $segmento = ControladorResultado::ctrCompetidorModelo($item, $valor);  
                  if($segmento['VENTAS'] == NULL){
                    $ventas2 = 0;
                  }
                  else{
                    $ventas2 = $segmento['VENTAS'];
                  }
                  $ventasp2 = round(100* $ventas2/ 2000000,2);
                  $nombre2 =  $segmento['NOMBRE'];

                  $valor = $modelo['SIMILAR3'];
                  $segmento = ControladorResultado::ctrCompetidorModelo($item, $valor);  
                  if($segmento['VENTAS'] == NULL){
                    $ventas3 = 0;
                  }
                  else{
                    $ventas3 = $segmento['VENTAS'];
                  }
                  $ventasp3 = round(100* $ventas3/ 2000000,2);
                  $nombre3 =  $segmento['NOMBRE'];   
                  
                  $ventas4 = ($ventas1+$ventas2+$ventas3)/3;
                  $ventasp4 = round(100* $ventas4/ 2000000,2);

                  echo '<tr>
                            <td>'.$modelo["SIMILAR1"].'</td>
                            <td>'.$nombre1.'</td>
                            <td align="right">'.number_format($ventas1, 0, ",", "." ).' Euros</td>                       
                            <td></td>
                            <td align="right"><span class="badge bg-red">'.$ventasp1.'%</span></td>
                          </tr>
                          <tr>
                            <td>'.$modelo["SIMILAR2"].'</td>
                            <td>'.$nombre2.'</td>
                            <td align="right">'.number_format($ventas2, 0, ",", "." ).' Euros</td>                          
                            <td></td>
                            <td align="right"><span class="badge bg-red">'.$ventasp2.'%</span></td>
                          </tr>
                          <tr>
                            <td>'.$modelo["SIMILAR3"].'</td>
                            <td>'.$nombre3.'</td>
                            <td align="right">'.number_format($ventas3, 0, ",", "." ).' Euros</td>                            
                            <td></td>
                            <td align="right"><span class="badge bg-red">'.$ventasp3.'%</span></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td><b>Ventas Promedio Previstas</b></td>
                            <td align="right"><b>'.number_format($ventas4, 0, ",", "." ).' Euros</b></td>                               
                            <td></td>
                            <td align="right"><span class="badge bg-red"><b>'.$ventasp4.'%</b></span></td>
                          </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Valoración de Ubicación</h3>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tr>
                <th style="width: 50%">Concepto</th>
                <th style="width: 10%">Valor</th>
                <th style="width: 32%"></th>
                <th style="width: 8%">Score</th>
              </tr>
                  <?php  
                  $item = 'id_candidato';
                  $valor = $_GET['idCandidato'];
                  $valoracion = ControladorResultado::ctrCandidatoValoracion($item, $valor);  
                  $p1_poblacion = round(100 * $valoracion["P1_POBLACIONREAL"],2);
                  $p1_poblacions = round(50 * $valoracion["P1_POBLACIONREAL"],2);
                  $p2_flotante = round(100 * $valoracion["P2_POBLACIONFLOTANTE"],2);
                  $p2_flotantes = round(25 * $valoracion["P2_POBLACIONFLOTANTE"],2);
                  $p3_comercio = round(100 * $valoracion["P3_COMERCIO"],2);
                  $p3_comercios = round(5 * $valoracion["P3_COMERCIO"],2);
                  $p4_atraccion = round(100 * $valoracion["P4_ATRACCION"],2);
                  $p4_atraccions = round(15 * $valoracion["P4_ATRACCION"],2);
                  $p5_empleados = round(100 * $valoracion["P5_EMPLEADOS"],2);
                  $p5_empleadoss = round(5 * $valoracion["P5_EMPLEADOS"],2);                  
                  $p6_exclusividad = round(5 * $valoracion["P6_EXCLUSIVIDAD"],2);
                  $a2_poblacion = round($valoracion["A2_POBLACION"],2);
                  $a2_viviendas = round($valoracion["A2_VIVIENDASSECUNDARIAS"],2);
                  $a1_turismo = round($valoracion["A1_INDICETURISMO"],2); 
                  $a1_comercio = round($valoracion["A1_INDICECOMERCIO"],2); 
                  $a1_gran = round($valoracion["A1_INDICEGRANSUPERFICIE"],2);                                                  
                  $a1_hoteles = round($valoracion["A1_INDICEHOTELES"],2);
                  $a1_salud = round($valoracion["A1_INDICESALUD"],2);
                  $a1_ocio = round($valoracion["A1_INDICEOCIO"],2);                  
                  $a1_restauracion = round($valoracion["A1_INDICERESTAURACION"],2);                                    
                  $a2_trabajadores = round($valoracion["A2_TRABAJADORES"],2);

                  $valoracion = round($p1_poblacions + $p2_flotantes 
                  + $p3_comercios + $p4_atraccions + $p5_empleadoss,2);

                  echo '<tr>  
                          <td style="color:#ff0000"><b>Población Efectiva</b></td>   
                          <td align="right" style="color:#ff0000"><b>'.number_format($p1_poblacion, 2, ",", "." ).'</b></td>
                          <td style="color:#ff0000"><b>%</b></td>   
                          <td align="right" ><span class="badge bg-red"><b>'.number_format($p1_poblacions, 2, ",", "." ).'%</b></span></td>
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Población</p></td>   
                          <td align="right" >'.number_format($a2_poblacion, 0, ",", "." ).'</td>     
                          <td> Habitantes</td>
                          <td></td>
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Segundas Residencias</p></td>   
                          <td align="right">'.number_format($a2_viviendas, 0, ",", "." ).'</td>     
                          <td>Viviendas Secundarias</td>
                          <td></td>
                        </tr>  
                        <tr>  
                          <td style="color:#ff0000"><b>Población Flotante</b></td>   
                          <td align="right" style="color:#ff0000"><b>'.number_format($p2_flotante, 2, ",", "." ).'</b></td>
                          <td style="color:#ff0000"><b>%</b></td>   
                          <td align="right" ><span class="badge bg-red"><b>'.number_format($p2_flotantes, 2, ",", "." ).'%</b></span></td>
                        </tr>                      
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Turismo</p></td>   
                          <td align="right">'.number_format($a1_turismo, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr>     
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Hoteles</p></td>   
                          <td align="right">'.number_format($a1_hoteles, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr>                                                     
                        <tr>  
                          <td style="color:#ff0000"><b>Atracción Comercial</b></td>   
                          <td align="right" style="color:#ff0000"><b>'.number_format($p3_comercio, 2, ",", "." ).'</b></td>
                          <td style="color:#ff0000"><b>%</b></td>   
                          <td align="right" ><span class="badge bg-red"><b>'.number_format($p3_comercios, 2, ",", "." ).'%</b></span></td>                        
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Comercio</p></td>   
                          <td align="right">'.number_format($a1_comercio, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr> 
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Grandes Superficies</p></td>   
                          <td align="right">'.number_format($a1_gran, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr>                                                     
                        <tr>  
                          <td style="color:#ff0000"><b>Trabajadores en la zona</b></td>   
                          <td align="right" style="color:#ff0000"><b>'.number_format($p5_empleados, 2, ",", "." ).'</b></td>
                          <td style="color:#ff0000"><b>%</b></td>   
                          <td align="right" ><span class="badge bg-red"><b>'.number_format($p5_empleadoss, 2, ",", "." ).'%</b></span></td>                        
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Trabajadores en Zona Estimados</p></td>   
                          <td align="right" >'.number_format($a2_trabajadores, 0, ",", "." ).'</td>     
                          <td>Personas</td>
                          <td></td>
                        </tr>                        
                        <tr>  
                          <td style="color:#ff0000"><b>Atracción Global</b></td>   
                          <td align="right" style="color:#ff0000"><b>'.number_format($p4_atraccion, 2, ",", "." ).'</b></td>
                          <td style="color:#ff0000"><b>%</b></td>   
                          <td align="right" ><span class="badge bg-red"><b>'.number_format($p4_atraccions, 2, ",", "." ).'%</b></span></td>                        
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Ocio</p></td>   
                          <td align="right">'.number_format($a1_ocio, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr> 
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Salud</p></td>   
                          <td align="right">'.number_format($a1_salud, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr>
                        <tr>  
                          <td><p style="margin-left: 40px">Indice de Restauración</p></td>   
                          <td align="right">'.number_format($a1_restauracion, 2, ",", "." ).'</td>     
                          <td>%</td>
                          <td></td>
                        </tr                                                  
                        <tr>  
                          <td style="color:#ff0000"><b>Valoracion</b></td>   
                          <td align="right" style="color:#ff0000"><b></b></td>
                          <td style="color:#ff0000"><b></b></td>   
                          <td align="right" ><span class="badge bg-green"><b>'.number_format($valoracion, 2, ",", "." ).'%</b></span></td>                        
                        </tr>                        
                        <tr>  
                          <td style="color:#ff0000"><b>Índice Exclusividad</b></td>   
                          <td align="right" style="color:#ff0000"><b></b></td>
                          <td style="color:#ff0000"><b></b></td>   
                          <td align="right" ><span class="badge bg-green"><b>'.number_format($p6_exclusividad, 2, ",", "." ).'%</b></span></td>                        
                        </tr>';
                  ?> 
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <div class="box-footer">
            <button style='width:19.6%;' type="button" class="btn btn-success btnIrLocal" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">1.- Ubicación</button>        
            <button style='width:19.6%;' type="button" class="btn btn-success btnIrCompetenciaBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">2.- Competencia</button>   
            <button style='width:19.6%;' type="button" class="btn btn-success btnIrAtractoresBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">3.- Atracción</button>   
            <button style='width:19.6%;' type="button" class="btn btn-success btnIrAreaPrimariaBack" idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">4.- Area Captación</button>
            <button style='width:19.6%;' type="button" class="btn btn-success btnIrResultadoBack" disabled idCandidato="<?php echo $_GET['idCandidato']; ?>" idEstado="<?php echo $_GET['idEstado']; ?>">5.- Valoración Ubicación</button>
        </div>
      </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
  </section>
</div><!-- /.content-wrapper -->
