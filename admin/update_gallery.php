<!DOCTYPE html>
<html>
<body>

<?php 
$json_string = json_encode(json_decode($HTTP_RAW_POST_DATA), JSON_PRETTY_PRINT);
file_put_contents('dance_galleries.txt', $json_string);
exec( 'cp ./dance_galleries.txt ./bak/dance_galleries.txt.$(date +%Y%m%d_%H%M%S)');
exec( './publish_gallery.sh >> ./publish_gallery.log');
?>

Thanks for posting the update!<br>
</body>
</html>
