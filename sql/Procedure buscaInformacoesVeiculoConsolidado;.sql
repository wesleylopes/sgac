DROP PROCEDURE IF EXISTS  buscaInformacoesVeiculoConsolidado;
DELIMITER $$
CREATE PROCEDURE buscaInformacoesVeiculoConsolidado( 
  IN p_veiculo VARCHAR(70)COLLATE utf8_unicode_ci,
  IN p_dataInicial VARCHAR(70)COLLATE utf8_unicode_ci,
  IN p_dataFinal VARCHAR(70)COLLATE utf8_unicode_ci )
  
BEGIN
  DECLARE somaQuantidade FLOAT DEFAULT 0;
  DECLARE somaDistancia FLOAT DEFAULT 0;
  DECLARE somaValorTotal FLOAT DEFAULT 0;
  DECLARE vlrCombustivel FLOAT DEFAULT 0;
  DECLARE peso FLOAT DEFAULT 0;  /* ( quantidade /  somaQuantidade)*/ 
  DECLARE valorCombustivelPeso FLOAT DEFAULT 0; /* ( valor unitário *  Peso )*/
  DECLARE somaValorCombustivel FLOAT DEFAULT 0; /* ( )*/
  DECLARE qtdCombustivel VARCHAR(70) DEFAULT '';
  DECLARE existe_mais_linhas INT DEFAULT 0;
  DECLARE v_centroResultado VARCHAR(70) DEFAULT '';
  DECLARE v_centroCusto VARCHAR(70) DEFAULT '';
  DECLARE v_valorUnitario FLOAT DEFAULT 0;
  DECLARE v_quantidade FLOAT DEFAULT 0;
  DECLARE v_data_movimento DATETIME;
  DECLARE v_cidade VARCHAR(70) DEFAULT '';
  DECLARE v_marca VARCHAR(70) DEFAULT '';
  DECLARE v_modelo VARCHAR(70) DEFAULT '';
  DECLARE v_motorista VARCHAR(70) DEFAULT '';
  DECLARE v_veiculo VARCHAR(70) DEFAULT '';
  DECLARE v_produto VARCHAR(70) DEFAULT '';
  

  DECLARE p_comb VARCHAR(70) DEFAULT '';
  DECLARE p_valorKm FLOAT DEFAULT 0;
  DECLARE p_kmLitro FLOAT DEFAULT 0;
  DECLARE v_custo_combustivel_km FLOAT DEFAULT 0;
  DECLARE cursorMovimentoVeiculos CURSOR FOR SELECT DISTINCT(PLACA_VEICULO) AS PLACA_VEICULO,
								    VALOR_UNITARIO,
                                    QUANTIDADE,
                                    DATA_MOVIMENTO,
                                    CENTRO_RESULTADO,
                                    PRODUTO,
                                    FABRICANTE_VEICULO,
                                    MODELO_VEICULO,
                                    MOTORISTA 
							      FROM movimento_veiculos a 	                            
								  WHERE PLACA_VEICULO LIKE CONCAT('%', p_veiculo,'%')
                                  AND a.CENTRO_RESULTADO in( SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						            AND  DATE(a.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal
                                   order by PLACA_VEICULO;  
                               
    -- Definição da variável de controle de looping do cursor                             
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET existe_mais_linhas=1;
  
     -- Puxando Soma Quantidade para calcular peso 
  SET somaQuantidade = (SELECT SUM(QUANTIDADE) 
					    FROM movimento_veiculos b WHERE 	                 
                        PLACA_VEICULO LIKE CONCAT('%', p_veiculo,'%')
						AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						AND DATE(b.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal) ;                     
                        
  SET somaDistancia = (SELECT SUM(DISTANCIA_PERCORIDA) 
					    FROM movimento_veiculos b 
	                    WHERE PLACA_VEICULO LIKE CONCAT('%', p_veiculo,'%')
						AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						AND DATE(b.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal);
                        
 SET somaValorTotal = (SELECT SUM(VALOR_TOTAL) 
					    FROM movimento_veiculos b 
	                    WHERE  PLACA_VEICULO LIKE CONCAT('%', p_veiculo,'%')
						AND b.CENTRO_RESULTADO in(SELECT DISTINCT(CENTRO_RESULTADO ) FROM movimento_veiculos a 
                                                    WHERE CENTRO_RESULTADO NOT IN('PIAUI','GOIAS'))
						AND DATE(b.DATA_MOVIMENTO) between p_dataInicial and p_dataFinal);
                       
                        
  SET p_kmLitro = ( somaDistancia / somaQuantidade );                       
-- Abertura do cursor
  OPEN cursorMovimentoVeiculos;

  -- Looping de execução do cursor
  cursorMovimentoVeiculos: LOOP
  FETCH cursorMovimentoVeiculos INTO  v_veiculo,
                                      v_valorUnitario,
                                      v_quantidade, 
                                      v_data_movimento, 
                                      v_centroResultado,
                                      v_produto,
                                      v_marca,
                                      v_modelo,
                                      v_motorista;

  -- Controle de existir mais registros na tabela
  IF existe_mais_linhas = 1 THEN
  LEAVE cursorMovimentoVeiculos;
  END IF;
  
  -- Soma a kilometragem do registro atual com o total acumulado
  SET peso = (v_quantidade / somaQuantidade);
  SET somaValorCombustivel = somaValorCombustivel + (v_valorUnitario * peso) ;

  -- Retorna para a primeira linha do loop
  END LOOP cursorMovimentoVeiculos;
  
  SET qtdCombustivel = IF (ROUND(somaQuantidade)>1000,REPLACE(FORMAT(ROUND(somaQuantidade),0), ',', ''), CONCAT("", round(somaQuantidade)));
  SET vlrCombustivel = IF (ROUND(somaValorTotal)>1000,REPLACE(FORMAT(ROUND(somaValorTotal),0), ',', ''), CONCAT("", round(somaValorTotal)));
  SET v_custo_combustivel_km = FORMAT(somaValorCombustivel,2) / FORMAT( p_kmLitro,0) ;
  -- Setando a variável com o resultado final
  
  IF ROUND(somaValorCombustivel,2) <>0 THEN
  SELECT 
    FORMAT(somaValorCombustivel,2) as VALOR_COMBUSTIVEL, 
	FORMAT(qtdCombustivel,0) as QUANTIDADE_LITROS,
    FORMAT(somaValorTotal,0) SOMA_VALOR_COMBUSTIVEL,    
    FORMAT( p_kmLitro,0) AS KM_LITRO,
    UPPER(p_veiculo) AS EQUIPE,
    FORMAT(v_custo_combustivel_km,2) as CUSTO_COMBUSTIVEL_KM,
    v_marca as MARCA,
    v_modelo AS MODELO,
    v_motorista AS MOTORISTA, v_veiculo as PLACA_VEICULO;
    END IF;
END $$




