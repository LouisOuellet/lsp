<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('http://localhost/','Example','3679-01ed-3e09-d7b0-009c-56ce-6f87-9276','$2y$10$QXBDUHl.8IWq1CIMfvB4bejh9Qy.6tairZynorXFcmmF5b4xIUWY2');

// In this case a variable $lsp->Status will be used to display the application or display an activation form instead.
if($lsp->Status){
	if($lsp->Update){
		// We configure our database access
		$lsp->configdb('localhost', 'username', 'password', 'example');
		// We backup the database structure in a JSON file
		$lsp->create('db.json');

		// We update the local files
		$lsp->update('branch');
		// We start updating our database
		$lsp->updatedb('db.json');
	} else {
		echo 'No Updates Available';
	}

	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Show Activation Form';
}

exit;
