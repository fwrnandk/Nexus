<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['email']))) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['email'];

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-administracao.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>NEXUS</title>
    <style>
        
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="user">
                <div class="user-txt">
                    <a href="adm_dinheiro.php">
                        <h2>Voltar</h2>
                    </a>
                </div>
            </div>
            <div class="logo">
                <img src="imagens/logo-branco.png">
            </div>
        </nav>
    </header>
    <div class="main">
        <div class="container">
            <div class="graficos">
                <h3>Baixe Seus Gastos em Excel</h3>
            </div>
            <div class="download-excel">
                <a href="gerar_excel.php?categoria=Dívida">Excel - Dívida</a>
                <a href="gerar_excel.php?categoria=Investimento">Excel - Investimento</a>
                <a href="gerar_excel.php?categoria=Compras">Excel - Compras</a>
                <a href="gerar_excel.php?categoria=Outros">Excel - Outros</a>
                <a href="gerar_excel.php">Excel - Todos os Registros</a>
            </div>
            <iframe src="pizza.php" width="100%" height="300px" frameborder="0"></iframe>
            <iframe src="coluna.php" width="100%" height="400px" frameborder="0"></iframe>
        </div>
    </div>
</body>

</html>