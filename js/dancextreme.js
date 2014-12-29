var nextEventUrl = "http://www.google.com/calendar/feeds/k9r659lk7fiqdshd2eql8ss1v8%40group.calendar.google.com/public/full?alt=json&orderby=starttime&max-results=1&singleevents=true&sortorder=ascending&futureevents=true";

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

var Event = {
    init: function() {

        var nextEvent = $('#next-event');
        if (!nextEvent.length) {
            return;
        }

        loadJson(nextEventUrl, function(json) {
            var events = json.feed.entry;
            if (events.length) {
                var event = events[0];
                var title = event.title.$t;
                var time = event.gd$when[0].startTime;
                time = time.substring(0, time.indexOf('T'));
                nextEvent.text(title + ' on ' + DateUtil.formatLong(time));
            }
        });

    }
};

var DOW = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var MOY = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

var Venue = {
    initClassesByDay: function(jsonArray) {

        var classesByDay = $('#classes-by-day');
        if (!classesByDay.length) {
            return;
        }

        $.each(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], function(iday, day) {
            var venues = Venue.classesForDay(jsonArray, day);
            if (venues.length) {
                classesByDay.append($('<h3/>').text(day + 's'));
                var ul = $('<ul/>');
                classesByDay.append(ul);
                $.each(venues, function(venuei, venue) {
                    ul.append($('<li/>').html('<a href="#/class/' + venue.id + '">' + venue.name + '</a>, <b>' + venue.area + '</b>'));
                });
            }
        });

        var classDetails = $('#class-details');
        if (!classDetails.length) {
            return;
        }

        $(window).bind('hashchange', function() {
            var venue;
            $.each(jsonArray, function(iv, v) {
                if (location.hash === '#/class/' + v.id) {
                    venue = v;
                }
            });
            if (!venue) {
                return;
            }
            classDetails.empty();
            classDetails.append($('<h2/>').text(venue.name));

            $.each(venue.timetable, function(itimetable, timetable) {
                classDetails.append($('<h3/>').text(timetable.day + 's'));
                var table = $('<table/>').addClass('class-timetable');
                classDetails.append(table);
                table.append(
                        $('<tr/>')
                        .append($('<th>').text('Time'))
                        .append($('<th>').text('Duration'))
                        .append($('<th>').text('Style'))
                        .append($('<th>').text('Level'))
                        .append($('<th>').text('Price')));

                var count = 0;
                $.each(timetable.time, function(it, t) {
                    table.append(
                            $('<tr/>').addClass(count % 2 ? "alt" : "")
                            .append($('<td>').text(t.startTime))
                            .append($('<td>').text(t.duration))
                            .append($('<td>').text(t.style))
                            .append($('<td>').text(t.level))
                            .append($('<td>').text(t.price)));

                    count++;
                });

                var closureDates = "";
                var sep = "";
                $.each(venue.closures, function(ic, c) {
                    if (DateUtil.sameDOW(c, timetable.day)) {
                        closureDates = closureDates + sep + DateUtil.formatShort(c);
                        sep = ", ";
                    }
                });
                if (closureDates.length) {
                    classDetails.append($('<p/>').html('<b>Holiday Closures:</b> <span class="closures">' + closureDates + '</span>'));
                }

                classDetails.append($('<br/>'));

            });

            classDetails.append($('<h3/>').text('Address'));
            classDetails.append($('<p/>').text(venue.address));
            classDetails.append($('<a/>', {href: 'venues#/venue/' + venue.id}).text('View Map'));

            function isDetailsInView()
            {
                var docViewTop = $(window).scrollTop();
                var docViewBottom = docViewTop + $(window).height();

                var elemTop = classDetails.offset().top;
                var elemBottom = elemTop + classDetails.height();

                return docViewBottom > elemTop;
            }

            if (!isDetailsInView()) {
                $('html, body').animate({
                    scrollTop: classDetails.offset().top
                }, 2000);
            }

        });

        if (location.hash && location.hash.match("^#/class/")) {
            console.log("Firing class bind");
            $(window).trigger('hashchange', [location.hash]);
        }

    },
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


//        $.each(Venue.venuesByArea(jsonArray), function(area, venues) {



//            var areaTable = $('<table/>', {style: "display:inline-table; width:160px;"});
//            $('<th/>').text(area).appendTo(  $('<tr/>').appendTo( areaTable ) );
//            $.each(venues, function(iv, v) {
//                $('<a/>', { href:'#'+hash.make(v.id)}).text(v.name).appendTo( $('<td/>').appendTo( $('<tr/>').appendTo(areaTable)));
//            });
//            areaTable.appendTo( $('<div/>', {style: 'display: inline; padding: 2px;'}).appendTo( areaDiv ) );

//            $('<a/>').text(area).click(function() {
//                var areaBounds = new google.maps.LatLngBounds();
//                $.each(venues, function(iv, v) {
//                    console.log(v.position);
//                    areaBounds.extend(new google.maps.LatLng(v.position.lat, v.position.lng));
//                });
//                map.fitBounds(areaBounds);
//                if (map.getZoom() > 15) {
//                    map.setZoom(15);
//                }
//                console.log(map.getZoom());
//            }).appendTo(areaDiv);

//        });

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

                Venue.initClassesByDay(jsonArray);
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
    Gallery.init();
    Slideshow.init();
    Event.init();
});

var app = angular.module('dancextremeApp', []);

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