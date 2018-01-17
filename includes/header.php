<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $set['siteName']; ?> &middot; <?php echo $headerIncTitle; ?></title>

	<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,300italic,400italic,600italic' />
	<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Muli:400,300,300italic,400italic' />

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/custom.css" />
	<link rel="stylesheet" type="text/css" href="css/layout.css" />
	<link rel="stylesheet" type="text/css" href="css/tasked.css" />
	<link rel="stylesheet" type="text/css" href="css/simpleWeather.css" />
	<link rel="stylesheet" type="text/css" href="css/footerCal.css" />
	<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />
	<?php if (isset($addCss)) { echo $addCss; } ?>
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />

	<script src="js/modernizr.js"></script>
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.js"></script>
	<![endif]-->
</head>

<body>