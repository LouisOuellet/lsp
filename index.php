<?php
session_start();
if(isset($_GET['license'],$_GET['app'],$_GET['fingerprint'],$_GET['action'])){
	if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json')){
		$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json'),true);
		$app=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/app.json'),true);
		if((isset($keys[$_GET['license']]))&&(password_verify($_GET['license'], $keys[$_GET['license']]['hash']))&&($keys[$_GET['license']]['status'])){
			switch($_GET['action']){
				case"validate":
					if($keys[$_GET['license']]['active']){
						if((password_verify($_GET['fingerprint'], $keys[$_GET['license']]['fingerprint']))){
							echo $app['token'];exit;
						}
					}
					break;
				case"activate":
					if(!$keys[$_GET['license']]['active']){
						$keys[$_GET['license']]['active']=TRUE;
						$keys[$_GET['license']]['fingerprint']=password_hash($_GET['fingerprint'], PASSWORD_DEFAULT);
						unlink(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json');
						$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json', 'w');
						fwrite($json, json_encode($keys));
						fclose($json);
						echo $app['token'];exit;
					}
					break;
			}
		}
	}
} else {
	if(!is_dir(dirname(__FILE__,1).'/apps')){
		mkdir(dirname(__FILE__,1).'/apps');
		if(!file_exists(dirname(__FILE__,1).'/apps/.htaccess')){
			$htaccess=fopen(dirname(__FILE__,1).'/apps/.htaccess', 'w');
			fwrite($htaccess, "Order deny,allow\n");
			fwrite($htaccess, "Deny from all\n");
			fclose($htaccess);
		}
	}
	if(!is_dir(dirname(__FILE__,1).'/git')){
		mkdir(dirname(__FILE__,1).'/git');
		if(!file_exists(dirname(__FILE__,1).'/git/.htaccess')){
			$htaccess=fopen(dirname(__FILE__,1).'/git/.htaccess', 'w');
			fwrite($htaccess, "Order deny,allow\n");
			fwrite($htaccess, "Deny from all\n");
			fclose($htaccess);
		}
	}
	if(!empty($_POST)){
		if((isset($_POST['GetStarted'],$_POST['username'],$_POST['password']))&&(!empty($_POST['username']))&&(!empty($_POST['password']))){
			if(!file_exists(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json')){
				if(!is_dir(dirname(__FILE__,1).'/users')){
					mkdir(dirname(__FILE__,1).'/users');
				}
				if(!file_exists(dirname(__FILE__,1).'/users/.htaccess')){
					$htaccess=fopen(dirname(__FILE__,1).'/users/.htaccess', 'w');
					fwrite($htaccess, "Order deny,allow\n");
					fwrite($htaccess, "Deny from all\n");
					fclose($htaccess);
				}
				$user['password']=password_hash($_POST['password'], PASSWORD_DEFAULT);
				$json = fopen(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json', 'w');
				fwrite($json, json_encode($user));
				fclose($json);
			}
		}
	}
	if((!is_dir(dirname(__FILE__,1).'/users'))||(count(scandir(dirname(__FILE__,1).'/users')) <= 3)){ ?>
		<!doctype html>
		<html lang="en" class="h-100">
		  <head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		    <meta name="description" content="A licensing and update service">
		    <meta name="author" content="Louis Ouellet, https://github.com/LouisOuellet">
		    <title>Licensing Software Platform</title>
		    <!-- Bootstrap core CSS -->
		    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
			  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
				<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
				<script src="https://kit.fontawesome.com/4f8426d3cf.js" crossorigin="anonymous"></script>
				<style>
					.vertical-input-group .input-group:first-child {
					  padding-bottom: 0;
					}
					.vertical-input-group .input-group:first-child * {
					  border-bottom-left-radius: 0;
					  border-bottom-right-radius: 0;
					}
					.vertical-input-group .input-group:last-child {
					  padding-top: 0;
					}
					.vertical-input-group .input-group:last-child * {
					  border-top-left-radius: 0;
					  border-top-right-radius: 0;
					}
					.vertical-input-group .input-group:not(:last-child):not(:first-child) {
					  padding-top: 0;
					  padding-bottom: 0;
					}
					.vertical-input-group .input-group:not(:last-child):not(:first-child) * {
					  border-radius: 0;
					}
					.vertical-input-group .input-group:not(:first-child) * {
					  border-top: 0;
					}
				</style>
		  </head>
		  <body class="pt-5">
				<form method="post">
					<div class="container">
						<div class="border-bottom mb-5">
							<h4 class="display-4">Create your first user</h4>
						</div>
						<div class="form-group">
							<div class="vertical-input-group">
								<div class="input-group">
									<input class="form-control" type="text" name="username" placeholder="Username">
								</div>
								<div class="input-group">
									<input class="form-control" type="password" name="password" placeholder="Password">
								</div>
								<div class="input-group">
									<input class="btn btn-success btn-block" type="submit" name="GetStarted" value="Get Started">
								</div>
							</div>
						</div>
					</div>
				</form>
			</body>
		</html>
		<?php exit;
	}
	if(!empty($_POST)){
		if((isset($_POST['Login'],$_POST['username'],$_POST['password']))&&(!empty($_POST['username']))&&(!empty($_POST['password']))){
			if(file_exists(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json')){
				$user=json_decode(file_get_contents(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json'),true);
				if(password_verify($_POST['password'], $user['password'])){
					$_SESSION['lsp']=$_POST['username'];
				}
			}
		}
		if(isset($_POST['Logout'])){
			session_unset();
			session_destroy();
			session_start();
		}
	}
	if((!isset($_SESSION['lsp']))||(!file_exists(dirname(__FILE__,1).'/users/'.$_SESSION['lsp'].'.json'))){?>
		<!doctype html>
		<html lang="en" class="h-100">
		  <head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		    <meta name="description" content="A licensing and update service">
		    <meta name="author" content="Louis Ouellet, https://github.com/LouisOuellet">
		    <title>Licensing Software Platform</title>
		    <!-- Bootstrap core CSS -->
		    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
			  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
				<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
				<script src="https://kit.fontawesome.com/4f8426d3cf.js" crossorigin="anonymous"></script>
				<style>
					.vertical-input-group .input-group:first-child {
					  padding-bottom: 0;
					}
					.vertical-input-group .input-group:first-child * {
					  border-bottom-left-radius: 0;
					  border-bottom-right-radius: 0;
					}
					.vertical-input-group .input-group:last-child {
					  padding-top: 0;
					}
					.vertical-input-group .input-group:last-child * {
					  border-top-left-radius: 0;
					  border-top-right-radius: 0;
					}
					.vertical-input-group .input-group:not(:last-child):not(:first-child) {
					  padding-top: 0;
					  padding-bottom: 0;
					}
					.vertical-input-group .input-group:not(:last-child):not(:first-child) * {
					  border-radius: 0;
					}
					.vertical-input-group .input-group:not(:first-child) * {
					  border-top: 0;
					}
				</style>
		  </head>
		  <body class="pt-5">
				<form method="post">
					<div class="container">
						<div class="border-bottom mb-5">
							<h4 class="display-4">Login</h4>
						</div>
						<div class="form-group">
							<div class="vertical-input-group">
								<div class="input-group">
									<input class="form-control" type="text" name="username" placeholder="Username">
								</div>
								<div class="input-group">
									<input class="form-control" type="password" name="password" placeholder="Password">
								</div>
								<div class="input-group">
									<input class="btn btn-primary btn-block" type="submit" name="Login" value="Login">
								</div>
							</div>
						</div>
					</div>
				</form>
			</body>
		</html>
		<?php exit;
	}
	if(!empty($_POST)){
		if(isset($_POST['CreateApp'],$_POST['name'])){
			if(!empty($_POST['name'])){
				if(!is_dir(dirname(__FILE__,1).'/apps')){ mkdir(dirname(__FILE__,1).'/apps'); }
				if(!is_dir(dirname(__FILE__,1).'/git')){ mkdir(dirname(__FILE__,1).'/git'); }
				if(!is_dir(dirname(__FILE__,1).'/apps/'.$_POST['name'])){
					mkdir(dirname(__FILE__,1).'/apps/'.$_POST['name']);
					mkdir(dirname(__FILE__,1).'/git/'.$_POST['name'].'.git');
					shell_exec("git init --bare ".dirname(__FILE__,1).'/git/'.$_POST['name'].'.git');
					$file = fopen(dirname(__FILE__,1).'/git/'.$_POST['name'].'.git/objects/info/packs', 'w');
					fwrite($file, json_encode("\n"));
					fclose($file);
					shell_exec("git init --bare ".dirname(__FILE__,1).'/git/'.$_POST['name'].'.git');
					$file = fopen(dirname(__FILE__,1).'/git/'.$_POST['name'].'.git/info/refs', 'w');
					fwrite($file, json_encode(""));
					fclose($file);
					$htaccess=fopen(dirname(__FILE__,1).'/git/'.$_POST['name'].'.git/.htaccess', 'w');
					fwrite($htaccess, "Order deny,allow\n");
					fwrite($htaccess, "Allow from all\n");
					fclose($htaccess);
					$app['token']=md5($_POST['name'].date("Y/m/d h:i:s"));
					$json = fopen(dirname(__FILE__,1).'/apps/'.$_POST['name'].'/app.json', 'w');
					fwrite($json, json_encode($app));
					fclose($json);
				}
			}
		}
		if(isset($_POST['GenKey'],$_POST['amount'],$_GET['name'])){
			if(is_dir(dirname(__FILE__,1).'/apps/'.$_GET['name'])){
				if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
					$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				}
				for ($x = 1; $x <= $_POST['amount']; $x++) {
					$key=implode("-", str_split(md5($_GET['name'].$x.date("Y/m/d h:i:s")), 4));
					$keys[md5($key)]=[
						'key' => $key,
						'hash' => password_hash(md5($key), PASSWORD_DEFAULT),
						'status' => FALSE,
						'active' => FALSE,
					];
				}
				if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
					unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				}
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['DeleteKey'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				unset($keys[$_POST['DeleteKey']]);
				unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['StatusEnable'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				$keys[$_POST['StatusEnable']]['status']=TRUE;
				unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['StatusDisable'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				$keys[$_POST['StatusDisable']]['status']=FALSE;
				unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['Activate'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				$keys[$_POST['Activate']]['active']=TRUE;
				unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['Deactivate'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
				$keys[$_POST['Deactivate']]['active']=FALSE;
				unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
				$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
				fwrite($json, json_encode($keys));
				fclose($json);
			}
		}
		if(isset($_POST['SaveKey'],$_GET['name'])){
			if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){
				if(isset($_POST['owner'])){
					$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
					$keys[$_POST['SaveKey']]['owner']=$_POST['owner'];
					unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
					$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
					fwrite($json, json_encode($keys));
					fclose($json);
				} else {
					$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true);
					unset($keys[$_POST['SaveKey']]['owner']);
					unlink(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json');
					$json = fopen(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json', 'w');
					fwrite($json, json_encode($keys));
					fclose($json);
				}
			}
		}
		if(isset($_POST['DeleteApp'])){
			if(is_dir(dirname(__FILE__,1).'/apps/'.$_POST['DeleteApp'])){
				shell_exec("rm -rf ".dirname(__FILE__,1).'/apps/'.$_POST['DeleteApp']);
				shell_exec("rm -rf ".dirname(__FILE__,1).'/git/'.$_POST['DeleteApp'].'.git');
			}
		}
		if(isset($_POST['DeleteUser'])){
			if(file_exists(dirname(__FILE__,1).'/users/'.$_POST['DeleteUser'].'.json')){
				unlink(dirname(__FILE__,1).'/users/'.$_POST['DeleteUser'].'.json');
			}
		}
		if((isset($_POST['SaveUser'],$_POST['password'],$_POST['password2']))&&(!empty($_POST['password']))&&(!empty($_POST['password2']))){
			if($_POST['password'] == $_POST['password2']){
				unlink(dirname(__FILE__,1) . '/users/'.$_GET['name'].'.json');
				$user['password']=password_hash($_POST['password'], PASSWORD_DEFAULT);
				$json = fopen(dirname(__FILE__,1).'/users/'.$_GET['name'].'.json', 'w');
				fwrite($json, json_encode($user));
				fclose($json);
			}
		}
		if((isset($_POST['CreateUser'],$_POST['username'],$_POST['password'],$_POST['password2']))&&(!empty($_POST['username']))&&(!empty($_POST['password']))&&(!empty($_POST['password2']))){
			if($_POST['password'] == $_POST['password2']){
				if(file_exists(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json')){
					unlink(dirname(__FILE__,1) . '/users/'.$_POST['username'].'.json');
				}
				$user['password']=password_hash($_POST['password'], PASSWORD_DEFAULT);
				$json = fopen(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json', 'w');
				fwrite($json, json_encode($user));
				fclose($json);
			}
		}
	}
	if((isset($_GET['p']))&&($_GET['p'] != '')){
		$page=$_GET['p'];
	} else {
		$page='index';
	}
	?>
	<!doctype html>
	<html lang="en" class="h-100">
	  <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="A licensing and update service">
	    <meta name="author" content="Louis Ouellet, https://github.com/LouisOuellet">
	    <title>Licensing Software Platform</title>
	    <!-- Bootstrap core CSS -->
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
			<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
			<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
			<script src="https://kit.fontawesome.com/4f8426d3cf.js" crossorigin="anonymous"></script>
	  </head>
	  <body class="d-flex flex-column h-100">
	    <header>
	      <!-- Fixed navbar -->
	      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
	        <a class="navbar-brand" href="?p="><i class="fas fa-file-contract mr-2"></i>Licensing Software Platform</a>
	        <div class="navbar-collapse">
	          <ul class="navbar-nav mr-auto">
							<li class="nav-item">
	              <a class="nav-link <?php if($page == 'apps'){ echo 'active'; }?>" href="?p=apps"><i class="fas fa-code mr-2"></i>Apps</a>
	            </li>
							<li class="nav-item">
	              <a class="nav-link <?php if($page == 'users'){ echo 'active'; }?>" href="?p=users"><i class="fas fa-users mr-2"></i>Users</a>
	            </li>
	            <li class="nav-item">
	              <a class="nav-link" href="https://github.com/LouisOuellet/lsp"><i class="fab fa-github mr-2"></i>GitHub</a>
	            </li>
	          </ul>
						<form class="form-inline my-2 my-lg-0" method="post">
							<span class="badge badge-primary" style="font-size:18px;margin-top:2px;">
								<i class="fas fa-user mr-1"></i>
								<?=$_SESSION['lsp']?>
							</span>
		          <button class="btn btn-outline-primary ml-3 my-2 my-sm-0" type="submit" name="Logout">Logout</button>
		        </form>
	        </div>
	      </nav>
	    </header>
	    <!-- Begin page content -->
	    <main role="main" class="flex-shrink-0" style="padding-top:50px;">
				<?php if(!empty($error)){ ?>
					<div class="container pt-5">
						<div class="row">
							<?php foreach($error as $err){ ?>
								<div class="col-12">
									<div class="alert alert-danger" role="alert">
										<?=$err?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php switch($page){
					case "apps":
						if((!isset($_GET['name']))||(!is_file(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/app.json'))){?>
							<div class="container pt-4">
								<div class="col-12 border-bottom mb-5 pl-0">
									<h3 class="display-4">
										Your Apps
										<button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#new">
											<i class="fas fa-plus mr-1"></i>
											New
										</button>
									</h3>
									<form method="post">
										<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog" role="document">
										    <div class="modal-content">
										      <div class="modal-header bg-success text-light">
										        <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>New Application</h5>
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										          <span aria-hidden="true">&times;</span>
										        </button>
										      </div>
										      <div class="modal-body">
														<div class="row">
															<div class="col-12">
																<div class="form-group">
													        <div class="input-group">
													          <div class="input-group-prepend">
													            <span class="input-group-text">
													              <i class="fas fa-code mr-2"></i>Name
													            </span>
													          </div>
													          <input type="text" class="form-control" name="name" placeholder="Application Name">
													        </div>
													      </div>
															</div>
														</div>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										        <button type="submit" name="CreateApp" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Create</button>
										      </div>
										    </div>
										  </div>
										</div>
									</form>
								</div>
								<table class="table table-striped display">
							    <thead>
						        <tr>
					            <th>Application</th>
					            <th style="width:250px;">Action</th>
						        </tr>
							    </thead>
							    <tbody>
										<?php foreach(scandir(dirname(__FILE__,1) . '/apps/') as $app){ ?>
											<?php if(("$app" != "..") and ("$app" != ".") and ("$app" != ".htaccess")){ ?>
								        <tr>
							            <td><?=$app?></td>
							            <td>
														<form method="post">
															<a href="?p=apps&name=<?=$app?>" class="btn btn-sm btn-primary">
																<i class="fas fa-eye mr-1"></i>
																Details
															</a>
															<button type="submit" name="DeleteApp" value="<?=$app?>" class="btn btn-sm btn-danger">
																<i class="fas fa-trash-alt mr-1"></i>
																Delete
															</button>
														</form>
													</td>
								        </tr>
											<?php } ?>
										<?php } ?>
							    </tbody>
								</table>
							</div>
						<?php } else {
							$application=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/app.json'),true);
							?>
							<div class="container pt-4">
								<div class="col-12 border-bottom mb-5 pl-0">
									<h3 class="display-4">
										<?=$_GET['name']?>
										<button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#clone">
											<i class="fas fa-clone mr-1"></i>
											Clone
										</button>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#token">
											<i class="fas fa-ticket-alt mr-1"></i>
											Get Token Hash
										</button>
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#generate">
											<i class="fas fa-key mr-1"></i>
											Generate
										</button>
									</h3>
									<div class="modal fade" id="token" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header bg-primary text-light">
													<h5 class="modal-title"><i class="fas fa-hashtag mr-2"></i>Hash</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="row">
														<div class="col-12">
															<div class="form-group">
																<textarea class="form-control" style="resize: none;"><?=password_hash($application['token'], PASSWORD_DEFAULT)?></textarea>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<div class="modal fade" id="clone" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header bg-info text-light">
													<h5 class="modal-title"><i class="fas fa-clone mr-2"></i>Clone</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="row">
														<div class="col-12">
															<div class="form-group">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<span class="input-group-text">
																			<i class="fas fa-globe-americas mr-2"></i>HTTP
																		</span>
																	</div>
																	<input type="text" class="form-control" value="<?=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'].'/'?>git/<?=$_GET['name']?>.git" >
																</div>
															</div>
														</div>
														<div class="col-12">
															<div class="form-group">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<span class="input-group-text">
																			<i class="fas fa-lock mr-2"></i>SSH
																		</span>
																	</div>
																	<input type="text" class="form-control" value="git@<?=$_SERVER['SERVER_ADDR']?>:<?=dirname(__FILE__,1).'/git/'.$_GET['name'].'.git'?>" >
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<form method="post">
										<div class="modal fade" id="generate" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog" role="document">
										    <div class="modal-content">
										      <div class="modal-header bg-success text-light">
										        <h5 class="modal-title"><i class="fas fa-key mr-2"></i>Generate New Key(s)</h5>
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										          <span aria-hidden="true">&times;</span>
										        </button>
										      </div>
										      <div class="modal-body">
														<div class="row">
															<div class="col-12">
																<div class="form-group">
													        <div class="input-group">
													          <div class="input-group-prepend">
													            <span class="input-group-text">
													              <i class="fas fa-list-ol mr-2"></i>Amount
													            </span>
													          </div>
													          <input type="number" class="form-control" value="1" name="amount" placeholder="Amount">
													        </div>
													      </div>
															</div>
														</div>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										        <button type="submit" name="GenKey" class="btn btn-success"><i class="fas fa-key mr-1"></i>Generate</button>
										      </div>
										    </div>
										  </div>
										</div>
									</form>
								</div>
								<?php if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json')){ ?>
									<table class="table table-striped display">
								    <thead>
							        <tr>
						            <th class="col-4">Key</th>
												<th style="width:100px;">Status</th>
												<th>Owner</th>
												<th style="width:100px;">Active</th>
						            <th style="width:100px;">Action</th>
							        </tr>
								    </thead>
								    <tbody>
											<?php foreach(json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true) as $key => $value){ ?>
								        <tr>
							            <td><?=$value['key']?></td>
													<td>
														<form method="post">
															<?php if($value['status']){ ?>
																<button type="submit" name="StatusDisable" value="<?=$key?>" class="btn btn-sm btn-success">
																	<i class="fas fa-key mr-1"></i>
																	Enabled
																</button>
															<?php } else { ?>
																<button type="submit" name="StatusEnable" value="<?=$key?>" class="btn btn-sm btn-danger">
																	<i class="fas fa-key mr-1"></i>
																	Disabled
																</button>
															<?php } ?>
														</form>
													</td>
													<td>
														<form method="post">
															<?php if(isset($value['owner'])){ ?>
																<button type="submit" name="SaveKey" value="<?=$key?>" class="btn btn-sm btn-primary">
																	<i class="fas fa-building mr-1"></i>
																	<?=$value['owner']?>
																</button>
															<?php } else { ?>
																<div class="form-group">
													        <div class="input-group input-group-sm">
													          <input type="text" class="form-control" name="owner" placeholder="Owner">
																		<div class="input-group-append">
																			<button type="submit" name="SaveKey" value="<?=$key?>" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save</button>
																		</div>
													        </div>
													      </div>
															<?php } ?>
														</form>
													</td>
													<td>
														<form method="post">
															<?php if($value['active']){ ?>
																<button type="submit" name="Deactivate" value="<?=$key?>" class="btn btn-sm btn-success">
																	<i class="fas fa-key mr-1"></i>
																	Activated
																</button>
															<?php } else { ?>
																<button type="submit" name="Activate" value="<?=$key?>" class="btn btn-sm btn-danger">
																	<i class="fas fa-key mr-1"></i>
																	Deactivated
																</button>
															<?php } ?>
														</form>
													</td>
							            <td>
														<form method="post">
															<button type="submit" name="DeleteKey" value="<?=$key?>" class="btn btn-sm btn-danger">
																<i class="fas fa-trash-alt mr-1"></i>
																Delete
															</button>
														</form>
													</td>
								        </tr>
											<?php } ?>
								    </tbody>
									</table>
								<?php } ?>
							</div>
						<?php } break;
					case "users":
						if((!isset($_GET['name']))||(!is_file(dirname(__FILE__,1).'/users/'.$_GET['name'].'.json'))){?>
							<div class="container pt-4">
								<div class="col-12 border-bottom mb-5 pl-0">
									<h3 class="display-4">
										Your Users
										<button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#new">
											<i class="fas fa-plus mr-1"></i>
											New
										</button>
									</h3>
									<form method="post">
										<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog" role="document">
										    <div class="modal-content">
										      <div class="modal-header bg-success text-light">
										        <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>New User</h5>
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										          <span aria-hidden="true">&times;</span>
										        </button>
										      </div>
										      <div class="modal-body">
														<div class="row">
															<div class="col-12">
																<div class="form-group">
													        <div class="input-group">
													          <div class="input-group-prepend">
													            <span class="input-group-text">
													              <i class="fas fa-user mr-2"></i>Username
													            </span>
													          </div>
													          <input type="text" class="form-control" name="username" placeholder="Username">
													        </div>
													      </div>
																<div class="form-group">
													        <div class="input-group">
													          <div class="input-group-prepend">
													            <span class="input-group-text">
													              <i class="fas fa-user mr-2"></i>Password
													            </span>
													          </div>
													          <input type="password" class="form-control" name="password" placeholder="Password">
													          <input type="password" class="form-control" name="password2" placeholder="Confirm Password">
													        </div>
													      </div>
															</div>
														</div>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										        <button type="submit" name="CreateUser" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Create</button>
										      </div>
										    </div>
										  </div>
										</div>
									</form>
								</div>
								<table class="table table-striped display">
							    <thead>
						        <tr>
					            <th>User</th>
					            <th style="width:250px;">Action</th>
						        </tr>
							    </thead>
							    <tbody>
										<?php foreach(scandir(dirname(__FILE__,1) . '/users/') as $user){ ?>
											<?php if(("$user" != "..") and ("$user" != ".") and ("$user" != ".htaccess")){ ?>
								        <tr>
							            <td><?=str_replace('.json','',$user)?></td>
							            <td>
														<form method="post">
															<a href="?p=users&name=<?=str_replace('.json','',$user)?>" class="btn btn-sm btn-primary">
																<i class="fas fa-eye mr-1"></i>
																Details
															</a>
															<button type="submit" name="DeleteUser" value="<?=str_replace('.json','',$user)?>" class="btn btn-sm btn-danger">
																<i class="fas fa-trash-alt mr-1"></i>
																Delete
															</button>
														</form>
													</td>
								        </tr>
											<?php } ?>
										<?php } ?>
							    </tbody>
								</table>
							</div>
						<?php } else {
							$user=json_decode(file_get_contents(dirname(__FILE__,1).'/users/'.$_GET['name'].'.json'),true);
							?>
							<div class="container pt-4">
								<div class="col-12 border-bottom mb-5 pl-0">
									<h3 class="display-4">
										<?=$_GET['name']?>
									</h3>
								</div>
								<div class="container">
									<form method="post">
										<div class="row">
											<div class="col-12">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">
																<i class="fas fa-lock mr-2"></i>Update Password
															</span>
														</div>
														<input type="password" class="form-control" name="password" placeholder="Password">
														<input type="password" class="form-control" name="password2" placeholder="Confirm Password">
														<div class="input-group-append">
															<button type="submit" name="SaveUser" class="btn btn-success"><i class="fas fa-save mr-1"></i>Save</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						<?php } break;
					default:?>
						<div class="jumbotron">
							<div class="container">
								<h1 class="display-3">Licensing Software Platform</h1>
								<p class="lead">Welcome to LSP the open source licensing and update service.</p>
								<a class="btn btn-lg btn-primary" href="?p=apps" role="button">View apps<i class="fas fa-chevron-right ml-2"></i></a>
								<a class="btn btn-lg btn-secondary" href="https://github.com/LouisOuellet/lsp" role="button"><i class="fab fa-github mr-2"></i>GitHub<i class="fas fa-chevron-right ml-2"></i></a>
							</div>
						</div>
						<div class="container">
							<div class="row">
								<div class="col-md-4">
									<h1 class="display-3 text-center"><i class="far fa-question-circle"></i></h1>
									<h2 class="text-center border-bottom border-secondary mb-3 pb-2">Get Started</h2>
									<p class="text-justify">To get started, you need to create your first application and generate some key(s). Additionnaly for PHP applications you can use the included LSP class in your application as described on <i class="fab fa-github mr-1"></i>GitHub.</p>
								</div>
								<div class="col-md-4">
									<h1 class="display-3 text-center"><i class="fas fa-key"></i></h1>
									<h2 class="text-center border-bottom border-secondary mb-3 pb-2">License Services</h2>
									<p class="text-justify">LSP makes use of cURL to provide a licensing access to your application. It can also generate a list of keys for a given app. By default, all license are disabled during creation. They will need to be Enabled for an application to authenticate it's license and activate it. Licenses are limited to 1 per instance of the application.</p>
								</div>
								<div class="col-md-4">
									<h1 class="display-3 text-center"><i class="fas fa-code-branch"></i></h1>
									<h2 class="text-center border-bottom border-secondary mb-3 pb-2">Update Services</h2>
									<p class="text-justify">LSP support a git server. This allows you to host your own git repositories and the ability to provide reliable updates. The included LSP classes also offers a method to upgrade your SQL database structure during the update process of your application. This allows you to focus on your application while LSP will takes care of the rest.</p>
								</div>
							</div>
						</div>
						<?php break;
				} ?>
	    </main>
	    <footer class="footer mt-auto py-3" style="padding:10px;background-color:#ccc;">
	      <div class="float-right d-none d-sm-block">
	        <b>Version</b> 1.1-0.2020-08-17
	      </div>
	      <strong>Copyright &copy; 2020-<?= date('Y') ?> <a href="https://albice.com">ALB Compagnie International Inc.</a></strong> All rights reserved.
	    </footer>
		</body>
		<script>
			$.extend( true, $.fn.dataTable.defaults, {
				"searching": true,
				"paging": true,
				"pageLength": 10,
				"lengthChange": true,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
				"info": true,
				"autoWidth": true,
				"processing": true,
				"scrolling": false,
				"buttons": [
					'copy', 'csv', 'pdf', 'print'
				],
			} );
		</script>
		<script>
			$(document).ready(function() {
				$('table.display').DataTable();
			} );
		</script>
	</html>
<?php } ?>
