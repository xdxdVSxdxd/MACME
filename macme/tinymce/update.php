<?php

require_once"../../../../wp-config.php";
global $wpdb;


$macme_table_statistics_data = "macme_stat_data";
$macme_table_statistics_location = "macme_stat_location";
$macme_table_content_assets = "macme_content_assets";


$res = "";


		$id = "";
		$title = "";
		$description = "";
		$url = "";
		$type = "";
		$display = "";
		$lat = "";
		$lng = "";
		$address = "";
		
		
		if( isset($_POST["title"]) && isset($_POST["description"])  && isset($_POST["url"])&& isset($_POST["type"])&& isset($_POST["display"])&& isset($_POST["lat"])&& isset($_POST["lng"])&& isset($_POST["address"])  ){
		
			$title = $_POST["title"] ;
			$description = $_POST["description"] ;
			$url = $_POST["url"] ;
			$type =  $_POST["type"] ;
			$display =  $_POST["display"] ;
			$lat = $_POST["lat"] ;
			$lng = $_POST["lng"] ;
			$address = $_POST["address"];
		
		
			if($lat==""){ $lat = "0"; }
			if($lng==""){ $lng = "0"; }
			if($type==""){ $type = "0"; }
			
			
			if($type=="1bis"){ $type="1"; }
			
			$qe = "INSERT INTO $macme_table_content_assets(title, description,url,type,display,qr_url,fiducial_url,lat,lng,address) values('" . $title . "','" . $description . "','" . $url . "',$type,'" . $display . "','','',$lat,$lng,'" . $address . "')";
			
			//echo($qe);
			
			$wpdb->query( $qe );			
		
		
			$id = $wpdb->insert_id;
		
		
			$res = " [macme title='" . str_replace("'"," ",$title) . "' id='" . $id . "'] ";
		}
		
		echo($res);

?>