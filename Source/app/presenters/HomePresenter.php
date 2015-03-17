<?php
/**
 * Z-Scheduler
 *
 * Last revison: 17.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Presenter pro správu událostí
 */


use \Nette\Application\UI\Form;


class HomePresenter extends \LoginPresenter
{

   /** @var EventModel DB model */
 //  protected $eventModel;

  
   /**
    * Inicializace presenteru
    */
   public function startup()
   {
      parent::startup();
//      $this->eventModel = EventModel();
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
      dibi::query('SET GLOBAL event_scheduler = ON');
      $this->redirect(':Home:default');
   }

   
   /**
    * Akce - Zastavení plánovače
    */
   public function actionOff()
   {
      dibi::query('SET GLOBAL event_scheduler = OFF');
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
      $defaults = array();
      if ($this->db != NULL)
         $defaults['database_name'] = $this->db;
      $form = new Form($this, 'event');
      $form->getElementPrototype()->class('form-horizontal');
      $form->addSelect('database_name', 'Databáze', EventModel::selectDatabase())->addRule(Form::FILLED)->setPrompt(' -- Vyberte --');
      $form->addText('name', 'Jméno', NULL, 64)->addRule(Form::FILLED);
      $form->addText('comment', 'Popis', NULL, 200);
      $form->addSelect('repeat', 'Opakovaně', $this->getLogical())->addRule(Form::FILLED);
      $form->addText('start', 'Začátek', NULL, 20);
      $form->addText('end', 'Konec', NULL, 20);
      $form->addText('interval', 'Interval', NULL, 200);
      $form->addSelect('unit', 'Jednotka', EventModel::selectUnit());
      $form->addSelect('preserve', 'Smazat po ukončení', $this->getLogical())->addRule(Form::FILLED);
      $form->addTextArea('sql', 'SQL příkaz', NULL, NULL)->addRule(Form::FILLED);
      $form->addSubmit('save', 'Vytvořit')->onClick[] = array($this, 'submitNew');
      $this->template->form = $form;
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
