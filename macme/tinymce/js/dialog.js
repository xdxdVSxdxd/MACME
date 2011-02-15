String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}


tinyMCEPopup.requireLangPack();

var MacmeDialog = {
	init : function() {
		//var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		// f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		// f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');

		document.getElementById("linkinputs").style.visibility = 'hidden';
		document.getElementById("videoinputs").style.visibility = 'hidden';
		document.getElementById("videoembedinputs").style.visibility = 'hidden';
		document.getElementById("soundinputs").style.visibility = 'hidden';
		document.getElementById("3Dinputs").style.visibility = 'hidden';
		document.getElementById("googleinputs").style.visibility = 'hidden';
		document.getElementById("flickrinputs").style.visibility = 'hidden';
		document.getElementById("latlonginputs").style.visibility = 'hidden';
		document.getElementById("addressinputs").style.visibility = 'hidden';
		
		document.getElementById("linkinputs").style.display = "none";
		document.getElementById("videoinputs").style.display = "none";
		document.getElementById("videoembedinputs").style.display = "none";
		document.getElementById("soundinputs").style.display = "none";
		document.getElementById("3Dinputs").style.display = "none";
		document.getElementById("googleinputs").style.display = "none";
		document.getElementById("flickrinputs").style.display = "none";
		document.getElementById("latlonginputs").style.display = "none";
		document.getElementById("addressinputs").style.display = "none";	
		
	},
	
	getTypeValue: function(){
	
		var quale = document.getElementById("macmetype").options[  document.getElementById("macmetype").selectedIndex ].value;
		
		jQuery("#macme-upload-asset-url").val("");
		
		document.getElementById("linkinputs").style.visibility = 'hidden';
		document.getElementById("videoinputs").style.visibility = 'hidden';
		document.getElementById("videoembedinputs").style.visibility = 'hidden';
		document.getElementById("soundinputs").style.visibility = 'hidden';
		document.getElementById("3Dinputs").style.visibility = 'hidden';
		document.getElementById("googleinputs").style.visibility = 'hidden';
		document.getElementById("flickrinputs").style.visibility = 'hidden';
		document.getElementById("latlonginputs").style.visibility = 'hidden';
		document.getElementById("addressinputs").style.visibility = 'hidden';
		
		document.getElementById("linkinputs").style.display = "none";
		document.getElementById("videoinputs").style.display = "none";
		document.getElementById("videoembedinputs").style.display = "none";
		document.getElementById("soundinputs").style.display = "none";
		document.getElementById("3Dinputs").style.display = "none";
		document.getElementById("googleinputs").style.display = "none";
		document.getElementById("flickrinputs").style.display = "none";
		document.getElementById("latlonginputs").style.display = "none";
		document.getElementById("addressinputs").style.display = "none";	
		
		if(quale=="0"){
			document.getElementById("linkinputs").style.visibility = 'visible';
			document.getElementById("linkinputs").style.display = "block";
		} else if(quale=="1"){
			document.getElementById("videoinputs").style.visibility = 'visible';
			document.getElementById("videoinputs").style.display = "block";
		} else if(quale=="1bis"){
			document.getElementById("videoembedinputs").style.visibility = 'visible';
			document.getElementById("videoembedinputs").style.display = "block";
		} else if(quale=="2"){
			document.getElementById("soundinputs").style.visibility = 'visible';
			document.getElementById("soundinputs").style.display = "block";
		} else if(quale=="3"){
			document.getElementById("3Dinputs").style.visibility = 'visible';
			document.getElementById("3Dinputs").style.display = "block";
		} else if(quale=="4"){
			document.getElementById("googleinputs").style.visibility = 'visible';
			document.getElementById("googleinputs").style.display = "block";
		} else if(quale=="5"){
			document.getElementById("flickrinputs").style.visibility = 'visible';
			document.getElementById("flickrinputs").style.display = "block";
		} else if(quale=="6"){
			document.getElementById("latlonginputs").style.visibility = 'visible';
			document.getElementById("latlonginputs").style.display = "block";
		} else if(quale=="7"){
			document.getElementById("addressinputs").style.visibility = 'visible';
			document.getElementById("addressinputs").style.display = "block";
		}
	}, 
	
	insert : function() {
		// Insert the contents from the input into the document
		// tinyMCEPopup.editor.execCommand('mceInsertContent', false, document.forms[0].someval.value);

		var quale = document.getElementById("macmetype").options[  document.getElementById("macmetype").selectedIndex ].value;
		var shortcode = "";
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

		
		var id = "";
		var title = "";
		var description = "";
		var url = "";
		var type = "";
		var display = "";
		var qr_url = "";
		var fiducial_url = "";
		var lat = "";
		var lng = "";
		var address = "";
		

		
		if(quale=="0"){
			
			title = jQuery("#macme-link-title").val();
			description = jQuery("#macme-link-description").val();
			url = jQuery("#macme-link-url").val();
			
		} else if(quale=="1"){
			
			title = jQuery("#macme-video-title").val();
			description = jQuery("#macme-video-description").val();
			url = jQuery("#macme-upload-asset-url").val();
		
		} else if(quale=="1bis"){
			
			title = jQuery("#macme-videoembed-title").val();
			description = jQuery("#macme-videoembed-description").val();
			display = jQuery("#macme-videoembed-embed").val();
			
		} else if(quale=="2"){
		
			title = jQuery("#macme-sound-title").val();
			description = jQuery("#macme-sound-description").val();
			url = jQuery("#macme-upload-asset-url").val();
			
		} else if(quale=="3"){
		
			title = jQuery("#macme-3D-title").val();
			description = jQuery("#macme-3D-description").val();
			url = jQuery("#macme-upload-asset-url").val();
			
		} else if(quale=="4"){
			
			title = jQuery("#macme-google-title").val();
			description = jQuery("#macme-google-description").val();
			display = jQuery("#macme-google-terms").val();
			
		} else if(quale=="5"){
			
			title = jQuery("#macme-flickr-title").val();
			description = jQuery("#macme-flickr-description").val();
			display = jQuery("#macme-flickr-terms").val();
			
			
		} else if(quale=="6"){
			
			title = jQuery("#macme-latlng-title").val();
			description = jQuery("#macme-latlng-description").val();
			lat = jQuery("#macme-latlng-lat").val();
			lng = jQuery("#macme-latlng-lng").val();
			
			
		} else if(quale=="7"){
			
			title = jQuery("#macme-address-title").val();
			description = jQuery("#macme-address-description").val();
			address = jQuery("#macme-address-string").val();
			
			
		}
		
		
		
		
		
		jQuery("div#loaddestination").load("update.php",{
					
				title: title,
				description: description,
				url: url,
				type: quale,
				display: display,
				lat: lat,
				lng: lng,
				address: address
					
			}, function(response, status, xhr ){
				
				if(response=="ERROR"){
					alert(response);	
				} else {
					
					
					shortcode = response;
					
					if( shortcode.trim()!="" ){
						tinyMCEPopup.editor.execCommand('mceInsertContent', false, shortcode);
						tinyMCEPopup.close();
 					}
					
					
					
				}		
			}
		);
		
		
		
		
				
		
	}
};

tinyMCEPopup.onInit.add(MacmeDialog.init, MacmeDialog);
