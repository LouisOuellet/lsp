<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('host','application','key','token');

// In this case a variable $lsp->Status will be used to display the application or display an activation form instead.
if($lsp->Status){
	if($lsp->Update){
		// We configure our database access
		$lsp->configdb('host', 'username', 'password', 'example');
		// We backup the database using a JSON file.
		$lsp->createRecords('backup.json');
		// We update the local files
		$lsp->update('branch');
		// We start updating our database
		$lsp->updateStructure('db.json');
		// We import skeleton records to the database using a JSON file.
		$lsp->insertRecords('skeleton.json');
	} else {
		echo 'No Updates Available';
	}

	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Show Activation Form';
}

exit;
