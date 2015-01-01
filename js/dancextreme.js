function loadJson(url, successCallback) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        cache: false,
        success: function(json) {
            successCallback(json);
        },
        error: function(xhr, status, errorThrown) {
            console.log("Error: " + errorThrown);
            console.dir(xhr);
        }
    });
}

function HashUtil(pathName, location) {
    this.pathName = pathName;
    this.location = location;
    this.patt = new RegExp('/' + pathName + '/(\\w*)');
    this.isPresent = function() {
        return this.patt.test(this.location.hash);
    };
    this.getToken = function() {
        var results = this.patt.exec(this.location.hash);
        return results === null ? null : results[1];
    };
    this.setToken = function(token) {
        var locHash = this.location.hash.replace(/^#/, '');
        if (this.isPresent()) {
            locHash = locHash.replace(this.patt, this.make(token));
        } else {
            locHash = (locHash + this.make(token)).replace(/\/\//g, '/');
        }
        this.location.hash = locHash;
        return locHash;
    };
    this.make = function(token) {
        return '/' + this.pathName + '/' + token;
    };
}

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

var Venue = {
    initMap: function(jsonArray) {
        console.log('loading map');
        var venueMap = $('#venue-map');
        if (!venueMap.length) {
            return;
        }
        var areaDiv = $('<div/>').appendTo(venueMap);
        $('<div id="gmap"/>').appendTo(venueMap);
        var hash = new HashUtil('venue', location);
        var map = new google.maps.Map(document.getElementById('gmap'), {center: {lat: 52.5530, lng: -2.0393}, zoom: 10});
        var bounds = new google.maps.LatLngBounds();
        var infoWindow = new google.maps.InfoWindow();
        //console.log(jsonArray);
        var timeIncr = 0;
        $.each(jsonArray, function(iv, v) {
            timeIncr += 200;
            setTimeout(function() {
                //console.log('loading marker');
//                console.log(v.position);
                v.showInfoWindow = function() {
                    var contentString = "<img src='img/dance-x-treme-small.jpg'/>" +
                            "<p><b>" + v.name + "</b><br/>" +
                            v.address + "<br/>" +
                            "<a target=\"_blank\" href=\"" + v.svUrl + "\">Get Directions</a>" +
                            "</p>";
                    if (v.timetable && v.timetable.length) {
                        contentString = contentString + "<p><a href=\"classes#/class/" + v.id + "\">Class timetable</a></p>";
                    }
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                };
                var marker = new google.maps.Marker({
                    position: v.position,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    title: v.name
                });

                google.maps.event.addListener(marker, 'click', function() {
                    hash.setToken(v.id);
                });
            }, timeIncr);

            bounds.extend(new google.maps.LatLng(v.position.lat, v.position.lng));

        });

        map.fitBounds(bounds);

        function venueChanged() {
            if (hash.isPresent()) {
                var token = hash.getToken();
                $.each(jsonArray, function(iv, v) {
                    if (v.id === token) {
                        v.showInfoWindow();
                    }
                });
            }
        }

        var sep = "";
        function addAreaBounds(area, venues) {
            $('<span/>').text(sep).appendTo(areaDiv);
            $('<a/>').text(area).click(function() {
                var areaBounds = new google.maps.LatLngBounds();
                $.each(venues, function(iv, v) {
                    console.log(v.position);
                    areaBounds.extend(new google.maps.LatLng(v.position.lat, v.position.lng));
                });
                map.fitBounds(areaBounds);
                if (map.getZoom() > 15) {
                    map.setZoom(15);
                } else {
                    map.setZoom(map.getZoom() - 1);
                }
            }).appendTo(areaDiv);
            sep = ", ";
        }



        $('<span/>').text('Click the map markers for more information.').appendTo(areaDiv);
        $('<br/>').appendTo(areaDiv);
        $('<br/>').appendTo(areaDiv);
        $('<span/>').text('Zoom to: ').appendTo(areaDiv);

        addAreaBounds('All', jsonArray);
        $.each(Venue.venuesByArea(jsonArray), addAreaBounds);

        $(window).bind('hashchange', venueChanged);
        setTimeout(function() {
            venueChanged();
        }, timeIncr + 5);
    },
    init: function() {

        if ($('#classes-by-day').length || $('#venue-map').length) {


            loadJson('data/dance_venues.txt', function(jsonArray) {
                jsonArray = Venue.removeDisabled(jsonArray);
                Venue.cleanClosures(jsonArray);

                Venue.initMap(jsonArray);

            });
        }
    },
    cleanClosures: function(jsonArray) {
        var cutoffTime = new Date().getTime() - 259200000;
        $.each(jsonArray, function(i, v) {
            if (v.closures && v.closures.length) {
                v.closures.sort(function(a, b) {
                    var ams = DateUtil.parse(a).getTime();
                    var bms = DateUtil.parse(b).getTime();
                    return ams - bms;
                });
                while (v.closures.length && DateUtil.parse(v.closures[0]).getTime() < cutoffTime) {
                    v.closures.shift();
                }
            }
        });
    },
    removeDisabled: function(jsonArray) {
        var newArray = [];
        $.each(jsonArray, function(i, v) {
            if (v.enabled) {
                newArray.push(v);
            }
        });
        return newArray;
    },
    classesForDay: function(jsonArray, day) {
        var classes = [];
        $.each(jsonArray, function(i, v) {
            if ('timetable' in v) {
                $.each(v.timetable, function(timetablei, timetable) {
                    if (timetable.day === day) {
                        classes.push(v);
                    }
                });
            }
        });
        return classes;
    },
    venuesByArea: function(jsonArray) {
        var areas = {};
        $.each(jsonArray, function(iv, v) {
            if (v.area in areas) {
                areas[v.area].push(v);
            } else {
                areas[v.area] = [v];
            }
        });
        return areas;
    }
};

$(document).ready(function() {
    Venue.init();
});

var app = angular.module('dancextremeApp', [ 'ngAnimate']);

app.controller('GalleryController', function ($scope, $http) {
    $http.get('data/dance_galleries.txt').success(function(data) {
        $scope.query = 'latest';
        parseAndSortDate(data);
        $.each(data, function(index, value) { index < 8 ? value.latest = 'latest' : value.latest = 'oldest' });
        $.each(data, function(index, value) { value.all = 'all'; });
        $scope.albums = data;
        $scope.canned = years(data).concat(['Pelsall', 'Coven', 'Tower', 'Cornbow', 'Latest', 'All' ]);
    });
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
    
});

//var socialCal = 'https://www.googleapis.com/calendar/v3/calendars/k9r659lk7fiqdshd2eql8ss1v8%40group.calendar.google.com/events/?key=AIzaSyAzo-Q6qXNjbhaTknSH9K7lsZnlgAkhV3I&singleEvents=true&maxResults=1&orderBy=startTime';
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
    $http.get('data/dance_galleries.txt').success(function(data) {
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
        console.log('length='+$scope.images.length)
        console.log('selected='+$scope.selected);
    }, $scope.interval);
});