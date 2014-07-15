Object.keys = Object.keys || function(o) { 
    var result = []; 
    for(var name in o) { 
        if (o.hasOwnProperty(name)) 
          result.push(name); 
    } 
    return result; 
};

jQuery(document).ready(function($){
	
	var animationDelay = 0; 
	var enableAnimation, extraColor, greyscale, enableZoom, markerImg, centerlng, centerlat, zoomLevel, latLng, infoWindows;
	var map = [];
	var infoWindows = [];
	
	for(var i = 0; i < $('.nectar-google-map').length; i++) {
		 infoWindows[i] = [];
	}

	$('.nectar-google-map').each(function(i){
			
		//map margin if page header
		if( $('#page-header-bg:not("[data-parallax=1]")').length > 0 ) { $('#contact-map').css('margin-top', 0);  $('.container-wrap').css('padding-top', 0);} 
		if( $('#page-header-bg[data-parallax=1]').length > 0 ) $('#contact-map').css('margin-top', '-30px');
		
	    zoomLevel = parseFloat($(this).attr('data-zoom-level'));
	    centerlat = parseFloat($(this).attr('data-center-lat'));
		centerlng = parseFloat($(this).attr('data-center-lng'));
		markerImg = $(this).attr('data-marker-img');
		enableZoom = $(this).attr('data-enable-zoom');
		greyscale = $(this).attr('data-greyscale');
		extraColor = $(this).attr('data-extra-color');
		enableAnimation = $(this).attr('data-enable-animation');
		
		if( isNaN(zoomLevel) ) { zoomLevel = 12;}
		if( isNaN(centerlat) ) { centerlat = 51.47;}
		if( isNaN(centerlng) ) { centerlng = -0.268199;}
		if( typeof enableAnimation != 'undefined' && enableAnimation == 1 && $(window).width() > 690) { animationDelay = 180; enableAnimation = google.maps.Animation.BOUNCE } else { enableAnimation = null; }
	
	    latLng = new google.maps.LatLng(centerlat,centerlng);
	    
	    //color
	    if(greyscale == '1' && extraColor.length > 0) {
		    styles = [
		    
		    {
				featureType: "poi",
				elementType: "labels",
				stylers: [{
					visibility: "off"
				}]
			}, 
			{ 
				featureType: "road.local", 
				elementType: "labels.icon", 
				stylers: [{ 
					"visibility": "off" 
				}] 
			},
			{ 
				featureType: "road.arterial", 
				elementType: "labels.icon", 
				stylers: [{ 
					"visibility": "off" 
				}] 
			},
			{
				featureType: "road",
				elementType: "geometry.stroke",
				stylers: [{
					visibility: "off"
				}]
			}, 
			{ 
				featureType: "transit", 
				elementType: "geometry.fill", 
				stylers: [
					{ hue: extraColor },
					{ visibility: "on" }, 
					{ lightness: 1 }, 
					{ saturation: 7 }
				]
			},
			{
				elementType: "labels",
				stylers: [{
				saturation: -100
				}]
			}, 
			{
				featureType: "poi",
				elementType: "geometry.fill",
				stylers: [
					{ hue: extraColor },
					{ visibility: "on" }, 
					{ lightness: 20 }, 
					{ saturation: 7 }
				]
			},
			{
				featureType: "landscape",
				stylers: [
					{ hue: extraColor },
					{ visibility: "on" }, 
					{ lightness: 20 }, 
					{ saturation: 20 }
				]
				
			}, 
			{
				featureType: "road",
				elementType: "geometry.fill",
				stylers: [
					{ hue: extraColor },
					{ visibility: "on" }, 
					{ lightness: 1 }, 
					{ saturation: 7 }
				]
			}, 
			{
				featureType: "water",
				elementType: "geometry",
				stylers: [
					{ hue: extraColor },
					{ visibility: "on" }, 
					{ lightness: 1 }, 
					{ saturation: 7 }
				]
			}];
			
		} 
		
		
		
		else if(greyscale == '1'){
			
			styles = [
		    
		    {
				featureType: "poi",
				elementType: "labels",
				stylers: [{
					visibility: "off"
				}]
			}, 
			{ 
				featureType: "road.local", 
				elementType: "labels.icon", 
				stylers: [{ 
					"visibility": "off" 
				}] 
			},
			{ 
				featureType: "road.arterial", 
				elementType: "labels.icon", 
				stylers: [{ 
					"visibility": "off" 
				}] 
			},
			{
				featureType: "road",
				elementType: "geometry.stroke",
				stylers: [{
					visibility: "off"
				}]
			}, 
			{
				elementType: "geometry",
				stylers: [{
					saturation: -100
				}]
			},
			{
				elementType: "labels",
				stylers: [{
				saturation: -100
				}]
			}, 
			{
				featureType: "poi",
				elementType: "geometry.fill",
				stylers: [{
					color: "#ffffff"
				}]
			},
			{
				featureType: "landscape",
				stylers: [{
					color: "#ffffff"
				}]
			}, 
			{
				featureType: "road",
				elementType: "geometry.fill",
				stylers: [ {
					color: "#eaeaea"
				}]
			}, 
			{
				featureType: "water",
				elementType: "geometry",
				stylers: [{
					color: "#b9e7f4"
				}]
			}];
				
			
		}
		
		
		else {
			 styles = [];
		} 
		
		var styledMap = new google.maps.StyledMapType(styles,
	    {name: "Styled Map"});
	
	
	    //options
		var mapOptions = {
	      center: latLng,
	      zoom: zoomLevel,
	      mapTypeControlOptions: {
	        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
	   	  },
	      scrollwheel: false,
	      panControl: false,
		  zoomControl: enableZoom,	  
		  zoomControlOptions: {
	        style: google.maps.ZoomControlStyle.LARGE,
	        position: google.maps.ControlPosition.LEFT_CENTER
	   	  },
		  mapTypeControl: false,
		  scaleControl: false,
		  streetViewControl: false
		  
	    };
		
		map[i] = new google.maps.Map(document.getElementById($(this).attr('id')), mapOptions);
		
		//Associate the styled map with the MapTypeId and set it to display.
	    map[i].mapTypes.set('map_style', styledMap);
	    map[i].setMapTypeId('map_style');
	
		var $count = i;
		
		google.maps.event.addListenerOnce(map[i], 'tilesloaded', function() {
			
			//don't start the animation until the marker image is loaded if there is one
			if(markerImg.length > 0) {
				var markerImgLoad = new Image();
				markerImgLoad.src = markerImg;
				
				$(markerImgLoad).load(function(){
					 setMarkers(map[i], map[i].j.id, $count);
				});
			}
			else {
				setMarkers(map[i], map[i].j.id, $count);
			}
	    });
    
   });
    
 
    function setMarkers(map,map_id,count) {


		  $('.map-marker-list.'+map_id).each(function(){
		      	
		        var enableAnimation = $('#'+map_id).attr('data-enable-animation');
				
		      	$(this).find('.map-marker').each(function(i){
				
		      		 var marker = new google.maps.Marker({
				      	position: new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng')),
				        map: map,
				        visible: false,
				        mapIndex: count,
						infoWindowIndex : i,
						icon: $('#'+map_id).attr('data-marker-img'),
						optimized: false
				      });
					  
					  //animation
					  if(typeof enableAnimation != 'undefined' && enableAnimation == 1 && $(window).width() > 690) {
					     setTimeout(function() {			     	
					  	    marker.setAnimation(google.maps.Animation.BOUNCE);
					  	    marker.setOptions({ visible: true });
					  	    setTimeout(function(){marker.setAnimation(null);},500);
					     },   i * 200);
				      } else {
				      	marker.setOptions({ visible: true });
				      }

					   //infowindows 
				      var infowindow = new google.maps.InfoWindow({
				   	    content: $(this).attr('data-mapinfo'),
				    	maxWidth: 300
					  });
					  
					  infoWindows[count].push(infowindow);
			
				      google.maps.event.addListener(marker, 'click', (function(marker, i) {
				        return function() {
				        	infoWindows[this.mapIndex][this.infoWindowIndex].open(map, this);
				        }
				        
				      })(marker, i));
				    
		      		 
		      	});
		      
		 });
				          
		     
	}//setMarker
	
});
