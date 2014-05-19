<!DOCTYPE HTML>
<html>
<head>
	<title><?php the_title();?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<!--[if lt IE 9]> <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->

	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo TEMPPATH;?>/css/style.css" />
	
</head>

<body>
	
	<div id="wrap">
		<div id="container" class="group">
			<!--Header - Name of Item Here-->
			<header class="group">
				<h1><img src="images/logo.png" alt="Director" /></h1>
				
				<div class="searchbar">
					<form name="search" method="get" action"/">
						<input type="text" name="s" value="Search…" />
						<input type="submit" name="submit" value="Search!" />
					</form>
				</div>
				
				<nav>
					<ul>
						<li><a href="#">Home</a></li>
						<li><a href="#">Directory</a></li>
						<li><a href="#">Blog</a></li>
					</ul>
				</nav>
				
			</header>
			
			<!-- End Header -->
			
			<!-- Main Area -->
			<div id="content" class="group">