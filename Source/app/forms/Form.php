<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Základ formulářů
 */


namespace BaseModule;

use \Nette\Application\UI\Form;


class BaseForm extends \Nette\Object
{

   /** @var type Presenter */
   protected $presenter;

   /**
    * Vytvoření instance
    * @param Presenter $presenter Presenter
    */
   public function __construct($presenter)
   {
      $this->presenter = $presenter;
   }
   
}
