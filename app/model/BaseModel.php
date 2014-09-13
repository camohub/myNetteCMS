<?php

/**
 * This file is part of user model in Nette aplication 
 * Copyright (c) 2014 Čamo (http://web.php5.sk)
 */

namespace App\Model;

use 	Nette,
	Nette\Diagnostics\Debugger;


class BaseModel
{
	/** @var Nette\Database\Context */
	public $database;
		
	public function __construct(Nette\Database\Context $db)
	{
		$this->database = $db;
	}
	
}
