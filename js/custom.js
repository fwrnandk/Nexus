//Pesquisa e exibe entre as categorias
async function pesquisar_cat() {
    var categoria = document.getElementById("categoria").value;
    console.log(categoria);

    var dados = await fetch("pesquisar_cat.php?categoria=" + categoria);

    var resposta = await dados.json();
    console.log(resposta);

    if(!resposta['status']) {
        document.getElementById('msg').innerHTML = resposta['msg'];
    }else {
        document.getElementById('msg').innerHTML = "";

        document.getElementById('listar-repart').innerHTML = resposta['dados'];
    }
}