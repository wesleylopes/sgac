<?php
function converteDateTimeMysql($dateTime){
  return date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dateTime)));
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
    $resultado = round($valor);
} else {
        $resultado = round($valor);
        
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
    // $sql= "call buscaPrecoQtdConsolidado('$tipoCombustivel','$dataInicial','$dataFinal')";
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
        'TIPO_COMBUSTIVEL_BUSCA' => $quantidade['TIPO_COMBUSTIVEL_BUSCA']
          
        );             
      }
    }       


//echo formataNumero();