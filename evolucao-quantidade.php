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
                                        <li class="breadcrumb-item active">(Trimestre - Diario)</li>
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
                        <div class="card-header">
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

                        if (isset($_POST["cidade"])){ 
                            $cidadeCheck = implode("','", $_POST["cidade"]);

                        }if (isset($_POST["polo"])){
                            $poloCheck = implode("','", $_POST["polo"]);

                        }if (isset($_POST["equipe"])){
                            $equipeCheck = implode("','", $_POST["equipe"]);

                        }if (isset($_POST["veiculo"])){
                            $veiculoCheck  = implode("','", $_POST["veiculo"]);

                        }if (isset($_POST["posto"])){
                            $postoCheck  = implode("','", $_POST["posto"]);

                        }if (isset($_POST["tpCombustivel"])){
                            $tipoCombustivelCheck  = implode("','", $_POST["tpCombustivel"]);
                            
                        }if (isset($_POST["tpVeiculo"])){
                            $tipoVeiculoCheck  = implode("','", $_POST["tpVeiculo"]);
                            
                        }if (isset($_POST["modeloVeiculo"])){
                            $modeloVeiculoCheck  = implode("','", $_POST["modeloVeiculo"]);   
                            
                        }
                        
                        $date     = $dti;	        
                        $end_date = $dtf;
                        $arrayVlrDiesel=array();
                        $arrayVlrGasolina=array();
                        $arrayVlrEtanol=array();    

                        $dti30 = date('Y-m-d',strtotime($dti) - (0 * 24 * 60 * 60));
                        $dtf30 = date('Y-m-d',strtotime($dtf) - (0 * 24 * 60 * 60)); 

                        $dti60 = date('Y-m-d',strtotime($dti) - (28 * 24 * 60 * 60));                 
                        $dtf60 = date('Y-m-d',strtotime($dtf) - (31 * 24 * 60 * 60)); 

                        $dti90 = date('Y-m-d',strtotime($dti) - (59 * 24 * 60 * 60));
                        $dtf90 = date('Y-m-d',strtotime($dtf) - (59 * 24 * 60 * 60));                


                        $vlrGasolinaConsolidado30Dias   = buscaValorQtCombConsolidado($dti30,$dtf30,'GASOLINA',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];  
                        
                        //$dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo, //$VeiculoCheck,$posto,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck

                        $vlrEtanolConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'ETANOL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrDieselConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'DIESEL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrGasolinaConsolidado60Dias   = buscaValorQtCombConsolidado($dti60,$dtf60,'GASOLINA',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrEtanolConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'ETANOL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrDieselConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'DIESEL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrGasolinaConsolidado90Dias   = buscaValorQtCombConsolidado($dti90,$dtf90,'GASOLINA', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrEtanolConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'ETANOL', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];

                        $vlrDieselConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'DIESEL', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck)['QUANTIDADE_LITROS'];


                        //De data Inicial á data Final busca valores na base de Dados e Alimenta Arrays de Exibição   
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> EVOLUÇÃO MENSAL PREÇO MÉDIO COMBUSTÍVEL ( ULTIMOS 3 MESES )
                                    </div>
                                    <div class="card-body">
                                        <canvas id="grafico1"></canvas>

                                    </div>
                                    <div class="card-footer small text-muted"></div>
                                </div>
                            </div>
                            <hr>
                        </div>                        

                        <!-- Start Barra Menu Lateral Esquerdo -->
                        <?php require_once ("front-end/bar-footer.php"); ?>
                        <!-- End Barra Menu Lateral Esquerdo -->


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

                        <script>
                            //código usando jQuery
                            $(document).ready(function() {
                                $('.load').hide();
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

                        </script>

                        <script>
                            var ctx1 = document.getElementById("grafico1").getContext('2d');
                            var barChart = new Chart(ctx1, {
                                type: 'line',
                                borderWidth: 10,
                                data: {
                                    labels: ["<?php echo extrairMesAnoPorExtenso($dti90);?>",
                                             "<?php echo extrairMesAnoPorExtenso($dti60);?>",
                                             "<?php echo extrairMesAnoPorExtenso($dti30);?>"
                                            ],
                                    datasets: [{
                                        label: 'DIESEL',
                                        data: [ <?php echo $vlrDieselConsolidado90Dias;?>, 
                                               <?php echo $vlrDieselConsolidado60Dias; ?>, 
                                               <?php echo $vlrDieselConsolidado30Dias; ?>
                                              ],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: ['rgba(96,167,0,0.9)'],
                                        borderColor: 'rgba(96,167,0,0.9)',
                                    }, {
                                        label: 'GASOLINA',
                                        data: [ <?php echo $vlrGasolinaConsolidado90Dias;?> , 
                                               <?php echo $vlrGasolinaConsolidado60Dias;?> , 
                                               <?php echo $vlrGasolinaConsolidado30Dias;?>
                                              ],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(255,167,0,0.9)',
                                        borderColor: ['rgba(255,167,0,0.9)'],
                                    }, {
                                        label: 'ETANOL',
                                        data: [ <?php echo $vlrEtanolConsolidado90Dias;?>, 
                                               <?php echo $vlrEtanolConsolidado60Dias;?>, 
                                               <?php echo $vlrEtanolConsolidado30Dias;?>
                                              ],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(78,149,212,0.9)',
                                        borderColor: 'rgba(78,149,212,0.9)',
                                    }],
                                },
                                options: {
                                    layout: {
                                        padding: {
                                            left: 50,
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
                                            display: true,
                                        }],
                                        yAxes: [{
                                            display: false,
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
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {                                                    
                                                    ctx.fillText(dataset.data[index].toFixed(2).replace('.', '.').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'), bar._model.x - 5, bar._model.y - 15);
                                                });
                                            });
                                        },
                                    },
                                }
                            });

                        </script>


                        </body>

                    </html>
