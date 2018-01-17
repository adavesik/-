<?php
	include 'includes/navigation.php';
	$taskId = $_GET['taskId'];
	$jsFile = 'viewTask';
	
	// Reopen Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'reopenTask') {
		$taskStatus = 'Reopened';
		$dateClosed = '0000-00-00 00:00:00';
		$lastUpdated = date("Y-m-d H:i:s");

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
    }
	
	// Complete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'completeTask') {
		$taskStatus = 'Closed';
		$taskPercent = '100';
		$dateClosed = $lastUpdated = date("Y-m-d H:i:s");

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
    }
	
	// Edit Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'editTask') {
		if($_POST['taskTitle'] == "") {
            $msgBox =  alertBox($taskTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskDesc'] == "") {
            $msgBox = alertBox($taskDescReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskPriority'] == "") {
            $msgBox = alertBox($taskPriorityReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskStatus'] == "") {
            $msgBox = alertBox($taskStatusReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskPercent'] == "") {
            $msgBox = alertBox($taskPercentCompReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskStart'] == "") {
            $msgBox = alertBox($taskStartDateReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskDue'] == "") {
            $msgBox = alertBox($taskDueDateReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$taskTitle = $mysqli->real_escape_string($_POST['taskTitle']);
			$catId = $mysqli->real_escape_string($_POST['catId']);
			$taskDesc = $_POST['taskDesc'];
			$taskPriority = $mysqli->real_escape_string($_POST['taskPriority']);
			$taskStatus = $mysqli->real_escape_string($_POST['taskStatus']);
			$taskPercent = $mysqli->real_escape_string($_POST['taskPercent']);
			$taskStart = $mysqli->real_escape_string($_POST['taskStart']);
			$taskDue = $mysqli->real_escape_string($_POST['taskDue']);
			$dateClosed = $mysqli->real_escape_string($_POST['dateClosed']);
			$assignerId = $mysqli->real_escape_string($_POST['assignId']);
			$taskDeadline = $mysqli->real_escape_string($_POST['taskDeadline']);
			$lastUpdated = date("Y-m-d H:i:s");

			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										catId = ?,
										assignerId = ?,
										taskTitle = ?,
										taskDesc = ?,
										taskPriority = ?,
										taskStatus = ?,
										taskPercent = ?,
										taskStart = ?,
										taskDue = ?,
										dateClosed = ?,
										lastUpdated = ?,
										taskDeadline = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('sssssssssssss',
								$catId,
								$assignerId,
								$taskTitle,
								$taskDesc,
								$taskPriority,
								$taskStatus,
								$taskPercent,
								$taskStart,
								$taskDue,
								$dateClosed,
								$lastUpdated,
								$taskDeadline,
								$taskId
			);
			$stmt->execute();
			$msgBox = alertBox($taskUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
    }
	
	// Edit Note
	if (isset($_POST['submit']) && $_POST['submit'] == 'editNote') {
		if($_POST['taskNote'] == "") {
            $msgBox = alertBox($taskNoteReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$noteId = $mysqli->real_escape_string($_POST['noteId']);
			$taskNote = $_POST['taskNote'];
			$lastUpdated = $lastUpdated = date("Y-m-d H:i:s");
			
			// Update the LastUpdated Date for the Task
			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										lastUpdated = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('ss',
								$lastUpdated,
								$taskId
			);
			$stmt->execute();
			$stmt->close();

			// Save the Note
			$stmt = $mysqli->prepare("UPDATE
										tasknotes
									SET
										taskNote = ?,
										lastUpdated = ?
									WHERE
										noteId = ?"
			);
			$stmt->bind_param('sss',
								$taskNote,
								$lastUpdated,
								$noteId
			);
			$stmt->execute();
			$msgBox = alertBox($taskNoteUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
			$_POST['taskNote'] = '';
		}
    }
	
	// Delete Note
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteNote') {
		$noteId = $mysqli->real_escape_string($_POST['noteId']);
		$lastUpdated = $lastUpdated = date("Y-m-d H:i:s");
		
		// Update the LastUpdated Date for the Task
		$stmt = $mysqli->prepare("UPDATE
									tasks
								SET
									lastUpdated = ?
								WHERE
									taskId = ?"
		);
		$stmt->bind_param('ss',
							$lastUpdated,
							$taskId
		);
		$stmt->execute();
		$stmt->close();
			
		// Delete the Note
		$stmt = $mysqli->prepare("DELETE FROM tasknotes WHERE noteId = ?");
		$stmt->bind_param('s', $noteId);
		$stmt->execute();
		$msgBox = alertBox($taskNoteDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
    }
	
	// Add New Note
    if (isset($_POST['submit']) && $_POST['submit'] == 'addNote') {
        // Validation
		if($_POST['taskNote'] == "") {
            $msgBox = alertBox($taskNoteReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$catId = $mysqli->real_escape_string($_POST['catId']);
			$taskNote = $_POST['taskNote'];
			$noteDate = date("Y-m-d H:i:s");
			
			// Update the LastUpdated Date for the Task
			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										lastUpdated = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('ss',
								$noteDate,
								$taskId
			);
			$stmt->execute();
			$stmt->close();

			// Save the New Note
			$stmt = $mysqli->prepare("
								INSERT INTO
									tasknotes(
										taskId,
										catId,
										userId,
										taskNote,
										noteDate
									) VALUES (
										?,
										?,
										?,
										?,
										?
									)
			");
			$stmt->bind_param('sssss',
								$taskId,
								$catId,
								$userId,
								$taskNote,
								$noteDate
			);
			$stmt->execute();
			$stmt->close();
			$msgBox = alertBox($taskNoteSavedMsg, "<i class='fa fa-check-square'></i>", "success");
			// Clear the Form of values
			$_POST['taskNote'] = '';
		}
	}
	
	// Task Data
	$qry = "SELECT
tasks.taskId,
tasks.catId,
tasks.userId,
tasks.assignerId,
tasks.taskTitle,
tasks.taskDesc,
tasks.taskPriority,
tasks.taskStatus,
tasks.taskPercent,
tasks.taskStart,
tasks.taskDue,
tasks.isClosed,
tasks.dateClosed,
tasks.lastUpdated,
tasks.taskDeadline,
categories.catId,
categories.catName,
assigners.assigner_desc
FROM
tasks
LEFT JOIN categories ON tasks.catId = categories.catId
INNER JOIN assigners ON assigners.id = tasks.assignerId
WHERE
				tasks.taskId = ".$taskId;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);
	
	// Task Note Data
	$sql = "SELECT
				noteId,
				catId,
				taskNote,
				noteDate,
				UNIX_TIMESTAMP(noteDate) AS orderDate,
				lastUpdated
			FROM tasknotes
			WHERE taskId = ".$taskId."
			ORDER BY orderDate";
	$results = mysqli_query($mysqli, $sql) or die('-2'.mysqli_error());
	$totNotes = mysqli_num_rows($results);
	
	if ($row['userId'] != $userId) {
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
				<?php echo $taskText; ?>: <?php echo clean($row['taskTitle']); ?>
				<span class="pull-right">
					<a href="index.php?page=viewCategory&catId=<?php echo $row['catId']; ?>" class="btn btn-info btn-sm"><i class="fa fa-tag" data-toggle="tooltip" data-placement="left" title="<?php echo $viewCatTooltip; ?>"></i></a>
					<a data-toggle="modal" href="#newTask" class="btn btn-info btn-sm"><i class="fa fa-tasks" data-toggle="tooltip" data-placement="left" title="<?php echo $newTaskTooltip; ?>"></i></a>
					<a data-toggle="modal" href="#newCategory" class="btn btn-warning btn-sm"><i class="fa fa-tag" data-toggle="tooltip" data-placement="left" title="<?php echo $newCatTooltip; ?>"></i></a>
				</span>
			</h3>
			<hr />
			
			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<ul class="nav nav-tabs mt-10" role="tablist">
				<li class="active"><a href="#task" role="tab" data-toggle="tab"><i class="fa fa-tasks"></i> <?php echo $taskText; ?></a></li>
				<li><a href="#notes" role="tab" data-toggle="tab"><i class="fa fa-comments"></i> <?php echo $taskNotesText; ?> <span class="badge pull-right"><?php echo $totNotes; ?></span></a></li>
				<form action="" method="post">
					<?php if ($row['isClosed'] == '1') { ?>
						<li class="pull-right"><button type="input" name="submit" value="reopenTask" class="btn btn-info btn-sm btn-icon"><i class="fa fa-reply-all"></i> <?php echo $reopenTaskBtn; ?></button></li>
					<?php } else { ?>
						<li class="pull-right"><button type="input" name="submit" value="completeTask" class="btn btn-tasked btn-sm btn-icon"><i class="fa fa-check"></i> <?php echo $completeTaskBtn; ?></button></li>
					<?php } ?>
				</form>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade in active" id="task">
					<?php if ($row['isClosed'] == '1') { ?>
						<div class="alertMsg message mt-10 mb-0">
							<i class="fa fa-check-square-o"></i> <?php echo $taskClosedMsg; ?>
						</div>
					<?php } ?>

					<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
						<div class="panel-body">
							<div class="form-group header bgcolor-default">
								<div class="col-md-12">
									 <h4><?php echo $taskDetailsText; ?></h4>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $titleText; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskTitle" value="<?php echo clean($row['taskTitle']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $categoryField; ?></label>
								<div class="col-sm-8">
									<select class="form-control" name="catId" id="catId">
										<?php
											$tcat = "SELECT catId, catName FROM categories WHERE userId = ".$userId." AND isActive = 1";
											$rest = mysqli_query($mysqli, $tcat) or die('-2'.mysqli_error());
										?>
										<?php while ($tcatrow = mysqli_fetch_assoc($rest)) { ?>
											<option value="<?php echo $tcatrow['catId']; ?>"><?php echo clean($tcatrow['catName']); ?></option>
										<?php } ?>
									</select>
									<input name="taskCat" id="taskCat" type="hidden" value="<?php echo clean($row['catName']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $descText; ?></label>
								<div class="col-sm-8">
									<textarea class="form-control" required="" name="taskDesc" rows="6"><?php echo clean($row['taskDesc']); ?></textarea>
								</div>
							</div>
							
							<div class="form-group header bgcolor-default mt-20">
								<div class="col-md-12">
									 <h4><?php echo $taskSettingsTitle; ?></h4>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $priorityField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskPriority" value="<?php echo clean($row['taskPriority']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $statusField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskStatus" value="<?php echo clean($row['taskStatus']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $perentCompField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskPercent" value="<?php echo clean($row['taskPercent']); ?>" />
									<span class="help-block"><?php echo $perentCompHelp; ?></span>
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $percentSymbol; ?></label>
								<div class="col-sm-8">
									<select class="form-control" name="assignId" id="assignId">
										<?php
										$tcat = "SELECT * FROM assigners";
										$rest = mysqli_query($mysqli, $tcat) or die('-2'.mysqli_error());
										?>
										<?php while ($tcatrow = mysqli_fetch_assoc($rest)) {
											if($tcatrow['assigner_desc'] == $row['assigner_desc']){
											?>
											<option value="<?php echo $tcatrow['id']; ?>" selected = selected><?php echo clean($tcatrow['assigner_desc']); ?></option>
												<?php } ?>
											<option value="<?php echo $tcatrow['id']; ?>"><?php echo clean($tcatrow['assigner_desc']); ?></option>
										<?php } ?>
									</select>
									<input name="taskAssign" id="taskAssign" type="hidden" value="<?php echo clean($row['assigner_desc']); ?>" />
								</div>
							</div>

							
							<div class="form-group header bgcolor-default mt-10">
								<div class="col-md-12">
									 <h4><?php echo $taskDatesTitle; ?></h4>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $startDateField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskStart" id="editTaskStart" value="<?php echo dbDateFormat($row['taskStart']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $dateDueField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskDue" id="editTaskDue" value="<?php echo dbDateFormat($row['taskDue']); ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $deadlineField; ?></label>
								<div class="col-sm-8">
									<input class="form-control" type="text" required="" name="taskDeadline" id="editTaskDeadline" value="<?php echo dbDateFormat($row['taskDeadline']); ?>" />
								</div>
							</div>





							<?php if ($row['isClosed'] == '1') { ?>
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $dateClosedField; ?></label>
									<div class="col-sm-8">
										<input class="form-control" type="text" name="dateClosed" id="editDateClosed" value="<?php echo dbDateFormat($row['dateClosed']); ?>" />
									</div>
								</div>
							<?php } else { ?>
								<input name="dateClosed" type="hidden" value="0000-00-00" />
							<?php } ?>
						</div>
						<hr />
						<button type="input" name="submit" value="editTask" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
					</form>
				</div>
				<div class="tab-pane fade" id="notes">
					<?php if(mysqli_num_rows($results) < 1) { ?>
						<div class="alertMsg message">
							<i class="fa fa-info-circle"></i> <?php echo $noNotesMsg; ?>
						</div>
					<?php
						} else {
							while ($rows = mysqli_fetch_assoc($results)) {
					?>
							<div class="well well-sm">
								<p class="mb-10"><?php echo nl2br(clean($rows['taskNote'])); ?></p>
								<hr />
								<p class="mt-10">
									<span class="text-muted"><i class="fa fa-calendar"></i> <?php echo dateFormat($rows['noteDate']) .' at '.timeFormat($rows['noteDate']); ?></span>
									<span class="pull-right">
										<span data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>">
											<a data-toggle="modal" href="#editNote<?php echo $rows['noteId']; ?>"><i class="fa fa-pencil"></i></a>
										</span>
										<span data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>">
											<a data-toggle="modal" href="#deleteNote<?php echo $rows['noteId']; ?>"><i class="fa fa-times-circle"></i></a>
										</span>
									</span>
								</p>
							</div>
							
							<div class="modal fade" id="editNote<?php echo $rows['noteId']; ?>" tabindex="-1" role="dialog" aria-labelledby="editNote<?php echo $rows['noteId']; ?>" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php echo $closeBtn; ?></span></button>
											<h4 class="modal-title"><?php echo $editNoteModal; ?></h4>
										</div>
										<form action="" method="post">
											<div class="modal-body">
												<div class="form-group">
													<textarea class="form-control" required="" name="taskNote" rows="5"><?php echo clean($rows['taskNote']); ?></textarea>
												</div>
											</div>
											<div class="modal-footer">
												<input name="noteId" type="hidden" value="<?php echo $rows['noteId']; ?>" />
												<button type="input" name="submit" value="editNote" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<div class="modal fade" id="deleteNote<?php echo $rows['noteId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteNote<?php echo $rows['noteId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $deleteNoteQuip; ?></p>
											</div>
											<div class="modal-footer">
												<input name="noteId" type="hidden" value="<?php echo $rows['noteId']; ?>" />
												<button type="input" name="submit" value="deleteNote" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
					<?php
							}
						}	
					?>
					
					<h4 class="mt-20"><?php echo $addNoteTitle; ?></h4>
					<hr />
					<form action="" method="post">
						<div class="form-group mt-10">
							<textarea class="form-control" required="" name="taskNote" rows="5"><?php echo isset($_POST['taskNote']) ? $_POST['taskNote'] : ''; ?></textarea>
						</div>
						<input name="catId" type="hidden" value="<?php echo $row['catId']; ?>" />
						<button type="input" name="submit" value="addNote" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
					</form>
				</div>
			</div>

		</div>
	</div>
<?php } ?>