
/*global google*/
/*global angular*/

var DateUtil = {
    DAY_LONG: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    MONTH_SHORT: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    MONTH_LONG: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    DATE_SUFFIX: ['', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th', 'th',
        'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th', 'th',
        'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th', 'th',
        'st'],
    datePattern: /^(\d{4})-(\d{2})-(\d{2})$/,
    sameDOW: function(dateString, dayString) {
        var date = DateUtil.parse(dateString);
        return date === null ? false : DateUtil.DAY_LONG[DateUtil.parse(dateString).getDay()] === dayString;
    },
    formatLong: function(dateString) {
        var date = DateUtil.parse(dateString);
        if (date) {
            return DateUtil.DAY_LONG[date.getDay()] + ', '
                    + date.getDate() + DateUtil.DATE_SUFFIX[date.getDate()] + ' '
                    + DateUtil.MONTH_LONG[date.getMonth()] + ' '
                    + date.getFullYear();
        }
        return dateString;
    },
    formatShort: function(dateString) {
        var date = DateUtil.parse(dateString);
        if (date) {
            return date.getDate() + '-' + DateUtil.MONTH_SHORT[date.getMonth()] + '-' + date.getFullYear();
        }
        return dateString;
    },
    formatReallyShort: function(dateString) {
        var date = DateUtil.parse(dateString);
        if (date) {
            return date.getDate() + '-' + DateUtil.MONTH_SHORT[date.getMonth()];
        }
        return dateString;
    },
    parse: function(dateString) {
        var result = DateUtil.datePattern.exec(dateString);
        if (result === null || result.length < 4 || result[1] < 1000 || result[2] < 1 || result[2] > 12 || result[3] < 1 || result[3] > 31) {
            return null;
        }
        return new Date(result[1], result[2] - 1, result[3], 0, 0, 0, 0);
    }
};

var DOW = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var MOY = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

var GALLERY_DATA = 'https://storage.googleapis.com/dance-x-treme-data/dance_galleries.txt?_='+ new Date().getTime();
var GALLERY_DATA_LOCAL = 'data/dance_galleries.txt?_='+ new Date().getTime();
var HOLIDAY_GALLERY_DATA = 'https://storage.googleapis.com/dance-x-treme-data/holiday_dance_galleries.txt?_='+ new Date().getTime();
var HOLIDAY_GALLERY_DATA_LOCAL = 'data/holiday_dance_galleries.txt?_='+ new Date().getTime();

var VENUE_DATA = 'https://storage.googleapis.com/dance-x-treme-data/dance_venues.txt?_='+ new Date().getTime();
var VENUE_DATA_LOCAL = 'data/dance_venues.txt?_='+ new Date().getTime();

var CANNED_ALBUMS = ['Pelsall', 'Coven', 'Cornbow', 'Halloween', 'Christmas', 'Valentines'];
var HOLIDAY_CANNED_ALBUMS = ['Tower', 'Weekend'];

var app = angular.module('dancextremeApp', [ 'ngAnimate', 'uiGmapgoogle-maps']);

app .config(function($locationProvider) {
  $locationProvider.html5Mode(false).hashPrefix('!');
});

app.filter('splitCommas', function() {
  return function(text) {
    return text.split(/,/g);
  };
});

app.filter('closureFilter', function($filter) {
    
    var cutoffTime = new Date().getTime() - 259200000;
    
    return function(dates, dayFilter) {
        var validClosures = [];
        $.each(dates, function(i,date) {
           if( DateUtil.sameDOW(date, dayFilter) && DateUtil.parse(date).getTime() > cutoffTime ) {
               validClosures.push(date);
           }
        });
        return validClosures;
    };
});

