<?php
	$f1 = "SELECT
				taskId,
				taskTitle,
				lastUpdated,
				UNIX_TIMESTAMP(lastUpdated) AS orderDate
			FROM
				tasks
			WHERE
				userId = ".$userId." AND
				lastUpdated != '0000-00-00 00:00:00'
			ORDER BY
				orderDate DESC
			LIMIT 5";
	$fr1 = mysqli_query($mysqli, $f1) or die('-f1'.mysqli_error());
	
	$f2 = "SELECT
				eventId,
				eventTitle,
				lastUpdated,
				UNIX_TIMESTAMP(lastUpdated) AS orderDate
			FROM
				events
			WHERE
				userId = ".$userId." AND
				lastUpdated != '0000-00-00 00:00:00'
			ORDER BY
				orderDate DESC
			LIMIT 5";
	$fr2 = mysqli_query($mysqli, $f2) or die('-f2'.mysqli_error());
	
	$f3 = "SELECT
				catId,
				catName,
				UNIX_TIMESTAMP(catDate) AS orderDate
			FROM
				categories
			WHERE
				userId = ".$userId." AND
				isActive = 1
			ORDER BY
				orderDate DESC";
	$fr3 = mysqli_query($mysqli, $f3) or die('-f3'.mysqli_error());
?>	
	<footer class="footer footer-col clearfix">
		<?php if ($set['enableWeather'] == '1') { ?>
			<div class="footer-content-1 footer-weather">
				<div class="inner-content no-padding pb-10">
					<div id="weather"></div>
				</div>
			</div>

			<hr class="divider">
		<?php } ?>
		
		<?php if ($set['enableCalendar'] == '1') { ?>
			<div class="footer-content-2">
				<div class="inner-content">
					<div class="calendar calendar-first" id="calendar_first">
						<div class="calendar_header">
							<button class="switch-month switch-left"> <i class="fa fa-chevron-left"></i></button>
							 <h2></h2>
							<button class="switch-month switch-right"> <i class="fa fa-chevron-right"></i></button>
						</div>
						<div class="calendar_weekdays"></div>
						<div class="calendar_content"></div>
					</div>				
				</div>
			</div>

			<hr class="divider">
		<?php } ?>
		
		<div class="footer-content-3">
			<div class="inner-content">
				<h5><?php echo $recentActivityTitle; ?></h5>
				<ul class="article-list">
					<?php
						while ($frow1 = mysqli_fetch_assoc($fr1)) {
							echo '<li class="task"><a href="index.php?page=viewTask&taskId='.$frow1['taskId'].'">'.clean($frow1['taskTitle']).'</a></li>';
						}
						while ($frow2 = mysqli_fetch_assoc($fr2)) {
							echo '<li class="date">'.clean($frow2['eventTitle']).'</li>';
						}
					?>
				</ul>
			</div>
		</div>

		<hr class="divider">
		
		<div class="footer-content-4">
			<div class="inner-content">
				<h5><?php echo $activeCategoriesTitle; ?></h5>
				<ul class="article-list">
					<?php while ($frow3 = mysqli_fetch_assoc($fr3)) { ?>
						<li class="category"><a href="index.php?page=viewCategory&catId=<?php echo $frow3['catId']; ?>"><?php echo clean($frow3['catName']); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<hr class="divider">

		<div class="inner-content copyright">
			<p class="text-center"><i class="fa fa-copyright"></i> <?php echo $copyrightText.' '.date('Y').' '.clean($set['siteName']).' '.$headerIncTitle; ?></p>
		</div>
	</footer>

	<div class="faux-col faux-nav-col"></div>
	<div class="faux-col faux-logo-col"></div>
	<div class="faux-col faux-content-col"></div>
	<div class="faux-col faux-footer-col"></div>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/winfix.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/simpleWeather.js"></script>
	<script type="text/javascript" src="js/footerCal.js"></script>
	<script type="text/javascript" src="js/datetimepicker.js"></script>
	<script type="text/javascript" src="js/includes/navigation.js"></script>
	<?php if (isset($dataTables)) {	echo '<script type="text/javascript" src="js/dataTables.js"></script>'; } ?>
	<?php if (isset($fullcalendar)) { echo '<script type="text/javascript" src="js/fullcalendar.js"></script>'; } ?>
	<?php if (isset($jsFile)) { echo '<script type="text/javascript" src="js/includes/'.$jsFile.'.js"></script>'; } ?>
	<?php if (isset($calinclude)) { include 'includes/calendar.php'; } ?>
	<script type="text/javascript">
		$(document).ready(function () {
			/** ******************************
			 * simpleWeather v3.0.2
			 * http://simpleweatherjs.com
			 ****************************** **/
			$.simpleWeather({
				location: '<?php echo $weatherLoc; ?>',
				woeid: '',
				unit: 'f',
				success: function(weather) {
					html = '<h2 class="mt-5"><i class="icon-'+weather.code+'"></i> '+weather.temp+'&deg;'+weather.units.temp+'</h2>';
					html += '<ul><li>'+weather.city+', '+weather.region+'</li></ul>';
					$("#weather").html(html);
				},
				error: function(error) {
					$("#weather").html('<p>'+error+'</p>');
				}
			});
		});
	</script>
</body>
</html>