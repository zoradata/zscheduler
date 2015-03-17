<?php
/**
 * Z-Scheduler
 *
 * Last revison: 15.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Formuláře událostí
 */


class EventForm extends \Nette\Object
{
    
   protected $presenter;

   
   private function __construct($presenter)
   {
      $this->presenter = $presenter;
   }

   
   /**
    * Formulář pro vložení nové události a pro úpravu existující události
    * @param array() $defaults Defaultní hodnoty
    * @param string $callback Funkce pro zpracování dat
    * @return form
    */              
   public function manage($defaults, $callback)
   {
      $form = new Form($this->presenter, 'event');
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
      $form->addSubmit('save', 'Vytvořit')->onClick[] = array($this->presenter, $callback);
      if (!$form->isSubmitted())
         $form->setDefaults($defaults);
      return $form;
   }

}
