<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/','Example','1f95-9552-f106-10eb-9ddc-3424-3e03-8d11','$2y$10$.j2r8zvBr74KOvLqCqhc8eExzuSCpNzpIP6SlkY7s30DmZDYEsI92');

// You can start your application now
echo 'Start Application';

exit;
