<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/','example','a76e-241c-242f-4b67-208f-0d23-1f37-da1a','$2y$10$CUNaEQPuRhHG1v3TwzLzQ.VJZEWL73C8p45BGfmmKvCNTHzSubHea');

// You can start your application now
echo 'Start Application';

exit;
