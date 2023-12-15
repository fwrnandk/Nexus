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
    <link rel="stylesheet" href="css/style-cadastro3.css">
    <link rel="icon" href="imagens/favicon2.ico" type="image/x-icon">
    <title>NEXUS</title>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="imagens/logo-preto.png">
        </div>

        <div class="form-image">
            <div class="txt-img">

                <div class="title">
                    <h3>Frente e Verso do Documento</h3>
                </div>
            </div>
            <img src="imagens/frente.png">
            <img src="imagens/verso.png">
        </div>
        <div class="form">
            <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dados['cadDoc'])) {
                    $img_doc_frente = $_FILES['frente_doc'];
                    $img_doc_verso = $_FILES['verso_doc'];

                    $query_update = "UPDATE usuarios SET tipo_doc = :tipo_doc, img_doc_frente = :img_doc_frente, img_doc_verso = :img_doc_verso WHERE email = :email";
                    $cad_usu = $conn->prepare($query_update);

                    $cad_usu->bindParam(':tipo_doc', $dados['tipo_doc'], PDO::PARAM_STR);
                    $cad_usu->bindParam(':img_doc_frente',  $img_doc_frente['name'], PDO::PARAM_STR);
                    $cad_usu->bindParam(':img_doc_verso', $img_doc_verso['name'], PDO::PARAM_STR);
                    $cad_usu->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
                    $cad_usu->execute();

                    if($cad_usu->rowCount()) {
                        $ultimo_id = $conn->lastInsertId();

                        $diretorio = "imagens_users/$ultimo_id/";

                        mkdir($diretorio, 0755);

                        $nome_doc_frente = $img_doc_frente['name'];
                        $nome_doc_verso = $img_doc_verso['name'];
                        $nome_img_rosto = $img_rosto['name'];

                        move_uploaded_file($img_doc_frente['tmp_name'], $diretorio . $nome_doc_frente);
                        move_uploaded_file($img_doc_verso['tmp_name'], $diretorio . $nome_doc_verso);
                        move_uploaded_file($img_rosto['tmp_name'], $diretorio . $nome_img_rosto);

                        header("Location: cadastropt4.php");

                    } else {
                        echo "<p>Erro: Imagens não inseridas com sucesso</p>";
                    }
                }
            ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                </div>

                <div class="input-group">
                    <h2>Foto do documento</h2>
                    RG: <input type="radio" name="tipo_doc" /><br />
                    CNH: <input type="radio" name="tipo_doc" /><br />
                </div>

                <!--bglh do drag drop-->
                <h2>Frente:</h2>
                <label class="picture" for="picture__input">
                    <span class="picture__image">+</span>
                </label>
                <input type="file" name="frente_doc" id="picture__input">
                  
                <h2>Verso:</h2>
                
                <label class="picture" for="picture__input2">
                    <span class="picture__image2">+</span>
                </label>
                <input type="file" name="verso_doc" id="picture__input2">
                <!--bglh do drag drop-->

                <!--script do bglh do drag drop-->
                <!--bglh do drag drop-->
           
            <br>
            <div class="login-button">
            <a href=""><input type="submit" name="cadDoc" value="PROXIMO"></a></input>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
<script>
                    function handleFileInputChange(inputFile, pictureImage, pictureImageTxt) {
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
  }

  const inputFile = document.querySelector("#picture__input");
  const pictureImage = document.querySelector(".picture__image");
  const pictureImageTxt = "+";
  handleFileInputChange(inputFile, pictureImage, pictureImageTxt);

  const inputFile2 = document.querySelector("#picture__input2");
  const pictureImage2 = document.querySelector(".picture__image2");
  const pictureImageTxt2 = "+";
  handleFileInputChange(inputFile2, pictureImage2, pictureImageTxt2);


                </script>