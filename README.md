# Licensing Software Platform

This software provide licensing services for applications. The licensing service performs 2 checks. When you create your application in LSP, it will generate an application token which will need to be stored within your application as a hash. Once your application is created, you can start generating licenses. So license authentication works as followed. Your application will send a cURL request to the LSP server with the included license. The LSP server will then try to identify it this license exist in its database and only reply when one is found. And it will reply with the Application Token. Which you can then be tested locally in the application to validate the LSP server.

## Change Log
 * [2020-08-13] - Added .htaccess files to the users & apps directories to prevent unauthorized access.
 * [2020-08-13] - Added a login system.
 * [2020-08-13] - Added verification for the existence of both apps and users directories. If missing, the system will create them.
 * [2020-08-13] - Added users CRUD.
 * [2020-08-13] - Now creates and initializes a Git repository within the apps folder during creation of an application.
 * [2020-08-13] - Added some fields to the keys. (status,owner,active,fingerprint)
 * [2020-08-13] - The validation process now validates a fingerprint taken from the application
 * [2020-08-13] - An activation process has been added

## Requirements
 * Apache2
 * PHP
 	 * Allow shell_exec module
 * Git-Core

## Tested on
### Hardware
 * Dual-Core Intel® Core™ i5-4310U CPU @ 2.00GHz
 * Intel Corporation Haswell-ULT Integrated Graphics Controller (rev 0b)
 * 7.9 GB memory
 * 471.5 GB storage (SATA SSD)
### Software
 * elementary OS 5.1.7 Hera
 * Apache/2.4.39 (Unix)
 * PHP 7.3.5 (cli) (built: May  3 2019 11:55:32) ( NTS )

## Usage
### Basics
```php
require_once('lsp.php');

$lsp = new LSP($LSP_server,$LSP_app,$LSP_license,$LSP_token);
```

Additionnaly if you want to be able to display an activation form if the application is not validated, you can add TRUE as the 5th argument to LSP. This will tell LSP not to exit the code and instead set a public variable $lsp->Status with the Boolean value of the result.

### Example 1
```php
// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('http://localhost/','Example','73b2-ce60-d953-03e8-65a5-f155-35fd-95da','$2y$10$rY6jd5gZE1ISJ7kPtE9kIODgi/7EBNrv0TQF1iyPIbY8GuiUvGZYa');

// In this case a variable $token->Status will be used to display the application or display an activation form instead.
if($lsp->Status){
	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Fingerprint: '.md5($_SERVER['SERVER_ADDR'].$_SERVER['SERVER_NAME']);
	$lsp->activate();
}

exit;
```
