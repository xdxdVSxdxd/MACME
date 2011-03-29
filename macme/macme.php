<?php

/*
Plugin Name: MACME
Plugin URI: http://www.fakepress.it/macme
Description: MACME Framework
Author: Salvatore Iaconesi
Version: 1.05
Author URI: http://www.artisopensource.net
*/


/*  Copyright 2011  FAKEPRESS  (email : xdxd.vs.xdxd@gmail.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


	global $MACME_LINK;
	global $MACME_VIDEO;
	global $MACME_SOUND;
	global $MACME_3D;
	global $MACME_GOOGLE;
	global $MACME_FLICKR;
	global $MACME_LATLONG;
	global $MACME_ADDRESS;

$MACME_LINK = 0;
$MACME_VIDEO = 1;
$MACME_SOUND = 2;
$MACME_3D = 3;
$MACME_GOOGLE = 4;
$MACME_FLICKR = 5;
$MACME_LATLONG = 6;
$MACME_ADDRESS = 7;


include("GeoIP/geoipcity.inc");
include("GeoIP/geoipregionvars.php");

global $macme_table_statistics_data;
global $macme_table_statistics_location;
global $macme_table_content_assets;
global $macme_is_iphone;
global $macme_is_ipad;
global $macme_is_android;
global $macme_is_mobile;

global $wpdb;

$macme_table_statistics_data = $wpdb->prefix . "macme_stat_data";
$macme_table_statistics_location = $wpdb->prefix . "macme_stat_location";
$macme_table_content_assets = $wpdb->prefix . "macme_content_assets";

$macme_is_iphone = false;
$macme_is_ipad = false;
$macme_is_android = false;
$macme_is_mobile = false;







//add actions
add_action( 'wp_print_scripts', 'enqueue_macme_scripts' );
add_action('wp_print_styles', 'enqueue_macme_stylesheet');
add_action( 'wp_footer', 'capture_user_source' );
add_action('activate_macme/macme.php', 'macme_install');
add_action('init', 'macme_addbuttons');
add_shortcode('macme', 'macme_shortcode_handler');
add_shortcode('macme_code', 'macme_code_shortcode_handler');
add_shortcode('macme_all_codes', 'macme_all_codes_shortcode_handler');
add_shortcode('macme_map', 'macme_map_shortcode_handler');
add_shortcode('macme_pdf', 'macme_pdf_shortcode_handler');
add_shortcode('macme_xhtml', 'macme_xhtml_shortcode_handler');
add_shortcode('macme_epub', 'macme_epub_shortcode_handler');
add_shortcode('macme_world_map_visits', 'macme_shortcode_handler_world_map_visits');
add_action('admin_menu', 'macme_admin_menu');
add_action('save_post', 'macme_save_post', 10, 2);
add_action('wp_head', 'macme_head_do');




// executed when header is built
// scans for user device
function macme_head_do(){
global $macme_is_iphone;
global $macme_is_ipad;
global $macme_is_android;
global $macme_is_mobile;


$macme_is_iphone = false;
$macme_is_ipad = false;
$macme_is_android = false;
$macme_is_mobile = false;


$macme_is_iphone = strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ),"IPHONE");
$macme_is_android = strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ),"ANDROID");
$macme_is_ipad = strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ),"IPAD");
$macme_is_mobile = strpos( strtoupper( $_SERVER['HTTP_USER_AGENT'] ),"MOBILE");



	if(  $macme_is_iphone || $macme_is_android ){
		?>
        <meta name="viewport" content="width=device-width" />
        <?php
	} else {
		?>
        <!-- mobile not detected -->
        <?php
	}


}









function macme_install()
{
    global $wpdb;
        
		
	global $macme_table_statistics_data;
	global $macme_table_statistics_location;
	global $macme_table_content_assets;
	global $macme_is_iphone;
	global $macme_is_ipad;
	global $macme_is_android;
	global $macme_is_mobile;
    
       
    
    //$q1 = "drop table if exists $macme_table_statistics_data";
	//$wpdb->query($q1);
	//$q2 = "drop table if exists $macme_table_statistics_location";
	//$wpdb->query($q2); 
	//$q3 = "drop table if exists $macme_table_content_assets";
	//$wpdb->query($q3); 
	
    $structure = "CREATE TABLE IF NOT EXISTS $macme_table_statistics_data (
        id INT(9) NOT NULL AUTO_INCREMENT,
        resource VARCHAR(255) NOT NULL,
        t DATETIME NOT NULL,
        count INT(9) DEFAULT 0, 
	UNIQUE KEY id (id) 
    );"; 
    $wpdb->query($structure);
    
    
    $structure2 = "CREATE TABLE IF NOT EXISTS $macme_table_statistics_location (
        id INT(9) NOT NULL AUTO_INCREMENT,
        ip VARCHAR(255) NOT NULL,
		name VARCHAR(255) NOT NULL,
        t DATETIME NOT NULL,
        lat FLOAT DEFAULT 0,
		lng FLOAT DEFAULT 0,
		country VARCHAR(255) NOT NULL,
		city VARCHAR(255) NOT NULL,
		resource TEXT NOT NULL,
	UNIQUE KEY id (id) 
    );"; 
    $wpdb->query($structure2);
 
 
 	$structure3 = "CREATE TABLE IF NOT EXISTS $macme_table_content_assets (
        id INT(9) NOT NULL AUTO_INCREMENT,
        id_post INT(9) NOT NULL,
		title VARCHAR(255) NOT NULL,
        description TEXT,
        url TEXT,
		type TINYINT,
		display TEXT NOT NULL,
		qr_url TEXT,
		fiducial_url TEXT,
		lat FLOAT,
		lng FLOAT,
		address TEXT,
	UNIQUE KEY id (id) 
    );"; 
    $wpdb->query($structure3);
 
 
 
    // Populate table
    //$wpdb->query("INSERT INTO $table(bot_name, bot_mark)
    //    VALUES('Google Bot', 'googlebot')");
    //$wpdb->query("INSERT INTO $table(bot_name, bot_mark)
     //   VALUES('Yahoo Slurp', 'yahoo')");
        
        
       // qui creare tutto quello che mi serve per inizializzare MACME
} // macme_install





// ADD MACME BUTTON TO EDIT INTERFACE

function macme_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   // if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_macme_tinymce_plugin");
     add_filter('mce_buttons', 'register_macme_button');
   // }
}
 
function register_macme_button($buttons) {
   array_push($buttons, "|", "macme");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_macme_tinymce_plugin($plugin_array) {
	
   $plugin_array['macme'] = WP_PLUGIN_URL . '/macme/tinymce/editor_plugin.js';
   
   return $plugin_array;
}


// ADD MACME BUTTON TO EDIT INTERFACE




// HANDLER DELLO SHORTCODE
// [macme_code idpost='ID di un post']

function macme_code_shortcode_handler($atts) {

	
	extract(shortcode_atts(array(
	'idpost' => ''
	), $atts));


	$res = "";
	
	if($idpost && $idpost<>""){
	
	
		$p = get_post($idpost);
		if($p){
			$plink = get_permalink( $idpost );
			$res = "<div class='macme-qrcode-box'><img class='macme-qrcode-img' src='" . get_macme_qrcode_for_url( $plink ) . "' /><div class='macme-qrcode-title'>" . $p->post_title . "</div><div class='macme-qrcode-link'><a href='$plink' title='" . str_replace("'"," ",$p->post_title) . "'>$plink</a></div></div>";
		} else {
			$res = "referenced post not found";
		}
		
		
	} else {
	
		$res = "error in parameters";
	}

	return $res;

}


// FINE HANDLER DELLO SHORTCODE
// [macme-doce idpost='ID di un post']











// HANDLER DELLO SHORTCODE
// [macme_all_codes]

function macme_all_codes_shortcode_handler($atts) {

	$res = "";
	
	
	$args = array('numberposts'     => -1);

	
		$posts = get_posts($args);
		foreach($posts as $p){
			$plink = get_permalink( $p->ID );
			$res = $res . "<div class='macme-qrcode-box'><img class='macme-qrcode-img' src='" . get_macme_qrcode_for_url( $plink ) . "' /><div class='macme-qrcode-title'>" . $p->post_title . "</div><div class='macme-qrcode-link'><a href='$plink' title='" . str_replace("'"," ",$p->post_title) . "'>$plink</a></div></div>";
			
			
			$pattern = get_shortcode_regex();
    		preg_match_all('/'.$pattern.'/s', $p->post_content, $matches);
    
	
			if(is_array($matches) && count( $matches)>2  ){
	
				
				for($i=0; $i<count( $matches[2] ); $i++){
		
					if($matches[2][$i]=='macme'){
		
		
						$parameters = $matches[3][$i];
						$i1 = strpos($parameters,"id='");
						if($i1){
							$i1+=4;
						}
						$i2 = strpos($parameters,"'",$i1);
						$s2 = substr($parameters,$i1,$i2-$i1);
						
						
						
						$i3 = strpos($parameters,"title='");
						if($i3){
							$i3+=7;
						}
						$i4 = strpos($parameters,"'",$i3);
						$s3 = substr($parameters,$i3,$i4-$i3);
						
						
						
				
						if($s2){
				
				
		
						
						
							$link_cont = WP_PLUGIN_URL . '/macme/show.php?id=' . $s2;
							
							$res = $res . "<div class='macme-qrcode-box'><img class='macme-qrcode-img' src='" . get_macme_qrcode_for_url( $link_cont ) . "' /><div class='macme-qrcode-title'>" . $s3 . "</div><div class='macme-qrcode-link'><a href='$link_cont ' title='" . str_replace("'"," ",$s3) . "'>$link_cont </a></div></div>";
		
		
						}//if s2
		
					}//if matches 2
					
				}//for i
				
				
			}//if isarray
			
		}//foreach
		
	
	return $res;

}


// FINE HANDLER DELLO SHORTCODE
// [macme_all_codes]






// POST SAVE ACTIONS
// need to save post IDs in asset rows
function macme_save_post($post_ID, $post) {

	$pattern = get_shortcode_regex();
    preg_match_all('/'.$pattern.'/s', $post->post_content, $matches);
    
	
	if(is_array($matches) && count( $matches)>2  ){
	
		for($i=0; $i<count( $matches[2] ); $i++){
		
			if($matches[2][$i]=='macme'){
			
				
				$parameters = $matches[3][$i];
				$i1 = strpos($parameters,"id='");
				if($i1){
					$i1+=4;
				}
				$i2 = strpos($parameters,"'",$i1);
				$s2 = substr($parameters,$i1,$i2-$i1);
				
				if($s2){
				
				
				
					global $wpdb;

					global $macme_table_statistics_data;
					global $macme_table_statistics_location;
					global $macme_table_content_assets;
					global $macme_is_iphone;
					global $macme_is_ipad;
					global $macme_is_android;
					global $macme_is_mobile;
				
				
					$q = "UPDATE $macme_table_content_assets SET id_post='" . $post->ID . "' WHERE id='" . $s2 . "'";
					$wpdb->query( $q );
				
				
				}
			
			}//if macme
		
		}//for i
	
	}//if isarray
	
	

}







// HANDLER DELLO SHORTCODE
// [macme_map]

function macme_map_shortcode_handler($atts) {

	$s = "";
	
	
	$styles = get_option("macme_google_maps_styler");
	
	
	
	$s = $s . "<div id='macme-general-map'> </div>\n";
	$s = $s . "<script type='text/javascript'>\n";
	$s = $s . "var initialLocation;\n";
	$s = $s . "var browserSupportFlag =  new Boolean();\n";
	$s = $s . "var map;\n";
	$s = $s . "var geocoder;\n";
	$s = $s . "var bounds = null;\n";
	$s = $s . "function initialize() {\n";

	$s = $s . "var myOptions;\n";
	$s = $s . "initialLocation = new google.maps.LatLng(0, 0);\n";
	$s = $s . "geocoder = new google.maps.Geocoder();\n";
	
	if(isset($styles) && $styles<>""){
		//se definito styles
		$s = $s . "var styles =[ " . str_replace('"','\'',$styles) . " ];\n";
		$s = $s . "var customMapType = new google.maps.StyledMapType(styles, {name: 'custom'});\n";

		$s = $s . "myOptions = {\n";
    	$s = $s . "	zoom: 1,\n";
    	$s = $s . "	center: initialLocation,\n";
		$s = $s . "	mapTypeControl: false,\n";
    	$s = $s . "	mapTypeId: 'custom' \n";
  		$s = $s . "}\n";
  		$s = $s . "map = new google.maps.Map(document.getElementById('macme-general-map'), myOptions);\n";
  		$s = $s . "map.mapTypes.set('custom', customMapType);\n";
  		$s = $s . "bounds = new google.maps.LatLngBounds();\n";
		//se definito styles - end
	} else {
		//se non è definito styles
		$s = $s . "myOptions = {\n";
    	$s = $s . "	zoom: 1,\n";
    	$s = $s . "	center: initialLocation,\n";
		$s = $s . "	mapTypeControl: false,\n";
    	$s = $s . "	mapTypeId: google.maps.MapTypeId.HYBRID\n";
  		$s = $s . "}\n";
  		$s = $s . "map = new google.maps.Map(document.getElementById('macme-general-map'), myOptions);\n";
  		$s = $s . "bounds = new google.maps.LatLngBounds();\n";
		//se non è definito styles - end
	}

		$s = $s . "}//fine initialize\n";

		$s = $s . "initialize();\n";
		$s = $s . "var markers = new Array();\n";

$imk = 0;
//fare ciclo su coordinate e indirizzi

global $wpdb;

	global $macme_table_statistics_data;
	global $macme_table_statistics_location;
	global $macme_table_content_assets;
	global $macme_is_iphone;
	global $macme_is_ipad;
	global $macme_is_android;
	global $macme_is_mobile;


$q = "SELECT * FROM $macme_table_content_assets WHERE type=6 OR type = 7";
$results = $wpdb->get_results($q);

if($results){
	foreach($results as $re){
	
	
	 $fetched_post = get_post( $re->id_post );
	 $info_content = "<div class='macme_map_content_div'><h1 class='macme_map_info_title'>" . $fetched_post->post_title . "</h1><p class='macme_map_info_paragraph'><a class='macme_map_info_link' href='" . get_permalink($re->id_post) . "' title='" . __("Open Content","macme") . "'>open</a></p></div>";
	
	
		if($re->type==6){
	
	
			$s = $s . "var ll$imk = new google.maps.LatLng(" . $re->lat . "," . $re->lng . ");\n";
			$s = $s . "var mk$imk = new google.maps.Marker( {";
			$s = $s . "	position: ll$imk,";
			$s = $s . "	map: map,";
			$s = $s . "	title: '" . str_replace("'", " ", $re->title ) . "'";//, icon: 'METTERE ICONA'";
			$s = $s . "});\n\n";
			$s = $s . "markers.push( mk$imk );\n";


			$s = $s . "var contentString = '" . str_replace("'","\'",$info_content) . "';\n";
			$s = $s . "var info$imk = new google.maps.InfoWindow({ \n";
    		$s = $s . "	content: contentString \n";
			$s = $s . "});\n";
			$s = $s . "google.maps.event.addListener(mk$imk, 'click', function() { \n";
  			$s = $s . "	info$imk.open(map,mk$imk);\n";
			$s = $s . "});\n";



		
		
		} else if($re->type==7){
		
		
		
			$s = $s ."geocoder.geocode( { 'address': '" . str_replace("'", "\'" , $re->address ) . "'}, function(results, status) {\n";
	      	$s = $s ."	if (status == google.maps.GeocoderStatus.OK) {\n";
		    $s = $s ."    	var mk$imk = new google.maps.Marker({\n";
        	$s = $s ."	    	map: map, \n";
            $s = $s ."			position: results[0].geometry.location,\n";
        	$s = $s ."			title: '" . str_replace("'", " ", $re->title ) . "'";//, icon: 'METTERE ICONA'";
			$s = $s ."		});\n";
			$s = $s . "		markers.push( mk$imk );\n";
      		
			
			$s = $s . "var contentString = '" . str_replace("'","\'",$info_content) . "';\n";
			$s = $s . "var info$imk = new google.maps.InfoWindow({ \n";
    		$s = $s . "	content: contentString \n";
			$s = $s . "});\n";
			$s = $s . "google.maps.event.addListener(mk$imk, 'click', function() { \n";
  			$s = $s . "	info$imk.open(map,mk$imk);\n";
			$s = $s . "});\n";
		
			
			$s = $s ."	}\n"; 
    		$s = $s ."});\n";

			
		}
	
		$imk++;

	}//foreach
}//if results

//per ogni coordinata $lat $lng:
/*
*/
//per ogni coordinata: end

