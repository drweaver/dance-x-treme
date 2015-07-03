
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



var app = angular.module('dancextremeApp', [ 'ngAnimate', 'uiGmapgoogle-maps']);

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
    
    $scope.markerClick = function(marker, event, obj) {
      obj.showInfoWindow();
    };
    
    function load(data) {
        $scope.aMap['All'] = new google.maps.LatLngBounds();
        $.each(data, function(index,venue) {
            if( !venue.enabled || !venue.timetable ) return true; // continue
            venue.showInfoWindow = function() {
                var contentString = "<img src='img/dance-x-treme-small.jpg'/>" +
                    "<p><b>" + venue.name + "</b><br/>" +
                    venue.address + "<br/>" +
                    "<a target=\"_blank\" href=\"" + venue.svUrl + "\">Get Directions</a>" +
                    "</p>";
                if (venue.timetable && venue.timetable.length) {
                    contentString = contentString + "<p><a href=\"classes#/class/" + venue.id + "\">Class timetable</a></p>";
                }
                infoWindow.setContent(contentString);
                infoWindow.open($scope.mapControl.getGMap(), $scope.markerControl.getPlurals().get(venue.id).gObject);
            };
            if( !$scope.aMap[venue.area] ) $scope.aMap[venue.area] = new google.maps.LatLngBounds();
            $scope.aMap[venue.area].extend(new google.maps.LatLng(venue.position.latitude, venue.position.longitude));
            $scope.aMap['All'].extend(new google.maps.LatLng(venue.position.latitude, venue.position.longitude));
            $scope.venues.push(venue);
        });
        

        
        uiGmapIsReady.promise(1).then(function(instances) {
            $scope.$watch(
                function() {
                return $location.path();
            },
            function(newVal, oldVal) {
                var results = /venue\/(.*)/.exec(newVal);
                if (results !== null) {
                    var id = results[1];
                     $scope.markerControl.getPlurals().get(id).model.showInfoWindow();
                }
            }
            );
            $scope.fitBounds = function(bounds) {
                  var map = $scope.mapControl.getGMap();
                  map.fitBounds(bounds);
                    if (map.getZoom() > 15) {
                        map.setZoom(15);
                    } else {
                        map.setZoom(map.getZoom() - 1);
                    }
            };
            
            $scope.loading = false;
        });
    
    };
    
    $http.get('data/dance_venues.txt?_='+ new Date().getTime()).success(load);


});



app.controller('ClassController', function($scope, $http, $location) {
    $scope.loading = true;
    $scope.dataArray = [];
    $scope.byArea = [];
    $scope.byDay = [];
    $scope.byVenue = [];
    $scope.order = '';
    var dayOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $scope.dayIndex = function(data) {
        var dayStripped = data.index.replace(/^on /, '').replace(/s$/, '');
        return dayOrder.indexOf(dayStripped);
    };
    $scope.search = function(type, query, order) {
        $scope.query = { type: type, index: query };
        $scope.order = order;
    };
    $scope.searchByType = function(query, order) {
        $scope.query = { type: query };
        $scope.order = order;
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
    $scope.searchByType('by-area', '+index');
    function load(data) {
        var aMap = {};
        var dMap = {};
        var vMap = {};
        $.each(data, function(index,venue) {
            if( !venue.enabled || !venue.timetable ) return true; // continue
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
           $scope.byArea.push( { index: i, venues: v, btnClass: 'btn-warning', type: 'by-area' } );
        });
        $.each( dMap, function(i,v) {
           $scope.byDay.push( { index: i, venues: v, btnClass: 'btn-info', type: 'by-day' } );
        });
        $.each( vMap, function(i,v) {
           $scope.byVenue.push( { index: i, venues: v, btnClass: 'btn-success', type: 'by-venue' } );
        });
        $scope.dataArray = $scope.byArea.concat($scope.byDay).concat($scope.byVenue);
        $scope.$watch(
            function() {return $location.path();},
            function(newVal, oldVal) {
                var results = /class\/(.*)/.exec(newVal);
                if( results !== null ) {
                    var id = results[1];
                    //console.log(id);
                    $.each(data, function(i,v) {
                       if( id === v.id ) {
                           var venueName = v.nickname || v.name;
                           //console.log( venueName );
                           $scope.search('by-venue', 'at '+ venueName, '+index');
                           return false;
                       } 
                    });
                } 
            }
        );
        $scope.loading = false;
    }
    $http.get('data/dance_venues.txt?_='+ new Date().getTime()).success(load);
});

app.controller('GalleryController', function ($scope, $http) {
    $scope.loading = true;
        $scope.search = function(query) {
        $scope.query = query;  
    };
    
    function parseAndSortDate(json) {
        $.each(json, function(index, value) {
            value.dateParsed = DateUtil.parse(value.date);
            value.datePretty = value.dateParsed.toLocaleDateString();
            value.dateYear = value.dateParsed.getFullYear();
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
            yearsArray.push(iy)
        });
        return yearsArray.sort().reverse();
    }
    $http.get('data/dance_galleries.txt?_='+ new Date().getTime()).success(function(data) {
        $scope.query = 'latest';
        parseAndSortDate(data);
        $.each(data, function(index, value) { index < 8 ? value.latest = 'latest' : value.latest = 'oldest' });
        $.each(data, function(index, value) { value.all = 'all'; });
        $scope.albums = data;
        $scope.canned = years(data).concat(['Pelsall', 'Coven', 'Tower', 'Cornbow', 'Latest', 'All' ]);
        $scope.loading = false;
    });

    
});

var g_api_key = 'AIzaSyAzo-Q6qXNjbhaTknSH9K7lsZnlgAkhV3I';

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
    $http.get('data/dance_galleries.txt?_='+ new Date().getTime()).success(function(data) {
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

