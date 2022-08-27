<?php
require_once("mailConfigs.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
//require_once __DIR__ . '/../PHPMailer/src/Exception.php';
//require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
//require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

function sendEmailProfessorOTPForSignature($oneTimePassword, $professorEmail, $professorName, $docNamesToSign)
{
    $configs = getMailConfigs();

    $mail = new PHPMailer();
    // DEFINI��O DOS DADOS DE AUTENTICA��O - Voc� deve alterar conforme o seu dom�nio!
    $mail->IsSMTP(); // Define que a mensagem ser� SMTP
    $mail->Host = $configs['host']; // Seu endere�o de host SMTP
    $mail->SMTPAuth = true; // Define que ser� utilizada a autentica��o -  Mantenha o valor "true"
    $mail->Port = $configs['port']; // Porta de comunica��o SMTP - Mantenha o valor "587"
    $mail->SMTPSecure = false; // Define se � utilizado SSL/TLS - Mantenha o valor "false"
    $mail->SMTPAutoTLS = true; // Define se, por padr�o, ser� utilizado TLS - Mantenha o valor "false"
    $mail->Username = $configs['username']; // Conta de email existente e ativa em seu dom�nio
    $mail->Password = $configs['password']; // Senha da sua conta de email
    // DADOS DO REMETENTE
    $mail->Sender = $configs['sender']; // Conta de email existente e ativa em seu dom�nio
    $mail->From = $configs['sender']; // Sua conta de email que ser� remetente da mensagem
    $mail->FromName = "SisEPI - Sistema de Informações da Escola do Parlamento de Itapevi"; // Nome da conta de email
    // DADOS DO DESTINAT�RIO
    $mail->AddAddress($professorEmail, $professorName); // Define qual conta de email receber� a mensagem

    // Defini��o de HTML/codifica��o
    $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
    $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
    // DEFINI��O DA MENSAGEM
    $mail->Subject  = "SisEPI - Senha para assinatura de documentação"; // Assunto da mensagem

    ob_start();
    $__VIEW = 'professorSignDocOTPMessage.view.php';
    require_once (__DIR__ . '/emailBaseBody.view.php');
    $emailBody = ob_get_clean();
    ob_end_clean();

    $mail->Body .= $emailBody;
    
    // ENVIO DO EMAIL
    $sent = $mail->Send();

    // Limpa os destinat�rios e os anexos
    $mail->ClearAllRecipients();

    // Exibe uma mensagem de resultado do envio (sucesso/erro)
    if ($sent) {
        return true;
    } else {
        throw new Exception("Não foi possível enviar o e-mail! Detalhes do erro: " . $mail->ErrorInfo);
    }
}
?>