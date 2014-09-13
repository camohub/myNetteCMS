<?php

namespace App\Presenters;

use	Nette,
	Nette\Caching\Cache,
	Nette\Diagnostics\Debugger,
	App\Model;

/**
 * Default presenter.
 */
class Drom1Presenter extends \App\Presenters\BasePresenter
{
	/** @var Nette\Database\Context @inject */
	public $database;
     /** @var Nette\Caching\IStorage @inject */
	public $storage;
	/** @var Model\GeneralModel @inject */
	public $genModel;

	public function __construct()
	{

	}

	public function renderDefault($id)
	{
		$selection = $this->genModel->getTable('users');
		//$selection = $this->database->table('users')->where('id > ?', 5)->limit(5);
		$this->template->selection = $selection;
		
		$this->template->test = $this->genModel->getTest();
		
		
	}

}