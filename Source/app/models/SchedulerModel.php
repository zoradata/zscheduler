<?php
/**
 * Z-Scheduler
 *
 * Last revison: 18.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Databázový model pro proces plánovače
 */


class SchedulerModel extends \DbModel
{
    
    
   /**
    * Start plánovače
    */              
   public static function start()
   {
      dibi::query('SET GLOBAL event_scheduler = ON');
   }

   
   /**
    * Stop plánovače
    */              
   public static function stop()
   {
      dibi::query('SET GLOBAL event_scheduler = OFF');
   }

}
