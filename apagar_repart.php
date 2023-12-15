<?php

session_start();
ob_start();
include_once 'conexao.php';

$id_rep = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if(empty($id_rep)) {
    header("Location: adm_dinheiro.php");
    exit();
}

$query_rep = "SELECT id_rep FROM reparticoes WHERE id_rep = $id_rep LIMIT 1";
$result_rep = $conn->prepare($query_rep);
$result_rep->execute();

if(($result_rep) AND ($result_rep->rowCount() != 0)) {
    $del_rep = "DELETE FROM reparticoes WHERE id_rep = $id_rep";
    $apagar_rep = $conn->prepare($del_rep);

    if($apagar_rep->execute()) {
        $_SESSION['msg'] = "<p style='color:green;'>Repartição apagada com sucesso!</p>";
        header("Location: adm_dinheiro.php");
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Erro: Repartição não apagada com sucesso!</p>";
        header("Location: adm_dinheiro.php");
    }
} else {
    $_SESSION['msg'] = "Erro: Repartição nã encontrada!";
    header("Location: adm_dinheiro.php");
}


?>