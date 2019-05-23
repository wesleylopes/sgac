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

function buscaValorQtCombCidadeConsolidado($dataInicial,$dataFinal,$tipoCombustivel,$cidade){        
   require("conexao.php");           
        
    //$sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
   $sql= "call buscaPrecoQtdCidadeConsolidado('$tipoCombustivel','$dataInicial','$dataFinal','$cidade')";
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
        'TIPO_COMBUSTIVEL_BUSCA' => $quantidade['TIPO_COMBUSTIVEL_BUSCA'],
        'QUANTIDADE_LITROS'      => $quantidade['QUANTIDADE_LITROS'],
        'CIDADE'                 => $quantidade['CIDADE']
          
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
