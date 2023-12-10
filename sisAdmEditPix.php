<?php
session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['telefone']) || empty($_SESSION['telefone'])) {
    header('Location: login.php');
    exit();
}

// Verifique se o usuário tem permissão de administrador
include_once('config.php');
$email = $_SESSION['email'];
$telefone = $_SESSION['telefone'];

$sql = "SELECT * FROM usuarios WHERE email = '$email' AND telefone = '$telefone' AND emailadm = '$email'";
$result = $conexao->query($sql);

if (mysqli_num_rows($result) < 1) {
    header('Location: sistema.php'); // Ou redirecione para outra página autorizada
    exit();
}

// Inclua o autoload do Composer
require __DIR__.'/vendor/autoload.php';

$logado = $_SESSION['email'];

if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    // Ajustando a condição WHERE para pesquisar nas colunas corretas da tabela 'dadospix'
    $sql = "SELECT * FROM dadospix WHERE id LIKE '%$data%' OR devedor_nome LIKE '%$data%' OR devedor_cpf LIKE '%$data%' ORDER BY id DESC";
} else {
    $data = '';
    $sql = "SELECT * FROM dadospix ORDER BY id DESC";
}

$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central ADMo</title>
    <link rel="stylesheet" href="styleADM.css">
    <style>
        .cfgvoltar a{
            position: absolute;
            left: 20%;
            top: 20%;
            background: darkgreen;
            border: 3px solid rgb(0, 161, 0);
            padding: 10px;
            border-radius: 10px;
            color: white;
        }
        .tabelaacesso{
            word-wrap: break-word;
            font-size: 2.3vw;
        }
    </style>
</head>

<body>
    <div class="navbar">

        <div class="cfgvoltar">
        <a href="sisAdm.php">Voltar</a>
        </div>
        <nav class="navbar-light">
            <form class="container-fluid justify-content-start">
                
                <a href="sisAdmPix.php" class="x">x</a>
                <a href="login.php">Encerrar acesso</a>
            </form>
        </nav>
    </div>

    <div class="titulo">
        <h2>Painel do ADM</h2>
        <div class="email">
            <?php
            echo "<h1><b> $logado</h1>";
            ?>
        </div>
    </div>

    <div class="box-search">
        <input value="<?php echo $data ?>" class="pesquisar" type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="botao-pesquisar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
        </button>
    </div>

    <div class="tabelaacesso m-5">
        <table class="table">
            <thead>
                <tr>
                    
                    <th scope="col">Tempo Limite</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Pix</th>
                    <th scope="col">Info Pix</th>

                    <!-- Adiciona outras colunas da tabela dadospix conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    
                    echo "<td>" . $user_data['expiracao'] . "</td>";
                    echo "<td>" . $user_data['devedor_cpf'] . "</td>";
                    echo "<td>" . $user_data['devedor_nome'] . "</td>";
                    echo "<td>" . $user_data['valor_original'] . "</td>";
                    echo "<td>" . $user_data['chave'] . "</td>";
                    echo "<td>" . $user_data['solicitacao_pagador'] . "</td>";
                    // Adiciona outras células da tabela dadospix conforme necessário
                    echo "</tr>";

                    echo "<td>
                    
                        <a class='btn btn-primary botao-editar' href='editCobranca.php?id=$user_data[id]'>

                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                        </svg>

                        </a>


                        
                    </td>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        var search = document.getElementById('pesquisar');

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                searchData();
            }
        });

        function searchData() {
            window.location = 'sisAdm.php?search=' + search.value;
        }
    </script>
</body>

</html>
