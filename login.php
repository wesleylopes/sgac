<?php
session_start();
require("conexao.php");

if (isset($_SESSION['ID'])==true){
   header("location: principal.php");
}else{


if (isset($_POST['email'])&& empty($_POST['email'])==false){
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    try{

       $sql = $db->query("SELECT * FROM usuarios WHERE email='$email'AND senha= '$senha'");
        echo "<br>";

       if ($sql->rowCount()>0){

         $dado= $sql->fetch();

         $_SESSION['ID']=$dado['ID'];
           header("location: principal.php");
        }
        else
            echo "Senha ou usuário Invalido!";

    }catch(PDOException $e){
        echo "Falhou: ".$e->getMessage();
    }
unset($_POST['email']);
}
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

    <div id="main">
        <div id="main">
            <div class="row d-flex justify-content-around">

                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4 card-area">
                    <div class="card1 mb-3">
                        <div class="card-header">
                            <h3><i class="fa fa-check-square-o"></i> LOGIN </h3>
                            <img id="logo-login" src="assets/images/logo-1.png">
                        </div>

                        <div class="card-body">

                            <form method="POST" action>
                                <div class="form-group">
                                    <label for="InputEmail">E-mail: </label>
                                    <input type="email" class="form-control" id="InputEmail" name="email" aria-describedby="emailHelp" placeholder=" usuário@potenciamedicoes.com.br" required>
                                </div>
                                <div class="form-group">
                                    <label for="InputPassword">Senha: </label>
                                    <input type="password" class="form-control" id="InputPassword" name="senha" placeholder="Digite a sua Senha" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-lock"></i> Entrar</button>

                                <a href="#">Esqueci a senha</a>
                                <br>
                                <br>
                                <span>SGAC - SISTEMA DE GESTÃO E ANALISE DE CONSUMO DE COMBUSTÍVEL</span>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
