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
								<a ng-href="/venues#!/by-venue/{{venue.path}}">View Map</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>