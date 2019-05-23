<?php
require("funcoes.php");
require("conexao.php");
  if (iniciaSessao()===true){ 
  ini_set('max_execution_time', 0); //teste
  
$dti   = $_POST['datai'];  // Captura data Inicial Formulário
$dtf   = $_POST['dataf'];  // Captura data Final Formulário
      
$sql="select distinct(a.CENTRO_CUSTO) AS CENTRO_CUSTO from movimento_veiculos a where a.CENTRO_CUSTO not like '%GO%' AND DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'"; 
  
$sql = $db->query($sql);            
$registros = $sql->fetchAll();       
            
foreach ($registros as $registro){
  $equipe =  $registro['CENTRO_CUSTO']; 
    
$arrayEquipeConsolidado[] = buscaInformacoesEquipeConsolidado($equipe, $dti, $dtf);  
    
}

$sql1="SELECT  distinct (PLACA_VEICULO) as PLACA from movimento_veiculos A where DATE(A.DATA_MOVIMENTO) between '$dti' and '$dtf'"; 
  
$sql1 = $db->query($sql1);            
$veiculos = $sql1->fetchAll();    
      
 function myFilter($var){
  return ($var !== NULL && $var !== FALSE && $var !== ''&& $var <=0 );
}     
            
foreach ($veiculos as $veiculo){
  $v_veiculo =  $veiculo['PLACA']; 
  
  $arrayVeiculoConsolidado[] = buscaInformacoesVeiculoConsolidado($v_veiculo,$dti,$dtf);  
    
   }
    
 echo "<pre>";
 echo "<br>";
   print_r($arrayVeiculoConsolidado);
 echo "</pre>";
 
    echo "<br>";
      echo "<-------------------------------------------------------->";
  echo "<pre>";
 echo "<br>";
   print_r($arrayVeiculoConsolidado);
 echo "</pre>";
       
      
      
      
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
                                    <li class="breadcrumb-item">Gestão Visual</li>
                                    <li class="breadcrumb-item active">Informações Equipe x Veiculos</li>
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

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card-header">
                                <h2> <i class="fa fa-filter"><span class="badge badge-success"> &nbsp;EQUIPES</span></i> </h2>
                            </div>
                            <br>


                        </div>

                        <div class="row view-equipe">
                            <?php foreach ($arrayEquipeConsolidado as $Equipe){?>

                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> <?php echo $Equipe['EQUIPE']?>
                                        <h8><span class="badge badge-success"></span></h8>
                                    </div>
                                    <div class="card-body">
                                        <h10><strong class="card-text"> VALOR ABASTECIDO :</strong></h10>
                                        <h8><span><?php echo $Equipe['SOMA_VALOR_COMBUSTIVEL']?></span></h8>
                                        <br>
                                        <h8><Strong class="card-text"> QUANTIDADE :</Strong></h8>
                                        <h8><span><?php echo $Equipe['QUANTIDADE_LITROS']?></span></h8>
                                        <br>
                                        <h8><Strong class="card-text"> KM POR LITRO :</Strong></h8>
                                        <h8><span><?php echo $Equipe['KM_LITRO']?></span></h8>
                                        <br>
                                        <h8><Strong class="card-text"> CUSTO MEDIO R$ DO KM:</Strong></h8>
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
                            <hr>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card-header">
                               <h2> <i class="fa fa-filter"><span class="badge badge-primary"> &nbsp;VEICULOS</span></i></h2>
                            </div>
                            <br>


                        </div>
                        <div class="row view-veiulo">

                            <?php foreach ($arrayVeiculoConsolidado as $Veiculo1){ ?>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                <div class="card-header">
                                    <i class="fa fa-table"></i> <?php echo $Veiculo1['PLACA']?>
                                    <h8><span class="badge badge-success"></span></h8>
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
                                    <h8><Strong class="card-text"> CUSTO MEDIO R$ DO KM:</Strong></h8>
                                    <h8><span><?php echo $Veiculo1['CUSTO_COMBUSTIVEL_KM']?></span></h8>
                                    <br>
                                    <h8><Strong class="card-text"> MARCA:</Strong></h8>
                                    <h8><span><?php echo $Veiculo1['MARCA']?></span></h8>
                                    <br>
                                     <h8><Strong class="card-text"> MODELO:</Strong></h8>
                                    <h8><span><?php echo $Veiculo1['MODELO']?></span></h8>
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
                        <script src="assets/js/funcoes.js"></script>

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



                        <!-- END Java Script Pagina -->

</body>

</html>
