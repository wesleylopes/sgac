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
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrEtanolConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'ETANOL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrDieselConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'DIESEL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrGasolinaConsolidado60Dias   = buscaValorQtCombConsolidado($dti60,$dtf60,'GASOLINA',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrEtanolConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'ETANOL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrDieselConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'DIESEL',$cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrGasolinaConsolidado90Dias   = buscaValorQtCombConsolidado($dti90,$dtf90,'GASOLINA', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrEtanolConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'ETANOL', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                        $vlrDieselConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'DIESEL', $cidadeCheck,
                                                                                      $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];


                        // Verifica se Houve Movimento no dia se não houver Repete o ultimo Preço de Combustivel abastecido.    
                        function verificaVazioPegaVlrAnterior($array,$valor){
                            if (!isset($valor)&& $valor == 0.00){
                                return $array[count($array)-1];
                            } else{
                                return $valor;
                            }
                        }
                        //De data Inicial á data Final busca valores na base de Dados e Alimenta Arrays de Exibição   
                        while (strtotime($date) <= strtotime($end_date)) {

                            $vlrGasolinaConsolidado1   = buscaValorQtCombConsolidado($date,$date,'GASOLINA', $cidadeCheck,
                                                                                     $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                            $vlrEtanolConsolidado1     = buscaValorQtCombConsolidado($date,$date,'ETANOL', $cidadeCheck,
                                                                                     $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                            $vlrDieselConsolidado1     = buscaValorQtCombConsolidado($date,$date,'DIESEL', $cidadeCheck,
                                                                                     $poloCheck, $equipeCheck, $veiculo, $veiculoCheck, $postoCheck)['VALOR_COMBUSTIVEL'];

                            $arrayVlrDiesel[]          = verificaVazioPegaVlrAnterior($arrayVlrDiesel,$vlrDieselConsolidado1);  
                            $arrayVlrGasolina[]        = verificaVazioPegaVlrAnterior($arrayVlrGasolina,$vlrGasolinaConsolidado1);  
                            $arrayVlrEtanol[]          = verificaVazioPegaVlrAnterior($arrayVlrEtanol,$vlrEtanolConsolidado1);

                            $dataFormatada= formataData($date);  
                            $arrayData[] = "'$dataFormatada'"; 

                            $date = date ("Y/m/d", strtotime("+1 day", strtotime($date)));  
                        } ?>
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
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> EVOLUÇÃO DIARIA PREÇO MÉDIO COMBUSTÍVEL
                                    </div>

                                    <div class="card-body">

                                        <canvas id="grafico2"></canvas>


                                    </div>
                                    <div class="card-footer small text-muted"></div>
                                </div>
                            </div>
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
                                             "<?php echo extrairMesAnoPorExtenso($dti30);?>"],
                                    datasets: [{
                                        label: 'DIESEL',
                                        data: [<?php echo $vlrDieselConsolidado90Dias;?>, 
                                               <?php echo $vlrDieselConsolidado60Dias;?>, 
                                               <?php echo $vlrDieselConsolidado30Dias;?>
                                              ],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: ['rgba(96,167,0,0.9)'],
                                        borderColor: 'rgba(96,167,0,0.9)',
                                    }, {
                                        label: 'GASOLINA',
                                        data: [ <?php echo $vlrGasolinaConsolidado90Dias;?>, 
                                               <?php echo $vlrGasolinaConsolidado60Dias;?>, 
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
                                            // ctx.font = "bold 11px Calibri";
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    data2 = data.toPrecision(3);
                                                    ctx.fillText(data2, bar._model.x - 5, bar._model.y - 15);
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
                                type: 'line',
                                borderWidth: 20,
                                data: {
                                    labels: [ <?php echo implode(',', $arrayData); ?>],
                                    datasets: [{
                                        label: 'DIESEL',
                                        data: [ <?php echo implode(',', $arrayVlrDiesel); ?>],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: ['rgba(96,167,0,0.9)'],
                                        borderColor: 'rgba(96,167,0,0.9)',
                                    }, {
                                        label: 'GASOLINA',
                                        data: [ <?php echo implode(',', $arrayVlrGasolina) ?>],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(255,167,0,0.9)',
                                        borderColor: ['rgba(255,167,0,0.9)'],
                                    }, {
                                        label: 'ETANOL',
                                        data: [ <?php echo implode(',', $arrayVlrEtanol) ?>],
                                        responsive: true,
                                        fill: false,
                                        backgroundColor: 'rgba(78,149,212,0.9)',
                                        borderColor: 'rgba(78,149,212,0.9)',
                                    }]
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
                                            //ctx.font = "bold 11px Calibri";
                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                   // data2 = data.toPrecision(3);
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
