<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Základ databázových modelů
 */


abstract class Db extends \Nette\Object
{
    
   protected $db;                                                                                                                 // Databázové připojení
   

   public function __construct($db)
   {
      $this->db = $db;
   }

  
   public function countRows()                                                                                                    // Předání celkového počtu řádků posledního dotazu
   {
      return $this->db->fetchSingle('SELECT FOUND_ROWS()');
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
