<?php
/**
 * Z-Scheduler
 *
 * Last revison: 15.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Databázový model pro detail
 */


class EventModel extends \Nette\Object
{
    

   private function __construct()
   {
   }

   
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

   
   /**
    * Nastavení filtru pro vyhledávání
    * @param array $filter Pole s hodnotami filtru
    * @param type $param Pole s parametry filtru
    * @return array Pole pro klauzuli WHERE v příkazu SELECT
    */
   
   /*
   public function filterCondition($filter, $param = array())                                                                                       //
   {
      $output = array();
      foreach ($filter as $key => $value)
      {
         if (!str_replace(' ', '', $value) == '')
         {
            if (isSet($param[$key]))
            {
               switch ($param[$key])
               {
                  case '=':
                     $output[$key] = $value;
                     break;
                  default:
                     $output[$key . '%~like~'] = $value;
               }
            }
            else $output[$key . '%~like~'] = $value;
         }
      }
      return $output;
   }
*/

   /**
    * Nastavení filtru pro vyhledávání
    * @param array $filter Pole s hodnotami filtru
    * @param type $param Pole s parametry filtru
    * @return string String pro klauzuli WHERE v příkazu SELECT
    */
   public function filterCondition($filter, $param = array())                                                                                       //
   {
      $output = ' 1 = 1';
      foreach ($filter as $key => $value)
      {
         if ($value != '')
         {
            if (isSet($param[$key]))
            {
               switch ($param[$key])
               {
                  case '=':
                     $output .= ' AND ' . $key . ' = ' . '\'' . $value . '\'';
                     break;
                  case '>':
                     $output .= ' AND ' . $key . ' > ' . '\'' . $value . '\'';
                     break;
                  case '>=':
                     $output .= ' AND ' . $key . ' >= ' . '\'' . $value . '\'';
                     break;
                  case '<':
                     $output .= ' AND ' . $key . ' < ' . '\'' . $value . '\'';
                     break;
                  case '<=':
                     $output .= ' AND ' . $key . ' <= ' . '\'' . $value . '\'';
                     break;
                  case '!=':
                     $output .= ' AND ' . $key . ' != ' . '\'' . $value . '\'';
                     break;
                  case 'LIKE':
                     $output .= ' AND ' . $key . ' LIKE ' . '\'' . $value . '\'';
                     break;
                  case 'LIKE%':
                     $output .= ' AND ' . $key . ' LIKE ' . '\'' . $value . '%\'';
                     break;
                  case '%LIKE':
                     $output .= ' AND ' . $key . ' LIKE ' . '\'%' . $value . '\'';
                     break;
                  case '%LIKE%':
                     $output .= ' AND ' . $key . ' LIKE ' . '\'%' . $value . '%\'';
                     break;
                  case 'NULL':
                     $output .= ' AND ' . $key . ' IS NULL';
                     break;
                  case 'NOTNULL':
                     $output .= ' AND ' . $key . ' IS NOT NULL';
                     break;
                  default:
                     $output .= ' AND ' . $key . ' LIKE ' . '\'%' . $value . '%\'';
               }
            }
            else
            {
            $output .= ' AND ' . $key . ' LIKE ' . '\'%' . $value . '%\'';
            }
         }
         else
         {
            if (isSet($param[$key]))
            {
               switch ($param[$key])
               {
                  case 'NULL':
                     $output .= ' AND ' . $key . ' IS NULL';
                     break;
                  case 'NOTNULL':
                     $output .= ' AND ' . $key . ' IS NOT NULL';
                     break;
                  default:
               }
            }
         }
      }
      return $output;
   }

   
   /**
    * Nastavení třídění
    * @param array $order Pole s hodnotami třídění
    * @param stringe $default Defaultrní třídění
    * @return string String pro klauzuli ORDER BY v příkazu SELECT
    */
   public function orderBy($order, $default = NULL)                                                                                       //
   {
      $separator = '';
      $output = '';
      foreach ($order as $key => $value)
      {
         if ($value != '' )
         {
            $output .= $separator  . $key . ' ' . $value;
            $separator = ', ';
         }
      }
      if ($output == '')
      {
         if ($default != NULL)
            $output = 'ORDER BY ' . $default;
      }
      else $output = 'ORDER BY ' . $output;
      return $output;
   }
   
}
