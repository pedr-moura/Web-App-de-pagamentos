<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['telefone'])) {
    include_once('config.php');
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND telefone = '$telefone'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['telefone']);
        header('Location: form.php');
    } else {
        $row = mysqli_fetch_assoc($result);

        if ($email == $row['emailadm']) {
            // Se o email for encontrado na coluna 'emailadm'
            $_SESSION['email'] = $email;
            $_SESSION['telefone'] = $telefone;
            header('Location: sisAdm.php');
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['telefone'] = $telefone;
            header('Location: sistema.php');
        }
    }
} else {
    header('Location: login.php');
}
?>
