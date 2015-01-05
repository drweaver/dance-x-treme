<div class="container">
	
	<div class="page-header"><h1>Gallery</h1></div>

	<p>View photos from our social dance events.  Click or search for albums using the form below.</p>
	<p ng-if="loading">Loading galleries, best dress, dance partner, please wait...</p>
	<div ng-controller="GalleryController">
		<div class="jumbotron ng-cloak">
			<form class="form-inline">
			Search: <input ng-model="query" class="form-control form-inline">
			<button class="btn btn-primary ng-cloak" type="button" ng-click="search(can)" ng-repeat="can in canned" style="margin: 4px;">{{can}}</button>
			</form>
		</div>
	
	  <div class="panel panel-default ng-cloak album-animate" ng-repeat="album in albums | filter:query" style="width: 190px; display:inline-block; margin:5px;">

	    <div class="panel-body">
	    	<a ng-href="{{album.url}}" target="_blank">
	      		<img ng-src="{{album.thumbnail}}" height="160" width="160">
	    	</a>
	      </div>
	      <div class="panel-footer">
	        <p class="small" style="min-height: 35px;" title="{{album.datePretty}}">{{album.name}}</p>
	        <p><a ng-href="{{album.url}}" target="_blank" class="btn btn-default" role="button">View Album &raquo;</a></p>
	      </div>
	    </div>
	  </div>
	</div>
	</div>
	
	<noscript>
	<?php
	
	$gallery_data = json_decode( file_get_contents('data/dance_galleries.txt'), true );
	
	foreach ($gallery_data as $g) {
	    
	    print('<table><tr><td>');
		print('<a href="'.$g['url'].'" target="_blank"><img height="160" width="160" src="'.$g['thumbnail'].'"></a>');
		print('</td></tr><tr><td style="text-align:center;">');
		print($g['name']);
		print('</td></tr><tr><td style="text-align:center;">');
		print($g['date']);
		print('</td></tr></table>');
	}
	
	?>
	</noscript>

</div>

