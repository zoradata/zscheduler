<?php
/**
 * Z-Scheduler
 *
 * Last revison: 21.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Základní presenter po přihlášení
 */



abstract class LoginPresenter extends BasePresenter
{

   /** @var string Název aktuální databáze */
   public $db;

   
   /** @var VisualPaginator Stránkovač výpisu databází */
   public $pageDb;

   
   /** @var VisualPaginator Stránkovač výpisu událostí */
   public $pageEvent;

   
   /**
    * Definice persistentních parametrů
    * @return array Pole parametrů
    */
   public static function getPersistentParams()
   {
      return array('db');
   }
   
   
   /**
    * Definice persistentních komponent
    * @return array Pole komponent
    */
   public static function getPersistentComponents()
   {
        return array('pageDb', 'pageEvent');
   }
  
   
   protected function startup()
   {
      parent::startup();

      if (!$this->getUser()->isLoggedIn())                                                                                        // Je přihlášen uživatel?
         $this->forward(':Sign:default');                                                                                         // Ne - Skok na přihlášení
      $this->user->setExpiration($this->param['loginExpiration'], TRUE);                                                          // Nastavení doby trvání přihlášení
      dibi::connect($this->getUser()->getIdentity()->getData());                                                                  // Ano - Přihlásit do DB
      $this->pageDb = new VisualPaginator($this, 'pageDb', $this->param['pageDatabase']);                                         // Vytvoření instance stránkovače výpisu databází
      $this->pageEvent = new VisualPaginator($this, 'pageEvent', $this->param['pageEvent']);                                      // Vytvoření instance stránkovače výpisu událostí
   }
   
}
