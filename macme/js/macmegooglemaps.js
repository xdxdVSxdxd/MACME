var geocoders = new Array();
var mapgeocoders = new Array();


function initlatlong(lat,lon,id) {
    var latlng = new google.maps.LatLng(lat, lon);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeControl: false,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var map = new google.maps.Map(document.getElementById(id),  myOptions);
    image = "http://google-maps-icons.googlecode.com/files/friends.png";
	var macmemarker = new google.maps.Marker({
      position: latlng,
      map: map,
      icon: image
  });

  }


function initvisitors(values,id) {
    var latlng = new google.maps.LatLng(0, 0);
    var myOptions = {
      zoom: 1,
      center: latlng,
      mapTypeControl: false,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var map = new google.maps.Map(document.getElementById(id),  myOptions);
    
    var totale = 0;
    for(var i=0; i<values.length; i++){
    	var v = values[i];
    	totale = totale + v.c;
    }
    
    var macmemarker = new Array();
    
    for(var i=0; i<values.length; i++){
    	var v = values[i];
    	var latlng2 = new google.maps.LatLng(v.lat, v.lng);
    	var valore = 100*v.c/totale;
    	var vs = valore.toFixed(2);
    	var icona = Math.round( valore );
    	var desi = icona;
    	if(icona<10){ desi = "0" + desi; }
    	
    	var iconaurl = "http://google-maps-icons.googlecode.com/files/black" + desi + ".png";
    	
    	var contentString = v.city + "(" + v.country + ") " + vs + "%[" + v.c + " visite]";
    	
		macmemarker[i] = new google.maps.Marker({
      		position: latlng2,
      		map: map,
      		icon: iconaurl,
      		title: contentString
  		});
  		
  		
	}
}



function initaddress(address,id){
	
	var gc = new GeoLocate();
	gc.init(address,id);

}



function GeoLocate(){

var mapi; 
var geocoderi;

this.process = function(results,status){
    	if (status == google.maps.GeocoderStatus.OK) {
    		//alert(results[0].geometry.location);
        	this.mapi.setCenter(results[0].geometry.location);
        	image = "http://google-maps-icons.googlecode.com/files/friends.png";
        	var marker = new google.maps.Marker({
            	map: this.mapi, 
            	position: results[0].geometry.location,
            	icon: image
        	});
      	} else {
        	alert("Geocode was not successful for the following reason: " + status);
      	}
}//process


this.init = function (address,id){

	this.geocoderi = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(0, 0);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeControl: false,
      mapTypeId: google.maps.MapTypeId.HYBRID
    }
    this.mapi = new google.maps.Map(document.getElementById(id), myOptions);
    
    var c;
    with( {p : this} ){
    	c = function(results,status){ p.process(results,status); };
    }
    
    this.geocoderi.geocode( { 'address': address}, c );
}//init


}//GeoLocate