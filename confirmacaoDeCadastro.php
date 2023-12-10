<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro CAMVET</title>
    <link rel="stylesheet" href="style.css">
    <style>
        span{
            font-size: 25px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="voltar">
        <a href="home.php">Voltar</a>
    </div>
    
    <div class="boxReg">
        <form action="testLogin.php" method="POST">
            <fieldset>
                <legend><b>Registro Concluido 😎🎉</b></legend>
                <br>
                Olá!<span> <?php echo isset($_GET['nome']) ? $_GET['nome'] : ''; ?></span>
                <br><br>
                <b>Seu cadastro foi realizado, <br> seja bem-vindo!</b> 
                <br>
            
                <br><br>

                <input value="Login" type="submit" name="submit" id="submit">

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
