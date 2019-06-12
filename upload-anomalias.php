<?php

if (!empty($_FILES['excel']['tmp_name'])) {
    move_uploaded_file($_FILES['excel']['tmp_name'],'xlsupload/mov-anomalias.xls');
    //echo "Arquivo Carregado com Sucesso!";    
    require('conexao.php');
    require('funcoes.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_erros', 1);
    ini_set('max_execution_time', 0); //5 Minutos
    mb_internal_encoding("UTF-8");
    mb_http_output("iso-8859-1");
    ob_start("mb_output_handler");
    header("Content-Type: text/html; charset=ISO-8859-1", true);
    error_reporting(E_ALL);

    include 'Classes/PHPExcel.php';
    include 'Classes/PHPExcel/IOFactory.php';

    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objReader->setReadDataOnly(true);
    $objPHPExcel        = $objReader->load("xlsupload/mov-anomalias.xls");
    $objWorksheet       = $objPHPExcel->getActiveSheet();
    $highestRow         = $objWorksheet->getHighestRow();
    $highestColumn      = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    $contador = 0;
    
    if (!$objWorksheet->getCellByColumnAndRow(18, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(18, 1)->getValue()=='Transação' &&
        !$objWorksheet->getCellByColumnAndRow(19, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(19, 1)->getValue()=='Anomalia'){

    for ($row = 2; $row <= $highestRow; ++$row) {        

        //unset($PLACA_VEICULO, $NUMERO_CARTAO);
        
        $DATA_IMPORTACAO     = buscaDataHoraFormatoBD();
        $DATA_MOVIMENTO      = converteDateTimeMysql($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
        $MOTORISTA           = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();        
        $PLACA_VEICULO        = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
        $MODELO_VEICULO      = utf8_decode($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
        $FABRICANTE_VEICULO  = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();        
        $NOME_POSTO          = utf8_decode($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
        $CIDADE              = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
        $ESTADO              = utf8_decode($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
        $PRODUTO             = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
       
        $DISTANCIA_PERCORIDA = virgulaToPonto($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
        $CONSUMO             = virgulaToPonto($objWorksheet->getCellByColumnAndRow(14, $row)->getValue());
        $UNIDADE_MEDIDA      = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
        $HODOMETRO_HORIMETRO = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
        $QUANTIDADE          = virgulatoPonto($objWorksheet->getCellByColumnAndRow(17, $row)->getValue());
        $VALOR_UNITARIO      = virgulaToPonto($objWorksheet->getCellByColumnAndRow(18, $row)->getValue());
        $VALOR_TOTAL         = virgulaToPonto($objWorksheet->getCellByColumnAndRow(19, $row)->getValue());
        $CUPOM_FISCAL        = ehVazio($objWorksheet->getCellByColumnAndRow(20, $row)->getValue());
        $CENTRO_RESULTADO    = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
        $FILIAL              = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
        $CENTRO_CUSTO        = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
        
        
        

        try {
            $sql = "DELETE FROM movimento_veiculos WHERE 
                            PLACA_VEICULO = '$PLACA_VEICULO'
                       AND NUMERO_CARTAO =  '$NUMERO_CARTAO'
                     AND DATA_MOVIMENTO = '$DATA_MOVIMENTO'";
            

            $db->beginTransaction();
            $sql = $db->query($sql);
            $db->commit();

            unset($sql);

       $contador++;
            $sql = "INSERT INTO anomalia_siag SET 
                        DATA_IMPORTACAO = '$DATA_IMPORTACAO',
                        DATA_MOVIMENTO  = '$DATA_MOVIMENTO',
                        MOTORISTA =  '$MOTORISTA',
                        MATRICULA = '$MATRICULA ',
                        NUMERO_FROTA = '$NUMERO_FROTA',
                        PLACA_VEICULO =  '$PLACA_VEICULO',
                        MODELO_VEICULO =  '$MODELO_VEICULO',
                        FABRICANTE_VEICULO =  '$FABRICANTE_VEICULO',
                        NOME_POSTO =  '$NOME_POSTO',
                        CIDADE =  '$CIDADE',
                        ESTADO =  '$ESTADO',
                        PRODUTO =  '$PRODUTO',
                        DISTANCIA_PERCORIDA  =  '$DISTANCIA_PERCORIDA',
                        CONSUMOL =  '$CONSUMOL',
                        RENDIMENTO_COMBUSTIVEL =  '$RENDIMENTO_COMBUSTIVEL',
                        KM_ANTERIOR =  '$KM_ANTERIOR',
                        HODOMETRO_HORIMETRO =  '$HODOMETRO_HORIMETRO',
                        QUANTIDADE =  '$QUANTIDADE',
                        VALOR_UNITARIO =  '$VALOR_UNITARIO',
                        VALOR_TOTAL =  '$VALOR_TOTAL',
                        TRANSACAO =  '$TRANSACAO',
                        ANOMALIA =  '$ANOMALIA',
                        FILIAL =  '$FILIAL',
                        CENTRO_RESULTADO =  '$CENTRO_RESULTADO',
                        CENTRO_CUSTO =  '$CENTRO_CUSTO',
                        TIPO_FROTA =  '$TIPO_FROTA'";      

            $db->beginTransaction();
            $sql = $db->query($sql);
            $db->commit(); 

            unset($sql);
            

        }
        catch (PDOexception $e) {
            echo "Falhou: " . $e->getMessage();
            $db->rollBack();
        }
    }   

       echo "<br>";
        echo "---------------------------------------------<br>";
        echo " Total de " . ($contador) . " Registros Importados <br>"; 
        echo " <a href='#' onclick= 'javascript:history.back(-1);'> Voltar</a>";

    }else {  
       echo "Arquivo de importação de Histórico de Anomalias Invalido!";
       echo "<br>";
       echo " <a href='#' onclick= 'javascript:history.back(-1);'> Voltar</a>";
    }

} else {
    echo "Selecione um arquivo para upload";
    echo "<br>";
    echo " <a href='#' onclick= 'javascript:history.back(-1);'> Voltar</a>";

}
?>
