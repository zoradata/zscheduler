<?php
/**
 * Z-Scheduler
 *
 * Last revison: 17.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Vytvoření příkazů pro událost
 */


class Statement extends \Nette\Object
{
    

   private function __construct();

           
   public static function create($data)
   {
     return self::statement('CREATE EVENT ', $data);;
   }

   
   public static function alter($data)
   {
     return self::statement('ALTER EVENT ', $data);;
   }

   
   private static function statement($sql, $data)
   {
     $output .= $sql . $data['database_name'] . '.' . $data['name'] . "\n";
     $output .= 'ON SCHEDULE ';
     if ($data['repeat'])
     {
        $output .= 'EVERY ' . $data['interval'] . ' ' . $data['unit'] . "\n";
        if ($data['start'] != NULL)
           $output .= 'STARTS ' . $data['start'] . "\n";
        if ($data['end'] != NULL)
           $output .= 'ENDS ' . $data['start'] . "\n";
     }
     else
     {
        $output .= 'AT ' . $data['start'] . "\n";
     }
     if ($data['comment'] != NULL)
        $output .= 'COMMENT \'' . $data['comment'] . "'\n";
     $output .= 'DO ' . $data['sql'] . "\n";
     return $output;
   }
   
}
