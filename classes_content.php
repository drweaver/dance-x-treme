<h1>Dance Classes</h1>
<p>
Dancing classes for all ages and abilities, couples and singles. We teach Ballroom and Latin with a 
great selection of music. Our classes are held at safe, superb facilities at all our 
<a href="venues">venues</a>. <a href="private-lessons">Private lessons</a>
 are also available.
</p>
<div id="classes-by-day-loading" class="hidden"><img src="img/spinner.gif"/> Loading...</div>
<div id="classes-by-day"></div>
<hr/>
<div id="class-details">
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