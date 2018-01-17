<?php
	// Check if install.php is present
	if(is_dir('install')) {
		header("Location: install/install.php");
	} else {
		// Access DB Info
		include('config.php');

		// Get Settings Data
		include ('includes/settings.php');
		$set = mysqli_fetch_assoc($setRes);

		// Set Localization
		$local = $set['localization'];
		switch ($local) {
			case 'ar':		include ('language/ar.php');		break;
			case 'bg':		include ('language/bg.php');		break;
			case 'ce':		include ('language/ce.php');		break;
			case 'cs':		include ('language/cs.php');		break;
			case 'da':		include ('language/da.php');		break;
			case 'en':		include ('language/en.php');		break;
			case 'en-ca':	include ('language/en-ca.php');		break;
			case 'en-gb':	include ('language/en-gb.php');		break;
			case 'es':		include ('language/es.php');		break;
			case 'fr':		include ('language/fr.php');		break;
			case 'ge':		include ('language/ge.php');		break;
			case 'hr':		include ('language/hr.php');		break;
			case 'hu':		include ('language/hu.php');		break;
			case 'hy':		include ('language/hy.php');		break;
			case 'id':		include ('language/id.php');		break;
			case 'it':		include ('language/it.php');		break;
			case 'ja':		include ('language/ja.php');		break;
			case 'ko':		include ('language/ko.php');		break;
			case 'nl':		include ('language/nl.php');		break;
			case 'pt':		include ('language/pt.php');		break;
			case 'ro':		include ('language/ro.php');		break;
			case 'sv':		include ('language/sv.php');		break;
			case 'th':		include ('language/th.php');		break;
			case 'vi':		include ('language/vi.php');		break;
			case 'yue':		include ('language/yue.php');		break;
		}

		// Include Functions
		include('includes/functions.php');

		$msgBox = '';
		
		$activeAccount = '';
		$nowActive = '';

		if((isset($_GET['userEmail']) && !empty($_GET['userEmail'])) && (isset($_GET['hash']) && !empty($_GET['hash']))) {
			// Set some variables
			$userEmail = $mysqli->real_escape_string($_GET['userEmail']);
			$hash = $mysqli->real_escape_string($_GET['hash']);

			// Check to see if there is an account that matches the link
			$check1 = $mysqli->query("SELECT
										userEmail,
										hash,
										isActive
									FROM
										users
									WHERE
										userEmail = '".$userEmail."' AND
										hash = '".$hash."' AND
										isActive = 0
			");
			$match = mysqli_num_rows($check1);
			
			// Check if account has all ready been activated
			$check2 = $mysqli->query("SELECT 'X' FROM users WHERE userEmail = '".$userEmail."' AND hash = '".$hash."' AND isActive = 1");
			if ($check2->num_rows) {
				$activeAccount = 'true';
			}

			// Match found, update the User's account to active
			if ($match > 0) {
				$isActive = '1';

				$stmt = $mysqli->prepare("
									UPDATE
										users
									SET
										isActive = ?
									WHERE
										userEmail = ?");
				$stmt->bind_param('ss',
								   $isActive,
								   $userEmail);
				$stmt->execute();
				$nowActive = 'true';
				$stmt->close();
			}
		}
		
?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php echo $set['siteName']; ?> &middot; <?php echo $activatePageTitle; ?></title>

		<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,300italic,400italic,600italic' />
		<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Muli:400,300,300italic,400italic' />

		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />
		<link rel="stylesheet" type="text/css" href="css/layout.css" />
		<link rel="stylesheet" type="text/css" href="css/tasked.css" />
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />

		<script src="js/modernizr.js"></script>
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.js"></script>
		<![endif]-->
	</head>


	<body>
		<div class="nav-col">
			<nav id="nav" class="clearfix" role="navigation">
				<ul class="primary-nav">
					<li><a href="login.php"><?php echo $loginNavLink; ?></a></li>
				</ul>
			</nav>
		</div>

		<div class="logo-col">
			<a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
			<div class="logo-wrapper">
				<a href="index.php" class="clearfix"> <img id="logo" src="images/logo.png" alt="<?php echo $taskedLogoAltText; ?>"></a>
			</div>
		</div>

		<div class="content-col" id="page">
			<div class="inner-content">
				<h1 class="font-weight-thin no-margin-top"><?php echo $activatePageTitle; ?></h1>
				<hr />
				
				<?php
					// The account has been activated - show a Signin button
					if ($nowActive != '') {
				?>
						<h4><?php echo $activateText1; ?></h4>
						<div class="alertMsg success">
							<i class="fa fa-check"></i> <?php echo $activateText2; ?>
						</div>
						<p><a href="login.php" class="btn btn-primary btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $activateText3; ?></a></p>
				<?php
					// An account match was found and has all ready been activated
					} else if ($activeAccount != '') {
				?>
						<h4><?php echo $activateText4; ?></h4>
						<div class="alertMsg success">
							<i class="fa fa-check"></i> <?php echo $activateText5; ?>
						</div>
						<p><a href="login.php" class="btn btn-primary btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $activateText6; ?></a></p>
				<?php
					// An account match was not found/or the
					// Client tried to directly access this page
					} else {
				?>
						<h4><?php echo $activateText7; ?></h4>
						<div class="alertMsg danger">
							<i class="fa fa-times-circle"></i> <?php echo $activateText8; ?>
						</div>
				<?php } ?>
			</div>
		</div>

		<div class="faux-col faux-nav-col"></div>
		<div class="faux-col faux-logo-col"></div>
		<div class="faux-col faux-content-col"></div>
		<div class="faux-col faux-footer-col"></div>

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/winfix.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
		
		</body>
	</html>
<?php } ?>