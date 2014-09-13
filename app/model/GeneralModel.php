<?php

/**
 * This file is part of user model in Nette aplication 
 * Copyright (c) 2014 Čamo (http://web.php5.sk)
 */

namespace App\Model;

use 	Nette,
	Nette\Diagnostics\Debugger;


class GeneralModel extends \App\Model\BaseModel
{
		
	public function __construct()
	{
		// create database connection
		parent::__construct();
	}
	
	/**
	 * @param  string  $table
	 * @param  string|NULL  $condition
	 * @param  string|NULL  $order
	 * @param  int|NULL  $limit
	 * @param  int|NULL  $offset	 	 
	 * 	 	   	 		
	 * @return selection
	 */
	public function getTable($table, $condition=NULL, $order=NULL, $limit=NULL, $offset=NULL)
	{
		$table = $this->database->table($table);
		
		if($condition)
		{
			$table->where($condition);
		}
		if($order)
		{
			$table->order($order);
		}
		if($limit)
		{
			$table->limit($limit, $offset);
		}
		
		return $table;
	
	}
	
	public function getTest()
	{
		return $this->test ? $this->test : 'prd makový' ;	
	}
	
	public function getThis()
	{
		return $this;
	} 

}
