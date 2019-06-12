<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){ 
    ini_set('max_execution_time', 0); //teste

    $dti   = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf   = $_POST['dataf'];  // Captura data Final Formulário

    $cidadeCheck='';
    $poloCheck='';
    $equipeCheck='';
    $veiculo='';
    $veiculoCheck='';
    $postoCheck=''; 
    $tipoCombustivelCheck='';
    $tipoVeiculoCheck='';
    $modeloVeiculoCheck ='';
    $arrayEquipeConsolidado = array();
    $arrayVeiculoConsolidado = array();
    $sql2 ='';

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
                                    <li class="breadcrumb-item">Gestão Visual</li>
                                    <li class="breadcrumb-item active">Informações Equipe x Veiculos</li>
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
                            <?php require_once ("front-end/form-pesquisa-padrao.php"); ?>
                        </div>

                        <?php

                            if (isset($_POST["cidade"])){ 
                                $cidadeCheck = implode("','", $_POST["cidade"]);
                                $sql2.="AND CIDADE IN ('$cidadeCheck')";

                            }if (isset($_POST["polo"])){
                                $poloCheck = implode("','", $_POST["polo"]);
                                $sql2.="AND CENTRO_RESULTADO IN('$poloCheck')";

                            }if (isset($_POST["equipe"])){
                                $equipeCheck = implode("','", $_POST["equipe"]);
                                $sql2.="AND CENTRO_CUSTO IN ('$equipeCheck')";

                            }if (isset($_POST["veiculo"])){
                                $veiculoCheck  = implode("','", $_POST["veiculo"]);
                                $sql2.="AND PLACA_VEICULO IN('$veiculoCheck')"; 
                            }if (isset($_POST["posto"])){
                                $postoCheck  = implode("','", $_POST["posto"]);
                                $sql2.="AND NOME_POSTO IN('$postoCheck')";
                            }
                            if (isset($_POST["tpCombustivel"])){
                                $tipoCombustivelCheck  = implode("','", $_POST["tpCombustivel"]);
                                $sql2.="AND PRODUTO IN('$tipoCombustivelCheck')";
                            }
                            if (isset($_POST["tpVeiculo"])){
                                $tipoVeiculoCheck  = implode("','", $_POST["tpVeiculo"]);

                                $sql2.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";
                            }

                            if (isset($_POST["modeloVeiculo"])){
                                $modeloVeiculoCheck  = implode("','", $_POST["modeloVeiculo"]);

                                $sql2.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
                            }

                            $sql="select distinct(a.CENTRO_CUSTO) AS CENTRO_CUSTO from movimento_veiculos a where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'"; 

                            $sql .=$sql2;


                            $sql = $db->query($sql);            
                            $registros = $sql->fetchAll();       

                            foreach ($registros as $registro){
                                $equipe =  $registro['CENTRO_CUSTO']; 

                                $arrayEquipeConsolidado[] = buscaInformacoesEquipeConsolidado($dti, $dtf, $cidadeCheck, $poloCheck, $equipe, $equipeCheck, $veiculoCheck, $postoCheck, $tipoCombustivelCheck, $tipoVeiculoCheck, $modeloVeiculoCheck);  

                            }

                            $sql1 ="SELECT  distinct (PLACA_VEICULO) as PLACA from movimento_veiculos A where DATE(A.DATA_MOVIMENTO) between '$dti' and '$dtf'and a.MODELO_VEICULO NOT like '%SERRA%'"; 

                            $sql1 .= $sql2;

                            $sql1 = $db->query($sql1);            
                            $veiculos = $sql1->fetchAll();    

                            function myFilter($var){
                                return ($var !== NULL && $var !== FALSE && $var !== ''&& $var <=0 );
                            }     

                            foreach ($veiculos as $veiculo){
                                $v_veiculo =  $veiculo['PLACA']; 

                                $arrayVeiculoConsolidado[] = buscaInformacoesVeiculoConsolidado($dti, $dtf, $cidadeCheck, $poloCheck, $equipeCheck, $v_veiculo, $veiculoCheck, $postoCheck, $tipoCombustivelCheck,$tipoVeiculoCheck, $modeloVeiculoCheck);  

                            }
                            ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card-header">
                                <h2> <i class="fa fa-filter"><span class="badge badge-success"> &nbsp;EQUIPES</span></i> </h2>
                            </div>
                            <br>
                        </div>

                        <div class="row view-equipe">
                            <?php foreach ($arrayEquipeConsolidado as $Equipe){
    if (count($arrayEquipeConsolidado)==1){
        echo "<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-12'>";
    }
    else{                        
        echo "<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3'>";
    }  
                                ?>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> <?php echo $Equipe['EQUIPE']?>
                                    <h8><span class="badge badge-success"></span></h8>
                                </div>
                                <div class="card-body">
                                    <h10><strong class="card-text"> VALOR ABASTECIDO R$:</strong></h10>
                                    <h8><span><?php echo $Equipe['SOMA_VALOR_COMBUSTIVEL']?></span></h8>
                                    <br>
                                    <h8><Strong class="card-text"> QUANTIDADE :</Strong></h8>
                                    <h8><span><?php echo $Equipe['QUANTIDADE_LITROS']?></span></h8>
                                    <br>
                                    <h8><Strong class="card-text"> KM POR LITRO :</Strong></h8>
                                    <h8><span><?php echo $Equipe['KM_LITRO']?></span></h8>
                                    <br>
                                    <h8><Strong class="card-text"> CUSTO MEDIO DO KM R$:</Strong></h8>
                                    <h8><span><?php echo $Equipe['CUSTO_COMBUSTIVEL_KM']?></span></h8>
                                    <br>
                                    <h8><Strong class="card-text"> VEICULOS C/ MOVIMENTO:</Strong></h8>
                                    <h8><span><?php echo $Equipe['QTD_VEICULOS']?></span></h8>
                                    <br>
                                </div>
                                <div class="card-footer small text-muted"></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card-header">
                            <h2> <i class="fa fa-filter"><span class="badge badge-primary"> &nbsp;VEICULOS</span></i></h2>
                        </div>
                        <br>
                    </div>
                    <div class="row view-veiulo">

                        <?php foreach ($arrayVeiculoConsolidado as $Veiculo1){ 
    if (count($arrayVeiculoConsolidado)==1){
        echo "<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-12'>";
    }else{                        
        echo "<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3'>";
    }  
                            ?>

                        <div class="card-header">
                            <i class="fa fa-table"></i> <?php echo $Veiculo1['PLACA']?>

                            <?php if($Veiculo1['STATUS_RENDIMENTO']=='OK'){
                                echo"<h8><span class='badge badge-success data-toggle='tooltip' data-placement='top' title='Veículo rodando dentro da normalidade'><i class='fa fa-check'></i> </span></h8>"; 

                            }else{
                                echo"<h8><span class='badge badge-danger data-toggle='tooltip' data-placement='top' title='O Veículo possui Inconsistências - rendimento fora do padrão'><i class='fa fa-warning'></i> </span></h8>"; 
                            }
                                ?>
                        </div>

                        <div class="card-body">
                            <h10><strong class="card-text"> VALOR ABASTECIDO :</strong></h10>
                            <h8><span><?php echo $Veiculo1['SOMA_VALOR_COMBUSTIVEL']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> QUANTIDADE :</Strong></h8>
                            <h8><span><?php echo $Veiculo1['QUANTIDADE_LITROS']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> KM POR LITRO :</Strong></h8>
                            <h8><span><?php echo $Veiculo1['KM_LITRO']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> KM RODADOS :</Strong></h8>
                            <h8><span><?php echo $Veiculo1['DISTANCIA']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> CUSTO MEDIO R$ DO KM:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['CUSTO_COMBUSTIVEL_KM']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> MARCA:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['MARCA']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> MODELO:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['MODELO']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> RENDIMENTO PADRÃO:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['RENDIMENTO_VEICULO']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> RENDIMENTO 5% TOLERANCIA:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['RENDIMENTO_VEICULO_ACRESCIMO']?></span></h8>
                            <br>
                            <h8><Strong class="card-text"> MOTORISTA:</Strong></h8>
                            <h8><span><?php echo $Veiculo1['MOTORISTA']?></span></h8>
                            <br>

                        </div>
                        <div class="card-footer small text-muted"></div>                        
                        <br>
                    </div>
                    <?php } ?>
                </div>
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
        $(document).ready(function() {
            $('.select2').select2();
        });

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
    <script src="assets/js/funcoes.js"></script>

    <script>
        $(document).ready(function() {
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })
        });

    </script>
    <!-- END Java Script Pagina -->

</body>

</html>
