<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Základ databázových modelů
 */


abstract class DbModel extends \Nette\Object
{
    
   private function __construct()
   {
   }

  
   public function countRows()                                                                                                    // Předání celkového počtu řádků posledního dotazu
   {
      return $this->db->fetchSingle('SELECT FOUND_ROWS()');
   }

}
