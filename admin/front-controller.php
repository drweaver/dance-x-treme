<?php

/**
 * This is an example of a front controller for a flat file PHP site. Using a
 * Static list provides security against URL injection by default. See README.md
 * for more examples.
 */
# [START gae_simple_front_controller]
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/update_venue.php':
        require 'update_venue.php';
        break;
    case '/update_gallery.php':
        require 'update_gallery.php';
        break;
    case '/update_holiday_gallery.php':
        require 'update_holiday_gallery.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}
# [END gae_simple_front_controller]