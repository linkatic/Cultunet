function mapinitialize() {
	if (GBrowserIsCompatible()) {
	   	map = new GMap2(document.getElementById("map"));
		if( linkValLng != 0 && linkValLat != 0 ) {
			var center = new GLatLng(linkValLat, linkValLng);
			map.setCenter(center, parseInt(linkValZoom));
		} else {
			var center = new GLatLng(defaultLat, defaultLng);
			map.setCenter(center, defaultZoom);
		}
		for (var i = 0, j = mapControl.length; i < j; i++) {
			map.addControl(mapControl[i]);
	 	}
	 	map.addControl(new GMapTypeControl());
        marker = new GMarker(center, {draggable: true});
        map.addOverlay(marker);
		geocoder = new GClientGeocoder();
        GEvent.addListener(marker, "dragend", function(){updateField(marker);});
        GEvent.addListener(map, "zoomend", function(){updateField(marker);});
        GEvent.addListener(marker, "dragstart", function(){map.closeInfoWindow();});
	} else {
		if( typeof(jQuery('#mapContainer').val()) != 'undefined' && typeof(txtNoAPIKey) != 'undefined' ) {
			jQuery('#mapContainer').html(txtNoAPIKey);
		}
	}
}
function showAddress(address) {
  geocoder.getLatLng(
    address,
    function(point) {
	  jQuery('#locateButton').val(txtLocateInMap);
	  jQuery('#locateButton').attr('disabled',0);
      if (!point) {
        alert(txtNotFound + address);
      } else {
        map.setCenter(point, 13);
		marker.setLatLng(point);
        marker.openInfoWindowHtml(address);
		updateField(marker);
      }
    }
  );
}			
function locateInMap() {
	jQuery('#locateButton').val(txtLocating);
	jQuery('#locateButton').attr('disabled',1);
	jQuery('#locateButton').css('font-weight','normal');
	showAddress(getAddress());
}
function getAddress() {
	var city;
	var state;
	var country;
	var postcode;
	if(typeof(jQuery('#country').val()) != 'undefined' && jQuery('#country').val() != ''){country=jQuery('#country').val();}
	else {country = defaultCountry;}
	if(typeof(jQuery('#state').val()) != 'undefined' && jQuery('#state').val() != ''){state=jQuery('#state').val();}
	else {state = defaultState;}
	if(typeof(jQuery('#city').val()) != 'undefined' && jQuery('#city').val() != ''){city=jQuery('#city').val();}
	else {city = defaultCity;}

	if(typeof(jQuery('#postcode').val()) == 'undefined') {
		postcode = '';
	} else {
		postcode = jQuery('#postcode').val();
	}
	var address = new Array(jQuery('#address').val(),city,state,postcode,country);
	var val = null;
	for(var i=0;i<address.length;i++){
		if(address[i] != '') {
			if(val == null) {
				val = address[i];
			} else {
				val += ', ' + address[i];
			}
		}
	}
	return val;
}
function updateMapAddress() {
	jQuery('#map-msg').html(getAddress());
	if(jQuery('#address').val() == '' && jQuery('#city').val() == '' && jQuery('#state').val() == '' && jQuery('#postcode').val() == '' && jQuery('#country').val() == '') {
		jQuery('#locateButton').css('font-weight','normal');
		jQuery('#locateButton').attr('disabled',1);
	} else {
		jQuery('#locateButton').css('font-weight','bold');
		jQuery('#locateButton').attr('disabled',0);
	}
}
function updateField(marker) {
	var locatedAddress = marker.getLatLng();
	jQuery('#lat').val(locatedAddress.lat());
	jQuery('#lng').val(locatedAddress.lng());
	jQuery('#zoom').val(map.getZoom());
}
jQuery(document).unload(function(){GUnload();});
jQuery(document).ready(function(){
	updateMapAddress();
	jQuery('#locateButton').css('font-weight','normal');
	if(linkValLat == 0 || linkValLng == 0) {
		jQuery('#map-msg').html(txtEnterAddress);
	}
	mapinitialize();
	jQuery('#address,#city,#state,#postcode,#country').change(function(){
		updateMapAddress();
	});
	jQuery('#address,#city,#state,#postcode,#country').keyup(function(){
		updateMapAddress();
	});
});