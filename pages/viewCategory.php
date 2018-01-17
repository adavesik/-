<?php
	include 'includes/navigation.php';
	$catId = $_GET['catId'];
	$dataTables = 'true';
	$jsFile = 'viewCategory';
	
	// Complete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'completeTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$taskStatus = 'Closed';
		$taskPercent = '100';
		$dateClosed = $lastUpdated = date("Y-m-d H:i:s");
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId) {
			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										taskStatus = ?,
										taskPercent = ?,
										isClosed = 1,
										dateClosed = ?,
										lastUpdated = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('sssss',
								$taskStatus,
								$taskPercent,
								$dateClosed,
								$lastUpdated,
								$taskId
			);
			$stmt->execute();
			$msgBox = alertBox($taskMarkedCompMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskCompErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Reopen Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'reopenTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$taskStatus = 'Reopened';
		$dateClosed = '0000-00-00 00:00:00';
		$lastUpdated = date("Y-m-d H:i:s");
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId) {
			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										taskStatus = ?,
										isClosed = 0,
										dateClosed = ?,
										lastUpdated = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('ssss',
								$taskStatus,
								$dateClosed,
								$lastUpdated,
								$taskId
			);
			$stmt->execute();
			$msgBox = alertBox($taskReopenedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskReopenErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Delete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId) {
			// Delete the task
			$stmt = $mysqli->prepare("DELETE FROM tasks WHERE taskId = ?");
			$stmt->bind_param('s', $taskId);
			$stmt->execute();
			$stmt->close();

			// Delete all related Task Notes
			$stmt = $mysqli->prepare("DELETE FROM tasknotes WHERE taskId = ?");
			$stmt->bind_param('s', $taskId);
			$stmt->execute();
			$msgBox = alertBox($taskedDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskDeleteErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Task Data
	$qry = "SELECT
				tasks.taskId,
				tasks.userId,
				tasks.catId,
				tasks.taskTitle,
				tasks.taskDesc,
				tasks.taskPriority,
				tasks.taskStatus,
				tasks.taskPercent,
				tasks.taskDue,
				tasks.isClosed,
				tasks.lastUpdated,
				categories.catName
			FROM
				tasks
				LEFT JOIN categories ON tasks.catId = categories.catId
			WHERE
				tasks.catId = ".$catId;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	
	// Category Data
	$sql = "SELECT userId, catName FROM categories WHERE catId = ".$catId;
	$results = mysqli_query($mysqli, $sql) or die('-2'.mysqli_error());
	$rows = mysqli_fetch_assoc($results);
	
	if ($rows['userId'] != $userId) {
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
				<?php echo $categoryField; ?>: <?php echo clean($rows['catName']); ?>
				<span class="pull-right">
					<a href="index.php?page=editCategory&catId=<?php echo $catId; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="<?php echo $editCatTooltip; ?>"></i></a>
				</span>
			</h3>
			<hr />
			
			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<?php if(mysqli_num_rows($res) < 1) { ?>
			<div class="alertMsg message">
				<i class="fa fa-info-circle"></i> <?php echo $noOpenTasks; ?>
			</div>
		<?php } else { ?>
			<table id="taskList" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th></th>
						<th><?php echo $taskTitleTH; ?></th>
						<th><?php echo $priorityTH; ?></th>
						<th><?php echo $statusTH; ?></th>
						<th><?php echo $percentSymbol; ?></th>
						<th><?php echo $dueDateField; ?></th>
						<th><?php echo $lastUpdatedText; ?></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
						while ($row = mysqli_fetch_assoc($res)) {
							// Set the Complete or Reopen Button
							if ($row['isClosed'] == '1') {
								$taskOption = '<a data-toggle="modal" href="#reopenTask'.$row['taskId'].'" class="success"><i class="fa fa-reply-all info" data-toggle="tooltip" data-placement="top" title="'.$reopenTaskText.'"></i></a>';
							} else {
								$taskOption = '<a data-toggle="modal" href="#completeTask'.$row['taskId'].'" class="success"><i class="fa fa-check-square-o success" data-toggle="tooltip" data-placement="top" title="'.$completeTooltip.'"></i></a>';
							}
							// Set the Status
							if ($row['isClosed'] == '0') { $theStatus = '<span class="text-success">'.clean($row['taskStatus']).'</span>'; } else { $theStatus = '<strong class="text-danger">'.clean($row['taskStatus']).'</strong>'; }
					?>
							<tr>
								<td><?php echo $row['isClosed']; ?></td>
								<td><a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewTaskTooltip; ?>"><?php echo clean($row['taskTitle']); ?></a></td>
								<td><?php echo clean($row['taskPriority']); ?></td>
								<td><?php echo $theStatus; ?></td>
								<td><?php echo clean($row['taskPercent']); ?>%</td>
								<td><?php echo dateFormat($row['taskDue']); ?></td>
								<td><?php echo dateFormat($row['lastUpdated']); ?></td>
								<td>
									<?php echo $taskOption; ?>
									<a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" class="info"><i class="fa fa-pencil warning" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i></a>
									<a data-toggle="modal" href="#deleteTask<?php echo $row['taskId']; ?>" class="danger"><i class="fa fa-times-circle danger" data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>"></i></a>
								</td>
							</tr>
							
							<div class="modal fade" id="completeTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="completeTask<?php echo $row['taskId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $completeTaskQuip1; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong> <?php echo $completeTaskQuip2; ?></p>
											</div>
											<div class="modal-footer">
												<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
												<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
												<button type="input" name="submit" value="completeTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<div class="modal fade" id="reopenTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="reopenTask<?php echo $row['taskId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $reopenTaskQuip; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong>?</p>
											</div>
											<div class="modal-footer">
												<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
												<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
												<button type="input" name="submit" value="reopenTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="modal fade" id="deleteTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteTask<?php echo $row['taskId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $deleteTaskQuip; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong>?</p>
											</div>
											<div class="modal-footer">
												<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
												<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
												<button type="input" name="submit" value="deleteTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
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