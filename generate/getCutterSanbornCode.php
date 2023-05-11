<?php

require_once "../includes/URL/URLGenerator.php";
require_once "checkLogin.php";

header('Content-Type: application/json; charset=utf-8');

final class NothingFitsException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}

function startsWith( $haystack, $needle ) 
{
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function firstNarrowTableByInitial(string $nameToFind, array $table) : array
{
    return array_filter($table, fn($item) => strcasecmp($item[0] ?? '***', $nameToFind[0] ?? "") === 0); 
}

function narrowTable(string $nameToFind, int $partialNameUpToIndex, array $table) : array
{
    if ($partialNameUpToIndex <= strlen($nameToFind) - 1)
    {
        $partialName = substr($nameToFind, 0, $partialNameUpToIndex + 1);
        $newTable = array_filter($table, function($item) use ($partialName) { $out = startsWith(strtolower($item[2] ?? '***'), $partialName); return $out; });

        $hasCloseMatch = array_filter($newTable, fn($item) => strcasecmp($item[2] ?? '***', $nameToFind) < 0);
        $hasCloseMatch = count($hasCloseMatch) > 0;

        if ($hasCloseMatch)
            return narrowTable($nameToFind, $partialNameUpToIndex + 1, $newTable);
        else
            return $table;
    }
    else
        return $table;
}

function selectBestFitName(string $nameToFind, array $table) : array
{
    $bestFit = null;

    foreach ($table as $item)
    {
        $compResult = strcasecmp($item[2] ?? '***', $nameToFind);

        if ($compResult < 0)
            $bestFit = $item;
        else if ($compResult === 0)
            return $item;
    }

    if (isset($bestFit))
        return $bestFit;
    else
        throw new NothingFitsException("Não foi encontrado código que se aproxime do nome especificado!"); 
}

function prepareName(string $name) : string
{
    $surnameFirst = "";
    if (mb_strpos($name, " ") !== false)
    {
        if (mb_strpos($name, ",") !== false)
            $surnameFirst = $name;
        else
        {
            $names = explode(' ', $name);
            $lastName = array_pop($names);
            $firstName = implode(' ', $names);
            $surnameFirst = "$lastName, $firstName";
        }
    }
    else
        $surnameFirst = $name;

    $normalized = Normalizer::normalize($surnameFirst, Normalizer::FORM_D);
    $withoutSpecialChars = mb_ereg_replace("[\u0300-\u036f]", "", $normalized);
    $trimmedLowerCase = trim(mb_strtolower($withoutSpecialChars));

    return $trimmedLowerCase;
}

function getAuthorCodeForUnknownAuthor(string $publicationTitle, array $entireTable) : array
{
    try
    {
        mb_ereg_search_init($publicationTitle, '^([oOAa]\s)?([Oo][Ss]\s)?([Aa][Ss]\s)?(.*)$');
        $result = mb_ereg_search_regs();

        if (!$result)
            throw new Exception("Erro no tratamento do título desta publicação.");
        
        [ $_matched, $_1, $_2, $_3, $titleWithoutArticles ] = $result;

        $preparedName = prepareName($titleWithoutArticles . ",");

        $firstNarrow = firstNarrowTableByInitial($preparedName, $entireTable);
        $narrowed = narrowTable($preparedName, 1, $firstNarrow);

        $fit = selectBestFitName($preparedName, $narrowed);
        return [...$fit, true ];
    }
    catch (NothingFitsException $e)
    {
        throw new NothingFitsException("Não foi possível gerar código de autor para autor desconhecido. Não foi encontrado código que se aproxime do título desta publicação.");
    }
    catch (Exception $e)
    {
        throw $e;
    }
}

function getAuthorCode(string $givenName, string $publicationTitle, array $entireTable) : array
{
    try
    {
        $preparedName = prepareName($givenName);

        $firstNarrow = firstNarrowTableByInitial($preparedName, $entireTable);
        $narrowed = narrowTable($preparedName, 1, $firstNarrow);

        $fit = selectBestFitName($preparedName, $narrowed);
        return [...$fit, false];
    }
    catch (NothingFitsException $e)
    {
        return getAuthorCodeForUnknownAuthor($publicationTitle, $entireTable);
    }
    catch (Exception $e)
    {
        throw $e;
    }
}


$exception = "";
$file = fopen( __DIR__ . '/../includes/cutter-sanborn-table.csv', 'r');
try
{
    $entireTable = array_fill(0, 12330, null);
    $lineCount = 0;
    while (!feof($file))
    {
        $line = fgetcsv($file, 255, ';');
        $entireTable[$lineCount++] = $line;
    }

    if (empty($_GET['name']) && empty($_GET['title']))
        throw new Exception("Nenhum nome ou título fornecido!");

    array_pop($entireTable);

    $givenName = $_GET['name'] ?? "";
    $givenTitle = $_GET['title'] ?? "";

    [ $initial, $code, $name, $unknownAuthor ] = getAuthorCode($givenName, $givenTitle, $entireTable);

    echo json_encode([ 'data' => 
    [ 
        'initial' => $initial,
        'code' => $code,
        'name' => $name,
        'unknownAuthor' => $unknownAuthor
    ]]);
}
catch (Exception $e)
{
    $exception = $e->getMessage();
}
finally { fclose($file); }

if ($exception)
    echo json_encode([ 'error' => $exception] );