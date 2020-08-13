<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('http://localhost/','Example','73b2-ce60-d953-03e8-65a5-f155-35fd-95da','$2y$10$rY6jd5gZE1ISJ7kPtE9kIODgi/7EBNrv0TQF1iyPIbY8GuiUvGZYa');

// In this case a variable $lsp->Status will be used to display the application or display an activation form instead.
if($lsp->Status){
	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Show Activation Form';
}

exit;
