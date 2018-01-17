<?php
	// Get Events
	$query = "SELECT
				eventId,
				userId,
				startDate,
				DATE_FORMAT(startDate,'%Y-%m-%d') AS startsOnDate,
				DATE_FORMAT(startDate,'%m') AS startMonth,
				DATE_FORMAT(startDate,'%H:%i') AS startTime,
				DATE_FORMAT(startDate,'%H:%i') AS timeStart,
				DATE_FORMAT(startDate,'%l:%i %p') AS displaystart,
				DATE_FORMAT(startDate,'%M %d, %Y') AS displayDate,
				endDate,
				DATE_FORMAT(endDate,'%Y-%m-%d') AS endsOnDate,
				DATE_FORMAT(endDate,'%m') AS endMonth,
				DATE_FORMAT(endDate,'%H:%i') AS endTime,
				DATE_FORMAT(endDate,'%Y, %m, %d') AS dateEnd,
				DATE_FORMAT(endDate,'%l:%i %p') AS displayend,
				eventTitle,
				eventDesc,
				eventColor,
				isTask
			FROM
				events
			WHERE
				userId = ".$userId;
	$res = mysqli_query($mysqli, $query) or die('-1'.mysqli_error());
?>
<script type="text/javascript">
	$(function() {
		var date = new Date();
		var d = date.getDate(),
			m = date.getMonth(),
			y = date.getFullYear();
		$('#calendar').fullCalendar({
			header: {
				left: 'prevYear,prev,next,nextYear today',
				center: 'title',
				right: 'newEvent month,agendaWeek,agendaDay'
			},
			buttonText: {
				prev: "<span class='fa fa-angle-left'></span>",
				next: "<span class='fa fa-angle-right'></span>",
				prevYear: "<span class='fa fa-angle-double-left'></span>",
				nextYear: "<span class='fa fa-angle-double-right'></span>",
				today: '<?php echo $todayLink; ?>',
				newEvent: "<?php echo $newEventLink; ?>",
				month: '<?php echo $monthLink; ?>',
				week: '<?php echo $weekLink; ?>',
				day: '<?php echo $dayLink; ?>'
			},
			events: [
			<?php
				$delim = '';
				while($row = mysqli_fetch_assoc($res)) {

					// Months start at 0 - so subtract 1 month from the query dates
					$eventStartMnth = $row['startMonth'];
					$eventStartMonth = --$eventStartMnth;
					$eventDate = strtotime($row['startDate']);
					$eventDate = date('Y', $eventDate).', '.$eventStartMonth.', '.date('d, H, i', $eventDate);

					$eventEndMnth = $row['endMonth'];
					$eventEndMonth = --$eventEndMnth;
					$eventEnd = strtotime($row['endDate']);
					$eventEnd = date('Y', $eventEnd).', '.$eventEndMonth.', '.date('d, H, i', $eventEnd);

					// Check for an All Day event
					if ($row['timeStart'] != '00:00') { $allDay = 'allDay: false,'; } else { $allDay = 'allDay: true,'; }
					if ($row['startTime'] == '00:00') { $startTime = ''; } else { $startTime = $row['startTime']; }
					if ($row['endTime'] == '00:00') { $endTime = ''; } else { $endTime = $row['endTime']; }
					// Check for an End Date
					if ($row['dateEnd'] != '0000, 00, 00') { $endsOn = "end: new Date(".$eventEnd."),"; } else { $endsOn = ""; }
					// Set the Times to Display
					if ($row['displaystart'] != '12:00 AM') { $displaytime = $row['displaystart'].' &mdash; '.$row['displayend']; } else { $displaytime = $noTimesSet; }
					// Check if it is a Task
					if ($row['isTask'] == '1') { $isTask = '<small class="label label-info preview-label">Task</small>'; $setColor = '#469cd5'; } else { $isTask = ''; }

					echo $delim."{";
					echo "
							title: '".str_replace("\'","\\'", $row['eventTitle'])."',
							start: new Date(".$eventDate."),
							startsondate: '".$row['startsOnDate']."',
							endsondate: '".$row['endsOnDate']."',
							starttime: '".$startTime."',
							endtime: '".$endTime."',
							".$endsOn."
							".$allDay."
							color: '".$row['eventColor']."',
							desc: '".str_replace("\'","\\'", $row['eventDesc'])."',
							startson: '".$row['displayDate']."',
							displaytime: '".$displaytime."',
							colorinput: '".$row['eventColor']."',
							task: '".$isTask."',
							eventid: '".$row['eventId']."',
							uid: '".$row['userId']."'
						";
					echo "}";
					$delim = ',';
				}
			?>
			],
			// Show event details & options on event title click
			eventClick: function(calEvent, jsEvent, view) {

				// View Event Modal
				$('.viewEvent').modal('toggle');
				$('.event-title').show().html(calEvent.title);
				$('.event-desc').show().html(calEvent.task + ' <span class="label label-default preview-label">' + calEvent.displaytime + '</span><span class="pull-right"></span><br /><br />' + calEvent.desc.replace(/\r\n/g, "<br />"));
				$('.event-actions').show().html('<span class="pull-right"><a data-toggle="modal" data-dismiss="modal" href="#editEvent' + calEvent.eventid + '" class="btn btn-success btn-icon"><i class="fa fa-edit"></i> <?php echo $editEvent; ?></a> <a data-toggle="modal" data-dismiss="modal" href="#deleteEvent' + calEvent.eventid + '" class="btn btn-danger btn-icon"><i class="fa fa-trash-o"></i> <?php echo $deleteEvent; ?></a> <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $closeBtn; ?></button></span>');

				// Edit Event Modal
				$('.editEvent').attr('id', 'editEvent' + calEvent.eventid +'');
				$('.event-modal-title').show().html(calEvent.title);
				$('#editstartDate').val(calEvent.startsondate);
				$('#editeventTime').val(calEvent.starttime);
				$('#editendDate').val(calEvent.endsondate);
				$('#editendTime').val(calEvent.endtime);
				$('.titleField').val(calEvent.title);
				$('#eventColor').val(calEvent.colorinput);
				$('.descField').val(calEvent.desc);
				$('.event-id').val(calEvent.eventid);
				$('.user-id').val(calEvent.uid);

				// Delete Event Modal
				$('.deleteEvent').attr('id', 'deleteEvent' + calEvent.eventid +'');
				$('.uid').val(calEvent.uid);
			}
		});
	});
</script>