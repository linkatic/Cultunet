<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	author		string
 * @param	categories	An array of category objects.
 * @param	category	An integer value of the selected category id if 0, not selected. 
 * @params	groups		An array of group objects.
 * @params	pagination	A JPagination object.  
 * @params	isJoined	boolean	determines if the current browser is a member of the group 
 * @params	isMine		boolean is this wall entry belong to me ?
 * @params	config		A CConfig object which holds the configurations for Jom Social
 * @params	sorttype	A string of the sort type. 
 */
defined('_JEXEC') or die();
?>

<div class="cLayout clrfix">
	<?php
	if( $featuredList )
	{
	?>
	<div class="cRow">
	<div class="ctitle"><?php echo JText::_('CC FEATURED GROUPS');?></div>
		<?php
			foreach($featuredList as $group)
			{
		?>
		<div class="featured-items">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>">
            <img class="avatar" src="<?php echo $group->getAvatar();?>" alt="<?php echo $this->escape($group->name);?>" />
            <div style="text-align: center; font-weight: bold; padding: 3px 0;"><?php echo $this->escape($group->name);?></div>
            </a>
			<?php
			if( $isCommunityAdmin )
			{
			?>
				<div class="icon-removefeatured"><a onclick="joms.featured.remove('<?php echo $group->id;?>','groups');" href="javascript:void(0);"><?php echo JText::_('CC REMOVE FEATURED'); ?></a></div>
			<?php
			}
			?>
		</div>
		<?php
		}
		?>
		<div class="clr"></div>
	</div>
	<?php
	}
	?>

	<?php if ( $index ) : ?>
	<div class="cRow">
		<div class="ctitle"><?php echo JText::_('CC CATEGORIES');?></div>
		<ul class="c3colList">
		<?php if( $categories ): ?>
		<li>
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups');?>"><?php echo JText::_( 'CC ALL GROUPS' ); ?></a>
		</li>
		
			<?php foreach( $categories as $row ): ?>
			<li>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&categoryid=' . $row->id ); ?>"><?php echo JText::_( $this->escape($row->name) ); ?></a> ( <?php echo $row->count; ?> )
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
		<?php echo ( isset($category) && ($category->id != '0') ) ? JText::sprintf('CC VIEW BY CATEGORY NAME' , JText::_( $this->escape($category->name)) ) : JText::_( 'CC ALL GROUPS' ); ?>
	</h3>
	<?php endif; ?>
    
	<?php echo $discussionsHTML;?>
	
	<!-- ALL GROUP LIST -->
	<div class="cMain clrfix">
		<?php echo $groupsHTML;?>
		<div class="pagination-container">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
	</div>
    <!-- ALL GROUP LIST -->
    
    <div class="clr"></div>
</div>