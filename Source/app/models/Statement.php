<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Základ databázových modelů
 */


class Statement extends \Nette\Object
{
    
   protected $db;                                                                                                                 // Databázové připojení
   

   public function __construct($db)
   {
      $this->db = $db;
   }

  
   public static function create($data)
   {
     $sql = 'CREATE EVENT ' . $data['database_name'] . '.' . $data['name'] . "\n";
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
