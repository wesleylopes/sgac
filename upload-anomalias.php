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
        
        $DATA_IMPORTACAO     = buscaDataHoraFormatoBD();
        $DATA_MOVIMENTO      = converteDateTimeMysql($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
        $MOTORISTA           = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();  
        $NUMERO_FROTA        = ehVazio($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
        $PLACA_VEICULO       = limpaCPF_CNPJ_PLACA($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
        $MODELO_VEICULO      = utf8_decode($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
        $FABRICANTE_VEICULO  = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();        
        $NOME_POSTO          = utf8_decode($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
        $CIDADE              = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
        $ESTADO              = utf8_decode($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
        $PRODUTO             = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();       
        $DISTANCIA_PERCORIDA = virgulaToPonto($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
        $CONSUMO             = virgulaToPonto($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
        $RENDIMENTO_COMBUSTIVEL = virgulaToPonto($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
        $KM_ANTERIOR         = limpaCPF_CNPJ_PLACA($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
        $HODOMETRO_HORIMETRO = limpaCPF_CNPJ_PLACA($objWorksheet->getCellByColumnAndRow(14, $row)->getValue());
        $QUANTIDADE          = virgulaToPonto($objWorksheet->getCellByColumnAndRow(15, $row)->getValue());
        $VALOR_UNITARIO      = virgulaToPonto($objWorksheet->getCellByColumnAndRow(16, $row)->getValue());
        $VALOR_TOTAL         = virgulaToPonto($objWorksheet->getCellByColumnAndRow(17, $row)->getValue());
        $TRANSACAO = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
        $ANOMALIA = limpaString($objWorksheet->getCellByColumnAndRow(19, $row)->getValue());
        $FILIAL = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
        $FILIAL_VEICULO = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
        $CENTRO_RESULTADO = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
        $CENTRO_CUSTO = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
        $TIPO_FROTA = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); 
        
        try {
    
            $sql = "DELETE FROM anomalia_siag WHERE 
                            PLACA_VEICULO = '$PLACA_VEICULO'
                     AND DATA_MOVIMENTO = '$DATA_MOVIMENTO'"; 
            
            $sql = $db->exec($sql);
            unset($sql);

            $contador++;
            
            $sql = "INSERT INTO anomalia_siag SET 
                        DATA_IMPORTACAO = '$DATA_IMPORTACAO',
                        DATA_MOVIMENTO  = '$DATA_MOVIMENTO',
                        MOTORISTA =  '$MOTORISTA',
                        NUMERO_FROTA = $NUMERO_FROTA,
                        PLACA_VEICULO =  '$PLACA_VEICULO',
                        MODELO_VEICULO =  '$MODELO_VEICULO',
                        FABRICANTE_VEICULO =  '$FABRICANTE_VEICULO',
                        NOME_POSTO =  '$NOME_POSTO',
                        CIDADE =  '$CIDADE',
                        ESTADO =  '$ESTADO',
                        PRODUTO =  '$PRODUTO',
                        DISTANCIA_PERCORIDA  =  $DISTANCIA_PERCORIDA,
                        CONSUMO =  $CONSUMO,
                        RENDIMENTO_COMBUSTIVEL =  $RENDIMENTO_COMBUSTIVEL,
                        KM_ANTERIOR =  $KM_ANTERIOR,
                        HODOMETRO_HORIMETRO =  $HODOMETRO_HORIMETRO,
                        QUANTIDADE =  $QUANTIDADE,
                        VALOR_UNITARIO =  $VALOR_UNITARIO,
                        VALOR_TOTAL =  $VALOR_TOTAL,
                        TRANSACAO =  '$TRANSACAO',
                        ANOMALIA =  '$ANOMALIA',
                        FILIAL =  '$FILIAL',
                        FILIAL_VEICULO =  '$FILIAL_VEICULO',
                        CENTRO_RESULTADO =  '$CENTRO_RESULTADO',
                        CENTRO_CUSTO =  '$CENTRO_CUSTO',
                        TIPO_FROTA =  '$TIPO_FROTA'";  
            //echo "<br>".$sql."<br>";

            $db->beginTransaction();
            $sql = $db->query($sql);
            $db->commit(); 
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
