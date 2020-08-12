<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's replied against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/index2.php','12345-12345-12345-12345-12345','$2y$10$BnwjifGipuexhkZGoEiIO.3ogtar42OyU/CSYkORpSV69OySS9is2');

// You can start your application now
echo 'Start Application';

exit;
