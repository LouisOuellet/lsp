# Licensing Software Platform

This software provide licensing services for applications. The licensing service performs 3 checks. When you create your application in LSP, it will generate an application token which will need to be stored within your application as a hash. Once your application is created, you can start generating licenses. License authentication works as followed. Your application will send a cURL request to the LSP server with the included license and a fingerprint of the application. The LSP server will then try to identify it this license exist in its database and only reply when one is found and validated. Then it will verify the application fingerprint against the activation fingerprint. If all is successful it will reply with the Application Token. Which you can then be tested locally in the application to validate the LSP server as a 3rd check.

## Change Log
 * [2020-08-18] - Adding Remote IP to license during activation.
 * [2020-08-17] - Improved encryption of the license during request. To prevent sniffing attacks.
 * [2020-08-17] - Adding license validation to the activation process.
 * [2020-08-17] - General code optimization.
 * [2020-08-17] - Improved documentation in README.md.
 * [2020-08-17] - Adding support for git request over http.
 * [2020-08-17] - Adding http clone link to app page.
 * [2020-08-17] - Added additionnal .htaccess files to limit access to the git folder.
 * [2020-08-17] - Modified form height within tables.
 * [2020-08-14] - Added support for Git clone using ssh.
 * [2020-08-14] - Added a MySQL Database Structure backup method to LSP.
 * [2020-08-14] - Added a MySQL Database Structure import method to LSP.
 * [2020-08-14] - Added a button "Clone" in the app page to get the repository.
 * [2020-08-14] - Improved fingerprint.
 * [2020-08-14] - Updated the welcome page.
 * [2020-08-13] - Added .htaccess files to the users & apps directories to prevent unauthorized access.
 * [2020-08-13] - Added a login system.
 * [2020-08-13] - Added verification for the existence of both apps and users directories. If missing, the system will create them.
 * [2020-08-13] - Added users CRUD.
 * [2020-08-13] - Now creates and initializes a Git repository within the apps folder during creation of an application.
 * [2020-08-13] - Added some fields to the keys. (status,owner,active,fingerprint)
 * [2020-08-13] - The validation process now validates a fingerprint taken from the application
 * [2020-08-13] - An activation process has been added

## Requirements for the LSP Server
 * Apache2 => Configured to use the git user
 * PHP
 	 * Allow shell_exec module
 * Git-Core

### Configuring PHP for LSP
#### Enable shell_exec function
In /etc/php/7.3/apache2/php.ini comment the line :
```php
disable_functions = ...
```
And add :
```php
disable_functions = ''
```

### Configuring apache2 for LSP
#### Run as git
If you do not configure apache2 to use git, then lsp will not be able to remove the repository when asked to. And would otherwise require sudo elevation.

 1. Open /etc/apache2/apache2.conf with your favorite editor
 2. Comment ```User ${APACHE_RUN_USER}```
 3. Comment ```Group ${APACHE_RUN_GROUP}```
 4. Insert ```User git```
 5. Insert ```Group git```
 6. Restart the service ```sudo service apache2 restart```

#### Configuring WebDAV
For LSP to provide http access to your application repository, you will need to enable WebDAV.

```bash
sudo a2enmod dav_fs
```
We also need to add this configuration file (git.conf).
```bash
Alias /git [local directory]/git

<Directory [local directory]/git>
  Options +Indexes
  DAV on
</Directory>
```
And enable it:
```bash
sudo a2enconf git
```
Finally we restart apache2:
```bash
sudo service apache2 restart
```
### Import your application ssh key to allow updates via SSH instead
By default, a ssh connection will require a password to be entered. This can prevent lsp from being able to pull the changes. Therefor, you need to copy your public ssh key to the lsp server.
```bash
ssh-keygen -t rsa
ssh-copy-id git@[host]
```

## Requirements for the LSP Class
 * PHP (Important)
 	 * Allow shell_exec module (Optional)(for the updates features)
 * Git-Core (Optional)(for the updates features)
 * MySQL (Optional)(for the updates features and if your application uses MySQL)

## Testing environment
### Hardware
 * Dual-Core Intel® Core™ i5-4310U CPU @ 2.00GHz
 * Intel Corporation Haswell-ULT Integrated Graphics Controller (rev 0b)
 * 7.9 GB memory
 * 471.5 GB storage (SATA SSD)
### Software
 * elementary OS 5.1.7 Hera
 * Apache/2.4.39 (Unix)
 * PHP 7.3.5 (cli) (built: May  3 2019 11:55:32) ( NTS )
 * MySQL Ver 15.1 Distrib 10.1.39-MariaDB

## Usage
### Licensing
#### Basics
```php
require_once('lsp.php');
$lsp = new LSP($LSP_server,$LSP_app,$LSP_license,$LSP_token);
```

#### Example
```php
// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('host','application','key','token');

// In this case a variable $lsp->Status will be used to
// display the application or display an activation form instead.
if($lsp->Status){
	// You can start your application now
	echo 'Start Application';
} else {
	echo 'Show Activation Form';
}

exit;
```
### Update Service
#### Update the Repository
With the configuration setup as above, the only way to push an update on the repository is using SSH. This setup provides access control to repository. So a developper would access the repository using SSH. While everyone else will use http request as Read-Only access.
#### Basics
LSP creates a repository for each application that can be use to store your application. Thus if you choose to do this, you can access the repository like so:

```bash
git clone [host]/git/[App Name].git
```
Or :
```bash
git clone git@[host]:[local directory]/git/[App Name].git
```

This setup will allow you to use git to provide updates to your application. Git is really useful to update the local files since you can preset directory or files that should be ignored using a .gitignore file in your repository. But what do we do for a mysql database. LSP comes with a method that allow us to compare a json file with your database structure and alter your database to match the json file. You can create the JSON file like this. Bare in mind that LSP will still require a valide license to create the file.

```php
// We need to include the LSP Class
require_once('lsp.php');
// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('host','application','key','token');
// We configure our database access
$lsp->configdb('host', 'username', 'password', 'example');
// We backup the database structure in a JSON file
$lsp->create('db.json');
```

#### Example
```php
// We need to include the LSP Class
require_once('lsp.php');

// Checks are done by verifying if the server replied and validating it's reply against the hash.
$lsp = new LSP('host','application','key','token');

// In this case a variable $lsp->Update will be used to
// display the start the update or report no update.
if($lsp->Update){
	// You can start your application now
	echo 'Start Updating';
	// We configure our database access
	$lsp->configdb('host', 'username', 'password', 'example');
	// We update the local files
	$lsp->update();
	// We start updating our database
	$lsp->updatedb('db.json');
} else {
	echo 'No update available';
}

exit;
```
