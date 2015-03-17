<?php
/**
 * Z-Scheduler
 *
 * Last revison: 15.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Databázový model pro události
 */


class EventModel extends \DbModel
{
    

   /**
    * Výpis databází s počtu událostí v nich
    * @return array Pole záznamů
    */              
   public static function database()
   {
      $sql = 'SELECT D.SCHEMA_NAME database_name, 
                     NULLIF(SUM(CASE WHEN E.STATUS != \'DISABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME
              GROUP BY database_name
              /*HAVING count_enabled > 0 OR count_disabled > 0*/
              ORDER BY database_name';
      return dibi::fetchAll($sql);
   }

   
   /**
    * Celkový počet událostí
    * @return array Záznam
    */              
   public static function databaseTotal()
   {
      $sql = 'SELECT NULLIF(SUM(CASE WHEN E.STATUS != \'DISABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled
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
    * Databáze pro HTML tag SELECT
    * @return array Pole pro HTML tag SELECT
    */              
   public static function detail($database, $event)
   {
      $sql = 'SELECT EVENT_CATALOG, EVENT_SCHEMA database_name, EVENT_NAME name, DEFINER, TIME_ZONE, 
                     EVENT_BODY sql_command, EVENT_DEFINITION, EVENT_TYPE, EXECUTE_AT, 
                     INTERVAL_VALUE interval_value, INTERVAL_FIELD interval_unit,
                     SQL_MODE, STARTS start, ENDS end, STATUS, ON_COMPLETION, 
                     CREATED created, LAST_ALTERED altered, LAST_EXECUTED executed,
                     EVENT_COMMENT comment, ORIGINATOR, CHARACTER_SET_CLIENT, COLLATION_CONNECTION, DATABASE_COLLATION
              FROM INFORMATION_SCHEMA.EVENTS
              WHERE EVENT_SCHEMA = %sN AND EVENT_NAME = %sN';
      return dibi::fetch($sql, $database, $event);
   }

}
