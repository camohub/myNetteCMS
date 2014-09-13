<?php

namespace App\Adminmodule\BlogModule\Presenters;

use	Nette,
	Nette\Diagnostics\Debugger;

/**
 * Clanok presenter.
 */

class SekciaPresenter extends \App\Presenters\BasePresenter
{
	/** @var Nette\Database\Context @inject */
	private $database;


	public function __construct(Nette\Database\Context $db)
	{
		$this->database = $db;

	}
	
	public function actionNew()
	{

	}

/////components/////////////////////////////////////////////////

	protected function createComponentSekciaForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('title', 'Meno sekcie')
		->setRequired('Pole nemôže ostať nevyplnené.');
		
		$form->addSubmit('send', 'Uložiť');

		$form->onSuccess[] = $this->sekciaFormSucceeded;

		return $form;
	}
	
	protected function sekciaFormSucceeded()
	{
		$values = $form->getValues();	
	}	
	

}