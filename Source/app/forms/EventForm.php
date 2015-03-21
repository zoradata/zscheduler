<?php
/**
 * Z-Scheduler
 *
 * Last revison: 21.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Formuláře událostí
 */


use \Nette\Application\UI\Form;


class EventForm extends \Nette\Object
{
    
   protected $presenter;

   
   public function __construct($presenter)
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
      $checkDatetime = '([1-3][0-9]{3,3})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][1-9]|3[0-1])\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9])';
      $errorDatetime = 'Pole %label musí mít tvar RRRR-MM-DD HH:MI:SS';
      $placeholderDatetime = 'RRRR-MM-DD HH:MI:SS';
      $form = new Form($this->presenter, 'event');
      $form->setTranslator($this->presenter->translator);
      $form->getElementPrototype()->class('form-horizontal');
      $form->addSelect('database_name', 'Databáze', EventModel::selectDatabase())->addRule(Form::FILLED)->setPrompt(' -- Vyberte --');
      $form->addText('name', 'Jméno', NULL, 64)->addRule(Form::FILLED);
      $form->addText('comment', 'Popis', NULL, 200);
      $form->addSelect('status', 'Stav', EventModel::selectStatus())->addRule(Form::FILLED);
      $form->addSelect('repeated', 'Typ', EventModel::selectType())->addRule(Form::FILLED);
      $form->addText('run_at', 'Spustit v', NULL, 20)
           ->setAttribute('placeholder', $placeholderDatetime)
           ->addCondition(Form::FILLED)
           ->addRule(Form::PATTERN, $errorDatetime, $checkDatetime);
      $form->addText('start', 'Začátek', NULL, 20)
           ->setAttribute('placeholder', $placeholderDatetime)              
           ->addCondition(Form::FILLED)
           ->addRule(Form::PATTERN, $errorDatetime, $checkDatetime);
      $form->addText('end', 'Konec', NULL, 20)
           ->setAttribute('placeholder', $placeholderDatetime)              
           ->addCondition(Form::FILLED)
           ->addRule(Form::PATTERN, $errorDatetime, $checkDatetime);
      $form->addText('interval_value', 'Spustit každých', NULL, 200)
           ->addConditionOn($form['repeated'], Form::EQUAL, TRUE)->addRule(Form::FILLED)->addCondition(Form::INTEGER);
      $form->addSelect('unit', 'Jednotka', EventModel::selectUnit())
           ->addConditionOn($form['repeated'], Form::EQUAL, TRUE)->addRule(Form::FILLED);
      $form['unit']->setPrompt(' -- Vyberte --');
      $form->addSelect('preserve', 'Smazat po ukončení', $this->presenter->getLogical())->addRule(Form::FILLED);
      $form->addTextArea('sql_command', 'SQL příkaz', NULL, NULL)->addRule(Form::FILLED)
           ->setAttribute('placeholder', "BEGIN SELECT 1; SELECT 2; END;");
      $form->addSubmit('save', 'Vytvořit')->onClick[] = array($this->presenter, $callback);
      if (!$form->isSubmitted())
         $form->setDefaults($defaults);
      return $form;
   }

   
   /**
    * Formulář pro smazání události
    * @param string $callback Funkce pro zpracování dat
    * @return form
    */              
   public function delete($callback)
   {
      $form = new Form($this->presenter, 'event');
      $form->setTranslator($this->presenter->translator);
      $form->getElementPrototype()->class('form-horizontal');
      $form->addSubmit('save', 'Zrušit')->onClick[] = array($this->presenter, $callback);
      return $form;
   }

}
