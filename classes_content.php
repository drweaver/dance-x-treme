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

	<div class="alert alert-warning" role="alert">
  		Class timetables below are now <b>updated for 2016</b>.  Hope you had a good Christmas and see you in January!
	</div>

	<?php 
	if(isset($_GET['_escaped_fragment_'])){
		$escaped_fragment = $_GET['_escaped_fragment_'];
		if( $escaped_fragment == '' ) {
			print('<a class="btn btn-primary" type="button" href="#!/by-area" style="margin: 4px;" role="button">By Location</a>');
			print('<a class="btn btn-primary" type="button" href="#!/by-day"  style="margin: 4px;" role="button">By Day</a>');
			print('<a class="btn btn-primary" type="button" href="#!/by-venue" style="margin: 4px;" role="button">By Venue</a>');
		}
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
		};
		
		$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );
		
		$areas = [];
		$venues = [];
		$days = [];
		
		function make_path($v) {
			return str_replace(" ", "-", str_replace("'", "", strtolower($v)));
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
				
			$area_path = '/by-area/in-'.make_path($g['area']);
			$venue_path = '/by-venue/at-'.make_path($g['name']);
			if( strcmp( $escaped_fragment, $area_path) == 0 || strcmp( $escaped_fragment, $venue_path) == 0 ) {
				print('<br/>'.$g['name'].'</h2><h3>'.$g['area'].' Area</h3>');
				foreach( $g['timetable'] as $day)
					print_day($day);
				print('<p>'.$g['address'].'</p><p><a href="venues#!'.$venue_path.'">View Map</a></p>');
			} else {
				foreach( $g['timetable'] as $day) {
					$day_path = '/by-day/on-'.make_path($day['day'].'s');
					if( strcmp( $escaped_fragment, $day_path ) == 0 ) {
						print('<br/>'.$g['name'].'</h2><h3>'.$g['area'].' Area</h3>');
						print_day($day);
						print('<p>'.$g['address'].'</p><p><a href="venues#!'.$venue_path.'">View Map</a></p>');
					}
				}
			}
			
		}

		
		switch($escaped_fragment) {
			case '/by-area':
				foreach(array_keys($areas) as $a) print('<a class="btn btn-primary" type="button" href="#!/by-area/in-'.make_path($a).'">'.$a.'</a> ');
				break;
			case '/by-venue': 
				foreach(array_keys($venues) as $v) print('<a class="btn btn-primary" type="button" href="#!/by-venue/at-'.make_path($v).'">'.$v.'</a> ');	
				break;
			case '/by-day': 
				foreach(array_keys($days) as $d) print('<a class="btn btn-primary" type="button" href="#!/by-day/on-'.make_path($d).'">'.$d.'</a> ');	
				break;
		}
		
	} else {
		include('class_controller.php');
	}
	?>
	
	
	<div class="panel-group ng-cloak" id="accordion" role="tablist" aria-multiselectable="true">
	
	</div>

</div>
