<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 */


use Nette, App\Model, Nette\Diagnostics\Debugger;


class ErrorPresenter extends \Nette\Application\UI\Presenter
{
  

   public function startup()
   {
      parent::startup();
   }


/*   public function renderDefault($exception)*/
   public function renderDefault(\Exception $exception)
   {
      if ($exception instanceof Nette\Application\BadRequestException)
      {
         $code = $exception->getCode();
	 $this->setView(in_array($code, array(403, 404, 405, 410, 500)) ? $code : '4xx');
	 Debugger::log($exception, Debugger::ERROR);
      } 
      else
      {
         $this->setView('500');
	 Debugger::log($exception, Debugger::ERROR);
      }
      if ($this->isAjax())
      {
         $this->payload->error = TRUE;
	 $this->terminate();
      }
   }

}
