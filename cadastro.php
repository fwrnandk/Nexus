<?php
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-cadastro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
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
                if(!empty($dados['cadUsu'])) {
                    $email = $dados['email'];
                    $empty_input = false;
                    $dados = array_map('trim', $dados);
                    if(in_array("", $dados)) {
                        $empty_input = true;
                        echo "<p style='color:red'>Necessário preencher todos os campos!</p>";
                    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                        $empty_input = true;
                        echo "<p style='color:red'>Necessário preencher com e-mail válido.</p>";
                    }
                    if(!$empty_input) {
                        if($dados['senha'] == $dados['asenha']) {

                            $senha = password_hash($dados['senha'], PASSWORD_DEFAULT);

                            $ver_email = "SELECT * FROM usuarios WHERE email='$email'";
                            $result_ver_email = $conn->prepare($ver_email);
                            $result_ver_email->execute();

                            $ver_cpf = "SELECT * FROM usuarios WHERE cpf=:cpf";
                            $result_ver_cpf = $conn->prepare($ver_cpf);
                            $result_ver_cpf->bindParam(':cpf', $dados['cpf'], PDO::PARAM_STR);
                            $result_ver_cpf->execute();

                            $data_nasc = $dados['data_nasc'];
                            $data_nasc_obj = new DateTime($data_nasc);
                            $data_atual = new DateTime();
                            $diferenca = $data_atual->diff($data_nasc_obj);
                    
                            if($result_ver_email->rowCount() != 0){
                                echo "<p style='color:red'>Email já cadastrado!</p>";
                            } elseif($result_ver_cpf->rowCount() != 0) {
                                echo "<p style='color:red'>Erro: CPF já cadastrado!</p>";
                            } elseif($diferenca->y < 18) {
                                echo "<p style='color:red'>Não é permitido a criação de contas para menores de idade.</p>";
                            } else {
                                $query_usuario = "INSERT INTO usuarios (nome, email, cpf, data_nasc, celular, senha, created) VALUES (:nome, :email, :cpf, :data_nasc, :celular, :senha, NOW())";
                                $cad_usu = $conn->prepare($query_usuario);
                                $cad_usu->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                                $cad_usu->bindParam(':cpf', $dados['cpf'], PDO::PARAM_STR);
                                $cad_usu->bindParam(':celular', $dados['celular'], PDO::PARAM_STR);
                                $cad_usu->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                                $cad_usu->bindParam(':data_nasc', $data_nasc, PDO::PARAM_STR);
                                $cad_usu->bindParam(':senha', $senha, PDO::PARAM_STR);
                                $cad_usu->execute();
                                if($cad_usu->rowCount()) {
                                    session_start();
                                    $_SESSION['email'] = $dados['email'];
                                    header("Location: parabens-cad.html");
                                } else {
                                    echo "<p style='color:red'>Usuário não cadastrado com sucesso</p>";
                                }
                            }

                            
                        } else {
                            echo "<p style='color:red'>Senhas Diferentes.</p>";
                        }
                    }
                }
            ?>

            <form name="cad-usuario" method="POST">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-box"><label for="name">Nome Completo</label>
                        <input id="name" type="text" name="nome" placeholder="Ex: Ariel  Fernandes" required></input>
                    </div>
                    <div class="input-box"><label for="cpf">CPF</label>
                        <input id="cpf" type="text" name="cpf" minlenght="14" maxlength="14" placeholder="Ex: 000.000.000-00" required></input>
                    </div>
                    <div class="input-box"><label for="data_nasc">Data de Nascimento</label>
                        <input id="data_nasc" type="date" name="data_nasc" minlenght="14" maxlength="14" required></input>
                    </div>
                    <div class="input-box"><label for="celular">Celular</label>
                        <input id="telefone" type="text" name="celular" required minlenght="11"  placeholder="Ex: (00) 00000-0000"></input>
                    </div>
                    <div class="input-box"><label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="Ex: email@email.com" required></input>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha<div id="icon"><i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha()"></i></div></label>
                        <input id="senha" type="password" name="senha" minlenght="8" placeholder="Ex: min 8 caracteres" value="<?php if (isset($dados['asenha'])) {
                            echo $dados['senha'];
                        } ?>" required></input>
                    </div>

                    <div class="input-box"><label for="password2">Confirme a Senha</label>
                        <input id="cf-senha" type="password" name="asenha" placeholder="Confirme a Senha" required></input>

                    </div>

                </div>

            <div class="login-button">
                <a href=""><input type="submit" name="cadUsu" value="PROXIMO"></a></input>
            </div>
            </form>
        </div>
    </div>

    <script src="assets/senha-login.js"></script>
    <script>
    //Aceitar apenas texto no nome
        document.addEventListener("DOMContentLoaded", function() {
            var nome = document.getElementById("nome");

            nome.addEventListener("input", function() {
                var textoDigitado = nome.value;

                // Expressão regular que permite apenas letras (maiúsculas e minúsculas) e espaços
                var regex = /^[a-zA-Z\s]*$/;

                if (!regex.test(textoDigitado)) {
                        // Remover caracteres não permitidos (números)
                        nome.value = textoDigitado.replace(/[0-9!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/g, '');
                    }
            });
        });

    //FIM

        //Máscara CPF
        const cpf = document.querySelector("#cpf");

        cpf.addEventListener('input', () => {
            let inputValue = cpf.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            let cpfLength = inputValue.length;

            if (cpfLength > 11) {
                inputValue = inputValue.slice(0, 11); // Limita o comprimento a 11 dígitos
            }

            if (cpfLength > 3 && cpfLength <= 6) {
                cpf.value = inputValue.substring(0, 3) + '.' + inputValue.substring(3);
            } else if (cpfLength > 6 && cpfLength <= 9) {
                cpf.value = inputValue.substring(0, 3) + '.' + inputValue.substring(3, 6) + '.' + inputValue.substring(6);
            } else if (cpfLength > 9) {
                cpf.value = inputValue.substring(0, 3) + '.' + inputValue.substring(3, 6) + '.' + inputValue.substring(6, 9) + '-' + inputValue.substring(9);
            } else {
                cpf.value = inputValue;
            }
    });
        //FIM

        //Máscara Telefone

        var telefone = document.getElementById("telefone");

        telefone.addEventListener("input", () => {
            var limparValor = telefone.value.replace(/\D/g, "").substring(0, 11);
            var numerosArray = limparValor.split("");
            var numeroFormatado = "";

            if (numerosArray.length > 0) {
                numeroFormatado += `(${numerosArray.slice(0, 2).join("")})`;
            }
            if (numerosArray.length > 2) {
                numeroFormatado += ` ${numerosArray.slice(2, 7).join("")}`;
            }
            if (numerosArray.length > 7) {
                numeroFormatado += `-${numerosArray.slice(7, 11).join("")}`;
            }
            telefone.value = numeroFormatado;
        });

        //Máscara Email

            document.addEventListener("DOMContentLoaded", function() {
                var campoEmail = document.getElementById("email");

                campoEmail.addEventListener("input", function() {
                    var emailDigitado = campoEmail.value;

                    // Expressão regular que permite apenas letras, números, ponto e @
                    var regex = /^[a-zA-Z0-9.@]*$/;

                    if (!regex.test(emailDigitado)) {
                        // Remover caracteres não permitidos
                        campoEmail.value = emailDigitado.replace(/[^a-zA-Z0-9.@]/g, '');
                    }
                });
            });

        //FIM

        //Botão de mostrar/ocultar senha
        function mostrarSenha() {
            var inputPass = document.getElementById('senha');
            var inputPass2 = document.getElementById('cf-senha');
            var btnShowPass = document.getElementById('btn-senha');

            if(inputPass.type == 'password') {
                inputPass.setAttribute('type', 'text');
                inputPass2.setAttribute('type', 'text');
                btnShowPass.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            }else {
                inputPass.setAttribute('type', 'password');
                inputPass2.setAttribute('type', 'password');
                btnShowPass.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        }

</script>
</body>

</html>