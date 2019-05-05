 DROP PROCEDURE IF EXISTS  buscaPrecoQtdCidadeConsolidado;
DELIMITER $$
CREATE PROCEDURE buscaPrecoQtdCidadeConsolidado(
   IN p_tipoCombustivel VARCHAR(70)COLLATE utf8_unicode_ci,
   IN p_dataInicial VARCHAR(70)COLLATE utf8_unicode_ci,
   IN p_dataFinal VARCHAR(70)COLLATE utf8_unicode_ci ,
   IN p_cidade VARCHAR(70)COLLATE utf8_unicode_ci )
BEGIN
  DECLARE somaQuantidade FLOAT DEFAULT 0;
  DECLARE peso FLOAT DEFAULT 0;  /* ( quantidade /  somaQuantidade)*/ 
  DECLARE valorCombustivelPeso FLOAT DEFAULT 0; /* ( valor unitário *  Peso )*/
  DECLARE somaValorCombustivel FLOAT DEFAULT 0; /* ( )*/
  DECLARE qtdCombustivel VARCHAR(70) DEFAULT '';
  DECLARE existe_mais_linhas INT DEFAULT 0;
  DECLARE v_centroResultado VARCHAR(70) DEFAULT '';
  DECLARE v_valorUnitario FLOAT DEFAULT 0;
  DECLARE v_quantidade FLOAT DEFAULT 0;
  DECLARE v_data_movimento DATETIME;
  DECLARE v_cidade VARCHAR(70) DEFAULT '';
  DECLARE v_produto VARCHAR(70) DEFAULT '';
  DECLARE p_comb VARCHAR(70) DEFAULT ''; 
  DECLARE cursorMovimentoVeiculos CURSOR FOR SELECT DISTINCT(CIDADE),
								    VALOR_UNITARIO,
                                    QUANTIDADE,
                                    DATA_MOVIMENTO,
                                    CENTRO_RESULTADO,PRODUTO 
							      FROM movimento_veiculos a 
	                              WHERE a.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
								  AND CIDADE like CONCAT('%', p_cidade,'%')
                                  AND a.CENTRO_RESULTADO in( SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						            AND  DATE(a.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal
                                    order by CIDADE;  
                               
    -- Definição da variável de controle de looping do cursor                             
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET existe_mais_linhas=1;
  
     -- Puxando Soma Quantidade para calcular peso 
  SET somaQuantidade = (SELECT SUM(QUANTIDADE) 
					    FROM movimento_veiculos b 
	                    WHERE b.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
                        AND CIDADE like CONCAT('%', p_cidade,'%')
						AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						AND DATE(b.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal); 
-- Abertura do cursor
  OPEN cursorMovimentoVeiculos;

  -- Looping de execução do cursor
  cursorMovimentoVeiculos: LOOP
  FETCH cursorMovimentoVeiculos INTO  v_cidade,
                                      v_valorUnitario,
                                      v_quantidade, 
                                      v_data_movimento, 
                                      v_centroResultado,
                                      v_produto;

  -- Controle de existir mais registros na tabela
  IF existe_mais_linhas = 1 THEN
  LEAVE cursorMovimentoVeiculos;
  END IF;
  
  -- Soma a kilometragem do registro atual com o total acumulado
  SET peso = (v_quantidade / somaQuantidade);
  SET somaValorCombustivel = somaValorCombustivel + (v_valorUnitario * peso) ;

  -- Retorna para a primeira linha do loop
  END LOOP cursorMovimentoVeiculos;
  
  SET qtdCombustivel = IF (ROUND(somaQuantidade)>1000,REPLACE(FORMAT(ROUND(somaQuantidade),0), ',', '.'), CONCAT(".", round(somaQuantidade)));
  -- Setando a variável com o resultado final
  
  IF ROUND(somaValorCombustivel,2) <>0 THEN
  SELECT 
    FORMAT(somaValorCombustivel,2) as VALOR_COMBUSTIVEL, 
	qtdCombustivel as QUANTIDADE_LITROS,
    v_produto AS TIPO_COMBUSTIVEL,
    UPPER( p_tipoCombustivel) AS TIPO_COMBUSTIVEL_BUSCA,
    UPPER( v_cidade) AS CIDADE;
    END IF;
END $$




