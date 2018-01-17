<?php
	include 'includes/navigation.php';
	$dataTables = 'true';
	$jsFile = 'openTasks';
	
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

										isClosed = 1,
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
			$msgBox = alertBox($taskMarkedCompMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskCompErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
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
	
	// Open Tasks
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
tasks.taskDeadline,
categories.catName,
assigners.assigner_desc
FROM
tasks
LEFT JOIN categories ON tasks.catId = categories.catId
INNER JOIN assigners ON assigners.id = tasks.assignerId
WHERE
				tasks.userId = ".$userId." AND
				tasks.isClosed = '0'";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top">
			<?php echo $openTasksNavLink; ?>
			<span class="pull-right">
				<a data-toggle="modal" href="#newTask" class="btn btn-info btn-sm"><i class="fa fa-tasks" data-toggle="tooltip" data-placement="left" title="<?php echo $newTaskTooltip; ?>"></i></a>
				<a data-toggle="modal" href="#newCategory" class="btn btn-warning btn-sm"><i class="fa fa-tag" data-toggle="tooltip" data-placement="left" title="<?php echo $newCatTooltip; ?>"></i></a>
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
						<th><?php echo $statusTH; ?></th>
						<th><?php echo $taskTitleTH; ?></th>
						<th><?php echo $taskCatTH; ?></th>
						<th><?php echo $priorityTH; ?></th>

						<th><?php echo $percentSymbol; ?></th>
						<th><?php echo $deadlineField; ?></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php while ($row = mysqli_fetch_assoc($res)) { ?>
						<tr>
							<td><?php echo clean($row['taskStatus']); ?></td>
							<td><a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewTaskTooltip; ?>"><?php echo clean($row['taskTitle']); ?></a></td>
							<td><a href="index.php?page=viewCategory&catId=<?php echo $row['catId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewCatTooltip; ?>"><?php echo clean($row['catName']); ?></a></td>
							<td><?php echo clean($row['taskPriority']); ?></td>

							<td><?php echo clean($row['assigner_desc']); ?></td>
							<td><?php echo dateFormat($row['taskDeadline']); ?></td>
							<td>
								<a data-toggle="modal" href="#completeTask<?php echo $row['taskId']; ?>" class="success"><i class="fa fa-check-square-o success" data-toggle="tooltip" data-placement="top" title="<?php echo $completeTooltip; ?>"></i></a>
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