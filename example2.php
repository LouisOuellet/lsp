<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
// In this case a variable $token->Status will be used to display the application or display an activation form instead.
$token = new LSP('http://localhost/index2.php','12345-12345-12345-12345-12345','$2y$10$BnwjifGipuexhkZGoEiIO.3ogtar42OyU/CSYkORpSV69OySS9is2',TRUE);

// You can start your application now
if($token->Status){
	echo 'Start Application';
} else {
	echo 'Display Activation Form';
}

exit;
