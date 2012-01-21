<?php /* $Id: default.php 779 2009-08-31 06:20:24Z CY $ */ defined('_JEXEC') or die('Restricted access'); ?>
<div class="border-container">
<?php
$rank = 1;
if ( is_array($listings) ) {
	//print_r(array_values($listings));
	$num_items = count($listings); 
	foreach( $listings AS $l ) {
		$num_items = $num_items - 1;
		if($num_items!=0) $style = "listing-summary";
		else $style = "listing-summary last-item";
		echo '<div class="'.$style.'">';
		echo '<div class="header">';	

		// Image
		if( $show_images ) {
			echo '<a href="' . $l->link . '">';
			echo '<img class="image-left" src="'. $l->image_path . '" alt="' . $l->link_name . '" />';
			echo  '</a>';
		}
			
		// Name
		echo '<h3>';
		if ( $show_rank == 1 ) echo "<b>".$rank++ . "</b>. ";
		echo '<a href="' . $l->link . '" class="'.$listingclass.'">';
		echo $l->trimmed_link_name;
		echo  '</a>';
		echo '</h3>';
		
		// Category
		if ( $show_category == 1 ) {
			/*echo "<small>".JText::_( 'Category' ).":</small>".*/
			echo "<p class=\"category\"><small><a href=\"" . $l->cat_link . "\">" . $l->cat_name . "</a></small></p>";
		}
		
		// Related Data
		if ( $show_rel_data == 1 && $type <> 2 ) {
			echo '<span class="related_data">';
			echo "<small>";
			switch( $type ) {
				case 1:
					echo JText::_( 'Created' ) . ": ".JHTML::_('date', strtotime($l->link_created), '%d %B %Y');
					break;
				case 3:
					echo JText::_( 'Hits' ) . ": ".$l->link_hits;
					break;
				case 4:
					echo JText::_( 'Votes' ) . ": ".$l->link_votes;
					break;
				case 5:
					$star = round($l->link_rating, 0);
					// Print stars
					for( $i=0; $i<$star; $i++) {
						echo '<img src="components/com_mtree/img/star_10.png" width="16" height="16" hspace="1" alt="Star10" />';
					}
					// Print blank star
					for( $i=$star; $i<5; $i++) {
						echo '<img src="components/com_mtree/img/star_00.png" width="16" height="16" hspace="1" alt="Star00" />';
					}
					break;
				case 6:
					echo JText::_( 'Reviews' ) . ": ".$l->reviews;
					break;
			}
			echo "</small>";
			echo "</span>";
		}
		
		
		// Website
		if ( $show_website == 1 && !empty($l->website) ) {
			echo '<p class="website">';
			echo "<a href=\"".$l->website."\">";
			echo $l->trimmed_website;
			echo "</a>";
			echo "</p>";
		}
		
		//Description
		echo '<p>';
		$intro_desc = str_split($l->link_desc,200);
		echo $intro_desc[0].' ...';
		echo '</p>'; 
		
		echo '</div><!-- header -->';	
		
		// Custom fields
		$displayfields = $params->get( 'fields', array() );
		if( !is_array($displayfields) ) {
			$displayfields = array($displayfields);
		}
		
		$num_fields = 0;
		if( !empty($displayfields) && isset($fields[$l->link_id]) )
		{
			echo '<div class="fields">';
			$fields[$l->link_id]->resetPointer();
			while( $fields[$l->link_id]->hasNext() ) {
				$field = $fields[$l->link_id]->getField();
				if( in_array($field->getId(),$displayfields) && $field->hasValue() )
				{
					$num_fields = $num_fields+1;
					if($num_fields%3 == 0) $style = "fieldRow lastFieldRow";
					else $style = "fieldRow";	
					echo '<div class="'.$style.'">';
					if($field->hasCaption()) {
						echo '<span class="caption">';
						echo $field->getCaption();
						echo '</span>';
					}
					$value = $field->getOutput(2);
					echo '<span class="output">';
					echo $value;
					echo '<span>';
					echo "</div><!-- Fin fieldRow -->";
					
				}
				$fields[$l->link_id]->next();
			}
			echo '</div><!-- Fin fields -->';
		}
		if ( $show_more ) {
			echo '<div class="mas-info">';
			echo '<a href="';
			echo $l->link;
			echo '" class="'.$listingclass.'">';
			echo $caption_showmore . '</a><img src="templates/gestionyculturatemplate/images/flecha_vermas.png" alt="Ficha del recurso con información extendida" title="Ficha del recurso con información extendida" />';
			echo '</div><!-- Fin mas-info -->';	
		}
		echo '</div><!-- Fin listing-summary -->';
		
	}//fin foreach
}//fin if ( is_array($listings) )


?>
<div class="clear">&nbsp;</div>
</div>
