<?php
class mErrorresRprt extends Exception
{
	public function __construct($message, $code = 0, Exception $previous = null) 
	{
	$message = "Hay un problema en la carga del reporte".$message;
	parent::__construct($message, $code, $previous);
	}public function __toString() 
	{
	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	
}