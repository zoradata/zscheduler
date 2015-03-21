<?php
/**
 * Z-Scheduler
 *
 * Last revison: 21.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Vytvoření příkazů pro událost
 */


class Statement extends \Nette\Object
{
    

   private function __construct()
   {
   }

           
   public static function create($data)
   {
     return self::statement('CREATE EVENT ', $data);;
   }

   
   public static function alter($data)
   {
     return self::statement('ALTER EVENT ', $data);;
   }

   
   private static function statement($command, $data)
   {
     $sql = $command . $data['database_name'] . '.' . $data['name'] . "\r\n";
     $sql .= 'ON SCHEDULE ';
     if ($data['repeated'])
     {
        $sql .= 'EVERY ' . $data['interval_value'] . ' ' . $data['unit'] . "\r\n";
        if ($data['start'] != NULL)
           $sql .= 'STARTS \'' . $data['start'] . "'\r\n";
        if ($data['end'] != NULL)
           $sql .= 'ENDS \'' . $data['end'] . "'\r\n";
     }
     else
     {
        if ($data['run_at'] != NULL)
           $sql .= 'AT \'' . $data['run_at'] . "'\r\n";
        else $sql .= 'AT now()' . "\r\n";
     }
     if ($data['preserve'])
     {
        $sql .= 'ON COMPLETION NOT PRESERVE' . "\r\n";
     }
     else
     {
        $sql .= 'ON COMPLETION PRESERVE' . "\r\n";
     }
     switch ($data['status'])
     {
        case 'ENABLED':
           $sql .= 'ENABLE' . "\r\n";
           break;
        case 'SLAVESIDE_DISABLED':
           $sql .= 'DISABLE ON SLAVE' . "\r\n";
           break;
        case 'DISABLED':
           $sql .= 'DISABLE' . "\r\n";
           break;
        default:
     }
     if ($data['comment'] != NULL)
        $sql .= 'COMMENT \'' . $data['comment'] . "'\r\n";
     $sql .= 'DO ' . $data['sql_command'] . "\r\n";
     return $sql;
   }
   
}
