<div class="container">
	
<div class="page-header"><h1>Gallery</h1></div>
<!--	
<div id="dance-galleries-loading" class="hidden"><img src="img/spinner.gif"/> Loading...</div>
<div id="dance-galleries">
	<div class="jumbotron album-years"></div>
	<div class="albums"></div>
</div>
-->
<div ng-controller="GalleryController">
	<div class="jumbotron">
		<form class="form-inline">
		Search: <input ng-model="query" class="form-control form-inline">
		<button class="btn btn-primary" type="button" ng-click="search(can)" ng-repeat="can in canned" style="margin: 4px;">{{can}}</a>
		</form>
	</div>
	<!--
	<a target="_blank" href="{{album.url}}" ng-repeat="album in albums | filter:query">
						<img src="{{album.thumbnail}}" width="160" height="160" class="img-thumbnail">
					</a>
		-->		
  <div class="panel panel-default" ng-repeat="album in albums | filter:query" style="width: 190px; display:inline-block; margin:5px;">
  	<!--
  	<div class="panel-heading">
  	</div>
  	-->
    <div class="panel-body">
    	<a href="{{album.url}}" target="_blank">
      		<img src="{{album.thumbnail}}" height="160" width="160">
    	</a>
      </div>
      <div class="panel-footer">
        <p class="small" style="min-height: 35px;" title="{{album.datePretty}}">{{album.name}}</p>
        <!--<p class="small">{{album.datePretty}}</p>-->
        <p><a href="{{album.url}}" class="btn btn-default" role="button">View Album &raquo;</a></p>
      </div>
    </div>
  </div>
</div>
					
	<!--
	<table style="display:inline-table; width:194px;" ng-repeat="album in albums | filter:query">
		<tbody>
			<tr>
				<td align="center" style="height:194px;background:url(img/transparent_album_background.gif) no-repeat left">
					<a target="_blank" href="{{album.url}}">
						<img src="{{album.thumbnail}}" style="margin:1px 0 0 4px">
					</a>
				</td>
			</tr>
			<tr>
				<td style="width: 160px;text-align:center;font-family:arial,sans-serif;font-size:11px">
					<span>{{album.name}}<span style="display:none;">{{album.latest}}</span></span>
				</td>
			</tr>
			<tr>
				<td style="width: 160px;text-align:center;font-family:arial,sans-serif;font-size:11px">
					<span>{{album.datePretty}}<span></span></span>
				</td>
			</tr>
		</tbody>
	</table>
	-->
</div>

</div>

<noscript>
<?php

$gallery_data = json_decode( file_get_contents('data/dance_galleries.txt'), true );

foreach ($gallery_data as $g) {
    
    print('<table><tr><td>');
	print('<a href="'.$g['url'].'"><img height="160" width="160" src="'.$g['thumbnail'].'"></a>');
	print('</td></tr><tr><td style="text-align:center;">');
	print($g['name']);
	print('</td></tr><tr><td style="text-align:center;">');
	print($g['date']);
	print('</td></tr></table>');
}

?>
</noscript>