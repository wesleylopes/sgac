<?php
function converteDateTimeMysql($dateTime){
    return date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dateTime)));
}

function formataData($dateTime){
    return date("d/m/Y", strtotime( $dateTime));
}

function iniciaSessao(){
    session_start();
    require("conexao.php");
    header('Content-Type: text/html; charset=utf-8');
    ini_set('display_errors', 1);
    ini_set('display_startup_erros', 1);
    ini_set('max_execution_time', 0); 
    //unset($_POST['datai']);
    //unset($_POST['dataif']);


    if (isset($_POST['datai'] )==false or isset($_POST['dataf'] )==false){
        $datai = 30;
        $dataf = 0;
        $_POST['datai']= date('Y-m-d',time() - ($datai * 24 * 60 * 60));
        $_POST['dataf']= date('Y-m-d',time() - ($dataf * 24 * 60 * 60));

    }

    if (isset($_SESSION['ID'])==false){
        header("location: login.php");
        return false;
    }else{
        echo "Carregando Àrea Restrita..."; 
        return true;

    }
}

function ehVazio($valor){
    if (is_string($valor) and empty($valor) ){
        return 'NULL'; } else 
        if (is_int($valor) and empty($valor) ){
            return 0;
        }
}

function virgulatoPonto($value){
    return str_replace(',', '.', $value);
}

function formataNumero($valor){
    if ($valor > 1000){
        $resultado = $valor;
    } else {
        $resultado = $valor;        
    }
    return $resultado;
}

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

