<?php

/**
 * This file is part of user model in Nette aplication 
 * Copyright (c) 2014 Čamo (http://web.php5.sk)
 */

namespace App\Model;

use 	Nette,
	Nette\Security\Passwords,
	Nette\Diagnostics\Debugger;

/**
 * @method RegisterUser
 */
class UserModel extends BaseModel
{
	/*	
	public function __construct(Nette\Database\Context $db)
	{
		// when rewriteing parent __construct must create database connection here
		// cause injection do not works then in BaseModel
		parent::__construct($db);
	} */
	
	/**
	 * @param  array $params
	 * @param  string  $table
	 * 	 		
	 * @return activeRow
	 * 
	 * @throw  Nette\InvalidArgumentException
	 * @throw  Model\Exceptions\DuplicateEntry	 	 	 
	 */
	public function registerUser(Nette\Utils\ArrayHash $values)
	{
		$hash = Passwords::hash($values['password']);
		$params = array('password' => $hash,
					'username' => $values['username'],
					'email' => $values['email'],
					'status' => 10,
					'created' => time() 
					);
		try
		{
			$row = $this->insert($params);
		}
		catch(\PDOException $e)
		{
			// This catch ONLY checks duplicate entry to fields with UNIQUE KEY
			$info = $e->errorInfo;
        		
			// mysql==1062  sqlite==19  postgresql==23505
			if ($info[0] == 23000 && $info[1] == 1062)
			{
				// if/elseif returns the name of problematic field and value
				if( $this->getTable()->where('username = ?', $values['username'])->fetch() )
				{
					$msg = 'username';
				}
				elseif( $this->getTable()->where('email = ?', $values['email'])->fetch() )
				{
					$msg = 'email';
				}
				
				throw new Exceptions\DuplicateEntryException($msg);
				
			}
			else throw $e;
		}

		return $row;
	}
	
	/**
	 * Method checks if user exists
	 * @param array
	 * @param string
	 * 
	 * @return DB row or false
	 */	 	 	 	 	
	public function signInUser($values)
	{
		$row = $this->getTable()->where('username = ?', $values['username'])->limit(1)->fetch();
		
		if($row && Passwords::verify($values['password'], $row['password']) )
		{
				return $row; 
 		}

		return false;
		
	}
	
	protected function getTable()
	{
		return $this->database->table('users');
	}
	
	/**
	 * @return activeRow or false
	 */ 	 	
	protected function insert(array $params)
	{
		return $this->getTable()->insert($params);	
	}

}
