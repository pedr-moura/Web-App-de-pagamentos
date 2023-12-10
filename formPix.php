<?php
    if(isset($_POST['submitPix']))
    {
        include_once('config.php');
        
        $expiracao = $_POST['expiracao'];
        $devedor_cpf = $_POST['devedor_cpf'];
        $devedor_nome = $_POST['devedor_nome'];
        $valor_original = $_POST['valor_original'];
        $chave = $_POST['chave'];
        $solicitacao_pagador = $_POST['solicitacao_pagador'];

        // Use declarações preparadas para evitar injeção de SQL
        $stmtPix = $conexao->prepare("INSERT INTO dadospix (expiracao, devedor_cpf, devedor_nome, valor_original, chave, solicitacao_pagador) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtPix->bind_param("ssssss", $expiracao, $devedor_cpf, $devedor_nome, $valor_original, $chave, $solicitacao_pagador);

        // Executar a declaração e verificar erros
        if ($stmtPix->execute()) {
            
        } else {
            echo "Erro na inserção: " . $stmtPix->error;
        }

        // Fechar a declaração
        $stmtPix->close();
        
        // Fechar a conexão com o banco de dados
        $conexao->close();

        header('Location: sisAdm.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Dados Pix</title>
    <link rel="stylesheet" href="style.css">
    <style>

        #submit{
    background-image: linear-gradient(to right, rgb(8, 82, 8), rgb(0, 161, 0));
    border-radius: 10px;
    width: 100%;
    border: none;
    outline: none;
    padding: 15px;
    color: white;
    font-size: 20px;
    cursor: pointer;
    }

    #submit:hover{
    background-image: linear-gradient(to right, rgb(6, 58, 6), rgb(0, 119, 0));
    }

    

    </style>
</head>
<body>
    <div class="voltar">
        <a href="sisAdm.php">Voltar</a>
    </div>
    
    <div class="boxReg">
        <form action="formPix.php" method="POST">
            <fieldset>
                <legend><b>Cadastrar Dados Pix</b></legend>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="expiracao" id="expiracao" class="inputUser" required>
                    <label for="expiracao" class="labelInput">Expiração</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="devedor_cpf" id="devedor_cpf" class="inputUser" required>
                    <label for="devedor_cpf" class="labelInput">CPF do Devedor</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="devedor_nome" id="devedor_nome" class="inputUser" required>
                    <label for="devedor_nome" class="labelInput">Nome do Devedor</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="valor_original" id="valor_original" class="inputUser" required>
                    <label for="valor_original" class="labelInput">Valor Original</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="chave" id="chave" class="inputUser" required>
                    <label for="chave" class="labelInput">Chave Pix</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="solicitacao_pagador" id="solicitacao_pagador" class="inputUser" required>
                    <label for="solicitacao_pagador" class="labelInput">Solicitação do Pagador</label>
                </div><br><br><br><br>

                <input type="submit" name="submitPix" id="submitPix">

            </fieldset>
        </form>
    </div>
    
</body>
</html>
