<?php

	require_once"../../../wp-config.php";

	global $wpdb;


	$macme_table_statistics_data = "macme_stat_data";
	$macme_table_statistics_location = "macme_stat_location";
	$macme_table_content_assets = "macme_content_assets";



	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}
	
	if($elements==""){
	
		?>
        
        ERROR! could not generate book, as book is empty!
        
        <?php
	
	
	} else {
	
	
		$s = macme_get_html_string_for_book();
		//echo($s);
	
		$unico = "macme_" . md5( uniqid() );
	
		$fnameURL =  WP_PLUGIN_URL . '/macme/generatedXHTML/' . $unico . ".html";
		$fnamePATH =  ABSPATH . 'wp-content/plugins/macme/generatedXHTML/' . $unico . ".html";
	
	
		$fileHTML = fopen ($fnamePATH , "w");
		fwrite($fileHTML, $s);
		fclose ($fileHTML); 
		
		
		update_option("macme_book_latest_XHTML_URL",$fnameURL);
		
		echo("<a href='" . $fnameURL . "' target='_blank'>OPEN GENERATED XHTML</a><br />");
		
	
	
	}


?>