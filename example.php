<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/','Example','afa1-f539-31bb-f980-ad8a-ec49-a4f3-85c3','$2y$10$IRrnap4vZtxZTXWDA/itXuxoutVnmjboRypgIhReHPAqGrMWqK9V6');

// You can start your application now
echo 'Start Application';

exit;
