<!DOCTYPE html>
<html>
<body>

<?php 
$json_string = json_encode(json_decode($HTTP_RAW_POST_DATA), JSON_PRETTY_PRINT);
file_put_contents('venue_update.txt', $json_string); 
exec( './publish_venue.sh >> ./publish_venue.log');
?>

Thanks for posting the update!<br>
</body>
</html>