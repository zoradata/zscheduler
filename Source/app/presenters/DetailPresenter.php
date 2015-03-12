<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 */


use \Nette\Application\UI\Form;



class DetailPresenter extends \LoginPresenter
{

   /** @var User Model uživatelů */
   protected $userModel;

  
   /**
    * Inicializace presenteru
    */
   public function startup()
   {
      parent::startup();
//      $this->userModel = new \UserModule\AdminModule\UserModel($this->db);
   }

   
   /**
    * Akce - Zobrazení detailu události
    */
   public function actionDefault()
   {
      $this->template->dbVersion = dibi::fetchSingle('SELECT VERSION()');
      $this->template->schedulerStatus = dibi::fetchSingle('SELECT CASE WHEN @@event_scheduler = \'ON\' THEN 1 ELSE 0 END');
      $sql = 'SELECT D.SCHEMA_NAME database_name, 
                     NULLIF(SUM(CASE WHEN E.STATUS != \'DISABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME
              GROUP BY database_name
              /*HAVING count_enabled > 0 OR count_disabled > 0*/
              ORDER BY database_name';
      $this->template->databases = dibi::fetchAll($sql);
      $this->template->events = dibi::fetchAll('SELECT * FROM INFORMATION_SCHEMA.EVENTS');
//    $this->template->previousLogin = $this->userModel->previousLogin($this->getUser()->getIdentity()->id);
   }

   
   /**
    * Akce - Nová událost
    */
   public function actionNew($defaults = array())
   {
      $this->template->dbVersion = dibi::fetchSingle('SELECT VERSION()');
      $this->template->schedulerStatus = dibi::fetchSingle('SELECT CASE WHEN @@event_scheduler = \'ON\' THEN 1 ELSE 0 END');
      $sql = 'SELECT D.SCHEMA_NAME database_name, 
                     NULLIF(SUM(CASE WHEN E.STATUS != \'DISABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME
              GROUP BY database_name
              /*HAVING count_enabled > 0 OR count_disabled > 0*/
              ORDER BY database_name';
      $this->template->databases = dibi::fetchAll($sql);
      $sql = 'SELECT NULLIF(SUM(CASE WHEN E.STATUS != \'DISABLED\' THEN 1 ELSE 0 END), 0) count_enabled,
                     NULLIF(SUM(CASE WHEN E.STATUS = \'DISABLED\' THEN 1 ELSE 0 END), 0) count_disabled
              FROM INFORMATION_SCHEMA.SCHEMATA D
              LEFT JOIN INFORMATION_SCHEMA.EVENTS E ON E.EVENT_SCHEMA = D.SCHEMA_NAME';
      $this->template->databaseTotal = dibi::fetch($sql);
      $form = new Form($this, 'event');
      $form->getElementPrototype()->class('form-horizontal');
      $form->addSelect('schema', 'Databáze', DetailModel::selectDatabase())->addRule(Form::FILLED);
      $form->addText('name', 'Jméno', NULL, 64)->addRule(Form::FILLED);
      $form->addText('comment', 'Popis', NULL, 200);
      $form->addText('repeat', 'Opakovaně', NULL, 1)->addRule(Form::FILLED);
      $form->addText('start', 'Začátek', NULL, 20);
      $form->addText('end', 'Konec', NULL, 20);
      $form->addText('interval', 'Interval', NULL, 200);
      $form->addSelect('unit', 'Jednotka', DetailModel::selectUnit());
      $form->addTextArea('sql', 'Příkaz', NULL, NULL)->addRule(Form::FILLED);
      $form->addSubmit('save', 'Vytvořit')->onClick[] = array($this, 'submitNew');
      $this->template->form = $form;
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

}

