<?php
require("funcoes.php");
require("conexao.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); 
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

$sql="SELECT * FROM Veiculos"; 
  
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
  
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
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
                <div class="pull-left">

                  <button type="submit" class="btn btn-primary btn-sm  btn-block" data-toggle="modal" data-target="#myModalcad"><i class="fa fa fa-plus-circle"></i> INSERIR VEICULO</button>
                </div>
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

            <!-- Inicio Modal -->
            <div class="modal fade" id="myModalcad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title text-center" id="myModalLabel">Cadastro de Veiculo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">

                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label class="control-label" for="InputDataf">Tipo* </label><br>
                            <select name "opcoes" class="form-control">
                              <?php echo "<option>---  </option>";                                                            
                                    $sql="SELECT distinct(TIPO_FROTA) as TIPO_FROTA FROM movimento_veiculos ";
                                    $sql = $db->query($sql);
                                    $dados = $sql->fetchAll();
                              
                                    foreach ($dados as $quantidade){
                                        echo "<option>".utf8_encode($quantidade[TIPO_FROTA])."</option>"; 
                           }                                                             
                          ?>
                            </select>
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label for="recipient-name" class="control-label">Placa:</label>
                            <input name="nome" type="select" class="form-control">
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label class="control-label" for="InputDataf">Marca* </label><br>
                            <select name "opcoes" class="form-control">
                              <?php echo "<option>---  </option>";                                                            
                                    $sql="SELECT distinct(FABRICANTE_VEICULO) as TIPO_FROTA FROM movimento_veiculos ";
                                    $sql = $db->query($sql);
                                    $dados = $sql->fetchAll();
                              
                                    foreach ($dados as $quantidade){
                                        echo "<option>$quantidade[TIPO_FROTA]</option>"; 
                           }                                                             
                          ?>
                            </select>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label class="control-label" for="InputDataf">Modelo* </label><br>
                            <select name "opcoes" class="form-control">
                              <?php echo "<option>---  </option>";                                                            
                                    $sql="SELECT distinct(MODELO_VEICULO) as TIPO_FROTA FROM movimento_veiculos ";
                                    $sql = $db->query($sql);
                              
                                    if ($sql->rowCount()){
                                      echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://localhost/sgac/veiculos.php'>
				                            <script type=\"text/javascript\">
				             	            alert(\"Cadastro carregado com Sucesso.\");
                                            </script>";	                                      
                                    }                             
                              
                                    $dados = $sql->fetchAll();
                              
                                    foreach ($dados as $quantidade){
                                        echo "<option>$quantidade[TIPO_FROTA]</option>"; 
                           }                                                             
                          ?>

                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="recipient-name" class="control-label">Número de Cartão:</label>
                            <input name="nome" type="text" size="1" maxlength="1" class="form-control">
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label class="control-label" for="InputDataf">Status </label><br>
                            <select name "opcoes" class="form-control">
                              <option> ATIVO </option>
                              <option> DESATIVO </option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="message-text" class="control-label">Observação:</label>
                            <textarea name="detalhes" class="form-control"></textarea>
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fim Modal -->

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
                       
                       <?php    
                       foreach ($registros as $veiculo){ 
                         echo "<tr>";
                         echo "<td> &nbsp".$veiculo['PLACA_VEICULO']."</td>";
                         echo "<td>".$veiculo['NUMERO_CARTAO']."</td>";
                         echo "<td>".utf8_decode($veiculo['MODELO_VEICULO'])."</td>";
                         echo "<td>".utf8_encode($veiculo['TIPO_VEICULO'])."</td>";
                         echo "<td>".$veiculo['FABRICANTE_VEICULO']."</td>";
                         echo "<td>".$veiculo['CENTRO_RESULTADO']."</td>";
                         echo "<td>".$veiculo['STATUS']."</td>";
                           
                         echo "<td><button type='submit' class='btn btn-primary btn-sm'><i class='fa fa fa-edit'></i> </button>
                                <button type='submit' class='btn btn-danger btn-sm  '><i class='fa fa fa-trash-o'></i> </button>
                                </td>";                           
                         echo "</tr>";
                       }
                      ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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

  <script type="text/javascript">
    $('#exampleModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      var recipientnome = button.data('whatevernome')
      var recipientdetalhes = button.data('whateverdetalhes')
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('ID do Curso: ' + recipient)
      modal.find('#id_curso').val(recipient)
      modal.find('#recipient-name').val(recipientnome)
      modal.find('#detalhes-text').val(recipientdetalhes)
    })
  </script>

  <!-- END Java Script Pagina -->

</body>

</html>
