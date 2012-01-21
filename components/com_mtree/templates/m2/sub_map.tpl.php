<?php
	$params = new JParameter( $this->link->attribs );
	$params->def( 'use_map', $this->mtconf['use_map'] );

	if( $this->mtconf['use_map'] == 1 && $params->get( 'use_map' ) == 1 && !empty($this->link->lat) && !empty($this->link->lng) && !empty($this->link->zoom) ) {
		
		$width = '97%';
		$height = '200px';
		
?><div class="map">
	<div class="title"><?php echo JText::_( 'Map' ); ?></div>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->mtconf['gmaps_api_key']; ?>" type="text/javascript"></script>
	<script type="text/javascript">
		var map = null;
	    var geocoder = null;
		function initialize() {
			if (GBrowserIsCompatible()) {
		   	map = new GMap2(document.getElementById("map"));
			<?php
			echo 'var center = new GLatLng(' . $this->link->lat . ', ' . $this->link->lng . ');' . "\n";
			echo 'map.setCenter(center, ' . ($this->link->zoom?$this->link->zoom:13) . ');' . "\n";
			if( $this->mtconf['map_control'] != '' ) {
				$mapcontrols = explode(',',$this->mtconf['map_control']);
				foreach( $mapcontrols AS $mapcontrol ) {
					echo 'map.addControl(new '.$mapcontrol.'());' . "\n";
				}
			}
			?>
	        marker = new GMarker(center);
	        map.addOverlay(marker);
			}
		}
		jQuery(document).ready(function(){initialize();});
		jQuery(document).unload(function(){GUnload();});
	</script>
	<div id="map" style="width:<?php echo $width; ?>;height:<?php echo $height; ?>"></div>
</div><?php
}
?>