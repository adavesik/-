<?php
	include 'includes/navigation.php';
	$jsFile = 'dashboard';

	// Complete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'completeTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$taskStatus = 'Closed';
		$taskPercent = '100';
		$dateClosed = $lastUpdated = date("Y-m-d H:i:s");

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
    }

	// Delete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);

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
    }

	// Delete Date
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteDate') {
		$eventId = $mysqli->real_escape_string($_POST['eventId']);
		$stmt = $mysqli->prepare("DELETE FROM events WHERE eventId = ?");
		$stmt->bind_param('s', $eventId);
		$stmt->execute();
		$msgBox = alertBox($dateDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
    }

	// Open Tasks & Count
	$q1 = "SELECT
				tasks.taskId,
				tasks.catId,
				tasks.taskTitle,
				tasks.taskDesc,
				tasks.taskPriority,
				tasks.taskStatus,
				tasks.taskPercent,
				tasks.taskDue,
				UNIX_TIMESTAMP(tasks.taskDue) AS orderDate,
				categories.catName
			FROM
				tasks
				LEFT JOIN categories ON tasks.catId = categories.catId
			WHERE
				tasks.userId = ".$userId." AND
				tasks.isClosed = '0'
			ORDER BY
				orderDate";
	$r1 = mysqli_query($mysqli, $q1) or die('-1'.mysqli_error());
	$totTasks = mysqli_num_rows($r1);
	if ($totTasks == 1) { $open = $taskText; } else { $open = $tasksText; }

	// Active Categories Count
	$q2 = "SELECT 'X' FROM categories WHERE categories.userId = ".$userId." AND categories.isActive = '1'";
	$r2 = mysqli_query($mysqli, $q2) or die('-2'.mysqli_error());
	$catTotal = mysqli_num_rows($r2);
	if ($catTotal == 1) { $catCount = $categoryText; } else { $catCount = $categoriesText; }

	// Upcoming Dates
	$q3 = "SELECT
				eventId,
				userId,
				startDate,
				DATE_FORMAT(startDate,'%H:%i') AS startTime,
				UNIX_TIMESTAMP(events.startDate) AS orderDate,
				endDate,
				DATE_FORMAT(endDate,'%H:%i') AS endTime,
				eventTitle,
				eventDesc,
				isTask,
				lastUpdated
			FROM
				events
			WHERE
				events.userId = ".$userId." AND
				events.endDate >= CURDATE()
			ORDER BY
				orderDate
			LIMIT 5";
	$r3 = mysqli_query($mysqli, $q3) or die('-3'.mysqli_error());

	// Upcoming Dates Count
	$q4 = "SELECT 'X' FROM events WHERE events.userId = ".$userId." AND events.endDate >= CURDATE()";
	$r4 = mysqli_query($mysqli, $q4) or die('-4'.mysqli_error());
	$totEvents = mysqli_num_rows($r4);
	if ($totEvents == 1) { $events = $dateText; } else { $events = $datesText; }
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h1 class="font-weight-thin no-margin-top"><?php echo $dashboardNavLink; ?></h1>
		<hr />

		<?php if ($msgBox) { echo $msgBox; } ?>

		<div class="row">
			<div class="col-md-4 mt-20">
				<div class="dashblocks dashblocks-info info">
					<div class="dashblocksBody">
						<i class="boxIcon fa fa-tasks"></i>
						<span><?php echo $totTasks; ?></span>
					</div>
					<div class="dashblocksFooter"><a href="index.php?page=openTasks"><?php echo $openText.' '.$open; ?></a></div>
				</div>
			</div>
			<div class="col-md-4 mt-20">
				<div class="dashblocks dashblocks-warning warning">
					<div class="dashblocksBody">
						<i class="boxIcon fa fa-tags"></i>
						<span><?php echo $catTotal; ?></span>
					</div>
					<div class="dashblocksFooter"><a href="index.php?page=categories"><?php echo $activeText.' '.$catCount; ?></a></div>
				</div>
			</div>
