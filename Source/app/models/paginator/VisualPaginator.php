<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Stránkovač
 */

 
use Nette\Application\UI\Control; 
use Nette\Utils\Paginator;


class VisualPaginator extends Control
{
   /** @var Paginator */
   private $paginator;

   /** @persistent */
   public $page = 1;

   /** @persistent */
   public $perPage = 0;


   public function __construct($parent = NULL, $name = NULL, $perPage = 15)
   {
      parent::__construct($parent, $name);
      if ($this->perPage == 0)
         $this->perPage = $perPage;
   }

        
   /**
    * @return Nette\Paginator
    */
   public function getPaginator()
   {
      if (!$this->paginator)
      {
         $this->paginator = new Paginator;
      }
      return $this->paginator;
   }



	/**
	 * Renders paginator.
	 * @return void
	 */
	public function render()
	{
		$paginator = $this->getPaginator();
		$page = $paginator->page;
		if ($paginator->pageCount < 2) {
			$steps = array($page);

		} else {
			$arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;
			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->firstPage;
			}
			sort($arr);
			$steps = array_values(array_unique($arr));
		}

		$this->template->steps = $steps;
		$this->template->paginator = $paginator;
		$this->template->setFile(dirname(__FILE__) . '/template.latte');
		$this->template->render();
	}



	/**
	 * Loads state informations.
	 * @param  array
	 * @return void
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->page;
		$this->getPaginator()->itemsPerPage = $this->perPage;
	}

}