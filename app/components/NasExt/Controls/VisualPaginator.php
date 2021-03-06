<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * @copyright  Copyright (c) 2009 David Grudl
 * @license    New BSD License
 * @autor Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Controls;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator,
Nette\Diagnostics\Debugger;

/**
 * VisualPaginator for Nette
 *
 * @author David Grudl
 * @author Dusan Hudak
 */
class VisualPaginator extends Control
{

	/** @ppersistent je momentálne vypnutý*/
	public $page = 1;

	/** @var array */
	public $onShowPage;

	/** @var  string */
	private $templateFile;

	/** @var Paginator */
	private $paginator;

	/** @var  bool */
	private $isAjax;


	public function __construct()
	{
		parent::__construct();

		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$name = $reflection->getShortName();
		$this->templateFile = $dir . DIRECTORY_SEPARATOR . $name . '.latte';
	}


	/**
	 * @param bool $value
	 * @return VisualPaginator provides fluent interface
	 */
	public function setAjaxRequest($value = TRUE)
	{
		$this->isAjax = $value;
		return $this;
	}


	/**
	 * @return Paginator
	 */
	public function getPaginator()
	{
		if (!$this->paginator) {
			$this->paginator = new Paginator;
		}
		return $this->paginator;
	}


	/**
	 * @param int $page
	 */
	public function handleShowPage($page)
	{
		$this->onShowPage($this, $page);
	}


	/**
	 * @return string
	 */
	public function getTemplateFile()
	{
		return $this->templateFile;
	}


	/**
	 * @param string $file
	 * @return VisualPaginator provides fluent interface
	 */
	public function setTemplateFile($file)
	{
		if ($file) {
			$this->templateFile = $file;
		}
		return $this;
	}


	/**
	 * Renders paginator.
	 * @return void
	 */
	public function render()
	{
		/* tento zakomentovaný kód by bol potrebný ku nastaveniu parametra
		page(predať metóde render sa nedá). Momentálne to funguje bez neho, vďaka perzistentnému
		parametru page, ktorý nastavuje metóda loadState()
		
		$page = $this->getParameter('page');
		Debugger::dump($page);
		$this->page = $page;
		$this->getPaginator()->page = $this->page; 
		*/
		
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
		$this->template->isAjax = $this->isAjax;
		$this->template->handle = 'showPage!';

		$this->template->setFile($this->getTemplateFile());
		$this->template->render();
	}


	/**
	 * Loads state informations.
	 * @param  array
	 * @return void
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);  // parent vezme getPersistentParams() a podľa nich vyberie z poľa $params a nastaví persistentné premenné 
		// keby som nezakomentoval @persistent page, tak by tento isset nebol potrebný. 
		//Predošlý riadok parent::loadState() by ho nastavil
		if(isset($params['page']))
		{
			$this->page = $params['page'];
		}
		$this->getPaginator()->page = $this->page; 
	}
}
