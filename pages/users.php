<?php
	include 'includes/navigation.php';
	$dataTables = 'true';
	$jsFile = 'users';
	
	// Delete User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteUser') {
		$uid = $mysqli->real_escape_string($_POST['uid']);
		
		if ($uid == '1') {
			$msgBox = alertBox($primAdminDeleteError, "<i class='fa fa-ban'></i>", "warning");
		} else {
			// Delete the Account
			$stmt = $mysqli->prepare("DELETE FROM users WHERE userId = ?");
			$stmt->bind_param('s', $uid);
			$stmt->execute();
			$stmt->close();

			// Delete User Dates
			$stmt = $mysqli->prepare("DELETE FROM events WHERE userId = ?");
			$stmt->bind_param('s', $uid);
			$stmt->execute();
			$stmt->close();
			
			// Delete the Category
			$stmt = $mysqli->prepare("DELETE FROM categories WHERE userId = ?");
			$stmt->bind_param('s', $uid);
			$stmt->execute();
			$stmt->close();

			// Delete all related Tasks
			$stmt = $mysqli->prepare("DELETE FROM tasks WHERE userId = ?");
			$stmt->bind_param('s', $uid);
			$stmt->execute();
			$stmt->close();
			
			// Delete all related Tasks Notes
			$stmt = $mysqli->prepare("DELETE FROM tasknotes WHERE userId = ?");
			$stmt->bind_param('s', $uid);
			$stmt->execute();
			$msgBox = alertBox($userDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
    }
	
	// users Data
	$qry = "SELECT * FROM users";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	
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
				<?php echo $userListNavLink; ?>
				<span class="pull-right">
					<a data-toggle="modal" href="#newUser" class="btn btn-success btn-sm"><i class="fa fa-user" data-toggle="tooltip" data-placement="left" title="<?php echo $newUserNavLink; ?>"></i></a>
				</span>
			</h3>
			<hr />

			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<?php if(mysqli_num_rows($res) < 1) { ?>
				<div class="alertMsg message">
					<i class="fa fa-info-circle"></i> <?php echo $noUsersFoundMsg; ?>
				</div>
			<?php } else { ?>
				<table id="userList" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo $usersNameTH; ?></th>
							<th><?php echo $emailAddyField; ?></th>
							<th><?php echo $statusTH; ?></th>
							<th><?php echo $catsNavLink; ?></th>
							<th><?php echo $tasksNavLink; ?></th>
							<th><?php echo $lastLoginTH; ?></th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						<?php
							while ($row = mysqli_fetch_assoc($res)) {
							
							// Set Account Status
							if ($row['isActive'] == '1') { $theStatus = '<span class="text-success">'.$activeText.'</span>'; } else { $theStatus = '<strong class="text-danger">'.$inactiveText.'</strong>'; }
							
							// Check the Last Visited Date
							if ($row['lastVisited'] != '0000-00-00 00:00:00') { $lastVisited = dateFormat($row['lastVisited']); } else { $lastVisited = ''; }
							
							// Get Total Categories for the User
							$qcats = "SELECT 'X' FROM categories WHERE userId = ".$row['userId'];
							$catCheck = mysqli_query($mysqli, $qcats) or die('-2'.mysqli_error());
							$totCat = mysqli_num_rows($catCheck);
							
							// Get Total Tasks for the User
							$qtasks = "SELECT 'X' FROM tasks WHERE userId = ".$row['userId'];
							$taskCheck = mysqli_query($mysqli, $qtasks) or die('-3'.mysqli_error());
							$totCount = mysqli_num_rows($taskCheck);
						?>
							<tr>
								<td><a href="index.php?page=viewUser&uid=<?php echo $row['userId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewUserTooltip; ?>"><?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?></a></td>
								<td><a href="mailto:<?php echo clean($row['userEmail']); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $emailUserTooltip; ?>"><?php echo clean($row['userEmail']); ?></a></td>
								<td><?php echo $theStatus; ?></td>
								<td><?php echo $totCat; ?></td>
								<td><?php echo $totCount; ?></td>
								<td><?php echo $lastVisited; ?></td>
								<td>
									<a href="index.php?page=viewUser&uid=<?php echo $row['userId']; ?>" class="info"><i class="fa fa-pencil warning" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i></a>
									<?php
										if ($row['userId'] == '1') {
											echo '<a data-toggle="modal" href="" class="disabled"><i class="fa fa-times-circle disabled" data-toggle="tooltip" data-placement="top" title="'.$disbaledTooltip.'"></i></a>';
										} else {
											echo '<a data-toggle="modal" href="#deleteUser'.$row['userId'].'" class="danger"><i class="fa fa-times-circle danger" data-toggle="tooltip" data-placement="top" title="'.$deleteTooltip.'"></i></a>';
										}
									?>
								</td>
							</tr>

							<div class="modal fade" id="deleteUser<?php echo $row['userId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCat<?php echo $row['userId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead mb-0">
													<?php echo $deleteUserQuip1; ?> <strong>"<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>"</strong>?</p>
													<p class="mt-0"><small><strong class="text-danger"><?php echo $deleteUserQuip2; ?></strong></small></p>
												</p>
											</div>
											<div class="modal-footer">
												<input name="uid" type="hidden" value="<?php echo $row['userId']; ?>" />
												<button type="input" name="submit" value="deleteUser" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			
		</div>
	</div>
<?php } ?>