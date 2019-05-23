<?php
require("funcoes.php");
require("conexao.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); //teste
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

$sql="SELECT DISTINCT(CIDADE) FROM movimento_veiculos WHERE DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'"; 
  
$sql = $db->query($sql);            
$registros = $sql->fetchAll();       
            
foreach ($registros as $registro){
  $cidade =  $registro['CIDADE']; 
  
  $arrayGasolinaConsolidado[]   = buscaValorQtCombCidadeConsolidado($dti,$dtf,'GASOLINA',$cidade);
  $arrayEtanolConsolidado[]     = buscaValorQtCombCidadeConsolidado($dti,$dtf,'ETANOL',$cidade);
  $arrayDieselConsolidado[]     = buscaValorQtCombCidadeConsolidado($dti,$dtf,'DIESEL',$cidade); 
}  

// Remove Registros Duplicados e Agrupa as quantidades   
function agrupaArray($array) {
    $agrupador = array();
    foreach ($array as $item) {
        $key = $item['CIDADE'];
      if (isset($key)){ 
        if (!isset($agrupador[$key])) {
            $agrupador[$key] = array(
                'CIDADE' => $key,
                'VALOR_COMBUSTIVEL' => $item['VALOR_COMBUSTIVEL'],
                'TIPO_COMBUSTIVEL_BUSCA' => $item['TIPO_COMBUSTIVEL_BUSCA'],
                'QUANTIDADE_LITROS' => $item['QUANTIDADE_LITROS'],
            );
        } 
      }
    }  
   return $agrupador;
} 
    
 $arrayValorGasolinaAgrupado = agrupaArray($arrayGasolinaConsolidado);
 $arrayValorEtanolAgrupado   = agrupaArray($arrayEtanolConsolidado); 
 $arrayValorDieselAgrupado   = agrupaArray($arrayDieselConsolidado);
    
 $arrayQuantidadeGasolinaAgrupado = agrupaArray($arrayGasolinaConsolidado);
 $arrayQuantidadeEtanolAgrupado   = agrupaArray($arrayEtanolConsolidado); 
 $arrayQuantidadeDieselAgrupado   = agrupaArray($arrayDieselConsolidado);
 
 function ordenaArrayValorCombustivel($a, $b) { 
    return $a['QUANTIDADE_LITROS'] < $b['QUANTIDADE_LITROS']; 
 }
    
function ordenaArrayQuantidadeCombustivel($a, $b) { 
   return $a['CIDADE'] == $b['CIDADE']; 
 }   
    
 usort($arrayValorGasolinaAgrupado,'ordenaArrayValorCombustivel');
 usort($arrayValorEtanolAgrupado,'ordenaArrayValorCombustivel');
 usort($arrayValorDieselAgrupado,'ordenaArrayValorCombustivel');
    
 usort($arrayQuantidadeGasolinaAgrupado,'ordenaArrayQuantidadeCombustivel');
 usort($arrayQuantidadeEtanolAgrupado,'ordenaArrayQuantidadeCombustivel');
 usort($arrayQuantidadeDieselAgrupado,'ordenaArrayQuantidadeCombustivel');
    
/*    
 echo "<pre>";
 echo "<br>";
   print_r($arrayQuantidadeDieselAgrupado);
 echo "</pre>";
 
    echo "<br>";
      echo "<-------------------------------------------------------->";
  echo "<pre>";
 echo "<br>";
   print_r($arrayValorDieselAgrupado);
 echo "</pre>";*/
    
function montaGraficoValor($array){
return implode('","', array_map(function ($entry) {
 
return $entry['VALOR_COMBUSTIVEL']; }, $array));  
  } 
    
function montaGraficoCidade($array){
return implode('","', array_map(function ($entry) {

return $entry['CIDADE']; }, $array));  
  } 
    
function montaGraficoQuantidade($array){
return implode('","', array_map(function ($entry) {
 
return $entry['QUANTIDADE_LITROS']; }, $array));  
  }  
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

    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

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
                                    <li class="breadcrumb-item">Evolução de Preço de Combustível</li>
                                    <li class="breadcrumb-item active">(Diario-Trimestre)</li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="container-fluid">
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
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card-header">
                                <i class="fa fa-filter"></i> FILTRAR PERIODO DE <> ATÉ
                            </div>
                            <br>

                            <form method="POST">
                                <div class="row">
                                    <div class="col">
                                        <label for="example3">
                                            Data Final:
                                        </label>
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="example3">
                                            Data Final:
                                        </label>
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="example3">
                                            Cidade:
                                        </label>
                                        <select class="form-control select2" id="example2" name="state[]" multiple="multiple2">
                                               <?php 
                                            $sql="SELECT distinct(CIDADE) as CIDADE
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['CIDADE']."</option>"; 
                                            } 
                                                            
                                            ?>

                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="example3">
                                            Polo:
                                        </label>
                                        <select class="form-control select2" id="example1" name="state2[]" multiple="multiple">
                                               <?php 
                                            $sql="SELECT distinct(CENTRO_RESULTADO) as CENTRO_RESULTADO
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['CENTRO_RESULTADO']."</option>"; 
                                            } 
                                                            
                                            ?>

                                        </select>
                                    </div>

                                    <div class="col">
                                        <label for="example4">
                                            Equipe:
                                        </label>
                                        <select class="form-control select2" id="example4" name="state3[]" multiple="multiple3">
                                            <?php 
                                            $sql="SELECT distinct(CENTRO_CUSTO) as CENTRO_CUSTO
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['CENTRO_CUSTO']."</option>"; 
                                            } 
                                                            
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <label for="example5">
                                            motorista:
                                        </label>
                                        <select class="form-control select2" id="example5" name="state3[]" multiple="multiple4">
                                               <?php 
                                            $sql="SELECT distinct(MOTORISTA) as MOTORISTA
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['MOTORISTA']."</option>"; 
                                            } 
                                                            
                                            ?>

                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="example5">
                                            Veiculo:
                                        </label>
                                        <select class="form-control select2" id="example6" name="state3[]" multiple="multiple5">
                                              <?php 
                                            $sql="SELECT distinct(PLACA_VEICULO) as PLACA_VEICULO
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['PLACA_VEICULO']."</option>"; 
                                            } 
                                                            
                                            ?>

                                        </select>
                                    </div>

                                    <div class="col">
                                        <label for="example5">
                                            Posto:
                                        </label>
                                        <select class="form-control select2" id="example7" name="state3[]" multiple="multiple6">

                                            <?php 
                                            $sql="SELECT distinct(NOME_POSTO) as NOME_POSTO
                                                  FROM movimento_veiculos";
                                            $sql = $db->query($sql);
                                            $dados = $sql->fetchAll();
                                            
                                            foreach ($dados as $quantidade){
                                               echo "<option>".$quantidade['NOME_POSTO']."</option>"; 
                                            } 
                                                            
                                            ?>

                                        </select>
                                    </div>

                                    <div class="col">
                                        <label for="example2">

                                        </label>
                                        <br>
                                        <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> PREÇO X QUANTIDADE MÉDIA CONSUMIDA DE COMBUSTÍVEL POR CIDADE
                                        <h8><span class="badge badge-warning">(DIESEL)</span></h8>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafico1"></canvas>
                                    </div>
                                    <div class="card-footer small text-muted"></div>
                                </div>
                            </div>
                            <hr>

                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> PREÇO X QUANTIDADE MÉDIA CONSUMIDA DE COMBUSTÍVEL POR CIDADE
                                        <span class="badge badge-success"> (GASOLINA)</span>
                                    </div>

                                    <div class="card-body">
                                        <canvas id="grafico2"></canvas>

                                    </div>
                                    <div class="card-footer small text-muted"></div>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> PREÇO X QUANTIDADE MÉDIA CONSUMIDA DE COMBUSTÍVEL POR CIDADE
                                        <span class="badge badge-primary">(ETANOL)</span>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafico3"></canvas>

                                    </div>
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
                        <!-- BEGIN Java Script for this page -->
                        <script src="assets/plugins/select2/js/select2.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('.select2').select2();
                            });

                        </script>
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
                        <script src="assets/js/detect.js"></script>

                        <script src="assets/js/jquery.scrollTo.min.js"></script>
                        <script src="assets/plugins/switchery/switchery.min.js"></script>

                        <!-- App js -->



                        <script src="assets/js/funcoes.js"></script>

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
                                borderWidth: 30,
                                data: {
                                    labels: ["<?php echo  montaGraficoCidade($arrayValorDieselAgrupado);?>"],
                                    datasets: [{
                                        label: 'PREÇO MÉDIO',
                                        data: ["<?php echo  montaGraficoValor($arrayValorDieselAgrupado); ?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(251,195,0,0.9)',
                                        borderColor: 'rgba(251,195,0,0.9)',
                                    }, {
                                        label: 'QUANTIDADE MÉDIA EM LITROS',
                                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorDieselAgrupado,$arrayQuantidadeDieselAgrupado))?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(255,140,0,0.9)',
                                        borderColor: 'rgba(255,140,0,0.9)',
                                    }],
                                },
                                options: {
                                    layout: {
                                        padding: {
                                            left: 10,
                                            right: 50,
                                            top: 10,
                                            bottom: 10
                                        }
                                    },
                                    title: {
                                        display: false,
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: false,
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
                                            // ctx.font = "bold 11px Calibri";
                                            ctx.fillStyle = ['blue', 'red'];
                                            ctx.strokeStyle = '#fff000';
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    data2 = data;
                                                    ctx.fillText(data2, bar._model.x + 20, bar._model.y - 5);
                                                });
                                            });
                                        },
                                    },
                                }
                            });

                        </script>

                        <script>
                            var ctx1 = document.getElementById("grafico2").getContext('2d');
                            var barChart = new Chart(ctx1, {
                                type: 'horizontalBar',
                                borderWidth: 30,
                                data: {
                                    labels: ["<?php echo  montaGraficoCidade($arrayValorGasolinaAgrupado);?>"],
                                    datasets: [{
                                        label: 'PREÇO MÉDIO',
                                        data: ["<?php echo  montaGraficoValor($arrayValorGasolinaAgrupado); ?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(96,167,0,0.9)',
                                        borderColor: 'rgba(96,167,0,0.9)',
                                    }, {
                                        label: 'QUANTIDADE MÉDIA EM LITROS',
                                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorGasolinaAgrupado,$arrayQuantidadeGasolinaAgrupado))?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(255,140,0,0.9)',
                                        borderColor: 'rgba(255,140,0,0.9)',
                                    }],
                                },
                                options: {
                                    layout: {
                                        padding: {
                                            left: 10,
                                            right: 50,
                                            top: 10,
                                            bottom: 10
                                        }
                                    },
                                    title: {
                                        display: false,
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: false,
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
                                            //ctx.font = "bold 11px Calibri";
                                            ctx.fillStyle = ['blue', 'red'];
                                            ctx.strokeStyle = '#fff000';
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    data2 = data;
                                                    ctx.fillText(data2, bar._model.x + 20, bar._model.y - 5);
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
                                borderWidth: 30,
                                data: {
                                    labels: ["<?php echo  montaGraficoCidade($arrayValorEtanolAgrupado);?>"],
                                    datasets: [{
                                        label: 'PREÇO MÉDIO',
                                        data: ["<?php echo  montaGraficoValor($arrayValorEtanolAgrupado); ?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(78,149,212,0.9)',
                                        borderColor: 'rgba(78,149,212,0.9)',
                                    }, {
                                        label: 'QUANTIDADE MÉDIA EM LITROS',
                                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorEtanolAgrupado,$arrayQuantidadeEtanolAgrupado))?>"],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(255,140,0,0.9)',
                                        borderColor: 'rgba(255,140,0,0.9)',
                                    }],
                                },
                                options: {
                                    layout: {
                                        padding: {
                                            left: 10,
                                            right: 50,
                                            top: 10,
                                            bottom: 10
                                        }
                                    },
                                    title: {
                                        display: false,
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: false,
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
                                            // ctx.font = "bold 11px Calibri";
                                            ctx.fillStyle = ['blue', 'red'];
                                            ctx.strokeStyle = '#fff000';
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    data2 = data;
                                                    ctx.fillText(data2, bar._model.x + 20, bar._model.y - 5);
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
