<?php

if (!empty($_FILES['excel']['tmp_name'])) {
    move_uploaded_file($_FILES['excel']['tmp_name'], 'xlsupload/cad-veiculos.xls');
    //echo "Arquivo Carregado com Sucesso!";    

    require('funcoes.php');
    require('conexao.php');

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
    $objPHPExcel        = $objReader->load("xlsupload/cad-veiculos.xls");
    $objWorksheet       = $objPHPExcel->getActiveSheet();
    $highestRow         = $objWorksheet->getHighestRow();
    $highestColumn      = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    $contador = 0;

    if (!$objWorksheet->getCellByColumnAndRow(0, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(0, 1)->getValue()=='Placa' &&
        !$objWorksheet->getCellByColumnAndRow(1, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(1, 1)->getValue()=='Número Frota' &&
        !$objWorksheet->getCellByColumnAndRow(2, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(2, 1)->getValue()=='Número Cartão' &&
        !$objWorksheet->getCellByColumnAndRow(3, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(3, 1)->getValue()=='Modelo' &&
        !$objWorksheet->getCellByColumnAndRow(4, 1)->getValue()=='' &&
        $objWorksheet->getCellByColumnAndRow(4, 1)->getValue()=='Tipo de Veículo'){
        
        for ($row = 2; $row <= $highestRow; $row++) {  

            $PLACA_VEICULO            = limpaCPF_CNPJ_PLACA($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $NUMERO_FROTA             = ehVazio($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
            $NUMERO_CARTAO            = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $MODELO_VEICULO           = utf8_decode($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
            $TIPO_VEICULO             = utf8_decode(limpaString($objWorksheet->getCellByColumnAndRow(4, $row)->getValue()));
            $FABRICANTE_VEICULO       = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            $TIPO_FROTA               = utf8_decode($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
            $RESPONSAVEL_FILIAL       = utf8_decode($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
            $FILIAL                   = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
            $CENTRO_RESULTADO         = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
            $CENTRO_CUSTO             = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
            $CIDADE                   = utf8_decode($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
            $STATUS                   = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
            $RENAVAM                  = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
            $CHASSI_NSERIE            = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
            $COMBUSTIVEIS_VEICULO     = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
            $CAPACIDADE_TANQUE        = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
            $ANO_FABRICACAO           = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
            $MODELO_FABRICACAO        = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
            $SITUACAO_VEICULO         = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
            $QUILOMETRAGEM_INICIAL    = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
            $COR_VEICULO              = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
            $VALOR_AQUISICAO          = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
            $CNPJ_LOCADORA            = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
            $RAZAO_SOCIAL_LOCADORA    = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
            $RENDIMENTO_COMBUSTIVEL   = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();

            try {   
                $sql = "DELETE FROM veiculos WHERE 
                            PLACA_VEICULO = '$PLACA_VEICULO'";

                $db->beginTransaction();
                $sql = $db->query($sql);
                $db->commit();
                unset($sql);
                
                $sql = "INSERT INTO veiculos SET 
                            PLACA_VEICULO             = '$PLACA_VEICULO',  
                            NUMERO_FROTA              = '$NUMERO_FROTA',
                            NUMERO_CARTAO            = '$NUMERO_CARTAO', 
                            MODELO_VEICULO           = '$MODELO_VEICULO', 
                            TIPO_VEICULO             = '$TIPO_VEICULO',
                            FABRICANTE_VEICULO       = '$FABRICANTE_VEICULO',
                            TIPO_FROTA               = '$TIPO_FROTA',  
                            RESPONSAVEL_FILIAL       = '$RESPONSAVEL_FILIAL',   
                            FILIAL                   = '$FILIAL',  
                            CENTRO_RESULTADO         = '$CENTRO_RESULTADO',   
                            CENTRO_CUSTO             = '$CENTRO_CUSTO',  
                            CIDADE                   = '$CIDADE',
                            STATUS                   = '$STATUS',        
                            RENAVAM                  = '$RENAVAM',  
                            CHASSI_NSERIE            = '$CHASSI_NSERIE',   
                            COMBUSTIVEIS_VEICULO     = '$COMBUSTIVEIS_VEICULO',  
                            CAPACIDADE_TANQUE        = '$CAPACIDADE_TANQUE',
                            ANO_FABRICACAO           = '$ANO_FABRICACAO', 
                            MODELO_FABRICACAO        = '$MODELO_FABRICACAO',
                            SITUACAO_VEICULO         = '$SITUACAO_VEICULO',  
                            QUILOMETRAGEM_INICIAL    = '$QUILOMETRAGEM_INICIAL', 
                            COR_VEICULO              = '$COR_VEICULO', 
                            VALOR_AQUISICAO          = '$VALOR_AQUISICAO',
                            CNPJ_LOCADORA            = '$CNPJ_LOCADORA',
                            RAZAO_SOCIAL_LOCADORA    = '$RAZAO_SOCIAL_LOCADORA',
                            RENDIMENTO_COMBUSTIVEL   = '$RENDIMENTO_COMBUSTIVEL'";

                $db->beginTransaction();
                $sql = $db->query($sql);
                $db->commit(); 

                unset($sql);
                $contador++;

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
       echo "Arquivo de importação de Cadastro de Veiculos Invalido!";
       echo "<br>";
       echo " <a href='#' onclick= 'javascript:history.back(-1);'> Voltar</a>";
    }

} else {
    echo "Selecione um arquivo para upload";
    echo "<br>";
    echo " <a href='#' onclick= 'javascript:history.back(-1);'> Voltar</a>";

}
?>
