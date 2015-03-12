<?php
/**
 * ZoraData Testy
 *
 * Last revison: 25.12.2012
 *
 * @copyright	Copyright (c) 2010 ZoraData sdružení
 * @link	http://www.zoradata.cz
 */


class ZDText
{
   const PARAM_DELIMITER = '#';


   public function __construct()
   {
   }


   private function getText($pText)
   {
      $value = dibi::fetchSingle('SELECT get_text(%sN) AS value', $pText);
      return $value;
   }

   
   public function replace($pContent, $pParam = array())
   {
      $output = $pContent;
      foreach ($pParam as $key => $value)
         $output = str_replace(self::PARAM_DELIMITER . $key . self::PARAM_DELIMITER, $value, $output);
      return $output;
   }

   
   public function get($pText, $pParam = array())
   {
      $output = $this->getText($pText);
      return $this->replace($output, $pParam);
   }

}

