<div class="container">
	
	<div class="page-header"><h1>Newsletters</h1></div>

	<?php
	
	if(isset($_GET['_escaped_fragment_'])){
		$query_terms_raw = split('/', strtolower($_GET['_escaped_fragment_']));
		$query_terms = array();
		foreach( $query_terms_raw as $q ) {
			if( $q != '' ) array_push($query_terms, $q);
		}

		$newsletter_data = json_decode( file_get_contents('data/newsletters.txt'), true );
		
		function newsletter_link($date, $title) {
			return '<a href="#!/'.$date.'">'.$title.'</a>';
		}
		
		if( count($query_terms) > 0 ) {
			$date = $query_terms[0];
			foreach ($newsletter_data as $n) {
				if( $date == $n['date']) {
					print('<h2>'.$n['title'].'</h2>');
					print('<h3>'.date_format(date_create($n['date']), 'jS F Y').'</h3>');
					print('<p>'.$n['content'].'</p>');
					$next = true;
					if( isset( $previous ) ) {
						print('<p>'.newsletter_link($previous['date'], '&larr; Newer').'</p>');
					}
					continue;	
				}
				if( isset($next) ) {
					print('<p>'.newsletter_link($n['date'], 'Older &rarr;').'</p>');
					break;
				}
				$previous = $n;
			}
		} else {
			foreach($newsletter_data as $n) {
				print(newsletter_link($n['date'],$n['title']).'<br/>');
			}
		}

	} else {
		include('newsletter_controller.php');
	}
	
	?>

</div>
	
