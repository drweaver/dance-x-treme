<!-- classes -->
<div class="container">
	
<div class="page-header">
    <h1>Classes</h1>
</div>
<p>Dance classes for all ages and abilities, couples and singles. We teach Ballroom and Latin with a great selection of music. 
Our classes are held at safe, superb facilities at all our <a href="/venues">venues</a>. <a href="/contact">Private lessons</a> are also available.</p>

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
  <dt>I have more questions</dt>
  <dd>Don't hestitate to <a href="/contact">contact us</a></dd>
  
</dl>
<hr/>
<h2>Timetables <span class="text-info small">click to expand</span></h2>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php 

$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );

function print_heading($venue, $id) {
	print('<div class="panel-heading" role="tab" id="heading'.$id.'">');
    print('<h4 class="panel-title">');
    print('<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">');
    print('<span class="text-primary">'.$venue['name'].'</span>, <span class="text-info small">'.$venue['area'].'</span>');
    print('</a></h4></div>');
}

function print_timetable($venue, $timetable, $id, $day) {
	print('<div id="collapse'.$id.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$id.'">');
    print('<div class="panel-body">');
    print('<h4>'.$day.'</h4>');
    print('<div class=""><div class="row"><div class="col-xs-12 col-sm-8">');
	print('<table class="table table-striped table-hover small">');
	print('<thead><tr><th>Time</th><th>Duration</th><th>Style</th><th>Level</th><th>Price</th></tr></thead><tbody>');
	$i=0;
	foreach ($timetable as $t) {
		$style = '';
		$i = $i+1;
		if( $i % 2 == 0 ) $style = '';
	    print('<tr class="'.$style.'"><td>'.$t['startTime'].'</td>');
		print('<td>'.$t['duration'].' mins</td>');
		print('<td>'.$t['style'].'</td>');
		print('<td>'.$t['level'].'</td>');
		print('<td>&pound;'.$t['price'].'</td></tr>');
	}
	print('</tbody></table>');
	print('</div>');
	print('<div class="col-xs-6 col-sm-4"><address><strong>'.$venue['name'].'</strong><br/>'.str_replace(',', '<br/>', $venue['address']).'</address><a href="/venues#/venue/'.$venue['id'].'">View Map</a></div>');
	// row
	print('</div>');
	// container
	print('</div>');
	// collapse, panel-body
	print('</div></div>');
}
		
foreach( array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') as $day ) {
	$day_title = '<h3>'.$day.'s</h3>';
	foreach( $venues_data as $g ) {
		if( $g['enabled'] == FALSE )
			continue;
		if( !array_key_exists('timetable', $g) )
			continue;
		foreach( $g['timetable'] as $t_day ) {
			if( $t_day['day'] == $day ) {
				print $day_title;
				$day_title = '';
				print '<div class="panel panel-default">';
				$id = $g['id'].$day;
				print_heading($g, $id);
				print_timetable($g, $t_day['time'], $id, $day);
				print '</div>';
			}
		}
	}
}
?>

</div>
