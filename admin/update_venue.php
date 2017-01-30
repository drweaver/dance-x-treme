<!DOCTYPE html>
<html>
<body>

<?php 
$json_string = json_encode(json_decode($HTTP_RAW_POST_DATA), JSON_PRETTY_PRINT);
file_put_contents('dance_venues.txt', $json_string);
exec( 'cp ./dance_venues.txt ./bak/dance_venues.txt.$(date +%Y%m%d_%H%M%S)');
exec( './publish_venue.sh >> ./publish_venue.log');
?>

Thanks for posting the update!<br>
</body>
</html>
