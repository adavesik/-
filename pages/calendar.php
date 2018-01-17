<?php
	include 'includes/navigation.php';
	$fullcalendar = 'true';
	$calinclude = 'true';
	$jsFile = 'calendar';
	
	// Add New Event
	if (isset($_POST['submit']) && $_POST['submit'] == 'newEvent') {
		// Validations
		if($_POST['eventTitle'] == '') {
			$msgBox = alertBox($dateTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['startDate'] == '') {
			$msgBox = alertBox($startDateReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$dateOfEvent = $mysqli->real_escape_string($_POST['startDate']);
			$timeOfEvent = $mysqli->real_escape_string($_POST['eventTime']);
			$startDate = $dateOfEvent.' '.$timeOfEvent.':00';
			$endOfEvent = $mysqli->real_escape_string($_POST['endDate']);
			$endTimeOfEvent = $mysqli->real_escape_string($_POST['endTime']);
			$endDate = $endOfEvent.' '.$endTimeOfEvent.':00';
			$eventTitle = $mysqli->real_escape_string($_POST['eventTitle']);
			if (isset($_POST['colorPick'])) {
				$eventColor = $mysqli->real_escape_string($_POST['colorPick']);
			} else {
				$eventColor = '#78a32d';
			}
			$eventDesc = $mysqli->real_escape_string($_POST['eventDesc']);

			$stmt = $mysqli->prepare("
								INSERT INTO
									events(
										userId,
										startDate,
										endDate,
										eventTitle,
										eventDesc,
										eventColor
									) VALUES (
										?,
										?,
										?,
										?,
										?,
										?
									)
			");
			$stmt->bind_param('ssssss',
								$userId,
								$startDate,
								$endDate,
								$eventTitle,
								$eventDesc,
								$eventColor
			);
			$stmt->execute();
			$msgBox = alertBox($dateSavedMsg, "<i class='fa fa-check-square'></i>", "success");
			// Clear the Form of values
			$_POST['startDate'] = $_POST['eventTime'] = $_POST['endDate'] = $_POST['endTime'] = $_POST['eventTitle'] = $_POST['eventDesc'] = $_POST['colorPick'] = '';
			$stmt->close();
		}
	}
	
	// Delete Event
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteEvent') {
		$deleteId = $mysqli->real_escape_string($_POST['deleteId']);
		$uId = $mysqli->real_escape_string($_POST['uId']);
		if ($uId == $userId) {
			$stmt = $mysqli->prepare("DELETE FROM events WHERE eventId = ?");
			$stmt->bind_param('s', $deleteId);
			$stmt->execute();
			$stmt->close();
			$msgBox = alertBox($dateDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
		} else {
			$msgBox = alertBox($dateDeletedErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Edit Event
	if (isset($_POST['submit']) && $_POST['submit'] == 'editEvent') {
		// Validations
		if($_POST['eventTitle'] == '') {
			$msgBox = alertBox($dateTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['startDate'] == '') {
			$msgBox = alertBox($startDateReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$eventId = $mysqli->real_escape_string($_POST['eventId']);
			$dateOfEvent = $mysqli->real_escape_string($_POST['startDate']);
			$timeOfEvent = $mysqli->real_escape_string($_POST['eventTime']);
			$startDate = $dateOfEvent.' '.$timeOfEvent.':00';
			$endOfEvent = $mysqli->real_escape_string($_POST['endDate']);
			$endTimeOfEvent = $mysqli->real_escape_string($_POST['endTime']);
			$endDate = $endOfEvent.' '.$endTimeOfEvent.':00';
			$eventTitle = $mysqli->real_escape_string($_POST['eventTitle']);
			$eventDesc = $mysqli->real_escape_string($_POST['eventDesc']);
			$uId = $mysqli->real_escape_string($_POST['uId']);
			$lastUpdated = $lastUpdated = date("Y-m-d H:i:s");

			if ($uId == $userId) {
				$stmt = $mysqli->prepare("
									UPDATE
										events
									SET
										startDate = ?,
										endDate = ?,
										eventTitle = ?,
										eventDesc = ?,
										lastUpdated = ?
									WHERE
										eventId = ?
				");
				$stmt->bind_param('ssssss',
									$startDate,
									$endDate,
									$eventTitle,
									$eventDesc,
									$lastUpdated,
									$eventId

				);
				$stmt->execute();
				$stmt->close();
				$msgBox = alertBox($dateUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
				// Clear the Form of values
				$_POST['startDate'] = $_POST['eventTime'] = $_POST['endDate'] = $_POST['endTime'] = $_POST['eventTitle'] = $_POST['eventDesc'] = '';
			} else {
				$msgBox = alertBox($dateUpdatedErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			}
		}
	}
	
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top">
			<?php echo $calendarPageTitle; ?>
			<span class="pull-right">
				<a data-toggle="modal" href="#newEvent" class="btn btn-success btn-sm"><i class="fa fa-calendar-o" data-toggle="tooltip" data-placement="left" title="<?php echo $newEventLink; ?>"></i></a>
			</span>
		</h3>
		<hr />

		<?php if ($msgBox) { echo $msgBox; } ?>
		
		<div id="calendar" class="mt-20"></div>
		<p class="text-muted mt-10"><?php echo $calendarPageQuip; ?></p>
	</div>
</div>

<div id="" class="modal fade viewEvent" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title"><span class="event-title"></span></h4>
			</div>
			<div class="modal-body event-padding">
				<p class="event-desc"></p>
			</div>
			<div class="modal-footer">
				<div class="event-actions"></div>
			</div>
		</div>
	</div>
</div>

<div id="" class="modal fade editEvent" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title"><?php echo $editEventModal; ?> <span class="event-modal-title"></span></h4>
			</div>
			<form action="" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="startDate"><?php echo $startDateField; ?></label>
								<input type="text" class="form-control" name="startDate" id="editstartDate" required="" value="" />
								<span class="help-block"><?php echo $dateFormatHelp; ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="eventTime"><?php echo $startTimeField; ?></label>
								<input type="text" class="form-control" name="eventTime" id="editeventTime" value="" />
								<span class="help-block"><?php echo $timeFormatHelp; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="endDate"><?php echo $endDateField; ?></label>
								<input type="text" class="form-control" name="endDate" id="editendDate" required="" value="" />
								<span class="help-block"><?php echo $dateFormatHelp; ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="endTime"><?php echo $endTimeField; ?></label>
								<input type="text" class="form-control" name="endTime" id="editendTime" value="" />
								<span class="help-block"><?php echo $timeFormatHelp; ?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="eventTitle"><?php echo $eventTitleField; ?></label>
						<input type="text" class="form-control titleField" name="eventTitle" required="" value="" />
					</div>
					<div class="form-group">
						<label for="eventDesc"><?php echo $eventDescField; ?></label>
						<textarea class="form-control descField" name="eventDesc" rows="4"></textarea>
						<span class="help-block"><?php echo $eventDescHelp; ?></span>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="eventId" class="event-id" value="" />
					<input type="hidden" name="uId" class="user-id" value="" />
					<button type="input" name="submit" value="editEvent" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
					<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="" class="modal fade deleteEvent" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="" method="post">
				<div class="modal-body">
					<p class="lead"><?php echo $deleteDateQuip; ?> <span class="event-modal-title"></span>?</p>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="deleteId" class="event-id" value="" />
					<input type="hidden" name="uId" class="uid" value="" />
					<button type="input" name="submit" value="deleteEvent" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
					<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
				</div>
			</form>

		</div>
	</div>
</div>

<div id="newEvent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newEvent" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title"><?php echo $newDateModal; ?></h4>
			</div>
			<form action="" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="startDate"><?php echo $startDateField; ?></label>
								<input type="text" class="form-control" name="startDate" id="newstartDate" required="" value="<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : ''; ?>" />
								<span class="help-block"><?php echo $dateFormatHelp; ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="eventTime"><?php echo $startTimeField; ?></label>
								<input type="text" class="form-control" name="eventTime" id="neweventTime" value="<?php echo isset($_POST['eventTime']) ? $_POST['eventTime'] : ''; ?>" />
								<span class="help-block"><?php echo $timeFormatHelp; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="endDate"><?php echo $endDateField; ?></label>
								<input type="text" class="form-control" name="endDate" id="newendDate" required="" value="<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : ''; ?>" />
								<span class="help-block"><?php echo $dateFormatHelp; ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="endTime"><?php echo $endTimeField; ?></label>
								<input type="text" class="form-control" name="endTime" id="newendTime" value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>" />
								<span class="help-block"><?php echo $timeFormatHelp; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="eventTitle"><?php echo $eventTitleField; ?></label>
								<input type="text" class="form-control" name="eventTitle" required="" value="<?php echo isset($_POST['eventTitle']) ? $_POST['eventTitle'] : ''; ?>" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="colorPick"><?php echo $selectColorField; ?></label><br />
								<input type="radio" name="colorPick" id="radioPrimary1" class="236b9b" value="#236b9b" />
								<label for="radioPrimary1" class="radPrimary"><i class="fa fa-square-o"></i></label>
								
								<input type="radio" name="colorPick" id="radioPrimary2" class="1e5d86" value="#1e5d86" />
								<label for="radioPrimary2" class="radPrimary2"><i class="fa fa-square-o"></i></label>

								<input type="radio" name="colorPick" id="radioInfo1" class="4da0d7" value="#4da0d7" />
								<label for="radioInfo1" class="radInfo"> <i class="fa fa-square-o"></i></label>
								
								<input type="radio" name="colorPick" id="radioInfo2" class="3895d2" value="#3895d2" />
								<label for="radioInfo2" class="radInfo2"> <i class="fa fa-square-o"></i></label>

								<input type="radio" name="colorPick" id="radioSuccess1" class="77c123" value="#77c123" />
								<label for="radioSuccess1" class="radSuccess"> <i class="fa fa-square-o"></i></label>
								
								<input type="radio" name="colorPick" id="radioSuccess2" class="6aab1f" value="#6aab1f" />
								<label for="radioSuccess2" class="radSuccess2"> <i class="fa fa-square-o"></i></label>

								<input type="radio" name="colorPick" id="radioWarning1" class="e5ad12" value="#e5ad12" />
								<label for="radioWarning1" class="radWarning"> <i class="fa fa-square-o"></i></label>
								
								<input type="radio" name="colorPick" id="radioWarning2" class="cd9b10" value="#cd9b10" />
								<label for="radioWarning2" class="radWarning2"> <i class="fa fa-square-o"></i></label>

								<input type="radio" name="colorPick" id="radioDanger1" class="d64e18" value="#d64e18" />
								<label for="radioDanger1" class="radDanger"> <i class="fa fa-square-o"></i></label>
								
								<input type="radio" name="colorPick" id="radioDanger2" class="a83d13" value="#a83d13" />
								<label for="radioDanger2" class="radDanger2"> <i class="fa fa-square-o"></i></label>
								<span class="help-block"><?php echo $selectColorHelp; ?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="eventDesc"><?php echo $eventDescField; ?></label>
						<textarea class="form-control" name="eventDesc" rows="4"><?php echo isset($_POST['eventDesc']) ? $_POST['eventDesc'] : ''; ?></textarea>
						<span class="help-block"><?php echo $eventDescHelp; ?></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="input" name="submit" value="newEvent" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveDateBtn; ?></button>
					<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
				</div>
			</form>

		</div>
	</div>
</div>