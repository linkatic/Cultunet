<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	albums	An array of album objects.
 * @param	user	Current browser's CUser object. 
 * @params	isOwner		boolean Determines if the current photos view belongs to the browser
 */
defined('_JEXEC') or die();

if( $featuredList )
{
?>
<div class="cToolbarBand">
	<div class="bandContent">
		<h3 class="bandContentTitle"><?php echo JText::_('CC FEATURED ALBUMS');?></h3>
		
<?php
	foreach($featuredList as $album)
	{
?>
	<div class="featured-items">
		<a href="<?php echo CRoute::_($album->getURI()); ?>"><img class="avatar" src="<?php echo $album->getCoverThumbPath();?>" alt="<?php echo $this->escape($album->name);?>" /></a>
		
		<div class="clr"></div>
		<div style="display: block; font-weight:700;"><?php echo $this->escape($album->name); ?></div>
        <?php
		if( $isCommunityAdmin )
		{
		?>
		<div class="icon-removefeatured">
            <a onclick="joms.featured.remove('<?php echo $album->id;?>','photos');" href="javascript:void(0);"><?php echo JText::_('CC REMOVE FEATURED'); ?></a>
        </div>
		<?php
		}
		?>
	</div>
<?php
	}
?>
	<div class="clr"></div>
	</div>
	
	<div class="bandFooter"><div class="bandFooter_inner"></div></div>
</div>
<?php } ?>


<div>
	<?php echo $albumsHTML; ?>
</div>
<div class="clr"></div>
<div class="pagination-container">
	<?php echo $pagination->getPagesLinks(); ?>
</div>