<!--			<div class="col-md-4 mt-20">
				<div class="dashblocks dashblocks-tasked tasked">
					<div class="dashblocksBody">
						<i class="boxIcon fa fa-calendar"></i>
						<span><?php /*echo $totEvents; */?></span>
					</div>
					<div class="dashblocksFooter"><a href="index.php?page=calendar"><?php /*echo $upcomingText.' '.$events; */?></a></div>
				</div>
			</div>-->
		</div>

		<div class="row mt-20">
			<div class="col-md-12">
				<h3>
					<?php echo $openTasksNavLink; ?>
					<span class="pull-right">
						<a href="#" id="open-tasks"><i class="fa fa-arrow-down" data-toggle="tooltip" data-placement="left" title="<?php echo $openTasksToggle; ?>"></i></a>
						<a href="#" id="close-tasks"><i class="fa fa-arrow-up" data-toggle="tooltip" data-placement="left" title="<?php echo $closeTasksToggle; ?>"></i></a>
						<a data-toggle="modal" href="#newTask" class="btn btn-info btn-xs"><i class="fa fa-tasks" data-toggle="tooltip" data-placement="left" title="<?php echo $newTaskTooltip; ?>"></i></a>
						<a data-toggle="modal" href="#newCategory" class="btn btn-warning btn-xs"><i class="fa fa-tag" data-toggle="tooltip" data-placement="left" title="<?php echo $newCatTooltip; ?>"></i></a>
					</span>
				</h3>
				<hr />
				<?php if(mysqli_num_rows($r1) < 1) { ?>
					<div class="alertMsg message">
						<i class="fa fa-info-circle"></i> <?php echo $noOpenTasks; ?>
					</div>
				<?php
					} else {
						while ($row = mysqli_fetch_assoc($r1)) {
							$t = "SELECT 'X' FROM tasknotes WHERE taskId = ".$row['taskId'];
							$r = mysqli_query($mysqli, $t) or die('-2'.mysqli_error());
							$totNotes = mysqli_num_rows($r);
							if ($totNotes == '1') { $textNote = $noteText; } else { $textNote = $notesText; }
				?>
							<div class="panel panel-task">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a href="#" data-perform="panel-collapse" class="toggle task-toggle" data-toggle="tooltip" data-placement="top" title="<?php echo $toggleLink; ?>"><i class="fa fa-chevron-right"></i></a>
										<a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewTaskTooltip; ?>">
											<?php echo clean($row['taskTitle']); ?>
										</a>
										<small>
											<a href="index.php?page=viewCategory&catId=<?php echo $row['catId']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewCatTooltip; ?>">
												<?php echo clean($row['catName']); ?>
											</a>
										</small>
										<div class="pull-right">
											<a data-toggle="modal" href="#completeTask<?php echo $row['taskId']; ?>" class="success">
												<i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="<?php echo $completeTooltip; ?>"></i>
											</a>
											<a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" class="info">
												<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i>
											</a>
											<a data-toggle="modal" href="#deleteTask<?php echo $row['taskId']; ?>" class="danger">
												<i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>"></i>
											</a>
										</div>
									</h4>
									<div class="clearfix"></div>
								</div>

								<div class="panel-wrapper collapse task-toggle">
									<div class="panel-body">
										<span class="label label-default"><?php echo clean($row['taskPriority']); ?></span>
										<span class="label label-default"><?php echo dateFormat($row['taskDue']); ?></span>
										<span class="label label-default"><?php echo clean($row['taskStatus']); ?></span>
										<span class="label label-default"><?php echo $totNotes.' '.$textNote; ?></span>
										<p class="mt-10 mb-15"><?php echo nl2br(clean($row['taskDesc'])); ?></p>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo clean($row['taskPercent']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo clean($row['taskPercent']); ?>%;">
												<span class="sr-only"><?php echo clean($row['taskPercent']).$percCompleteText; ?></span>
											</div>
											<span class="progress-completed"><?php echo clean($row['taskPercent']).$percentSymbol; ?></span>
										</div>
									</div>
								</div>
							</div>

							<div class="modal fade" id="completeTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="completeTask<?php echo $row['taskId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $completeTaskQuip1; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong> <?php echo $completeTaskQuip2; ?></p>
											</div>
											<div class="modal-footer">
												<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
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
												<button type="input" name="submit" value="deleteTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
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
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h3>
					<?php echo $upcomingDatesTitle; ?>
					<span class="pull-right">
						<a href="#" id="open-dates"><i class="fa fa-arrow-down" data-toggle="tooltip" data-placement="left" title="<?php echo $openDatesToggle; ?>"></i></a>
						<a href="#" id="close-dates"><i class="fa fa-arrow-up" data-toggle="tooltip" data-placement="left" title="<?php echo $closeDatesToggle; ?>"></i></a>
						<a href="index.php?page=calendar" class="btn btn-tasked btn-xs" data-toggle="tooltip" data-placement="left" title="<?php echo $calendarNavLink; ?>"><i class="fa fa-calendar"></i></a>
					</span>
				</h3>
				<hr />

				<?php if(mysqli_num_rows($r3) < 1) { ?>
					<div class="alertMsg message">
						<i class="fa fa-info-circle"></i> <?php echo $noUpcomingDatesMsg; ?>
					</div>
				<?php
					} else {
						while ($rows = mysqli_fetch_assoc($r3)) {
							if (dbTimeFormat($rows['startDate']) == '00:00') { $dateTimes = ''; } else { $dateTimes = timeFormat($rows['startDate']).' to '.timeFormat($rows['endDate']); }
							if (dbDateFormat($rows['startDate']) == dbDateFormat($rows['endDate'])) { $dateSpan = ''; } else { $dateSpan = '<span class="label label-default">'.dateFormat($rows['startDate']).' through '.dateFormat($rows['endDate']).'</span>'; }
				?>
							<div class="panel panel-date">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a href="#" data-perform="panel-collapse" class="toggle date-toggle" data-toggle="tooltip" data-placement="top" title="<?php echo $toggleLink; ?>"><i class="fa fa-chevron-right"></i></a>
										<?php echo clean($rows['eventTitle']); ?>
										<small>
											<?php
												if ($rows['isTask'] == '1') {
													echo 'Task Due: '.dateFormat($rows['startDate']);
												} else {
													echo dateFormat($rows['startDate']);
												}

												if ($rows['startTime'] != '00:00') {
													echo ' at <span>'.timeFormat($rows['startDate']).'</span>';
												}
											?>
										</small>
										<div class="pull-right">
											<a data-toggle="modal" href="#deleteDate<?php echo $rows['eventId']; ?>" class="danger">
												<i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>"></i>
											</a>
										</div>
									</h4>
									<div class="clearfix"></div>
								</div>

								<div class="panel-wrapper collapse date-toggle">
									<div class="panel-body">
										<?php echo $dateSpan; ?>
										<span class="label label-default"><?php echo $dateTimes; ?></span>
										<p class="mt-10">
											<?php
												if ($rows['eventDesc'] != '') {
													echo str_replace('\r\n', '<br/>', $rows['eventDesc']);
												} else {
													echo $noDateDetails;
												}
											?>
										</p>
									</div>
								</div>
							</div>

							<div class="modal fade" id="deleteDate<?php echo $rows['eventId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteDate<?php echo $rows['eventId']; ?>" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $deleteDateQuip; ?> <strong>"<?php echo clean($rows['eventTitle']); ?>"</strong>?</p>
											</div>
											<div class="modal-footer">
												<input name="eventId" type="hidden" value="<?php echo $rows['eventId']; ?>" />
												<button type="input" name="submit" value="deleteDate" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
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
			</div>
		</div>

	</div>
</div>