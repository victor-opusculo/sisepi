<?php

use SisEpi\Model\Database\Connection;

require_once __DIR__ . '/../includes/logEngine.php';
require_once __DIR__ . '/../includes/URL/URLGenerator.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_POST["btnsubmitSubmitTest"]))
{
	$messages = [];
	$approved = false;
    $conn = Connection::create();
	try
	{
        $compTest = new \SisEpi\Model\Events\EventCompletedTest();
        $compTest->setCryptKey(Connection::getCryptoKey());
        $compTest->fillPropertiesFromFormInput($_POST);
        [ 'event' => $event, 'studentInfos' => $studentInfos ] = $compTest->createFromTestPage($conn);

        $insertResult = $compTest->save($conn);
		if($insertResult['newId'])
		{
            [ $approved, $percent, $minRequired ] =  $compTest->isApproved();

            $formattedPercent = number_format($percent, 0, ',', '');
            if ($approved)
            {
			    $messages[] = "Avaliação enviada! Você foi aprovado(a) com {$formattedPercent}% de acerto!";
                $approved = true;
            }
            else
                $messages[] = "Avaliação enviada! Infelizmente você não foi aprovado(a) porque acertou {$formattedPercent}% dos {$minRequired}% requeridos.";

			writeLog("Questionário de avaliação enviado. Nome: {$studentInfos->name}. Evento Id: {$event->id}");

            try
            {
                $notification = new \SisEpi\Model\Notifications\Classes\EventTestSentNotification
                ([
                    'studentName' => $studentInfos->name,
                    'event' => $event,
                    'testId' => $insertResult['newId']
                ]);
                $notification->push($conn);
            }
            catch (Exception $e) { }
		}
		else
			throw new Exception("Não foi possível gravar as respostas da avaliação.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao enviar questionário de avaliação: {$e->getMessage()} (E-mail: " . $_POST['hidEmail'] . ". Evento id: " . $_POST['eventcompletedtests:hidEventId'] . ")");
	}
    finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, [ 'eventId' => $_GET['eventId'], 'email' => $_GET['email'], 'approved' => $approved, 'filled' => 1, 'goTo' => $_GET['goTo'] ?? '', 'messages' => $messagesString ]), true, 303);
	
}