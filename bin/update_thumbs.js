var http = require('https');
var URL = require('url');
var async = require('async');

var albums = require('./dance_galleries.json');

//console.log(albums);

var request = function(url, res) {
    var req = http.get(url, function(response) {

        if (response.statusCode > 300 && response.statusCode < 400 && response.headers.location) {
            // The location for some (most) redirects will only contain the path,  not the hostname;
            // detect this and add the host to the path.
            var redirect;
            if (URL.parse(response.headers.location).hostname) {
                // Hostname included; make request to res.headers.location
                redirect = response.headers.location;

            }
            else {
                // Hostname not included; get host from requested URL (url.parse()) and prepend to location.
                //console.log('need to rqst '+response.headers.location);
                var newURL = URL.parse(url);
                //newURL.pathname = response.headers.location;
                redirect = newURL.protocol + "//" + newURL.host + response.headers.location;
            }
            //console.log('need to redirect to '+redirect);
            request(redirect, function(err, inner_res) {
                res(err, inner_res);
            });
            req.abort();
        }
        else if (response.statusCode != 200) {
            res("Response gave status code: " + response.statusCode + " for url:" + url);
            req.abort();
        }
        else {

            // handle the response
            var res_data = '';
            response.on('data', function(chunk) {
                res_data += chunk;
            });
            response.on('end', function() {
                //console.log(res_data);
                res(null, res_data);
            });
        }
    });
    req.on('error', function(err) {
        //console.log("Request error: " + err.message);
        res("Request error: " + err.message + " for url: " + url);
    });
}

var getThumbnail = function(url, callback) {
    request(url, function(err, html) {
        if (err) {
            //console.error(err);
            callback(err);
        }
        else {
            //console.log('got html');
            var thumb;
            html.split("\n").forEach(function(line) {
                if (line.indexOf('albumCoverUrl') != -1) {
                    thumb = line.match(/http.*jpg/)[0];
                    thumb = thumb.replace(/\\x2F/g, '/');
                    //console.log(thumb);
                    //console.log(line);
                }
            });
            if (thumb) {
                callback(null, thumb);
            }
            else {
                callback("albumCoverUrl not found in html for url: " + url);
            }
        }
    });
}

var updateThumbnail = function(album, done) {
    getThumbnail(album.url, function(err, thumb) {
        if (err) {
            done(err);
        }
        else {
            console.log(thumb);
            album.thumbnail = thumb;
            done();
        }
    });
}

async.eachSeries(albums, updateThumbnail, function(err) {

    if (err) {
        console.error("Error occurred: " + err);
    }
    else {
        console.log("finished successfully");

        //console.log( require('util').inspect(albums) );

        var fs = require('fs');

        var outputFilename = 'fixed_thumbs.json';
        
        fs.writeFile(outputFilename, JSON.stringify(albums, null, 4), function(err) {
            if (err) {
                console.log(err);
            }
            else {
                console.log("saved to " + outputFilename);
            }
        });
        
    }
});
