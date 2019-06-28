<?php
function converteDateTimeMysql($dateTime){
    return date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dateTime)));
}

function formataData($dateTime){
    return date("d/m/Y", strtotime( $dateTime));
}

function removeCaracteres($value){
    return preg_replace('/[^A-Za-z0-9-]/', '', $value);
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
 
    /*
    if (isset($_POST['datai'] )==false or isset($_POST['dataf'] )==false){
        $datai = 30;
        $dataf = 0;
        $_POST['datai']= date('Y-m-d',time() - ($datai * 24 * 60 * 60));
        $_POST['dataf']= date('Y-m-d',time() - ($dataf * 24 * 60 * 60));

    }
    */

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

function buscaDataHora(){ 
    date_default_timezone_set('America/Sao_Paulo');        
    return date('d/m/Y H:i:s');
} 

function buscaDataHoraFormatoBD(){ 
    date_default_timezone_set('America/Sao_Paulo');        
    return date('Y-m-d H:i:s');
} 

function verificaAtualizacaoPeriodoDadosSistema($tipoVerificacao=''){
    require("conexao.php");     
    
    if($tipoVerificacao=='consumo'){
        $sql="SELECT 
            date_format( min(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_INICIAL,
            date_format(max(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_FINAL,
            date_format(max(DATA_IMPORTACAO),'%d/%m/%Y') as ULTIMA_IMPORTACAO 
         FROM movimento_veiculos";
        
    }else if($tipoVerificacao=='veiculos'){
        $sql="SELECT 
            date_format( min(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_INICIAL,
            date_format(max(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_FINAL,
            date_format(max(DATA_IMPORTACAO),'%d/%m/%Y') as ULTIMA_IMPORTACAO 
         FROM movimento_veiculos";

    }if($tipoVerificacao=='anomalias'){
        $sql="SELECT 
            date_format( min(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_INICIAL,
            date_format(max(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_FINAL,
            date_format(max(DATA_IMPORTACAO),'%d/%m/%Y') as ULTIMA_IMPORTACAO 
         FROM movimento_veiculos";
        
    }else{ $sql="SELECT 
            date_format( min(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_INICIAL,
            date_format(max(DATA_MOVIMENTO), '%d/%m/%Y') as MOVIMENTO_FINAL,
            date_format(max(DATA_IMPORTACAO),'%d/%m/%Y') as ULTIMA_IMPORTACAO 
         FROM movimento_veiculos";

         }
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

function buscaValorQtCombUnit($unidade,$dataInicial,$dataFinal,$tipoCombustivel){        
    require("conexao.php");


    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    $sql.= "AND b.CENTRO_RESULTADO in('$unidade')";
    
    //$sql.= "AND a.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                     //                          WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";


    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);     

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(CENTRO_RESULTADO)AS CENTRO_RESULTADO,
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,            
            PRODUTO 
		  FROM movimento_veiculos a                                   
	      WHERE a.PRODUTO like '%$tipoCombustivel%'  
        AND DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    $sql.= "AND a.CENTRO_RESULTADO in('$unidade')";
    //$sql.= "AND a.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
           //                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";

    //echo "TESTE2 <br>".$sql."<br>";

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
            'CENTRO_RESULTADO'       => $movimentoVeiculo['CENTRO_RESULTADO'],
            'TIPO_COMBUSTIVEL_BUSCA' => $tipoCombustivel,
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],0,'.','')

        );
    }   
}   

function buscaValorQtCombTot($dataInicial,$dataFinal,$tipoCombustivel){        
    require("conexao.php");


    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";


    // echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);     

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(CENTRO_RESULTADO)AS CENTRO_RESULTADO,
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,            
            PRODUTO 
		  FROM movimento_veiculos a                                   
	      WHERE a.PRODUTO like '%$tipoCombustivel%'  
        AND DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    $sql.= "AND a.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))";

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
            'CENTRO_RESULTADO'       => $movimentoVeiculo['CENTRO_RESULTADO'],
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],0,'.','')

        );
    }   
}     

function buscaValorQtCombConsolidado($dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo, $VeiculoCheck,$posto,$tipoCombustivelCheck,$tipoVeiculoCheck,$modeloVeiculoCheck){        
    require("conexao.php"); 

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculoCheck)&&!$veiculoCheck==''){
        $sql.="AND PLACA_VEICULO IN('$veiculoCheck')"; 

    }if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')"; 

    }if (isset($tipoCombustivelCheck) && !$tipoCombustivelCheck==''){
        $sql.="AND PRODUTO IN('$tipoCombustivelCheck')";

    }if (isset($tipoVeiculoCheck) && !$tipoVeiculoCheck==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";

    }if (isset($modeloVeiculoCheck) && !$$modeloVeiculoCheck==''){

        $sql.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
    }
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);     

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(CENTRO_RESULTADO)AS CENTRO_RESULTADO,
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,            
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

    }if (isset($veiculoCheck)&&!$veiculoCheck==''){
        $sql.="AND PLACA_VEICULO IN('$veiculoCheck')"; 

    }if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";      
    }
    if (isset($tipoCombustivelCheck) && !$tipoCombustivelCheck==''){
        $sql.="AND PRODUTO IN('$tipoCombustivelCheck')";

    }if (isset($tipoVeiculoCheck) && !$tipoVeiculoCheck==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";

    }if (isset($modeloVeiculoCheck) && !$$modeloVeiculoCheck==''){

        $sql.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
    }

    //echo "TESTE2 <br>".$sql."<br>";

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
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],2,'.','')

        );
    }   
} 


