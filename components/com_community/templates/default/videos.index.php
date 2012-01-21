<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * 
 */
defined('_JEXEC') or die();
?>

<?php echo $featuredHTML; ?>

<div class="cRow">
	<div class="ctitle"><?php echo JText::_('CC CATEGORIES');?></div>
    <ul class="c3colList">
    <?php if( $categories ): ?>
		<li>
				<a href="<?php echo CRoute::_($allVideosUrl);?>">
						<?php echo JText::_( 'CC ALL VIDEOS' ); ?>
				</a>
		</li>
		<?php foreach( $categories as $row ): ?>
		<li>
				<a href="<?php echo CRoute::_($catVideoUrl . $row->id ); ?>">
						<?php echo JText::_($this->escape($row->name)); ?>
				</a> ( <?php echo $row->count; ?> )
		</li>
		<?php endforeach; ?>
    <?php else: ?>
        <li><?php echo JText::_('CC NO CATEGORIES CREATED'); ?></li>
    <?php endif; ?>
    </ul>
    <div class="clr"></div>
</div>


<?php echo $sortings; ?>

<?php echo $videosHTML; ?>
<div style="clear: both;"></div>
<div class="pagination-container">
	<?php echo $pagination->getPagesLinks(); ?>
</div>