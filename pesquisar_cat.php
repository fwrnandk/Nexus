<?php

session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['email']))) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['email'];

$categoria = filter_input(INPUT_GET, 'categoria', FILTER_DEFAULT);

if (!empty($categoria)) {
    $query_repart = "SELECT * FROM reparticoes WHERE user_rep=:user_rep AND categoria=:categoria";
    $result_repart = $conn->prepare($query_repart);
    $result_repart->bindParam(':user_rep', $user);
    $result_repart->bindParam(':categoria', $categoria);
    $result_repart->execute();
} else {
    $query_repart = "SELECT * FROM reparticoes WHERE user_rep=:user_rep";
    $result_repart = $conn->prepare($query_repart);
    $result_repart->bindParam(':user_rep', $user);
    $result_repart->execute();
}

$listar_repart = "";
if (($result_repart) and ($result_repart->rowCount() != 0)) {
    while ($row_repart = $result_repart->fetch(PDO::FETCH_ASSOC)) {
        extract($row_repart);

        $listar_repart .= "<div class='reparticoes'><div class='retangulo'>
        <div class='lateral-esquerda'></div>
        <p>Gasto: $nome_rep </p>
        <p>Valor: R$ $valor </p>
        <p>Categoria: $categoria </p>
        <p class='reparticoes-txt'><a href='editar_repart.php?id=$id_rep'>Editar</a> | <a href='apagar_repart.php?id=$id_rep'>Apagar</a></p>
    </div></div>";
    }
    $retorna = ['status' => true, 'dados' => $listar_repart];
} else {
    $retorna = ['status' => false, 'msg' => "<p>ERRO: Nenhum resultado encontrado!</p>"];
}

echo json_encode($retorna);

?>
