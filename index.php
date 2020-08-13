<?php
session_start();
if(isset($_GET['license'],$_GET['app'])){
	if(file_exists(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json')){
		$keys=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/keys.json'),true);
		$app=json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['app'].'/app.json'),true);
		if((isset($keys[$_GET['license']]))&&(password_verify($_GET['license'], $keys[$_GET['license']]))){
			echo $app['token'];
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
	if(!empty($_POST)){
		if((isset($_POST['GetStarted'],$_POST['username'],$_POST['password']))&&(!empty($_POST['username']))&&(!empty($_POST['password']))){
			if(!file_exists(dirname(__FILE__,1).'/users/'.$_POST['username'].'.json')){
				mkdir(dirname(__FILE__,1).'/users');
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
	if(!is_dir(dirname(__FILE__,1).'/users')){ ?>
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
	if(!isset($_SESSION['lsp'])){?>
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
				if(!is_dir(dirname(__FILE__,1).'/apps/'.$_POST['name'])){
					mkdir(dirname(__FILE__,1).'/apps/'.$_POST['name']);
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
					$keys[$key]=password_hash($key, PASSWORD_DEFAULT);
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
		if(isset($_POST['DeleteApp'])){
			if(is_dir(dirname(__FILE__,1).'/apps/'.$_POST['DeleteApp'])){
				foreach(scandir(dirname(__FILE__,1) . '/apps/'.$_POST['DeleteApp'].'/') as $file){
					if(("$file" != "..") and ("$file" != ".")){
						unlink(dirname(__FILE__,1) . '/apps/'.$_POST['DeleteApp'].'/'.$file);
					}
				}
				rmdir(dirname(__FILE__,1) . '/apps/'.$_POST['DeleteApp']);
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
										<button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#token">
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
						            <th>Key</th>
						            <th style="width:250px;">Action</th>
							        </tr>
								    </thead>
								    <tbody>
											<?php foreach(json_decode(file_get_contents(dirname(__FILE__,1).'/apps/'.$_GET['name'].'/keys.json'),true) as $key => $hash){ ?>
								        <tr>
							            <td><?=$key?></td>
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
					default:?>
						<div class="jumbotron">
							<div class="container">
								<h1 class="display-3">Licensing Software Platform</h1>
								<p class="lead">Welcome to the open source licensing service.</p>
								<a class="btn btn-lg btn-primary" href="?p=apps" role="button">View apps<i class="fas fa-chevron-right ml-2"></i></a>
								<a class="btn btn-lg btn-secondary" href="https://github.com/LouisOuellet/lsp" role="button"><i class="fab fa-github mr-2"></i>Github<i class="fas fa-chevron-right ml-2"></i></a>
							</div>
						</div>
						<div class="container">
							<div class="row">
								<div class="col-md-4">
									<h2>License Services</h2>
									<p>This software makes use of cURL to provide a licensing access to your application. It can also generate a list of keys for a given app.</p>
									<p><a class="btn btn-secondary" href="#" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
								</div>
								<div class="col-md-4">
									<h2>Coming soon</h2>
									<p>I am planning on adding an update service based on git. As you are developping your application, you may need to provide an update system to it. So that your users may fetch new versions of your application.</p>
									<p><a class="btn btn-secondary" href="#" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
								</div>
								<div class="col-md-4">
									<h2>Get Started</h2>
									<p>To get started, you need to create your first application and generate some key(s). Then you will need to add the LSP class to your application as described on github.</p>
									<p><a class="btn btn-secondary" href="https://github.com/LouisOuellet/lsp" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
								</div>
							</div>
						</div>
						<?php break;
				} ?>
	    </main>
	    <footer class="footer mt-auto py-3" style="padding:10px;background-color:#ccc;">
	      <div class="float-right d-none d-sm-block">
	        <b>Version</b> 1.1-0
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
