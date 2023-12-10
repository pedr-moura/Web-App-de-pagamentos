<?php
    if(!empty($_GET['id']))
    {
        include_once('config.php');

        $id = $_GET['id'];
         
        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";

        $result = $conexao->query($sqlSelect);

        if($result->num_rows >0 )
        {
            while($user_data = mysqli_fetch_assoc($result))
            {
                $nome = $user_data['nome'];
                $email = $user_data['email'];
                $telefone = $user_data['telefone'];
                $sexo = $user_data['sexo'];
                $data_nasc = $user_data['data_nasc'];
            }       
        }
        else
        {
            header('Location: sisAdm.php');
        }
    }

    else
    {
        header('Location: sisAdm.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro CAMVET</title>
    <link rel="stylesheet" href="styleADM.css">
</head>
<body>
    <div class="voltar">

            
    <a href="sisAdm.php">Voltar</a>

    </div>
    
    <div class="boxReg">
        <form action="saveEdit.php" method="POST">
            <fieldset>
                <legend><b>Registrar Aluno</b></legend>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" value="<?php  echo $nome ?>" required>
                    <label for="nome" class="labelInput">Nome completo</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser" value="<?php  echo $email ?>"  required>
                    <label for="email" class="labelInput">Email</label>
                </div><br><br><br><br>
                <div class="inputBox">
                    <input type="text" name="telefone" id="telefone" class="inputUser" value="<?php  echo $telefone ?>"  required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div><br><br><br><br>

                <p>Sexo:</p>
                <input type="radio" id="feminino" name="genero" value="feminino" <?php echo $sexo == 'feminino'?'checked' : '' ?>  required>
                <label for="feminino">Feminino</label><br>
                <input type="radio" id="maculino" name="genero" value="maculino" <?php echo $sexo == 'maculino'?'checked' : '' ?> required>
                <label for="maculino">Masculino</label><br>
                <input type="radio" id="outro" name="genero" value="outro" <?php echo $sexo == 'outro'?'checked' : '' ?> required>
                <label for="outro">Outro</label><br>

                <br><br>
                    <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                    
                    <input type="date" name="data_nascimento" id="data_nascimento" value="<?php  echo $data_nasc ?>" required>
                <br><br><br><br>

                <input type="hidden" name="id" value="<?php echo $id ?>">

                <input type="submit" name="update" id="update">

            </fieldset>
        </form>
    </div>
    
</body>
</html>
