<?php

namespace App\BlogModule\Presenters;

use	Nette,
	App\Model,
	/*Nette\Caching\Cache,
	Nette\Diagnostics\Debugger,*/
	Nette\Utils\Paginator;

/**
 * Autor presenter.
 */

class AutoriPresenter extends \App\Presenters\BasePresenter
{

	public function __construct()
	{

	}

	public function startup()
	{
		parent::startup();
	}

	public function renderDefault()
	{
		$this->template->users = $this->database->table('users')
											->select('id, username')
											->where('NOT username', 'eshop')
											->order('username');
		$this->template->half = ceil(count($this->template->users)/2);
		
		
			
	}

	public function renderShow($id)
	{
		$contents = $this->database->table('content')->where('users_id =?', $id)->order('created_at DESC');
		$this->template->contents = $contents;
		$this->template->autor = $id;
		
	}

}