//fare ciclo su coordinate e indirizzi - END
		$s = $s . "</script>\n";
	
	
	
	
	
	

	
	
	return $s;

}


// FINE HANDLER DELLO SHORTCODE
// [macme_map]



















// HANDLER DELLO SHORTCODE
// [macme title='titolo escaped' id='id di asset']

function macme_shortcode_handler($atts) {
	
	global $wpdb;
	
	global $macme_table_statistics_data;
	global $macme_table_statistics_location;
	global $macme_table_content_assets;
	global $macme_is_iphone;
	global $macme_is_ipad;
	global $macme_is_android;
	global $macme_is_mobile;
	
	
	global $MACME_LINK;
	global $MACME_VIDEO;
	global $MACME_SOUND;
	global $MACME_3D;
	global $MACME_GOOGLE;
	global $MACME_FLICKR;
	global $MACME_LATLONG;
	global $MACME_ADDRESS;

	
	extract(shortcode_atts(array(
		'title' => '',
		'id' => ''
	), $atts));
	
	
	//echo($macme_table_content_assets);
	
	$assets = $wpdb->get_results("SELECT * FROM $macme_table_content_assets WHERE id=" . $id );


	$replacement = "";
		

	foreach($assets as $a){



	if($a->type==$MACME_LINK){
	
		$representation = "<a href='" . $a->url . "' title='" . str_replace("'", "",$title) . "'>" . __("[open link]" , "macme") . "</a>";
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
		
	} else if($a->type==$MACME_VIDEO){
	
	
	
		$representation = "";
		
		if(isset($a->url) && $a->url<>""){
		
		
		
			if(macme_string_endswith($a->url, ".mov")){
			
				$representation = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="100%" width="100%"> <param name="src" value="' . $a->url . '"> <param name="autoplay" value="true"> <param name="type" value="video/quicktime" height="100%" width="100%"> <embed src="' . $a->url . '" height="100%" width="100%" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></object>';
			
			} else {
		
				$representation = "<video src='" . $a->url . "' width='100%' height='100%' controls='controls'>";
				$representation = $representation . "Your browser does not support the video tag.";
				$representation = $representation . "</video>";
			}
			
		} else{
		
				
				if($macme_is_iphone || $macme_is_android){
				
					$embed = preg_replace('/(width)=("[^"]*")/i', 'width="310"', $a->display );
					$embed = preg_replace('/(height)=("[^"]*")/i', 'height="200"', $embed);
					$representation = $embed;
				
				
				} else if($macme_is_ipad){
					$embed = preg_replace('/(width)=("[^"]*")/i', 'width="800"', $a->display );
					$embed = preg_replace('/(height)=("[^"]*")/i', 'height="450"', $embed);
					$representation = $embed;
				
				} else {
				
					$representation = $a->display;
				}
				
				
				
				
				//$r2 = resizeEmbed( $a->display , '100%', '100%');
				//$representation  = $r2["embed"];
				
		}
		
		
		
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
	
	
	} else if($a->type==$MACME_SOUND){
	
	
		
	
		$representation = "";
		
		if(isset($a->url) && $a->url<>""){
			
			
			if(macme_string_endswith($a->url, ".mp3")){
			
				$representation = '<object width="100%" height="100%"><param name="src" value="' . $a->url . '"><param name="autoplay" value="false"><param name="controller" value="true"><param name="bgcolor" value="#FFFFFF"><embed src="' . $a->url . '" autostart="false" loop="false" width="100%" height="100%" controller="true" bgcolor="#FFFFFF"></embed></object>';
			
			} else {
		
				$representation = "<audio src='" . $a->url . "' onended='this.play();' controls='controls' autobuffer></audio>";;
		
			}
			
			
		} else{
		
			$representation = $a->display;
		}
		
		
		
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
	
	
	
	
	
	}  else if($a->type==$MACME_3D){
	
	
		$representation = "<a href='$url' title='" . str_replace("'", "",$title) . "'>" . __("[download 3D object]" , "macme") . "</a>";
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
	
	
	
	}  else if($a->type==$MACME_GOOGLE){
	
	
	
	
		$searchstring = str_replace("'"," ",$a->display);
		$representation = "<a href='http://www.google.com/search?q=$searchstring' target='_blank' title='$searchstring' class='macmegooglesearch'>[ " .  $searchstring . " ]</a>";
		$representation = $representation . "<a href='" . WP_PLUGIN_URL . "/macme/googleviz/viualize-google-search.php?s=" . $searchstring . "&t=" . $title . "' title='info visualization for searching $searchstring on Google' target='_blank'>>> <img src='" . WP_PLUGIN_URL . "/macme/google.png'" . " border='0'></a>";
		
		
		
		
		
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
		
		
	}  else if($a->type==$MACME_FLICKR){
	
	
		$searchstring = str_replace("'"," ",$a->display);
		$representation = "<a href='http://www.flickr.com/search/?q=$searchstring&w=all' target='_blank' title='$searchstring' class='macmeflickrsearch'>[ " . $searchstring  . " ]</a>";
		$representation = $representation . "<a href='" . WP_PLUGIN_URL . "/macme/googleviz/viualize-flickr-search.php?s=" . $searchstring . "&t=" . $title . "' title='info visualization for searching $searchstring on Flickr' target='_blank'>>> <img src='" . WP_PLUGIN_URL . "/macme/flickr.png'" . " border='0'></a>";
	
	
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
	
	
	
	}  else if($a->type==$MACME_LATLONG){
	
	
		$representation = "<div id='map-latlong-" . $a->lat . "-" . $a->lng . "' class='macmemap'></div>";
		$representation = $representation . "<script type='text/javascript'>initlatlong(" . $a->lat . "," . $a->lng . ",'map-latlong-" . $a->lat . "-" . $a->lng . "');</script>";
		
		
		// example to use static maps
		//$replacement = "<img src='http://maps.google.com/maps/api/staticmap?center=$lat,$long&zoom=$zoom&size=500x400&maptype=hybrid&sensor=false' border='0'>";
	
	
	
	
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "</div></div>";
		
	
	
	
	}  else if($a->type==$MACME_ADDRESS){
	
	
		//$replacement = "<h3>$address</h3><img src='http://maps.google.com/maps/api/staticmap?center=$address&zoom=$zoom&size=500x400&maptype=hybrid&sensor=false' border='0'>";
	
	
		$aa = md5( $a->address );
		$representation = "<div id='map-address-$aa' class='macmemap'></div>";
		$representation = $representation . "<script type='text/javascript'>initaddress('" . str_replace('"' , "\"" , $a->address) . "','map-address-$aa');</script>";
		
		
		$replacement=  $replacement . "<div class='macmecontent'><div class='macmeassettitle'>$title</div><div class='macmeassetcontent'>$representation</div><div class='macmeassetdescription'>" . $a->description . "(" . $a->address . ")</div></div>";
		
		
	}
	
	}

	return $replacement;
}

