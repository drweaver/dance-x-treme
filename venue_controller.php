
<div class="container" ng-controller="venueController">
    <div id="venue-map-loading" class="hidden"><img src="img/spinner.gif"/> Loading...</div>
    <div id="venue-map">
    	<span ng-if="!loading">Zoom to:
    		<a class="btn ng-cloak btn-sm btn-warning" type="button" ng-repeat="(path, area) in aMap" ng-href="#!/by-area/{{path}}"  style="margin: 4px;">{{area.name}}</a>
    	</span>
    	<ui-gmap-google-map center='map.center' zoom='map.zoom' options='options' control="mapControl">
    		<ui-gmap-markers models="venues" idkey="'path'" coords="'position'" icon="'icon'" click="markerClick" control="markerControl"></ui-gmap-markers>
    	</ui-gmap-google-map>
    </div>
</div>