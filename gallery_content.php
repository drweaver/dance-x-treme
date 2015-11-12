<div class="container">
	
	<div class="page-header"><h1>Gallery</h1></div>
	<p>View photos from our social dance events.  Click or search for albums using the form below.</p>

	<?php
	
	if(isset($_GET['_escaped_fragment_'])){
		$escaped_fragment = str_replace('/', '', strtolower($_GET['_escaped_fragment_']));
		$query_terms_raw = split('/', strtolower($_GET['_escaped_fragment_']));
		$query_terms = array();
		foreach( $query_terms_raw as $q ) {
			if( $q != '' ) array_push($query_terms, $q);
		}

		$gallery_data = json_decode( file_get_contents('data/dance_galleries.txt'), true );
		
		if( count($query_terms) == 0 ) {
			$years = array();
			foreach($gallery_data as $g) {
				$years[substr($g['date'], 0, 4)] = 1;
			}
			$canned = array_merge( array('Pelsall', 'Coven', 'Tower', 'Cornbow', 'Latest'), array_keys($years));
			foreach($canned as $can) {
				print('<a href="#!/'.$can.'">'.$can.' Photo Albums</a><br/>');
			}
			return;
		}
		
		function match_all_queries($g, $query_terms, $n) {
			foreach( $query_terms as $q ) {
				if( !( ( $q == 'latest' && $n<8 ) || strpos( strtolower($g['name']), $q) !== false || substr($g['date'],0,4) == $q ) )
					return false;
			}
			return true;
		}
		
		$n = 0;
		foreach ($gallery_data as $g) {
			$n++;
		    if( match_all_queries($g, $query_terms, $n) ) {
			    print('<table><tr><td>');
				print('<a href="'.$g['url'].'" target="_blank"><img height="160" width="160" src="'.$g['thumbnail'].'"></a>');
				print('</td></tr><tr><td style="text-align:center;">');
				print($g['name']);
				print('</td></tr><tr><td style="text-align:center;">');
				print(date_format(date_create($g['date']), 'jS F Y'));
				print('</td></tr></table>');
		    }
		}
	} else {
		include('gallery_controller.php');
	}
	
	?>

</div>
	
