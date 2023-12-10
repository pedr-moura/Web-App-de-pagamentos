<?php
    session_start();

    unset($_SESSION['email']);
    unset($_SESSION['telefone']);

    header('Location: login.php');

?>