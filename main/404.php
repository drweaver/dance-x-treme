<?php

http_response_code(404);

include('./default_vars.php');

$t_description = 'Page not found!';
$t_content_inc = './404_content.php';

include('./master.php');

?>
