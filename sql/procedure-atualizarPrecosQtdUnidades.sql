 DROP PROCEDURE IF EXISTS  atualizarPrecosQtdUnidades;
DELIMITER $$
CREATE PROCEDURE atualizarPrecosQtdUnidades( 
   IN p_unidade  VARCHAR(70) COLLATE utf8_unicode_ci,
   -- IN p_dataInicial DATE ,
   -- IN p_dataFinal DATE, 
   IN p_tipoCombustivel VARCHAR(70)COLLATE utf8_unicode_ci)
BEGIN
  DECLARE somaQuantidade FLOAT DEFAULT 0;
  DECLARE peso FLOAT DEFAULT 0;  /* ( quantidade /  somaQuantidade)*/ 
  DECLARE valorCombustivelPeso FLOAT DEFAULT 0; /* ( valor unitário *  Peso )*/
  DECLARE somaValorCombustivel FLOAT DEFAULT 0; /* ( )*/
  DECLARE existe_mais_linhas INT DEFAULT 0;
  DECLARE v_centroResultado VARCHAR(70) DEFAULT '';
  DECLARE v_valorUnitario FLOAT DEFAULT 0;
  DECLARE v_quantidade FLOAT DEFAULT 0;
  DECLARE v_data_movimento DATETIME;
  DECLARE v_cidade VARCHAR(70) DEFAULT '';
  DECLARE p_comb VARCHAR(70) DEFAULT '';
  
  DECLARE cursorMovimentoVeiculos CURSOR FOR SELECT DISTINCT(CENTRO_RESULTADO),
								    VALOR_UNITARIO,
                                    QUANTIDADE,
                                    DATA_MOVIMENTO,
                                    CIDADE
							      FROM movimento_veiculos a 
	                              WHERE a.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
							        AND a.CENTRO_RESULTADO = p_unidade
						            AND  DATE(a.DATA_MOVIMENTO) between '2019-04-23' and '2019-04-23';  
                               
    -- Definição da variável de controle de looping do cursor                             
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET existe_mais_linhas=1;
  
     -- Puxando Soma Quantidade para calcular peso 
  SET p_comb ='';  
  SET somaQuantidade = (SELECT SUM(QUANTIDADE) 
					    FROM movimento_veiculos b 
	                    WHERE b.PRODUTO like CONCAT('%', p_tipoCombustivel, '%')
						AND b.CENTRO_RESULTADO =p_unidade 
						AND DATE(b.DATA_MOVIMENTO) between '2019-04-23' and '2019-04-23');  

-- Abertura do cursor
  OPEN cursorMovimentoVeiculos;

  -- Looping de execução do cursor
  cursorMovimentoVeiculos: LOOP
  FETCH cursorMovimentoVeiculos INTO  v_centroResultado,
                                      v_valorUnitario,
                                      v_quantidade, 
                                      v_data_movimento, 
                                      v_cidade;

  -- Controle de existir mais registros na tabela
  IF existe_mais_linhas = 1 THEN
  LEAVE cursorMovimentoVeiculos;
  END IF;
  
  -- Soma a kilometragem do registro atual com o total acumulado
  SET peso = (v_quantidade / somaQuantidade);
  SET somaValorCombustivel = somaValorCombustivel + (v_valorUnitario * peso) ;

  -- Retorna para a primeira linha do loop
  END LOOP cursorMovimentoVeiculos;

  -- Setando a variável com o resultado final

SELECT v_centroResultado as CENTRO_RESULTADO ,
round(somaValorCombustivel,2) as VALOR_COMBUSTIVEL_PONDERADO, 
ROUND(somaQuantidade) as SOMA_QUANTIDADE,
v_centroResultado,
v_valorUnitario,
v_quantidade, 
v_data_movimento, 
v_cidade,
p_unidade;
  END $$






