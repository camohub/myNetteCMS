<?php

namespace App\Presenters;

use	Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var Nette\Http\Session|Nette\Http\SessionSection */
	protected $userSess;
	
	public function startup()
	{
		parent::startup();
		$this->userSess = $this->getSession('user');
		$this->template->userSess = $this->userSess;
	}
	
	public function isModuleCurrent($module)
	{
		//return \Nette\Utils\Strings::startsWith($this->getName(), $module);
		return stripos($this->getName(), $module) === 0;
	}	

}
