<?php
    session_start();
    ob_start();
    include_once 'conexao.php';
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['login'])) {
        $query_email = "SELECT senha FROM usuarios WHERE email = :email LIMIT 1";
        $result_email = $conn->prepare($query_email);
        $result_email->bindParam(':email', $dados['email'], PDO::PARAM_STR);
        $result_email->execute();
    
        $usuario = $result_email->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($dados['senha'], $usuario['senha'])) {
            $_SESSION['email'] = $dados['email'];
            header("Location: principal.php");
        } else {
            header("Location: erro_login.html");
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS</title>
    <link rel="stylesheet" href="css/style-login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
</head>

<body>
    <div class="main-login">
        <div class="logo">
            <img src="imagens/logo-preto.png">
        </div>
        <div class="imagem-porco">
            <img src="imagens/pig-login.png">
        </div>
       
        <div class="card-login">
            
            <h1>LOGIN</h1>
            <form method="post">
            <div class="textfield">
                <input type="text" name="email" placeholder="Email">
            </div>
            <div class="textfield">
                <label for="senha"><div id="icon"><i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha()"></i></div></label>
                <input type="password" name="senha" placeholder="Senha" id="senha">
            </div>

            <div class="login-button">  
            <a href="">
                <input type="submit" name="login" value="LOGIN"></a>
            </input>
                
            </div>
            <p>Não tem login? <br> <a href="cadastro.php">Cadastre-se</a></p>
        </div>
        </form>
    </div>
    <?php
                if (isset($erro_login)) {
                    echo "<p style='color:red;>$erro_login</p>";
                }
            ?>

    <script >   //Botão de mostrar/ocultar senha
        function mostrarSenha() {
            var inputPass = document.getElementById('senha');
            var btnShowPass = document.getElementById('btn-senha');

            if(inputPass.type == 'password') {
                inputPass.setAttribute('type', 'text');
                btnShowPass.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            }else {
                inputPass.setAttribute('type', 'password');
                btnShowPass.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        }</script>
    
</body>

</html>