// FINE HANDLER DELLO SHORTCODE
// [macme title='titolo escaped' id='id di asset']





// HANDLER DELLO SHORTCODE
//[macme_xhtml]
function macme_xhtml_shortcode_handler($atts){

	$res = "";
	
	$o = get_option("macme_book_latest_XHTML_URL");
	if(isset($o) && $o<>""){
	
		$res = "<a href='" . $o . "' target='_blank'>OPEN GENERATED XHTML</a>";
	
	} else {
	
		$res = __("Sorry, no XHTML file has been generated yet. Please contact webmaster for information.","macme");
	
	}


	return $res;

}
// FINE HANDLER DELLO SHORTCODE
//[macme_xhtml]




// HANDLER DELLO SHORTCODE
//[macme_pdf]
function macme_pdf_shortcode_handler($atts){

	$res = "";
	
	$o = get_option("macme_book_latest_PDF_URL");
	if(isset($o) && $o<>""){
	
		$res = "<a href='" . $o . "' target='_blank'>OPEN GENERATED PDF</a>";
	
	} else {
	
		$res = __("Sorry, no PDF file has been generated yet. Please contact webmaster for information.","macme");
	
	}


	return $res;

}
// FINE HANDLER DELLO SHORTCODE
//[macme_pdf]



// HANDLER DELLO SHORTCODE
//[macme_epub]
function macme_epub_shortcode_handler($atts){

	$res = "";
	
	$o = get_option("macme_book_latest_EPUB_URL");
	if(isset($o) && $o<>""){
	
		$res = "<a href='" . $o . "' target='_blank'>OPEN GENERATED EPUB</a>";
	
	} else {
	
		$res = __("Sorry, no EPUB file has been generated yet. Please contact webmaster for information.","macme");
	
	}


	return $res;

}
// FINE HANDLER DELLO SHORTCODE
//[macme_epub]






