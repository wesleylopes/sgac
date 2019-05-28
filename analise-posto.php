<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){ 
    ini_set('max_execution_time', 0); 

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
                                    <i class="fa fa-filter"></i> FILTROS
                                </div>

                                <div class=" container card mb-3">
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example3">
                                                    Data Inicial:
                                                </label>
                                                <div class="form-group ">
                                                    <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                                                </div>
                                            </div>
                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example3">
                                                    Data Final:
                                                </label>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">
                                                </div>
                                            </div>

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example3">
                                                    Cidade:
                                                </label>
                                                <select multiple class=" form-control select2" id="cidade" name="cidade[]" multiple="cidade">
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

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example3">
                                                    Polo:
                                                </label>
                                                <select multiple class=" form-control select2" id="polo" name="polo[]">
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

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example4">
                                                    Equipe:
                                                </label>
                                                <select multiple class=" form-control select2" id="equipe" name="equipe[]">
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

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example5">
                                                    Veiculo - motorista:
                                                </label>
                                                <select multiple class=" form-control select2" id="veiculo" name="veiculo[]">
                                                    <?php 
                                                    $sql="SELECT distinct(PLACA_VEICULO) as PLACA_VEICULO,MOTORISTA
                                                  FROM movimento_veiculos";
                                                    $sql = $db->query($sql);
                                                    $dados = $sql->fetchAll();

                                                    foreach ($dados as $quantidade){
                                                        echo "<option value=".$quantidade['PLACA_VEICULO'].   ">".$quantidade['PLACA_VEICULO']." - ".$quantidade['MOTORISTA']."</option>"; 
                                                    } 

                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example5">
                                                    Posto:
                                                </label>
                                                <select multiple class=" form-control select2" id="posto" name="posto[]">

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

                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                                <label for="example2">

                                                </label>
                                                <br>
                                                <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <?php

                                $cidadeCheck='';
                                $poloCheck='';
                                $equipeCheck='';
                                $veiculo='';
                                $veiculoCheck='';
                                $postoCheck='';

                                $sql="SELECT DISTINCT(NOME_POSTO) as NOME_POSTO FROM movimento_veiculos a 
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

                                 //echo "TESTE3 <br>".$sql."<br>";

                                $sql = $db->query($sql);            
                                $registros = $sql->fetchAll(); 

                                $arrayGasolinaConsolidado =array();
                                $arrayEtanolConsolidado   =array();  
                                $arrayDieselConsolidado   =array(); 

                                if($sql->rowCount() >0){

                                    foreach ($registros as $registro){
                                        $posto =  $registro['NOME_POSTO'];

                                        $arrayGasolinaConsolidado[]   = buscaValorQtCombPostoConsolidado(
                                            $dti,$dtf,'GASOLINA',$cidadeCheck,$poloCheck,$equipeCheck,$veiculoCheck,$posto
                                        );

                                        $arrayEtanolConsolidado[]   = buscaValorQtCombPostoConsolidado(
                                            $dti,$dtf,'ETANOL',$cidadeCheck,$poloCheck,$equipeCheck,$veiculoCheck,$posto
                                        );

                                        $arrayDieselConsolidado[]= buscaValorQtCombPostoConsolidado(
                                            $dti,$dtf,'DIESEL',$cidadeCheck,$poloCheck,$equipeCheck,$veiculoCheck,$posto
                                        );
                                    } 
                                }

                                

                                    // Remove Registros Duplicados e Agrupa as quantidades   
                                    function agrupaArray($array) {
                                    $agrupador = array();
                                    foreach ($array as $item) {
                                        $key = $item['NOME_POSTO'];
                                        if (isset($key)){ 
                                            if (!isset($agrupador[$key])) {
                                                $agrupador[$key] = array(
                                                    'NOME_POSTO' => $key,
                                                    'VALOR_COMBUSTIVEL' => $item['VALOR_COMBUSTIVEL'],
                                                    'TIPO_COMBUSTIVEL_BUSCA' => $item['TIPO_COMBUSTIVEL_BUSCA'],
                                                    'QUANTIDADE_LITROS' => $item['QUANTIDADE_LITROS'],
                                                    'CIDADE' => $item['CIDADE'],
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
                                    return $a['NOME_POSTO'] == $b['NOME_POSTO']; 
                                }   

                                usort($arrayValorGasolinaAgrupado,'ordenaArrayValorCombustivel');
                                usort($arrayValorEtanolAgrupado,'ordenaArrayValorCombustivel');
                                usort($arrayValorDieselAgrupado,'ordenaArrayValorCombustivel');

                                usort($arrayQuantidadeGasolinaAgrupado,'ordenaArrayQuantidadeCombustivel');
                                usort($arrayQuantidadeEtanolAgrupado,'ordenaArrayQuantidadeCombustivel');
                                usort($arrayQuantidadeDieselAgrupado,'ordenaArrayQuantidadeCombustivel');


                                function montaGraficoValor($array){
                                    return implode('","', array_map(function ($entry) {

                                        return $entry['VALOR_COMBUSTIVEL']; }, $array));  
                                } 

                                function montaGraficoPosto($array){
                                    return implode('","', array_map(function ($entry) {

                                        return $entry['NOME_POSTO']; }, $array));  
                                } 

                                function montaGraficoQuantidade($array){
                                    return implode('","', array_map(function ($entry) {

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
                                    <hr>

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
        <script src="assets/plugins/select2/js/select2.min.js"></script>
        <script>           
        $('.select2').select2();
            
        </script>
                   
       <script src = "assets/js/pikeadmin.js" >

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
               
            });
            }

        </script>

        <script>
            var ctx1 = document.getElementById("grafico1").getContext('2d');
            var barChart = new Chart(ctx1, {
                type: 'horizontalBar',
                borderWidth: 30,
                data: {
                    labels: ["<?php echo  montaGraficoPosto($arrayValorDieselAgrupado);?>"],
                    datasets: [ {
                        label: 'QUANTIDADE MÉDIA EM LITROS',
                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorDieselAgrupado,$arrayQuantidadeDieselAgrupado))?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    },{
                        label: 'PREÇO MÉDIO',
                        data: ["<?php echo  montaGraficoValor($arrayValorDieselAgrupado); ?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(251,195,0,0.9)',
                        borderColor: 'rgba(251,195,0,0.9)',
                    }],
                },
                options: {
                    layout: {
                        padding: {
                            left: 0,
                            right: 20,
                            top: 0,
                            bottom: 0
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
                            ctx.font = "70% bold Calibri";
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
                    labels: ["<?php echo  montaGraficoPosto($arrayValorGasolinaAgrupado);?>"],
                    datasets: [ {
                        label: 'QUANTIDADE MÉDIA EM LITROS',
                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorGasolinaAgrupado,$arrayQuantidadeGasolinaAgrupado))?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    },{
                        label: 'PREÇO MÉDIO',
                        data: ["<?php echo  montaGraficoValor($arrayValorGasolinaAgrupado); ?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(96,167,0,0.9)',
                        borderColor: 'rgba(96,167,0,0.9)',
                    }],
                },
                options: {
                    layout: {
                        padding: {
                            left: 0,
                            right: 20,
                            top: 0,
                            bottom: 0
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
                            ctx.font = "70% bold Calibri";
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
                    labels: ["<?php echo  montaGraficoPosto($arrayValorEtanolAgrupado);?>"],
                    datasets: [ {
                        label: 'QUANTIDADE MÉDIA EM LITROS',
                        data: ["<?php echo montaGraficoQuantidade(agrupaArray($arrayValorEtanolAgrupado,$arrayQuantidadeEtanolAgrupado))?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(255,140,0,0.9)',
                        borderColor: 'rgba(255,140,0,0.9)',
                    },{
                        label: 'PREÇO MÉDIO',
                        data: ["<?php echo  montaGraficoValor($arrayValorEtanolAgrupado); ?>"],
                        responsive: true,
                        fill: false,
                        backgroundColor: 'rgba(78,149,212,0.9)',
                        borderColor: 'rgba(78,149,212,0.9)',
                    }],
                },
                options: {
                    layout: {
                        padding: {
                            left: 0,
                            right: 20,
                            top: 0,
                            bottom: 0
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
                            ctx.font = "70% bold Calibri";
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
