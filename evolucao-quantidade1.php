<?php
require("funcoes.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); 
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

$date     = $dti;	        
$end_date = $dtf;

$arrayQtdDiesel=array();
$arrayQtdGasolina=array();
$arrayQtdEtanol=array();    
 
$dti30 = date('Y-m-d',strtotime($dti) - (0 * 24 * 60 * 60));
$dtf30 = date('Y-m-d',strtotime($dtf) - (0 * 24 * 60 * 60)); 
    
$dti60 = date('Y-m-d',strtotime($dti) - (28 * 24 * 60 * 60));                 
$dtf60 = date('Y-m-d',strtotime($dtf) - (31 * 24 * 60 * 60)); 
    
$dti90 = date('Y-m-d',strtotime($dti) - (59 * 24 * 60 * 60));
$dtf90 = date('Y-m-d',strtotime($dtf) - (59 * 24 * 60 * 60));                
    
 
$QtdGasolinaConsolidado30Dias   = buscaValorQtCombConsolidado($dti30,$dtf30,'GASOLINA')['QUANTIDADE_LITROS'];

$QtdEtanolConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'ETANOL')['QUANTIDADE_LITROS'];

$QtdDieselConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'DIESEL')['QUANTIDADE_LITROS'];
  
$QtdGasolinaConsolidado60Dias   = buscaValorQtCombConsolidado($dti60,$dtf60,'GASOLINA')['QUANTIDADE_LITROS'];
 
$QtdEtanolConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'ETANOL')['QUANTIDADE_LITROS'];
  
$QtdDieselConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'DIESEL')['QUANTIDADE_LITROS'];
   
$QtdGasolinaConsolidado90Dias   = buscaValorQtCombConsolidado($dti90,$dtf90,'GASOLINA')['QUANTIDADE_LITROS'];
  
$QtdEtanolConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'ETANOL')['QUANTIDADE_LITROS'];

$QtdDieselConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'DIESEL')['QUANTIDADE_LITROS'];
    

    
// Verifica se Houve Movimento no dia se não houver Repete o ultimo Preço de Combustivel abastecido.    
function verificaVazioPegaQtdAnterior($array,$valor){
  if (isset($valor)&& $valor == 0.00){
      return $array[count($array)-1];
  } else{
    return $valor;
  }
}
//De data Inicial á data Final busca valores na base de Dados e Alimenta Arrays de Exibição   
while (strtotime($date) <= strtotime($end_date)) {
  
  $qtdGasolinaConsolidado1   = buscaValorQtCombConsolidado($date,$date,'GASOLINA')['QUANTIDADE_LITROS'];
  $qtdEtanolConsolidado1     = buscaValorQtCombConsolidado($date,$date,'ETANOL')['QUANTIDADE_LITROS'];
  $qtdDieselConsolidado1     = buscaValorQtCombConsolidado($date,$date,'DIESEL')['QUANTIDADE_LITROS'];
  
  $arrayQtdDiesel[]          = $qtdDieselConsolidado1;  
  $arrayQtdGasolina[]        = $qtdGasolinaConsolidado1; 
  $arrayQtdEtanol[]          = $qtdEtanolConsolidado1;
  
  $dataFormatada= formataData($date);  
  $arrayData[] = "'$dataFormatada'"; 
  
  $date = date ("Y/m/d", strtotime("+1 day", strtotime($date)));  
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
                                        <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar Graficos</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> EVOLUÇÃO MENSAL CONSUMO MÉDIO COMBUSTÍVEL EM LITROS (90/60/30 DIAS)
                                </div>
                                <div class="card-body">
                                    <canvas id="grafico1"></canvas>
                                </div>
                                <div class="card-footer small text-muted"></div>
                            </div>
                        </div>
                    </div>
                </div>
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
            type: 'line',
            borderWidth: 10,
            data: {
                labels: ["<?php echo extrairMesAnoPorExtenso($dti90);?>", "<?php echo extrairMesAnoPorExtenso($dti60);?>", "<?php echo extrairMesAnoPorExtenso($dti30);?>"],
                datasets: [{
                    label: 'DIESEL',
                    data: [ < ? php echo $QtdDieselConsolidado90Dias; ? > , <
                        ?
                        php echo $QtdDieselConsolidado60Dias; ? > , <
                        ?
                        php echo $QtdDieselConsolidado30Dias; ? >
                    ],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(96,167,0,0.9)',
                    borderColor: 'rgba(96,167,0,0.9)',
                }, {
                    label: 'GASOLINA',
                    data: [ < ? php echo $QtdGasolinaConsolidado90Dias; ? > , <
                        ?
                        php echo $QtdGasolinaConsolidado60Dias; ? > , <
                        ?
                        php echo $QtdGasolinaConsolidado30Dias; ? >
                    ],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(255,167,0,0.9)',
                    borderColor: ['rgba(255,167,0,0.9)'],
                }, {
                    label: 'ETANOL',
                    data: [ < ? php echo $QtdEtanolConsolidado90Dias; ? > , <
                        ?
                        php echo $QtdEtanolConsolidado60Dias; ? > , <
                        ?
                        php echo $QtdEtanolConsolidado30Dias; ? >
                    ],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(78,149,212,0.9)',
                    borderColor: 'rgba(78,149,212,0.9)',
                }],
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
                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x - 5, bar._model.y - 15);
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
