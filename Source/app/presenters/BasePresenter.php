<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 */


abstract class BasePresenter extends \Nette\Application\UI\Presenter

{
   /** @var string Název vzhledu */
   public $skin = 'skin5';
   

   public $session;

   /** @var DibiConnection Připojení k databázi */
   public $db;

   /** @var HttpResponse HTTP odpověď */
   public $httpResponse;

   /** @var translator Instance translatoru */
   protected $translator;   

   
   protected function startup()
   {
      parent::startup();
      $this->session = $this->context->getService('session');                                                                     // Načtení session
//      $this->db = $this->context->db;                                                                                             // Otevření databáze
      $this->httpResponse = $this->context->getByType('Nette\Http\Response');                                                     // Načtení HTTP odpovědi
   }

   
   public function getModuleName()                                                                                                                                      // Zjištení jména modulu
   {
      $pos = strrpos($this->name, ':');
      if (is_int($pos))
         return str_replace(':', '_', substr($this->name, 0, $pos));
      return NULL;
   }


   public function getPresenterName()                                                                                                                                   // Zjištení jména presenteru (bez modulu)
   {
      $pos = strrpos($this->name, ':');
      if (is_int($pos))
        return substr($this->name, $pos + 1);
      return $this->name;
   }


   protected function getUrl()                                                                                                    // Zjištení aktuálního URL
   {
      $httpRequest = $this->context->getService('httpRequest');
      $url = $httpRequest->getUrl();
      return $url->absoluteUrl;
   }

   
   protected function getHostUrl()                                                                                                    // Zjištení aktuálního URL
   {
      $httpRequest = $this->context->getService('httpRequest');
      $url = $httpRequest->getUrl();
      return $url->hostUrl;
   }

   
   /**
    * Injektování translatoru
    * @param \GettextTranslator\GetText $translator
    */
   public function injectTranslator(\GettextTranslator\GetText $translator)
   {
      $this->translator = $translator;
   }

   
   /**
    * Vytvoření šablony s vlastním rozšířením
    * @param string $class
    * @return type
    */
   protected function createTemplate($class = NULL)
   {
      $template = parent::createTemplate($class);
      $template->registerHelper('boolean', '\BaseModule\Helpers::boolean');                                                       // Přidání helprů do šablony
      $template->registerHelper('znumber', '\ComModule\Helpers::number');             
      $template->registerHelper('zcurrency', '\ComModule\Helpers::currency');    
      $template->setTranslator($this->translator);                                                                                // Přidání translatoru do šabloby
      return $template;
   }

   
   public function getLogical()
   {
      return array( '1'=>'Ano', '0'=>'Ne');
   }

}
