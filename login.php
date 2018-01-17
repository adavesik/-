<?php
	// Check if install.php is present
	if(is_dir('install')) {
		header("Location: install/install.php");
	} else {
		if (!isset($_SESSION)) { session_start(); }

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

		// User Log In Form
		if (isset($_POST['submit']) && $_POST['submit'] == 'signIn') {
			if($_POST['userEmail'] == '') {
				$msgBox = alertBox($accEmailAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] == '') {
				$msgBox = alertBox($accPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				// Check if the User account has been activated
				$userEmail = (isset($_POST['userEmail'])) ? $mysqli->real_escape_string($_POST['userEmail']) : '';
				$check = $mysqli->query("SELECT isActive FROM users WHERE userEmail = '".$userEmail."'");
				$row = mysqli_fetch_assoc($check);

				// If the account is active - allow the login
				if ($row['isActive'] == '1') {
					$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
					$password = encryptIt($_POST['password']);

					if($stmt = $mysqli -> prepare("
											SELECT
												userId,
												isAdmin,
												userEmail,
												userFirst,
												userLast,
												recEmails,
												weatherLoc
											FROM
												users
											WHERE
												userEmail = ? AND
												password = ?
					"))	{
						$stmt -> bind_param("ss",
											$userEmail,
											$password
						);
						$stmt -> execute();
						$stmt -> bind_result(
									$userId,
									$isAdmin,
									$userEmail,
									$userFirst,
									$userLast,
									$recEmails,
									$weatherLoc
						);
						$stmt -> fetch();
						$stmt -> close();

						if (!empty($userId)) {
								$_SESSION["userId"] 	= $userId;
								$_SESSION["isAdmin"] 	= $isAdmin;
								$_SESSION["userEmail"] 	= $userEmail;
								$_SESSION["userFirst"]	= $userFirst;
								$_SESSION["userLast"] 	= $userLast;
								$_SESSION["recEmails"] 	= $recEmails;
								$_SESSION["weatherLoc"] = $weatherLoc;
							header('Location: index.php');
						} else {
							$msgBox = alertBox($loginFailedMsg, "<i class='fa fa-times-circle'></i>", "danger");
						}
					}

					// Update Last Visited Date for User
					$lastVisited = date("Y-m-d H:i:s");
					$sqlStmt = $mysqli->prepare("
											UPDATE
												users
											SET
												lastVisited = ?
											WHERE
												userId = ?
					");
					$sqlStmt->bind_param('ss',
									   $lastVisited,
									   $userId
					);
					$sqlStmt->execute();
					$sqlStmt->close();

				} else if ($row['isActive'] == '0') {
					// If the account is not active, show a message
					$msgBox = alertBox($accInactiveMsg, "<i class='fa fa-warning'></i>", "warning");
				} else {
					// No account found
					$msgBox = alertBox($accNotFoundMsg, "<i class='fa fa-times-circle'></i>", "danger");
				}
			}
		}

		// Reset Account Password Form
		if (isset($_POST['submit']) && $_POST['submit'] == 'resetPass') {
			// Set the email address
			$theEmail = (isset($_POST['theEmail'])) ? $mysqli->real_escape_string($_POST['theEmail']) : '';

			// Validation
			if ($_POST['theEmail'] == "") {
				$msgBox = alertBox($accEmailAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$query = "SELECT userEmail FROM users WHERE userEmail = ?";
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s",$theEmail);
				$stmt->execute();
				$stmt->bind_result($userEmail);
				$stmt->store_result();
				$numrows = $stmt->num_rows();

				if ($numrows == 1){
					// Generate a RANDOM Hash for a password
					$randomPassword = uniqid(rand());

					// Take the first 8 digits and use them as the password we intend to email the user
					$emailPassword = substr($randomPassword, 0, 8);

					// Encrypt $emailPassword for the database
					$newpassword = encryptIt($emailPassword);

					//update password in db
					$updatesql = "UPDATE users SET password = ? WHERE userEmail = ?";
					$update = $mysqli->prepare($updatesql);
					$update->bind_param("ss", $newpassword, $theEmail);
					$update->execute();

					// Send out the email in HTML
					$installUrl 	= $set['installUrl'];
					$siteName 		= $set['siteName'];
					$siteEmail		= $set['siteEmail'];

					$subject = $resetPassEmailTitle;

					// -------------------------------
					// ---- START Edit Email Text ----
					// -------------------------------
					$message = '<html><body>';
					$message .= '<h3>'.$subject.'</h3>';
					$message .= '<p>'.$resetPassEmail1.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$emailPassword.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$resetPassEmail2.'</p>';
					$message .= '<p>'.$resetPassEmail3.' '.$installUrl.'</p>';
					$message .= '<p>'.$thankYouText.'<br>'.$siteName.'</p>';
					$message .= '</body></html>';
					// -----------------------------
					// ---- END Edit Email Text ----
					// -----------------------------

					$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
					$headers .= "Reply-To: ".$siteEmail."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

					if (mail($theEmail, $subject, $message, $headers)) {
						$msgBox = alertBox($passwordResetMsg, "<i class='fa fa-check-square'></i>", "success");
						$isReset = 'true';
						$stmt->close();
					}
				} else {
					// No account found
					$msgBox = alertBox($accNotFoundMsg, "<i class='fa fa-warning'></i>", "warning");
				}
			}
		}

		// Create a New Account Form
		if (isset($_POST['submit']) && $_POST['submit'] == 'createAccount') {
			// User Validations
			if($_POST['newEmail'] == '') {
				$msgBox = alertBox($validEmailAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] == '') {
				$msgBox = alertBox($newPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] != $_POST['passwordr']) {
				$msgBox = alertBox($passwordsNotMatchMsg, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['answer'] == '') {
				$msgBox = alertBox($captchaCodeReq, "<i class='fa fa-times-circle'></i>", "danger");
			// Black Hole Trap to help reduce bot registrations
			} else if($_POST['noAnswer'] != '') {
				$msgBox = alertBox($newAccountErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				// Set some variables
				$dupEmail = '';
				$newEmail = $mysqli->real_escape_string($_POST['newEmail']);

				// Check for Duplicate email
				$check = $mysqli->query("SELECT 'X' FROM users WHERE userEmail = '".$newEmail."'");
				if ($check->num_rows) {
					$dupEmail = 'true';
				}

				// If duplicates are found
				if ($dupEmail != '') {
					$msgBox = alertBox($duplicateAccountMsg, "<i class='fa fa-times-circle'></i>", "danger");
				} else {
					if(strtolower($_POST['answer']) == $_SESSION['thecode']) {
						// Create the new account
						$password = encryptIt($_POST['password']);
						$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
						$userLast = $mysqli->real_escape_string($_POST['userLast']);
						$joinDate = date("Y-m-d H:i:s");
						$hash = md5(rand(0,1000));
						$isActive = '0';

						$stmt = $mysqli->prepare("
											INSERT INTO
												users(
													userEmail,
													password,
													userFirst,
													userLast,
													joinDate,
													hash,
													isActive
												) VALUES (
													?,
													?,
													?,
													?,
													?,
													?,
													?
												)");
						$stmt->bind_param('sssssss',
							$newEmail,
							$password,
							$userFirst,
							$userLast,
							$joinDate,
							$hash,
							$isActive
						);
						$stmt->execute();

						// Send out the email in HTML
						$installUrl = $set['installUrl'];
						$siteName = $set['siteName'];
						$siteEmail = $set['siteEmail'];
						$newPass = $mysqli->real_escape_string($_POST['password']);

						$subject = $newAccountEmailSubject;

						// -------------------------------
						// ---- START Edit Email Text ----
						// -------------------------------
						$message = '<html><body>';
						$message .= '<h3>'.$subject.'</h3>';
						$message .= '<p>'.$newAccountEmail1.'</p>';
						$message .= '<hr>';
						$message .= '<p>'.$newAccountEmail2.' '.$newPass.'</p>';
						$message .= '<p>'.$newAccountEmail3.$installUrl.'activate.php?userEmail='.$newEmail.'&hash='.$hash.'</p>';
						$message .= '<hr>';
						$message .= '<p>'.$newAccountEmail4.'</p>';
						$message .= '<p>'.$newAccountEmail5.' '.$installUrl.'</p>';
						$message .= '<p>'.$thankYouText.'<br>'.$siteName.'</p>';
						$message .= '</body></html>';
						// -------------------------------
						// ---- END Edit Email Text ----
						// -------------------------------

						$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
						$headers .= "Reply-To: ".$siteEmail."\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

						if (mail($newEmail, $subject, $message, $headers)) {
							$msgBox = alertBox($newAccountCreatedMsg, "<i class='fa fa-check-square'></i>", "success");
							// Clear the Form of values
							$_POST['newEmail'] = $_POST['userFirst'] = $_POST['userLast'] = $_POST['answer'] = '';
						}
						$stmt->close();
					} else {
						$msgBox = alertBox($incorrectCaptchaMsg, "<i class='fa fa-warning'></i>", "warning");
					}
				}
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

		<title><?php echo $set['siteName']; ?> &middot; <?php echo $loginPageTitle; ?></title>

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
					<?php if ($set['allowRegistrations'] == '1') { ?>
						<li><a href="#" id="create"><?php echo $createAccNavLink; ?></a></li>
					<?php } ?>
					<li><a href="#" id="reset"><?php echo $resetPassNavLink; ?></a></li>

				</ul>
			</nav>
		</div>

		<div class="logo-col">
			<a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
			<div class="logo-wrapper">
				<a href="index.php" class="clearfix"><img id="logo" src="images/logo.png" alt="<?php echo $taskedLogoAltText; ?>"></a>
			</div>
		</div>

		<div class="content-col" id="page">
			<div class="inner-content">
				<div id="loginForm">
					<h1 class="font-weight-thin no-margin-top"><?php echo $loginNavLink; ?></h1>
					<hr />
					
					<?php if ($msgBox) { echo $msgBox; } ?>

					<div class="row">
						<article class="col-sm-12 clearfix">
							<p class="mt-10"><?php echo $loginPageQuip; ?></p>
							<form action="" method="post">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="userEmail"><?php echo $emailAddyField; ?></label>
											<input type="email" class="form-control" required="" name="userEmail" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="password"><?php echo $passwordField; ?></label>
											<input type="password" class="form-control" required="" name="password" />
										</div>
									</div>
								</div>
								<button type="input" name="submit" value="signIn" class="btn btn-tasked btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $signInBtn; ?></button>
							</form>
						</article>
					</div>
				</div>

				<?php if ($set['allowRegistrations'] == '1') { ?>
					<div id="createForm">
						<h1 class="font-weight-thin no-margin-top"><?php echo $createAccountTitle; ?></h1>
						<hr />

						<div class="row">
							<article class="col-sm-12 clearfix">
								<p class="mt-10"><?php echo $createAccountQuip; ?></p>
								<form action="" method="post">
									<div class="form-group">
										<label for="newEmail"><?php echo $emailAddyField; ?></label>
										<input type="email" class="form-control" required="" name="newEmail" value="<?php echo isset($_POST['newEmail']) ? $_POST['newEmail'] : ''; ?>" />
										<span class="help-block"><?php echo $emailAddyFieldHelp; ?></span>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="password"><?php echo $passwordField; ?></label>
												<input type="password" class="form-control" autocomplete="off" required="" name="password" />
												<span class="help-block"><?php echo $passwordFieldHelp1; ?></span>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="passwordr"><?php echo $repeatText.' '.$passwordField; ?></label>
												<input type="password" class="form-control" autocomplete="off" required="" name="passwordr" />
												<span class="help-block"><?php echo $repeatPassHelp; ?></span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="userFirst"><?php echo $firstNameField; ?></label>
												<input type="text" class="form-control" required="" name="userFirst" value="<?php echo isset($_POST['userFirst']) ? $_POST['userFirst'] : ''; ?>" />
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="userLast"><?php echo $lastNameField; ?></label>
												<input type="text" class="form-control" required="" name="userLast" value="<?php echo isset($_POST['userLast']) ? $_POST['userLast'] : ''; ?>" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="answer"><?php echo $captchaCodeField; ?></label>
												<input type="text" class="form-control" required="" name="answer" />
											</div>
										</div>
										<div class="col-sm-6">
											<p class="mt-30"><img src="includes/captcha.php" id="captcha" required="" data-toggle="tooltip" data-placement="top" title="<?php echo $captchaCodeField; ?>" /></p>
										</div>
									</div>
									<input type="hidden" name="noAnswer" />
									<button type="input" name="submit" value="createAccount" class="btn btn-tasked btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $createAccountBtn; ?></button>
								</form>
							</article>
						</div>
					</div>
				<?php } ?>

				<div id="resetForm">
					<h1 class="font-weight-thin no-margin-top"><?php echo $resetPasswordTitle; ?></h1>
					<hr />

					<article class="col-sm-12 clearfix">
						<p class="mt-10"><?php echo $resetPasswordQuip; ?></p>
						<form action="" method="post">
							<div class="form-group">
								<label for="theEmail"><?php echo $emailAddyField; ?></label>
								<input type="email" class="form-control" name="theEmail" required="" />
								<span class="help-block"><?php echo $resetPassHelp; ?></span>
							</div>
							<button type="input" name="submit" value="resetPass" class="btn btn-tasked btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $resetPassBtn; ?></button>
						</form>
					</article>
				</div>
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
		<script type="text/javascript">
			$(document).ready(function() {
				$('#createForm, #resetForm').hide();

				$('#login').click(function(e) {
					e.preventDefault();
					$('#createForm, #resetForm').fadeOut(300, function() {
						$(this).hide;
					});
					$('#loginForm').delay(300).fadeIn("slow", function() {
						$(this).show;
					});
				});

				$('#create').click(function(e) {
					e.preventDefault();
					$('#loginForm, #resetForm').fadeOut(300, function() {
						$(this).hide;
					});
					$('#createForm').delay(300).fadeIn("slow", function() {
						$(this).show;
					});
				});

				$('#reset').click(function(e) {
					e.preventDefault();
					$('#createForm, #loginForm').fadeOut(300, function() {
						$(this).hide;
					});
					$('#resetForm').delay(300).fadeIn("slow", function() {
						$(this).show;
					});
				});
			});
		</script>
	</body>
	</html>
<?php } ?>