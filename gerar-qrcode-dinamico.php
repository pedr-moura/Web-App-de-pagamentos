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

// Restante do seu código...

require __DIR__.'/app/Pix/Api.php';
require __DIR__.'/app/Pix/Payload.php';
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config-pix.php';

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// Função para gerar um valor único
function gerarValorUnico() {
    // Combinação de timestamp e um identificador único
    return 'CAMVET' . time() . uniqid();
}

// Gere um valor único
$valorUnico = gerarValorUnico();

// Crie a instância do objeto Api
$obApiPix = new Api('https://pix.api.efipay.com.br',
    'Client_Id_aac94a171bb46c6d2004481008113eeb034c180a',
    'Client_Secret_4ae9b88e7661b11355c9a2e19ba6a43ea4932458',
    __DIR__.'/files/certificates/producao-529016-CAMVET2024.pem');

// Defina o array de request
$request = [
    'calendario' => [
        'expiracao' => 3600
    ],
    'devedor' => [
        'cpf' => '12345678909',
        'nome' => 'Francisco da Silva'
    ],
    'valor' => [
        'original' => '0.01'
    ],
    'chave' => 'yeehtreecko@gmail.com',
    'solicitacaoPagador' => 'Pagamento do pedido 123'
];

// Chame o método createCob com o valor único gerado
$response = $obApiPix->createCob($valorUnico, $request);

// Imprima a resposta da API para verificar
print_r($response);

if (!isset($response['location']) || !isset($response['txid'])) {
    echo 'Problemas ao gerar pix dinâmico';

    echo "<pre>";
    print_r($response);
    echo "</pre>";
    exit(); // Certifique-se de encerrar a execução após exibir a mensagem de erro
}

// Obtendo o txid gerado pelo usuário
$txidUsuario = $response['txid'];

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

// INSTANCIA PRINCIPAL PAYLOAD PIX

$obPayLoad = (new Payload)  
                            
                            ->setMerchantName('CAMVET')
                            ->setMerchantCity('IMPERATRIZ')
                            ->setAmount($response['valor']['original'])
                            ->setTxid($response['txid'])
                            ->setUrl($response['location'])
                            ->setUniquePayment(true);

// CODIGO DE PAGAMENTO PIX
$payLoadQrCode = $obPayLoad->getPayload();

// QR CODE
$obQrCode = new QrCode($payLoadQrCode);

// IMAGEM DO QR CODE
$image = (new Output\Png)->output($obQrCode,400);


// ... Código anterior ...

// QR CODE
$obQrCode = new QrCode($payLoadQrCode);

// IMAGEM DO QR CODE
$imageBase64 = base64_encode((new Output\Png)->output($obQrCode, 400));

// Adicione o valor do QR code e a imagem à sessão
$_SESSION['qr_code_value'] = $payLoadQrCode;
$_SESSION['qr_code_image'] = $imageBase64;

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
