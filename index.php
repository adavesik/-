<?php
	// Check if install.php is present
	if(is_dir('install')) {
		header('Location: install/install.php');
	} else {
		session_start();
		if (!isset($_SESSION['userId'])) {
			header ('Location: login.php');
			exit;
		}

		// Logout
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			if ($action == 'logout') {
				session_destroy();
				header('Location: login.php');
			}
		}

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

		// Keep some Client data available
		$userId 			= $_SESSION['userId'];
		$isAdmin 			= $_SESSION['isAdmin'];
		$userEmail 			= $_SESSION['userEmail'];
		$userFullName 		= $_SESSION['userFirst'].' '.$_SESSION['userLast'];
		$recEmails			= $_SESSION['recEmails'];
		$weatherLoc			= $_SESSION['weatherLoc'];

		// Link to the Page
		if (isset($_GET['page']) && $_GET['page'] == 'profile') {
			$page = 'profile';
		} else if (isset($_GET['page']) && $_GET['page'] == 'openTasks') {
			$page = 'openTasks';
			$addCss = '<link rel="stylesheet" type="text/css" href="css/dataTables.css" />';
		} else if (isset($_GET['page']) && $_GET['page'] == 'closedTasks') {
			$page = 'closedTasks';
			$addCss = '<link rel="stylesheet" type="text/css" href="css/dataTables.css" />';
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewTask') {
			$page = 'viewTask';
		} else if (isset($_GET['page']) && $_GET['page'] == 'calendar') {
			$page = 'calendar';
			$addCss = '
				<link rel="stylesheet" type="text/css" href="css/fullcalendar.css" />
				<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />
			';
		} else if (isset($_GET['page']) && $_GET['page'] == 'categories') {
			$page = 'categories';
			$addCss = '<link rel="stylesheet" type="text/css" href="css/dataTables.css" />';
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewCategory') {
			$page = 'viewCategory';
			$addCss = '<link rel="stylesheet" type="text/css" href="css/dataTables.css" />';
		} else if (isset($_GET['page']) && $_GET['page'] == 'editCategory') {
			$page = 'editCategory';
		} else if (isset($_GET['page']) && $_GET['page'] == 'users') {
			$page = 'users';
			$addCss = '<link rel="stylesheet" type="text/css" href="css/dataTables.css" />';
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewUser') {
			$page = 'viewUser';
		} else if (isset($_GET['page']) && $_GET['page'] == 'siteSettings') {
			$page = 'siteSettings';
		} else if (isset($_GET['page']) && $_GET['page'] == 'searchResults') {
			$page = 'searchResults';
		} else {
			$page = 'dashboard';
		}

		include('includes/header.php');

		if (file_exists('pages/'.$page.'.php')) {
			// Load the Page
			include('pages/'.$page.'.php');
		} else {
			include 'includes/navigation.php';
			// Else Display an Error
			echo '
					<div class="content-col" id="page">
						<div class="inner-content">
							<h1 class="font-weight-thin no-margin-top">'.$pageNotFoundHeader.'</h1>
							<hr />
							<div class="alertMsg warning">
								<i class="fa fa-warning"></i> '.$pageNotFoundQuip.' "'.$page.'"
							</div>
						</div>
					</div>
				';
		}

		include('includes/footer.php');
	}
?>