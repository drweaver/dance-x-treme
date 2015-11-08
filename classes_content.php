<!-- classes -->
<div class="container">
	
	<div class="page-header">
	    <h1>Classes</h1>
	</div>
	
	<div class="row">
		<div class="col-lg-6 col-md-4">
			<p>Dance classes for adults of all ages and abilities, couples and singles. We teach Ballroom and Latin with a great selection of music. 
			Our classes are held at safe, superb facilities at all our <a href="/venues">venues</a>. <a href="/contact">Private lessons</a> are also available.</p>
		</div>	
	
		<div class="col-lg-6 col-md-8">
		
			<dl class="dl-horizontal">
			  <dt>Do I need to book?</dt>
			  <dd>No, just turn up at a class at the appropriate time</dd>
			  <dt>Is there a booking fee?</dt>
			  <dd>No</dd>
			  <dt>How do I pay?</dt>
			  <dd>Just pay for individual classes as and when you attend</dd>
			  <dt>Can I attend multiple?</dt>
			  <dd>Yes, absolutely</dd>
			  <dt>What shall I wear?</dt>
			  <dd>Comfortable shoes and casual clothing</dd>
			  <dt>Do you teach children?</dt>
			  <dd>No, Adults only</dd>
			  <dt>I have more questions</dt>
			  <dd>Don't hestitate to <a href="/contact">contact us</a></dd>
			</dl>
		</div>
	</div>
	
	<hr/>

	<?php 
	if(isset($_GET['_escaped_fragment_'])){
		$escaped_fragment = $_GET['_escaped_fragment_'];
		if( $escaped_fragment == '' ) {
		?>
		<a class="btn btn-primary" type="button" href="#!/by-area" style="margin: 4px;" role="button">By Location</a>
		<a class="btn btn-primary" type="button" href="#!/by-day"  style="margin: 4px;" role="button">By Day</a>
		<a class="btn btn-primary" type="button" href="#!/by-venue" style="margin: 4px;" role="button">By Venue</a> 
		<br>
		<?php
		}
		$print_day = function($d) {
			print('<br/><h3>'.$d['day'].'s</h3>');
			print('<table class="class-timetable">');
			print('<tr><th>Time</th><th>Duration</th><th>Style</th><th>Level</th><th>Price</th></tr>');
			$i=0;
			foreach ($d['time'] as $t) {
				$style = '';
				$i = $i+1;
				if( $i % 2 == 0 ) $style = 'alt';
			    print('<tr class="'.$style.'"><td>'.$t['startTime'].'</td>');
				print('<td>'.$t['duration'].' mins</td>');
				print('<td>'.$t['style'].'</td>');
				print('<td>'.$t['level'].'</td>');
				print('<td>&pound;'.$t['price'].'</td></tr>');
			}
			print('</table>');
		};
		
		$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );
		
		$areas = [];
		$venues = [];
		$days = [];
		
		$make_path = function($v) {
			return str_replace(" ", "-", strtolower($v));
		};
		
		foreach ($venues_data as $g) {
			if( $g['enabled'] == FALSE )
				continue;
			if( !array_key_exists('timetable', $g) )
				continue;
				
			$areas[$g['area']] = true;
			$venues[$g['name']] = true;
			foreach( $g['timetable'] as $day) {
				$days[$day['day'].'s'] = true;
			}
				
			$area_path = '/by-area/in-'.$make_path($g['area']);
			$venue_path = '/by-venue/at-'.$make_path($g['name']);
			if( strcmp( $escaped_fragment, $area_path) == 0 || strcmp( $escaped_fragment, $venue_path) == 0 ) {
				print('<br/>'.$g['name'].'</h2><h3>'.$g['area'].' Area</h3>');
				foreach( $g['timetable'] as $day)
					print_day($day);
				print('<p>'.$g['address'].'</p><p><a href="venues#/venue/'.$g['id'].'">View Map</a></p>');
			} else {
				foreach( $g['timetable'] as $day) {
					$day_path = '/by-day/on-'.$make_path($day['day'].'s');
					if( strcmp( $escaped_fragment, $day_path ) == 0 ) {
						print('<br/>'.$g['name'].'</h2><h3>'.$g['area'].' Area</h3>');
						print_day($day);
						print('<p>'.$g['address'].'</p><p><a href="venues#/venue/'.$g['id'].'">View Map</a></p>');
					}
				}
			}
			
		}

		
		switch($escaped_fragment) {
			case '/by-area':
				foreach(array_keys($areas) as $a) print('<a class="btn btn-primary" type="button" href="#!/by-area/in-'.$make_path($a).'">'.$a.'</a> ');
				break;
			case '/by-venue': 
				foreach(array_keys($venues) as $v) print('<a class="btn btn-primary" type="button" href="#!/by-venue/at-'.$make_path($v).'">'.$v.'</a> ');	
				break;
			case '/by-day': 
				foreach(array_keys($days) as $d) print('<a class="btn btn-primary" type="button" href="#!/by-day/on-'.$make_path($d).'">'.$d.'</a> ');	
				break;
		}
		
	} else {
	?>

	<div ng-controller="ClassController">
		<div class="alert alert-info" ng-if="loading"><div class="loader" >Loading...</div>Loading class timetables, dance shoes, good posture, please wait...</div>
		<div class="jumbotron ng-cloak" ng-if="!loading">
			<form class="form-inline">
				<div class="row">
					<div class="col-sm-1">
						<h5 >Class Filter:</h5> 
					</div>	
					<div class="col-sm-11">
						<a class="btn btn-primary" type="button" href="#!/by-area" ng-class="typeButtonClass('by-area')" style="margin: 4px;" role="button">By Location</a>
						<a class="btn btn-primary" type="button" href="#!/by-day"  ng-class="typeButtonClass('by-day')"style="margin: 4px;" role="button">By Day</a>
						<a class="btn btn-primary" type="button" href="#!/by-venue" ng-class="typeButtonClass('by-venue')"style="margin: 4px;" role="button">By Venue</a> 
						<br/>
						<a class="btn ng-cloak btn-sm album-animate" ng-if="query.type == 'by-area'" ng-class="data.btnClass" type="button" ng-href="#!/by-area/{{data.index}}" ng-repeat="data in byArea | orderBy:'+index'" style="margin: 4px;">{{data.name}}</a>
						<a class="btn ng-cloak btn-sm album-animate" ng-if="query.type == 'by-day'" ng-class="data.btnClass" type="button" ng-href="#!/by-day/{{data.index}}" ng-repeat="data in byDay | orderBy:dayIndex" style="margin: 4px;">{{data.name}}</a>
						<a class="btn ng-cloak btn-sm album-animate" ng-if="query.type == 'by-venue'" ng-class="data.btnClass" type="button" ng-href="#!/by-venue/{{data.index}}" ng-repeat="data in byVenue | orderBy:'+index'" style="margin: 4px;">{{data.name}}</a>

					</div>
				</div>
			</form>
		</div>
		
		<div ng-repeat="data in dataArray | filter:query:false | orderBy:order" class="album-animate">
			<h1 class="ng-cloak">{{data.name}}</h1>
			<div ng-repeat="venue in data.venues | orderBy:'+name'" class="panel panel-default ng-cloak">
				<div class="panel-heading">
					<h3 class="panel-title">{{venue.nickname || venue.name}}</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<!-- timetable column -->
						<div class="col-xs-12 col-sm-8">
							<div ng-repeat="day in venue.timetable">
								<h4 class="ng-cloak">{{day.day}}</h4>
									<table class="table table-striped table-hover small">
										<thead><tr><th>Time</th><th>Duration</th><th>Style</th><th>Level</th><th>Price</th></tr></thead>
										<tbody>
											<tr ng-repeat="time in day.time"><td>{{time.startTime}}</td>
												<td>{{time.duration}} mins</td>
												<td>{{time.style}}</td>
												<td>{{time.level}}</td>
												<td>&pound;{{time.price}}</td>
											</tr>
										</tbody>
									</table>
									<div ng-init="validClosures = (venue.closures | closureFilter:day.day | orderBy:getTime)">
										<div class="alert alert-danger" role="alert" ng-if="validClosures.length" >
											<span ng-repeat="closure in validClosures">
												<span ng-if="$first">{{day.day}} classes at {{venue.nickname || venue.name}} will be closed on </span>
												<span ng-if="$last && !$first"> &amp; </span><span ng-if="!$last && !$first">, </span>
												{{closure | date:'d-MMM-yyyy'}}</span>
										</div>
									</div>
										
							</div>
						</div>
						<!-- address column -->
						<div class="col-xs-6 col-sm-4">
							<address>
								<strong>{{venue.name}}</strong><span ng-repeat="line in (venue.address | splitCommas ) track by $index"><br/>{{line}}</span></address>
								<a ng-href="/venues#/venue/{{venue.id}}">View Map</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	?>
	
	
<div class="panel-group ng-cloak" id="accordion" role="tablist" aria-multiselectable="true">

</div>
<noscript>
<?php 
$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );
function print_day($d) {
	print('<br/><h3>'.$d['day'].'s</h3>');
	print('<table class="class-timetable">');
	print('<tr><th>Time</th><th>Duration</th><th>Style</th><th>Level</th><th>Price</th></tr>');
	$i=0;
	foreach ($d['time'] as $t) {
		$style = '';
		$i = $i+1;
		if( $i % 2 == 0 ) $style = 'alt';
	    print('<tr class="'.$style.'"><td>'.$t['startTime'].'</td>');
		print('<td>'.$t['duration'].' mins</td>');
		print('<td>'.$t['style'].'</td>');
		print('<td>'.$t['level'].'</td>');
		print('<td>&pound;'.$t['price'].'</td></tr>');
	}
	print('</table>');
}
		
foreach ($venues_data as $g) {
	if( $g['enabled'] == FALSE )
		continue;
	if( !array_key_exists('timetable', $g) )
		continue;
	print('<br/><h2 id="/class/'.$g['id'].'">'.$g['name'].'</h2>');
	print('<h3>'.$g['area'].' Area</h3>');
	foreach( $g['timetable'] as $day)
		print_day($day);
	if( array_key_exists('closures', $g) and count($g['closures']) > 0) {
		print('<p><b>Closure Dates: </b>');
		$c = '';
		$sep = '';
		foreach($g['closures'] as $closed ) {
			$c = $c . $sep . $closed;
			$sep = ', ';
		}
		print($c.'</p>');
	}
	print('<p>'.$g['address'].'</p>');
	print('<p><a href="venues#/venue/'.$g['id'].'">View Map</a></p>');
}
?>
</noscript>

</div>
