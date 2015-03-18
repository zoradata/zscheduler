<?php
/**
 * Z-Scheduler
 *
 * Last revison: 18.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
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
     $sql = $command . $data['database_name'] . '.' . $data['name'] . "\n";
     $sql .= 'ON SCHEDULE ';
     if ($data['repeat'])
     {
        $sql .= 'EVERY ' . $data['interval'] . ' ' . $data['unit'] . "\n";
        if ($data['start'] != NULL)
           $sql .= 'STARTS ' . $data['start'] . "\n";
        if ($data['end'] != NULL)
           $sql .= 'ENDS ' . $data['start'] . "\n";
     }
     else
     {
        $sql .= 'AT ' . $data['start'] . "\n";
     }
     if ($data['comment'] != NULL)
        $sql .= 'COMMENT \'' . $data['comment'] . "'\n";
     $sql .= 'DO ' . $data['sql'] . "\n";
     return $sql;
   }
   
}
