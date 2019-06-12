<?php
session_start();
require("conexao.php");
require("funcoes.php");
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
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />
    
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
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
                                <h1 class="main-title float-left">Atualizacão da Base de Dados - Anomalias</h1>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item">Analise de Combustiveis </li>
                                    <li class="breadcrumb-item active">Anomalias</li>
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
                                    <?php $arrayMensagem = verificaAtualizacaoPeriodoDadosSistema(); ?>
                                <br>
                                <span class="text-red"> A base de Dados Possui Registros de
                                    <?php echo $arrayMensagem['MOVIMENTO_INICIAL']?> á <?php echo $arrayMensagem['MOVIMENTO_FINAL']?> </span>
                                <br>
                                <span> Ultima importação: <?php echo $arrayMensagem['ULTIMA_IMPORTACAO']?> </span>

                            </div>
                            <br>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-upload"></i> Selecione o Arquivo (lstAnomalia.xls) para upload e clique no botão "Enviar" para atualizar a base de Dados.

                                    <form class="form-group" method="POST" action="upload-anomalias.php" enctype="multipart/form-data">
                                        <br />
                                        Arquivo(Excel) Cadastro de Veiculo:<br><br>
                                        <input type="file" name="excel" /><br /><br />
                                        <br />

                                        <input type="submit" name="Enviar" /><br />
                                    </form>

                                    <a href="#" onclick="javascript:history.back(-1);"> Voltar</a>
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
    <script src="assets/js/pikeadmin.js"></script>

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
</body>

</html>
