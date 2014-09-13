<?php

namespace App\EshopModule\Presenters;

use	Nette,
	App\Model,
	Nette\Caching\Cache,
	Nette\Diagnostics\Debugger;

/**
 * Default presenter.
 */

class DefaultPresenter extends \App\Presenters\BasePresenter
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

	public function renderDefault($id)
	{
		$countAll = $this->database->table('content')->where('status = ?', 1)->count('*');
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 3;
		$paginator->itemCount = $countAll;
		
		$this->template->contents = $this->database->table('content')
							->select('content.*, users.username')
							->order('created_at DESC')
							->limit($paginator->itemsPerPage, $paginator->offset);
	}
	
	public function renderShow($id, $title)
	{
		$this->template->id = $id;
		$this->template->title = $title;		
	}
	
/////components/////////////////////////////////////////////////////////////////////

	protected function createComponentVp($name)
	{
		$control = new \NasExt\Controls\VisualPaginator($this, $name);
		// enable ajax request, default is false
		/*$control->setAjaxRequest();
		
		$that = $this;
		$control->onShowPage[] = function ($component, $page) use ($that) {
		if($that->isAjax()){
		$that->invalidateControl();
		}
		};   */
		return $control;
	}


}