function buscaValorQtCombCidadeConsolidado($dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo,$veiculoCheck, $posto,$tipoCombustivelCheck, $tipoVeiculoCheck, $modeloVeiculoCheck){        
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

    }if (isset($veiculoCheck)&&!$veiculoCheck==''){
        $sql.="AND PLACA_VEICULO IN('$veiculoCheck')"; 

    }if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";

    }if (isset($_POST["tpCombustivel"])){
        $tipoCombustivelCheck  = implode("','", $_POST["tpCombustivel"]);        
        $sql.="AND PRODUTO IN('$tipoCombustivelCheck')";

    }if (isset($_POST["tpVeiculo"])){
        $tipoVeiculoCheck  = implode("','", $_POST["tpVeiculo"]);

        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";

    }if (isset($_POST["modeloVeiculo"])){
        $modeloVeiculoCheck  = implode("','", $_POST["modeloVeiculo"]);        
        $sql.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
    }

    // echo "TESTE <br>".$sql."<br>";

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

    }if (isset($_POST["tpCombustivel"])){
        $tipoCombustivelCheck  = implode("','", $_POST["tpCombustivel"]);
        $sql.="AND PRODUTO IN('$tipoCombustivelCheck')";

    }if (isset($_POST["tpVeiculo"])){
        $tipoVeiculoCheck  = implode("','", $_POST["tpVeiculo"]);
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculoCheck')))";

    }if (isset($_POST["modeloVeiculo"])){
        $modeloVeiculoCheck  = implode("','", $_POST["modeloVeiculo"]);
        $sql.="AND MODELO_VEICULO IN('$modeloVeiculoCheck')";
    }
    // echo "TESTE2 <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $movimentoVeiculos = $sql->fetchAll();

    $somaValorCombustivel=0; 
    // Soma a kilometragem do registro atual com o total acumulado
    foreach ($movimentoVeiculos as $movimentoVeiculo){  
        $peso = ($movimentoVeiculo['QUANTIDADE'] / $quantidadetotal);
        $somaValorCombustivel = $somaValorCombustivel + ($movimentoVeiculo['VALOR_UNITARIO'] * $peso) ;

    }

    if  (number_format($somaValorCombustivel,2)<=>0){
        return array(
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2),
            'TIPO_COMBUSTIVEL_BUSCA' => $tipoCombustivel,
            'QUANTIDADE_LITROS'      => $quantidadetotal,
            'CIDADE'                 => $cidade

        );
    }       
} 

