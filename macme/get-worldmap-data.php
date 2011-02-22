<?php

	require_once"../../../wp-config.php";

	global $wpdb;
	
	$results = $wpdb->get_results( "SELECT count(l.id) as c, c.name as country, c.code as code FROM " . $wpdb->prefix . "macme_stat_location l, countrylist c WHERE UPPER(l.country)=UPPER(c.name) GROUP BY c.name" );
	//print_r($results);

	if(isset($results) && is_array($results) ){
		?>
		<worldmap>
			<?php
				if( count($results)>0 ){
					foreach($results as $r){
						?>
						<country  code="<?php echo( strtolower( trim( $r->code ) ) ); ?>" name="<?php echo( strtolower( trim( $r->country ) ) ); ?>" c="<?php echo( $r->c ); ?>" />
						<?php
					}//foreach($results as $r){
				}//if( count($results)>0 ){
				
				$results2 = $wpdb->get_results("SELECT count(l.id) as c, l.city as city , l.country as country FROM " . $wpdb->prefix . "macme_stat_location l GROUP BY l.city");
				if(isset($results2) && is_array($results2) && count($results2)>0 ){
					foreach($results2 as $r2){
						$city = $r2->city;
						if($city==""){
							$city = "unknown city";
						}					
					
						?>
						<city c="<?php echo($r2->c ); ?>" n="<?php echo($city ); ?> [ <?php echo($r2->country ); ?> ]" />
						<?php
					}//foreach($results2 as $r2){ 
				}//if(isset($results2) && is_array($results2) && count($results2)>0 ){ 
			?>
		</worldmap>
		<?
	}//if(isset($results) && is_array($results) ){

?>