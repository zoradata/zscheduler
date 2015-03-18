<?php
/**
 * Z-Scheduler
 *
 * Last revison: 18.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Presenter pro správu událostí
 */


class HomePresenter extends \LoginPresenter
{

   /**
    * Inicializace presenteru
    */
   public function startup()
   {
      parent::startup();
   }

   
   /**
    * Akce - Zobrazení úvodní přehledové stránky
    */
   public function actionDefault($db = NULL)
   {
      $this->setBaseData();
      $paginatorEvent = $this->pageEvent->getPaginator();
      $this->template->events = EventModel::event($db, $paginatorEvent->itemsPerPage, $paginatorEvent->offset);
      $paginatorEvent->itemCount = EventModel::countRows();
   }

   
   /**
    * Akce - Výběr databáze
    */
   public function actionSelect($db)
   {
      $this->db = $db;
      $this->redirect(':Home:default', $this->db);
   }

   
   /**
    * Akce - Spuštení plánovače
    */
   public function actionOn()
   {
      SchedulerModel::start();
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Zastavení plánovače
    */
   public function actionOff()
   {
      SchedulerModel::stop();
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Spuštení události
    */
   public function actionEnable($schema, $name)
   {
      dibi::query('ALTER EVENT %sql.%sql ENABLE', $schema, $name);
      $this->redirect(':Home:default');
   }

   /**
    * Akce - Zastavení události
    */
   public function actionDisable($schema, $name)
   {
      dibi::query('ALTER EVENT %sql.%sql DISABLE', $schema, $name);
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Detail události
    */
   public function actionDetail($database, $event)
   {
      $this->setBaseData();
      $this->template->detail = EventModel::detail($database, $event);
   }

   
   /**
    * Akce - Nová událost
    */
   public function actionNew()
   {
      $this->setBaseData();
      $defaults = array('preserve'=>0);
      if ($this->db != NULL)
         $defaults['database_name'] = $this->db;
      $eventForm = new EventForm($this);
      $this->template->form = $eventForm->manage($defaults, 'submitNew');
   }

   
   /**
    * Akce - Nová událost - Zpracování dat
    * @param Nette\Forms\Controls\SubmitButton $button Submit button formuláře
    */
   public function submitNew(\Nette\Forms\Controls\SubmitButton $button)
   {
      $data = $button->getForm()->getValues();
      $sql = Statement::create($data);
      // echo '<br><br>xxx: ' . $sql;
      try
      {
         //dibi::query('DELIMITER $$');
         dibi::query($sql);
      }
      catch (Exception $e)
      {
         $button->getForm()->addError($e->getMessage());
         $button->getForm()->addError($sql);
         return FALSE;        
      }
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Změna události
    */
   public function actionEdit($database, $event)
   {
      $this->setBaseData();
      $defaults = EventModel::detail($database, $event);
      $eventForm = new EventForm($this);
      $this->template->form = $eventForm->manage($defaults, 'submitEdit');
   }

   
   /**
    * Akce - Změna události - Zpracování dat
    * @param Nette\Forms\Controls\SubmitButton $button Submit button formuláře
    */
   public function submitEdit(\Nette\Forms\Controls\SubmitButton $button)
   {
      $data = $button->getForm()->getValues();
      $sql = Statement::alter($data);
      try
      {
         dibi::query($sql);
      }
      catch (Exception $e)
      {
         $button->getForm()->addError($e->getMessage());
         return FALSE;        
      }
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Smazání události
    */
   public function actionDelete($database, $event)
   {
      $this->setBaseData();
      $this->template->detail = EventModel::detail($database, $event);
      $eventForm = new EventForm($this);
      $this->template->form = $eventForm->delete('submitDelete');
   }

   
   /**
    * Akce - Změna události - Zpracování dat
    * @param Nette\Forms\Controls\SubmitButton $button Submit button formuláře
    */
   public function submitDelete(\Nette\Forms\Controls\SubmitButton $button)
   {
      try
      {
         EventModel::drop($this->template->detail->database_name, $this->template->detail->name);
      }
      catch (Exception $e)
      {
         $button->getForm()->addError($e->getMessage());
         return FALSE;        
      }
      $this->redirect(':Home:default');
   }

   
   /**
    * Načtení základních dat
    */
   private function setBaseData()
   {
      $this->template->dbVersion = EventModel::dbVersion();
      $this->template->schedulerStatus = EventModel::schedulerStatus();

      $this->template->databaseTotal = EventModel::databaseTotal();
      $paginatorDb = $this->pageDb->getPaginator();
      $this->template->databases = EventModel::database($paginatorDb->itemsPerPage, $paginatorDb->offset);
      $paginatorDb->itemCount = EventModel::countRows();
   }
   
}
