<?php
if(isset($_GET['license'])){
	#
} else {
	// include('lsp.php');
	// $License = new LSP('http://localhost/','12345-12345-12345-12345-12345');
	// exit;
	if((isset($_GET['p']))&&($_GET['p'] != '')){
		$page=$_GET['p'];
	} else {
		$page='index';
	}
	if(!empty($_POST)){
	  #
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
			<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
			<script src="https://kit.fontawesome.com/4f8426d3cf.js" crossorigin="anonymous"></script>
	    <style>
	      .bd-placeholder-img {
	        font-size: 1.125rem;
	        text-anchor: middle;
	        -webkit-user-select: none;
	        -moz-user-select: none;
	        -ms-user-select: none;
	        user-select: none;
	      }

	      @media (min-width: 768px) {
	        .bd-placeholder-img-lg {
	          font-size: 3.5rem;
	        }
	      }
	    </style>
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
	        </div>
	      </nav>
	    </header>
	    <!-- Begin page content -->
	    <main role="main" class="flex-shrink-0" style="padding-top:50px;">
				<?php switch($page){
					case "apps":?>
						<div class="container pt-5">
							some apps
						</div>
						<?php break;
					default:?>
						<div class="jumbotron">
							<div class="container">
								<h1 class="display-3">Licensing Software Platform</h1>
								<p class="lead">Welcome to the open source licensing and update service.</p>
								<a class="btn btn-lg btn-primary" href="?p=apps" role="button">View apps<i class="fas fa-chevron-right ml-2"></i></a>
								<a class="btn btn-lg btn-secondary" href="https://github.com/LouisOuellet/lsp" role="button"><i class="fab fa-github mr-2"></i>Github<i class="fas fa-chevron-right ml-2"></i></a>
							</div>
						</div>
						<div class="container">
							<div class="row">
								<div class="col-md-4">
									<h2>License Services</h2>
									<p>This software makes use of cURL to provide a licensing access to your application. It can also generate a list of license for a given app.</p>
									<p><a class="btn btn-secondary" href="#" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
								</div>
								<div class="col-md-4">
									<h2>Update Services</h2>
									<p>As you are developping your application, you may need to provide an update system to it. So that your users may get fetch new versions of your application.</p>
									<p><a class="btn btn-secondary" href="#" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
								</div>
								<div class="col-md-4">
									<h2>Get Started</h2>
									<p>To get started, we will need to setup a few things. We will setup a git environment and SQL database so we can provide both services to your application.</p>
									<p><a class="btn btn-secondary" href="#" role="button">View details<i class="fas fa-chevron-right ml-2"></i></a></p>
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
	</html>
<?php } ?>
