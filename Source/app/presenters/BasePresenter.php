<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Základní presenter
 */


abstract class BasePresenter extends \Nette\Application\UI\Presenter

{

   public $session;
   /** @var array Konfigurační parametry aplikace (pole 'parametr'=>'hodnota') */
   public $param;

   /** @var HttpResponse HTTP odpověď */
   public $httpResponse;

   /** @var translator Instance translatoru */
   public $translator;   

   
   protected function startup()
   {
      parent::startup();
//      $this->session = $this->context->getService('session');                                                                     // Načtení session
      
      $this->param = $this->context->getParameters();                                                                             // Načtení parametrů  
      $this->httpResponse = $this->context->getByType('Nette\Http\Response');                                                     // Načtení HTTP odpovědi
      $this->translator->lang = $this->param['language'];      
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
