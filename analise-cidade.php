<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){ 
    ini_set('max_execution_time', 0); //teste

    $dti   = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf   = $_POST['dataf'];  // Captura data Final Formulário    
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
                                <?php require_once ("front-end/form-pesquisa-padrao.php"); ?>
                            </div>


                            <?php
                                $cidadeCheck='';
                                $poloCheck='';
                                $equipeCheck='';
                                $veiculo='';
                                $veiculoCheck='';
                                $postoCheck=''; 
                                $tipoCombustivelCheck='';
                                $tipoVeiculoCheck='';
                                $modeloVeiculoCheck ='';

                                $sql="SELECT DISTINCT(CIDADE) as CIDADE FROM movimento_veiculos a 
                                                 WHERE DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'";
                              
                                 if (isset($_POST["cidade"])){ 
                                $cidadeCheck = implode("','", $_POST["cidade"]);
                                $sql.="AND CIDADE IN ('$cidadeCheck')";

                            }if (isset($_POST["polo"])){
                                $poloCheck = implode("','", $_POST["polo"]);
                                $sql.="AND CENTRO_RESULTADO IN('$poloCheck')";

                            }if (isset($_POST["equipe"])){
                                $equipeCheck = implode("','", $_POST["equipe"]);
                                $sql.="AND CENTRO_CUSTO IN ('$equipeCheck')";

                            }if (isset($_POST["veiculo"])){
                                $veiculoCheck  = implode("','", $_POST["veiculo"]);
                                $sql.="AND PLACA_VEICULO IN('$veiculoCheck')"; 
                            }if (isset($_POST["posto"])){
                                $postoCheck  = implode("','", $_POST["posto"]);
                                $sql.="AND NOME_POSTO IN('$postoCheck')";
                            }
                            if (isset($_POST["tpCombustivel"])){
                                $tipoCombustivelCheck  = implode("','", $_POST["tpCombustivel"]);
                                $sql.="AND PRODUTO IN('$tipoCombustivelCheck')";
                            }
                            if (isset($_POST["tpVeiculo"])){
                                $tipoVeiculoCheck  = implode("','", $_POST["tpVeiculo"]);

                                $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";
                            }

                            if (isset($_POST["modeloVeiculo"])){
                                $modeloVeiculoCheck  = implode("','", $_POST["modeloVeiculo"]);

                                $sql.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
                            }

                                 //echo "<br>".$sql."<br>";

                                $sql = $db->query($sql);            
                                $registros = $sql->fetchAll(); 

                                $arrayGasolinaConsolidado =array();
                                $arrayEtanolConsolidado   =array();  
                                $arrayDieselConsolidado   =array(); 

                                if($sql->rowCount() >0){

                                    foreach ($registros as $registro){
                                        $cidade =  $registro['CIDADE'];

                                        $arrayGasolinaConsolidado[]   = buscaValorQtCombCidadeConsolidado(                                            $dti, $dtf,'GASOLINA',$cidade, $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,
                                           $tipoCombustivelCheck, $tipoVeiculoCheck, $modeloVeiculoCheck
                                        );

                                        $arrayEtanolConsolidado[]   = buscaValorQtCombCidadeConsolidado(
                                           $dti, $dtf, 'ETANOL', $cidade, $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck, $tipoCombustivelCheck, $tipoVeiculoCheck, $modeloVeiculoCheck
                                        );

                                        $arrayDieselConsolidado[]= buscaValorQtCombCidadeConsolidado(      
                                          $dti, $dtf, 'DIESEL', $cidade, $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,
                                          $tipoCombustivelCheck, $tipoVeiculoCheck, $modeloVeiculoCheck
                                        );
                                    } 
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


                                function montaGraficoValor($array){
                                    return implode(',', array_map(function ($entry) {

                                        return $entry['VALOR_COMBUSTIVEL']; }, $array));  
                                } 

                                function montaGraficoCidade($array){
                                    return implode('","', array_map(function ($entry) {

                                        return $entry['CIDADE']; }, $array));  
                                } 

                                function montaGraficoQuantidade($array){
                                    return implode(',', array_map(function ($entry) {

                                        return $entry['QUANTIDADE_LITROS']; }, $array));  
                                } 
                                ?>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
                                <div class="card-footer small text-muted"></div>


                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <i class="fa fa-table"></i> PREÇO X QUANTIDADE MÉDIA CONSUMIDA DE COMBUSTÍVEL POR CIDADE
                                            <span class="badge badge-success">(GASOLINA)</span>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico2"></canvas>

                                        </div>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
                            <!-- Start Barra Menu Lateral Esquerdo -->
                            <?php require_once ("front-end/bar-footer.php"); ?>
                            <!-- End Barra Menu Lateral Esquerdo -->
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

        <!-- Counter-Up-->
        <script src="assets/plugins/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>
        <script src="assets/js/detect.js"></script>

        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/plugins/switchery/switchery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

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
            //código usando jQuery
            $(document).ready(function() {
                $('.load').hide();
            });

        </script>

        <script>
            var ctx1 = document.getElementById("grafico1").getContext('2d');
            var barChart = new Chart(ctx1, {
                type: 'horizontalBar',
                borderWidth: 30,
                data: {
                    labels: ["<?php echo  montaGraficoCidade($arrayValorDieselAgrupado);?>"],
                    datasets: [{
                        label: 'QUANTIDADE MÉDIA EM LITROS',

                        data: [<?php echo montaGraficoQuantidade(agrupaArray($arrayValorDieselAgrupado, $arrayQuantidadeDieselAgrupado))?> ],
                        responsive: false,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    }, {
                        label: 'PREÇO MÉDIO',
                        data: [<?php echo montaGraficoValor($arrayValorDieselAgrupado);?>],
                        responsive: false,
                        fill: false,
                        backgroundColor: 'rgba(251,195,0,0.9)',
                        borderColor: 'rgba(251,195,0,0.9)',
                    }],
                },
                options: {
                    layout: {
                        padding: {
                            left: 10,
                            right: 100,
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
                            ctx.strokeStyle = '#fff000';
                            this.data.datasets.forEach(function(dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
                                    ctx.fillText(dataset.data[index].toFixed(2).replace('.', '.').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'), bar._model.x + 25, bar._model.y - 5);
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
                        label: 'QUANTIDADE MÉDIA EM LITROS',
                        data: [<?php echo montaGraficoQuantidade(agrupaArray($arrayValorGasolinaAgrupado, $arrayQuantidadeGasolinaAgrupado))?> ],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    }, {
                        label: 'PREÇO MÉDIO',
                        data: [<?php echo montaGraficoValor($arrayValorGasolinaAgrupado);?>],
                        responsive: false,
                        fill: false,
                        backgroundColor: 'rgba(96,167,0,0.9)',
                        borderColor: 'rgba(96,167,0,0.9)',
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
                            // ctx.font = "70% bold Calibri";
                            ctx.fillStyle = ['blue', 'red'];
                            ctx.strokeStyle = '#fff000';
                            this.data.datasets.forEach(function(dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
                                    ctx.fillText(dataset.data[index].toFixed(2).replace('.', '.').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'), bar._model.x + 25, bar._model.y - 5);
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
                        label: 'QUANTIDADE MÉDIA EM LITROS',
                        data: [<?php echo montaGraficoQuantidade(agrupaArray($arrayValorEtanolAgrupado, $arrayQuantidadeEtanolAgrupado)) ?>],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    }, {
                        label: 'PREÇO MÉDIO',
                        data: [<?php echo montaGraficoValor($arrayValorEtanolAgrupado); ?>],
                        responsive: false,
                        fill: false,
                        backgroundColor: 'rgba(78,149,212,0.9)',
                        borderColor: 'rgba(78,149,212,0.9)',
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
                            margin: 30
                        }],
                        yAxes: [{
                            display: true,
                            margin: 30
                        }]
                    },
                    animation: {
                        duration: 1000,
                        onComplete: function() {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = 'rgba(25,0,0,0.9)';
                            //ctx.font = "100% bold Calibri";
                            ctx.fillStyle = ['blue', 'red'];
                            ctx.strokeStyle = '#fff000';
                            this.data.datasets.forEach(function(dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
                                    ctx.fillText(dataset.data[index].toFixed(2).replace('.', '.').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'), bar._model.x + 25, bar._model.y - 5);
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
