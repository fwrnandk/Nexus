<?php
    session_start();
    ob_start();
    include_once 'conexao.php';

    if(!isset($_SESSION['email'])) {
        header('Location: login.php');
    }

    if(isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Consulta o banco de dados para obter o primeiro nome do usuário
        $query = "SELECT SUBSTRING_INDEX(nome, ' ', 1) AS primeiro_nome FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Obtém o resultado da consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se encontrou algum resultado
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-principal.css">
    <link rel="stylesheet" href="https://cdn.es.gov.br/fonts/font-awesome/css/font-awesome.min.css">
    <title>NEXUS</title>
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
</head>

<body>
    <header>
        <nav>
            <div class="user">
                <div class="user-img">
                    <!--<img src="imagens/do-utilizador.png">-->
                    <i class="fa fa-user"></i>
                </div>
                <div class="user-txt">
                    <?php
                        if ($resultado) {
                            $primeiro_nome = $resultado['primeiro_nome'];
                            echo "<h2>Olá,<br>$primeiro_nome!</h2>";
                        }
                    ?>
                </div>
            </div>

            <ul class="menu">
                <h1>Alterar Dados</h1>
                <li><a class="menuItem" href="alterar_dados.php">Dados Pessoais</a></li>
                <li><a class="menuItem" href="alterar_endereco.php">Endereço</a></li>
                <a href="sair.php" style="color:red;">Sair</a>
            </ul>
            <button class="hamburger">
                <!-- material icons https://material.io/resources/icons/ -->
                <div class="menu-icon"><i class="fa fa-bars"></i></div>
                <div class="close-icon"><i class="fa fa-times-circle"></i></div>
            </button>
           
        </nav>
    </header>
    <div class="main">
        <div class="container">
            <h2>Saldo disponível<br>
                R$ 1000,00
            </h2>
            <div class="button-group">
                <a href="erro.html">
                    <div class="button-random"><button><img src="imagens/pix.png"></button>
                        <p>Pix</p>
                    </div>
                </a>
                <a href="erro.html">
                    <div class="button-random"><button><img src="imagens/boleto.png"></button>
                        <p>Pagar</p>
                    </div>
                </a>
                <a href="erro.html">
                    <div class="button-random"><button><img src="imagens/transferencia.png"></button>
                        <p>Transferir</p>
                    </div>
                </a>
                <a href="erro.html">
                    <div class="button-random"><button><img src="imagens/investir.png"></button>
                        <p>Investir</p>
                    </div>
                </a>
                <a href="erro.html">
                    <div class="button-random"><button><img src="imagens/extrato.png"></button>
                        <p>Pedir extrato</p>
                    </div>
                </a>
                <a href="adm_dinheiro.php">
                    <div class="button-adm"><button><img src="imagens/carteira.png"></button>
                        <p>Meu dinheiro</p>
                    </div>
                </a>

            </div>
            <div class="button-group2">
                <a href="erro.html">
                    <div class="conta-button"><button>
                            <img src="imagens/cartao.png">
                            <p>Meus cartões</p>
                        </button></div>
                </a>
                <a href="erro.html">
                    <div class="conta-button"><button>
                            <img src="imagens/poupanca.png">
                            <p>Conta poupança</p>
                        </button></div>
                </a>
            </div>
        </div>
    </div>
    <script src="assets/menu-adm.js"></script>
</body>

</html>