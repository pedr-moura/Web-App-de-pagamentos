<?php
include_once('config.php');

if (isset($_POST['updatePix'])) {
    $id = $_POST['id'];
    $expiracao = $_POST['expiracao'];
    $devedor_cpf = $_POST['devedor_cpf'];
    $devedor_nome = $_POST['devedor_nome'];
    $valor_original = $_POST['valor_original'];
    $chave = $_POST['chave'];
    $solicitacao_pagador = $_POST['solicitacao_pagador'];

    $sqlUpdatePix = "UPDATE dadospix SET expiracao = '$expiracao', devedor_cpf = '$devedor_cpf', devedor_nome = '$devedor_nome', 
                      valor_original = '$valor_original', chave = '$chave', solicitacao_pagador = '$solicitacao_pagador'
                      WHERE id = '$id'";

    $resultPix = $conexao->query($sqlUpdatePix);
}

header('Location: sisAdm.php');
?>
