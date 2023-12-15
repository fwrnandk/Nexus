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
    <link rel="stylesheet" href="https://cdn.es.gov.br/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>Meu Dinheiro</title>

</head>

<body>
    <header>
        <nav>
            <div class="user">
                <div class="user-txt">
                    <a href="principal.php">
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
                <h1>Administre Seu Dinheiro</h1>
            </div>

            <div class="button-gastos">
                <div class="crie-txt">
                    <p>Crie uma nova repartição para seus gastos</p>
                </div>
                <a href="criar_repart.php"><button class="button">
                        <p>CRIAR</p>
                </a>
                </button>
            </div>


            <?php
            $primeiroDia = date('Y-m-01');
            $ultimoDia = date('Y-m-t');

            $query_gastos_mes = "SELECT SUM(valor) AS gastos_mes FROM reparticoes WHERE created BETWEEN :primeiroDia AND :ultimoDia AND user_rep = :user_rep";
            $result_gastos_mes = $conn->prepare($query_gastos_mes);
            $result_gastos_mes->bindParam(':user_rep', $user);
            $result_gastos_mes->bindParam(':primeiroDia', $primeiroDia);
            $result_gastos_mes->bindParam(':ultimoDia', $ultimoDia);
            $result_gastos_mes->execute();

            $row_gastos_mes =  $result_gastos_mes->fetch(PDO::FETCH_ASSOC);
            echo "<div class='gastos-txt'><p>Gastos do Mês: R$ " . $row_gastos_mes['gastos_mes'] .  '</p></div>';
            ?>




            <div class="download-excel">
                <a href="excel-graficos.php">
                    <p>Veja os graficos e faça download dos relatorios de seus gastos</p>
                </a>
            </div>

            <div class="calendario"><?php
                                    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                                    ?>

                <form method="POST" action="">
                    <div class="caixa"> <label>
                            <h3>Data de Início</h3>
                        </label>
                        <input class="calend" type="date" name="data_inicio" value="<?php
                                                                                        if (isset($dados['data_inicio'])) {
                                                                                            echo $dados['data_inicio'];
                                                                                        }
                                                                                    ?>">
                    </div>

                    <div class="caixa"><label>
                            <h3>Data de Final</h3>
                        </label>
                        <input class="calend" type="date" name="data_final" value="<?php
                                                                                        if (isset($dados['data_final'])) {
                                                                                            echo $dados['data_final'];
                                                                                        }
                                                                                    ?>">
                    </div>
                    <div class="botao"><input class="button-pesq" type="submit" value="PESQUISAR" name="pesq_rep"></div>

                </form>
            </div>

            <div class="ls-custom-select">
                <form>
                    <p>Selecione a categoria dos gastos</p>
                    <select class="ls-select" name="categoria" id="categoria" onchange="pesquisar_cat();">
                        <option value="">Selecione</option>
                        <option value="">Todas</option>
                        <option value="Dívida">Dívida</option>
                        <option value="Investimento">Investimento</option>
                        <option value="Compras">Compras</option>
                        <option value="Outros">Outros</option>
                    </select>
                </form>
            </div>

            <?php
            if ((isset($dados['data_inicio'])) and (isset($dados['data_final']))) {
                echo "<div class='download-excel'><hr><a href='gerar_excel.php?data_inicio=" . $dados['data_inicio'] . "&data_final=" . $dados['data_final'] . "'>Gerar EXCEL da Pesquisa</a><hr></div>";
            }

            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            if (!empty($dados['pesq_rep'])) {
                $query_pesq_data = "SELECT * FROM reparticoes WHERE created BETWEEN :data_inicio AND :data_final AND user_rep = :user_rep";
                $result_pesq_data = $conn->prepare($query_pesq_data);
                $result_pesq_data->bindParam(':user_rep', $user);
                $result_pesq_data->bindParam(':data_inicio', $dados['data_inicio']);
                $result_pesq_data->bindParam(':data_final', $dados['data_final']);

                $result_pesq_data->execute();

                if (($result_pesq_data) and ($result_pesq_data->rowCount() != 0)) {

                    $query_gastos_data = "SELECT SUM(valor) AS gastos_data FROM reparticoes WHERE created BETWEEN :data_inicio AND :data_final AND user_rep = :user_rep";
                    $result_gastos_data = $conn->prepare($query_gastos_data);
                    $result_gastos_data->bindParam(':user_rep', $user);
                    $result_gastos_data->bindParam(':data_inicio', $dados['data_inicio']);
                    $result_gastos_data->bindParam(':data_final', $dados['data_final']);
                    $result_gastos_data->execute();

                    $row_gastos_data =  $result_gastos_data->fetch(PDO::FETCH_ASSOC);
                    echo "<div class='gastos-txt'><p>Gastos entre as datas: R$ " . $row_gastos_data['gastos_data'] .  '</p></div>';

                    while ($row_repart = $result_pesq_data->fetch(PDO::FETCH_ASSOC)) {
                        extract($row_repart);

                        echo "<div class='reparticoes'><div class='retangulo'>
                        <div class='lateral-esquerda'></div>
                        <p>Gasto: $nome_rep </p>
                        <p>Valor: R$ $valor </p>
                        <p>Categoria: $categoria </p>
                        <p class='reparticoes-txt'><a href='editar_repart.php?id=$id_rep'>Editar <i class='bi bi-pencil-square'></i></a> | <a href='apagar_repart.php?id=$id_rep'>Apagar <i class='bi bi-trash'></i></a></p>
                    </div></div>";
                    }
                } else {
                    echo "<div class='erro-txt'><p>Nenhum repartição encontrada!</p></div>";
                }
            }
            ?>

            <span id="msg"></span>

            <span id="listar-repart"></span>

            <script src="js/custom.js"></script>
        </div>

    </div>

</body>

</html>