<?php

/**
 * This file is extensions of Nette Framework
 *
 * @copyright  Copyright (c) 2009 David Grudl
 * @license    New BSD License
 * @autor Vladimir Čamaj (http://web.php5.sk)
 */

namespace App\Controls;

use	Nette,
	Nette\Application\UI\Control,
	Nette\Diagnostics\Debugger;


class Menu extends Control
{

	/** @var array */
	public $onShowPage;
	
	/** @var Nette\Database\Context */
	protected $database;
	
	/** @var string */
	protected $table = 'menu';


	public function __construct(Nette\Database\Context $db)
	{
		parent::__construct();
		$this->database = $db;
	}


	/**
	 * @param bool $value
	 * @return VisualPaginator provides fluent interface
	 */
	public function render()
	{
		$template = $this->template;
		
		$template->setFile(__DIR__ . '/menu.latte');
		// vložíme do šablony nějaké parametry
		$template->menuArr = $this->getArray();
		// a vykreslíme ji
		$template->render();
	}
	
	/**
	 * @return array of arrays 
	 * like $a[0][5] $a[0][6] where [0] is parent_id which is array of his childrens
	 */	 	
	protected function getArray()
	{
		$selection = $this->getSelection();
		while($row = $selection->fetch())
		{
			$arr[$row['parent_id']][$row['id']] = $row;
		}
		
		return $arr;
	}
	
	/**
	 * @return Nette database selection
	 */	 	
	protected function getSelection()
	{
		$selection = $this->database->table($this->table)
			->where('visible = ?',1)
			->order('parent_id ASC, menu_order ASC');
		
		return $selection;
	}
	
}