<?php
include_once 'conexao.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-cadastro2.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>NEXUS</title>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="imagens/logo-preto.png">
        </div>
        <div class="form-image">
            <img src="imagens/piggy-cadastro.png">
        </div>
        <div class="form">
            <?php
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dados['cadEnd'])) {
                    $query_update = "UPDATE usuarios SET CEP = :cep, endereco = :endereco, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado WHERE email = :email";
                    $stmt = $conn->prepare($query_update);

                    $stmt->bindParam(':cep', $dados['cep'], PDO::PARAM_STR);
                    $stmt->bindParam(':endereco', $dados['endereco'], PDO::PARAM_STR);
                    $stmt->bindParam(':numero', $dados['numero'], PDO::PARAM_STR);
                    $stmt->bindParam(':cidade', $dados['cidade'], PDO::PARAM_STR);
                    $stmt->bindParam(':bairro', $dados['bairro'], PDO::PARAM_STR);
                    $stmt->bindParam(':estado', $dados['estado'], PDO::PARAM_STR);
                    $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);

                    // Executa a declaração preparada
                    $stmt->execute();

                    // Verifica se a atualização foi bem-sucedida
                    if ($stmt->rowCount() > 0) {
                        header("Location: cadastropt3.php");
                    } else {
                        echo "<p style='color:red'>Erro: verifique se seus dados estão corretos.</p>";
                    }
                }
            ?>
            <form method="POST">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastro</h1>
                    </div>
             
                </div>

                <div class="input-group">
                    <div class="input-box"><label for="cep">CEP</label>
                        <input id="cep" type="text" name="cep" placeholder="Ex: 00000-000" required>
                    </div>
                    <div class="input-box"><label for="endereco">Endereço</label>
                        <input id="endereco" type="text" name="endereco" placeholder="Ex: Rua dos Bobos" required>
                    </div>
                    <div class="input-box"><label for="numero">Número</label>
                        <input id="numero" type="number" name="numero" placeholder="Ex: 000" required>
                    </div>
                    <div class="input-box"><label for="bairro">Bairro</label>
                        <input id="bairro" type="text" name="bairro" placeholder="EX: Colinas do Anhaguera" required>
                    </div>
                    <div class="input-box"><label for="cidade">Cidade</label>
                        <input id="cidade" type="text" name="cidade"  placeholder="Ex: Santana de Parnaíba" required>
                    </div>
                    <div class="input-box"><label for="estado">Estado</label>
                        <input id="estado" type="text" name="estado" maxlength="2" placeholder="Ex: SP" required>
                    </div>
                </div>
            <div class="login-button">
                <a href=""><input type="submit" name="cadEnd" value="PROXIMO"></input></a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<script src="main.js"></script>

