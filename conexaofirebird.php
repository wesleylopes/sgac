<?php
mb_internal_encoding("UTF-8"); 
header('Content-type: text/html; charset=utf-8');

$servidor = 'servidorapp:/usr/share/db/POTENCIANEW.FDB';
 
//conexão com o banco, se der erro mostrara uma mensagem.
if (!($dbh=ibase_connect($servidor, 'SYSDBA', 'masterkey')))
        die('Erro ao conectar: ' .  ibase_errmsg());






$sql ="";
$sql = "INSERT INTO MOVES_MO (SEQUE_MO,
    DATMO_MO,
    CODOB_MO,
    CODAL_MO,
    CODFU_MO,
    CODMO_MO,
    OBSER_MO,
    USUAR_MO,
    NOTAS_MO,
    REQAL_MO,
    TIPES_MO,
    CODEM_MO,
    MOVNF_MO,
    NRNOT_MO,
    GRUPO_MO) values((select max(SEQUE_MO) as SEQUENCIA from MOVES_MO)+1,(SELECT CURRENT_DATE FROM RDB$DATABASE),2101101,211,1,2,'IMPORTACAO AUTOMATICA ALMOXARIFADO WESLEY 16/04/2019',null, null, null,'ENTRADA',1,null, null, null)";

$query= ibase_query ($dbh, $sql);


try{
  $tr = ibase_trans();
  $query= ibase_query ($dbh, $sql);
  ibase_commit($tr);

}

catch(Exception $e) {
        echo $e->getMessage();
    ibase_rollback($tr);
}
    
ibase_free_result($query);
ibase_close($dbh);



?>