function buscaValorQtCombPostoConsolidado($dataInicial,$dataFinal,$tipoCombustivel,$cidade,$polo,$equipe,$veiculo,$posto,$tipoCombustivelCheck, $tipoVeiculo,$modeloVeiculo){        
    require("conexao.php"); 
    $quantidadetotal = 0 ;
    $peso = 0;
    // Coleta quantidade Total de Combustivel Abatecido de Acordo com os Filtros 

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE,CIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";

    }if (isset($tipoCombustivelCheck) && !$tipoCombustivelCheck==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivelCheck')";

    }if (isset($modeloVeiculo) && !$modeloVeiculo==''){
        $sql.= "AND MODELO_VEICULO IN ('$modeloVeiculo')";

    }if (isset($tipoVeiculo) && !$tipoVeiculo==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculo')))";
    }
    // echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
        $cidade1 = $somaQuantidade['CIDADE'];
    }

    unset($sql);

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(NOME_POSTO),
            VALOR_UNITARIO,
          QUANTIDADE as QUANTIDADE,
            DATA_MOVIMENTO,
            CENTRO_RESULTADO,
            PRODUTO,
            CIDADE 
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
        $peso = ( $movimentoVeiculo['QUANTIDADE'] / $quantidadetotal);
        $somaValorCombustivel = $somaValorCombustivel + ($movimentoVeiculo['VALOR_UNITARIO'] * $peso) ;

    }
    if  (number_format($somaValorCombustivel,2)<=>0){
        return array(
            'NOME_POSTO' =>  $posto,
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2), 
            'TIPO_COMBUSTIVEL_BUSCA' => $tipoCombustivel,
            'QUANTIDADE_LITROS'      => $somaQuantidade['SOMA_QUANTIDADE'],
            'CIDADE'                 => $cidade1

        );
    }   
} 

function buscaInformacoesEquipeConsolidado($dataInicial, $dataFinal, $cidade, $polo, $equipe,$equipecheck , $veiculo, $posto,$tipoCombustivel){ 
    require("conexao.php");   

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";

    }if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){
        $sql.="AND CENTRO_CUSTO IN ('$equipe')";


    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')";


    }if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";

    }if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";

    }


    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);                                                                                                                   


    $sql= "SELECT SUM(DISTANCIA_PERCORIDA) as SOMA_DISTANCIA,
            SUM(VALOR_TOTAL)  as SOMA_VALOR, COUNT(*) AS VEICULOS_MOVIMENTO
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){
        $sql.="AND CENTRO_CUSTO IN ('$equipe')";


    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')";

    }

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";  

    }

    if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";

    }

    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaDistancia){
        $distanciaTotal = $somaDistancia['SOMA_DISTANCIA'];
        $somaValorTotal = $somaDistancia['SOMA_VALOR'];
        $quantidadeVeiculos = $somaDistancia['VEICULOS_MOVIMENTO']; //Busca qtd de Veiculos que possuem movimento de abastecimento
    }     

    unset($sql); 
    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(CENTRO_CUSTO),
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,
            CENTRO_RESULTADO,
            PRODUTO 
		  FROM movimento_veiculos a                                   
	      WHERE DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

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

    if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";
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

    $kmLitro = ( $distanciaTotal / $quantidadetotal );

    $custoCombustivelKm = ($somaValorCombustivel / $kmLitro) ;


    if  (number_format($somaValorCombustivel,2)<=>0){        
        return array(
            'SOMA_VALOR_COMBUSTIVEL' => number_format($somaValorTotal,2,',','.'),
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2),
            'QUANTIDADE_LITROS'      => number_format($quantidadetotal,2,'.','.'),
            'KM_LITRO'               => number_format($kmLitro,2),
            'EQUIPE'                 => $equipe,
            'QTD_VEICULOS'           => $quantidadeVeiculos,
            'CUSTO_COMBUSTIVEL_KM'   => number_format($custoCombustivelKm, 2,',','')    

        );
    }   
} 

