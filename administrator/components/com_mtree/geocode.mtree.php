<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

$task2 = JRequest::getCmd( 'task2', '');

global $mainframe;

switch($task2) {
	case 'savecoord':
		savecoord();
		jexit();
		break;
	case 'saveaddress':
		saveaddress();
		jexit();
		break;
	case 'getlistings':
		$count = JRequest::getInt( 'count', 25);
		getlistings($count);
		jexit();
		break;
	default:
		geocode();
}

function savecoord() {
	$db =& JFactory::getDBO();
		
	$link_ids = JRequest::getVar( 'link_id', array(), 'post');
	JArrayHelper::toInteger($link_ids, array(0));

	$lats = JRequest::getVar( 'lat', array(), 'post');
	$lngs = JRequest::getVar( 'lng', array(), 'post');
	
	$coordinates = array();
	$done_link_ids = array();
	
	if( empty($link_ids) ) {
		echo '';
	} else {
		foreach( $link_ids AS $link_id ) {
			if( $lats[$link_id] && !empty($lats[$link_id]) && $lngs[$link_id] && !empty($lngs[$link_id]) ) {
				$coordinates[$link_id] = array('lat'=>$lats[$link_id], 'lng'=>$lngs[$link_id]);
			}
		}
		if( !empty($coordinates) ) {
			foreach( $coordinates AS $link_id => $coordinate ) {
				$sql = 'UPDATE #__mt_links SET lat = ' . $db->Quote($coordinate['lat']) . ', lng = ' . $db->Quote($coordinate['lng']) . ', zoom = ' . $db->Quote('10') . ' WHERE link_id = ' . $db->Quote($link_id) . ' LIMIT 1';
				$db->setQuery($sql);
				$db->query();
				if( $db->getAffectedRows() > 0 ) {
					$done_link_ids[] = $link_id;
				}
			}
		}
	}
	
	if( !empty($done_link_ids) ) {
		echo implode(',',$done_link_ids);
	}
}

function saveaddress() {
	$db =& JFactory::getDBO();
		
	$link_id = JRequest::getInt( 'link_id', 0, 'post');

	$address = JRequest::getVar( 'address', '', 'post');
	$city = JRequest::getVar( 'city', '', 'post');
	$state = JRequest::getVar( 'state', '', 'post');
	$postcode = JRequest::getVar( 'postcode', '', 'post');
	$country = JRequest::getVar( 'country', '', 'post');
	
	if( !empty($link_id) ) {
		$db->setQuery( 'UPDATE #__mt_links SET address = ' . $db->Quote($address) . ', city = ' . $db->Quote($city) . ', state = ' . $db->Quote($state) . ', postcode = ' . $db->Quote($postcode) . ', country = ' . $db->Quote($country) . ' WHERE link_id = ' . $db->Quote($link_id) . ' LIMIT 1' );
		$db->query();
		return 1;
	} else {
		return 0;
	}
}

function getlistings($count=10) {
	$link_ids = JRequest::getVar( 'link_id', array(), 'post');
	JArrayHelper::toInteger($link_ids, array(0));
	
	$listings = getUnGeocodedListings($count,$link_ids);
	foreach( $listings AS $listing ) {
		echo $listing->link_id;
		echo "|";
		echo $listing->link_name;
		echo "|";
		echo $listing->address;
		echo "|";
		echo $listing->city;
		echo "|";
		echo $listing->state;
		echo "|";
		echo $listing->postcode;
		echo "|";
		echo $listing->country;
		echo "\n";
	}
}

function geocode() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	if( strpos(strtolower($user_agent),'mozilla') === false ) {
		HTML_mtgeocode::incompatible_browser();
		return;
	}
	
	$db =& JFactory::getDBO();
	
	// Get total number of listings
	$db->setQuery( 'SELECT COUNT(*) FROM #__mt_links' );
	$total['all'] = $db->loadResult();
	
	// Get the number of geocoded listings, ie: listings that has latitude & longitude values
	$db->setQuery( 'SELECT COUNT(*) FROM #__mt_links WHERE lat != 0 && lng != 0 && zoom != 0' );
	$total['geocoded'] = $db->loadResult();
	
	// Get the number of listings which needs to be geocoded
	$db->setQuery( 'SELECT COUNT(*) FROM #__mt_links WHERE (lat = 0 && lng = 0 && zoom = 0) && (address != \'\' || city != \'\' || state != \'\' || country != \'\' || postcode != \'\')' );
	$total['req_geocoding'] = $db->loadResult();
	
	$listings = getUnGeocodedListings(25);
	
	HTML_mtgeocode::status( $listings, $total );
}

