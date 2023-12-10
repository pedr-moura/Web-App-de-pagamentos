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

include_once('config.php');

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['telefone'])) {
    include_once('config.php');
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND telefone = '$telefone'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['telefone']);
        header('Location: login.php');
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
}


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
        'expiracao' => 60
    ],
    'devedor' => [
        'cpf' => '12345678909',
        'nome' => 'Discente Uemasul'
    ],
    'valor' => [
        'original' => '0.01'
    ],
    'chave' => 'yeehtreecko@gmail.com',
    'solicitacaoPagador' => 'Pagamento do pedido 123'
];

// Chame o método createCob com o valor único gerado
$response = $obApiPix->createCob($valorUnico, $request);

// Obtendo o txid gerado pelo usuário
$txidUsuario = $response['txid'];
$statusPix = $response['status']; // Adiciona a linha para obter o status Pix

// Conectar ao banco de dados
$conexao = new mysqli('localhost', 'root', '', 'formulario-uemasul');

// Verifica se a conexão foi bem-sucedida
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Atualizando o banco de dados com o txid e o statusPix
$queryUpdate = "UPDATE usuarios SET identificador_pix = ?, status_pix = ? WHERE email = ?";
$stmtUpdate = $conexao->prepare($queryUpdate);

if (!$stmtUpdate) {
    die("Erro de preparação da consulta: " . $conexao->error);
}

$stmtUpdate->bind_param('sss', $txidUsuario, $statusPix, $logado); // Adiciona o statusPix ao bind_param

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
$image = (new Output\Png)->output($obQrCode, 400);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central do Aluno</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .boxtela{
            position: absolute;
            
            width: 50vw;
            left: 50%;
            top: 20%;
            justify-content: center;
            text-align: center;
            display: block;
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            transform: translate(-50%,0%);
           
            
        }

        .email {
            margin-top: 10vh;
            width: 100%;
            text-align: center;
            position: absolute;
            
        }
        .qrcode img {
        width: 80%; 
        max-width: 260px; 
        display: block;
        margin: 0 auto; 
        }

        .qrcode strong {
            justify-content: center;
            word-wrap: break-word;
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

    <div class="email">
                        <?php echo "<h1><b>$logado</b></h1>"; ?>
    </div>

    
 


        <div class="boxtela">
            
            

            <div class="qrcode">
                <br>
                <img src="data:image/png;base64, <?=base64_encode($image)?>" alt="">
                <br><br>
                Pix Copia-Cola:<br><br>
                <strong id="codigoPix"><?=$payLoadQrCode?></strong>
                <br><br>
                <button class="inputSubmit" onclick="copiarCodigoPix()">Copiar Código Pix</button>
            </div>
        </div>
    

    <script>
        function copiarCodigoPix() {
            var codigoPixElement = document.getElementById('codigoPix');
            var inputTemporario = document.createElement('textarea');
            inputTemporario.value = codigoPixElement.innerText;
            document.body.appendChild(inputTemporario);
            inputTemporario.select();
            inputTemporario.setSelectionRange(0, 99999);
            document.execCommand('copy');
            document.body.removeChild(inputTemporario);
            alert('Código Pix copiado com sucesso!');
        }
    </script>
</body>
</html>
