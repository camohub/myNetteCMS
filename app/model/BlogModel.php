<?php
namespace App\Model;

use 	Nette,
	Nette\Diagnostics\Debugger;

/**
 * @method getAllArticles   
 */	


class BlogModel
{
	/** @var Nette\Database\Context */	
	protected $database;
	

	public function __contruct(Nette\Database\Context $db)
	{
		$this->database = $db;	
	}
	
	
	public function getAllArticles();
	{
		return $this->getTable()->where('visible = ?', 1); 
	}
	
	public function getUserArticles($id)
	{
		return $this->getTable()->where('user_id = ?', $id);
	}
	
	protected function getTable()
	{
		return $this->database->table('blog');
	}

}