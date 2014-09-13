<?php

namespace App\Model\Exceptions;

use PDOException;


/**
 * For inserting duplicate value to UNIQUE KEY field
 */
class DuplicateEntryException extends PDOException
{
	public $msg;
	
	public function __construct($msg)
	{
		$this->msg = $msg;
	}

}
