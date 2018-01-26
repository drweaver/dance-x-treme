<!DOCTYPE html>
<html>
<body>
<?php

$my_bucket = 'dance-x-treme-data';
$DANCE_GALLERIES = 'dance_galleries.txt';

$options = ['gs' => ['acl' => 'public-read']];
$context = stream_context_create($options);

$json_string = json_encode(json_decode($HTTP_RAW_POST_DATA), JSON_PRETTY_PRINT);
file_put_contents("gs://${my_bucket}/${DANCE_GALLERIES}", $json_string, 0, $context);

?>

Thanks for posting the update!<br>
</body>
</html>