function buscaInformacoesVeiculoConsolidado($dataInicial, $dataFinal, $cidade, $polo, $equipe, $veiculo,$veiculocheck, $posto,$tipoCombustivel, $tipoVeiculo,$modeloVeiculo){        
    require("conexao.php");    

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal' and b.MODELO_VEICULO NOT like '%SERRA%'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 
    }

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";
    }

    if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";
    }

    if (isset($modeloVeiculo) && !$modeloVeiculo==''){
        $sql.= "AND MODELO_VEICULO IN ('$modeloVeiculo')";

    }

    if (isset($tipoVeiculo) && !$tipoVeiculo==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculo')))";
    }

    //echo "TESTE1 <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }

    unset($sql);                                                                                                                   


    $sql= "SELECT SUM(DISTANCIA_PERCORIDA) as SOMA_DISTANCIA,
            SUM(VALOR_TOTAL)  as SOMA_VALOR, COUNT(*) AS VEICULOS_MOVIMENTO,FABRICANTE_VEICULO,
            MODELO_VEICULO,
            MOTORISTA 
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'and b.MODELO_VEICULO NOT like '%SERRA%' ";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";
    }

    if (isset($polo) && !$polo==''){

        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){

        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')";

    }

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";
    }

    if (isset($modeloVeiculo) && !$modeloVeiculo==''){
        $sql.= "AND MODELO_VEICULO IN ('$modeloVeiculo')";

    }

    if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";

    }

    if (isset($tipoVeiculo) && !$tipoVeiculo==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculo')))";
    }
    //echo "TESTE2 <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaDistancia){
        $distanciaTotal = $somaDistancia['SOMA_DISTANCIA'];
        $somaValorTotal = $somaDistancia['SOMA_VALOR'];
        $quantidadeVeiculos = $somaDistancia['VEICULOS_MOVIMENTO'];
        $vMarca = $somaDistancia['FABRICANTE_VEICULO'];
        $vModelo = $somaDistancia['MODELO_VEICULO'];
        $vMotorista = $somaDistancia['MOTORISTA'];

    } 

    unset($sql);                                                                                                                   
    $rendimentoCombustivel=0;

    $sql= "SELECT format(RENDIMENTO_COMBUSTIVEL,2) AS RENDIMENTO_COMBUSTIVEL FROM veiculos WHERE PLACA_VEICULO ='$veiculo'";
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $veiculos = $sql->fetchAll(); 

    foreach ($veiculos as $item ){
        $rendimentoCombustivel = $item['RENDIMENTO_COMBUSTIVEL'];

    }     

    unset($sql); 
    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(PLACA_VEICULO) AS PLACA_VEICULO,
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,
            CENTRO_RESULTADO,
            PRODUTO,
            FABRICANTE_VEICULO,
            MODELO_VEICULO,
            MOTORISTA  
		  FROM movimento_veiculos a                                   
	      WHERE DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'and a.MODELO_VEICULO NOT like '%SERRA%'";

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

    }if (isset($tipoCombustivel) && !$tipoCombustivel==''){
        $sql.= "AND PRODUTO IN ('$tipoCombustivel')";

    }if (isset($modeloVeiculo) && !$modeloVeiculo==''){
        $sql.= "AND MODELO_VEICULO IN ('$modeloVeiculo')";

    }if (isset($tipoVeiculo) && !$tipoVeiculo==''){
        $sql.="AND PLACA_VEICULO in((select PLACA_VEICULO  from veiculos B where B.TIPO_VEICULO IN('$tipoVeiculo')))"; 

    }

    // echo "TESTE 3 <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $movimentoVeiculos = $sql->fetchAll();

    $somaValorCombustivel=0; 
    //Soma a kilometragem do registro atual com o total acumulado
    foreach ($movimentoVeiculos as $movimentoVeiculo){  
        $peso = ($movimentoVeiculo['QUANTIDADE'] / $quantidadetotal);
        $somaValorCombustivel = $somaValorCombustivel + ($movimentoVeiculo['VALOR_UNITARIO'] * $peso) ;        

    }

    $kmLitro = 0;
    $custoCombustivelKm =0;

    $kmLitro = ( $distanciaTotal / $quantidadetotal ); 

    $kmLitroAcrescimo = $rendimentoCombustivel - ($rendimentoCombustivel * 0.05);    


    if ($kmLitroAcrescimo > $kmLitro){
        $statusRendimento ='ER';
    } else {
        $statusRendimento ='OK';        
    }

    if (number_format($somaValorCombustivel,2)>0 && number_format( $kmLitro,2)>0){
        $custoCombustivelKm = ($somaValorCombustivel /$kmLitro);     
    }

    if  (number_format($somaValorCombustivel,2)<=>0){        
        return array(
            'SOMA_VALOR_COMBUSTIVEL' => number_format($somaValorTotal,2,',','.'),
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2),
            'QUANTIDADE_LITROS'      => number_format($quantidadetotal,2,'.','.'),
            'KM_LITRO'               => number_format($kmLitro,2),
            'EQUIPE'                 => $equipe,
            'QTD_VEICULOS'           => $quantidadeVeiculos,
            'CUSTO_COMBUSTIVEL_KM'   => number_format($custoCombustivelKm, 2,',',''),
            'DISTANCIA'              => number_format($distanciaTotal,0,'.','.'),
            'MARCA'                  => $vMarca,
            'MODELO'                 => $vModelo,
            'MOTORISTA'              => $vMotorista,
            'PLACA'                  => $veiculo,
            'STATUS_RENDIMENTO'      => $statusRendimento,
            'RENDIMENTO_VEICULO'     => $rendimentoCombustivel,
            'RENDIMENTO_VEICULO_ACRESCIMO' => number_format($kmLitroAcrescimo,2)
        );
    }   
}     

