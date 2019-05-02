<?php
require("funcoes.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); 
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

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
    
 
$vlrGasolinaConsolidado30Dias   = buscaValorQtCombConsolidado($dti30,$dtf30,'GASOLINA')['VALOR_COMBUSTIVEL'];

$vlrEtanolConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'ETANOL')['VALOR_COMBUSTIVEL'];

$vlrDieselConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$vlrGasolinaConsolidado60Dias   = buscaValorQtCombConsolidado($dti60,$dtf60,'GASOLINA')['VALOR_COMBUSTIVEL'];
 
$vlrEtanolConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'ETANOL')['VALOR_COMBUSTIVEL'];
  
$vlrDieselConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'DIESEL')['VALOR_COMBUSTIVEL'];
   
$vlrGasolinaConsolidado90Dias   = buscaValorQtCombConsolidado($dti90,$dtf90,'GASOLINA')['VALOR_COMBUSTIVEL'];
  
$vlrEtanolConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'ETANOL')['VALOR_COMBUSTIVEL'];

$vlrDieselConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'DIESEL')['VALOR_COMBUSTIVEL'];
    
?>
    
// Verifica se Houve Movimento no dia se não houver Repete o ultimo Preço de Combustivel abastecido.    
function verificaVazioPegaVlrAnterior($array,$valor){
  if (isset($valor)&& $valor == 0.00){
      return $array[count($array)-1];
  } else{
    return $valor;
  }
}
//De data Inicial á data Final busca valores na base de Dados e Alimenta Arrays de Exibição   
while (strtotime($date) <= strtotime($end_date)) {
  
  $vlrGasolinaConsolidado1   = buscaValorQtCombConsolidado($date,$date,'GASOLINA')['VALOR_COMBUSTIVEL'];
  $vlrEtanolConsolidado1     = buscaValorQtCombConsolidado($date,$date,'ETANOL')['VALOR_COMBUSTIVEL'];
  $vlrDieselConsolidado1     = buscaValorQtCombConsolidado($date,$date,'DIESEL')['VALOR_COMBUSTIVEL'];
  
  $arrayVlrDiesel[]          = verificaVazioPegaVlrAnterior($arrayVlrDiesel,$vlrDieselConsolidado1);  
  $arrayVlrGasolina[]        = verificaVazioPegaVlrAnterior($arrayVlrGasolina,$vlrGasolinaConsolidado1);  
  $arrayVlrEtanol[]          = verificaVazioPegaVlrAnterior($arrayVlrEtanol,$vlrEtanolConsolidado1);
  
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
        <div id="sidebar-menu">

          <ul>
            <li class="submenu">
              <a class="active" href="index.php"><i class="fa fa-fw fa-tachometer"></i><span> VISÃO GERAL </span> </a>
              <a href="#"><i class="fa fa-filter"></i> <span> FILTROS / PESQUISAR</span> <span class="menu-arrow"></span></a>
              <ul class="list-unstyled">
                <li>
                  <div class="container">
                    <br>
                    <form method="POST">
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
                </li>
              </ul>
            </li>
            <li class="submenu">
              <a class="active2" href="index.php"><i class="fa fa-fw fa-bullhorn"></i><span> PAINEL DE ALERTAS </span><span class="badge badge-danger"> 0 </span> </a>
              <a class="active2" href="index.php"><i class="fa fa-fw fa-bar-chart"></i><span> PREÇO MÉDIO POSTO </span> </a>
              <a class="active2" href="evolucao-preco.php"><i class="fa fa-fw fa-line-chart"></i><span> EVOLUÇÃO DE PREÇO</span> </a>
              <a class="active2" href="index.php"><i class="fa fa-fw fa-file-excel-o"></i><span>IMPORTAR DADOS (EXCEL) </span> </a>
            </li>

            <li class="submenu">
              <a href="#"><i class="fa fa-archive"></i> <span> CADASTROS</span> <span class="menu-arrow"></span></a>
              <ul class="list-unstyled">
                <li><a href="tables-basic.html"><i class="fa fa-fw fa-tachometer"></i>Entradas e Saidas</a></li>
                <a href="#"><i class="fa fa-fw fa-tachometer"></i> <span> Analise por Periodo </span> <span class="menu-arrow"></span></a>
                <ul class="list-unstyled">
                  <li><a href="tables-basic.html">Entradas</a></li>
                </ul>
                <li><a href="tables-datatable.html">Posição de Estoque</a></li>
              </ul>
            </li>




            <li class="submenu">
              <a href="#"><i class="fa fa-wpforms"></i> <span> RELATÓRIOS</span> <span class="menu-arrow"></span></a>
              <ul class="list-unstyled">
                <li><a href="tables-basic.html">RELATÓRIO 1</a></li>
                <a href="#"> <span> Analise por Periodo </span> <span class="menu-arrow"></span></a>
                <ul class="list-unstyled">
                  <li><a href="tables-basic.html">Entradas</a></li>
                </ul>
                <li><a href="tables-datatable.html">Posição de Estoque</a></li>
              </ul>
            </li>

            <li class="submenu">
              <a href="sair.php"><i class="fa fa-sign-out"></i> <span> SAIR</span></a>
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
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-12">
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

          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-table"></i> EVOLUÇÃO MENSAL PREÇO MÉDIO COMBUSTÍVEL (30/60/90 DIAS)
                </div>
                <div class="card-body">
                  <?php /*
 
 echo "<br>"; 
 echo $dti30 = date('Y-m-d',strtotime($dti) - (0 * 24 * 60 * 60));
                  echo "<br>"; 
 echo $dtf30 = date('Y-m-d',strtotime($dtf) - (0 * 24 * 60 * 60));
                  echo "<br>"; 
    
 echo $dti60 = date('Y-m-d',strtotime($dti) - (28 * 24 * 60 * 60));
                  echo "<br>"; 
 echo $dtf60 = date('Y-m-d',strtotime($dtf) - (31 * 24 * 60 * 60));
                  echo "<br>"; 
    
 echo $dti90 = date('Y-m-d',strtotime($dti) - (59 * 24 * 60 * 60));
                  echo "<br>"; 
 echo $dtf90 = date('Y-m-d',strtotime($dtf) - (59 * 24 * 60 * 60));
                  echo "<br>"; 
    
  echo "<br>";    
  echo  $vlrGasolinaConsolidado30Dias  = buscaValorQtCombConsolidado($dti30,$dtf30,'GASOLINA')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrEtanolConsolidado30Dias    = buscaValorQtCombConsolidado($dti30,$dtf30,'ETANOL')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrDieselConsolidado30Dias     = buscaValorQtCombConsolidado($dti30,$dtf30,'DIESEL')['VALOR_COMBUSTIVEL'];
  echo "<br>";    
  echo  $vlrGasolinaConsolidado60Dias  = buscaValorQtCombConsolidado($dti60,$dtf60,'GASOLINA')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrEtanolConsolidado60Dias    = buscaValorQtCombConsolidado($dti60,$dtf60,'ETANOL')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrDieselConsolidado60Dias     = buscaValorQtCombConsolidado($dti60,$dtf60,'DIESEL')['VALOR_COMBUSTIVEL'];
  echo "<br>";    
  echo  $vlrGasolinaConsolidado90Dias  = buscaValorQtCombConsolidado($dti90,$dtf90,'GASOLINA')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrEtanolConsolidado90Dias    = buscaValorQtCombConsolidado($dti90,$dtf90,'ETANOL')['VALOR_COMBUSTIVEL'];
  echo "<br>";
  echo  $vlrDieselConsolidado90Dias     = buscaValorQtCombConsolidado($dti90,$dtf90,'DIESEL')['VALOR_COMBUSTIVEL'];
  echo "<br>";
             */     
?>
                  <canvas id="grafico1"></canvas>
                 
                </div>
                <div class="card-footer small text-muted"></div>
              </div>
            </div>
            <hr>

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
              labels: ["<?php echo extrairMesAnoPorExtenso($dti90);?>","<?php echo extrairMesAnoPorExtenso($dti60);?>","<?php echo extrairMesAnoPorExtenso($dti30);?>"],
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
                  data: [<?php echo $vlrGasolinaConsolidado90Dias;?>,
                  <?php echo $vlrGasolinaConsolidado60Dias;?>,
                  <?php echo $vlrGasolinaConsolidado30Dias;?>
                  ],
                  responsive: true,
                  fill: false,
                  backgroundColor: 'rgba(255,167,0,0.9)',
                  borderColor: ['rgba(255,167,0,0.9)'],
                  }, {
                  label: 'ETANOL',
                  data: [<?php echo $vlrEtanolConsolidado90Dias;?>,
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

                  <script>
                    var ctx1 = document.getElementById("grafico2").getContext('2d');
                    var barChart = new Chart(ctx1, {
                      type: 'line',
                      borderWidth: 10,
                      data: {
                        labels: [<?php echo implode(',', $arrayData);?>],
                        datasets: [{
                          label: 'DIESEL',
                          data: [<?php echo implode(',',$arrayVlrDiesel);?>],
                          responsive: true,
                          fill: false,
                          backgroundColor: ['rgba(96,167,0,0.9)'],
                          borderColor: 'rgba(96,167,0,0.9)',
                        }, {
                          label: 'GASOLINA',
                          data: [<?php echo implode(',',$arrayVlrGasolina)?>],
                          responsive: true,
                          fill: false,
                          backgroundColor: 'rgba(255,167,0,0.9)',
                          borderColor: ['rgba(255,167,0,0.9)'],
                        }, {
                          label: 'ETANOL',
                          data: [<?php echo implode (',',$arrayVlrEtanol) ?>],
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
          <?php }?>

</body>

</html>
