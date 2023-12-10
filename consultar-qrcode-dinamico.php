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

require __DIR__.'/app/Pix/Api.php';
require __DIR__.'/app/Pix/Payload.php';
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config-pix.php';



use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;


//INSTANCIA DA API PIX
$obApiPix = new Api(API_PIX_URL,
                    API_PIX_CLIENT_ID,
                    API_PIX_CLIENT_SECRET,
                    API_PIX_CERTIFICATE);

// Recupere o valor salvo pelo usuário na coluna 'identificador_pix'
// Você deve implementar a lógica adequada para obter esse valor do banco de dados
$conexao = new mysqli('localhost', 'root', '', 'formulario-uemasul');

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

$querySelect = "SELECT identificador_pix FROM usuarios WHERE email = ?";
$stmtSelect = $conexao->prepare($querySelect);

if (!$stmtSelect) {
    die("Erro de preparação da consulta: " . $conexao->error);
}

$stmtSelect->bind_param('s', $logado);

if (!$stmtSelect->execute()) {
    die("Erro ao executar a consulta: " . $stmtSelect->error);
}

$stmtSelect->bind_result($identificadorPix);

if ($stmtSelect->fetch()) {
    // Agora, $identificadorPix contém o valor salvo pelo usuário
    $stmtSelect->close();
    $conexao->close();

    // RESPOSTA DA REQUISIÇÃO DE CONSULTA
    $response = $obApiPix->consultCob($identificadorPix);

    // VERIFICA A EXISTÊNCIA DO ITEM 'LOCATION'
    if (!isset($response['location'])) {
        echo 'Problemas ao consultar Pix dinâmico';
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;
    }

    // ... (restante do seu código)

    // Imprima a resposta da API para verificar
    print_r($response);

    // DEBUG DOS DADOS DO RETORNO
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    exit;
} else {
    $stmtSelect->close();
    $conexao->close();
    echo 'Identificador Pix não encontrado para o usuário';
    exit;
}
