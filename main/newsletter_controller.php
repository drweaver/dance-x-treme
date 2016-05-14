<div ng-controller="NewsletterController" enable-path-watch>

    <div class="alert alert-info" ng-if="loading">
        <div class="loader">Loading...</div>Loading newsletters, best dress, dance partner, please wait...
    </div>

    <div class="ng-cloak" ng-if="!loading">
        <div  ng-repeat="newsletter in newsletters" ng-if="selection == newsletter.date" class="album-animate">
        <div class="row">
            <div class="col-md-2 col-xs-6">
                <nav>
                  <ul class="pager">
                    <li class="previous" ng-class="$first?'disabled':''"><a ng-href="{{$first?'':'#!'+makePath(newsletters[$index-1])}}"><span aria-hidden="true">&larr;</span> Newer </a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="col-md-2 col-md-push-8 col-xs-6">
                <nav>
                  <ul class="pager">
                    <li class="next" ng-class="$last?'disabled':''"><a ng-href="{{$last?'':'#!'+makePath(newsletters[$index+1])}}">Older <span aria-hidden="true">&rarr;</span></a></li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-8 col-md-pull-2 col-xs-12">
                    <div class="panel panel-default" >
                        <div class="panel-heading"><div class="row"><div class="col-sm-8">{{newsletter.title}}</div><div class="col-sm-4 text-right"><small >{{newsletter.dateMonth}} {{newsletter.dateYear}}</small></div> </div></div>
                        <div class="panel-body"><p>{{newsletter.content}}</p></div>
                    </div>
            </div>
            
        </div>
        </div>            

        <div class="alert alert-danger" role="alert" ng-if="!isAnyNews()" >
            <p><strong>Oops!</strong> The newsletter you were looking for just missed the last waltz. Try our <a ng-href="#!{{makePath(newsletters[0])}}">latest newsletter</a></p>
        </div>
        
    </div>
</div>