//SCRIPTS E SCHERMATE DI AMMINISTRAZIONE


function enqueue_macme_scripts(){
	wp_enqueue_script('jquery', WP_PLUGIN_URL . '/macme/js/jquery.js', array() );
	wp_enqueue_script('macmesoundJS2', WP_PLUGIN_URL . '/macme/js/macme.js', array() );
	wp_enqueue_script('macmejquery', WP_PLUGIN_URL . '/macme/js/macmejquery.js', array('jquery') );
	//wp_enqueue_script('macmefileuploader', WP_PLUGIN_URL . '/macme/fileuploader.js', array('jquery') );
	wp_enqueue_script('googlemaps', 'http://maps.google.com/maps/api/js?sensor=false', array() );
	wp_enqueue_script('macmegooglemaps', WP_PLUGIN_URL . '/macme/js/macmegooglemaps.js', array('googlemaps') );
}


function enqueue_macme_stylesheet(){

		$myStyleUrl = WP_PLUGIN_URL . '/macme/macme.css';
        $myStyleFile = WP_PLUGIN_DIR . '/macme/macme.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('macmestylesheet', $myStyleUrl);
            wp_enqueue_style( 'macmestylesheet');
        }

}

function macme_admin_menu() {

  	add_options_page('MACME Configuration', 'MACME', 'manage_options', 'macme-config', 'macme_options');
	add_options_page('MACME Book Configuration', 'MACME_BOOK', 'manage_options', 'macme-book-config', 'macme_book_options');
	add_options_page('MACME Book Generation', 'MACME_BOOK_GENERATE', 'manage_options', 'macme-book-generate', 'macme_book_generate'); 
	//add_submenu_page( "Settings", "MACME Configuration", "basic settings", "manage_options", "macme-basic-settings", "macme_options");
	add_action( 'admin_init', 'register_macme_settings' );
	add_action( 'admin_print_styles', 'macme_admin_styles' );

}




   function macme_admin_styles() {
       $myStyleUrl = WP_PLUGIN_URL . '/macme/macme.css';
        $myStyleFile = WP_PLUGIN_DIR . '/macme/macme.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('macmeadminstylesheet', $myStyleUrl);
            wp_enqueue_style( 'macmeadminstylesheet');
        }
   }



