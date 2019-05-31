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

function buscaValorQtCombUnit($unidade,$dataInicial,$dataFinal,$tipoCombustivel){        
    require("conexao.php");


    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
           FROM movimento_veiculos b 
	       WHERE b.PRODUTO like '%$tipoCombustivel%'
           AND DATE(b.DATA_MOVIMENTO) between '$dataInicial' and '$dataFinal'";

    $sql.= "AND b.CENTRO_RESULTADO in('$unidade')";


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

function buscaValorQtCombConsolidado($dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo, $posto){        
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

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";
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
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],2,'.','')

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

function buscaValorQtCombPostoConsolidado($dataInicial, $dataFinal, $tipoCombustivel, $cidade, $polo, $equipe, $veiculo, $posto){        
    require("conexao.php");  

    // Coleta quantidade Total de Combustivel Abatecido de Acordo com os Filtros 

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

    }if (isset($veiculo)&&!$veiculo==''){
        $sql.="AND PLACA_VEICULO IN('$veiculo')"; 

    }

    if (isset($cidade) && !$cidade==''){
        $sql.= "AND CIDADE IN ('$cidade')";
    }
    //echo "TESTE <br>".$sql."<br>";

    $sql = $db->query($sql);            
    $somaQuantidades = $sql->fetchAll(); 

    foreach ($somaQuantidades as $somaQuantidade){
        $quantidadetotal = $somaQuantidade['SOMA_QUANTIDADE'];
    }


    unset($sql);

    // Calcula Valor Combustivel por média Ponderada

    $sql= "SELECT DISTINCT(NOME_POSTO),
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
            'NOME_POSTO' =>  $posto,
            'VALOR_COMBUSTIVEL'      => number_format($somaValorCombustivel,2), 
            'TIPO_COMBUSTIVEL_BUSCA' => $tipoCombustivel,
            'QUANTIDADE_LITROS'      => number_format($somaQuantidade['SOMA_QUANTIDADE'],2,'.',''),
            'CIDADE'                 => $cidade

        );
    }   
} 

function buscaInformacoesEquipeConsolidado($dataInicial, $dataFinal, $cidade, $polo, $equipe,$equipecheck , $veiculo, $posto){       
    require("conexao.php");   

    $sql= "SELECT SUM(QUANTIDADE) as SOMA_QUANTIDADE
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
    $custoCombustivelKm = number_format($somaValorCombustivel,2) / number_format( $kmLitro,2) ;


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

function buscaInformacoesVeiculoConsolidado($dataInicial, $dataFinal, $cidade, $polo, $equipe, $veiculo,$veiculocheck, $posto){        
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
