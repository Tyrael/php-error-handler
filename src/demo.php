<?php
ini_set('display_errors', 'Off');
include 'php-error-handler.php';
set_exception_handler(
        function (Exception $e) {
                echo "exception_handler:\n";
                var_dump($e);
        }
);

// comment out one of the lines below to test the errors

//include 'e_error.php';
//include 'e_parse.php';
//include 'e_core_error.php';
//include 'e_compile_error.php';
