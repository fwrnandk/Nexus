<?php
    session_start();
    ob_start();
    include_once 'conexao.php';

    if((!isset($_SESSION['email']))) {
        header('Location: login.php');
        exit();
    }

    $user = $_SESSION['email'];

    $data_inicio = filter_input(INPUT_GET, 'data_inicio', FILTER_DEFAULT);
    $data_final = filter_input(INPUT_GET, 'data_final', FILTER_DEFAULT);
    $categoria = filter_input(INPUT_GET, 'categoria', FILTER_DEFAULT);

    $query_pesq_data = "SELECT nome_rep, valor, categoria FROM reparticoes WHERE user_rep = :user_rep ";

    if ($data_inicio !== null && $data_final !== null) {
        $query_pesq_data .= " AND created BETWEEN :data_inicio AND :data_final";
    }

    if ($categoria !== null) {
        $query_pesq_data .= " AND categoria = :categoria";
    }

    $result_repart = $conn->prepare($query_pesq_data);
    $result_repart->bindParam(':user_rep', $user);

    if ($data_inicio !== null && $data_final !== null) {
        $result_repart->bindParam(':data_inicio', $data_inicio);
        $result_repart->bindParam(':data_final', $data_final);
    }

    if ($categoria !== null) {
        $result_repart->bindParam(':categoria', $categoria);
    }

    $result_repart->execute();


    

    if (($result_repart) && ($result_repart->rowCount() != 0)) {
        // Aceitar CSV ou texto
        header('Content-Type: text/csv; charset=utf-8');
        // Nome do Arquivo
        header('Content-Disposition: attachment; filename=arquivo.csv');
        // Gravar no buffer
        $resultado = fopen("php://output", "w");
    
        // Adicionar BOM para indicar UTF-8
        echo "\xEF\xBB\xBF";
    
        // Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter caracteres especiais
        $cabecalho = ['Gasto', 'Valor', 'Categoria'];
    
        // Converter o cabeçalho para UTF-8
        $cabecalho = array_map('utf8_encode', $cabecalho);
    
        // Escrever o cabeçalho no arquivo
        fputcsv($resultado, $cabecalho, ';');
    
        while ($row_repart = $result_repart->fetch(PDO::FETCH_ASSOC)) {
            // Converter os valores para UTF-8
            $row_repart = array_map('utf8_encode', $row_repart);
            fputcsv($resultado, $row_repart, ';');
        }
    } else {
        $_SESSION['msg'] = '<p>Erro: Nenhuma repartição encontrada!</p>';
        header('Location:excel-graficos.php');
    }
    
    
?>