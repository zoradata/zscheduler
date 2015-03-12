<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 */
 
namespace BaseModule;



class Helpers extends \Nette\Object
{

   public static function boolean($value)
   {
      if ($value > 0)
         return 'Ano';
      else return 'Ne';
   }


   public static function number($value, $decimal = 0)
   {
      $formatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL);
      $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimal);
      return $formatter->format($value);
   }

   
   public static function currency($value, $currency = 'CZK')
   {
      $formatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
      return $formatter->formatCurrency($value, $currency);
   }

}
