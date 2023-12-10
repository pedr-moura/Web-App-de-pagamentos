<?php
    if(!empty($_GET['id'])) {
        include_once('config.php');

        $id = $_GET['id'];

        $sqlSelect = "SELECT * FROM dadospix WHERE id=$id";

        $result = $conexao->query($sqlSelect);

        if($result->num_rows > 0) {
            while($pix_data = mysqli_fetch_assoc($result)) {
                $expiracao = $pix_data['expiracao'];
                $devedor_cpf = $pix_data['devedor_cpf'];
                $devedor_nome = $pix_data['devedor_nome'];
                $valor_original = $pix_data['valor_original'];
                $chave = $pix_data['chave'];
                $solicitacao_pagador = $pix_data['solicitacao_pagador'];
            }
        } else {
            header('Location: sisAdmPix.php');
        }
    } else {
        header('Location: sisAdmPix.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados Pix</title>
    <link rel="stylesheet" href="pix.css">
</head>
<body>
    <div class="voltar">
        <a href="sisAdm.php">Voltar</a>
    </div>
    
    <div class="boxReg">
        <form action="saveEditPix.php" method="POST">
            <fieldset>
                <legend><b>Editar Dados Pix</b></legend>
                <br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="expiracao" id="expiracao" class="inputUser" value="<?php echo $expiracao ?>" required>
                    <label for="expiracao" class="labelInput">Expiração</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="devedor_cpf" id="devedor_cpf" class="inputUser" value="<?php echo $devedor_cpf ?>" required>
                    <label for="devedor_cpf" class="labelInput">CPF do Devedor</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="devedor_nome" id="devedor_nome" class="inputUser" value="<?php echo $devedor_nome ?>" required>
                    <label for="devedor_nome" class="labelInput">Nome do Devedor</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="valor_original" id="valor_original" class="inputUser" value="<?php echo $valor_original ?>" required>
                    <label for="valor_original" class="labelInput">Valor Original</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="chave" id="chave" class="inputUser" value="<?php echo $chave ?>" required>
                    <label for="chave" class="labelInput">Chave Pix</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="solicitacao_pagador" id="solicitacao_pagador" class="inputUser" value="<?php echo $solicitacao_pagador ?>" required>
                    <label for="solicitacao_pagador" class="labelInput">Solicitação do Pagador</label>
                </div><br><br><br><br><br><br>

                <input type="hidden" name="id" value="<?php echo $id ?>">

                <input type="submit" name="updatePix" id="updatePix" value="Atualizar">
            </fieldset>
        </form>
    </div>
</body>
</html>
