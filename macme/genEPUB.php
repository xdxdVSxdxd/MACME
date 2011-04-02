<?php

	require_once"../../../wp-config.php";




	//echo(".... started epub generation<br />");

	global $wpdb;


	$macme_table_statistics_data = $wpdb->prefix ."macme_stat_data";
	$macme_table_statistics_location = $wpdb->prefix . "macme_stat_location";
	$macme_table_content_assets = $wpdb->prefix . "macme_content_assets";





	$elements = get_option("macme_book_elements");
	
	if(!$elements){
		$elements = "";
	}
	
	if($elements==""){
	
		//echo(".... book index has not been created.<br />");
	
		?>
        
        ERROR! could not generate book, as book is empty!
        
        <?php
	
	
	} else {
	
	
	
		$unico = "macme_" . md5( uniqid() );
	
	
		//echo(".... book index has been created, using uid=" . $unico . "<br />");
	
		$fileDir = ABSPATH . 'wp-content/plugins/macme/generatedEPUB/';

		include_once("EPub.php");

		$book = new EPub();

		//echo(".... initalized EPUB class<br />");
	
	
		$book->setTitle("A Book made with MACME");
		if(get_option('macme_book_title') && get_option('macme_book_title')<>""){
			$book->setTitle(get_option('macme_book_title'));
		}
		
		
		$book->setIdentifier("http://www.fakepress.it/books/ABook.html", EPub::IDENTIFIER_URI); // Could also be the ISBN number, prefered for published books, or a UUID.
		if(get_option('macme_book_identifier') && get_option('macme_book_identifier')<>""){
			$book->setIdentifier(get_option('macme_book_identifier'), EPub::IDENTIFIER_URI);
		}
		
		
		
		$book->setLanguage("en"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
		if(get_option('macme_book_language') && get_option('macme_book_language')<>""){
			$book->setLanguage(get_option('macme_book_language'));
		}
		
		
		
		$book->setDescription("This book was made using MACME");
		if(get_option('macme_book_description') && get_option('macme_book_description')<>""){
			$book->setDescription(get_option('macme_book_description'));
		}
		
		
		
		$book->setAuthor("A Happy MACME user", "Happy, MACME User");
		if(get_option('macme_book_author') && get_option('macme_book_author')<>""){
			$book->setAuthor(get_option('macme_book_author'),get_option('macme_book_author'));
		}
		
		
		
		$book->setPublisher("http://www.fakepress.it", "http://www.fakepress.it"); // I hope this is a non existant address :) 
		if(get_option('macme_book_publisher') && get_option('macme_book_publisher')<>""){
			$book->setPublisher(get_option('macme_book_publisher'),get_option('macme_book_publisher'));
		}
		
		
		
		$book->setDate(time()); // Strictly not needed as the book date defaults to time().
		
		$book->setRights("Licensed under GPL3."); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
		if(get_option('macme_book_rights') && get_option('macme_book_rights')<>""){
			$book->setRights(get_option('macme_book_rights'));
		}
		
		
		$book->setSourceURL("http://www.fakepress.it/books/ABook.html");
		if(get_option('macme_book_source_url') && get_option('macme_book_source_url')<>""){
			$book->setSourceURL(get_option('macme_book_source_url'));
		}
		
		
		$cssData = "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 8px;\n}\n\nh1{font: bold 24pt serif; color: #000000;}\n\nh2{font: 20pt serif; color: #000000;}\n\nh1 {\n    page-break-before: always; \nmargin-bottom: 64px;\n}\n\nh2 {\n    page-break-before: always; \nmargin-bottom: 16px;\n}\n\n.macme-qrcode-block{margin: 16px; }";
		if(get_option('macme_book_css_data') && get_option('macme_book_css_data')<>""){
			$cssData = (get_option('macme_book_css_data'));
		}
		
		

		$book->addCSSFile("styles.css", "css1", $cssData);


		//echo(".... added styles<br />");
	
		$book->setCoverImage("Cover.jpg", file_get_contents(ABSPATH . 'wp-content/plugins/macme/epub-cover.jpg'), "image/jpeg");
		//$book->setCoverImage(ABSPATH . 'wp-content/plugins/macme/epub–cover.jpg');
		if(get_option('macme_book_cover_image_url') && get_option('macme_book_cover_image_url')<>""){
			$book->setCoverImage("Cover.jpg", file_get_contents(get_option('macme_book_cover_image_url')), "image/jpeg");
		}
		
		//echo(".... added cover image<br />");
		
		$booktitle = "A Book made with MACME";
		if(get_option('macme_book_title') && get_option('macme_book_title')<>""){
			$booktitle  = get_option('macme_book_title');
		}
		
		$bookauthor = "A Happy MACME User";
		if(get_option('macme_book_author') && get_option('macme_book_author')<>""){
			$bookauthor  = get_option('macme_book_author');
		}
		

		$content_start =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
			. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
			. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
			. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
			. "<head>"
			. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
			. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
			. "<title>$booktitle</title>\n"
			. "</head>\n"
			. "<body>\n";
	
		
		
		$cover = $content_start . "<h1>$booktitle</h1>\n<h2>$bookauthor</h2>\n"
			. "</body>\n</html>\n";
		$book->addChapter("Notices", "Cover.html", $cover);
		
		//echo("<br /> finished initializing...");
		
		
		macme_get_epub_elements($book,$content_start);	
	
	
		//echo("<br /> got book parts...");
		
		
		$book->finalize();
		
		//echo("<br /> book finalized...");
	
		$s = $book->getBook();
	
		$fnameURL =  WP_PLUGIN_URL . '/macme/generatedEPUB/' . $unico . ".epub";
		$fnamePATH =  ABSPATH . 'wp-content/plugins/macme/generatedEPUB/' . $unico . ".epub";
	
	
		
		$fileHTML = fopen ($fnamePATH , "w");
		fwrite($fileHTML, $s);
		fclose ($fileHTML); 
		
		//echo("<br /> book saved...");
		
		
		update_option("macme_book_latest_XHTML_URL",$fnameURL);
		
		echo("<a href='" . $fnameURL . "' target='_blank'>OPEN GENERATED EPUB</a><br />");
		
	
		//echo("<br /> generation finished...");
	
	}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
