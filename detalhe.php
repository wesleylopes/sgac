<?php
require("funcoes.php");
require("conexao.php");
if (iniciaSessao()===true){
    ini_set('max_execution_time', 0); 


    $_POST['datai']= $_GET['dti'];
    $_POST['dataf']= $_GET['dtf'];

    $dti = $_POST['datai'];  // Captura data Inicial Formulário
    $dtf = $_POST['dataf'];  // Captura data Final Formulário

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

                                <form id ="form-busca" method="POST">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="date" class="form-control" value="<?php echo $dti;?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="date" class="form-control" value="<?php echo $dtf ;?>" id="InputDataf" name="dataf" placeholder="Data final">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <button type="submit" id="btn-atualizar-tabela" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar Registros</button>
                                        </div>
                                    </div>
                                </form>

                                </div>
                            </div>                  

                            <div id="main">





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
                                                <h5><i class="fa fa-exchange"></i> TRANSAÇÕES</h5>
                                                Baseado no movimento de Abastecimentos com Cartão .
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabela-transacoes" class="table table-bordered table-hover display">
                                                        <thead>
                                                            <tr>
                                                                <th>Placa</th>
                                                                <th>Numero Cartao</th>
                                                                <th>Data </th>
                                                                <th>Matricula</th>
                                                                <th>Motorista</th>
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
    if(($_GET['tipoerro'])==='transacoes'){
        $sql= " select PLACA_VEICULO, NUMERO_CARTAO,       
       date_format(str_to_date(DATA_MOVIMENTO, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO,
       MATRICULA,
       MOTORISTA,
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
       from movimento_veiculos a where 
       (a.PRODUTO  not like '%ARLA%')
       and (a.PRODUTO  not like '%MOTOR%')       
       and DATE(DATA_MOVIMENTO) between '$dti' and '$dtf'";

    }else if (($_GET['tipoerro'])==='transacoesoutros'){

        $sql= " select PLACA_VEICULO, NUMERO_CARTAO,       
       date_format(str_to_date(DATA_MOVIMENTO, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO,
       MATRICULA,
       MOTORISTA,
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
       from movimento_veiculos a where
        (a.PRODUTO  not like '%GASOLINA%')
       and (a.PRODUTO  not like '%ETANOL%')
       and (a.PRODUTO  not like '%DIESEL%')    
       and DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' ";

   
    }

    $sql = $db->query($sql); 
    $dados= $sql->fetchAll(); 

    foreach ($dados as $quantidade){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo utf8_encode($quantidade['PLACA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['NUMERO_CARTAO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MATRICULA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MOTORISTA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['TIPO_FROTA']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['MODELO_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['FABRICANTE_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['NOME_POSTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['CIDADE']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['PRODUTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['DISTANCIA_PERCORIDA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['CONSUMO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['UNIDADE_MEDIDA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['HODOMETRO_HORIMETRO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['QUANTIDADE']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['VALOR_UNITARIO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['DISTANCIA_PERCORIDA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['CENTRO_RESULTADO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['FILIAL']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['CENTRO_CUSTO']);?></td>



                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div><!-- end card-->
                                    </div>

                                    <?php }?>

                                    <?php  if(($_GET['busca']) ==='anomalias') { 
    $mensagem = $_GET['mensagem'];
    if(($_GET['tipoerro'])==='anomalia'){

                                    ?>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5><i class="fa fa-exchange"></i> ANOMALIAS - MENSAGEM: <?php echo '"'.strtoupper($mensagem).'"'    ?></h5>
                                                Baseado em erros ao passar o Cartão.
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabela-transacoes" class="table table-bordered table-hover display">
                                                        <thead>
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Motorista</th>                                                    
                                                                <th>Marca </th>
                                                                <th>Modelo</th>
                                                                <th>Placa</th>                                                 
                                                                <th>Posto</th>
                                                                <th>Cidade</th>                                                   
                                                                <th>Polo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php   



                                        $sql= "SELECT
 date_format(str_to_date(DATA_MOVIMENTO, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO,
 MOTORISTA,
 FABRICANTE_VEICULO AS MARCA_VEICULO,
 MODELO_VEICULO,
 PLACA_VEICULO,
 NOME_POSTO,
 CIDADE AS CIDADE_POSTO,
 CENTRO_RESULTADO AS POLO
 FROM anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' and ANOMALIA=";
        $sql.= "'$mensagem'";     

        $sql = $db->query($sql); 
        $dados= $sql->fetchAll(); 

        foreach ($dados as $quantidade){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MOTORISTA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MARCA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MODELO_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['PLACA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['NOME_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['CIDADE_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['POLO']);?></td>

                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div><!-- end card-->
                                    </div>

                                    <?php }else if(($_GET['tipoerro'])==='motorista'){ ?>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5><i class="fa fa-exchange"></i> ANOMALIAS - MOTORISTA: <?php echo '"'.strtoupper($mensagem).'"'    ?></h5>
                                                Baseado em erros ao passar o Cartão.
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabela-transacoes" class="table table-bordered table-hover display">
                                                        <thead>
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Anomalia</th>                                                    
                                                                <th>Marca </th>
                                                                <th>Modelo</th>
                                                                <th>Placa</th>
                                                                <th>KM Anterior</th>
                                                                <th>KM Informado</th>
                                                                <th>Rendimento Combustivel</th>
                                                                <th>Valor Unitario</th>
                                                                <th>Valor Total</th>
                                                                <th>Quantidade</th>                                       
                                                                <th>Posto</th>
                                                                <th>Cidade</th>                                                   
                                                                <th>Polo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php   



        $sql= "SELECT
 date_format(str_to_date(DATA_MOVIMENTO, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO,
 ANOMALIA,
 FABRICANTE_VEICULO AS MARCA_VEICULO,
 MODELO_VEICULO,
 PLACA_VEICULO, 
 KM_ANTERIOR,
 HODOMETRO_HORIMETRO,
 RENDIMENTO_COMBUSTIVEL,
 VALOR_UNITARIO,
 VALOR_TOTAL, 
 QUANTIDADE,
 NOME_POSTO,
 CIDADE AS CIDADE_POSTO,
 CENTRO_RESULTADO AS POLO
 FROM anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' and MOTORISTA=";
                                                                                      $sql.= "'$mensagem'";     
                                                                                      $sql = $db->query($sql); 
                                                                                      $dados= $sql->fetchAll(); 

                                                                                      foreach ($dados as $quantidade){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['ANOMALIA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MARCA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MODELO_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['PLACA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['KM_ANTERIOR']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['HODOMETRO_HORIMETRO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['RENDIMENTO_COMBUSTIVEL']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['VALOR_UNITARIO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['VALOR_TOTAL']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['QUANTIDADE']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['NOME_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['CIDADE_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['POLO']);?></td>

                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div><!-- end card-->
                                    </div>

                                    <?php } else if(($_GET['tipoerro'])==='veiculo') { ?>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5><i class="fa fa-exchange"></i> ANOMALIAS - VEICULO: <?php echo '"'.strtoupper($mensagem).'"'    ?></h5>
                                                Baseado em erros ao passar o Cartão.
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabela-transacoes" class="table table-bordered table-hover display">
                                                        <thead>
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Anomalia</th>                                                    
                                                                <th>Marca </th>
                                                                <th>Modelo</th>                                                               
                                                                <th>KM Anterior</th>
                                                                <th>KM Informado</th>
                                                                <th>Rendimento Combustivel</th>
                                                                <th>Valor Unitario</th>
                                                                <th>Valor Total</th>
                                                                <th>Quantidade</th>                                       
                                                                <th>Posto</th>
                                                                <th>Cidade</th>                                                   
                                                                <th>Polo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php   



        $sql= "SELECT
 date_format(str_to_date(DATA_MOVIMENTO, '%Y-%m-%d %H:%i:%s '), '%d/%m/%Y %H:%i:%s') AS DATA_ABASTECIMENTO,
 ANOMALIA,
 FABRICANTE_VEICULO AS MARCA_VEICULO,
 MODELO_VEICULO,
 PLACA_VEICULO, 
 KM_ANTERIOR,
 HODOMETRO_HORIMETRO,
 RENDIMENTO_COMBUSTIVEL,
 VALOR_UNITARIO,
 VALOR_TOTAL, 
 QUANTIDADE,
 NOME_POSTO,
 CIDADE AS CIDADE_POSTO,
 CENTRO_RESULTADO AS POLO
 FROM anomalia_siag where DATE(DATA_MOVIMENTO) between '$dti' and '$dtf' and PLACA_VEICULO=";
                                                                                      $sql.= "'$mensagem'";     
                                                                                      $sql = $db->query($sql); 
                                                                                      $dados= $sql->fetchAll(); 

                                                                                      foreach ($dados as $quantidade){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo utf8_encode($quantidade['DATA_ABASTECIMENTO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['ANOMALIA']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MARCA_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['MODELO_VEICULO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['KM_ANTERIOR']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['HODOMETRO_HORIMETRO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['RENDIMENTO_COMBUSTIVEL']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['VALOR_UNITARIO']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['VALOR_TOTAL']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['QUANTIDADE']);?></td>
                                                                <td><?php echo utf8_encode($quantidade['NOME_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['CIDADE_POSTO']);?> </td>
                                                                <td><?php echo utf8_encode($quantidade['POLO']);?></td>

                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div><!-- end card-->
                                    </div>

                                    <?php }
}?>


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
                    "aaSorting": [40],
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    }


                }

                                                                        );

                var tabelaAbastecimento = $('#tabela-abastecimento').DataTable({
                    "aaSorting": [6],
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    }
                });

                var tabelaTransacoes = $('#tabela-transacoes').DataTable({
                    "aaSorting": [0],
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    }
                });


                $('.counter').counterUp({
                    delay: 300,
                    time: 300
                });
            });

        </script>

        <script>
            $(document).ready(function () {
                $('#form-busca').delay(5000).fadeOut("slow"){
                    $('#form-busca').submit();
                };

            });


        </script>



    </body>

</html>
