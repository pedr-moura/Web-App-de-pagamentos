<?php

    include_once('config.php');

    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $sexo = $_POST['genero'];
        $data_nasc = $_POST['data_nascimento'];

        $sqlUpdate = "UPDATE usuarios SET nome= '$nome', email= '$email', telefone= '$telefone', sexo= '$sexo', data_nasc= '$data_nasc'
        WHERE id='$id' ";

        $result = $conexao->query($sqlUpdate);
    }

    header('Location: sisAdmPix.php')
        
    
?>