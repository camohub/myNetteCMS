<?php

namespace App\Presenters;

use	Nette,
	App\Model,
	Nette\Security\Passwords,
	Nette\Diagnostics\Debugger;
	

/**
 * Sign in/out presenters.
 */
class SignPresenter extends \App\Presenters\BasePresenter
{
	/** @var Model\UserModel */
	private $userModel;

	public function __construct(Model\UserModel $userModel)
	{
		$this->userModel = $userModel; 
	}
	
	public function startup()
	{
		parent::startup();
	}
	
	public function renderDefault()
	{
		$this->template->setFile(__DIR__ . '/../templates/Sign/in.latte');
	}
	
	public function renderIn()
	{
	
	}
	
	public function actionOut()
	{
		$this->userSess->remove();
		$this->flashMessage('Boli ste odhlásený.');
		$this->redirect(':Default:');
	}

//////components///////////////////////////////////////////////////////////////	

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.')
			->setAttribute('class', 'formEl');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.')
			->setAttribute('class', 'formEl');

		$form->addSubmit('send', 'Sign in');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}


	public function signInFormSucceeded($form)
	{
		$values = $form->getValues();
		
		$user = $this->userModel->signInUser($values);
		
		if(!$user)
		{
			$form->addError('Zadané meno, alebo heslo neexistuje. Skontrolujte, či nemáte v údajoch chybu.');				
		}
		else
		{
			$this->userSess->username = $user['username'];
			$this->userSess->id = $user['id'];
			$this->userSess->created = $user['created'];
			$this->userSess->status = $user['status'];
		
			$this->flashMessage('Vitajte '.$values['username'].'. Vaše prihlásenie bolo úspešné.');
			$this->redirect(':Default:');
		}      
			                       
	}

}

