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
        th{
            word-wrap: break-word;
        }
        .tabelaacesso{
            
            left: 50%;
            
            
        }
        .boxfuncoes{
            position: absolute;
            
            width: 70%;
            left: 5%;
            top: 2.5%;
        }
.botaocobrar{
margin-right: 15px;
border: none;
background-color: rgb(116, 240, 116);
color: darkolivegreen;
padding: 15px;
border-radius: 30px;
right: 0%;
    
}
.botaoexcluir{
    
    margin-right: 15px;
    border: none;
    background-color: rgb(116, 240, 116);
    color: darkolivegreen;
    padding: 15px;
    border-radius: 30px;

}
.botaoeditar{
    
    margin-right: 15px;
    border: none;
    background-color: rgb(116, 240, 116);
    color: darkolivegreen;
    padding: 15px;
    border-radius: 30px;

}
.botaopix{
    
    
    margin-right: 15px;
    border: none;
    background-color: rgb(0, 100, 0);
    color: white;
    padding: 15px;
    border-radius: 30px;
    

}

.botaopixedit{
    
    
    margin-right: 15px;
    border: none;
    background-color: rgb(215, 241, 68);
    color: black;
    padding: 15px;
    border-radius: 30px;
    

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
        <h2>PIX</h2>
        <div class="email">
            <?php
            echo "<h1><b> $logado</h1>";
            ?>
        </div>
    </div>

    <div class="box-search">
        <input class="pesquisar" type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button class="botao-pesquisar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
    </div>

    
    <div class="boxfuncoes">
            <a class="botaocobrar" href="sisAdmCobranca.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                </svg>
            </a>

            <a class="botaoexcluir" href="sisAdmExc.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
            </a>
            <a class="botaoeditar" href="sisAdmEdit.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </a>
            <a class="botaopix" href="sisAdm.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-fill" viewBox="0 0 16 16">
                <path d="M3.904 1.777C4.978 1.289 6.427 1 8 1s3.022.289 4.096.777C13.125 2.245 14 2.993 14 4s-.875 1.755-1.904 2.223C11.022 6.711 9.573 7 8 7s-3.022-.289-4.096-.777C2.875 5.755 2 5.007 2 4s.875-1.755 1.904-2.223Z"/>
                <path d="M2 6.161V7c0 1.007.875 1.755 1.904 2.223C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777C13.125 8.755 14 8.007 14 7v-.839c-.457.432-1.004.751-1.49.972C11.278 7.693 9.682 8 8 8s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972"/>
                <path d="M2 9.161V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13s3.022-.289 4.096-.777C13.125 11.755 14 11.007 14 10v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972"/>
                <path d="M2 12.161V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972"/>
                </svg>
            </a>
            <a class="botaopixedit" href="editCobranca.php?id=1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-fill-gear" viewBox="0 0 16 16">
                <path d="M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1"/>
                <path d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12.31 12.31 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.552 4.552 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.507 4.507 0 0 1-1.3-1.905m3.631-4.538c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                </svg>
            </a>
    </div>

    <div class="tabelaacesso m-5">
        <table class="table">
            <thead>
                <tr>
                    
                    <th scope="col">Tempo Limite</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Info Pix</th>
                    <th scope="col">Chave</th>

                    <!-- Adiciona outras colunas da tabela dadospix conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    
                    echo "<td>" . $user_data['expiracao'] . "</td>";
                    echo "<td>" . $user_data['valor_original'] . "</td>";
                    echo "<td>" . $user_data['solicitacao_pagador'] . "</td>";
                    echo "<td>" . $user_data['chave'] . "</td>";
                    // Adiciona outras células da tabela dadospix conforme necessário
                    echo "</tr>";
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
