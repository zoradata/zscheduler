<?php
/**
 * Z-Scheduler
 *
 * Last revison: 17.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Základ databázových modelů
 */


abstract class DbModel extends \Nette\Object
{
    
   private function __construct()
   {
   }

  
   public static function countRows()                                                                                             // Předání celkového počtu řádků posledního dotazu
   {
      return dibi::fetchSingle('SELECT FOUND_ROWS()');
   }

}
