<?php

/**
 * An exception creatable from error info from
 * PDO::getErrorInfo()
 */
class DatabaseException extends Exception {

	/**
	 * Constructor.
	 */
	public function __construct($errorInfo, $previous=NULL) {
		parent::__construct($errorInfo[2],$errorInfo[1],$previous);
	}
}