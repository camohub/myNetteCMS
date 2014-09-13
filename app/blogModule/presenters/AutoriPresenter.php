<?php

namespace App\BlogModule\Presenters;

use	Nette,
	/*App\Model,
	Nette\Caching\Cache, */
	Nette\Diagnostics\Debugger,
	Nette\Utils\Paginator;

/**
 * Autor presenter.
 */

class AutoriPresenter extends \App\Presenters\BasePresenter
{
	/** @var Nette\Database\Context */
	private $database;
     /** @var Nette\Caching\IStorage @inject */
	public $storage;


	public function __construct(\Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function startup()
	{
		parent::startup();
	}

	public function renderDefault()
	{
		/*$this->template->posts = $this->database->table('posts')
										->select('posts.title, posts.id, users.username')
										->order('username ASC'); */
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