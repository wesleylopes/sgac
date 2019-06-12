<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){
    ini_set('max_execution_time', 0); 

    $dti         = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf         = $_POST['dataf'];  // Captura data Final Formulário
    $qtdMotoristas = buscaQtdMotoristas($dti,$dtf);

    $qtdVeiculosAtivos = buscaQtdVeiculos($dti,$dtf)['QTD_VEICULOS_ATIVOS'];
    $qtdVeiculosCadastrados = buscaQtdVeiculos($dti,$dtf)['QTD_VEICULOS_CADASTRADOS'];


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
                                <h1 class="main-title float-left">PAGINA DETALHE - <?php echo strtoupper($_GET['busca'])?> </h1>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item">-</li>
                                    <li class="breadcrumb-item active">Analise de Combustivel</li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
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
                        <?php  if(($_GET['busca']) ==='condutores') { 
                                    ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5><i class="fa fa-money"></i> CONDUTORES x DESPESA</h5>
                                    Baseado no total em R$.
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tabela-condutores" class="table table-bordered table-hover display">
                                            <thead>
                                                <tr>
                                                    <th>Motorista</th>
                                                    <th>Cidade</th>
                                                    <th>Tipo Veiculo</th>
                                                    <th>Valor Consumido</th>
                                                    <th>Litros</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
                                                  $sql= "SELECT MOTORISTA,
                                                         B.TIPO_VEICULO as TP_VEICULO,
                                                         A.MODELO_VEICULO as MOD_VEICULO,
                                                         ROUND(SUM(a.VALOR_TOTAL),2) AS VALOR,
                                                         ROUND(SUM(a.QUANTIDADE),2) AS QUANTIDADE,
                                                         A.CENTRO_RESULTADO AS C_RESULTADO FROM 
                                                         sgacbase.movimento_veiculos a  
                                                         INNER JOIN veiculos B ON ( A.PLACA_VEICULO = B.PLACA_VEICULO)
                                                         AND Date(a.data_movimento) BETWEEN '$dti' AND '$dtf'
                                                         GROUP BY A.MOTORISTA
                                                         ORDER BY VALOR DESC";  

                                                         $sql = $db->query($sql); 
                                                         $dados= $sql->fetchAll(); 

                                                         foreach ($dados as $quantidade){  
                                                            ?>
                                                <tr>
                                                    <td><?php echo utf8_encode($quantidade['MOTORISTA']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['C_RESULTADO']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['TP_VEICULO']);?></td>
                                                    <td>R$ <?php echo utf8_encode($quantidade['VALOR']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['QUANTIDADE']);?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div><!-- end card-->
                        </div>

                        <?php }?>

                        <?php  if(($_GET['busca']) ==='abastecimento') { 
                                    ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5><i class="fa fa-money"></i> VEICULO X ABASTECIMENTO</h5>
                                    Baseado no total em R$.
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tabela-abastecimento" class="table table-bordered table-hover display">
                                            <thead>
                                                <tr>
                                                    <th>Placa</th>
                                                    <th>Modelo</th>
                                                    <th>Marca</th>
                                                    <th>Valor Consumido</th>
                                                    <th>Litros</th>
                                                     <th>Data Abastecimento</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
    $sql= "SELECT distinct(b.PLACA_VEICULO) as PLACA,
           A.MODELO_VEICULO MOD_VEICULO,
           A.FABRICANTE_VEICULO AS MARCA,
           A.VALOR_TOTAL AS VALOR,
           A.QUANTIDADE AS QUANTIDADE1,
           date_format(str_to_date(a.data_movimento, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO
         FROM movimento_veiculos a INNER JOIN veiculos B ON ( A.PLACA_VEICULO = B.PLACA_VEICULO) 
         AND Date(a.data_movimento) BETWEEN '$dti' AND '$dtf' ORDER BY a.data_movimento DESC "; 

    $sql = $db->query($sql); 
    $dados= $sql->fetchAll(); 

    foreach ($dados as $quantidade){  
                                                            ?>
                                                <tr>
                                                    <td><?php echo utf8_encode($quantidade['PLACA']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['MOD_VEICULO']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['MARCA']);?></td>
                                                    <td>R$ <?php echo utf8_encode($quantidade['VALOR']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['QUANTIDADE1']);?></td>
                                                     <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                   
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div><!-- end card-->
                        </div>

                        <?php }?>
                        
                         <?php  if(($_GET['busca']) ==='transacoes') { 
                                    ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5><i class="fa fa-money"></i>TRANSAÇÕES</h5>
                                    Baseado no movimento de Abastecimentos com Cartão .
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tabela-abastecimento" class="table table-bordered table-hover display">
                                            <thead>
                                                <tr>
                                                    <th>Placa</th>
                                                    <th>Numero Cartao</th>
                                                    <th>Data </th>
                                                    <th>Matricula</th>
                                                    <th>Tipo Frota</th>
                                                    <th>Modelo</th>
                                                    <th>Marca </th>
                                                    <th>Posto</th>
                                                    <th>Cidade</th>
                                                     <th>Produto</th>
                                                     <th>Distancia</th>
                                                     <th>Consumo</th>
                                                    <th>Unidade</th>
                                                     <th>Hodometro /Horimetro</th>
                                                     <th>Quantidade</th>
                                                     <th>Valor Unitario</th>
                                                    <th>Valor Total</th>
                                                     <th>Polo</th>
                                                     <th>Filial</th>
                                                     <th>Equipe</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
    $sql= " 
select PLACA_VEICULO, 
       NUMERO_CARTAO, 
       DATA_MOVIMENTO, 
       MATRICULA, 
       TIPO_FROTA,
       MODELO_VEICULO,
       FABRICANTE_VEICULO,
       NOME_POSTO,
       CIDADE,
       PRODUTO,
       DISTANCIA_PERCORIDA,
       CONSUMO,
       UNIDADE_MEDIDA,
       HODOMETRO_HORIMETRO, QUANTIDADE, VALOR_UNITARIO, VALOR_TOTAL, CENTRO_RESULTADO, FILIAL, CENTRO_CUSTO
       from movimento_veiculos a where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' "; 
    $sql = $db->query($sql); 
    $dados= $sql->fetchAll(); 

    foreach ($dados as $quantidade){  
                                                            ?>
                                                <tr>
                                                    <td><?php echo utf8_encode($quantidade['PLACA_VEICULO']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['NUMERO_CARTAO']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['NUMERO_CARTAO']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['DATA_MOVIMENTO']);?></td>
                                                    <td>R$ <?php echo utf8_encode($quantidade['MATRICULA']);?></td>
                                                    <td><?php echo utf8_encode($quantidade['QUANTIDADE1']);?></td>
                                                     <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                   
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div><!-- end card-->
                        </div>

                        <?php }?>

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
            var tabelaCondutores = $('#tabela-condutores').DataTable({
                "aaSorting": [3]
            });
            
            var tabelaAbastecimento = $('#tabela-abastecimento').DataTable({
                "aaSorting": [6]
            });
            
            $('.counter').counterUp({
                delay: 300,
                time: 300
            });
        });

    </script>


</body>

</html>
