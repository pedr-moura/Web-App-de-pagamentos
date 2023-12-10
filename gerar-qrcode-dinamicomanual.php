<?php
// Inicie a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifique se 'email' está definido na sessão
if (!isset($_SESSION['email'])) {
    // Redirecione para a página de login
    header('Location: login.php');
    exit(); // Certifique-se de encerrar a execução após o redirecionamento
}

// Obtenha o e-mail do usuário logado
$logado = $_SESSION['email'];

// Inicie a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifique se 'email' está definido na sessão
if (!isset($_SESSION['email'])) {
    // Redirecione para a página de login
    header('Location: login.php');
    exit(); // Certifique-se de encerrar a execução após o redirecionamento
}

$logado = $_SESSION['email'];

// Função para obter o 'nome' do usuário a partir do ID
function obterNomeUsuarioPorID($idUsuario)
{
    // Implemente a lógica para buscar o nome do usuário do banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'formulario-uemasul');

    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    $query = "SELECT nome FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($query);

    if (!$stmt) {
        die("Erro de preparação da consulta: " . $conexao->error);
    }

    $stmt->bind_param('i', $idUsuario);

    if (!$stmt->execute()) {
        die("Erro ao executar a consulta: " . $stmt->error);
    }

    $stmt->bind_result($nomeUsuario);

    if ($stmt->fetch()) {
        $stmt->close();
        $conexao->close();
        return $nomeUsuario;
    } else {
        $stmt->close();
        $conexao->close();
        return null;
    }
}

// Obtém o 'nome' do usuário
$nomeUsuario = obterNomeUsuarioPorID($logado);

// Resto do seu código...

require __DIR__.'/app/Pix/Api.php';
require __DIR__.'/app/Pix/Payload.php';
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config-pix.php';

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$obApiPix = new Api('https://pix.api.efipay.com.br',
    'Client_Id_aac94a171bb46c6d2004481008113eeb034c180a',
    'Client_Secret_4ae9b88e7661b11355c9a2e19ba6a43ea4932458',
    __DIR__.'/files/certificates/producao-529016-CAMVET2024.pem');

// Valor manual para [txid]
$txidUsuario = 'WDEV1234123412340000000007';

// Atualizando o banco de dados com o txid
$conexao = new mysqli('localhost', 'root', '', 'formulario-uemasul');

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

$queryUpdate = "UPDATE usuarios SET identificador_pix = ? WHERE email = ?";
$stmtUpdate = $conexao->prepare($queryUpdate);

if (!$stmtUpdate) {
    die("Erro de preparação da consulta: " . $conexao->error);
}

$stmtUpdate->bind_param('ss', $txidUsuario, $logado);

if (!$stmtUpdate->execute()) {
    die("Erro ao executar a atualização: " . $stmtUpdate->error);
}

$stmtUpdate->close();
$conexao->close();

//INSTANCIA PRINCIPAL PAYLOAD PIX
$obPayLoad = (new Payload)  ->setMerchantName('CAMVET')
                            ->setMerchantCity('IMPERATRIZ')
                            ->setAmount('0.01')
                            ->setTxid($txidUsuario) // Usando o valor manual para [txid]
                            ->setUrl('') // Você pode ajustar isso conforme necessário
                            ->setUniquePayment(true);

//CODIGO DE PAGAMENTO PIX
$payLoadQrCode = $obPayLoad->getPayload();

//QR CODE
$obQrCode = new QrCode($payLoadQrCode);

//IMAGEM DO QR CODE
$image = (new Output\Png)->output($obQrCode, 400);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Página</title>
    <!-- Seus estilos ou links para bibliotecas aqui -->
</head>
<body>

<h2>E-mail do Usuário: <?=$logado?></h2>

<h1>QR CODE PIX</h1>
<br>
<img src="data:image/png;base64, <?=base64_encode($image)?>" alt="">

<br><br>

Código dinâmico do Pix:<br>
<strong><?=$payLoadQrCode?></strong>

<!-- Seus scripts ou outros conteúdos HTML aqui -->

</body>
</html>
