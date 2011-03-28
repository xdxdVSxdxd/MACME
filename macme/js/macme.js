function addChapter(){
	
	var chname = jQuery("input#newchaptername").val();
	
	if(!chname || chname==""){
		alert("Please insert Chapter Name to add Chapter!");	
	} else {
	
		var chaps = jQuery("div.macme-chapter");
		var nextid = "c" + 0;
		if(chaps && chaps.length>0){
			
			var lastobj = chaps[ (chaps.length - 1)];
			var lastid = jQuery(lastobj).attr("id");
			var idd = parseInt( lastid.substring(1) );
			idd++;
			nextid = "c" + idd;
		
		}
	
		jQuery("div#macme-to").append("<div id='" + nextid + "' class='macme-chapter'><div class='macme-chapter-name'>" + chname + "</div><div class='macme-chapter-body'><input type='button' value='UP' onClick=\"upChap('" + nextid + "');\" /><input type='button' value='DOWN' onClick=\"downChap('" + nextid + "');\" /><input type='button' value='(X)' onClick=\"delChapter('" + nextid + "');\" /></div></div>");
		
		jQuery("input#newchaptername").val("");
		
	}//else di if chname empty

}//addChapter


function delChapter(idchap){

	jQuery("div#macme-to div#" + idchap).remove();

}//delChapter



function upChap(idchap){

	var p = jQuery("div#macme-to div#" + idchap);
	
	if(  jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").length>1 ){
	
	jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").each(function(index){
	
		if(jQuery(this).attr("id")==jQuery(p).attr("id")){
		
			//alert("index=" + index );
		
			var pp = jQuery(p).detach();
			var idx1 = index-1;
			
			//alert("idx1=" + idx1);
			
			if(idx1<0){ idx1=0; }
			
			var pq = jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from")[idx1];
			
			//alert("id di prima=" + jQuery(pq).attr("id") );
			
			jQuery(pp).insertBefore(  pq  );
		
		}
										 
	});
	
	}//if length ==1
	
}//upChap








function downChap(idchap){

	var p = jQuery("div#macme-to div#" + idchap);
	
	if(  jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").length>1 ){
	
	jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").each(function(index){
	
		if(jQuery(this).attr("id")==jQuery(p).attr("id")){
		
			//alert("index=" + index );
		
			var pp = jQuery(p).detach();
			var idx1 = index;
			
			//alert("idx1=" + idx1);
			
			if(idx1>(jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").length - 1) ){ idx1=(jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").length - 1); }
			
			var pq = jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from")[idx1];
			
			//alert("id di prima=" + jQuery(pq).attr("id") );
			
			jQuery(pp).insertAfter(  pq  );
		
		}
										 
	});
	
	}//if length ==1
	
}//downChap

function addContent(idcont){
	var titolo = jQuery("div#" + idcont + " div.macme-post-from-title").html();
	//alert(titolo);
	if( jQuery("div#macme-to div#" + idcont).length==0 ){
		jQuery("div#macme-to").append("<div class='macme-post-from' id='" + idcont + "'><div class='macme-post-from-title'>" + titolo + "</div><div class='macme-post-from-tools'><input type='button' value='UP' onClick=\"upChap('" + idcont + "');\" /><input type='button' value='DOWN' onClick=\"downChap('" + idcont + "');\" /><input type='button' value='(X)' onClick=\"delChapter('" + idcont + "');\" /></div></div>");
	} else {
		alert("the content is already part of the book!");	
	}

}


function saveStructure(){
	
	var s = "";
	
	jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").each(function(index){
	
		var id = jQuery(this).attr("id");
		
		if(id.charAt(0)=="c"){
			var titolo = jQuery(this).children("div.macme-chapter-name").html();
			s = s + "@" + "#" + titolo + "";
			
		} else if(id.charAt(0)=="p"){
			
			var titolo = jQuery(this).children("div.macme-post-from-title").html();
			var idpost = id.substring(1);
			s = s + "@" +  "_" + idpost + "|" + titolo + "";
			
			
		}
	
	
		if(index==(jQuery("div#macme-to div.macme-chapter, div#macme-to div.macme-post-from").length-1) ){
		
			//alert(s);
			jQuery("input#macme_book_elements").val(s);
			jQuery("form#book-structure-form").submit();
		
		}
	
	
	});
	
	
}








function generatePDF(){
	
	jQuery("div#macme-generate-ajax-destination").html("please wait... generating PDF");
	jQuery("div#macme-generate-ajax-destination").load("../wp-content/plugins/macme/genPDF.php");
	
}




function generateXHTML(){
	jQuery("div#macme-generate-ajax-destination").html("please wait... generating XHTML");
	jQuery("div#macme-generate-ajax-destination").load("../wp-content/plugins/macme/genHTML.php");
}


function generateEPUB(){
	jQuery("div#macme-generate-ajax-destination").html("please wait... generating XHTML");
	jQuery("div#macme-generate-ajax-destination").load("../wp-content/plugins/macme/genEPUB.php");
}