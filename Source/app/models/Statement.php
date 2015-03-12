<?php
/**
 * Z-Event
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
     $sql = 'CREATE EVENT ' . $data['schema'] . '.' . $data['name'] . ' ';
     if ($data['repeat'])
     {
        $sql .= 'EVRY ' . $data['interval'] . '.' . $data['unit'] . ' ';
        if ($data['start'] != NULL)
           $sql .= 'STARTS ' . $data['start'] . ' ';
        if ($data['end'] != NULL)
           $sql .= 'ENDS ' . $data['start'] . ' ';
     }
     else
     {
        $sql .= 'AT ' . $data['start'] . ' ';
     }
     $sql .= 'COMMENT ' . $data['comment'] . ' ';
     $sql .= 'DO ' . $data['sql'] . ' ';
     return $sql;
   }
   
}
