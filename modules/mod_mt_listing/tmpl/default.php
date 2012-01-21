<?php /* $Id: default.php 779 2009-08-31 06:20:24Z CY $ */ defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><?php
$rank = 1;
if ( is_array($listings) ) {
	//print_r(array_values($listings));
	foreach( $listings AS $l ) {
		echo '<tr><td>';
		if ( $show_rank == 1 ) echo "<b>".$rank++ . "</b>. ";
		
		// Name
		echo '<a href="' . $l->link . '" class="'.$listingclass.'">';
		echo $l->trimmed_link_name;
		echo  '</a>';
		
		// Image
		if( $show_images ) {
			echo '<br />';
			if ( $show_rank == 1 ) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo '<a href="' . $l->link . '">';
			echo '<img align="bottom" border="0" src="'. $l->image_path . '" alt="' . $l->link_name . '" />';
			echo  '</a>';
		}
		
		// Website
		if ( $show_website == 1 && !empty($l->website) ) {
			echo "<br />";
			if ( $show_rank == 1 ) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<small><a href=\"".$l->website."\">";
			echo $l->trimmed_website;
			echo "</a></small>";
		}
		
		// Category
		if ( $show_category == 1 ) {
			echo "<br />";
			if ( $show_rank == 1 ) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<small>".JText::_( 'Category' ).": <a href=\"" . $l->cat_link . "\">" . $l->cat_name . "</a></small>";
		}
		
		// Related Data
		if ( $show_rel_data == 1 && $type <> 2 ) {
			echo "<br />";
			if ( $show_rank == 1 ) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
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
		}
		
		// Custom fields
		$displayfields = $params->get( 'fields', array() );
		if( !is_array($displayfields) ) {
			$displayfields = array($displayfields);
		}
		
		if( !empty($displayfields) && isset($fields[$l->link_id]) )
		{
			echo '<small>';
			$fields[$l->link_id]->resetPointer();
			while( $fields[$l->link_id]->hasNext() ) {
				$field = $fields[$l->link_id]->getField();
				if( in_array($field->getId(),$displayfields) && $field->hasValue() )
				{
					echo '<br />';
					if ( $show_rank == 1 ) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					if($field->hasCaption()) {
						echo $field->getCaption();
						echo ': ';
					}
					$value = $field->getOutput(2);
					echo $value;
				}
				$fields[$l->link_id]->next();
			}
			echo '</small>';
		}
		
		echo '</td></tr>';	
	}
}

if ( $show_more ) {
	echo '<tr><td>';
	echo '<a href="';
	echo $show_more_link;
	echo '" class="'.$listingclass.'">';
	echo $caption_showmore . '</a></td></tr>';	
}

?></table>