function register_macme_settings() {
	//register our settings
	register_setting( 'macme-settings-group', 'macme_google_maps_api' );
	register_setting( 'macme-settings-group', 'macme_google_maps_styler' );
	register_setting( 'macme-settings-group', 'macme_book_elements' );
	register_setting( 'macme-settings-group', 'macme_book_latest_PDF_URL' );
	register_setting( 'macme-settings-group', 'macme_book_latest_XHTML_URL' );
	register_setting( 'macme-settings-group', 'macme_book_latest_EPUB_URL' );
	register_setting( 'macme-settings-group', 'macme_book_title' );
	register_setting( 'macme-settings-group', 'macme_book_identifier' );
	register_setting( 'macme-settings-group', 'macme_book_language' );
	register_setting( 'macme-settings-group', 'macme_book_description' );
	register_setting( 'macme-settings-group', 'macme_book_author' );
	register_setting( 'macme-settings-group', 'macme_book_publisher' );
	register_setting( 'macme-settings-group', 'macme_book_rights' );
	register_setting( 'macme-settings-group', 'macme_book_source_url' );
	register_setting( 'macme-settings-group', 'macme_book_css_data' );
	register_setting( 'macme-settings-group', 'macme_book_cover_image_url' );
}













function macme_book_generate(){



	if (!current_user_can('manage_options'))  {
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}
	
	
	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}
	
	
	
	?>  
  
		<div class="wrap">
	
    		<h2>MACME: Book Generation</h2>
    
    		<hr />
    
    
    		<?php
			
				if($elements==""){
	
	
					echo( __("Please use the MACME_BOOK function to configure your book before attempting to generating it." , "macme") );
					
				} else {
				
				
					?>
                    
                    
                    	<div class="macme-admin-panel">
                        	
                            <input type="button" value="GENERATE PDF" onClick="generatePDF();" />
                            <input type="button" value="GENERATE HXTML" onClick="generateXHTML();" />
                            <input type="button" value="GENERATE EPUB" onClick="generateEPUB();" />
                            
                        </div>
                        <div id="macme-generate-ajax-destination">
                        </div>
                    
                    
                    <?php
				
				
				}// else di if elements empty
			
			?>        
            
        </div>
        
   <?php
	
	


}
















function macme_book_options(){

	if (!current_user_can('manage_options'))  {
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}
	
	
	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}

	?>  
  
		<div class="wrap">
	
    		<h2>MACME: Book Configuration</h2>
    
    		<hr />
            
            <div class="macme-admin-panel">
            	<div class="macme-admin-panel-toolbar"><input type="button" value="SAVE ALL BOOK STRUCTURE" onclick="saveStructure();"  /></div>
            	<div class="macme-admin-panel-header">CMS Contents</div>
            	<div id="macme-from" class="macme-admin-panel-body">
            	<?php
				
					$args = array('numberposts'     => -1);
					$posts = get_posts($args);
				
					if($posts){
					
						foreach($posts as $p){
						
						
							$pos = strpos($elements,"_" . $p->ID . "_");
						
							if(true){//$pos===false){
							
								?>
                            
            	                <div class='macme-post-from' id="p<?php echo($p->ID); ?>">
                                	<div class='macme-post-from-title'><?php echo( $p->post_title ); ?></div>
                                    <div class='macme-post-from-tools'><input type="button" value=">>" onClick="addContent('p<?php echo($p->ID); ?>')"/></div>
                                </div>
                            
                	            <?php
							} // if pos === false	
						
						}//foreach posts
					
					}//if posts
				
				
				?>
            	</div>
            </div>
            
            <div class="macme-admin-panel">
            	<div class="macme-admin-panel-toolbar">
                	<input type="text" name="newchaptername" id="newchaptername"  />
                    <input type="button" value="(+) ADD CHAPTER" onClick="addChapter();" />
                </div>
            	<div class="macme-admin-panel-header" >Book Structure</div>
            	<div id="macme-to" class="macme-admin-panel-body">
                
                
                <?php
				
				
					$elparts = explode("@",$elements);
					
					$idchap = 0;
					
					if($elparts){
					
						foreach($elparts as $el){
						
						
							$ini = substr($el,0,1);
							$rest = substr($el,1);
							
							if($ini=="_"){
							
								// è un contenuto
								
								$pas = explode("|",$rest);
								
								echo("<div class='macme-post-from' id='p" . $pas[0] . "'><div class='macme-post-from-title'>"  . $pas[1] .  "</div><div class='macme-post-from-tools'><input type='button' value='UP' onClick=\"upChap('p"  . $pas[0] .  "');\" /><input type='button' value='DOWN' onClick=\"downChap('p"  . $pas[0] . "');\" /><input type='button' value='(X)' onClick=\"delChapter('p"  . $pas[0] .  "');\" /></div></div>");
							
							} else if ($ini=="#"){
							
								// è un capitolo
								
								$nextid = "c" . $idchap;
								
								$idchap++;
								
								echo("<div id='" . $nextid . "' class='macme-chapter'><div class='macme-chapter-name'>" . $rest . "</div><div class='macme-chapter-body'><input type='button' value='UP' onClick=\"upChap('" . $nextid . "');\" /><input type='button' value='DOWN' onClick=\"downChap('" . $nextid . "');\" /><input type='button' value='(X)' onClick=\"delChapter('" . $nextid . "');\" /></div></div>");
								
								
							
							}
						
						
						}//foreach elparts
					
					}//if elparts
				
				
				?>
                
                
                
            	</div>
            </div>
            
    
		</div>
		<form id="book-structure-form" method="post" action="options.php">
		<?php 
			//wp_nonce_field('update-options');
	 		settings_fields( 'macme-settings-group' );  
		?>
        
        	<input type="hidden" id="macme_book_elements" name="macme_book_elements" value="<?php echo get_option('macme_book_elements'); ?>" />
        	<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="macme_book_elements" />

        </form>

	<?php
}










