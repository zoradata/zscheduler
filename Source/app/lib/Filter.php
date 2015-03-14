<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Knihovní třída pro podporu filtrování a třídění v SQL dotazech
 */

namespace BaseModule;
 

class Filter extends \Nette\Object
{
   
   private function __construct() 
   {
   }

   
   /**
    * Pole logických hodnot pro filtr
    * @return array
    */
   public static function getLogical()
   {
      return array(''=>'', '1'=>'Ano', '0'=>'Ne');
   }
   
   
   /**
    * Pole paramtrů hodnoty filtru
    * @return array
    */
   public static function getParam()
   {
      return array('LIKE%' => 'Začíná',
                   '%LIKE%' => 'Obsahuje',
                   '%LIKE' => 'Končí',
                   'LIKE' => 'Maska',
                   '=' => 'Je rovno',
                   '>' => 'Je větší',
                   '>=' => 'Je větší nebo rovno',
                   '<' => 'Je menší',
                   '<=' => 'Je menší nebo rovno',
                   '!=' => 'Není rovno',
                   'NULL' => 'Je prázdné',
                   'NOTNULL' => 'Není prázdné');
   }

   
   /**
    * Pole třídění
    * @return array
    */
   public static function getOrder()
   {
      return array('ASC' => 'Vzestupně',
                   'DESC' => 'Sestupně');
   }

}
