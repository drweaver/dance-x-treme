<div class="container" ng-controller="venueController">
	
<div class="page-header">
<h1>Venues</h1>
</div>
<p>Find venues where we host dance classes and social events below.</p>
<div id="venue-map-loading" class="hidden"><img src="img/spinner.gif"/> Loading...</div>
<div id="venue-map">
	<span ng-if="!loading">Zoom to:
		<button class="btn ng-cloak btn-sm btn-warning" type="button" ng-repeat="(area, bounds) in aMap" ng-click="fitBounds(bounds)"  style="margin: 4px;">{{area}}</button>
	</span>
	<ui-gmap-google-map center='map.center' zoom='map.zoom' options='options' control="mapControl">
		<ui-gmap-markers models="venues" idkey="id" coords="'position'" icon="'icon'" click="markerClick" control="markerControl"></ui-gmap-markers>
	</ui-gmap-google-map>
<noscript>
<?php
$venues_data = json_decode( file_get_contents('data/dance_venues.txt'), true );

$key_param='&key=AIzaSyAnZG8goK-RU8FGq6MCS31bjgYzi5YF_rQ';

$markers = '';
$sep = '';
foreach ($venues_data as $g) {
	if( $g['enabled'] == FALSE )
		continue;
	$p = $g['position'];
	$markers = $markers . $sep . $p['lat'].','.$p['lng'];
	$sep = '|';
}
print('<img src="http://maps.googleapis.com/maps/api/staticmap?size=600x600&maptype=roadmap&sensor=false&markers='.$markers.$key_param.'">'); 

print('<br/><hr>');

foreach ($venues_data as $g) {
	if( $g['enabled'] == FALSE )
		continue;
	print('<br/><h2 id="/venue/'.$g['id'].'">'.$g['name'].'</h2>');
	print('<h3>'.$g['area'].' Area</h3>');
	print('<p>'.$g['address'].'</p>');
	if( array_key_exists('timetable', $g) )
		print('<p><a href="classes#/class/'.$g['id'].'">Class Timetable</a></p>');
	print('<p><a href="'.$g['svUrl'].'" target="_blank">Get Directions</a></p>');
	$p = $g['position'];
	print('<img src="http://maps.googleapis.com/maps/api/staticmap?zoom=15&size=600x200&maptype=roadmap&sensor=false&markers='.$p['lat'].','.$p['lng'].$key_param.'">');
	print('<br/><br/><hr/>');
}
?>
</noscript>
</div>
</div>
