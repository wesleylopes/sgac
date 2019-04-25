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
    if ($valor >= 1000){
    $resultado = number_format($valor,0,"","");
} else {
        $resultado = round("0".$valor);
        
  }
return $resultado;

}


//echo formataNumero();