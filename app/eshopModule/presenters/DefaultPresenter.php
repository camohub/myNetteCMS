<?php

namespace App\EshopModule\Presenters;

use	Nette,
	App\Model;

/**
 * Default presenter.
 */

class DefaultPresenter extends \App\Presenters\BasePresenter
{
     /** @var Nette\Caching\IStorage @inject */
	public $storage;

	public function __construct()
	{

	}

	public function startup()
	{
		parent::startup();
	}

	public function renderDefault($id)
	{

	}
	
	public function renderShow($id, $title)
	{
	
	}
	
/////components/////////////////////////////////////////////////////////////////////



}