app.controller('venueController', function($scope, $http, $location, uiGmapIsReady) {

    $scope.map = { center: { latitude: 52.5530, longitude: -2.0393 }, zoom: 10 };
    $scope.options = {scrollwheel: false};
    $scope.venues = [];
    $scope.mapControl = {};
    $scope.markerControl = {};
    $scope.aMap = {};
    $scope.loading = true;

    var infoWindow = new google.maps.InfoWindow();

    
    $scope.fitBounds = function(bounds) {
    uiGmapIsReady.promise(1).then(function(instances) {
      var map = $scope.mapControl.getGMap();
      map.fitBounds(bounds);
        if (map.getZoom() > 15) {
            map.setZoom(15);
        } else {
            map.setZoom(map.getZoom() - 1);
        }
    });
    };
    
    
    var makePath = function(index) {
        return index.replace(/ /g, "-").replace(/'/g, "").toLowerCase();
    };
    
    function load(data) {
        $scope.aMap['all'] = { name: 'All', bounds: new google.maps.LatLngBounds() };
        $.each(data, function(index,venue) {
            if( !venue.enabled ) return true; // continue
            var areaPath = 'in-'+makePath(venue.area);
            var venuePath = 'at-'+makePath(venue.name);
            venue.showInfoWindow = function() {
                var contentString = "<img src='img/dance-x-treme-small.jpg'/>" +
                    "<p><b>" + venue.name + "</b><br/>" +
                    venue.address + "<br/>" +
                    "<a target=\"_blank\" href=\"" + venue.svUrl + "\">Get Directions</a>" +
                    "</p>";
                if (venue.timetable && venue.timetable.length) {
                    contentString = contentString + "<p><a href=\"classes#!/by-venue/" + venuePath + "\">Class timetable</a></p>";
                }
                infoWindow.setContent(contentString);
                infoWindow.open($scope.mapControl.getGMap(), $scope.markerControl.getPlurals().get(venuePath).gObject);
            };
            if( !$scope.aMap[areaPath] ) $scope.aMap[areaPath] = { name: venue.area, bounds: new google.maps.LatLngBounds() };
            $scope.aMap[areaPath].bounds.extend(new google.maps.LatLng(venue.position.latitude, venue.position.longitude));
            $scope.aMap['all'].bounds.extend(new google.maps.LatLng(venue.position.latitude, venue.position.longitude));
            venue.path = venuePath;
            $scope.venues.push(venue);
        });
        
        uiGmapIsReady.promise(1).then(function(instances) {
            
            $scope.search = function(by, index) {
                if (by == 'by-venue' && index !== undefined && $scope.markerControl.getPlurals().get(index) !== undefined ) {
                    $scope.markerControl.getPlurals().get(index).model.showInfoWindow();
                } else if( by == 'by-area' && $scope.aMap[index] !== undefined ) {
                    $scope.fitBounds($scope.aMap[index].bounds);
                }
                $location.path('/'+by+(index!==undefined?'/'+index:'')).replace();
            };
            
            $scope.markerClick = function(marker, event, obj) {
                $scope.search('by-venue', obj.path);
            };
            
            $scope.loading = false;
            
            if( $location.path() == '' ) {
                $scope.search('by-area', 'all');
            }
            
            $scope.$watch(
                function() {
                    return $location.path();
                },
                function(newVal, oldVal) {
                    var results = newVal.split("/");
                    results.shift();
                    var by = results.shift();
                    var index = results.shift();
                    $scope.search(by, index);
                }
            );

        });
    
    }
    
     $http.get(VENUE_DATA, {timeout: 3000})
     .then(null,err=>{
     	console.log("Failed to load "+VENUE_DATA+": "+err+"\nLoading local copy...");
     	return $http.get(VENUE_DATA_LOCAL);
     })
     .then(r=>{load(r.data);})
   	 .then(null,err=>{console.log("Failed to load "+VENUE_DATA_LOCAL+": "+err);});


});



app.controller('ClassController', function($scope, $http, $location) {
    $scope.loading = true;
    $scope.dataArray = [];
    $scope.byArea = [];
    $scope.byDay = [];
    $scope.byVenue = [];
    $scope.order = '';
    $scope.query = { type: "unset"};
    var dayOrder = ['on-sundays', 'on-mondays', 'on-tuesdays', 'on-wednesdays', 'on-thursdays', 'on-fridays', 'on-saturdays'];
    $scope.dayIndex = function(data) {
        return dayOrder.indexOf(data.index);
    };
    $scope.search = function(type, query) {
        $scope.query = { type: type };
        if( query !== undefined ) $scope.query.index = query;
        $scope.order = type == 'by-day' ? $scope.dayIndex : '+index';
        $location.path('/'+type+(query!==undefined?'/'+query:'')).replace();
    };
    $scope.typeButtonClass = function(type) {
        if( $scope.query.type == type ) 
            return 'active';
        return 'not-active';
    };
    $scope.indexButtonClass = function(index) {
        if( $scope.query.index == index ) 
            return 'active';
        return 'not-active';
    };
    $scope.getTime = function(dateString) {
        return DateUtil.parse(dateString).getTime();
    };
    //$scope.search('by-area');
    function load(data) {
        var aMap = {};
        var dMap = {};
        var vMap = {};
        var makePath = function(index) {
            return index.replace(/ /g, "-").replace(/'/g, "").toLowerCase();
        };
        $.each(data, function(index,venue) {
            if( !venue.enabled || !venue.timetable ) return true; // continue
            venue.path = makePath('at-'+venue.name);
            var aKey = 'in '+venue.area;
            if( !aMap[aKey] ) aMap[aKey] = [];
            aMap[aKey].push(venue);
            $.each(venue.timetable, function(ti,t) {
                var dKey = 'on '+t.day+'s';
               if( !dMap[dKey] ) dMap[dKey] = [];
               var venueForDay = $.extend({}, venue);
               venueForDay.timetable = [ t ];
               dMap[dKey].push(venueForDay);
            });     
            var vKey = 'at '+ (venue.nickname || venue.name );
            if( !vMap[vKey] ) vMap[vKey] = [];
            vMap[vKey].push(venue);
        });
        $.each( aMap, function(i,v) {
           $scope.byArea.push( { name: i, venues: v, btnClass: 'btn-warning', type: 'by-area', index: makePath(i) } );
        });
        $.each( dMap, function(i,v) {
           $scope.byDay.push( { name: i, venues: v, btnClass: 'btn-info', type: 'by-day', index: makePath(i) } );
        });
        $.each( vMap, function(i,v) {
           $scope.byVenue.push( { name: i, venues: v, btnClass: 'btn-success', type: 'by-venue', index: makePath('at '+v[0].name) } );
        });
        $scope.dataArray = $scope.byArea.concat($scope.byDay).concat($scope.byVenue);
        $scope.$watch(
            function() {return $location.path();},
            function(newVal, oldVal) {
                //var results = /^(\/.+)(\/.+)?$/.exec(newVal);
                var results = newVal.split("/");
                results.shift();
                var by = results.shift();
                var index = results.shift();
                if( by !== undefined ) {
                    $scope.search(by, index);
                    console.log("calling search with: " + by  +" "+index);
                } 
            }
        );
        if( $location.path() === '' ) {
            console.log("setting default path");
            $scope.search('by-area');
        }
        $scope.loading = false;
    }
    
     $http.get(VENUE_DATA, {timeout: 3000})
     .then(null,err=>{
     	console.log("Failed to load "+VENUE_DATA+": "+err+"\nLoading local copy...");
     	return $http.get(VENUE_DATA_LOCAL);
     })
     .then(r=>{load(r.data);})
   	 .then(null,err=>{console.log("Failed to load "+VENUE_DATA_LOCAL+": "+err);});
      

});

function CommonGalleryController( gallery_url, gallery_url_local, canned_albums ) {
    return function ($scope, $http, $location) {
        $scope.loading = true;
        $scope.query_terms = [];
        $scope.search = function(query) {
            $location.path('/'+query.replace(/ /g, '/')).replace();
        };
        $scope.keyup = function(enter_pressed) {
            if( enter_pressed ) {
                $location.path('/'+$scope.query.replace(/ /g, '/')).replace();
            } else {
                $scope.query_terms = $scope.query.split(" ");
            }
        };
        
        $scope.album_filter = function(value, index, array) {
            if( $scope.query_terms.length == 0 ) 
                return false;
                
            for (var i in $scope.query_terms) {
                var term = $scope.query_terms[i].toLowerCase();
                if( value.date.indexOf(term) == -1 && value.name.toLowerCase().indexOf(term) == -1 && value.latest != term ) 
                    return false;
            }
            return true;
            
        };
        
        function parseAndSortDate(json) {
            $.each(json, function(index, value) {
                value.dateParsed = DateUtil.parse(value.date);
                value.datePretty = value.dateParsed.toLocaleDateString();
                value.dateYear = value.dateParsed.getFullYear();
                value.dateMonth = DateUtil.MONTH_LONG[value.dateParsed.getMonth()]; 
            });
            json.sort(function(a, b) {
                return b.dateParsed.getTime() - a.dateParsed.getTime();
            });
        }
        
        function years(json) {
            var years = {};
            $.each(json, function(index, value) {
                years[value.dateYear] = true;
            });
            var yearsArray = [];
            $.each(years, function(iy, y) {
                yearsArray.push(iy);
            });
            return yearsArray.sort().reverse();
        }
        function load(data) {
            //$scope.query = 'latest';
            parseAndSortDate(data);
            $.each(data, function(index, value) { index < 8 ? value.latest = 'latest' : value.latest = 'oldest' });
            $scope.albums = data;
            $scope.canned = ['Latest'].concat(years(data).concat(canned_albums));
            if( $location.path() == '' ) {
                console.log("setting default path");
                $location.path('/Latest').replace();
            }        
            $scope.loading = false;
            $scope.$watch(
                function() {return $location.path();},
                function(newVal, oldVal) {
                    var results = newVal.split("/");
                    $scope.query_terms = [];
                    $.each(results, function(i,v) { v != '' ? $scope.query_terms.push(v):false; });
                    $scope.query=$scope.query_terms.join(' '); 
                }
            );

        }
        
     $http.get(gallery_url, {timeout: 3000})
     .then(null,err=>{
     	console.log("Failed to load "+gallery_url+": "+err+"\nLoading local copy...");
     	return $http.get(gallery_url_local);
     })
     .then(r=>{load(r.data);})
   	 .then(null,err=>{console.log("Failed to load "+gallery_url_local+": "+err);});
        
    };
}

app.controller('GalleryController', CommonGalleryController(GALLERY_DATA, GALLERY_DATA_LOCAL, CANNED_ALBUMS));
app.controller('HolidayGalleryController', CommonGalleryController(HOLIDAY_GALLERY_DATA, HOLIDAY_GALLERY_DATA_LOCAL, HOLIDAY_CANNED_ALBUMS));


app.controller('NewsletterController', function ($scope, $http, $location) {
    $scope.loading = true;
    
    $scope.selection = '';
    $scope.makePath = function(newsletter) {
        return '/' + newsletter.date + '/' + newsletter.title.replace(/[-' ]/g, '-').toLowerCase();
    };
    $scope.isAnyNews = function() {
        for (var i in $scope.newsletters) {
            if( $scope.newsletters[i].date == $scope.selection ) 
                return true;
        }
        return false;
    };
    $http.get('data/newsletters.txt?_='+ new Date().getTime()).success(function(data) {
        $scope.newsletters = data;
        $.each($scope.newsletters, function(i,value) {
            var dateParsed = DateUtil.parse(value.date);
            value.dateYear = dateParsed.getFullYear();
            value.dateMonth = DateUtil.MONTH_LONG[dateParsed.getMonth()]; 
        });
        $scope.loading = false;
        
        $scope.enablePathWatch = function() {
            $scope.$watch(
                function() {return $location.path();},
                function(newVal, oldVal) {
                    var results = newVal.split("/");
                    var query_terms = [];
                    $.each(results, function(i,v) { v != '' ? query_terms.push(v):false; });
                    $scope.selection = query_terms.length > 0 ? query_terms[0] : '';
                }
            );
            if( $location.path() == '' ) {
                $location.path($scope.makePath($scope.newsletters[0])).replace();
            }
        };
        $scope.selectDefaultPath = function() {
            $scope.selection = $scope.newsletters[0].date;
        };
    });

});

app.directive('selectDefaultPath', function() {
   return {
       controller:
        ['$scope', function($scope) {
          $scope.$watch(
              function(){return $scope.selectDefaultPath},
              function(newVal) {
                 if( newVal !== undefined ) newVal();
              });  
        }]
   };
});

app.directive('enablePathWatch', function() {
   return {
       controller: 
            ['$scope', function($scope) {
                console.log('enabling path watch');
                $scope.$watch(
                    function() {return $scope.enablePathWatch},
                    function(newVal) {
                       if( newVal !== undefined ) newVal();
                    });
            }]
   };
});

var g_api_key =  'AIzaSyDm0r4q0ScAz4-UCBQO3KdxM38Fo-1Z2jo';

app.factory('getNextEvent', ['$http',
  function($http) {
      var baseUrl = 'https://www.googleapis.com/calendar/v3/calendars/';
      var params = 'singleEvents=true&maxResults=1&orderBy=startTime';
      return function(calendarId, callback) {
          var url = baseUrl + calendarId + '/events/' + '?' + params + '&' + 'key='+g_api_key+'&timeMin='+ new Date().toJSON();
            $http.get(url).success(function(data) {
                if( data.items && data.items.length > 0 ) {
                    var item = data.items[0];
                    callback( { title: item.summary, date: item.start.dateTime ? item.start.dateTime : item.start.date }  );
                } 
            });
        };
      }
  ]);

var socialCal = 'k9r659lk7fiqdshd2eql8ss1v8%40group.calendar.google.com';
var practiceCal = 'mtjt1tm2mjrgf0mnan2or7su50%40group.calendar.google.com';
var holidayCal = 'eekb8u8h6bc0ode46dqveji0mo%40group.calendar.google.com';

app.controller('NextSocialDanceController', function( $scope, getNextEvent ) {
    $scope.events = [ { title: "being scheduled, please remind Sue!", date: '3000-01-01', hideMe: true } ];
    $.each([socialCal, practiceCal, holidayCal], function(i, id) {
        getNextEvent(id, function(data) {
          $scope.events.push(data);
        });    
    });
});

app.controller('NextDanceImageController', function( $scope, $interval, $http ) {
    $scope.interval = 5000;
    $scope.selected = 0;
    $scope.count = 0;
    $scope.images = [];
    $http.get(GALLERY_DATA).success(function(data) {
        var start = Math.floor((Math.random() * (data.length-5)));
        for( var i=start; i<(start+5); i++) {
            $scope.images.push( { src: data[i].thumbnail });
        }
        $interval(function() {
            $scope.count++;
            $scope.selected = $scope.count % $scope.images.length;
        }, $scope.interval);
    });
});

app.controller('SocialMediaImageController', function($scope, $interval) {
    $scope.images =  
        [
        { src: "img/twitter_logo_white2.png",    href:"https://twitter.com/Dance_X_Treme" },
        { src: "img/facebook_logo_blue_144.png", href:"http://www.facebook.com/Dance.X.Treme.UK" }
        ];
    $scope.interval = 8000;
    $scope.selected = 0;
    $scope.count = 0;
    $interval(function() {
        $scope.count++;
        $scope.selected = $scope.count % $scope.images.length;
    }, $scope.interval);
});

