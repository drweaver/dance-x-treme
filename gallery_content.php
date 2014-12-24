<h1>Gallery</h1>
<div id="dance-galleries-loading" class="hidden"><img src="img/spinner.gif"/> Loading...</div>
<div id="dance-galleries">
<noscript>
<?php

$gallery_data = json_decode( file_get_contents('data/dance_galleries.txt'), true );

foreach ($gallery_data as $g) {
    
    print('<table><tr><td>');
	print('<a href="'.$g['url'].'"><img height="160" width="160" src="'.$g['thumbnail'].'"></a>');
	print('</td></tr><tr><td style="text-align:center;">');
	print($g['name']);
	print('</td></tr><tr><td style="text-align:center;">');
	print($g['date']);
	print('</td></tr></table>');
}

?>
</noscript>
</div>