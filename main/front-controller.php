<?php

/**
 * This is an example of a front controller for a flat file PHP site. Using a
 * Static list provides security against URL injection by default. See README.md
 * for more examples.
 */
# [START gae_simple_front_controller]
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
    case '/index.html':
        require 'index.php';
        break;
    case '/classes':
    case '/classes.html':
        require 'classes.php';
        break;
    case '/venues':
    case '/venues.html':
        require 'venues.php';
        break;
    case '/events':
    case '/events.html':
        require 'events.php';
        break;
    case '/gallery':
    case '/gallery.html':
        require 'gallery.php';
        break;
    case '/contact':
    case '/contact.html':
        require 'contact.php';
        break;
    default:
        http_response_code(404);
        require '404.php';
}
# [END gae_simple_front_controller]