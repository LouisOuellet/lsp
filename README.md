# Licensing Software Platform

This software provide licensing and update services for php applications. The licensing service performs 2 checks. When you create your application in LSP, it will generate an application token which will need to be stored within your application as a hash. Once your application is created, you can start generating licenses. So license authentication works as followed. Your application will send a cURL request to the LSP server with the included license. The LSP server will then try to identify it this license exist in its database and only reply when one is found. And it will reply with the Application Token. Which you can then test locally in the application.

## Usage

## Example 1
```php
<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's replied against the hash.
// If it doesn't, then the class will exit the code and display Invalid License
$token = new LSP('http://localhost/index2.php','12345-12345-12345-12345-12345','$2y$10$BnwjifGipuexhkZGoEiIO.3ogtar42OyU/CSYkORpSV69OySS9is2');

// You can start your application now
echo 'Start Application';

exit;
```

## Example 2
```php
<?php

// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's replied against the hash.
// In this case a variable $token->Status will be used to display the application or display an activation form instead.
$token = new LSP('http://localhost/index2.php','12345-12345-12345-12345-12345','$2y$10$BnwjifGipuexhkZGoEiIO.3ogtar42OyU/CSYkORpSV69OySS9is2',TRUE);

// You can start your application now
if($token->Status){
	echo 'Start Application';
} else {
	echo 'Display Activation Form';
}

exit;
```
