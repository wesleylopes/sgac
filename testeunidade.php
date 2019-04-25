<?php
   require("conexao.php");

    $sql = $db->query("SELECT * FROM movimento_veiculos");    

    if ($sql->rowCount()>0){
       $dado= $sql->fetchall();
    }
?>
