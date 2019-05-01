<?php
session_start();
require("../conexao.php");
require("../funcoes.php");
header('Content-Type: text/html; charset=utf-8');

unset($_POST['datai']);
unset($_POST['dataif']);


if (isset($_POST['datai'] )==false or isset($_POST['dataf'] )==false){
    $datai = 30;
    $dataf = 0;
    $_POST['datai']= date('Y-m-d',time() - ($datai * 24 * 60 * 60));
    $_POST['dataf']= date('Y-m-d',time() - ($dataf * 24 * 60 * 60));

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
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

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
                <a href="principal.php" class="logo"><img alt="Logo" src="../assets/images/logo.png" /> <span>S.G.A.C</span></a>
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
                            <a title="Clcik to visit Pike Admin Website" target="_blank" href="#" class="dropdown-item notify-item notify-all">
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
                                    <img src="../assets/images/avatars/avatar11.png" alt="img" class="rounded-circle img-fluid">
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
                                    <img src="../assets/images/avatars/avatar4.png" alt="img" class="rounded-circle img-fluid">
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
                            <img src="../assets/images/logo.png" alt="Profile image" class="avatar-rounded">
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
                            <a href="../sair.php" class="dropdown-item notify-item">
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
                        <li class="submenu1">
                            <a class="active2" href="index.php"><i class="fa fa-fw fa-tachometer"></i><span> Dashboard Principal </span> </a>
                            <a class="active2" href="index.php"><i class="fa fa-fw fa-bar-chart"></i><span> Preço Médio por Posto </span> </a>
                            <a class="active2" href="../evolucao-preco.php"><i class="fa fa-fw fa-line-chart"></i><span> Evolução de Preço </span> </a>
                             <a class="active" href="index.php"><i class="fa fa-fw fa-cloud-upload"></i><span>Upload de arquivos (Excel) </span> </a>



                        </li>

                        <div class="clearfix">
                        </div>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="container">



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
                            <h1 class="main-title float-left">Atualizacão da Base de Dados </h1>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item">Analise de Automóveis </li>
                                <li class="breadcrumb-item active">Comparativo </li>
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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-12">
                        <div class="card-footer small text-muted">
                            <?php                   
                    $arrayMensagem = verificaAtualizacaoPeriodoDadosSistema();
                    ?>
                            <br>
                            <span class="text-red"> A base de Dados Possui Registros de
                                <?php echo $arrayMensagem['MOVIMENTO_INICIAL']?> á <?php echo $arrayMensagem['MOVIMENTO_FINAL']?> </span>
                            <br>
                            <span> Ultima importação: <?php echo $arrayMensagem['ULTIMA_IMPORTACAO']?> </span>

                        </div>
                        <br>
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-upload"></i> Selecione 3 Arquivos para upload para atualizar a base de Dados

                                <form class="form-group" method="POST" action="upload.php" enctype="multipart/form-data">
                                    <br />
                                    Arquivo(Excel) Analise de Combustivel:<br><br>
                                    <input type="file" name="excel" /><br /><br />
                                     <br />
                                    
                                    <input type="submit" name="Enviar" /><br />
                                </form>
                                <a href="../principal.php"> Voltar</a>
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
            </div>
        </div>
    </div>

    <!-- END main -->

    <script src="../assets/js/modernizr.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/moment.min.js"></script>

    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script src="../assets/js/detect.js"></script>
    <script src="../assets/js/fastclick.js"></script>
    <script src="../assets/js/jquery.blockUI.js"></script>
    <script src="../assets/js/jquery.nicescroll.js"></script>

    <!-- App js -->
    <script src="../assets/js/pikeadmin.js"></script>

    <!-- BEGIN Java Script for this page -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Counter-Up-->
    <script src="../assets/plugins/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="../assets/plugins/counterup/jquery.counterup.min.js"></script>

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
</body>

</html>
