<?php

namespace App\Presenters;

use	Nette,
	Nette\Security\Passwords,
	App\Model,
	Nette\Diagnostics\Debugger;



/**
 * Registration presenter.
 */
class RegistPresenter extends \App\Presenters\BasePresenter
{
	/** @var App\Model\UserModel */
	private $userModel;	

	public function __construct(Model\UserModel $userModel)
	{
		$this->userModel = $userModel;
	}
	
	/**
	 * Registration form factory
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentRegistForm()
	{
		$form = new Nette\Application\UI\Form;              
		$form->addText('username', 'User name:')
			->setRequired('Vyplňte prosím meno.')
			->setAttribute('class', 'formEl');

		$form->addPassword('password', 'Password:')
			->setRequired('Zadajte prosím heslo.')
			->addRule($form::MIN_LENGTH, 'Zadajte prosím heslo s minimálne %d znakmi', 3)
			->setAttribute('class', 'formEl');
		
		$form->addPassword('password2', 'Password check:')
			->setRequired('Zadajte prosím heslo.')
			->addRule($form::EQUAL, 'Heslá sa nezhodujú. Zopakujte prosím kontrolu.', $form['password'])
			->setAttribute('class', 'formEl');
			
		$form->addText('email', 'Email:')
			->setRequired('Zadajte prosím emailovú adresu.')
			//->addCondition()
			->addRule($form::EMAIL, 'Nezadali ste platnú mailovú adresu. Opravte si prosím chybu.', $form['password'])
			->setAttribute('class', 'formEl');
			
		$form->addSubmit('send', 'Registrovať');

		$form->onSuccess[] = $this->registFormSucceeded;
		return $form;
	}


	public function registFormSucceeded($form)
	{
		$values = $form->getValues();

		try
		{
			$user = $this->userModel->registerUser($values);
		}
		catch(Model\Exceptions\DuplicateEntryException $e)
		{
			if($e->msg == 'username')
			{
				$form->addError('Meno '.$values['username'].' je už obsadené. Vyberte si prosím iné.');
			}
			elseif($e->msg == 'email')
			{
				$form->addError('Email '.$values['email'].' je už zaregistrovaný. Musíte uviesť unikátny email.');
			}
			return;
		}
		
		$this->userSess->username = $user['username'];
		$this->userSess->id = $user['id'];
		$this->userSess->created = $user['created'];
		$this->userSess->status = $user['status'];
	
		$this->flashMessage('Vitajte '.$values['username'].'. Vaša registrácia bola úspešná a loli ste automaticky prihlásený(á).');
		$this->redirect(':Default:');

	}

} 



