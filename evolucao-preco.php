<?php
require("funcoes.php");
if (iniciaSessao()===true){  
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário

//Busca Valores por Unidade  
  
$vlrGasolinaUnai        = buscaValorQtComb('UNAI',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$vlrGasolinaParacatu    = buscaValorQtComb('Paracatu',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$vlrGasolinaPirapora    = buscaValorQtComb('Pirapora',$dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$qtdGasolinaUnai        = buscaValorQtComb('UNAI',$dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselUnai          = buscaValorQtComb('UNAI',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
$qtdGasolinaParacatu    = buscaValorQtComb('Paracatu',$dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselParacatu      = buscaValorQtComb('Paracatu',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
$qtdGasolinaPirapora    = buscaValorQtComb('Pirapora',$dti,$dtf,'GASOLINA') ['QUANTIDADE_LITROS'];
$qtdEtanolPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselPirapora      = buscaValorQtComb('Pirapora',$dti,$dtf,'DIESEL')['QUANTIDADE_LITROS']; 
  
// Busca Valores e Quantidades Consolidadas 
$vlrGasolinaConsolidado   = buscaValorQtCombConsolidado($dti,$dtf,'GASOLINA')['VALOR_COMBUSTIVEL'];
$vlrEtanolConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'ETANOL')['VALOR_COMBUSTIVEL'];
$vlrDieselConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'DIESEL')['VALOR_COMBUSTIVEL'];
  
$qtdGasolinaConsolidado   = buscaValorQtCombConsolidado($dti,$dtf,'GASOLINA')['QUANTIDADE_LITROS'];
$qtdEtanolConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'ETANOL')['QUANTIDADE_LITROS'];
$qtdDieselConsolidado     = buscaValorQtCombConsolidado($dti,$dtf,'DIESEL')['QUANTIDADE_LITROS'];
  
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
                        <li class="submenu1">
                            <a href="sgac/principal.php"><i class="fa fa-fw fa-tachometer"></i><span> Dashboard Principal </span> </a>
                            <a href="../index.php"><i class="fa fa-fw fa-bar-chart"></i><span> Preço Médio por Posto </span> </a>
                            <a class="active" href="index.php"><i class="fa fa-fw fa-line-chart"></i><span> Evolução de Preço </span> </a>
                            <a href="importa/index.php"><i class="fa fa-fw fa-cloud-upload"></i><span>Upload de arquivos (Excel) </span> </a>
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
                
                <br>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-table"></i> EVOLUÇÃO MENSAL PREÇO MÉDIO COMBUSTÍVEL (30/60/90 DIAS)
                            </div>
                            <div class="card-body">
                                 

                           

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
                                <canvas id="barChart"></canvas>

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

            
            <!-- END Java Script for this page -->

</body>

</html>