function macme_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

?>  
  
<div class="wrap">
<img src="<?php echo( WP_PLUGIN_URL . '/macme/macme-logo-writing.png'); ?>" border="0">

<hr>

<p><b>MACME Configuration</b></p>
<form method="post" action="options.php">
<?php 
	//wp_nonce_field('update-options');
	 settings_fields( 'macme-settings-group' );  
?>

<table class="form-table">

<tr valign="top">
<th scope="row">Google Maps API Key</th>
<td><input type="text" name="macme_google_maps_api" value="<?php echo get_option('macme_google_maps_api'); ?>" /></td>
</tr>



<tr valign="top">
<th scope="row">Google Maps Styler</th>
<td><input type="text" name="macme_google_maps_styler" value="<?php echo ( str_replace('"', "'", get_option('macme_google_maps_styler') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Title</th>
<td><input type="text" name="macme_book_title" value="<?php echo ( str_replace('"', "'", get_option('macme_book_title') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Identifier (ISBN or unique URL)</th>
<td><input type="text" name="macme_book_identifier" value="<?php echo ( str_replace('"', "'", get_option('macme_book_identifier') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Language (2 char ISO code)</th>
<td><input type="text" name="macme_book_language" value="<?php echo ( str_replace('"', "'", get_option('macme_book_language') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book description</th>
<td><input type="text" name="macme_book_description" value="<?php echo ( str_replace('"', "'", get_option('macme_book_description') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Author(s)</th>
<td><input type="text" name="macme_book_author" value="<?php echo ( str_replace('"', "'", get_option('macme_book_author') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Publisher</th>
<td><input type="text" name="macme_book_publisher" value="<?php echo ( str_replace('"', "'", get_option('macme_book_publisher') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Rights (a string describing licensing)</th>
<td><input type="text" name="macme_book_rights" value="<?php echo ( str_replace('"', "'", get_option('macme_book_rights') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Book Source URL (URL for the book)</th>
<td><input type="text" name="macme_book_source_url" value="<?php echo ( str_replace('"', "'", get_option('macme_book_source_url') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">CSS used to style the book (see example at bottom)</th>
<td><input type="text" name="macme_book_css_data" value="<?php echo ( str_replace('"', "'", get_option('macme_book_css_data') ) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Cover Image URL (URL for a 800x600 pixels image used for teh book cover)</th>
<td><input type="text" name="macme_book_cover_image_url" value="<?php echo ( str_replace('"', "'", get_option('macme_book_cover_image_url') ) ); ?>" /></td>
</tr>



 
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="macme_google_maps_api,macme_google_maps_style, macme_book_title, macme_book_identifier, macme_book_language, macme_book_description, macme_book_author, macme_book_publisher, macme_book_rights, macme_book_source_url, macme_book_css_data, macme_book_cover_image_url" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>



<h2>HELP</h2>


<p>
<strong>Google Maps Styler</strong>
</p>

<p>
Google maps can now be styled. Try for example this value:<br />
<br />
<i>
{ featureType: "water", elementType: "labels", stylers: [ { visibility: "off" } ] },{ featureType: "water", elementType: "geometry", stylers: [ { saturation: -99 }, { lightness: -100 } ] },{ featureType: "landscape.man_made", elementType: "all", stylers: [ { lightness: -72 }, { saturation: -100 } ] },{ featureType: "poi", elementType: "all", stylers: [ { visibility: "off" } ] },{ featureType: "transit", elementType: "all", stylers: [ { visibility: "off" } ] },{ featureType: "administrative", elementType: "labels", stylers: [ { lightness: -63 } ] },{ featureType: "administrative.country", elementType: "geometry", stylers: [ { visibility: "off" },{ lightness: -89 } ] },{ featureType: "road", elementType: "all", stylers: [ { saturation: -100 }, { lightness: -66 } ] },{ featureType: "landscape.natural", elementType: "all", stylers: [ { saturation: -96 }, { lightness: -87 } ] }
</i>
</p>




<p>
<strong>CSS Used to style the book</strong>
</p>

