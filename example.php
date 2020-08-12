<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/','test','f53a-b798-b9ec-a894-91fa-a76c-c9ae-1897','$2y$10$PiBAwDPnaRDv44S9WI49VObo1Ife/Yu/QjCQBBHd72ixUgyBd63BW');

// You can start your application now
echo 'Start Application';

exit;
