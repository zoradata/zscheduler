<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 */



abstract class LoginPresenter extends BasePresenter
{

   /** @var string Název aktuální databáze */
   public $db;

   
   /**
    * Definice persistentních parametrů
    * @return array Pole parametrů
    */
   public static function getPersistentParams()
   {
      return array('db');
   }
    
   
  protected function startup()
  {
      parent::startup();

      if (!$this->getUser()->isLoggedIn())                                                                                        // Je přihlášen uživatel?
      {
         $backlink = $this->storeRequest();                                                                                       // Ne - Skok na přihlášení
         $this->forward(':Sign:default', $backlink);
      }
      dibi::connect($this->getUser()->getIdentity()->getData());

   }
   

   /**
    * Testování přístupu do dané sekce aplikace (podle přístupových práv aktivního uživatele)
    * @param type $module Název modulu nebo * pro všechny
    * @param type $presenter Název presenteru nebo * pro všechny
    * @param type $action Název akce nebo * pro všechny
    * @return boolean TRUE, pokud je přístup povolen, FALSE pokud ne
    */
   public function isAccess($module, $presenter = '*', $action = '*')
   {
      $privileges = $this->getUser()->getRoles();
      foreach($privileges as $sentence => $privilege)
      {
//         echo 'M' . $privilege['module'] . 'X' . $module . '<br />';
//         echo 'P' . $privilege['presenter'] . 'X' . $presenter . '<br />';
//         echo 'A' . $privilege['action'] . 'X' . $action . '<br />';
         if (!isSet($privilege['module']) or !isSet($privilege['presenter']) or !isSet($privilege['action']))
         {
            return FALSE;
         }
         if (($privilege['module'] == $module or $privilege['module'] == '*' or $module == '*') and
             ($privilege['presenter'] == $presenter or $privilege['presenter'] == '*' or $presenter == '*') and
             ($privilege['action'] == $action or $privilege['action'] == '*' or $action == '*' ))
         {
            return TRUE;
         }
      }
      return FALSE;
   }
   
}
