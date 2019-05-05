<?php
require("funcoes.php");
require("conexao.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); 
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

$sql="SELECT DISTINCT(CIDADE) FROM movimento_veiculos WHERE DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'"; 
  
$sql = $db->query($sql);            
$registros = $sql->fetchAll();       
            

    

    
}  

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>S.G.A.C-POTÊNCIA MEDIÇÕES</title>
  <meta name="description" conte nt="">
  <meta name="author" content="">

  <!-- Favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.ico">

  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <!-- Font Awesome CSS -->
  <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

  <!-- Custom CSS -->
  <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

  <!-- BEGIN CSS for this page -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />
  <!-- END CSS for this page -->

</head>

<body class="adminbody">
  <div id="main">

    <!-- Start  Menu Principal Superior -->
    <?php require_once ("front-end/main-menu-top.php"); ?>
    <!-- End Menu Principal Superior -->

    <!-- Start Barra Menu Lateral Esquerdo -->
    <?php require_once ("front-end/sidebar-menu-left.php"); ?>
    <!-- End Barra Menu Lateral Esquerdo -->

    <div class="content-page">

      <!-- Start content -->
      <div class="content">

        <div class="container-fluid">

          <div class="row">
            <div class="col-xl-12">
              <div class="breadcrumb-holder">
                <h1 class="main-title float-left"> <i class="fa fa-fw fa-car"></i> &nbsp;VEICULOS </h1>
                <ol class="breadcrumb float-right">
                  <li class="breadcrumb-item">Visão Geral</li>
                  <li class="breadcrumb-item active">Veiculos</li>
                </ol>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="container">
          <div class="card-footer small text-muted">

            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <button type="submit" class="btn btn-primary btn-sm  btn-block"><i class="fa fa fa-plus-circle"></i> INSERIR VEICULO</button>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">

              </div>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                <button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa fa-print"></i> IMPRIMIR</button>
                <button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-file-pdf-o"></i> PDF</button>
                <button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-file-excel-o"></i> EXCEL</button>
                <button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-cloud-upload"></i> IMP</button>


              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-12"><br>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-table"></i> CADASTRE AQUI TODOS OS VEÍCULOS DA SUA FROTA.
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover display">
                      <thead>
                        <tr>
                          <th>Placa</th>
                          <th>Numero Cartão</th>
                          <th>Modelo</th>
                          <th>Tipo de Veiculo</th>
                          <th>Marca</th>
                          <th>Filial</th>
                          <th>Status</th>
                          <th>Ação</th>

                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>BBG-2774</td>
                          <td>60645800070566140</td>
                          <td>ATEGO 1719/36</td>
                          <td>CAMINHÃO</td>
                          <td>MERCEDES-BENZ</td>
                          <td>PARACATU</td>
                          <td>ATIVO</td>
                          <td><button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-edit"></i> </button>
                              <button type="submit" class="btn btn-danger btn-sm  "><i class="fa fa fa-trash-o"></i> </button>
                          </td>
                        </tr>
                        <tr>
                          <td>BBG-2774</td>
                          <td>60645800070566140</td>
                          <td>ATEGO 1719/36</td>
                          <td>CAMINHÃO</td>
                          <td>MERCEDES-BENZ</td>
                          <td>PARACATU</td>
                          <td>ATIVO</td>
                          <td><button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-edit"></i> </button>
                              <button type="submit" class="btn btn-danger btn-sm  "><i class="fa fa fa-trash-o"></i> </button>
                          </td>
                        </tr>
                        <tr>
                          <td>BBG-2774</td>
                          <td>60645800070566140</td>
                          <td>ATEGO 1719/36</td>
                          <td>CAMINHÃO</td>
                          <td>MERCEDES-BENZ</td>
                          <td>PARACATU</td>
                          <td>ATIVO</td>
                          <td><button type="submit" class="btn btn-primary btn-sm  "><i class="fa fa fa-edit"></i> </button>
                              <button type="submit" class="btn btn-danger btn-sm  "><i class="fa fa fa-trash-o"></i> </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>





              <!-- END main -->

              <script src="assets/js/modernizr.min.js"></script>
              <script src="assets/js/jquery.min.js"></script>
              <script src="assets/js/moment.min.js"></script>

              <script src="assets/js/popper.min.js"></script>
              <script src="assets/js/bootstrap.min.js"></script>

              <script src="assets/js/detect.js"></script>
              <script src="assets/js/fastclick.js"></script>
              <script src="assets/js/jquery.blockUI.js"></script>
              <script src="assets/js/jquery.nicescroll.js"></script>

              <!-- App js -->
              <script src="assets/js/pikeadmin.js">
              </script>

              <!-- BEGIN Java Script for this page -->
              <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
              <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
              <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

              <!-- Counter-Up-->
              <script src="assets/plugins/waypoints/lib/jquery.waypoints.min.js"></script>
              <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

              <script>
                $(document).ready(function() {
                  // data-tables
                  $('#example1').DataTable();

                  // counter-up
                  $('.counter').counterUp({
                    delay: 10,
                    time: 600
                  });
                });
                }

              </script>

              <script>
                var ctx1 = document.getElementById("grafico1").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayValorDieselAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X PREÇO MÉDIO COMBUSTÍVEL',
                      data: [<?php echo  montaGraficoValor($arrayValorDieselAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(251,195,0,0.9)',
                      borderColor: 'rgba(251,195,0,0.9)',
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

                var ctx1 = document.getElementById("grafico2").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayQuantidadeDieselAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X QUANTIDADE MÉDIA EM LITROS',
                      data: [<?php echo  montaGraficoQuantidade($arrayQuantidadeDieselAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(251,195,0,0.9)',
                      borderColor: 'rgba(251,195,0,0.9)'
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

              </script>

              <script>
                var ctx1 = document.getElementById("grafico3").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayValorGasolinaAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X PREÇO MÉDIO COMBUSTÍVEL',
                      data: [<?php echo  montaGraficoValor($arrayValorGasolinaAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(96,167,0,0.9)',
                      borderColor: 'rgba(96,167,0,0.9)',
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

                var ctx1 = document.getElementById("grafico4").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayQuantidadeGasolinaAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X QUANTIDADE MÉDIA EM LITROS',
                      data: [<?php echo  montaGraficoQuantidade($arrayQuantidadeGasolinaAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(96,167,0,0.9)',
                      borderColor: 'rgba(96,167,0,0.9)'
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

              </script>

              <script>
                var ctx1 = document.getElementById("grafico5").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayValorEtanolAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X PREÇO MÉDIO COMBUSTÍVEL',
                      data: [<?php echo  montaGraficoValor($arrayValorEtanolAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(0,123,255)',
                      borderColor: 'rgba(78,149,212,0.9)',
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

                var ctx1 = document.getElementById("grafico6").getContext('2d');
                var barChart = new Chart(ctx1, {
                  type: 'horizontalBar',
                  borderWidth: 10,
                  data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayQuantidadeEtanolAgrupado);?>"],
                    datasets: [{
                      label: 'CIDADE X QUANTIDADE MÉDIA EM LITROS',
                      data: [<?php echo  montaGraficoQuantidade($arrayQuantidadeEtanolAgrupado); ?>],
                      responsive: true,
                      fill: false,
                      backgroundColor: 'rgba(0,123,255)',
                      borderColor: 'rgba(78,149,212,0.9)'
                    }, ],
                  },
                  options: {
                    title: {
                      display: false,

                    },
                    scales: {
                      xAxes: [{
                        display: true,
                      }],
                      yAxes: [{
                        display: true,
                      }]
                    },
                    animation: {
                      duration: 1000,
                      onComplete: function() {
                        var chartInstance = this.chart,
                          ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = 'rgba(25,0,0,0.9)';
                        ctx.font = "bold 11px Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i);
                          meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x + 20, bar._model.y);
                          });
                        });
                      },
                    },
                  }
                });

              </script>

              <!-- END Java Script Pagina -->

</body>

</html>
