<?php
session_start();
ob_start();
include_once 'conexao.php';

if((!isset($_SESSION['email']))) {
    header('Location: login.php');
}

$user = $_SESSION['email'];

$dataLimite = date('Y-m-d', strtotime('-30 days'));
$query_repart = "SELECT nome_rep, valor, categoria FROM reparticoes WHERE user_rep = :user_rep AND created >= :data_limite";
$result_repart = $conn->prepare($query_repart);
$result_repart->bindParam(':user_rep',$user);
$result_repart->bindParam(':data_limite',$dataLimite);
$result_repart->execute();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Dinheiro</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "Density", { role: "style" } ],
            <?php
                while ($row_repart = $result_repart->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_repart);
            ?>
            
            ["<?php echo $nome_rep;?>", <?php echo $valor;?> , "purple"],   
            <?php } ?>
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

        var options = {
            title: "Repartições",
            width: '100%', // Tornar o gráfico 100% responsivo em largura
            height: 400,
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }

    </script>
</head>
<body>
    <div id="columnchart_values"></div>
</body>
</html>