function buscaAnomalias($dataInicial, $dataFinal, $cidade, $polo, $equipe, $veiculo,$veiculocheck, $posto){        
    require("conexao.php");    

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal' and b.MODELO_VEICULO NOT like '%SERRA%'";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";

    }if (isset($polo) && !$polo==''){
        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){
        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";
    }
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];

    }
    unset($sql);  

    $sql= "SELECT SUM(DISTANCIA_PERCORIDA) as SOMA_DISTANCIA,
            SUM(VALOR_TOTAL)  as SOMA_VALOR, COUNT(*) AS VEICULOS_MOVIMENTO,FABRICANTE_VEICULO,
            MODELO_VEICULO,
            MOTORISTA 
           FROM movimento_veiculos b 
	       WHERE DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'and b.MODELO_VEICULO NOT like '%SERRA%' ";

    if (isset($posto)&&!$posto==''){    
        $sql.="AND NOME_POSTO IN('$posto')";

    }if (isset($polo) && !$polo==''){
        $sql.= "AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                               WHERE CENTRO_RESULTADO IN('$polo'))";

    }if (isset($equipe)&&!$equipe==''){
        $sql.="AND CENTRO_CUSTO IN ('$equipe')";

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')";

    }if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";

    }
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaDistancia){
        $distanciaTotal = $somaDistancia['SOMA_DISTANCIA'];
        $somaValorTotal = $somaDistancia['SOMA_VALOR'];
        $quantidadeVeiculos = $somaDistancia['VEICULOS_MOVIMENTO'];
        $vMarca = $somaDistancia['FABRICANTE_VEICULO'];
        $vModelo = $somaDistancia['MODELO_VEICULO'];
        $vMotorista = $somaDistancia['MOTORISTA'];

    } 

    unset($sql);                                                                                                                   
    $rendimentoCombustivel=0;

    $sql= "SELECT format(RENDIMENTO_COMBUSTIVEL,2) AS RENDIMENTO_COMBUSTIVEL FROM veiculos WHERE PLACA_VEICULO ='$veiculo'";
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $veiculos = $sql->fetchAll(); 

    foreach ($veiculos as $item ){
        $rendimentoCombustivel = $item['RENDIMENTO_COMBUSTIVEL'];
    }     

    unset($sql); 
    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(PLACA_VEICULO) AS PLACA_VEICULO,
            VALOR_UNITARIO,
            QUANTIDADE,
            DATA_MOVIMENTO,
            CENTRO_RESULTADO,
            PRODUTO,
            FABRICANTE_VEICULO,
            MODELO_VEICULO,
            MOTORISTA  
		  FROM movimento_veiculos a                                   
	      WHERE DATE(a.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'and a.MODELO_VEICULO NOT like '%SERRA%'";

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
    $kmLitro = 0;
    $custoCombustivelKm =0;

    $kmLitro = ( $distanciaTotal / $quantidadetotal ); 

    if ($kmLitro < $rendimentoCombustivel){
        $statusRendimento ='ER';
    } else {
        $statusRendimento ='OK';
    }

    if (number_format($somaValorCombustivel,2)>0 && number_format( $kmLitro,2)>0){
        $custoCombustivelKm = ($somaValorCombustivel /$kmLitro);
    }

    if  (number_format($somaValorCombustivel,2)<=>0){        
        return array(
            'SOMA_VALOR_COMBUSTIVEL' => number_format($somaValorTotal,2,',','.'),
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2),
            'QUANTIDADE_LITROS'      => number_format($quantidadetotal,2,'.','.'),
            'KM_LITRO'               => number_format($kmLitro,2),
            'EQUIPE'                 => $equipe,
            'QTD_VEICULOS'           => $quantidadeVeiculos,
            'CUSTO_COMBUSTIVEL_KM'   => number_format($custoCombustivelKm, 2,',',''),
            'DISTANCIA'              => number_format($distanciaTotal,0,'.','.'),
            'MARCA'                  => $vMarca,
            'MODELO'                 => $vModelo,
            'MOTORISTA'              => $vMotorista,
            'PLACA'                  => $veiculo,
            'STATUS_RENDIMENTO'      => $statusRendimento,
            'RENDIMENTO_VEICULO'     => $rendimentoCombustivel
        );
    }   
}     

