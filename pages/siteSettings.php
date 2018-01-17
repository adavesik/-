<?php
	include 'includes/navigation.php';
	$jsFile = 'siteSettings';
	
	// Edit User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'editSettings') {
		if($_POST['installUrl'] == "") {
            $msgBox = alertBox($installURLReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['siteName'] == "") {
            $msgBox = alertBox($siteNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['siteEmail'] == "") {
            $msgBox = alertBox($siteEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$installUrl = $mysqli->real_escape_string($_POST['installUrl']);
			$localization = $mysqli->real_escape_string($_POST['localization']);
			$siteName = $mysqli->real_escape_string($_POST['siteName']);
			$siteEmail = $mysqli->real_escape_string($_POST['siteEmail']);
			$allowRegistrations = $mysqli->real_escape_string($_POST['allowRegistrations']);
			$enableWeather = $mysqli->real_escape_string($_POST['enableWeather']);
			$enableCalendar = $mysqli->real_escape_string($_POST['enableCalendar']);

			$stmt = $mysqli->prepare("UPDATE
										sitesettings
									SET
										installUrl = ?,
										localization = ?,
										siteName = ?,
										siteEmail = ?,
										allowRegistrations = ?,
										enableWeather = ?,
										enableCalendar = ?"
			);
			$stmt->bind_param('sssssss',
								$installUrl,
								$localization,
								$siteName,
								$siteEmail,
								$allowRegistrations,
								$enableWeather,
								$enableCalendar
			);
			$stmt->execute();
			$msgBox = alertBox($siteSettingsUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
	}
	
	// Settings Data
	$qry = "SELECT * FROM sitesettings";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);
	
	if ($row['allowRegistrations'] == '1') { $allowRegistrations = 'selected'; } else { $allowRegistrations = ''; }
	if ($row['enableWeather'] == '1') {
		$enableWeather = 'selected';
		$set['enableWeather'] = '1';
	} else {
		$enableWeather = '';
		$set['enableWeather'] = '0';
	}
	if ($row['enableCalendar'] == '1') {
		$enableCalendar = 'selected';
		$set['enableCalendar'] = '1';
	} else {
		$enableCalendar = '';
		$set['enableCalendar'] = '0';
	}
	
	if ($row['localization'] == 'ar') { $ar = 'selected'; } else { $ar = ''; }
	if ($row['localization'] == 'bg') { $bg = 'selected'; } else { $bg = ''; }
	if ($row['localization'] == 'ce') { $ce = 'selected'; } else { $ce = ''; }
	if ($row['localization'] == 'cs') { $cs = 'selected'; } else { $cs = ''; }
	if ($row['localization'] == 'da') { $da = 'selected'; } else { $da = ''; }
	if ($row['localization'] == 'en') { $en = 'selected'; } else { $en = ''; }
	if ($row['localization'] == 'en-ca') { $en_ca = 'selected'; } else { $en_ca = ''; }
	if ($row['localization'] == 'en-gb') { $en_gb = 'selected'; } else { $en_gb = ''; }
	if ($row['localization'] == 'es') { $es = 'selected'; } else { $es = ''; }
	if ($row['localization'] == 'fr') { $fr = 'selected'; } else { $fr = ''; }
	if ($row['localization'] == 'ge') { $ge = 'selected'; } else { $ge = ''; }
	if ($row['localization'] == 'hr') { $hr = 'selected'; } else { $hr = ''; }
	if ($row['localization'] == 'hu') { $hu = 'selected'; } else { $hu = ''; }
	if ($row['localization'] == 'hy') { $hy = 'selected'; } else { $hy = ''; }
	if ($row['localization'] == 'id') { $id = 'selected'; } else { $id = ''; }
	if ($row['localization'] == 'it') { $it = 'selected'; } else { $it = ''; }
	if ($row['localization'] == 'ja') { $ja = 'selected'; } else { $ja = ''; }
	if ($row['localization'] == 'ko') { $ko = 'selected'; } else { $ko = ''; }
	if ($row['localization'] == 'nl') { $nl = 'selected'; } else { $nl = ''; }
	if ($row['localization'] == 'pt') { $pt = 'selected'; } else { $pt = ''; }
	if ($row['localization'] == 'ro') { $ro = 'selected'; } else { $ro = ''; }
	if ($row['localization'] == 'sv') { $sv = 'selected'; } else { $sv = ''; }
	if ($row['localization'] == 'th') { $th = 'selected'; } else { $th = ''; }
	if ($row['localization'] == 'vi') { $vi = 'selected'; } else { $vi = ''; }
	if ($row['localization'] == 'yue') { $yue = 'selected'; } else { $yue = ''; }

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
			<h3 class="font-weight-thin no-margin-top"><?php echo $siteSettingsNavLink; ?></h3>
			<hr />

			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
				<div class="panel-body">
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4><?php echo $globalSettingsTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $installURLField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="installUrl" value="<?php echo clean($row['installUrl']); ?>" />
							<span class="help-block"><?php echo $installURLHelp; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $siteNameField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="siteName" value="<?php echo clean($row['siteName']); ?>" />
							<span class="help-block"><?php echo $siteNameHelp; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $siteEmailField; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="siteEmail" value="<?php echo clean($row['siteEmail']); ?>" />
							<span class="help-block"><?php echo $siteEmailHelp; ?></span>
						</div>
					</div>
					
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4><?php echo $localTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $selectLangField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="localization">
								<option value="ar" <?php echo $ar; ?>><?php echo $optionArabic; ?> &mdash; ar.php</option>
								<option value="bg" <?php echo $bg; ?>><?php echo $optionBulgarian; ?> &mdash; bg.php</option>
								<option value="ce" <?php echo $ce; ?>><?php echo $optionChechen; ?> &mdash; ce.php</option>
								<option value="cs" <?php echo $cs; ?>><?php echo $optionCzech; ?> &mdash; cs.php</option>
								<option value="da" <?php echo $da; ?>><?php echo $optionDanish; ?> &mdash; da.php</option>
								<option value="en" <?php echo $en; ?>><?php echo $optionEnglish; ?> &mdash; en.php</option>
								<option value="en-ca" <?php echo $en_ca; ?>><?php echo $optionCanadianEnglish; ?> &mdash; en-ca.php</option>
								<option value="en-gb" <?php echo $en_gb; ?>><?php echo $optionBritishEnglish; ?> &mdash; en-gb.php</option>
								<option value="es" <?php echo $es; ?>><?php echo $optionEspanol; ?> &mdash; es.php</option>
								<option value="fr" <?php echo $fr; ?>><?php echo $optionFrench; ?> &mdash; fr.php</option>
								<option value="ge" <?php echo $ge; ?>><?php echo $optionGerman; ?> &mdash; ge.php</option>
								<option value="hr" <?php echo $hr; ?>><?php echo $optionCroatian; ?> &mdash; hr.php</option>
								<option value="hu" <?php echo $hu; ?>><?php echo $optionHungarian; ?> &mdash; hu.php</option>
								<option value="hy" <?php echo $hy; ?>><?php echo $optionArmenian; ?> &mdash; hy.php</option>
								<option value="id" <?php echo $id; ?>><?php echo $optionIndonesian; ?> &mdash; id.php</option>
								<option value="it" <?php echo $it; ?>><?php echo $optionItalian; ?> &mdash; it.php</option>
								<option value="ja" <?php echo $ja; ?>><?php echo $optionJapanese; ?> &mdash; ja.php</option>
								<option value="ko" <?php echo $ko; ?>><?php echo $optionKorean; ?> &mdash; ko.php</option>
								<option value="nl" <?php echo $nl; ?>><?php echo $optionDutch; ?> &mdash; nl.php</option>
								<option value="pt" <?php echo $pt; ?>><?php echo $optionPortuguese; ?> &mdash; pt.php</option>
								<option value="ro" <?php echo $ro; ?>><?php echo $optionRomanian; ?> &mdash; ro.php</option>
								<option value="sv" <?php echo $sv; ?>><?php echo $optionSwedish; ?> &mdash; sv.php</option>
								<option value="th" <?php echo $th; ?>><?php echo $optionThai; ?> &mdash; th.php</option>
								<option value="vi" <?php echo $vi; ?>><?php echo $optionVietnamese; ?> &mdash; vi.php</option>
								<option value="yue" <?php echo $yue; ?>><?php echo $optionCantonese; ?> &mdash; yue.php</option>
							</select>
						</div>
					</div>
					
					<div class="form-group header bgcolor-default mt-10">
						<div class="col-md-12">
							 <h4><?php echo $globalOptionsTitle; ?></h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $enableRegField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="allowRegistrations">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $allowRegistrations; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $enableRegHelp; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $enableWeatherField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="enableWeather">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $enableWeather; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $enableWeatherHelp; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $enableCalField; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="enableCalendar">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $enableCalendar; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $enableCalHelp; ?></span>
						</div>
					</div>

				</div>
				<hr />
				<button type="input" name="submit" value="editSettings" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
			</form>
			
		</div>
	</div>
<?php } ?>