<p>
You can start from this example:<br />
<br />
<i>
body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 8px;\n}\n\nh1{font: bold 24pt serif; color: #000000;}\n\nh2{font: 20pt serif; color: #000000;}\n\nh1 {\n    margin-bottom: 64px;\n}\n\nh2 {\n    margin-bottom: 16px;\n}\n\n.macme-qrcode-block{margin: 16px; }\n\n
</i>
</p>





<p>
<strong>SHORTTAGS</strong>
</p>

<p>
<i>[macme title='title of the tag' id='ID of the tag']</i>
These will be generated automatically when inserting MACME content into posts.
You can change the appearance of the rendered tags by modifying the <b>macme.css</b> file included in the plugin
</p>


<p>
<i>[macme_code idpost='ID of a post' ]</i>
You can use this tag to generate the QRCode pointing to the post whose ID is inserted into the <i>idpost</i> field.
</p>

<p>
<i>[macme_all_codes]</i>
You can use this tag to generate a matrix of all the QRCodes for your posts.
Each QRCode will have a description (the title of the post) and a link to the post (which is also the link represented by the QRCode)
</p>


<p>
<i>[macme_pdf]</i>
generates a link to the latest PDF generated by the platform
</p>


<p>
<i>[macme_epub]</i>
generates a link to the latest epub generated by the platform
</p>

<p>
<i>[macme_xhtml]</i>
generates a link to the latest xhtml generated by the platform
</p>


<p>
<i>[macme_map]</i>
generates a map to all the geographic content inserted in MACME
</p>



<p>
<i>[macme_world_map_visits]</i>
generates a map to all the people accessing MACME
</p>




</div>
  
  
  <?php

}



//SCRIPTS E SCHERMATE DI AMMINISTRAZIONE














function capture_user_source(){

	global $wpdb;

	$ip = "";
	if ( isset($_SERVER['HTTP_X_FORWARD_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$name = "";
	if($ip<>""){
		$name = GetHostByName($ip);
	}
	
	//echo("ip=$ip<br>");
	
	$dbfile = dirname(__FILE__) . "/GeoIP/GeoLiteCity.dat";
	//echo("dbfile=$dbfile<br>");
	
	$gi = geoip_open($dbfile ,GEOIP_STANDARD);
	//echo("opened<br>");
	$record = geoip_record_by_addr($gi,$ip);
	
	//print_r( $record );	
	if(isset($record) && is_array($record)){
		$lat = $record->latitude;
		$lng = $record->longitude;
		$country = $record->country_name ;
		$city = $record->city;
		$resource = curPageURL();
	
	
		$q = "INSERT INTO $macme_table_statistics_location( ip, name, t, lat, lng, country, city, resource ) values ('" . $wpdb->escape( md5( $ip ) )  . "','" . $wpdb->escape( $name )  . "',NOW(), $lat , $lng ,'" . $wpdb->escape( $country )  . "','" . $wpdb->escape( $city )  . "','" . $wpdb->escape( $resource )  . "')";
		
		//echo("q=$q<br>"); 	
		$wpdb->query($q);

	}

}




function curPageURL() {
	 $pageURL = 'http';
 	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	} else {
  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 	}
 	return $pageURL;
}























function macme_shortcode_handler_world_map_visits( $atts ){

	global $isMobile;
	$show = true;
	if(isset($isMobile)&&$isMobile==true){
		$show = false;
	}
	
	//$show = true;

	$res = ""; 
	
	if($show==true){
	
		// metto il flash
		
	
	$res = $res . "<div id='map-visitors' class='macmemap'>";
	$res= $res . "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width='480' height='280' id='worldmap' align='middle'>";
	$res= $res . "<param name='allowScriptAccess' value='sameDomain' />";
	$res= $res . "<param name='allowFullScreen' value='false' />";
	$res= $res . "<param name='movie' value='" . WP_PLUGIN_URL . "/macme/worldmap.swf' /><param name='quality' value='high' /><param name='bgcolor' value='#cccccc' />	<embed src='" . WP_PLUGIN_URL . "/macme/worldmap.swf' quality='high' bgcolor='#cccccc' width='480' height='280' name='worldmap' align='middle' allowScriptAccess='sameDomain' allowFullScreen='false' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />";
	$res= $res . "</object>";
	$res = $res . "</div>";
		
		
		
	
	} else {
	
	global $wpdb;
	
	$q = "SELECT lat,lng,country,city, count(*) c FROM macme_stat_location group by city";
	$r =  $wpdb->get_results( $q );
	
	
	
	$res = $res . "<div id='map-visitors' class='macmemap'></div>";
	$res = $res . "<script type='text/javascript'>"; 

	$res = $res . "var values = new Array();"; 
		
			foreach($r as $rr){
				
				$res = $res . "var a = new Object();";
				$res = $res . "a.c =".  $rr->c  . ";";
				$res = $res . "a.city = \"" . $rr->city . "\";";
				$res = $res . "a.country = \"" . $rr->country . "\";";
				$res = $res . "a.lat = " . $rr->lat . ";";
				$res = $res . "a.lng = " . $rr->lng . ";";
				$res = $res . "values.push( a );";

			}
		$res = $res . "initvisitors(values,'map-visitors');";
		
	$res = $res . "</script>";
	
	}//shw == true 
	
	return $res; 
}






//UTILITY FUNCTION: get XHTML string for the configured book
function macme_get_html_string_for_book(){

	$s = "";


			$s = $s . "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
			. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
			. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
			. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
			. "<head>"
			. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
			. "</head>\n";
			$s = $s . "<body>\n";

	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}
	
	if($elements==""){
	
		?>
        
        ERROR! could not generate book, as book is empty!
        
        <?php
	
	
	} else {
	
	
	
	
					$elparts = explode("@",$elements);
					
					$idchap = 0;
					
					if($elparts){
					
						foreach($elparts as $el){
						
						
							$ini = substr($el,0,1);
							$rest = substr($el,1);
							
							if($ini=="_"){
							
								// è un contenuto
								
								$pas = explode("|",$rest);
								
								
							
								$s = $s . "<h2>" . $pas[1] . "</h2>";
							
								$post = get_post( $pas[0] );
								
								if($post){
								
								
								
									$cont = $post->post_content;
									
								
									$pattern = get_shortcode_regex();
									
									
									$matches;
									
									
									$finished = false;
									
									do{
									
									
										unset($matches);
									
    									preg_match('/'.$pattern.'/s', $cont, $matches);
    							
								
									
										$finished = true;
										
										if(isset($matches) && count($matches)>2 && $matches[2]=="macme"){
											
											$finished = false;
											
											$atts = shortcode_parse_atts( $matches[3] );

											//print_r($atts);
											
											if($atts["id"]<>""){
												
												$rep = "<div class='macme-qrcode-block'><div class='macme-qrcode'><img src='" . get_macme_qrcode_for_url(  WP_PLUGIN_URL . '/macme/show.php?id=' . $atts["id"] ) . "' border='0' /></div> <div class='macme-qrcode-label'>" . $atts["title"] . "</div> </div> ";
												
												$cont = str_replace($matches[0],$rep,$cont);
											
											} else {
												$cont = str_replace($matches[0],'',$cont);
											}	
										
										
										}
										
									}while( !finished );
									
									
									$cont = do_shortcode($cont);
									
									$s = $s . "<div class='body'>" . nl2br($cont) . "</div>";
								
								
								}
							
							
							} else if ($ini=="#"){
							
								
								$s = $s . "<h1>$rest</h1>";
								
							
							}
						
						
						}//foreach elparts
					
					}//if elparts
	

	
	}
	
	
	$s = $s . "</body>\n";
	$s = $s . "</html>\n";


	return $s;


}























//UTILITY FUNCTION: generate EPUB elements
function macme_get_epub_elements(&$book,$cs){

	
	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}
	
	if($elements==""){
	
		?>
        
        ERROR! could not generate book, as book is empty!
        
        <?php
	
	
	} else {
	
	
	
	
					$elparts = explode("@",$elements);
					
					$idchap = 0;
					$seqch = 0;
					$chtit ="";
					
					$qrcodeseq = 0;
					
					if($elparts){
					
						$c = "";
					
						foreach($elparts as $el){
						
						
							//echo("...got part<br />");
						
						
							$ini = substr($el,0,1);
							$rest = substr($el,1);
							
							if($ini=="_"){
							
								// è un contenuto
								
								$pas = explode("|",$rest);
								
								
							
								$c = $c . "<h2>" . $pas[1] . "</h2>";
							
								$post = get_post( $pas[0] );
								
								if($post){
								
								
									//echo("...... got post<br />");
								
									$cont = $post->post_content;
									
								
									$pattern = get_shortcode_regex();
									
									
									$matches;
									
									$finished = false;
									
									do{
									
										//echo("...... replacing macme content<br />");
										
										unset($matches);
									
    									preg_match('/'.$pattern.'/s', $cont, $matches);
    							
								
										//print_r($matches);
										$finished = true;
										
										if(isset($matches) && count($matches)>2 && $matches[2]=="macme"){
											
											$finished = false;
											
											$atts = shortcode_parse_atts( $matches[3] );

											//print_r($atts);
											
											if($atts["id"]<>""){
											
												$urlos = get_macme_qrcode_for_url(  WP_PLUGIN_URL . '/macme/show.php?id=' . $atts["id"] );
												
												
												/*						
												$img = file_get_contents($urlos);
												
												$qrcodeseq++;
												
												$fnamePATH =  'generatedEPUB/QRCODE-' . $qrcodeseq . ".png";
												$fnameURL =  'QRCODE-' . $qrcodeseq . ".png";
											
											
												$fileHTML = fopen ($fnamePATH , "w");
												fwrite($fileHTML, $img);
												fclose ($fileHTML); 
												*/
												
												
												$rep = "<div class='macme-qrcode-block'><div class='macme-qrcode'><img src='" . $urlos . "' border='0' /></div> <div class='macme-qrcode-label'>" . $atts["title"] . "</div> </div> ";
												
												$cont = str_replace($matches[0],$rep,$cont);
											
											} else {
												$cont = str_replace($matches[0],'',$cont);
											}	
										
										
										}
										
									}while( !$finished );
									
									$cont = do_shortcode( $cont );
									
									$c = $c . "<p class='body'>" . nl2br($cont) . "</p>";
									
								
								}
							
							
							} else if ($ini=="#"){
							
								if($c<>""){
								
									$c = $c . "</html></body>";
								
									$seqch++;
									$book->addChapter($chtit, "Chapter" . str_pad($seqch ,4,"0", STR_PAD_LEFT) . ".html", $c, true, EPub::EXTERNAL_REF_ADD, "");
									
									
									$c = "";
									$chtit = "";
								
								
								}
								
								$c = $cs . "<h1>$rest</h1>";
								$chtit = $rest;
								
							
							}
						
						
						}//foreach elparts
					
					
						//ADD LAST CHAPTER
						$c = $c . "</html></body>";
								
						$seqch++;
						$book->addChapter($chtit, "Chapter" . str_pad($seqch ,4,"0", STR_PAD_LEFT) . ".html", $c);
						
					
					
					}//if elparts
	

	
	}
	
	



}





