<?php		
		$num_items = $num_items - 1;
		if($num_items!=0) $style = "listing-summary";
		else $style = "listing-summary last-item";
		echo '<div class="'.$style.'';
		
?>

<?php echo ($link->link_featured && $this->config->getTemParam('useFeaturedHighlight','1')) ? ' featured':''?>">

<div class="header">
<?php 	

		// Listing's first image
		if(!is_null($fields->getFieldById(2)) || $link->link_image) {
			if ($link->link_image && $this->config->getTemParam('showImageInSummary',1) ) {
				$this->plugin( 'ahreflistingimage', $link, 'class="image-left' . (($this->config->getTemParam('imageDirectionListingSummary','right')=='right') ? '':'-left') . '" alt="'.htmlspecialchars($link->link_name).'"' );
			}
		}
		
		//Name 		

?>
		<h3><?php 
			$link_name = $fields->getFieldById(1);
			switch( $this->config->getTemParam('listingNameLink','1') )
			{
				default:
				case 1:
					$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), '', array('delete'=>false) );
					break;
				case 4:
					if( !empty($link->website) ) {
						$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), '', array('delete'=>false), 1 );
					} else {
						$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), '', array('delete'=>false) );
					}
					break;
				case 2:
					$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), '', array('delete'=>false), 1 );
					break;
				case 3:
					$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), 'target="_blank"', array('delete'=>false), 1 );
					break;
				case 0:
					$this->plugin( 'ahreflisting', $link, $link_name->getOutput(2), '', array('delete'=>false, 'link'=>false) );
					break;
			}
		?></h3><?php
		// Listing's category
		if($this->task <> 'listcats' && $this->task <> '' ) {
			//echo '<div class="category"><span>' . JText::_( 'Category' ) . ':</span>';
			echo '<p class="category"><small>';
			$this->plugin( 'mtpath', $link->cat_id, '' );
			echo '</small></p>';
			//echo '</div>';
		}
		
		// Rating
		echo '<span class="related_data"><small>';
		$this->plugin( 'rating', $link->link_rating, $link->link_votes, $link->attribs);
		echo '</small></span>';
		
		// Website
		$website = $fields->getFieldById(12);
		if(!is_null($website) && $website->hasValue()) { echo '<p class="website">' . $website->getOutput(2) . '</p>'; }

		//Description
		if(!is_null($fields->getFieldById(2))) { 
			echo '<p style="margin:0;">';
			$link_desc = $fields->getFieldById(2);
			echo strip_tags($link_desc->getOutput(2));
			echo '</p>';
		}	
?>
</div><!-- Fin header -->
<?php			
		// Other custom field		
		$fields->resetPointer();
		echo '<div class="fields">';
		
		while( $fields->hasNext() ) {
			$field = $fields->getField();
			$value = $field->getOutput(2);
			if(
				( 
					(
						!$field->hasInputField() && !$field->isCore() && empty($value)) 
						|| 
						(!empty($value) || $value == '0')
					) 
					&&	
					!in_array($field->getId(),array(1,2,12))
					&&
					(
						($this->config->getTemParam('displayAddressInOneRow','1') && !in_array($field->getId(),array(4,5,6,7,8))
						||
						$this->config->getTemParam('displayAddressInOneRow','1') == 0						
					)
				)
			) {
				echo '<div class="fieldRow">';
				if($field->hasCaption()) {
					echo '<span class="caption">' . $field->getCaption() . '</span>';
					echo '<span class="output">' . $field->getOutput(2) . '</span>';
				} else {
					echo '<span class="output">' . $field->getOutput(2) . '</span>';
				}
				echo '</div>';
			}
			$fields->next();
		}
				// Address
		if( $this->config->getTemParam('displayAddressInOneRow','1') ) {
			$fields->resetPointer();
			$address_parts = array();
			while( $fields->hasNext() ) {
				$field = $fields->getField();
				$output = $field->getOutput(2);
				if(in_array($field->getId(),array(4,5,6,7,8)) && !empty($output)) {
					$address_parts[] = $output;
				}
				$fields->next();
			}
			if( count($address_parts) > 0 ) { echo '<div class="address"><span class="caption">'.JText::_( 'Address' ).'</span><span class="output">' . implode(', ',$address_parts) . '</span></div>'; }
		}
		echo '</div>';
		
		if($this->config->getTemParam('showActionLinksInSummary','0')) {
			echo '<div class="actions">';
			$this->plugin( 'ahrefreview', $link, array("rel"=>"nofollow") ); 
			$this->plugin( 'ahrefrecommend', $link, array("rel"=>"nofollow") );	
			$this->plugin( 'ahrefprint', $link );
			$this->plugin( 'ahrefcontact', $link, array("rel"=>"nofollow") );
			$this->plugin( 'ahrefvisit', $link );
			$this->plugin( 'ahrefreport', $link, array("rel"=>"nofollow") );
			$this->plugin( 'ahrefclaim', $link, array("rel"=>"nofollow") );
			$this->plugin( 'ahrefownerlisting', $link );
			$this->plugin( 'ahrefmap', $link );
			echo '</div>';
		}
		
		if ( $show_more ) {
			echo '<div class="mas-info">';
			echo '<a href="';
			echo $show_more_link;
			echo '" class="'.$listingclass.'">';
			echo $caption_showmore . '</a><img src="templates/gestionyculturatemplate/images/flecha_vermas.png" alt="Ficha del recurso con información extendida" title="Ficha del recurso con información extendida" />';
			echo '</div><!-- Fin mas-info -->';	
		}
?></div>