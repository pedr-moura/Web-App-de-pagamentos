<?php
    if(isset($_POST['submit']))
    {
        include_once('config.php');
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $sexo = $_POST['genero'];
        $data_nasc = $_POST['data_nascimento'];

        // Use declarações preparadas para evitar injeção de SQL
        $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, telefone, sexo, data_nasc) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $telefone, $sexo, $data_nasc);

        // Executar a declaração e verificar erros
        if ($stmt->execute()) {
            // Passa o nome do usuário para a próxima página
            header("Location: confirmacaoDeCadastro.php?nome=$nome");
            
        } else {
            echo "Erro na inserção: " . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
        
        // Fechar a conexão com o banco de dados
        $conexao->close();
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro CAMVET</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="voltar">

            
    <a href="home.php">Voltar</a>

    </div>
    
    <div class="boxReg">
        <form action="form.php" method="POST">
            <fieldset>
                <legend><b>Registrar Aluno</b></legend>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome" class="labelInput">Nome completo</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser" required>
                    <label for="email" class="labelInput">Email</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="telefone" id="telefone" class="inputUser" required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div><br><br><br><br>

                <p>Sexo:</p>
                <input type="radio" id="feminino" name="genero" value="feminino" required>
                <label for="feminino">Feminino</label><br>
                <input type="radio" id="maculino" name="genero" value="maculino" required>
                <label for="maculino">Masculino</label><br>
                <input type="radio" id="outro" name="genero" value="outro" required>
                <label for="outro">Outro</label><br>

                <br><br>
                    <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                    
                    <input type="date" name="data_nascimento" id="data_nascimento" required>
                <br><br><br><br>

                <input type="submit" name="submit" id="submit">

            </fieldset>
        </form>
    </div>

    <div class="dog2">
        <img src="media/cachorro 2.png" alt="">
    </div>

    <div class="moca">
        <img src="media/moca-em-pe.png" alt="">
    </div>

</body> 
</html>
