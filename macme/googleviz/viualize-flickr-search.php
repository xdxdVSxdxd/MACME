<?php

	require_once"../../../../wp-config.php";

	

	$s = "";
	if( isset($_POST["s"])  ){
		$s = $_POST["s"];
	} else if( isset($_GET["s"])  ){
		$s = $_GET["s"];
	}
	
	$t = "";
	if( isset($_POST["t"])  ){
		$t = $_POST["t"];
	} else if( isset($_GET["t"])  ){
		$t = $_GET["t"];
	}
	
	$numresults = 0;
?>	
<html>
	<head>
		<title>MACME - searching google for: "<?php echo( $s ); ?>"</title>
		<link rel="stylesheet" type="text/css" media="all" href="style.css" />
		<script type='text/javascript' src='../js/jquery.js'></script>
		<script type="text/javascript">
		
			var W;
			var H;
			
			var co = new Array();
			var inc = 0.002;
			
			var percerchio = 10;
			
			var hbarra;
			
			var canvasContainer;
				var myCanvas;
				
				var canvasContainer2;
				var myCanvas2;
		
			$(document).ready(function(){
			
				W = $("body").width();
				H = $("body").height();
				
				hbarra = H/4;
				
				$.each(  $("div.result"), function(i,l){
					
					var startA = Math.random()*2.0*Math.PI;					
					co[i] = startA;
					
					$( l ).css("top", "" + (Math.sin( co[i] )*H/2 + H/2) + "px");
					$( l ).css("left", "" + (Math.cos( co[i] )*W/2 + W/2) + "px");
					
					
				} );			
			
   				$("div.result").click(function(){
   					$("div.result-title").hide();
					$("div.result-content").hide();
					$("div.result-link").hide();
   					$(this).children().show();
   				});
   				
				
				
				canvasContainer = document.createElement('div');
				document.body.appendChild(canvasContainer);
				canvasContainer.style.position="absolute";
				canvasContainer.style.left="0px";
				canvasContainer.style.top="0px";
				canvasContainer.style.width="100%";
				canvasContainer.style.height="100%";
				canvasContainer.style.zIndex="500";
				myCanvas = document.createElement('canvas');
				myCanvas.style.width = canvasContainer.scrollWidth+"px";
				myCanvas.style.height = canvasContainer.scrollHeight+"px";
				myCanvas.width=canvasContainer.scrollWidth;
				myCanvas.height=canvasContainer.scrollHeight;
				myCanvas.style.overflow = 'visible';
				myCanvas.style.position = 'absolute';
				canvasContainer.appendChild(myCanvas);
				
				
				canvasContainer2 = document.createElement('div');
				document.body.appendChild(canvasContainer2);
				canvasContainer2.style.position="absolute";
				canvasContainer2.style.left="0px";
				canvasContainer2.style.top="0px";
				canvasContainer2.style.width="100%";
				canvasContainer2.style.height="100%";
				canvasContainer2.style.zIndex="200";
				myCanvas2 = document.createElement('canvas');
				myCanvas2.style.width = canvasContainer2.scrollWidth+"px";
				myCanvas2.style.height = canvasContainer2.scrollHeight+"px";
				myCanvas2.width=canvasContainer2.scrollWidth;
				myCanvas2.height=canvasContainer2.scrollHeight;
				myCanvas2.style.overflow = 'visible';
				myCanvas2.style.position = 'absolute';
				canvasContainer2.appendChild(myCanvas2);
				
				
				initstats();

   				loopa();
   				
			 });
			 
			 
			 function loopa(){

					var ctx2 = myCanvas.getContext("2d");
					ctx2.clearRect(0,0,W,H);
					
					$.each($("div.result"),function(i,l){
						
						co[i] = co[i] + inc;
						
						var qualeraggio = Math.floor(i/percerchio);
						
						var rW = W/2 - 50 - qualeraggio*70;
						var rH = H/2 - 50 - qualeraggio*70;

						$( l ).css("top", "" + (Math.sin( co[i] )*rH + H/2 ) + "px");
						$( l ).css("left", "" + (Math.cos( co[i] )*rW + W/2 ) + "px");
						
						var cOffset = $( l ).offset();	
						
						
						
            
                		var w2 = $( l ).width()/2;
                		var h2 = $( l ).height()/2;
                		
                		
                		ctx2.beginPath();
                		ctx2.lineWidth = 1;
                		ctx2.strokeStyle = "rgb(180,180,180)";
                		ctx2.moveTo(W/2,H/2);
                		ctx2.lineTo(cOffset.left +10 ,cOffset.top + 10);
						ctx2.stroke();												
					});
					setTimeout("loopa()", 50);
			 
			 }
			 
		</script>
	</head>
	<body>
		<div id="resultsdiv">
			<?php 
				$url = "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=8&" . "q=" . urlencode( $s )  . "%20site:flickr.com&userip=" . $_SERVER['HTTP_HOST'];
				//$url = "http://boss.yahooapis.com/ysearch/ysearch/v1/" . urlencode($s) . "?appid=aOI2roXV34G_vz6frtxWRAT7a8r7JLvyQcWmtoF8EcfXmRWgPTiKmd1dUIby6IXMrvnY";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, "" . $_SERVER['HTTP_HOST'] . "/macme/wp-content/plugins/macme/viualize-google-search.php" );
				$body = curl_exec($ch);
				curl_close($ch);
				$json = json_decode($body);
				$numpages = count( $json->responseData->cursor->pages );
				$numresults =  $json->responseData->cursor->estimatedResultCount ;
				
				$toupd = $wpdb->get_results("select TIMESTAMP(NOW()) as now, TIMESTAMP(max(t)) as maxo, (TIMESTAMP(NOW())-TIMESTAMP(max(t)) ) as delta from macme_stat_data where resource='" . md5($s) . "' ");
				$updateneeded = false;
				if( count($toupd)==0){
					$updateneeded = true;
				} else {
					$delta = $toupd[0]->delta;
					$now = $toupd[0]->now;
					$maxo = $toupd[0]->maxo;
					//echo("delta=$delta<br>now=$now<br>max=$maxo<br><br>");
					if( !isset($delta) || !isset($maxo) || $delta>4000 ){
						$updateneeded = true;
					}
				}
				
				//$updateneeded = true;
				
				
				if($updateneeded == true ){
					$updated = $wpdb->query( "insert into macme_stat_data(resource,t,count) values('" .  md5($s) . "',NOW(),$numresults )" );
				}
								
				$stats = $wpdb->get_results("select count from macme_stat_data where resource='" . md5($s) . "' order by t asc");
				
				//print_r( $stats );
				
				//echo("numpages=$numpages;numresults=$numresults");
				$results = $json->responseData->results;
			?>
			<?php
				if( count( $results)>0  ){
					foreach ($results as $r){
					?>
						<div class="result">
							<div class="crosshair"></div>
							<div class="result-title"><a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank"><?php  echo( $r->title ); ?></a></div>
							<div class="result-content"><a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank"><?php  echo( strip_tags($r->content) ); ?></a></div>
							<div class="result-link">
								<a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank">>></a>
							</div>
						</div>
					<?php
					}
				}
				for($i = 1; $i<$numpages; $i++){
					$quale = $json->responseData->cursor->pages[ $i ]->start;
					
					$url = "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=8&start=$quale&" . "q=" . urlencode( $s )  . "%20site:flickr.com&userip=" . $_SERVER['HTTP_HOST'];
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_REFERER, "" . $_SERVER['HTTP_HOST'] . "/macme/wp-content/plugins/macme/viualize-google-search.php" );
					$body = curl_exec($ch);
					curl_close($ch);
					$json2 = json_decode($body);
					$results = $json2->responseData->results;
					foreach ($results as $r){
						?>
							<div class="result">
								<div class="crosshair"></div>
								<div class="result-title"><a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank"><?php  echo( $r->title ); ?></a></div>
								<div class="result-content"><a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank"><?php  echo( strip_tags($r->content) ); ?></a></div>
								<div class="result-link">
									<a href="<?php  echo( $r->unescapedUrl ); ?>" title="<?php  echo( $r->titleNoFormatting ); ?>" target="_blank">>></a>
								</div>
							</div>
						<?php
					}
						
				}
			?>
		</div>
		<div id="title-results"><?php echo( $t ); ?></div>
		<div id="number-results"><?php echo( $numresults ); ?></div>
		<?php
			//print_r( $json   ); 
			
			
			$maxstats = -999999999;
			$minstats = 9999999999;
			foreach($stats as $st){
			
				if($st->count > $maxstats ) {  $maxstats = $st->count; }
				if($st->count < $minstats ) {  $minstats = $st->count; }
				
			}
			
			$ds = $maxstats - $minstats;
			if($ds==0){ $ds = 0.00001; }
			
			?>
			<script type="text/javascript">

				function initstats(){
						var ctx3 = myCanvas2.getContext("2d");
						ctx3.clearRect(0,0,W,H);
						
						var ds = (hbarra -20)/ <?php echo( $ds ); ?>;
						var dx = W / <?php echo( count( $stats) ); ?>;
						var px = dx;

						ctx3.beginPath();
                		ctx3.lineWidth = 1;
                		ctx3.strokeStyle = "rgb(255,255,0)";
                		ctx3.moveTo(0,H);
                		
                		<?php
                			foreach($stats as $st){
                		?>
                		
                		
                				ctx3.lineTo( px,  H- <?php echo(  ($st->count-$minstats  ) ); ?>*ds -20 );
								px = px + dx;
						
						<?php
								
                			}//foreach
                		?>
						
						ctx3.stroke();				
				}
				
			
			</script>
			<?php 
			
		?>
	</body>
</html>