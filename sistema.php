<?php
    session_start();
    //print_r($_SESSION);

    //verificação email telefone
    if((!isset($_SESSION['email']) == true) and (!isset ($_SESSION['telefone']) == true))
    {
        unset($_SESSION['email']);
        unset($_SESSION['telefone']);

        header('Location: login.php');
    }
    
    $logado = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Central do Aluno</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .email{
            font-size: 60%;
        }
    </style>

</head>
<body>
    <div class="navbar">
        <nav class="navbar-light">
            <form class="container-fluid justify-content-start">

                <a href="login.php">Encerrar acesso</a>

            </form>
        </nav>
    </div>

    <div class="titulo">

    <h2>Bem vindo!</h2>

        <div class="email">
            <?php
                echo "<h1><b> $logado</u></h1>";
            ?>
        </div>

    </div>
    <span> <?php echo isset($_GET['nome']) ? $_GET['nome'] : ''; ?></span>
</body>
</html>