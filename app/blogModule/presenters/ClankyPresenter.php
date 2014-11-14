<?php
namespace App\BlogModule\Presenters;

use	Nette,
	Nette\Utils\Validators,
	Nette\Caching\Cache,
	Nette\Diagnostics\Debugger,
	App\Model;
	

class ClankyPresenter extends \App\Presenters\BasePresenter
{

	/** @var Nette\Caching\IStorage @inject */
	public $storage;


	public function __construct()
	{

	}
	
	public function startup()
	{
		parent::startup();
		
	}

	public function renderDefault($id)
	{
		$countAll = $this->database->table('blog')->where('status = ?', 1)->count('*');
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 3;
		$paginator->itemCount = $countAll;
		
		$this->template->contents = $this->database->table('blog')
							->select('blog.*, users.username')
							->order('created_at DESC')
							->limit($paginator->itemsPerPage, $paginator->offset);
	}
	
	public function renderShow($id, $title)
	{
		$selection = $this->database->table('blog')
								->select('blog.*, users.username')
								->where('blog.id = ?', $id)
								->limit(1);
		$this->template->content = $content = $selection->fetch();
		
		$this->template->comments = $content->related('comment')->order('created_at');
		
	}
	
	public function actionEdit($postId)
	{
		if (!$this->userSess->id)
		{
			$this->flashMessage('Nemáte oprávnenie k úprave článku.');
			$this->redirect('show', $postId);
		}
		$post = $this->database->table('posts')->get($postId);
		if (!$post)
		{
			$this->error('Príspevok nebol nájdený');
		}
		if($post->users_id != $this->userSess->id)
		{
			$this->flashMessage('Nemáte oprávnenie k úprave článku. Nie ste prihlásený ako jeho autor.');
			$this->redirect('show', $postId);			
		}
		$this['postForm']->setDefaults($post->toArray());
	}

	public function actionCreate()
	{
		if(!$this->userSess->id)
		{
			$this->flashMessage('Nemáte oprávnenie vytvárať nové články.');
			$this->redirect('Homepage:default');
		}
	}

/////component/////////////////////////////////////////////////////////////////////

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

/////component/////////////////////////////////////////////////////////////////////////

	protected function createComponentCommentForm()
	{
		// zrejme sa následne po vytiahnutí dát z DB engine pozerá po "komponentách"
		$form = new Nette\Application\UI\Form;

		$form->addText('name', 'Jméno:')
		->setRequired('Meno je povinná položka')
		->setAttribute('class', 'formEl');

		$form->addText('email', 'Email:')
		->setAttribute('class','formEl');
		
		$form->addTextArea('content', 'Komentář:')
			->setRequired('Komentár je povinná položka')
			->setAttribute('class','area400 formEl');

		$form->addSubmit('send', 'Publikovat komentář');
		// určíme čo sa má stať, po odoslaní formulára = zavoláme metódu commentFormSucceeded
		$form->onSuccess[] = $this->commentFormSucceeded; // bez zátvoriek

		return $form;
	}

	public function commentFormSucceeded($form)
	{
		// vykoná sa po ododslaní formuláru
		$values = $form->getValues();
		$id = $this->getParameter('id');

		$row = $this->database->table('comments')->insert(array('content_id' => $id,
														'name' => $values->name,
														'email' => $values->email,
														'content' => $values->content,
													));

		if($row)
		{
			$this->flashMessage('Ďakujeme za komentár', 'success');
			$this->redirect('this');
		}
		else
		{
			$form->addError('Došlo k chybe. Váš komentár sa nepodarilo odslať. Skúste to ešte raz.');
		}
	}

/////component/////////////////////////////////////////////////////////////////

     protected function createComponentPostForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addText('title', 'Titulek:')
		->setRequired('Titulok je povinná položka')
		->setAttribute('class', 'formEl');

		$form->addTextArea('content', 'Obsah:')
		->setRequired('Pole obsah nesmie byť prázdne')
		->setAttribute('class','area600 formEl');

		$form->addSubmit('send', 'Uložit a publikovat');

		$form->onSuccess[] = $this->postFormSucceeded;

		return $form;
	}

	public function postFormSucceeded($form)
	{
		if (!$this->userSess->id)
		{
			$this->flashMessage('Nemáte oprávnenie vytvárať nové články.');
			$this->redirect('Homepage:default');
		}
		// $form->getValues() sama ošetrí kľúče premennej $_POST
		// tým, že ich neberie z $_POST ale z PHP kódu
		$values = $form->getValues();
		// ziskame $_GET['posId']
		$postId = $this->getParameter('postId');

		if ($postId)
		{
			$post = $this->database->table('blog')->where('id ?', $postId)->update($values);
			$this->flashMessage('Príspevok bol úspešne zeditovaný.', 'success');			
		} 
		else
		{
			$values['users_id'] = $this->userSess->id;
			$values['visible'] = 1;
			$post = $this->database->table('blog')->insert($values);
			$postId = $post->id;
			$this->flashMessage('Príspevok bol úspešne publikovaný.', 'success');
		}

		$this->redirect('show', $postId, $values['title']);
	}

}
