<?php

	require_once"../../../wp-config.php";
	require_once dirname(__FILE__) . '/HTML_ToPDF.php';

	global $wpdb;


	$macme_table_statistics_data = $wpdb->prefix . "macme_stat_data";
	$macme_table_statistics_location = $wpdb->prefix . "macme_stat_location";
	$macme_table_content_assets = $wpdb->prefix . "macme_content_assets";



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
		
		
		/*
		
				HTML TO PDF VERSION (needs software in PERL ibstalled to the server
				
		$pdf =& new HTML_ToPDF($s, get_bloginfo("url") );
		$pdf->setDefaultPath(ABSPATH . 'wp-content/plugins/macme/generatedPDF/');
		$pdf->setDebug(true);
		$result = $pdf->convert();
		if (is_a($result, 'HTML_ToPDFException')) {
    		die($result->getMessage());
		}
		else {
	
	
			$pdffnameURL =  WP_PLUGIN_URL . '/macme/generatedPDF/' . $unico . ".pdf";
			$pdffnamePATH =  ABSPATH . 'wp-content/plugins/macme/generatedPDF/' . $unico . ".pdf";
			
			$pdffileHTML = fopen ($pdffnamePATH , "w");
			fwrite($pdffileHTML, $result);
			fclose ($pdffileHTML); 
			
			
			update_option("macme_book_latest_PDF_URL",$pdffnameURL);
		
			echo("<a href='" . $pdffnameURL . "' target='_blank'>OPEN GENERATED PDF</a><br />");
			
	
		}
		
		
		*/
		
		
		
		/*
		
			MPDF VERSION
			
		*/
		
		include("mpdf/mpdf.php");

		$mpdf=new mPDF(); 

		$pdffnameURL =  WP_PLUGIN_URL . '/macme/generatedPDF/' . $unico . ".pdf";
		$pdffnamePATH =  ABSPATH . 'wp-content/plugins/macme/generatedPDF/' . $unico . ".pdf";

		$mpdf->WriteHTML($s);
		$mpdf->Output($pdffnamePATH,"F");
		
		update_option("macme_book_latest_PDF_URL",$pdffnameURL);
		
		echo("<a href='" . $pdffnameURL . "' target='_blank'>OPEN GENERATED PDF</a><br />");
			
	
	
	}


?>