<?php
session_start();

require dirname(__FILE__,1).'/src/lib/api.php';

$API = new API();

if((!empty($_POST))&&(isset($_POST['logout']))){
	unset($_SESSION['lsp']);
	$API->Status = FALSE;
}

if(!$API->Status){
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
} else { ?>
<!doctype html>
<html lang="en" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="A licensing and update service">
		<meta name="author" content="Louis Ouellet, https://github.com/LouisOuellet">
		<title>LSP | Panel</title>
		<link rel="shortcut icon" href="/dist/img/favicon.ico" />
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="./dist/css/panel.css">
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
				<a class="navbar-brand" href="">
					<img class="mr-2" src="/dist/img/logo.png" alt="" width="32" height="32">
					Licensing Software Platform
				</a>
				<div class="navbar-collapse">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" id="navBtnApps" style="cursor:pointer;"><i class="fas fa-code mr-2"></i>Apps</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="navBtnUsers" style="cursor:pointer;"><i class="fas fa-users mr-2"></i>Users</a>
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
						<button class="btn btn-outline-primary ml-3 my-2 my-sm-0" type="submit" name="logout">Logout</button>
					</form>
				</div>
			</nav>
		</header>
		<!-- Begin page content -->
		<main role="main" id="mainCTN" class="flex-shrink-0" style="padding-top:50px;"></main>
		<footer class="footer mt-auto py-3" style="padding:10px;background-color:#ccc;">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 2.b.2020-09-23
			</div>
			<strong>Copyright &copy; 2020-<?= date('Y') ?> <a href="https://albice.com">ALB Compagnie International Inc.</a></strong> All rights reserved.
		</footer>
		<script type="text/javascript" language="javascript" src="/dist/js/panel.js"></script>
	</body>
</html>
<?php } ?>
