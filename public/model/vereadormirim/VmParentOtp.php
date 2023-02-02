<?php
//Public

namespace Model\VereadorMirim;

use Model\VereadorMirim\VmParent;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DataEntity;
use DataProperty;
use mysqli;
use SqlSelector;
use DateTime;

require_once __DIR__ . '/../../../model/DataEntity.php';
require_once __DIR__ . '/../../../model/exceptions.php';
require_once __DIR__ . '/../../../model/vereadormirim/VmParent.php';
require_once __DIR__ . "/../../../includes/Mail/mailConfigs.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

class VmParentOtp extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'vmParentId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'oneTimePassword' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'expiryDateTime' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'vereadormirimparentotps';
    protected string $formFieldPrefixName = 'vmparentotps';
    protected array $primaryKeys = ['id'];

    protected string $rawOtp = "";
    
    public ?VmParent $vmParent;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new VmParentOtp();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    private function loadParentFromEmail(mysqli $conn, string $email)
    {
        $getter = new VmParent();
        $getter->setCryptKey($this->encryptionKey);
        $getter->email = $email;

        $this->vmParent = $getter->getSingleByEmail($conn);
    }

    public function setUp(mysqli $conn, string $email) : string
    {
        $this->loadParentFromEmail($conn, $email);

        $otp = (string)mt_rand(100000, 999999);
        $otpHash = password_hash($otp, PASSWORD_DEFAULT);

        $this->properties->vmParentId->setValue($this->vmParent->id);
        $this->properties->oneTimePassword->setValue($otpHash);

        return $this->rawOtp = $otp;
    }

    public function verify(mysqli $conn, string $givenOtp) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.*");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.id = ? ");
        $selector->addValue('i', $this->id);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (empty($dr))
            throw new Exception('Não há senha temporária registrada. Tente gerar uma nova.');

        $vmParOtp = $this->newInstanceFromDataRow($dr);

        $expires = new DateTime($vmParOtp->expiryDateTime);
        $now = new DateTime('now');

        if ($now >= $expires)
            throw new Exception('Senha expirada! Tente gerar uma nova.');

        $passed = password_verify($givenOtp, $vmParOtp->oneTimePassword);
        
        return [ 'passed' => $passed, 'vmParentOtp' => $vmParOtp ];
    }

    public function clearAllFromParent(mysqli $conn) : bool
    {
        $query = "DELETE FROM {$this->databaseTable} WHERE {$this->databaseTable}.vmParentId = ? ";
        $stmt = $conn->prepare($query);
        $vmPId = $this->properties->vmParentId->getValue();
        $stmt->bind_param('i', $vmPId);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function clearExpired(mysqli $conn)
    {
        $query = "DELETE FROM {$this->databaseTable} WHERE {$this->databaseTable}.expiryDateTime <= ? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', date('Y-m-d H:i:s'));
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $date = new DateTime();
        $date->modify('+15 minutes');

        $this->properties->expiryDateTime->setValue($date->format('Y-m-d H:i:s'));

        $this->clearAllFromParent($conn);
        return 0;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        $this->sendEmail();
        return $insertResult;
    }

    public function sendEmail()
    {
        if (empty($this->rawOtp)) throw new Exception("Tentativa de enviar OTP para responsável de vereador mirim sem OTP preparada.");
        if (empty($this->vmParent)) throw new Exception("Tentativa de enviar OTP para responsável de vereador mirim sem objeto 'VmParent' carregado.");

        $configs = getMailConfigs();
        $oneTimePassword = $this->rawOtp;
        $parent = $this->vmParent;
        $vmParentName = $parent->name;

        $mail = new PHPMailer();
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
        $mail->AddAddress($parent->email, $parent->name); // Define qual conta de email receber� a mensagem
    
        // Defini��o de HTML/codifica��o
        $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
        $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
        // DEFINI��O DA MENSAGEM
        $mail->Subject  = "SisEPI - Senha de acesso ao Painel do Responsável"; // Assunto da mensagem
    
        ob_start();
        $__VIEW = 'vmParentLoginOTPMessage.view.php';
        require_once (__DIR__ . '/../../../includes/Mail/emailBaseBody.view.php');
        $emailBody = ob_get_clean();
        ob_end_clean();
    
        $mail->Body .= $emailBody;

        $sent = $mail->Send();
        $mail->ClearAllRecipients();
    
        if ($sent) {
            return true;
        } else {
            throw new Exception("Não foi possível enviar o e-mail! Detalhes do erro: " . $mail->ErrorInfo);
        }
    }
    
}