<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Databázový model pro detail
 */


class DetailModel extends \Nette\Object
{
    

   private function __construct()
   {
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
