<?php

/**
 * E-Learning
 *
 * Last revison: 2.5.2014
 * @copyright	Copyright (c) 2014 Quality Consult (http://www.qualityconsult.cz)
 */


namespace BaseModule;


class Privilege extends \Nette\Object
{


   public static function isPrivilege($modulePrivilege)
   {
      return TRUE;
      if (!ActualUser::exists())
         return FALSE;
/*
      if (count(array_intersect(ActualUser::getPrivilege(), $modulePrivilege)) > 0)
         return TRUE;
      else return FALSE;
*/
   }

}

