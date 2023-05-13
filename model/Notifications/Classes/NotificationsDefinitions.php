<?php

namespace SisEpi\Model\Notifications\Classes;

require_once __DIR__ . '/../../../vendor/autoload.php';
define('DEF_FILE', __DIR__ . '/definitions.json');

abstract class NotificationsDefinitions
{
    private static function generateNewDefinitionsFile() : array
    {
        if (is_file(DEF_FILE)) unlink(DEF_FILE);

        $classesFiles = glob(__DIR__ . '/*Notification.php', GLOB_NOESCAPE);

        $defsArray = [];
        $conn = \SisEpi\Model\Database\Connection::get();
        foreach ($classesFiles as $file)
        {
            require_once $file;
            $class = '\\SisEpi\\Model\\Notifications\\Classes\\' . substr(basename($file), 0, strlen(basename($file)) - 4);
            
            $instance = new $class();
            $instance->save($conn);

            if (!isset($defsArray[$instance->module])) 
                $defsArray[$instance->module] = [];
                
            $defsArray[$instance->module][$instance->id] = [ 'className' => $class, 'name' => $instance->name, 'conditionsComponentName' => $class::CONDITIONS_COMPONENT_NAME ];
        }
        file_put_contents(DEF_FILE, json_encode($defsArray));
        return $defsArray;
    }

    public static function get() : array
    {
        if (is_file(DEF_FILE))
        {
            if (time() - filemtime(DEF_FILE) > 86400)
                return self::generateNewDefinitionsFile();
            else
                return json_decode(file_get_contents(DEF_FILE), true);
        }
        else
            return self::generateNewDefinitionsFile();
    }
}