<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['email']))) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-criar-repart.css">
    <link rel="stylesheet" href="https://cdn.es.gov.br/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>Anote Gastos</title>
</head>

<body>
    
    <header>
        <nav>
            <div class="user">
                <div class="user-txt">
                    <a href="adm_dinheiro.php">
                        <h2><i class="fa fa-right-from-bracket"></i>Voltar</h2>
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
            <div class="container-text">
                <h1>Anote Seus Gastos</h1>
            </div>
            <form name="criar-reparticao" method="POST" action="">
                <div class="form-group">
                    <div class="caixa"><label>
                            <h3>Nome do gasto</h3>
                        </label>
                        <input type="text" name="nome_rep" placeholder="Coloque o nome" required value="<?php if (isset($dados['nome_rep'])) {
                                                                                                            echo $dados['nome_rep'];
                                                                                                        } ?>">
                    </div>
                    <div class="caixa"> <label>
                            <h3>Valor do gasto</h3>
                        </label>
                        <input type="text" name="valor" id="valor_dinheiro" placeholder="0,00" required value="<?php if (isset($dados['valor'])) {
                                                                                                            echo $dados['valor'];
                                                                                                        } ?>">
                    </div>
                </div>

                <div class="ls-custom-select">
                    <label>
                        <h3>Categoria</h3>
                    </label>
                    <select class="ls-select" name="categoria" required>
                        <option value="">Escolha uma Categoria</option>
                        <option value="Divída">Divída</option>
                        <option value="Investimento">Investimento</option>
                        <option value="Compras">Compras</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div class="botao"><input class="button-pesq" type="submit" value="Criar" name="criar_rep"></div>
            </form>
            <?php
                $email_user = $_SESSION['email'];
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (!empty($dados['criar_rep'])) {
                    $nome_rep = $dados['nome_rep'];
                    $valor = $dados['valor'];
                    $categoria = $dados['categoria'];

                    $empty_input = false;

                    $dados = array_map('trim', $dados);
                    if (in_array("", $dados)) {
                        $empty_input = true;
                        echo "<br><p style='color:red; margin-left: 20px;'>ERRO: Necessário preencher todos os campos!</p>";
                    }
                    if (!$empty_input) {
                        $query_reparticao = "INSERT INTO reparticoes (user_rep, nome_rep, valor, categoria, created) VALUES ('$email_user', '$nome_rep', '$valor', '$categoria', NOW())";
                        $criar_rep = $conn->prepare($query_reparticao);
                        $criar_rep->execute();
                        if ($criar_rep->rowCount()) {
                            echo "<br><p style='color:green; margin-left: 20px;'>Repartição criada com sucesso!</p>";
                            unset($dados);
                        } else {
                            echo "<br><p style='color:red; margin-left: 20px;'>Erro: Não foi possivel anotar esse gasto, tente novamente.</p>";
                        }
                    }
                }
            ?>
        </div>
    </div>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const valorDinheiroInput = document.getElementById("valor_dinheiro");
        const aviso = document.getElementById("aviso");

        valorDinheiroInput.addEventListener("input", function() {
            // Remove todos os caracteres não numéricos, exceto o primeiro ponto
            const valorFormatado = valorDinheiroInput.value.replace(/[^0-9.]+/g, "").replace(/(\..*)\./g, '$1');

            // Limita a dois dígitos após o ponto
            const partes = valorFormatado.split(".");
            if (partes.length > 1) {
                partes[1] = partes[1].substring(0, 2);
            }

            valorDinheiroInput.value = partes.join(".");

            // Verifica se a entrada contém letras
            if (/[^0-9.]/.test(valorDinheiroInput.value)) {
                aviso.textContent = "Apenas números e um ponto decimal são permitidos, com no máximo dois dígitos após o ponto.";
            } else {
                aviso.textContent = "";
            }
        });
    });
</script>