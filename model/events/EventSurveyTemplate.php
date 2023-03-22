<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';

class EventSurveyTemplate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'type' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'templateJson' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'jsontemplates';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = [ 'type', 'id' ];
    protected array $setPrimaryKeysValue = ['type'];

    public const TYPES_AND_VALUES =
    [
        'fiveStarRating' =>
        [
            '0' => 'Sem resposta',
            '1' => '1 - Muito Ruim',
            '2' => '2 - Ruim',
            '3' => '3 - Regular',
            '4' => '4 - Bom',
            '5' => '5 - Excelente'
        ],

        'yesNo' =>
        [
            '0' => 'Não',
            '1' => 'Sim'
        ],

        'checkList' => [ '__checkList' => true ],
        'textArea' => [ '__freeText' => true ],
        'shortText' => [ '__freeText' => true ]
    ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAllQuestionsAndPossibleValues(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('templateJson');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.type = 'eventsurvey' ");

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        $output = [];

        $getQuestionTitleAndValues = function(array $questionBlock, array &$output)
        {
            foreach ($questionBlock as $quest)
            {
                $possibleValues = [];
                
                $typeDef = self::TYPES_AND_VALUES[$quest->type];

                if (!empty($typeDef['__checkList']))
                {
                    foreach ($quest->checkList as $opt)
                        $possibleValues[$opt->name] = $opt->name;
                }
                else if (!empty($typeDef['__freeText']))
                { 
                }
                else
                {
                    foreach ($typeDef as $val => $label)
                        $possibleValues[$val] = $label;
                }

                $output[$quest->title] =  array_unique(array_merge($output[$quest->title] ?? [], $possibleValues));
            }
        };

        foreach ($drs as $dr)
        {
            $decoded = json_decode($dr['templateJson']);
            
            $getQuestionTitleAndValues($decoded->head, $output);
            $getQuestionTitleAndValues($decoded->body, $output);
            $getQuestionTitleAndValues($decoded->foot, $output);
        }
        
        return $output;
    }

    public static function checkIfValueIsWithinArray(array $answeredQuestion, array $expectedValues) : bool
    {
        $typeDef = self::TYPES_AND_VALUES[$answeredQuestion['type']];

        if (!empty($typeDef['__checkList']))
        {
            foreach ($answeredQuestion['checkList'] as $opt)
                if (in_array($opt['name'], $expectedValues) && isset($opt['value']) && $opt['value'] == "1")
                    return true;
        }
        else if (!empty($typeDef['__freeText']))
        { 
            if (!empty($answeredQuestion['value']) && in_array('{não em branco}', $expectedValues))
                return true;

            foreach ($expectedValues as $expVal)
                if (mb_strpos($answeredQuestion['value'], $expVal) !== false)
                    return true;
        }
        else
        {
            if (in_array($answeredQuestion['value'], $expectedValues))
                return true;
        }

        return false;
    }
}