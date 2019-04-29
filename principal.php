<?php
session_start();
require("conexao.php");
require("funcoes.php");
header('Content-Type: text/html; charset=utf-8');

if (isset($_POST['datai'] )==false or isset($_POST['dataf'] )==false){
  $_POST['datai']= date('Y-m-d',time() - (31 * 24 * 60 * 60));
  $_POST['dataf']= date('Y-m-d',time() - (1 * 24 * 60 * 60));

}

if (isset($_SESSION['ID'])==false){
   header("location: login.php");
}else{
   echo "Àrea Restrita...";     
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
        <!-- top bar navigation -->
        <div class="headerbar">

            <!-- LOGO -->
            <div class="headerbar-left">
                <a href="principal.php" class="logo"><img alt="Logo" src="assets/images/logo.png" /> <span>S.G.A.C</span></a>
            </div>

            <nav class="navbar-custom">
                <ul class="list-inline float-right mb-0">

                    <li class="list-inline-item dropdown notif">
                        <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fa fa-fw fa-question-circle"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-arrow-success dropdown-lg">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5><small>Ajuda</small></h5>
                            </div>

                            <!-- item-->
                            <a target="_blank" href="https://www.pikeadmin.com" class="dropdown-item notify-item">
                                <p class="notify-details ml-0">
                                    <b>Àrea em Desenvolvimento</b>
                                    <span>...</span>
                                </p>
                            </a>

                            <!-- item-->


                            <!-- All-->
                            <a title="Clcik to visit Pike Admin Website" target="_blank" href="https://www.pikeadmin.com" class="dropdown-item notify-item notify-all">
                                <i class="fa fa-link"></i> ...
                            </a>
                        </div>
                    </li>

                    <li class="list-inline-item dropdown notif">
                        <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fa fa-fw fa-bell-o"></i><span class="notif-bullet"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5><small><span class="label label-danger pull-xs-right">5</span>Alertas</small></h5>
                            </div>

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item">
                                <div class="notify-icon bg-faded">
                                    <img src="assets/images/avatars/avatar11.png" alt="img" class="rounded-circle img-fluid">
                                </div>
                                <p class="notify-details">
                                    <b>Paracatu</b>
                                    <span>Combustível subiu 3%</span>
                                    <small class="text-muted">2 dias atras</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item">
                                <div class="notify-icon bg-faded">
                                    <img src="assets/images/avatars/avatar33.png" alt="img" class="rounded-circle img-fluid">
                                </div>
                                <p class="notify-details">
                                    <b>Unai</b>
                                    <span>Vantagem de preço da Gasolina sobre o Etanol</span>
                                    <small class="text-muted">12 minutos atras</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item">
                                <div class="notify-icon bg-faded">
                                    <img src="assets/images/avatars/avatar4.png" alt="img" class="rounded-circle img-fluid">
                                </div>
                                <p class="notify-details">
                                    <b>Paracatu</b>
                                    <span>Valor Total de Combustivel Consumido</span>
                                    <small class="text-muted">35 minutes ago</small>
                                </p>
                            </a>

                            <!-- All-->
                            <a href="#" class="dropdown-item notify-item notify-all">
                                Visualizar todos Alertas!
                            </a>

                        </div>
                    </li>

                    <li class="list-inline-item dropdown notif">
                        <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="assets/images/avatars/admin.png" alt="Profile image" class="avatar-rounded">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow"><small>Olá, Wesley</small> </h5>
                            </div>

                            <!-- item-->
                            <a href="pro-profile.html" class="dropdown-item notify-item">
                                <i class="fa fa-user"></i> <span>Perfil</span>
                            </a>

                            <!-- item-->
                            <a href="sair.php" class="dropdown-item notify-item">
                                <i class="fa fa-power-off"></i> <span>Sair</span></a>


                        </div>
                    </li>

                </ul>



                <ul class="list-inline menu-left mt-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left mt-2">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                    </li>

                </ul>

            </nav>

        </div>
        <!-- End Navigation -->


        <!-- Left Sidebar -->
        <div class="left main-sidebar">

            <div class="sidebar-inner leftscroll">

                <br>
                <div class="container">

                    <form method="POST" action>
                        <div class="form-group">
                            <label class="label" for="InputDatai">Data Inicial </label>
                            <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                        </div>

                        <div class="form-group">
                            <label class="label" for="InputDataf">Data Final </label>
                            <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">

                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar Graficos</button>


                        <br>
                        <br>
                    </form>

                </div>
                <div id="sidebar-menu">

                    <ul>
                        <li class="submenu">
                            <a class="active" href="index.php"><i class="fa fa-fw fa-tachometer"></i><span> Dashboard Principal </span> </a>
                            <a class="active2" href="index.php"><i class="fa fa-fw fa-bar-chart"></i><span> Preço Médio por Posto </span> </a>
                            <a class="active2" href="evolucao-preco.php"><i class="fa fa-fw fa-line-chart"></i><span> Evolução de Preço </span> </a>
                            <a class="active2" href="index.php"><i class="fa fa-fw fa-cloud-upload"></i><span>Upload de arquivos (Excel) </span> </a>



                        </li>

                        <div class="clearfix">
                        </div>
                    </ul>


                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <!-- End Sidebar -->


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


        <?php
        
function buscaDataHora(){ 
  date_default_timezone_set('America/Sao_Paulo');        
  return date('d/m/Y H:i:s');
}     
        
function verificaAtualizacaoPeriodoDadosSistema(){
   require("conexao.php");           

   $sql="SELECT 
            date_format( min(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_INICIAL,
            date_format(max(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_FINAL,
            date_format(max(DATA_IMPORTACAO),'%d/%m/%Y') as ULTIMA_IMPORTACAO 
         FROM movimento_veiculos";
       /*
         echo $sql;
            echo "<br>";
            echo "<br>";
            echo "<br>";
            */
         $sql = $db->query($sql);            
         $registros = $sql->fetchAll();       
            
    foreach ($registros as $registro){
       return array('MOVIMENTO_INICIAL' => $registro['MOVIMENTO_INICIAL'], 'MOVIMENTO_FINAL' => $registro['MOVIMENTO_FINAL'],'ULTIMA_IMPORTACAO' => $registro['ULTIMA_IMPORTACAO']); 
     }             
   }   
            
function buscaValorCombustivelUnidade($unidade,$dataInicial,$dataFinal,$tipoCombustivel){
require("conexao.php");
        
   $sql="SELECT  
           DISTINCT(CENTRO_RESULTADO),
           VALOR_UNITARIO,
           QUANTIDADE,
           DATA_MOVIMENTO,
           CIDADE,
           (SELECT 
              SUM(QUANTIDADE) 
            FROM movimento_veiculos b 
            where b.PRODUTO like '%$tipoCombustivel%' 
              and B.CENTRO_RESULTADO= A.CENTRO_RESULTADO           
              and DATE(b.DATA_MOVIMENTO)>= '$dataInicial'
              and DATE(b.DATA_MOVIMENTO)<= '$dataFinal' 
           ) as SOMAQTDCOMBUSTIVEL 
         FROM  movimento_veiculos a 
         WHERE a.PRODUTO like '%$tipoCombustivel%'
         AND   a.CENTRO_RESULTADO in ('$unidade')
         AND   DATE(a.DATA_MOVIMENTO) >= '$dataInicial'
         AND   DATE(a.DATA_MOVIMENTO) <= '$dataFinal'";
    
         /* echo $sql;
         echo "<br>";
         echo "<br>";
         echo "<br>"; */
    
         $sql = $db->query($sql); 
         $dados = $sql->fetchAll();
         $row = $sql->rowCount();
      
       if ($sql->rowCount()>0){
         $sql->rowCount();       
       }            
        $array=array();
        $arrayWlancamentos=array();        
       
        $somaPeso=0;  
        
        foreach ($dados as $dado){
            
        $peso =  ($dado['QUANTIDADE'] / $dado['SOMAQTDCOMBUSTIVEL']);     
        $somaPeso = $somaPeso + $peso;
        $valorCombustivelReal = ( $dado['VALOR_UNITARIO'] *$peso ) ;        
           
        $array = array('VALOR_COMBUSTIVEL' => $valorCombustivelReal,
                       'CENTRO_RESULTADO' => $dado['CENTRO_RESULTADO'],
                      'CIDADE' => $dado['CIDADE']);
    
        $arrayWlancamentos[] = $array; 
            
        }  
           
        $sum1 = array_sum(array_column($arrayWlancamentos,'VALOR_COMBUSTIVEL'));
        $title =$sum1 ; 
    
        if (isset ($arrayWlancamentos[0])){
          $unidadePolo = $arrayWlancamentos[0]['CENTRO_RESULTADO'];
          $cidade = $arrayWlancamentos[0]['CIDADE'];
        }else{
          $unidadePolo='';
        }
    
        $valorCombustivel = number_format($sum1, 2);
      
        return array('UNIDADE' => $unidadePolo, 'VALOR' => $valorCombustivel,'CIDADE' => $cidade);
       
        
        }                         
        function buscaQuantidadeCombustivelUnidade($unidade,$dataInicial,$dataFinal,$tipoCombustivel){
        
        require("conexao.php");           
        
        $sql="SELECT CENTRO_RESULTADO,
                  SUM(QUANTIDADE) as SOMAQUANTIDADE
                FROM sgac.movimento_veiculos 
                WHERE PRODUTO like'%$tipoCombustivel%' 
                  AND   DATE(DATA_MOVIMENTO) >= '$dataInicial'
                  AND   DATE(DATA_MOVIMENTO) <= '$dataFinal'                
                  AND CENTRO_RESULTADO in ('$unidade') 
                GROUP BY CENTRO_RESULTADO";         
       
         /*echo $sql;
            echo "<br>";
            echo "<br>";
            echo "<br>";*/
            
         $sql = $db->query($sql);            
         $dados = $sql->fetchAll();       
            
        foreach ($dados as $quantidade){
            return array('UNIDADE' => $quantidade['CENTRO_RESULTADO'], 'QUANTIDADE' => $quantidade['SOMAQUANTIDADE']); 
          }             
        }
                                   
        ?>

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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-table"></i> R$/L - CUSTO MÉDIO POR POLO
                            </div>
                            <div class="card-body">
                                <canvas id="barChart"></canvas>

                                <?php                   
                $dataInicial1 = $_POST['datai'];
                $dataFinal1   = $_POST['dataf'];  

                //Busca Valor Ponderado

                $gasolinaUnai                  = buscaValorCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'GASOLINA');
                $etanolUnai                    = buscaValorCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'ETANOL');
                $dieselUnai                    = buscaValorCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'DIESEL');

                $gasolinaParacatu              = buscaValorCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'GASOLINA');
                $etanolParacatu                = buscaValorCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'ETANOL');
                $dieselParacatu                = buscaValorCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'DIESEL');

                $gasolinaPirapora              = buscaValorCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'GASOLINA');
                $etanolPirapora                = buscaValorCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'ETANOL');
                $dieselPirapora                = buscaValorCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'DIESEL');

                //Busca Quantidade Por Unidade 

                $quantidadeGasolinaParacatu    = buscaQuantidadeCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'GASOLINA');
                $quantidadeEtanolParacatu      = buscaQuantidadeCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'ETANOL');
                $quantidadeDieselParacatu      = buscaQuantidadeCombustivelUnidade('PARACATU',$dataInicial1,$dataFinal1,'DIESEL');

                $quantidadeGasolinaPirapora    = buscaQuantidadeCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'GASOLINA');
                $quantidadeEtanolPirapora      = buscaQuantidadeCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'ETANOL');
                $quantidadeDieselPirapora      = buscaQuantidadeCombustivelUnidade('PIRAPORA',$dataInicial1,$dataFinal1,'DIESEL');

                $quantidadeGasolinaUnai        = buscaQuantidadeCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'GASOLINA');
                $quantidadeEtanolUnai          = buscaQuantidadeCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'ETANOL'); 
                $quantidadeDieselUnai          = buscaQuantidadeCombustivelUnidade('UNAI',$dataInicial1,$dataFinal1,'DIESEL');


                $consolidadoEtanol             = ($etanolUnai['VALOR']+ $etanolParacatu['VALOR'] + $etanolPirapora['VALOR'])/3  ;
                $consolidadoGasolina           = ($gasolinaUnai['VALOR'] + $gasolinaParacatu['VALOR'] +$gasolinaPirapora['VALOR'])/3;
                $consolidadoDiesel             = ($dieselUnai['VALOR'] + $dieselParacatu['VALOR']+$dieselPirapora['VALOR'])/3  ;

                $quantidadeConsolidadaEtanol   = ($quantidadeEtanolUnai['QUANTIDADE']+ $quantidadeEtanolParacatu['QUANTIDADE'] + $quantidadeEtanolPirapora['QUANTIDADE'])/3  ;

                $quantidadeConsolidadaGasolina = ($quantidadeGasolinaUnai['QUANTIDADE'] + $quantidadeGasolinaParacatu['QUANTIDADE'] + $quantidadeGasolinaPirapora['QUANTIDADE'])/3;

                $quantidadeConsolidadaDiesel   = ($quantidadeDieselUnai['QUANTIDADE'] + $quantidadeDieselPirapora['QUANTIDADE'] + $quantidadeDieselParacatu['QUANTIDADE'])  ; 
                
            ?>

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
                                <canvas id="barChart2"></canvas>
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
                                <canvas id="barChart3"></canvas>
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
                                <canvas id="barChart4"></canvas>
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
            < <script src="assets/js/pikeadmin.js">
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
                    var ctx1 = document.getElementById("barChart").getContext('2d');
                    var barChart = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: ["DIESEL", "GASOLINA", "ETANOL"],
                            datasets: [{
                                label: 'UNAI',
                                data: ["<?php echo $dieselUnai['VALOR']?>", "<?php echo $gasolinaUnai['VALOR']?>", "<?php echo $etanolUnai['VALOR']?>"],
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
                                data: [<?php echo $dieselParacatu['VALOR']?>, <?php echo formataNumero($gasolinaParacatu['VALOR'])?>, <?php echo $etanolParacatu['VALOR']?>],
                                data: [<?php echo $dieselParacatu['VALOR']?>, <?php echo formataNumero($gasolinaParacatu['VALOR'])?>, <?php echo $etanolParacatu['VALOR']?>],

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
                                data: [<?php echo $dieselPirapora['VALOR']?>, <?php echo $gasolinaPirapora['VALOR']?>, <?php echo $etanolPirapora['VALOR']?>],
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
                                text: 'REFERENCIA <?php echo $dataInicial1?> A <?php echo $dataFinal1?>'
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
                                        stepSize: 9,
                                        gridLines: {
                                            lineWidth: 0
                                        }

                                    }
                                }]
                            }
                        }
                    });

                    var ctx1 = document.getElementById("barChart2").getContext('2d');
                    var barChart = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: ["DIESEL", "GASOLINA", "ETANOL"],
                            datasets: [{
                                label: '',
                                data: ["<?php echo number_format($consolidadoDiesel,2) ?>",
                                    "<?php echo number_format($consolidadoGasolina,2)?>",
                                    "<?php echo number_format($consolidadoEtanol,2)?>"
                                ],
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
                                text: 'REFERENCIA <?php echo $dataInicial1?> A <?php echo $dataFinal1?>'
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
                                        stepSize: 10
                                    }
                                }]
                            }
                        }
                    });

                    var ctx1 = document.getElementById("barChart3").getContext('2d');
                    var barChart = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: ["DIESEL", "GASOLINA", "ETANOL"],
                            datasets: [{
                                label: 'UNAI',
                                data: ["<?php echo formataNumero($quantidadeDieselUnai['QUANTIDADE'])?>",
                                    "<?php echo formataNumero($quantidadeGasolinaUnai['QUANTIDADE'])?>", "<?php echo formataNumero($quantidadeEtanolUnai['QUANTIDADE'])?>"
                                ],
                                backgroundColor: [
                                    'rgba(251,195,0)',
                                    'rgba(251,195,0)',
                                    'rgba(251,195,0)',

                                ],
                                borderColor: [],
                                borderWidth: 1,
                                responsive: true,
                            }, {
                                label: 'PARACATU',
                                data: ["<?php echo  formataNumero($quantidadeDieselParacatu['QUANTIDADE'])?>", "<?php echo formataNumero($quantidadeGasolinaParacatu['QUANTIDADE'])?>", "<?php echo formataNumero($quantidadeEtanolParacatu['QUANTIDADE'])?>"],

                                backgroundColor: [
                                    'rgba(96,167,0)',
                                    'rgba(96,167,0)',
                                    'rgba(78,A2,13S,1)'
                                ],
                                borderColor: [

                                ],
                                borderWidth: 1,
                            }, {
                                label: 'PIRAPORA',
                                data: [<?php echo formataNumero($quantidadeDieselPirapora['QUANTIDADE'])?>, "<?php echo formataNumero($quantidadeGasolinaPirapora['QUANTIDADE'])?>", "<?php echo formataNumero($quantidadeEtanolPirapora['QUANTIDADE'])?>"],
                                backgroundColor: [
                                    'rgba(78,149,212)',
                                    'rgba(78,149,212)',
                                    'rgba(78,149,212)',
                                ],
                                borderColor: [

                                ]
                            }]
                        },
                        options: {
                            title: {
                                display: false,
                                text: 'REFERENCIA <?php echo $dataInicial1?> A <?php echo $dataFinal1?>'
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

                                            }

                                        );
                                    });
                                },
                                tooltips: {},
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        lineWidth: 0,
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: "white",
                                        fontSize: 11,
                                        stepSize: 0,
                                        gridLines: {
                                            lineWidth: 0
                                        }
                                    }
                                }]
                            }
                        }
                    });

                    var ctx1 = document.getElementById("barChart4").getContext('2d');
                    var barChart = new Chart(ctx1, {
                        type: 'doughnut',
                        data: {
                            labels: ["DIESEL <?php echo formataNumero($quantidadeConsolidadaDiesel)?>", "GASOLINA <?php echo formataNumero($quantidadeConsolidadaGasolina)?>", "ETANOL <?php echo formataNumero($quantidadeConsolidadaEtanol)?>"],
                            datasets: [{
                                label: 'UNAI',
                                data: ["<?php echo formataNumero($quantidadeConsolidadaDiesel)?>", "<?php echo formataNumero($quantidadeConsolidadaGasolina)?>", "<?php echo formataNumero($quantidadeConsolidadaEtanol)?>"],
                                backgroundColor: [
                                    'rgba(251,195,0)',
                                    'rgba(96,167,0)',
                                    'rgba(78,149,212)'
                                ],
                                borderColor: [],
                                borderWidth: 1
                            }, ]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'REFERENCIA <?php echo $dataInicial1?> A <?php echo $dataFinal1?>'
                            },


                            hover: {
                                animationDuration: 1
                            },
                            animation: {
                                duration: 1000,
                                tooltips: {},
                            },

                        }
                    });

                </script>
                <!-- END Java Script for this page -->

</body>

</html>
