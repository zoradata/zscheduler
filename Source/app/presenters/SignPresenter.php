<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Presenter pro přihlášení a odhlášení uživatele
 */


use \Nette\Application\UI\Form;


class SignPresenter extends \BasePresenter
{
   
   protected function startup()
   {
      parent::startup();
   }


   /**
    * Akce - Přihlášení uživatele do aplikace
    */
   public function actionDefault()
   {
    /*  if (ActualUser::get()->getLogoutReason() === NUser::INACTIVITY)
         $this->message = 'Uživatel byl odhlášen z důvodů neaktivity.';*/
      $form = new Form($this, 'login');
      $form->getElementPrototype()->class('form-horizontal');
      $form->addText('host', 'Server', NULL, 255)->addRule(Form::FILLED)->setDefaultValue('localhost');
      $form->addText('user', 'Uživatel', NULL, 255);
      $form->addPassword('password', 'Heslo', NULL, 30);
      $form->addSubmit('login', 'Přihlásit')->onClick[] = array($this, 'submitLogin');
      $this->template->form = $form;
   }

   
   /**
    * Submit - Přihlášení uživatele do aplikace
    * @param Nette\Forms\Controls\SubmitButton $button Submit button formuláře
    */
   public function submitLogin(\Nette\Forms\Controls\SubmitButton $button)
   {
      $data = $button->getForm()->getValues();
      try
      {
         $this->user->login($data['host'], $data['user'], $data['password']);                                                     // Přihlášení
         $this->user->setExpiration('30 MINUTES', TRUE);                                                                          // Nastavení doby trvání přihlášení
         $this->redirect(':Home:default');
      }
      catch (\Nette\Security\AuthenticationException $e)
      {
         $button->getForm()->addError($e->getMessage());                                                                          // Přidání chyby do formuláře
      }
   }
   
   
   /**
    * Akce - Odhlášení uživatele z aplikace
    */
   public function actionLogout()
   {
      $this->user->logout(TRUE);                                                                                                  // Vlastní odhlášení
      $this->redirect(':Home:default');
   }
   
}
