<?php
ini_set('display_errors', 'Off');
error_reporting(-1);
header("Content-Type: text/plain");

require dirname(__FILE__).DIRECTORY_SEPARATOR.'php-error-handler.php';

function my_exception_handler(Exception $e){
	var_dump('Exception handler:');
	var_dump($e);
}

set_exception_handler('my_exception_handler');

$PHPErrorHandler = new PHP_Error_Handler(NULL, true);

try{
	echo $foo;
}
catch(Exception $e) {
	echo "Exception was thrown\n";
	var_dump($e);
}

// comment out one of the lines below to test the fatal errors

//include 'e_error.php';
//include 'e_parse.php';
//include 'e_core_error.php';
//include 'e_compile_error.php';