function buscaValorQtComb($unidade,$dataInicial,$dataFinal,$tipoCombustivel){        
    require("conexao.php");           

    $sql= "call buscaPrecoQtdUnidade('$unidade','$tipoCombustivel','$dataInicial','$dataFinal')";   
    /*
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return array(
            'VALOR_COMBUSTIVEL'      => $quantidade['VALOR_COMBUSTIVEL'], 
            'CENTRO_RESULTADO'       => $quantidade['CENTRO_RESULTADO'],
            'TIPO_COMBUSTIVEL_BUSCA' => $quantidade['TIPO_COMBUSTIVEL_BUSCA'],
            'QUANTIDADE_LITROS'      => $quantidade['QUANTIDADE_LITROS']

        );             
    }
}   

function buscaValorQtCombConsolidado($dataInicial,$dataFinal,$tipoCombustivel){        
    require("conexao.php");           

    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    $sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return array(
            'VALOR_COMBUSTIVEL'      => $quantidade['VALOR_COMBUSTIVEL'],
            'TIPO_COMBUSTIVEL_BUSCA' => formataNumero($quantidade['TIPO_COMBUSTIVEL_BUSCA']),
            'QUANTIDADE_LITROS'      => $quantidade['QUANTIDADE_LITROS']

        );             
    }
} 

function buscaValorQtCombCidadeConsolidado($dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo, $posto){        
    require("conexao.php");           

    // Coleta quantidade Total de Combustivel Abatecido de Acordo com os Filtros 

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";


    }if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);     

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(CIDADE),
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,
            CENTRO_RESULTADO,
            PRODUTO 
		  FROM movimento_veiculos a                                   
	      WHERE a.PRODUTO like '%$tipoCombustivel%'  
        AND DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";


    }if (isset($polo) && !$polo==''){

        $sql.= "AND a.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos b 
                                               WHERE b.CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";      
    }
    
   // echo "TESTE2 <br>".$sql."<br>";
    
    $sql = $db->query($sql);            
    $movimentoVeiculos = $sql->fetchAll();

    $somaValorCombustivel=0; 
    //Soma a kilometragem do registro atual com o total acumulado
    foreach ($movimentoVeiculos as $movimentoVeiculo){  
        $peso = ($movimentoVeiculo['QUANTIDADE'] / $quantidadetotal);
        $somaValorCombustivel = $somaValorCombustivel + ($movimentoVeiculo['VALOR_UNITARIO'] * $peso) ;

    }
    if  (number_format($somaValorCombustivel,2)<=>0){
        return array(
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2),
            'TIPO_COMBUSTIVEL_BUSCA' => $tipoCombustivel,
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],2,'.',''),
            'CIDADE'                 => $cidade

        );
    }       
} 

function buscaInformacoesEquipeConsolidado($equipe, $dataInicial, $dataFinal){        
    require("conexao.php");           

    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    $sql= "call buscaInformacoesEquipeConsolidado('$equipe','$dataInicial','$dataFinal')";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $dado){
        return array(
            'SOMA_VALOR_COMBUSTIVEL' => $dado['SOMA_VALOR_COMBUSTIVEL'],
            'VALOR_COMBUSTIVEL'      => $dado['VALOR_COMBUSTIVEL'],
            'QUANTIDADE_LITROS'      => $dado['QUANTIDADE_LITROS'],
            'KM_LITRO'               => $dado['KM_LITRO'],
            'EQUIPE'                 => $dado['EQUIPE'],
            'QTD_VEICULOS'           => $dado['QTD_VEICULOS'],
            'CUSTO_COMBUSTIVEL_KM'   => $dado['CUSTO_COMBUSTIVEL_KM']

        );             
    }
} 

function buscaInformacoesVeiculoConsolidado($veiculo, $dataInicial, $dataFinal){        
    require("conexao.php");           

    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    $sql= "call buscaInformacoesVeiculoConsolidado('$veiculo','$dataInicial','$dataFinal')";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $dado){
        return array(
            'SOMA_VALOR_COMBUSTIVEL' => $dado['SOMA_VALOR_COMBUSTIVEL'],
            'VALOR_COMBUSTIVEL'      => $dado['VALOR_COMBUSTIVEL'],
            'QUANTIDADE_LITROS'      => $dado['QUANTIDADE_LITROS'],
            'KM_LITRO'               => $dado['KM_LITRO'],
            'EQUIPE'                 => $dado['EQUIPE'],
            'MARCA'                  => $dado['MARCA'],
            'MODELO'                 => $dado['MODELO'],
            'MOTORISTA'              => $dado['MOTORISTA'],
            'PLACA'                  => $dado['PLACA'],
            'CUSTO_COMBUSTIVEL_KM'   => $dado['CUSTO_COMBUSTIVEL_KM']

        );             
    }
} 



function buscaValorQtCombPostoConsolidado($dataInicial,$dataFinal,$tipoCombustivel,$posto){        
    require("conexao.php");           

    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    $sql= "call buscaPrecoQtdPostoConsolidado('$tipoCombustivel','$dataInicial','$dataFinal','$posto')";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return array(
            'NOME_POSTO'             => $quantidade['NOME_POSTO'],
            'VALOR_COMBUSTIVEL'      => $quantidade['VALOR_COMBUSTIVEL'],
            'TIPO_COMBUSTIVEL_BUSCA' => $quantidade['TIPO_COMBUSTIVEL_BUSCA'],
            'QUANTIDADE_LITROS'      => $quantidade['QUANTIDADE_LITROS'],
            'CIDADE'                 => $quantidade['CIDADE']

        );             
    }
} 

function buscaValorQtdtransacoes($dataInicial,$dataFinal){        
    require("conexao.php");           

    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
    $sql= "SELECT Format (Count(*),0)         AS QTD_TRANSACOES, 
          Format (Sum(valor_total), 2) AS VALOR_TRANSACOES 
   FROM   movimento_veiculos a 
   WHERE  a.centro_resultado IN(SELECT DISTINCT( centro_resultado ) 
                             FROM   movimento_veiculos a 
                             WHERE  centro_resultado NOT IN( 'PIAUI', 'GOIAS' )) 
       AND Date(a.data_movimento) BETWEEN '$dataInicial' AND '$dataFinal'"; 

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return array(
            'QTD_TRANSACOES'             => $quantidade['QTD_TRANSACOES'],
            'VALOR_TRANSACOES'           => $quantidade['VALOR_TRANSACOES']          
        );             
    }
} 

function buscaQtdMotoristas(){        
    require("conexao.php");    

    $sql= "select count(distinct(MOTORISTA)) as QTD_MOTORISTAS
          from movimento_veiculos a where 
          a.CENTRO_RESULTADO in( SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return  $quantidade['QTD_MOTORISTAS'];          
    }
} 

function buscaQtdVeiculos(){        
    require("conexao.php");    

    $sql= "select count(distinct(PLACA_VEICULO)) as QTD_VEICULOS
          from movimento_veiculos a where 
          a.CENTRO_RESULTADO in( SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";
    /*   
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/

    $sql = $db->query($sql);            
    $dados = $sql->fetchAll(); 

    foreach ($dados as $quantidade){
        return  $quantidade['QTD_VEICULOS'];          
    }
} 

function extrairMesAnoPorExtenso($data){   
    setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8","Portuguese_Brazilian","Portuguese_Brazil");
    date_default_timezone_set('America/Sao_Paulo'); 

    return ucfirst(strftime("%B de %Y", strtotime($data)));                
}
