<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
?>

<div id="community-events-wrap">
	<?php if ( $index ) : ?>
	<div class="cRow">
		<div class="ctitle"><?php echo JText::_('CC CATEGORIES');?></div>
		<ul class="c3colList">
		<?php if( $categories ): ?>
		<li>
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=events');?>"><?php echo JText::_( 'CC ALL EVENTS' ); ?> </a>
		</li>
		<?php foreach( $categories as $row ): ?>
		<li>
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&categoryid=' . $row->id ); ?>"><?php echo JText::_( $this->escape($row->name) ); ?></a> ( <?php echo $row->count; ?> )
		</li>
		<?php endforeach; ?>
		<?php else: ?>
			<li><?php echo JText::_('CC NO CATEGORIES CREATED'); ?></li>
		<?php endif; ?>
		</ul>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	
	<?php echo $sortings; ?>
	
	<?php if( $index ): ?>
	<h3>
		<?php echo ( isset($category) && ($category->id != '0') ) ? JText::sprintf('CC VIEW BY CATEGORY NAME' , JText::_( $this->escape($category->name) ) ) : JText::_( 'CC ALL EVENTS' ); ?>
	</h3>
	<?php endif; ?>
	
	<div id="community-events-results-wrapper">
		<?php echo $eventsHTML;?>
		<div class="pagination-container">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
	</div>
</div>