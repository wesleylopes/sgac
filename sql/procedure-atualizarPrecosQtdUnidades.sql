 DROP PROCEDURE IF EXISTS  buscaPrecoQtdUnidade;
DELIMITER $$
CREATE PROCEDURE buscaPrecoQtdUnidade( 
   IN p_unidade  VARCHAR(70) COLLATE utf8_unicode_ci,
   IN p_tipoCombustivel VARCHAR(70)COLLATE utf8_unicode_ci,
   IN p_dataInicial VARCHAR(70)COLLATE utf8_unicode_ci,
   IN p_dataFinal VARCHAR(70)COLLATE utf8_unicode_ci)
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
  DECLARE cursorMovimentoVeiculos CURSOR FOR SELECT DISTINCT(CENTRO_RESULTADO),
								    VALOR_UNITARIO,
                                    QUANTIDADE,
                                    DATA_MOVIMENTO,
                                    CIDADE,PRODUTO 
							      FROM movimento_veiculos a 
	                              WHERE a.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
                                  AND a.CENTRO_RESULTADO in( p_unidade)
						            AND  DATE(a.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal;  
                               
    -- Definição da variável de controle de looping do cursor                             
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET existe_mais_linhas=1;
  
     -- Puxando Soma Quantidade para calcular peso 
  SET somaQuantidade = (SELECT SUM(QUANTIDADE) 
					    FROM movimento_veiculos b 
	                    WHERE b.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
						AND b.CENTRO_RESULTADO in(p_unidade)
						AND DATE(b.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal);  

-- Abertura do cursor
  OPEN cursorMovimentoVeiculos;

  -- Looping de execução do cursor
  cursorMovimentoVeiculos: LOOP
  FETCH cursorMovimentoVeiculos INTO  v_centroResultado,
                                      v_valorUnitario,
                                      v_quantidade, 
                                      v_data_movimento, 
                                      v_cidade,
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
 
 /*IF p_tipoconsulta=0 THEN
  SET p_comb = 'PASSOU AQUI'; else
   SET p_comb= 'NAO PASSOU AQUI';
  END IF;*/
  
  SELECT UPPER(v_centroResultado) as CENTRO_RESULTADO ,
    FORMAT(somaValorCombustivel,2) as VALOR_COMBUSTIVEL, 
	qtdCombustivel as QUANTIDADE_LITROS,
    v_produto AS TIPO_COMBUSTIVEL,
    UPPER( p_tipoCombustivel) AS TIPO_COMBUSTIVEL_BUSCA, v_cidade AS CIDADE;
END $$






