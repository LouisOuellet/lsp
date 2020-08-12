# Licensing Software Platform

This software provide licensing and update services for php applications. The licensing service performs 2 checks. When you create your application in LSP, it will generate an application token which will need to be stored within your application as a hash. Once your application is created, you can start generating licenses. So license authentication works as followed. Your application will send a cURL request to the LSP server with the included license. The LSP server will then try to identify it this license exist in its database and only reply when one is found. And it will reply with the Application Token. Which you can then test locally in the application.

```php
require_once('lsp.php');
$token = new LSP('http://localhost/','12345-12345-12345-12345-12345');
if($token->authenticate($hash)){
	# Your application
} else {
	echo 'invalid license';
}
```
