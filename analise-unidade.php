<?php
require("funcoes.php");
if (iniciaSessao()===true){
ini_set('max_execution_time', 0); 
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

//Busca Valores por Unidade  
  
$vlrGasolinaUnai        = buscaValorQtComb('UNAI',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$vlrGasolinaParacatu    = buscaValorQtComb('Paracatu',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$vlrGasolinaPirapora    = buscaValorQtComb('Pirapora',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$qtdGasolinaUnai        = buscaValorQtComb('UNAI',$dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
$qtdGasolinaParacatu    = buscaValorQtComb('Paracatu',$dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
$qtdGasolinaPirapora    = buscaValorQtComb('Pirapora',$dti,$dtf,'GASOLINA') ['QUANTIDADE_LITROS'];
$qtdEtanolPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS']; 
  
// Busca Valores e Quantidades Consolidadas 
$vlrGasolinaConsolidado   = buscaValorQtCombConsolidado($dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$qtdGasolinaConsolidado   = buscaValorQtCombConsolidado($dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
}?>

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
              <h1 class="main-title float-left">Dashboard </h1>
              <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Principal</li>
                <li class="breadcrumb-item active">Dashboard / Analise de Combustivel</li>
              </ol>
              <div class="clearfix"></div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="container">
        <div class="card-footer small text-muted">Ultima Sincronização de Tela:
          <?php echo buscaDataHora();                    
                    $arrayMensagem = verificaAtualizacaoPeriodoDadosSistema();
                    ?>
          <br>
          <span class="text-red"> A base de Dados Possui Registros de
            <?php echo $arrayMensagem['MOVIMENTO_INICIAL']?> á <?php echo $arrayMensagem['MOVIMENTO_FINAL']?> </span>
          <br>
          <span> Ultima importação: <?php echo $arrayMensagem['ULTIMA_IMPORTACAO']?> </span>

        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-12">
            <div class="card-header">
              <i class="fa fa-filter"></i> FILTRAR PERIODO DE <> ATÉ
            </div>
            <br>

            <form method="POST">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">
                  </div>
                </div>

                <div class="col">
                  <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar Graficos</button>
                </div>
              </div>
            </form>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> R$/L - CUSTO MÉDIO POR POLO
              </div>
              <div class="card-body">
                <canvas id="grafico1"></canvas>


              </div>
              <div class="card-footer small text-muted"></div>
            </div>
          </div>
          <hr>

          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> R$/L - CUSTO MÉDIO CONSOLIDADO
              </div>

              <div class="card-body">
                <canvas id="grafico2"></canvas>
              
              </div>
              <div class="card-footer small text-muted"></div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> QTD/L - QUANTIDADE LITROS POR UNIDADE
              </div>

              <div class="card-body">
                <canvas id="grafico3"></canvas>
              </div>
              <div class="card-footer small text-muted"></div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> QTD/L - QUANTIDADE LITROS TOTAL
              </div>

              <div class="card-body">
                <canvas id="grafico4"></canvas>


              </div>

              <br>
              <div class="card-footer small text-muted"></div>
            </div>
          </div>  
        </div>

        <footer class="footer">
          <span class="text-right">
            S.G.A.C Versão 1.0.0.1 <a target="_blank" href="#">2019 Potência Medicões</a>
          </span>
          <span class="float-right">
            Desenvolvido por <a target="_blank" href="http://www.potenciamedicoes.com.br"><b>Wesley Lopes</b></a>
          </span>
        </footer>
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
      <script src="assets/js/funcoes.js"></script>

      <script>
        $(document).ready(function() {
          // data-tables
          $('#example1').DataTable();

          // counter-up
          $('.counter').counterUp({
            delay: 300,
            time: 600
          });
        });

      </script>

      <script>
        var ctx1 = document.getElementById("grafico1").getContext('2d');
        var barChart = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: ["DIESEL", "GASOLINA", "ETANOL"],
            datasets: [{
              label: 'UNAI',
              data: ["<?php echo $vlrDieselUnai ?>", "<?php echo $vlrGasolinaUnai ?>", "<?php echo $vlrEtanolUnai ?>"],
              backgroundColor: [
                'rgba(251,195,0)',
                'rgba(251,195,0)',
                'rgba(251,195,0)',
              ],
              borderColor: [

              ],
              borderWidth: 1
            }, {
              label: 'PARACATU',
              data: ["<?php echo $vlrDieselParacatu ?>", "<?php echo $vlrGasolinaParacatu ?>", "<?php echo $vlrEtanolParacatu ?>"],
              backgroundColor: [
                'rgba(96,167,0)',
                'rgba(96,167,0)',
                'rgba(96,167,0)'
              ],
              borderColor: [

              ],
              responsive: true,
              borderWidth: 1,
              responsive: true,
            }, {
              label: 'PIRAPORA',
              data: ["<?php echo $vlrDieselPirapora ?>", "<?php echo $vlrGasolinaPirapora ?>", "<?php echo $vlrEtanolPirapora ?>"],
              backgroundColor: [
                'rgba(78,149,212)',
                'rgba(78,149,212)',
                'rgba(78,149,212)',
              ],
              borderColor: []

            }]
          },
          options: {
            title: {
              display: false,
              text: 'REFERENCIA'
            },

            tooltips: {
              enabled: true
            },
            hover: {
              animationDuration: 1
            },
            animation: {
              duration: 1000,
              onComplete: function() {
                var chartInstance = this.chart,
                  ctx = chartInstance.ctx;
                ctx.textAlign = 'center';
                ctx.fillStyle = "rgba(0, 0, 0, 1)";
                ctx.textBaseline = 'bottom';

                this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                  });
                });
              },

              tooltips: {

              },
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  fontColor: "white",
                  fontSize: 11,
                  stepSize: 6,
                  gridLines: {
                    lineWidth: 0
                  }

                }
              }]
            }
          }
        });

        var ctx1 = document.getElementById("grafico2").getContext('2d');
        var barChart = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: ["DIESEL", "GASOLINA", "ETANOL"],
            datasets: [{
              label: 'TIPOS DE COMBUSTÍVEIS',
              data: ["<?php echo $vlrDieselConsolidado ?>", "<?php echo $vlrGasolinaConsolidado?>", "<?php echo $vlrEtanolConsolidado ?>"],
              backgroundColor: [
                'rgba(251,195,0)',
                'rgba(251,195,0)',
                'rgba(251,195,0)',

              ],
              borderColor: [

              ],
              borderWidth: 1
            }, ]
          },
          options: {
            title: {
              display: false,
              text: 'REFERENCIA '
            },

            tooltips: {
              enabled: true
            },
            hover: {
              animationDuration: 1
            },
            responsive: true,
            animation: {
              duration: 1000,
              onComplete: function() {
                var chartInstance = this.chart,
                  ctx = chartInstance.ctx;
                ctx.textAlign = 'center';
                ctx.fillStyle = "rgba(0, 0, 0, 1)";
                ctx.textBaseline = 'bottom';

                this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                  });
                });
              },

              tooltips: {

              },
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  fontColor: "white",
                  fontSize: 11,
                  stepSize: 6
                }
              }]
            }
          }
        });

        var ctx1 = document.getElementById("grafico3").getContext('2d');
        var barChart = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: ["DIESEL", "GASOLINA", "ETANOL"],
            datasets: [{
              label: 'UNAI',
              data: ["<?php echo $qtdDieselUnai ?>", "<?php echo $qtdGasolinaUnai ?>", "<?php echo $qtdEtanolUnai ?>"],
              backgroundColor: [
                'rgba(251,195,0)',
                'rgba(251,195,0)',
                'rgba(251,195,0)',
              ],
              borderColor: [

              ],
              borderWidth: 1
            }, {
              label: 'PARACATU',
              data: ["<?php echo $qtdDieselParacatu ?>", "<?php echo $qtdGasolinaParacatu ?>", "<?php echo $qtdEtanolParacatu ?>"],
              backgroundColor: [
                'rgba(96,167,0)',
                'rgba(96,167,0)',
                'rgba(96,167,0)'
              ],
              borderColor: [

              ],
              responsive: true,
              borderWidth: 1,
              responsive: true,
            }, {
              label: 'PIRAPORA',
              data: ["<?php echo $qtdDieselPirapora ?>", "<?php echo $qtdGasolinaPirapora ?>", "<?php echo $qtdEtanolPirapora ?>"],
              backgroundColor: [
                'rgba(78,149,212)',
                'rgba(78,149,212)',
                'rgba(78,149,212)',
              ],
              borderColor: []
            }]
          },
          options: {
            title: {
              display: false,
              text: 'REFERENCIA'
            },

            tooltips: {
              enabled: true
            },
            hover: {
              animationDuration: 1
            },
            animation: {
              duration: 1000,
              onComplete: function() {
                var chartInstance = this.chart,
                  ctx = chartInstance.ctx;
                ctx.textAlign = 'center';
                ctx.fillStyle = "rgba(0, 0, 0, 1)";
                ctx.textBaseline = 'bottom';

                this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                    //ctx.font = "bold 90% calibri";                  
                    ctx.fillText(formatNumber(data), bar._model.x, bar._model.y - 5);
                  });
                });
              },

              tooltips: {

              },
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  fontColor: "white",
                  fontSize: 11,
                  stepSize: 9000,
                  gridLines: {
                    lineWidth: 0
                  }

                }
              }]
            }
          }
        });

        var ctx1 = document.getElementById("grafico4").getContext('2d');
        var barChart = new Chart(ctx1, {
          type: 'doughnut',
          data: {
            labels: ["DIESEL <?php echo $qtdDieselConsolidado ?>", "GASOLINA <?php echo $qtdGasolinaConsolidado?>", "ETANOL <?php echo $qtdEtanolConsolidado ?>"],
            datasets: [{
              label: '',
              data: ["<?php echo $qtdDieselConsolidado ?>", "<?php echo $qtdGasolinaConsolidado?>", "<?php echo $qtdEtanolConsolidado ?>"],
              backgroundColor: [
                'rgba(251,195,0,0.9)',
                'rgba(96,167,0,0.9)',
                'rgba(78,149,212,0.9)'
              ],
              borderColor: [

              ],
              borderWidth: 1
            }, ]
          },
          options: {
            title: {
              display: true,
              text: 'REFERÊNCIA ( <?php echo formataData($dti)?> Á <?php echo formataData($dtf) ?> )'
            },

            tooltips: {
              enabled: true
            },
            hover: {
              animationDuration: 1
            },
            responsive: true,

            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  fontColor: "white",
                  fontSize: 11,
                  stepSize: 0
                }
              }]
            }
          }
        });

      </script>
      <!-- END Java Script for this page -->

</body>

</html>
