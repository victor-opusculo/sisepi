<?php
require_once "checkLogin.php";
require_once "../vendor/autoload.php";

$fileName = $_GET['file'];

$file = fopen(__DIR__ . DIRECTORY_SEPARATOR . $fileName, 'r');

$headers = fgetcsv($file, 255, ';');
$conn = \SisEpi\Model\Database\Connection::create();
$conn->begin_transaction();
$affectedRows = 0;
try
{
    $stmt = $conn->prepare('UPDATE librarycollection SET authorCode = ? WHERE id = ? ');
    echo ('<pre>');
    while (!feof($file))
    {
        [ $id, $author, $title, $authorCode ] = fgetcsv($file, 2000, ';');
        $stmt->bind_param('si', $authorCode, $id);
        $stmt->execute();

    }
    echo ('</pre>');
    $conn->commit();
    $affectedRows += $conn->affected_rows;
    echo "$affectedRows registros atualizados!" . PHP_EOL;
}
catch (mysqli_sql_exception $e)
{
    $conn->rollback();
    throw $e;
}
finally
{ 
    $conn->close(); 
    fclose($file);
}
echo "Terminado!";