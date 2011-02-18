<?php
ini_set('display_errors', 0);

register_shutdown_function(function(){
	try{
	        $error = error_get_last();
		if($error){
                	$exception_handler = set_exception_handler(function(){});
                	$exception_handler(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        	}
	}
	catch(Exception $e){
		error_log("unexpected exception in register_shutdown_function:\n".print_r($e, true), 4);
	}
});

$old_error_handler = set_error_handler(
        function ($errno, $errstr, $errfile, $errline ) {
		if (!(error_reporting() & $errno)) {
			return;
		}

                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
);

if($old_error_handler){
        restore_error_handler();
        throw new Exception('error handler already defined');
}