// UTILITY FUNCTION: check wether a string ends with another string
function macme_string_endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, -$testlen) === 0;
}


// UTILITY FUNCTION: generates URL for QRCode image representing passed url
function get_macme_qrcode_for_url( $l , $w=150, $h=150){
	$res = "https://chart.googleapis.com/chart?chs=" . $w . "x" . $h . "&cht=qr&chl=" . $l . "&choe=UTF-8";
	return $res;
}




// UTILITY FUNCTION: maintain only allowed tags in a HTML string
function real_strip_tags($i_html, $i_allowedtags = array(), $i_trimtext = FALSE) {
	if (!is_array($i_allowedtags)) $i_allowedtags = !empty($i_allowedtags) ? array($i_allowedtags) : array();
	$tags = implode('|', $i_allowedtags);
	if (empty($tags)) $tags = '[a-z]+';
	preg_match_all('@</?\s*(' . $tags . ')(\s+[a-z_]+=(\'[^\']+\'|"[^"]+"))*\s*/?>@i', $i_html, $matches);
	$full_tags = $matches[0];
	$tag_names = $matches[1];
	foreach ($full_tags as $i => $full_tag) {
		if (!in_array($tag_names[$i], $i_allowedtags)) if ($i_trimtext) unset($full_tags[$i]); else $i_html = str_replace($full_tag, '', $i_html);
	}
	return $i_trimtext ? implode('', $full_tags) : $i_html;
}


// UTILITY FUNCTION: resize embed
function resizeEmbed($video,$new_width='100%',$new_height='100%') {
	echo("v0" . $video);
	$video = real_strip_tags($video,array('iframe','embed','param','object'),true);
	
	echo("v1" . $video);
	
	preg_match("/width=\"([^\"]*)\"/i",$video,$w); $w = (integer)$w[1];
	preg_match("/height=\"([^\"]*)\"/i",$video,$h); $h = (integer)$h[1];
        if (!$new_width) $new_width = $w;
	$w2 = $new_width;
	//$ratio = (float)($w2/$w);
	$h2 = $new_height;//(integer)($h * $ratio);
	$video = str_replace("width=\"$w\"","width=\"$w2\"",$video);
	echo("v2" . $video);
	$video = str_replace("height=\"$h\"","height=\"$h2\"",$video);
	echo("v3" . $video);
	return array("embed"=>$video,"w"=>$w2,"h"=>$h2,"w0"=>$w,"h0"=>$h);
}



?>