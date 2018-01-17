<?php
	include 'includes/navigation.php';
	$catId = $_GET['catId'];
	$jsFile = 'editCategory';
	
	// Edit Category
	if (isset($_POST['submit']) && $_POST['submit'] == 'editCat') {
		if($_POST['catName'] == "") {
            $msgBox = alertBox($catNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['catDesc'] == "") {
            $msgBox = alertBox($catDescReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['catDate'] == "") {
            $msgBox = alertBox($catDateReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$catName = $mysqli->real_escape_string($_POST['catName']);
			$catDesc = $_POST['catDesc'];
			$isActive = $mysqli->real_escape_string($_POST['isActive']);
			$catDate = $mysqli->real_escape_string($_POST['catDate']);
			$lastUpdated = date("Y-m-d H:i:s");

			$stmt = $mysqli->prepare("UPDATE
										categories
									SET
										catName = ?,
										catDesc = ?,
										isActive = ?,
										catDate = ?,
										lastUpdated = ?
									WHERE
										catId = ?"
			);
			$stmt->bind_param('ssssss',
								$catName,
								$catDesc,
								$isActive,
								$catDate,
								$lastUpdated,
								$catId
			);
			$stmt->execute();
			$msgBox = alertBox($catUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
    }
	
	// Category Data
	$qry = "SELECT
				*
			FROM
				categories
			WHERE
				catId = ".$catId;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['isActive'] == '1') { $theStatus = 'selected'; } else { $theStatus = ''; }
	
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
				<?php echo $editCatTooltip; ?>: <?php echo clean($row['catName']); ?>
				<span class="pull-right">
					<a href="index.php?page=viewCategory&catId=<?php echo $catId; ?>" class="btn btn-warning btn-sm"><i class="fa fa-tag" data-toggle="tooltip" data-placement="left" title="<?php echo $viewCatTooltip; ?>"></i></a>
				</span>
			</h3>
			<hr />
			
			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
				<div class="panel-body">
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4><?php echo $catDetailsTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $catNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="catName" value="<?php echo clean($row['catName']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $descText; ?></label>
						<div class="col-sm-8">
							<textarea class="form-control" required="" name="catDesc" rows="2"><?php echo clean($row['catDesc']); ?></textarea>
						</div>
					</div>
					
					<div class="form-group header bgcolor-default mt-20">
						<div class="col-md-12">
							 <h4><?php echo $catSettingsTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $statusField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="isActive">
								<option value="0"><?php echo $inactiveText; ?></option>
								<option value="1" <?php echo $theStatus; ?>><?php echo $activeText; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $dateCreatedTH; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="catDate" id="editCatDate" value="<?php echo dbDateFormat($row['catDate']); ?>" />
						</div>
					</div>
				</div>
				<hr />
				<button type="input" name="submit" value="editCat" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
			</form>
			
		</div>
	</div>
<?php } ?>