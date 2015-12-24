<div class="container">
	
	<div class="page-header"><h1>Venues</h1></div>
	<p>Find venues where we host dance classes and social events below.</p>
	
	<?php
	if(isset($_GET['_escaped_fragment_'])){
		$escaped_fragment = $_GET['_escaped_fragment_'];
		function make_path($v) {
			return str_replace(" ", "-", strtolower($v));
		};
		$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );
		$key_param='&key=AIzaSyAnZG8goK-RU8FGq6MCS31bjgYzi5YF_rQ';
		function print_venue($g) {
			print('<br/><h2 id="/venue/'.$g['id'].'">'.$g['name'].'</h2>');
			print('<h3>'.$g['area'].' Area</h3>');
			print('<p>'.$g['address'].'</p>');
			if( array_key_exists('timetable', $g) ) {
				$path = make_path($g['name']);
				print('<p><a href="classes#!/by-venue/at-'.$path.'">Class Timetable</a></p>');
			}
			print('<p><a href="'.$g['svUrl'].'" target="_blank">Get Directions</a></p>');
			$p = $g['position'];
			print('<img src="//maps.googleapis.com/maps/api/staticmap?zoom=15&size=600x200&maptype=roadmap&sensor=false&markers='.$p['latitude'].','.$p['longitude'].$key_param.'">');
			print('<br/><br/><hr/>');
		};

		$areas = [];
		$venues = [];

		foreach ($venues_data as $g) {
			if( $g['enabled'] == FALSE )
				continue;

			$areas[$g['area']] = true;
			$venues[$g['name']] = true;

			$area_path = '/by-area/in-'.make_path($g['area']);
			$venue_path = '/by-venue/at-'.make_path($g['name']);
			if( strcmp( $escaped_fragment, $area_path) == 0 || strcmp( $escaped_fragment, $venue_path) == 0 ) {
				print_venue($g);
			} 
			
		}

		if( $escaped_fragment == '' ) {
			print('<a class="btn btn-primary" type="button" href="#!/by-area">By Area</a> ');
			print('<a class="btn btn-primary" type="button" href="#!/by-venue">By Venue</a> ');
			print('<br/>');
			$markers = '';
			$sep = '';
			foreach ($venues_data as $g) {
				if( $g['enabled'] == FALSE )
					continue;
				$p = $g['position'];
				$markers = $markers . $sep . $p['latitude'].','.$p['longitude'];
				$sep = '|';
			}
			print('<img src="//maps.googleapis.com/maps/api/staticmap?size=600x600&maptype=roadmap&sensor=false&markers='.$markers.$key_param.'">'); 
		}
		if( $escaped_fragment == '/by-area') {
				foreach(array_keys($areas) as $a) 
					print('<a class="btn btn-primary" type="button" href="#!/by-area/in-'.make_path($a).'">'.$a.'</a> ');
		}
		if( $escaped_fragment == '/by-venue') {
				foreach(array_keys($venues) as $v) 
					print('<a class="btn btn-primary" type="button" href="#!/by-venue/at-'.make_path($v).'">'.$v.'</a> ');	
		}
		
	} else {
		include( 'venue_controller.php' );
	}
	?>

</div>

