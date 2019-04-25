<?php   
session_start();
if (isset($_SESSION['ID'])==false){
   header("location: login.php");
}else{   
   header("location: principal.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SGAC LOGIN</title>
    <meta name="description" content="Sistema de Gestão de e Analise de Combustível">
    <meta name="author" content="Potencia Medicões">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Switchery css -->
    <!--<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />--S>

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
   <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN CSS for this page -->

    <!-- END CSS for this page -->

</head>

<body class="adminbody">
    <div id="main"> <a href="sair.php">Sair</a>
    </div>
</body>


