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
    <link rel="stylesheet" href="css/style-cadastro4.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>NEXUS</title>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="imagens/logo-preto.png">
        </div>

        <div class="form-image">
            <p>Foto do rosto</p>

            <img src="imagens/foto-rosto.png">
          
        </div>
        <div class="form">
        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dados['cadImg'])) {
                    $img_rosto = $_FILES['img_rosto'];
                
                    $query_update = "UPDATE usuarios SET img_rosto = :img_rosto, tipo_doc = :tipo_doc WHERE email = :email";
                    $cad_usu = $conn->prepare($query_update);

                    $cad_usu->bindParam(':img_rosto', $img_rosto['name'], PDO::PARAM_STR);
                    $cad_usu->bindParam(':tipo_doc', $dados['tipo_doc'], PDO::PARAM_STR);
                    $cad_usu->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
                    $cad_usu->execute();

                    if($cad_usu->rowCount()) {
                        $ultimo_id = $conn->lastInsertId();

                        $diretorio = "imagens_users/$ultimo_id/";

                        $nome_doc_frente = $img_rosto['name'];

                        move_uploaded_file($img_rosto['tmp_name'], $diretorio . $nome_doc_frente);

                        header("Location:parabens-log.html");

                    } else {
                        echo "<p style='color:red;'>Erro: Imagens não inseridas com sucesso</p>";
                    }
                }
            ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>

                </div>

                <div class="input-group">
                    <h2>Foto do rosto:</h2>
                
                    <label class="picture" for="picture__input" tabIndex="0">
                        <span class="picture__image"></span>
                      </label>
                      
                      <input type="file" name="img_rosto" id="picture__input">
                <!--bglh do drag drop-->

                <!--script do bglh do drag drop-->
                
                <!--bglh do drag drop-->
                </div>
            
            <br>
            <div class="login-button">
            <a href=""><input type="submit" name="cadImg" value="PROXIMO"></a></input>
            </form>
            </div>
        </div>
    </div>

</body>

</html>

<script>
                    const inputFile = document.querySelector("#picture__input");
const pictureImage = document.querySelector(".picture__image");
const pictureImageTxt = "+";
pictureImage.innerHTML = pictureImageTxt;

inputFile.addEventListener("change", function (e) {
  const inputTarget = e.target;
  const file = inputTarget.files[0];

  if (file) {
    const reader = new FileReader();

    reader.addEventListener("load", function (e) {
      const readerTarget = e.target;

      const img = document.createElement("img");
      img.src = readerTarget.result;
      img.classList.add("picture__img");

      pictureImage.innerHTML = "";
      pictureImage.appendChild(img);
    });

    reader.readAsDataURL(file);
  } else {
    pictureImage.innerHTML = pictureImageTxt;
  }
});

                </script>