<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){ 
    ini_set('max_execution_time', 0); 

    $dti   = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf   = $_POST['dataf'];  // Captura data Final Formulário
    
    $cidadeCheck='';
    $poloCheck='';
    $equipeCheck='';
    $veiculo='';
    $veiculoCheck='';
    $postoCheck='';
    $sql1='';
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
                                <h1 class="main-title float-left">Dashboard </h1>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item">Anomalias</li>
                                    <li class="breadcrumb-item active">Transações Negadas</li>
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

                            <div class=" container-fluid card mb-3">
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
                                            <label for="example2">

                                            </label>
                                            <br>
                                            <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php


                                if (isset($_POST["cidade"])){ 
                                    $cidadeCheck = implode("','", $_POST["cidade"]);
                                    $sql1.="AND CIDADE IN ('$cidadeCheck')";

                                }if (isset($_POST["polo"])){
                                    $poloCheck = implode("','", $_POST["polo"]);
                                    $sql1.="AND CENTRO_CUSTO IN('$poloCheck')";

                                }if (isset($_POST["veiculo"])){
                                    $veiculoCheck  = implode("','", $_POST["veiculo"]);
                                    $sql1.="AND PLACA_VEICULO IN('$veiculoCheck')"; 
                                }if (isset($_POST["posto"])){
                                    $postoCheck  = implode("','", $_POST["posto"]);
                                    $sql1.="AND NOME_POSTO IN('$postoCheck')";
                                }

                                $sql="SELECT ANOMALIA, COUNT(ANOMALIA) AS CONTADOR FROM sgacbase.anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' ";
                                
                                $sql .= $sql1;
                                
                                $sql .= ' GROUP BY ANOMALIA ORDER BY CONTADOR DESC';


                                //echo "TESTE3 <br>".$sql."<br>";

                                $sql = $db->query($sql);            
                                $registros = $sql->fetchAll();                               

                                if($sql->rowCount() >0){

                                    foreach ($registros as $registro){ 
                                        if($registro['CONTADOR']>3) {                                        
                                            $arrayAnomalia[]= utf8_encode($registro['ANOMALIA']); 
                                            $arrayContadorAnomalia[]= $registro['CONTADOR'];
                                        } 

                                    } 
                                }  

                                unset($sql);

                                $sql="SELECT MOTORISTA, COUNT(MOTORISTA) AS CONTADOR FROM sgacbase.anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'";
                                $sql .= $sql1;
                                $sql .= ' GROUP BY MOTORISTA ORDER BY CONTADOR DESC';


                                //echo "TESTE3 <br>".$sql."<br>";

                                $sql = $db->query($sql);            
                                $registros = $sql->fetchAll();                               

                                if($sql->rowCount() >0){

                                    foreach ($registros as $registro){   
                                        if($registro['CONTADOR']>3) {
                                            $arrayAnomaliaMotorista[]= utf8_encode($registro['MOTORISTA']); 
                                            $arrayContadorAnomaliaMotorista[]= $registro['CONTADOR'];
                                        }


                                    } 
                                }  

                                unset($sql);


                                $sql="SELECT PLACA_VEICULO, COUNT(PLACA_VEICULO) AS CONTADOR FROM sgacbase.anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' ";
                                $sql .= $sql1;
                                $sql .= ' GROUP BY PLACA_VEICULO ORDER BY CONTADOR DESC';


                                //echo "TESTE3 <br>".$sql."<br>";

                                $sql = $db->query($sql);            
                                $registros = $sql->fetchAll();                               

                                if($sql->rowCount() >0){

                                    foreach ($registros as $registro){   
                                        if($registro['CONTADOR']>3) {
                                            $arrayAnomaliaVeiculo[]= utf8_encode($registro['PLACA_VEICULO']); 
                                            $arrayContadorAnomaliaVeiculo[]= $registro['CONTADOR'];
                                        } 
                                    } 
                                }  

                                ?>

                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <i class="fa fa-table"></i> TIPOS DE TRANSAÇÕES
                                            <h8><span class="badge badge-danger">(NEGADAS)</span></h8>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="grafico1"></canvas>
                                        </div>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>


                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <i class="fa fa-table"></i> CONDUTORES
                                                    <h8><span class="badge badge-danger">(REINCIDENTES)</span></h8>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="grafico2"></canvas>
                                                </div>
                                                <div class="card-footer small text-muted"></div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <i class="fa fa-table"></i> VEICULOS
                                                    <h8><span class="badge badge-danger">(REINCIDENTES)</span></h8>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="grafico3"></canvas>
                                                </div>
                                                <div class="card-footer small text-muted"></div>
                                            </div>
                                        </div>

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

        });
        }

    </script>

    <script>
        var ctx1 = document.getElementById("grafico1").getContext('2d');
        var barChart1 = new Chart(ctx1, {
            type: 'horizontalBar',
            borderWidth: 30,
            data: {
                labels: ["<?php echo implode('", "',$arrayAnomalia);?>"],
                datasets: [{
                    label: 'QUANTIDADE',
                    data: ["<?php echo implode('", "',$arrayContadorAnomalia);?>"],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(255,0,0,0.9)',
                    borderColor: 'rgba(255,140,0,0.9)',
                }],
            },
            options: {
                layout: {
                    padding: {
                        left: 0,
                        right: 50,
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
                        //ctx.font = "70% bold Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                data2 = data;
                                ctx.fillText(data2, bar._model.x + 25, bar._model.y - 5);
                            });
                        });
                    },
                },
            }
        });
    </script>
    
    <script>
    document.getElementById("grafico1").onclick = function(evt){
    var activePoints = barChart1.getElementsAtEvent(evt); 
        
    window.location.href='detalhe.php?busca=anomalias&dti=<?php echo $_POST['datai']?>&dtf=<?php echo $_POST['dataf']?>&tipoerro=anomalia&mensagem='+activePoints[0]._view.label;  
    };
    </script>

    <script>
        var ctx1 = document.getElementById("grafico2").getContext('2d');
        var barChart2 = new Chart(ctx1, {
            type: 'horizontalBar',
            borderWidth: 30,
            data: {
                labels: ["<?php echo implode('", "',$arrayAnomaliaMotorista);?>"],
                datasets: [{
                    label: 'QUANTIDADE',
                    data: ["<?php echo implode('", "',$arrayContadorAnomaliaMotorista);?>"],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(255,0,0,0.9)',
                    borderColor: 'rgba(255,140,0,0.9)',
                }],
            },
            options: {
                layout: {
                    padding: {
                        left: 0,
                        right: 50,
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
                        //ctx.font = "70% bold Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                data2 = data;
                                ctx.fillText(data2, bar._model.x + 25, bar._model.y - 5);
                            });
                        });
                    },
                },
            }
        });

    </script>
    
     <script>
    document.getElementById("grafico2").onclick = function(evt){
    var activePoints = barChart2.getElementsAtEvent(evt); 
    
     window.location.href='detalhe.php?busca=anomalias&dti=<?php echo $_POST['datai']?>&dtf=<?php echo $_POST['dataf']?>&tipoerro=motorista&mensagem='+activePoints[0]._view.label;  
   
   
        
    };
    </script>


    <script>
        var ctx1 = document.getElementById("grafico3").getContext('2d');
        var barChart3 = new Chart(ctx1, {
            type: 'horizontalBar',
            borderWidth: 30,
            data: {
                labels: ["<?php echo implode('", "',$arrayAnomaliaVeiculo);?>"],
                datasets: [{
                    label: 'QUANTIDADE',
                    data: ["<?php echo implode('", "',$arrayContadorAnomaliaVeiculo);?>"],
                    responsive: true,
                    fill: false,
                    backgroundColor: 'rgba(255,0,0,0.9)',
                    borderColor: 'rgba(255,140,0,0.9)',
                }],
            },
            options: {
                layout: {
                    padding: {
                        left: 0,
                        right: 50,
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
                        //ctx.font = "70% bold Calibri";
                        ctx.fillStyle = ['blue', 'red'];
                        ctx.strokeStyle = '#fff000';
                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                data2 = data;
                                ctx.fillText(data2, bar._model.x + 25, bar._model.y - 5);
                            });
                        });
                    },
                },
            }
        });

    </script>
    
     <script>
    document.getElementById("grafico3").onclick = function(evt){
    var activePoints = barChart3.getElementsAtEvent(evt); 
    
     window.location.href='detalhe.php?busca=anomalias&dti=<?php echo $_POST['datai']?>&dtf=<?php echo $_POST['dataf']?>&tipoerro=veiculo&mensagem='+activePoints[0]._view.label; 
   
        
    };
    </script>




    <!-- END Java Script Pagina -->

</body>

</html>
