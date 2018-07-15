 <?php
 $item = null;
 $valor = null;
 $Meses = [];
 $candidatos = ControladorInicio::ctrRecuperarHistoricoCandidatos($item, $valor);

 foreach ($candidatos as $key => $value){       
  switch($value["MES"]){
    case 1:
    array_push($Meses, "Enero");
    break;
    case 2:
    array_push($Meses, "Febrero");
    break;
    case 3:
    array_push($Meses, "Marzo");
    break;                    
    case 4:
    array_push($Meses, "Abril");
    break;
    case 5:
    array_push($Meses, "Mayo");
    break;
    case 6:
    array_push($Meses, "Junio");
    break;
    case 7:
    array_push($Meses, "Julio");
    break;
    case 8:
    array_push($Meses, "Agosto");
    break;                      
    case 9:
    array_push($Meses, "Septiembre");
    break;                      
    case 10:
    array_push($Meses, "Octubre");
    break;                      
    case 11:
    array_push($Meses, "Noviembre");
    break;
    case 12:
    array_push($Meses, "Diciembre");
    break;                      
  }
}
?>

         <!-- AREA CHART -->

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Número de Candidatos Analizado </h3>
              <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div>
                <table align="right" cellspacing="0">
                  <td>          
                    <th><span class="badge bg-blue">Freestander</span></th>
                    <th><span class="badge bg-green">Instore</span></th>
                  </td>
                </table> 
              </div>              
              <div class="chart">
                <canvas id="areaChart" style="height:180px"></canvas>
              </div> <!-- /.chart -->
            </div> <!-- /.box-body -->

          </div> <!--- ./box --->


<script>
$(function () {
  //--------------
  //- AREA CHART -
  //--------------

  // Get context with jQuery - using jQuery's .get() method.
  var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
  // This will get the first returned node in the jQuery collection.
  var areaChart       = new Chart(areaChartCanvas)

  var areaChartData = {
    labels  : [<?php             
    foreach ($Meses as $key => $value){       
      echo '"'; echo $value; echo '", ';
    }
    ?>],

    datasets: [
    {
    label               : 'Freestander',
    fillColor           : 'rgba(60,141,188,0.9)',
    strokeColor         : 'rgba(60,141,188,0.9)',
    pointColor          : 'rgba(60,141,188,0.9)',
    pointStrokeColor    : '#c1c7d1',
    pointHighlightFill  : '#fff',
    pointHighlightStroke: 'rgba(220,220,220,1)',
      data                : [<?php             
      foreach ($candidatos as $key => $value){       
        echo $value["FRE"]; echo ','; 
      }
      ?>]

    },
    {
    label               : 'Instore',
    fillColor           : '#00a65a',
    strokeColor         : '#00a65a',
    pointColor          : '#00a65a',
    pointStrokeColor    : 'rgba(60,141,188,1)',
    pointHighlightFill  : '#fff',
    pointHighlightStroke: 'rgba(60,141,188,1)',
      data                : [<?php             
      foreach ($candidatos as $key => $value){       
        echo $value["INS"]; echo ',';
      }
      ?>]
    }
    ]
  }

  var areaChartOptions = {
    showScale               : true,
    scaleShowGridLines      : false,
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    scaleGridLineWidth      : 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines  : true,
    bezierCurve             : true,
    bezierCurveTension      : 0.3,
    pointDot                : false,
    pointDotRadius          : 4,
    pointDotStrokeWidth     : 1,
    pointHitDetectionRadius : 20,
    datasetStroke           : true,
    datasetStrokeWidth      : 2,
    datasetFill             : true,
    maintainAspectRatio     : true,
    responsive              : true,
    legend: {
      display: true,
      position: 'top'
    }
  }

  areaChart.Line(areaChartData, areaChartOptions)
})  
</script>