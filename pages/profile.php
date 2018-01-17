<?php
	include 'includes/navigation.php';
	$jsFile = 'profile';
	
	// Edit Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'editProfile') {
		if($_POST['userFirst'] == "") {
            $msgBox = alertBox($yourFisrtNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['userLast'] == "") {
            $msgBox = alertBox($yourLastNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['userEmail'] == "") {
            $msgBox = alertBox($yourEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['passwordNew'] != $_POST['passwordRepeat']) {
			$msgBox = alertBox($passwordsNotMatchMsg, "<i class='fa fa-warning'></i>", "warning");
		} else {
			if($_POST['currentPass'] != '') {
				$currPass = encryptIt($_POST['currentPass']);
			} else {
				$currPass = '';
			}
			
			if($_POST['currentPass'] == '') {
				$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
				$userLast = $mysqli->real_escape_string($_POST['userLast']);
				$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
				$newPassword = $_POST['passwordOld'];
				if ($set['enableWeather'] == '1') {
					$weatherLoc = $mysqli->real_escape_string($_POST['weatherLoc']);
				} else {
					$weatherLoc = 'Washington, DC';
				}
				
				// Update the $_SESSION variables
				$_SESSION["userFirst"]	= $userFirst;
				$_SESSION["userLast"] 	= $userLast;
				$_SESSION["userEmail"] 	= $userEmail;
				$_SESSION["weatherLoc"] = $weatherLoc;

				$stmt = $mysqli->prepare("UPDATE
											users
										SET
											userEmail = ?,
											password = ?,
											userFirst = ?,
											userLast = ?,
											weatherLoc = ?
										WHERE
											userId = ?"
				);
				$stmt->bind_param('ssssss',
									$userEmail,
									$newPassword,
									$userFirst,
									$userLast,
									$weatherLoc,
									$userId
				);
				$stmt->execute();
				$msgBox = alertBox($accountProfileUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
				$stmt->close();
			} else if ($_POST['currentPass'] != '' && encryptIt($_POST['currentPass']) == $_POST['passwordOld']) {
				$newPassword = encryptIt($_POST['passwordNew']);
				$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
				$userLast = $mysqli->real_escape_string($_POST['userLast']);
				$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
				if ($set['enableWeather'] == '1') {
					$weatherLoc = $mysqli->real_escape_string($_POST['weatherLoc']);
				} else {
					$weatherLoc = 'Washington, DC';
				}
				
				// Update the $_SESSION variables
				$_SESSION["userFirst"]	= $userFirst;
				$_SESSION["userLast"] 	= $userLast;
				$_SESSION["userEmail"] 	= $userEmail;
				$_SESSION["weatherLoc"] = $weatherLoc;

				$stmt = $mysqli->prepare("UPDATE
											users
										SET
											userEmail = ?,
											password = ?,
											userFirst = ?,
											userLast = ?,
											weatherLoc = ?
										WHERE
											userId = ?"
				);
				$stmt->bind_param('ssssss',
									$userEmail,
									$newPassword,
									$userFirst,
									$userLast,
									$weatherLoc,
									$userId
				);
				$stmt->execute();
				$msgBox = alertBox($accountProfileUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
				$stmt->close();
			} else {
				$msgBox = alertBox($currentPassError, "<i class='fa fa-warning'></i>", "warning");
			}
		}
	}
	
	// User Data
	$qry = "SELECT
				*
			FROM
				users
			WHERE
				userId = ".$userId;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);
	
	if ($row['lastVisited'] == '0000-00-00 00:00:00') { $lastLogin = $neverText; } else { $lastLogin = dateFormat($row['lastVisited']); }
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top"><?php echo $profilePageTitle; ?></h3>
		<hr />

		<?php if ($msgBox) { echo $msgBox; } ?>
		
		<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
				<div class="panel-body">
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4>
								<?php echo $profileDetailsTitle; ?>
								<small class="pull-right mt-5"><?php echo $lastLoginTH; ?>: <?php echo $lastLogin; ?></small>
							</h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $firstNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userFirst" value="<?php echo clean($row['userFirst']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $lastNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userLast" value="<?php echo clean($row['userLast']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $emailAddyField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userEmail" value="<?php echo clean($row['userEmail']); ?>" />
						</div>
					</div>

					<div class="form-group header bgcolor-default mt-10">
						<div class="col-md-12">
							 <h4><?php echo $changePasswordTitle; ?></h4>
							 <p><small><?php echo $changePasswordQuip; ?></small></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $currentText.' '.$passwordField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="password" autocomplete="off" name="currentPass" id="currentPass" value="" />
							<span class="help-block"><?php echo $currentPasswordHelp; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $newPassField; ?></label>
						<div class="col-sm-8">
							<div class="input-group">
								<input type="password" class="form-control" autocomplete="off" name="passwordNew" id="passwordNew" value="" />
								<span class="input-group-addon"><a href="" id="generatePass" data-toggle="tooltip" data-placement="top" title="<?php echo $generatePassTooltip; ?>"><i class="fa fa-key"></i></a></span>
							</div>
							<span class="help-block">
								<a href="" id="show2" class="btn btn-warning btn-xs"><?php echo $showPlainText; ?></a>
								<a href="" id="hide2" class="btn btn-info btn-xs"><?php echo $hidePlainText; ?></a>
								<a href="" id="clear2" class="btn btn-success btn-xs"><?php echo $clearFieldsText; ?></a>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $repeatText.' '.$passwordField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="password" autocomplete="off" name="passwordRepeat" id="passwordRepeat" value="" />
							<input type="hidden" name="passwordOld" value="<?php echo $row['password']; ?>" />
						</div>
					</div>
					
					<?php if ($set['enableWeather'] == '1') { ?>
						<div class="form-group header bgcolor-default mt-10">
							<div class="col-md-12">
								 <h4><?php echo $locationTitle; ?></h4>
								 <p><small><?php echo $locationQuip; ?></small></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $cityStateField; ?></label>
							<div class="col-sm-8">
								<input class="form-control" type="text" required="" name="weatherLoc" value="<?php echo clean($row['weatherLoc']); ?>" />
							</div>
						</div>
					<?php } ?>

				</div>
				<hr />
				<button type="input" name="submit" value="editProfile" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
			</form>
		
	</div>
</div>