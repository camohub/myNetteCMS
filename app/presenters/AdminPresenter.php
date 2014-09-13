<?php

namespace App\Presenters;

use	Nette,
	Nette\Caching\Cache;

/**
 * Admin presenter.
 */

class AdminPresenter extends \App\Presenters\BasePresenter
{
	/** @var Nette\Database\Context */
	private $database;
     /** @var Nette\Caching\IStorage @inject */
	public $storage;

	public function __construct(\Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault()
	{

	}

}