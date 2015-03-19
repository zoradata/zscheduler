<?php
/**
 * Z-Scheduler
 *
 * Last revison: 18.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Databázový model pro události
 */


class EventModel extends \DbModel
{
    
    
   /**
    * Výpis verze databáze
    * @return string Verze databáze
    */              
   public static function dbVersion()
   {
      $sql = 'SELECT VERSION()';
      return dibi::fetchSingle($sql);
   }

   
   /**
    * Výpis stavu plánovače úloh
    * @return boolean Stav plánovače (TRUE = ON, FALSE = OFF)
    */              
   public static function schedulerStatus()
   {
      $sql = 'SELECT CASE WHEN @@event_scheduler = \'ON\' THEN 1 ELSE 0 END';
      return dibi::fetchSingle($sql);
   }

   
   /**
    * Výpis databází s počtu událostí v nich
    * @return array Pole záznamů
    */              
   public static function database($limit, $offset)
   {
      $sql = 'SELECT SQL_CALC_FOUND_ROWS D.SCHEMA_NAME database_name, 
                     NULLIF(SUM(CASE WHEN E.STATUS = \'ENABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'SLAVESIDE_DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled_on_slave
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME
              GROUP BY database_name
              ORDER BY database_name %ofs %lmt';
      return dibi::fetchAll($sql, $offset, $limit);
   }

   
   /**
    * Celkový počet událostí
    * @return array Záznam
    */              
   public static function databaseTotal()
   {
      $sql = 'SELECT NULLIF(SUM(CASE WHEN E.STATUS = \'ENABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'SLAVESIDE_DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled_on_slave
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME';
      return dibi::fetch($sql);
   }
   
   
   /**
    * Databáze pro HTML tag SELECT
    * @return array Pole pro HTML tag SELECT
    */              
   public static function selectDatabase()
   {
      $sql = 'SELECT SCHEMA_NAME database_id, SCHEMA_NAME database_name
              FROM INFORMATION_SCHEMA.SCHEMATA
              ORDER BY 1';
      return dibi::fetchPairs($sql);
   }


   /**
    * Výpis událostí
    * @return array Pole záznamů
    */              
   public static function event($db, $limit, $offset)
   {
      $sql = 'SELECT * FROM INFORMATION_SCHEMA.EVENTS
              WHERE EVENT_SCHEMA LIKE IFNULL(%sN, \'%\') ORDER BY EVENT_SCHEMA, EVENT_NAME %ofs %lmt';
      return dibi::fetchAll($sql, $db, $offset, $limit);
   }
   
      
   /**
    * Jednotky času pro HTML tag SELECT
    * @return array Pole pro HTML tag SELECT
    */              
   public static function selectUnit()
   {
      return array('SECOND'=>'Vteřina',
                   'MINUTE'=>'Minuta',
                   'MINUTE_SECOND'=>'Minuta:Vteřina',
                   'HOUR'=>'Hodina',
                   'HOUR_SECOND'=>'Hodina:Vteřina',
                   'HOUR_MINUTE'=>'Hodina:Minuta',
                   'DAY'=>'Den',
                   'DAY_SECOND'=>'Den:Vteřina',
                   'DAY_MINUTE'=>'Den:Minuta',
                   'DAY_HOUR'=>'Den:Hodina',
                   'WEEK'=>'Týden',
                   'MONTH'=>'Měsíc',
                   'QUARTER'=>'Čtvrtletí',
                   'YEAR'=>'Rok',
                   'YEAR_MONTH'=>'Rok:Měsíc');
   }

   
   /**
    * Typ události pro HTML tag SELECT
    * @return array Pole pro HTML tag SELECT
    */              
   public static function selectType()
   {
      return array(1=>'Opakovaná',
                   0=>'Jednorázová');
   }


   /**
    * Stav pro HTML tag SELECT
    * @return array Pole pro HTML tag SELECT
    */              
   public static function selectStatus()
   {
      return array('ENABLED'=>'Zapnutá',
                   'SLAVESIDE_DISABLED'=>'Vypnutá na podřízeném',
                   'DISABLED'=>'Vypnutá');
   }


   /**
    * Databáze pro HTML tag SELECT
    * @param string $database Jméno databáze
    * @param string $event Jméno události
    * @return array Pole pro HTML tag SELECT
    */              
   public static function detail($database, $event)
   {
      $sql = 'SELECT EVENT_CATALOG, EVENT_SCHEMA database_name, EVENT_NAME name, DEFINER definer, TIME_ZONE, 
                     EVENT_BODY body_type, EVENT_DEFINITION sql_command, EVENT_TYPE event_type, 
                     CASE WHEN EVENT_TYPE = \'RECURRING\' THEN TRUE ELSE FALSE END event_type_b,
                     EXECUTE_AT execute_at, 
                     INTERVAL_VALUE interval_value, INTERVAL_FIELD interval_unit,
                     SQL_MODE, STARTS start, ENDS end, STATUS status, ON_COMPLETION on_completion,
                     CASE WHEN ON_COMPLETION = \'PRESERVE\' THEN FALSE ELSE TRUE END on_completion_b,
                     CREATED created, LAST_ALTERED altered, LAST_EXECUTED executed,
                     EVENT_COMMENT comment, ORIGINATOR, CHARACTER_SET_CLIENT, COLLATION_CONNECTION, DATABASE_COLLATION
              FROM INFORMATION_SCHEMA.EVENTS
              WHERE EVENT_SCHEMA = %sN AND EVENT_NAME = %sN';
      return dibi::fetch($sql, $database, $event);
   }

   
   /**
    * Zrušení události
    * @param string $database Jméno databáze
    * @param string $event Jméno události
    */              
   public static function drop($database, $event)
   {
      $sql = 'DROP EVENT %sql.%sql';
      dibi::query($sql, $database, $event);
   }

}
