<?php

class PHP_Error_Handler {
	protected $errorHandler;
	protected $errorsAsExceptions;
	protected $canceled;

	public function __construct($errorHandler=NULL, $errorsAsExceptions=false, $overwriteHandler=false) {
		if($errorHandler && !is_callable($errorHandler)) {
			throw new InvalidArgumentException('$errorHandler passed, but it isn\'t callable!');
		}
		$this->errorHandler = $errorHandler;
		$this->errorsAsExceptions = (bool) $errorsAsExceptions;
		if($errorsAsExceptions){
			$oldErrorHandler = set_error_handler(array($this, 'errorHandler'));
			if($oldErrorHandler && !$overwriteHandler) {
				restore_error_handler();
				throw new InvalidArgumentException('error handler is already set');
			}
		}
		register_shutdown_function(array($this, 'shutdownHandler'));
	}

	public function __destruct() {
		if($this->errorsAsExceptions){
			restore_error_handler();
		}
		$this->canceled = true;
	}

	public function shutdownHandler() {
		try{
			$error = error_get_last();
			if($error && !$this->canceled){
				if($this->errorsAsExceptions) {
					if(!$this->errorHandler) {
						$this->errorHandler = set_exception_handler(array($this, 'errorHandler'));
						restore_error_handler();
						if(!$this->errorHandler) {
							error_log('cannot find the exception handler for error: '.print_r($error, true));
							return;
						}
					}

                			call_user_func($this->errorHandler,new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
				}
				else {
					if(!$this->errorHandler) {
						$this->errorHandler = set_error_handler(array($this, 'errorHandler'));
						restore_error_handler();
						if(!$this->errorHandler) {
							return;
						}
					}
					call_user_func($this->errorHandler, $error['type'], $error['message'], $error['file'], $error['line']);
				}
			}
		}
		catch(Exception $e){
			error_log("exception cannot be thrown from the shutdownHandler:\n".print_r($e, true), 4);
        	}
	}

	public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext=NULL) {
		throw New ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
}