function getUnGeocodedListings($count=20,$exclude=null) {
	$db =& JFactory::getDBO();
	
	// Get listings
	$sql = 'SELECT link_id, link_name, address, city, state, postcode, country '
		. 'FROM #__mt_links '
		. 'WHERE '
		. '(lat = 0 && lng = 0 && zoom = 0) '
		. ' && (address != \'\' || city != \'\' || state != \'\' || country != \'\' || postcode != \'\') ';
	if( !is_null($exclude) && !empty($exclude) ) {
		$sql .= ' && link_id NOT IN (' . implode(',',$exclude) . ')';
	}
	$sql .= ' LIMIT ' . $count;
	$db->setQuery( $sql );
	$listings = $db->loadObjectList();
	
	return $listings;
}

class HTML_mtgeocode {

	function status($listings, $total) {
		global $mtconf, $mainframe;
	?>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $mtconf->get('gmaps_api_key'); ?>" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
	<style type="text/css">
	#btnSave, #btnGeocode {
		font-weight: bold;
	}
	#grid tr {
		background-color: #F1F3F5;
	}
	#grid td {
		border-bottom: 1px solid #C9CCCD;
	}
	#grid td span.link_name {
		line-height: 2em;
	}
	.status {
		float: left;
		background-color: #E6F5D3;
		padding: 0 5px;
		margin: 1px 5px 0 0;
		
	}
	#grid td span.fulladdress {
		color: #0B55C4;
		line-height: 1.5em;
		white-space:nowrap;
		empty-cells:show;
		border-collapse: collapse;
		display: block;
		padding-bottom: 8px;
	}
	#grid td.link_name {
		width: 180px;
		display:block;
		height:29px;
		overflow:hidden;
	}
	#grid td.address {