function buscaValorQtdtransacoes($dataInicial,$dataFinal){        
    require("conexao.php");           

    $sql= "SELECT count(*) AS QTD_TRANSACOES, 
          Sum(valor_total) AS VALOR_TRANSACOES,
          Sum(quantidade) AS QUANTIDADE_LITROS 
    FROM movimento_veiculos a WHERE 
  (a.PRODUTO  not like '%ARLA%')
 and (a.PRODUTO  not like '%MOTOR%')
  and Date(a.data_movimento) BETWEEN '$dataInicial' AND '$dataFinal'";
    //echo $sql; 

    $sql = $db->query($sql);            
    $dadosTransacoes = $sql->fetchAll(); 
    
    foreach ($dadosTransacoes as $itemTransacao){
     $qtdTransacoes= $itemTransacao['QTD_TRANSACOES']; 
        $vlrTransacoes = $itemTransacao['VALOR_TRANSACOES']; 
        $qtdLitros = $itemTransacao['QUANTIDADE_LITROS'];        
     
    }
    
    unset($sql);
    
     $sql= "SELECT count(*) AS QTD_TRANSACOES_OUTROS, 
          Sum(valor_total) AS VALOR_TRANSACOES_OUTROS,
          Sum(quantidade) AS QUANTIDADE_LITROS_OUTROS 
    FROM movimento_veiculos a WHERE 
  (a.PRODUTO  not like '%GASOLINA%')
 and (a.PRODUTO  not like '%ETANOL%')
 and (a.PRODUTO  not like '%DIESEL%')
  and Date(a.data_movimento) BETWEEN '$dataInicial' AND '$dataFinal'";
    //echo $sql; 

    $sql = $db->query($sql);            
    $dadosTransacoesOutros = $sql->fetchAll(); 
   
     
    foreach ($dadosTransacoesOutros as $itemTransacaoOutros){
     $qtdTransacoesOutros= $itemTransacaoOutros['QTD_TRANSACOES_OUTROS']; 
        $vlrTransacoesOutros = $itemTransacaoOutros['VALOR_TRANSACOES_OUTROS']; 
        $qtdLitrosOutros= $itemTransacaoOutros['QUANTIDADE_LITROS_OUTROS'];        
     
    }   
   
        return array(
            'QTD_TRANSACOES'             => $qtdTransacoes,
            'VALOR_TRANSACOES'           => number_format($vlrTransacoes, 2, ',', '.'), 
            'QUANTIDADE_LITROS'          => number_format($qtdLitros,2 ,',' ,'.'),
            'QTD_TRANSACOES_OUTROS'      => $qtdTransacoesOutros,
            'VALOR_TRANSACOES_OUTROS'    => number_format($vlrTransacoesOutros, 2, ',', '.'), 
            'QUANTIDADE_LITROS_OUTROS'   => number_format($qtdLitrosOutros, 2, ',', '.')
            
        );             
   
} 

