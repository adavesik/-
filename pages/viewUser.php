<?php
	include 'includes/navigation.php';
	$uid = $_GET['uid'];
	$jsFile = 'viewUser';

	// Edit User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'editUser') {
		if($_POST['userFirst'] == "") {
            $msgBox = alertBox($userFirstReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['userLast'] == "") {
            $msgBox = alertBox($userLastReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['userEmail'] == "") {
            $msgBox = alertBox($userEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['passwordNew'] != $_POST['passwordRepeat']) {
			$msgBox = alertBox($passwordsNotMatchMsg, "<i class='fa fa-warning'></i>", "warning");
		} else {
			$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
			$userLast = $mysqli->real_escape_string($_POST['userLast']);
			$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
			$setActive = $mysqli->real_escape_string($_POST['isActive']);
			$setAdmin = $mysqli->real_escape_string($_POST['isAdmin']);
			$joinDate = $mysqli->real_escape_string($_POST['joinDate']);
			$userNotes = $_POST['userNotes'];

			if(isset($_POST['passwordNew']) && $_POST['passwordNew'] != "") {
				$newPassword = encryptIt($_POST['passwordNew']);
			} else {
				$newPassword = $_POST['passwordOld'];
			}

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										isAdmin = ?,
										userEmail = ?,
										password = ?,
										userFirst = ?,
										userLast = ?,
										joinDate = ?,
										userNotes = ?,
										isActive = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('sssssssss',
								$setAdmin,
								$userEmail,
								$newPassword,
								$userFirst,
								$userLast,
								$joinDate,
								$userNotes,
								$setActive,
								$uid
			);
			$stmt->execute();
			$msgBox = alertBox($userAccUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
	}

	// User Data
	$qry = "SELECT
				*
			FROM
				users
			WHERE
				userId = ".$uid;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['lastVisited'] == '0000-00-00 00:00:00') { $lastLogin = $neverText; } else { $lastLogin = dateFormat($row['lastVisited']); }
	if ($row['isActive'] == '1') { $theStatus = 'selected'; } else { $theStatus = ''; }
	if ($row['isAdmin'] == '1') { $theType = 'selected'; } else { $theType = ''; }

	if ($isAdmin != '1') {
?>
	<div class="content-col" id="page">
		<div class="inner-content">
			<h1 class="font-weight-thin no-margin-top"><?php echo $accessErrorHeader; ?></h1>
			<hr />

			<div class="alertMsg danger">
				<i class="fa fa-warning"></i> <?php echo $permissionDenied; ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="content-col" id="page">
		<div class="inner-content">
			<h3 class="font-weight-thin no-margin-top">
				<?php echo $viewUserTitle; ?>: <?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>
				<span class="pull-right">
					<a data-toggle="modal" href="#newUser" class="btn btn-success btn-sm"><i class="fa fa-user" data-toggle="tooltip" data-placement="left" title="<?php echo $newUserNavLink; ?>"></i></a>
				</span>
			</h3>
			<hr />

			<?php if ($msgBox) { echo $msgBox; } ?>

			<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
				<div class="panel-body">
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4>
								<?php echo $userDetailsTitle; ?>
								<small class="pull-right mt-5"><?php echo $lastLoginTH; ?>: <?php echo $lastLogin; ?></small>
							</h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $usersText.' '.$firstNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userFirst" value="<?php echo clean($row['userFirst']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $usersText.' '.$lastNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userLast" value="<?php echo clean($row['userLast']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $usersEmailField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="userEmail" value="<?php echo clean($row['userEmail']); ?>" />
						</div>
					</div>

					<div class="form-group header bgcolor-default mt-10">
						<div class="col-md-12">
							 <h4><?php echo $changeUserPassTitle; ?></h4>
							 <p><small><?php echo $changeUserPassQuip; ?></small></p>
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

					<div class="form-group header bgcolor-default mt-10">
						<div class="col-md-12">
							 <h4><?php echo $userStatusTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $statusField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="isActive">
								<option value="0"><?php echo $inactiveAccText; ?></option>
								<option value="1" <?php echo $theStatus; ?>><?php echo $activeAccText; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $accTypeField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="isAdmin">
								<option value="0"><?php echo $viewUserTitle; ?></option>
								<option value="1" <?php echo $theType; ?>><?php echo $siteAdminText; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $joinDateField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="joinDate" id="editJoinDate" value="<?php echo dbDateFormat($row['joinDate']); ?>" />
						</div>
					</div>

					<div class="form-group header bgcolor-default mt-10">
						<div class="col-md-12">
							 <h4><?php echo $userNotesTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $privNotesField; ?></label>
						<div class="col-sm-8">
							<textarea class="form-control" name="userNotes" rows="2"><?php echo clean($row['userNotes']); ?></textarea>
							<span class="help-block"><?php echo $privNotesHelp; ?></span>
						</div>
					</div>

				</div>
				<hr />
				<button type="input" name="submit" value="editUser" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
			</form>

		</div>
	</div>
<?php } ?>