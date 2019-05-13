<?php
$dsn="mysql:dbname=sgacbase;localhost;charset=utf8";
    $dbuser="root";
    $dbpass="";
    
    try{
         $db = new PDO($dsn, $dbuser, $dbpass);
        
       }catch(PDOException $e){
          echo "Falhou: ".$e->getMessage();
       }