function buscaQtdMotoristas($dataInicial,$dataFinal){        
    require("conexao.php");    

    $sql= "select count(distinct(MOTORISTA)) as QTD_MOTORISTAS
          from movimento_veiculos a
   WHERE Date(a.data_movimento) BETWEEN '$dataInicial' AND '$dataFinal'";  
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

function buscaQtdVeiculos($dataInicial,$dataFinal){        
    require("conexao.php");   

    $sql= "SELECT COUNT(DISTINCT(PLACA_VEICULO)) AS QTD_VEICULOS_ATIVOS FROM movimento_veiculos a
   WHERE Date(a.data_movimento) BETWEEN '$dataInicial' AND '$dataFinal'";  
    //echo $sql;
    $sql = $db->query($sql); 
    $dados= $sql->fetchAll(); 

    foreach ($dados as $quantidade){        
        $qtdVeiculosAtivos = $quantidade['QTD_VEICULOS_ATIVOS'];
    }

    unset($sql);
    $sql= "select count(*) as QTD_VEICULOS_CADASTRADOS from veiculos a WHERE a.STATUS = 'Ativo' and TIPO_VEICULO <> 'EQUIPAMENTOS' ";     
    //echo $sql;
    $sql = $db->query($sql); 
    $dados= $sql->fetchAll(); 

    foreach ($dados as $quantidade){        
        $qtdVeiculosCadastrados = $quantidade['QTD_VEICULOS_CADASTRADOS'];
    } 

    return array(
        'QTD_VEICULOS_ATIVOS'           => $qtdVeiculosAtivos,
        'QTD_VEICULOS_CADASTRADOS'      => $qtdVeiculosCadastrados,
        'QTD_EQUIPAMENTOS_CADASTRADOS'      => $qtdEquipamentosCadastrados,
        'QTD_EQUIPAMENTOS_ATIVOS'      => $qtdEquipamentosAtivos,
    );   
} 

function buscaCondutoresMaiordespesa($dataInicial,$dataFinal){        
    require("conexao.php");   

    return array(
        'QTD_VEICULOS_ATIVOS'           => $qtdVeiculosAtivos,
        'QTD_VEICULOS_CADASTRADOS'      => $qtdVeiculosCadastrados
    );   
} 

function extrairMesAnoPorExtenso($data){   
    setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8","Portuguese_Brazilian","Portuguese_Brazil");
    date_default_timezone_set('America/Sao_Paulo'); 

    return ucfirst(strftime("%B de %Y", strtotime($data)));     
}

function retornaValorChecado($chave,$array){

    if (in_array(utf8_encode(trim(($chave))),$array)=== true){        
        return "<option selected>".utf8_encode($chave)."</option>";
    }else{
        return "<option >".utf8_encode($chave)."</option>";          
    }    
}

function formataNumero($valor){    
    $valorFormatado = number_format($valor,2,',','.');
    return $valorFormatado;
}

function limpaCPF_CNPJ_PLACA($valor){
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);    
    return $valor;

}

function limpaString($texto) {
    // matriz de entrada
    $listaCaracteres = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','Ã','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

    // matriz de saída
    $substituirPor = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','A','E','I','O','U','n','n','c','C',' ',' ',' ','  ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ' );

    // devolver a string
    return str_replace($listaCaracteres, $substituirPor, $texto);
}
function mostrarMensagem($mensagem){
    echo "<script> alert('test')</script>"; 
}


