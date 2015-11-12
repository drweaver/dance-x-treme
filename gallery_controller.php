<div ng-controller="GalleryController">

    <div class="alert alert-info" ng-if="loading">
        <div class="loader">Loading...</div>Loading galleries, best dress, dance partner, please wait...
    </div>

    <div class="jumbotron ng-cloak" nf-if="!loading">
        <form class="form-inline">
            Search:
            <input ng-model="query" class="form-control form-inline">
            <a class="btn btn-primary ng-cloak" type="button" ng-href="#!/{{can}}" ng-repeat="can in canned" style="margin: 4px;">{{can}}</a>
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