/*		width:420px;*/
		width:100%;
		max-width:440px;
		padding-top:6px;
		height:29px;
		overflow:hidden;
	}

	#grid .editform {
		display: none;
		border-left: 1px solid #C9CCCD;
		padding-left:10px;
		
	}
	#grid .editform span {
		display:block;
		margin-bottom:6px;
	}
	#grid .editform label {
		width: 60px;
		display: block;
		float:left;
	}
	.editformcancel {
		margin-left:5px;
		color: #0B55C4;
	}
	#grid input.linkcheckbox {
		position:relative;
		top:3px;
	}
	#grid span.found {
		color: green;
		font-weight: bold;
	}
	#grid span.notfound {
		color: red;
	}
	tfoot td {
		padding: 10px 6px 0 0;
		line-height: 2.5em;
		font-weight:bold;
	}
	</style>
	<script language="javascript" type="text/javascript">
	jQuery.noConflict();
	var geocoder = new GClientGeocoder();
	var admin_site_url = '<?php echo $mainframe->getSiteURL(); ?>' + 'administrator/index.php';
	
	var defaultLat = '<?php echo addslashes($mtconf->get('map_default_lat')); ?>';
	var defaultLng = '<?php echo addslashes($mtconf->get('map_default_lng')); ?>';
	var defaultZoom = 2;
	var mapControl = [new <?php echo implode("(), new ",explode(',',$mtconf->get('map_control'))); ?>()];
	var bounds = null; 
	var marker = [];
	var txtFound = '<?php echo JText::_( 'Found', true ); ?>';
	var txtNotFound = '<?php echo JText::_( 'Not found', true ); ?>';
	var txtNoAPIKey = '<?php echo JText::_( 'No API Key message', true ); ?>';

	jQuery(document).ready(function(){
		
		jQuery('#btnGeocode').click(function(){
			executeGeocode();
			//set centre
		});
		
		
		if (GBrowserIsCompatible()) {
		   	map = new GMap2(document.getElementById("map"));
			bounds = new GLatLngBounds();
			var center = new GLatLng(defaultLat, defaultLng);
			map.setCenter(center, defaultZoom);
			for (var i = 0, j = mapControl.length; i < j; i++) {
				map.addControl(mapControl[i]);
		 	}
		 	map.addControl(new GMapTypeControl());
		
			<?php 
			$address_parts = array('address','city','state','postcode','country');
			foreach( $listings AS $listing ) { 
				echo 'addRow("'.addslashes($listing->link_id).'", "'.addslashes($listing->link_name).'", "'.addslashes($listing->address).'", "'.addslashes($listing->city).'", "'.addslashes($listing->state).'", "'.addslashes($listing->postcode).'", "'.addslashes($listing->country).'")';
				echo "\n;";
			}
			?>
		} else {
			jQuery('#status').html(txtNoAPIKey);
			jQuery('#listings').html('');
			jQuery('#map').html('');
		}
		
		updateSaveLocationsButton();
			
		function executeGeocode() {
			var addresses = getAddresses();
			for (var i = 0, j = addresses.length; i < j; i++) {
				if( addresses[i] ) {
					var link_id = addresses[i][0];
					var address = addresses[i][1];
					var result = geocode( link_id, address )
				}
			}
		}
		
		function markdone(link_id, point) {
			jQuery('#checkbox'+link_id).attr('disabled',false);
			jQuery('#checkbox'+link_id).attr('checked',true);
			jQuery('#lat'+link_id).val(point.lat());
			jQuery('#lng'+link_id).val(point.lng());
			jQuery('#status'+link_id).html(txtFound);
			jQuery('#status'+link_id).removeClass('notfound');
			jQuery('#status'+link_id).addClass('found');
			jQuery('#status'+link_id).css('display','block');
			placeMarker(link_id,point.lat(),point.lng());
			bounds.extend(point);
			map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
			updateSaveLocationsButton();
		}
		function markundone(link_id, point) {
			jQuery('#status'+link_id).removeClass('found');
			jQuery('#status'+link_id).addClass('notfound');
			jQuery('#status'+link_id).html(txtNotFound);
			jQuery('#status'+link_id).css('display','block');
			updateSaveLocationsButton();
		}

		function getAddresses() {
			var addresses = [];
			jQuery('span.fulladdress').each(function(i){
				var link_id = jQuery(this).attr("id").substr(11);
				if( jQuery('#lat'+link_id).val() == '' && jQuery('#lng'+link_id).val() == '' ) {
					var address = jQuery(this).html();
					addresses[i] = [link_id,address];
				}
			});
			return addresses;
		}
		
		function geocode(link_id,address) {
			geocoder.getLatLng(
				address,
				function(point) {
					if (!point) {
						markundone(link_id, point);
					} else {
						markdone(link_id, point);
					}
				});			
			}
		});
		
		function getListings(count) {
			var link_ids = '';
			jQuery('span.fulladdress').each(function(i){
				var link_id = jQuery(this).attr("id").substr(11);
				link_ids += "&link_id[]="+link_id;
			});
			
			jQuery.ajax({
			  type: "POST",
			  url: admin_site_url,
			  data: "option=com_mtree&task=geocode&task2=getlistings&tmpl=component&hide=1&count="+count+link_ids,
			  dataType: "html",
			  success: function(str){
				var links = str.split("\n");
				for (var i = 0, j = links.length; i < j; i++) {
					if( links[i] != '' ) {
						var link = links[i].split("|");
						addRow(link[0],link[1],link[2],link[3],link[4],link[5],link[6]);
					}
				}
				updateSaveLocationsButton();
			  }
			});
		}
		
		function addRow(link_id, link_name, address, city, state, postcode, country) {
			var grid = document.getElementById("grid");
			var row = document.createElement("tr");
			row.id = "row" + link_id;
			
            var cell_1 = document.createElement("td");
			cell_1.setAttribute('valign', 'top');
			
			var cb = document.createElement( "input" );
			cb.type = "checkbox";
			cb.id = "checkbox"+link_id;
			cb.className = "linkcheckbox";
			cb.name = "link_id[]";
			cb.value = link_id;
			cb.checked = false;
			cb.disabled = true;
            cell_1.appendChild(cb);

			var lat = document.createElement( "input" );
			lat.type = "hidden";
			lat.id = "lat"+link_id;
			lat.className = "linklat";
			lat.name = "lat["+link_id+"]";
			lat.value = "";
            cell_1.appendChild(lat);

			var lng = document.createElement( "input" );
			lng.type = "hidden";
			lng.id = "lng"+link_id;
			lng.className = "linklng";
			lng.name = "lng["+link_id+"]";
			lng.value = "";
            cell_1.appendChild(lng);

			var addressesName = ['address','city','state','postcode','country'];
			var addresses = [address,city,state,postcode,country];
			for (var i = 0, j = addresses.length; i < j; i++) {
				var input = document.createElement( "input" );
				input.type = "hidden";
				input.id = addressesName[i]+link_id;
				input.className = "link"+addressesName[i];
				input.name = addressesName[i]+'['+link_id+']';
				input.value = addresses[i];
	            cell_1.appendChild(input);
				delete input;
			}
			
			var linkNameText = document.createTextNode(link_name);
			var linkNameSpan = document.createElement("span"); 
			linkNameSpan.className = "link_name"; 
			linkNameSpan.id = "link_name"+link_id; 
			linkNameSpan.title = link_name;
			linkNameSpan.appendChild(linkNameText);
            cell_1.appendChild(linkNameSpan);
			cell_1.className = 'link_name';

			row.appendChild(cell_1);
			
/*			var addresses = [address,city,state,postcode,country];
			var address = [];
			for (var i = 0, j = addresses.length; i < j; i++) {
				if( addresses[i] != '' ) {
					address.push(addresses[i]);
				}
			}*/
			
            var cell_2 = document.createElement("td");
			cell_2.className = 'address';
			var cellText_2 = document.createTextNode(getAddressString(address,city,state,postcode,country));

			var statusTag = document.createElement("span"); 
			statusTag.id = "status"+link_id; 
			statusTag.className = "status"; 
			statusTag.style.display = "none"; 
			statusTag.appendChild(cellText_2);
			cell_2.appendChild(statusTag);

			var spanTag = document.createElement("span"); 
			spanTag.id = "fulladdress"+link_id; 
			spanTag.className = "fulladdress"; 
			spanTag.title = getAddressString(address,city,state,postcode,country);
			spanTag.appendChild(cellText_2);
			cell_2.appendChild(spanTag);

			var divEditform = document.createElement("div");
			divEditform.id = 'editform'+link_id;
			divEditform.className = 'editform';
			cell_2.appendChild(divEditform);
			
            row.appendChild(cell_2);

			grid.appendChild(row);
			
			bindRowEvents(link_id);
		}
		
		function saveCoord() {
			var link_ids = jQuery('.linkcheckbox').serialize();
			var lats = jQuery('.linklat').serialize();
			var lngs = jQuery('.linklng').serialize();
			jQuery.ajax({
			  type: "POST",
			  url: admin_site_url,
			  data: "option=com_mtree&task=geocode&task2=savecoord&tmpl=component&hide=1&"+link_ids+"&"+lats+"&"+lngs,
			  dataType: "html",
			  success: function(str){
				var done_link_ids = str.split(',');
				for (var i = 0, j = done_link_ids.length; i < j; i++) {
					jQuery('#row'+done_link_ids[i]).empty();
					jQuery('#row'+done_link_ids[i]).hide();
					marker[done_link_ids[i]].hide();
					delete marker[done_link_ids[i]];
				}
				updateSaveLocationsButton();
				}
			});
		}
		
		function saveAddress(link_id,address,city,state,postcode,country) {
			jQuery.ajax({
			  type: "POST",
			  url: admin_site_url,
			  data: "option=com_mtree&task=geocode&task2=saveaddress&tmpl=component&hide=1&link_id="+link_id+"&address="+address+"&city="+city+"&state="+state+"&postcode="+postcode+"&country="+country,
			  dataType: "html",
			  success: function(str){
/*				console.log('saveAddress: '+str);*/
 			  }
		  });
		}
		
		function bindRowEvents(link_id) {
			jQuery('#grid tr#row'+link_id).hover(function(){
				jQuery(this).parent().css('cursor','hand');
			},function(){
				jQuery(this).parent().css('cursor','pointer');
			});

			jQuery('#grid tr span#fulladdress'+link_id).click(function(){

				if( typeof jQuery('#editformaddress'+link_id).val() == 'undefined' ) {
					jQuery('#editform'+link_id).hide();
					jQuery('#editform'+link_id).html(
						'<span><label for="editformaddress'+link_id+'">Address:</label><input type="text" name="address" value="'+jQuery('input#address'+link_id).val()+'" id="editformaddress'+link_id+'" size="30" /></span>'
						+'<span><label for="editformcity'+link_id+'">City:</label><input type="text" name="city" value="'+jQuery('input#city'+link_id).val()+'" id="editformcity'+link_id+'" size="30" /></span>'
						+'<span><label for="editformstate'+link_id+'">State:</label><input type="text" name="state" value="'+jQuery('input#state'+link_id).val()+'" id="editformstate'+link_id+'" size="30" /></span>'
						+'<span><label for="editformpostcode'+link_id+'">Postcode:</label><input type="text" name="postcode" value="'+jQuery('input#postcode'+link_id).val()+'" id="editformpostcode'+link_id+'" size="30" /></span>'
						+'<span><label for="editformcountry'+link_id+'">Country:</label><input type="text" name="country" value="'+jQuery('input#country'+link_id).val()+'" id="editformcountry'+link_id+'" size="30" /></span>'
						+'<input type="button" value="Save" class="saveaddress" id="saveaddress'+link_id+'" /> <a class="editformcancel" onclick="javascript:editformcancel('+link_id+')">Cancel</a>'
						+'<br /><br />'
						);
					jQuery('#editform'+link_id).slideDown('fast');
					var addressfield = document.getElementById('editformaddress'+link_id);
					addressfield.focus();
					addressfield.select();
			
					jQuery('#saveaddress'+link_id).click(function(){
						jQuery('#editform'+link_id).slideUp('fast');
						jQuery('input#address'+link_id).val(jQuery('#editformaddress'+link_id).val());
						jQuery('input#city'+link_id).val(jQuery('#editformcity'+link_id).val());
						jQuery('input#state'+link_id).val(jQuery('#editformstate'+link_id).val());
						jQuery('input#postcode'+link_id).val(jQuery('#editformpostcode'+link_id).val());
						jQuery('input#country'+link_id).val(jQuery('#editformcountry'+link_id).val());

						jQuery('span#fulladdress'+link_id).html(
							getAddressString(
								jQuery('#editformaddress'+link_id).val(),
								jQuery('#editformcity'+link_id).val(),
								jQuery('#editformstate'+link_id).val(),
								jQuery('#editformpostcode'+link_id).val(),
								jQuery('#editformcountry'+link_id).val()
							)
						);
						if( jQuery('span#fulladdress'+link_id).hasClass('notfound') ) {
							jQuery('span#fulladdress'+link_id).removeClass('notfound');
							jQuery('#lat'+link_id).val('');
							jQuery('#lng'+link_id).val('');
						}
						saveAddress(link_id,jQuery('#editformaddress'+link_id).val(),jQuery('#editformcity'+link_id).val(),jQuery('#editformstate'+link_id).val(),jQuery('#editformpostcode'+link_id).val(),jQuery('#editformcountry'+link_id).val());
					
					});
				
					jQuery("input[id^='editform']").keypress(function(e){
						if (e.which == 13) {
							var id = jQuery(this).attr("id");
							jQuery('#saveaddress'+link_id).click();
						} 
					});
				} else {
					jQuery('#editform'+link_id).toggle('fast');
				}
				
			});
			
			jQuery('#grid tr#row'+link_id).click(function(){
					jQuery('#grid td').css('background-color','');
					jQuery(this).children('td').css('background-color','#DCE9F8');
					var lat = getLat(link_id);
					var lng = getLng(link_id);
					if( lat != '' && lng != '' ) {
						if(jQuery('#checkbox'+link_id).attr('checked')) {
							openMarkerWindow(link_id);					
						}
					} else {
						// No coordinates
					}
			});
			
			jQuery('#checkbox'+link_id).click(function(){
				updateSaveLocationsButton();
			});
			
		}
		

		function updateSaveLocationsButton() {
			var count = 0;
			jQuery('.linkcheckbox').each(function(i){
				var link_id = jQuery(this).attr("id").substr(8);
				if( 
					jQuery('#checkbox'+link_id).attr('disabled') == false
					&&
					jQuery('#checkbox'+link_id).attr('checked') == true
				) {
					count++;
				}
			});
			if( count > 0 ) {
				jQuery('#btnSave').attr('disabled',false);
			} else {
				jQuery('#btnSave').attr('disabled',true);
			}
		}
		
		function getAddressString(address,city,state,postcode,country) {
			var addresses = [address,city,state,postcode,country];
			var address = [];
			for (var i = 0, j = addresses.length; i < j; i++) {
				if( addresses[i] != '' ) {
					address.push(addresses[i]);
				}
			}
			return address.join(', ');
		}
		
		function editformcancel(link_id) {
			jQuery('#editform'+link_id).slideUp('fast');
		}
		
		function placeMarker(link_id,lat,lng) {
			var location = new GLatLng(lat, lng);
			marker[link_id] = null;
			marker[link_id] = new GMarker(location, {draggable: true});
			marker[link_id].txt = jQuery('span#link_name'+link_id).html();
	        map.addOverlay(marker[link_id]);
			GEvent.addListener(marker[link_id],"click",function() 
		   { 
				openMarkerWindow(link_id);
		    });
		    GEvent.addListener(marker[link_id], "dragend", function(){
				var locatedAddress = marker[link_id].getLatLng();
				setLatLng(link_id,locatedAddress.lat(),locatedAddress.lng());
			});
		}
		
		function openMarkerWindow(link_id) {
			  marker[link_id].openInfoWindowHtml(marker[link_id].txt); 
		}
		function getLat(link_id) {
			return jQuery('#lat'+link_id).val();
		}
		
		function getLng(link_id) {
			return jQuery('#lng'+link_id).val();
		}
		function setLatLng(link_id,lat,lng) {
			setLat(link_id,lat);
			setLng(link_id,lng);
		}
		function setLat(link_id,lat) {
			jQuery('#lat'+link_id).val(lat);
		}
		function setLng(link_id,lng) {
			jQuery('#lng'+link_id).val(lng);
		}
		
	</script>
	<table width="100%">
		<tr>
			<td colspan="2" id="status">
			<fieldset>
			<legend><?php echo JText::_( 'Status' ) ?></legend>
			<table width="100%" border=0>
				<tr>
					<td>
						<?php printf(JText::_( 'Total listings in directory: {{n}}' ), $total['all']) ?>
						<p /><?php printf(JText::_( 'Geocoded listings: {{n}}' ), $total['geocoded']) ?>
						<p /><?php printf(JText::_( '{{n}} listings that haven\'t been geocoded but have address information with them. These listings can be geocoded, to locate their address in map.' ), $total['req_geocoding']) ?>
						<p /><?php echo JText::_( 'GEOCODE INSTRUCTION' ); ?>
					</td>
				</tr>
			</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td width="620px" valign="top" id="listings">
				<fieldset>
				<legend><?php echo JText::_( 'Listings' ); ?> (<?php echo $total['req_geocoding']; ?>)</legend>
				<?php if(!empty($listings)) { ?>

				<input type="button" value="Geocode" id="btnGeocode" />
				<input type="button" onclick="javascript:saveCoord();return false;" value="Save Locations" id="btnSave" disabled />
				<div style="margin-top:5px; height:; width:620px; overflow:show; display:block;float:left;clear:none">
				<table width="100%" border=0 cellpadding=0 cellspacing=0>
					<tbody id="grid">
					</tbody>
					<tfoot><tr><td colspan="2" align="right"><a href="javascript:getListings(25);"><?php echo JText::_( 'Load more listings' ); ?></a></td></tr></tfoot>
				</table>
				</div>
				<?php } ?>
				</fieldset>
			</td>
			<td valign="top">
				<div id="map" style="margin-top:8px;width:350px;height:384px;float:right;clear:none"></div>
			</td>
		</tr>
	</table>
		<?php
	}
	
	function incompatible_browser() {
		?>
		<h1><?php echo JText::_( 'Unsupported Browser' ); ?></h1>
		<?php echo JText::_( 'This feature requires Firefox 3 or later to operate.' ); ?>
		<?php
	}
}
?>