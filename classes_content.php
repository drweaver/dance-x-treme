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
	<div ng-controller="ClassController">
		<div class="jumbotron">
			<form class="form-inline" id="a">
				Filter: <input ng-model="query.index" class="form-control form-inline">
				<button class="btn ng-cloak btn-default" type="button" ng-click="searchByType('by-area')" style="margin: 4px;">All</button>
				<button class="btn ng-cloak" ng-class="data.btnClass" type="button" ng-click="search(data.index)" ng-repeat="data in dataArray | orderBy:'+index'" style="margin: 4px;">{{data.index}}</button>
			</form>
		</div>
		<div ng-repeat="data in dataArray | filter:query:false | orderBy:'+index'" >
			<h1 class="ng-cloak">{{data.index}}</h1>
			<div ng-repeat="venue in data.venues | orderBy:'+name'" class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title ng-cloak">{{venue.name}}</h3>
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
											<tr class="ng-cloak" ng-repeat="time in day.time"><td>{{time.startTime}}</td>
												<td>{{time.duration}} mins</td>
												<td>{{time.style}}</td>
												<td>{{time.level}}</td>
												<td>&pound;{{time.price}}</td>
											</tr>
										</tbody>
									</table>
							
							</div>
						</div>
						<!-- address column -->
						<div class="col-xs-6 col-sm-4">
							<address class="ng-cloak">
								<strong>{{venue.name}}</strong><span ng-repeat="line in (venue.address | splitCommas ) track by $index"><br/>{{line}}</span></address>
								<a href="/venues#/venue/'.$venue['id'].'">View Map</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
