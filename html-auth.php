<?php
	$username = ''; 
	$password = '';
	$range = '';
	
	if (!isset($_SERVER['PHP_AUTH_USER']) || 
		!isset($_SERVER['PHP_AUTH_PW']) ||
	    ($_SERVER['PHP_AUTH_USER'] != $username) || 
	    ($_SERVER['PHP_AUTH_PW'] != $password)) 
    { 	
		header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Basic realm= "'.$range.'"');
		exit('<h1 style="text-align:center; margin-top:30px; ">Access denied.</h1>');
    }
?>
