<?php
	include 'includes/navigation.php';
	$dataTables = 'true';
	$jsFile = 'categories';
	
	// Delete Category
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteCat') {
		$catId = $mysqli->real_escape_string($_POST['catId']);
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId) {
			// Delete the Category
			$stmt = $mysqli->prepare("DELETE FROM categories WHERE catId = ?");
			$stmt->bind_param('s', $catId);
			$stmt->execute();
			$stmt->close();

			// Delete all related Tasks
			$stmt = $mysqli->prepare("DELETE FROM tasks WHERE catId = ?");
			$stmt->bind_param('s', $catId);
			$stmt->execute();
			$stmt->close();
			
			// Delete all related Tasks Notes
			$stmt = $mysqli->prepare("DELETE FROM tasknotes WHERE catId = ?");
			$stmt->bind_param('s', $catId);
			$stmt->execute();
			$msgBox = alertBox($catDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($catDeleteErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Categories Data
	$qry = "SELECT * FROM categories WHERE userId = ".$userId;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top">
			<?php echo $catListNavLink; ?>
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
						<th><?php echo $taskTitleTH; ?></th>
						<th><?php echo $descTH; ?></th>
						<th><?php echo $dateCreatedTH; ?></th>
						<th><?php echo $statusTH; ?></th>
						<th><?php echo $tasksNavLink; ?></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
						while ($row = mysqli_fetch_assoc($res)) {
						
						// Set the Status
						if ($row['isActive'] == '1') { $theStatus = '<span class="text-success">'.$activeText.'</span>'; } else { $theStatus = '<strong class="text-danger">'.$inactiveText.'</strong>'; }
						
						// Get Total Tasks for the Category
						$qtasks = "SELECT 'X' FROM tasks WHERE catId = ".$row['catId'];
						$taskCheck = mysqli_query($mysqli, $qtasks) or die('-2'.mysqli_error());
						$totCount = mysqli_num_rows($taskCheck);
					?>
						<tr>
							<td><a href="index.php?page=viewCategory&catId=<?php echo $row['catId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewCatTooltip; ?>"><?php echo clean($row['catName']); ?></a></td>
							<td><?php echo ellipsis($row['catDesc'],30); ?></td>
							<td><?php echo dateFormat($row['catDate']); ?></td>
							<td><?php echo $theStatus; ?></td>
							<td><?php echo $totCount; ?></td>
							<td>
								<a href="index.php?page=editCategory&catId=<?php echo $row['catId']; ?>" class="info"><i class="fa fa-pencil warning" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i></a>
								<a data-toggle="modal" href="#deleteCat<?php echo $row['catId']; ?>" class="danger"><i class="fa fa-times-circle danger" data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>"></i></a>
							</td>
						</tr>

						<div class="modal fade" id="deleteCat<?php echo $row['catId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCat<?php echo $row['catId']; ?>" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="" method="post">
										<div class="modal-body">
											<p class="lead mb-0">
												<?php echo $deleteCatQuip1; ?> <strong>"<?php echo clean($row['catName']); ?>"</strong>?</p>
												<p class="mt-0"><small><strong class="text-danger"><?php echo $deleteCatQuip2; ?></strong></small></p>
											</p>
										</div>
										<div class="modal-footer">
											<input name="catId" type="hidden" value="<?php echo $row['catId']; ?>" />
											<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
											<button type="input" name="submit" value="deleteCat" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
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