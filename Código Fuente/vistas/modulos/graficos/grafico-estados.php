 <?php
 $item = null;
 $valor = null;
 $Estado = [];
 $candidatos = ControladorInicio::ctrRecuperarEstadoCandidatos($item, $valor);

 foreach ($candidatos as $key => $value){       
  switch($value["ID_ESTADO"]){
    case 1:
    array_push($Estado, "Nuevo Local");
    break;
    case 2:
    array_push($Estado, "Competencia");
    break;
    case 3:
    array_push($Estado, "Atracción");
    break;                    
    case 4:
    array_push($Estado, "Captación");
    break;
    case 5:
    array_push($Estado, "Evaluación");
    break;        
  }
}
?>

          <!-- BAR CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estado Actual de los Candidatos</h3>          
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
                <canvas id="barChart" style="height:180px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->



<script>
  $(function () {
//-------------
//- BAR CHART -
//-------------
var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
var barChart                         = new Chart(barChartCanvas)
var barChartData = {
  labels  : [<?php             
    foreach ($Estado as $key => $value){       
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
    data                :[<?php             
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


var barChartOptions                  = {
  scaleBeginAtZero        : true,
  scaleShowGridLines      : true,
  scaleGridLineColor      : 'rgba(0,0,0,.05)',
  scaleGridLineWidth      : 1,
  scaleShowHorizontalLines: true,
  scaleShowVerticalLines  : true,
  barShowStroke           : true,
  barStrokeWidth          : 2,
  barValueSpacing         : 5,
  barDatasetSpacing       : 1,
  responsive              : true,
  maintainAspectRatio     : true,
  legend: {
    display: false,
    position: 'top'
  }
}

barChart.Bar(barChartData, barChartOptions)

})           
</script>