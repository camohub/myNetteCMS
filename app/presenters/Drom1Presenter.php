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

	public function __construct()
	{

	}

	public function renderDefault($id)
	{
		$userModel = new Model\UserModel($this->database);
		$users = $userModel->getUsers();
		$this->template->users = $users;
		
	}
	
	//Toto sa má objaviť IBA vo vetve test repozitára myNetteCMS
}