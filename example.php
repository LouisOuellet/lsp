<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('http://localhost/','Example','3128-54c3-2317-2afd-a3fd-6790-4f2b-48f2','$2y$10$pe1JZLe8S0Mu8GNTHwf9rOPX/zJwb1DfUemKjz5G9oGVypHFfnFim');

// In this case a variable $lsp->Status will be used to display the application or display an activation form instead.
if($lsp->Status){

	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Show Activation Form';
}

exit;
