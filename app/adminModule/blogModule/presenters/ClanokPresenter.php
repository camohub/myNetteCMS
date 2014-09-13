<?php

namespace App\Adminmodule\BlogModule\Presenters;

use	Nette,
	Nette\Diagnostics\Debugger;

/**
 * Clanok presenter.
 */

class ClanokPresenter extends \App\Presenters\BasePresenter
{
	/** @var Nette\Database\Context @inject */
	private $database;


	public function __construct(Nette\Database\Context $db)
	{
		$this->database = $db;

	}

	public function startup()
	{
		parent::startup();
	}

	public function renderDefault()
	{
		$selection = $this->database->table('content')->where('users_id = ?', $this->userSess->id);
		$this->template->selection = $selection;
	}
	
	
	public function actionCreate()
	{

		
	}
	
	public function actionDelete($id=NULL)
	{
		if($id)
		{
			$this->database->table('content')->where('users_id = ?', $this->userSess->id)
									->where('id = ?', $id)->delete();
			$this->redirect(':Admin:Blog:Default:default');
		}
		else
		{
		     $selection = $this->database->table('content')->where('users_id = ?', $this->userSess->id);
		     $this->template->selection = $selection;
		}	
	}

	public function actionEdit($id)
	{
		$row = $this->database->table('content')->get($id);
		if (!$row)
		{
			$this->error('Príspevok nebol nájdený');
		}
		if($row->users_id != $this->userSess->id)
		{
			$this->flashMessage('Nemáte oprávnenie k úprave článku. Nie ste prihlásený ako jeho autor.');
			$this->redirect(':Admin:Blog:Default:default');			
		}
		$this['articleForm']->setDefaults($row->toArray());
		$this->template->id = $id;
	}

/////components/////////////////////////////////////////////////////////////////////////

     protected function createComponentArticleForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addText('title', 'Titulok:')
		->setRequired('Titulok je povinná položka')
		->setAttribute('class', 'formEl');
		
		$form->addTextArea('meta_desc','Stručný posis:(100znakov)')
		->setRequired('Popis musí byť vyplnený.')
		->addRule($form::MAX_LENGTH, 'Popis nesmie mať viac ako %d znakov.', 100)
		->setAttribute('class', 'area400 formEl');

		$form->addTextArea('content', 'Obsah:')
		->setRequired('Pole obsah nesmie byť prázdne')
		->setAttribute('class','area600 formEl');
		
		$form->addCheckbox('status', 'Zverejniť / skryť:')
		->setRequired('Pole status musí byť zaškrtnúté.')
		->setAttribute('class','formEl');
		
		$form->setDefaults(array('status' => TRUE));

		$form->addSubmit('send', 'Uložiť a publikovať');

		$form->onSuccess[] = $this->articleFormSucceeded;

		return $form;
	}

	public function articleFormSucceeded($form)
	{
		if ($this->userSess->id && $this->userSess->id < 10)
		{
			$this->flashMessage('Nemáte oprávnenie vytvárať a editovať články.');
			$this->redirect('Homepage:default');
		}
		// $form->getValues() sama ošetrí kľúče premennej $_POST
		// tým, že ich neberie z $_POST ale z PHP kódu
		$values = $form->getValues();
		// ziskame $_GET['posId']
		$id = $this->getParameter('id');

		if ($id)
		{
			$row = $this->database->table('content')->where('id ?', $id)->update($values);
			$this->flashMessage('Príspevok bol úspešne zeditovaný.', 'success');			
		} 
		else
		{
			$values['users_id'] = $this->userSess->id;
			$values['status'] = 1;
			$values['created_at'] = time();
			$row = $this->database->table('content')->insert($values);
			$this->flashMessage('Príspevok bol úspešne publikovaný.', 'success');
		}

		$this->redirect(':Admin:Blog:Default:default');
	}
}