<?php

/**
 * ATTEST W-Tazatel
 *
 * Last revison: 12.05.2010
 * @copyright	Copyright (c) 2010 Attest s.r.o. (http://www.attest.cz)
 */


class Validate
{


   public static function validateDate($pControl)
   {
      if (preg_match ("/^([0-9]{2}).([0-9]{2}).([0-9]{4})$/", $pControl->value, $parts))
      {
         if(isset($parts[2]) and isset($parts[1]) and isset($parts[3]))
         {
            if(checkdate($parts[2],$parts[1],$parts[3]))
               return TRUE;
         }
      }
      return FALSE;
   }


   public static function validateDateTime($pControl)
   {
      if (preg_match("/^([0-9]{2}).([0-9]{2}).([0-9]{4}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $pControl->value, $parts))
      {
         if(isset($parts[2]) and isset($parts[1]) and isset($parts[3]))
         {
            if(checkdate($parts[2],$parts[1],$parts[3]))
               return TRUE;
         }
      }
      return FALSE;
   }
}

