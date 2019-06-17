<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){
    ini_set('max_execution_time', 0); 

    $dti         = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf         = $_POST['dataf'];  // Captura data Final Formulário
    $qtdMotoristas = buscaQtdMotoristas($dti,$dtf);

    $qtdVeiculosAtivos = buscaQtdVeiculos($dti,$dtf)['QTD_VEICULOS_ATIVOS'];
    $qtdVeiculosCadastrados = buscaQtdVeiculos($dti,$dtf)['QTD_VEICULOS_CADASTRADOS'];

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
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>

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
                                    <h1 class="main-title float-left">VISÃO GERAL</h1>
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item">Principal</li>
                                        <li class="breadcrumb-item active">Dashboard / Analise de Combustivel</li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
                                                <button type="submit" id ="btn-atualizar-tabela"class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar</button>
                                            </div>
                                        </div>
                                    </form>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4 col-xl-4">  
                                        <div class="card-box noradius noborder bg-success">
                                            <i class="fas fa-exchange-alt float-right text-white"></i>
                                            <h6 class="text-white text-uppercase m-b-10">Transações no PERÍODO</h6>
                                            <span class="text-white">Quantidade</span>
                                            <h4 class="m-b-20 text-white "><?php echo buscaValorQtdtransacoes($dti,$dtf)['QTD_TRANSACOES']?></h4>
                                            <span class="text-white">Valor Combustiveis R$</span>
                                            <h4 class="m-b-20 text-white "><?php echo buscaValorQtdtransacoes($dti,$dtf)['VALOR_TRANSACOES']?></h4>
                                            <span class="text-white">Litragem</span>
                                            <h4 class="m-b-20 text-white "><?php echo buscaValorQtdtransacoes($dti,$dtf)['QUANTIDADE_LITROS']?></h4>
                                            <span class="text-white">Valor Outros R$</span>
                                            <h4 class="m-b-20 text-white "><?php echo buscaValorQtdtransacoes($dti,$dtf)['VALOR_TRANSACOES']?></h4>
                                            
                                            <span class="text-white">Analise de Combustivel</span>
                                            <button class="btn botao-painel float-right"><a class="text-white" href="detalhe.php?busca=transacoes&dti=<?php echo $_POST['datai']?>&dtf=<?php echo $_POST['dataf']?>">Ver Mais..</a></button>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                        <div class="card-box noradius noborder bg-warning">
                                            <i class="fa fa-car float-right text-white"></i>
                                            <h6 class="text-white text-uppercase m-b-10"> RESUMO Veiculos </h6>
                                            <span class="text-white">Cadastrados</span>
                                            <h4 class="m-b-20 text-white counter"><?php echo $qtdVeiculosCadastrados  ?></h4>
                                            <span class="text-white">Em Atividade</span>
                                            <h4 class="m-b-20 text-white counter"><?php echo $qtdVeiculosAtivos ?></h4>
                                            <span class="text-white">Cadastro de Veiculos</span>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                        <div class="card-box noradius noborder bg-default">
                                            <i class="fas fa-user float-right text-white"></i>
                                            <h6 class="text-white text-uppercase m-b-10">RESUMO MOTORISTAS</h6>
                                            <span class="text-white">Ativos</span>
                                            <h4 class="m-b-20 text-white counter"><?php echo $qtdMotoristas ?></h4>
                                            <span class="text-white">Motoristas que Abasteceram no periodo</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                            </div>
                                            <div class="card-footer small text-muted"></div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5><i class="fa fa-tint"></i> 10 ÚLTIMOS VEICULOS ABASTECIDOS</h5>
                                                
                                                Baseado na ultima data de carga de dados e filtros.
                                            </div>

                                            <div class="card-body">

                                                <div class="widget-messages nicescroll" style="height: 400px;">
                                                    <?php   
                                                 $sql= "SELECT  
                                             distinct(b.PLACA_VEICULO) as PLACA,
                                           A.MODELO_VEICULO MOD_VEICULO,
                                           A.FABRICANTE_VEICULO AS MARCA,
                                               A.VALOR_TOTAL AS VALOR,
                                               A.QUANTIDADE AS QUANTIDADE1,
                                               date_format(str_to_date(Date(a.data_movimento), '%Y-%m-%d '), '%d/%m/%Y') AS DATA_ABASTECIMENTO
                                             FROM movimento_veiculos a INNER JOIN veiculos B ON ( A.PLACA_VEICULO = B.PLACA_VEICULO) 
                                             AND Date(a.data_movimento) BETWEEN '$dti' AND '$dtf' ORDER BY a.data_movimento DESC LIMIT 10";  
                                                 
                                                $sql = $db->query($sql); 
                                                $dados= $sql->fetchAll(); 

                                                foreach ($dados as $quantidade){  
                                                    ?>
                                                    <a href="#">
                                                        <div class="message-item">
                                                            <p class="message-item-user"><?php echo utf8_encode($quantidade['PLACA']);?> </p>
                                                            <p class="message-item-msg"><?php echo utf8_encode($quantidade['MOD_VEICULO']);?></p>
                                                            <p class="message-item-date">R$ <?php echo formataNumero(utf8_encode($quantidade['VALOR']));?></p>
                                                        </div>
                                                    </a>
                                                    <?php              
                                                           }     
                                                    ?>
                                                </div>

                                            </div>
                                            <button class="btn botao-painel float-right btn-link"><a href="detalhe.php?busca=abastecimento">Ver Mais..</a></button>
                                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                                        </div><!-- end card-->
                                    </div>  
                                    
                                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5><i class="fa fa-money"></i> 10 CONDUTORES COM MAIOR DESPESA </h5>
                                                Baseado no total em R$  e filtros de periodo.
                                            </div>

                                            <div class="card-body">

                                                <div class="widget-messages nicescroll" style="height: 400px;">
                                                    <?php   
                                                     $sql= "SELECT MOTORISTA,
                                                     B.TIPO_VEICULO as TP_VEICULO,
                                                     A.MODELO_VEICULO as MOD_VEICULO,
                                                     ROUND(SUM(a.VALOR_TOTAL),2) AS VALOR,
                                                     A.CENTRO_RESULTADO AS C_RESULTADO FROM 
                                                     sgacbase.movimento_veiculos a  
                                                     INNER JOIN veiculos B ON ( A.PLACA_VEICULO = B.PLACA_VEICULO)
                                                     AND Date(a.data_movimento) BETWEEN '$dti' AND '$dtf'
                                                     GROUP BY A.MOTORISTA
                                                     ORDER BY VALOR DESC LIMIT 10";  
                                                 
                                                $sql = $db->query($sql); 
                                                $dados= $sql->fetchAll(); 

                                                foreach ($dados as $quantidade){  
                                                    ?>
                                                    <a href="#">
                                                        <div class="message-item">
                                                            <p class="message-item-user"><?php echo utf8_encode($quantidade['MOTORISTA']);?> </p>
                                                            <p class="message-item-msg"><?php echo utf8_encode($quantidade['C_RESULTADO']);?></p>
                                                            <p class="message-item-date">R$ <?php echo formataNumero(utf8_encode($quantidade['VALOR']));?></p>
                                                        </div>
                                                    </a>
                                                    <?php              
                                                           }     
                                                    ?>
                                                </div>

                                            </div>
                                            <button class="btn botao-painel float-right btn-link"><a href="detalhe.php?busca=condutores">Ver Mais..</a></button>
                                            <div class="card-footer small text-muted">Atualizado Hoje</div>
                                        </div><!-- end card-->
                                    </div> 
                                </div>

                                <!-- Start Barra Menu Lateral Esquerdo -->
                                <?php require_once ("front-end/bar-footer.php"); ?>
                                <!-- End Barra Menu Lateral Esquerdo -->

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
                                        delay: 300,
                                        time: 300
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
                                                    stepSize: 9,
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
