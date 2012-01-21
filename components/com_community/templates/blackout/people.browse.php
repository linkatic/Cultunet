<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	author		string
 * @param	categories	An array of category objects.
 * @params	groups		An array of group objects.
 * @params	pagination	A JPagination object.
 * @params	isJoined	boolean	determines if the current browser is a member of the group
 * @params	isMine		boolean is this wall entry belong to me ?
 * @params	config		A CConfig object which holds the configurations for Jom Social
 * @params	sortings	A html data that contains the sorting toolbar 
 */
defined('_JEXEC') or die();
CFactory::load( 'libraries' , 'messaging' );

if( $featuredList && $showFeaturedList )
{	
?>

<div class="cToolbarBand">

	<div class="bandContent">
	<h3 class="bandContentTitle"><?php echo JText::_('CC FEATURED MEMBERS');?></h3>
<?php
	foreach($featuredList as $id)
	{
		$user	= CFactory::getUser( $id );
?>
		<div class="featured-items">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->id );?>"><img class="avatar jomTips" src="<?php echo $user->getThumbAvatar();?>" alt="<?php echo $user->getDisplayName();?>" title="<?php echo cAvatarTooltip($user); ?>"/></a>
			
			<div style="font-weight: bold;"><?php echo $user->getDisplayName();?></div>
<?php
		if( $isCommunityAdmin )
		{
?>
			<div class="icon-removefeatured" style="margin: 0 auto;"><a onclick="joms.featured.remove('<?php echo $user->id;?>','search');" href="javascript:void(0);"><?php echo JText::_('CC REMOVE FEATURED'); ?></a></div>
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
<?php
}
?>
<?php echo $sortings; ?>

<?php if( !empty( $data ) ) { ?>
	<?php foreach( $data as $row ) : ?>
		<?php $displayname = $row->user->getDisplayName(); ?>
		<?php if(!empty($row->user->id) && !empty($displayname)) : ?>
<div class="mini-profile">
	<div class="mini-profile-avatar">
		<a href="<?php echo $row->profileLink; ?>"><img class="avatar" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" /></a>
	</div>
	<div class="mini-profile-details">
		<h3 class="name">
			<a href="<?php echo $row->profileLink; ?>"><strong><?php echo $row->user->getDisplayName(); ?></strong></a>
		</h3>
		<div class="mini-profile-details-status"><?php echo $row->user->getStatus() ;?></div>
		<div class="mini-profile-details-action">
		    <span class="icon-group">
		    	<?php echo JText::sprintf( (CStringHelper::isPlural($row->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT', $row->friendsCount);?>
		    </span>

		    <?php if( $config->get('enablepm') ): ?>
	        <span class="icon-write">	            
	            <a onclick="<?php echo CMessaging::getPopup($row->user->id); ?>" href="javascript:void(0);">	            	            
	            <?php echo JText::_('CC WRITE MESSAGE'); ?>
	            </a>
	        </span>
	        <?php endif; ?>
	        
			<?php if($row->addFriend) { ?>
			    <span class="icon-add-friend">
					<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('CC ADD AS FRIEND'); ?></span></a>
				</span>
			<?php } ?>
			<?php
			if( $isCommunityAdmin )
			{
				if( !in_array($row->user->id, $featuredList) )
				{
			?>
					<span class="icon-addfeatured" id="featured-<?php echo $row->user->id;?>">	            
			            <a onclick="joms.featured.add('<?php echo $row->user->id;?>','search');" href="javascript:void(0);">	            	            
			            <?php echo JText::_('CC MAKE FEATURED'); ?>
			            </a>
			        </span>
			<?php			
				}
			}
			?>	        
		</div>
		
		<?php if($row->user->isOnline()): ?>
		<span class="icon-online-overlay">
	    	<?php echo JText::_('CC ONLINE'); ?>
	    </span>
	    <?php endif; ?>

		
	</div>
	<div class="clr"></div>
</div>
		<?php endif; ?>
	<?php endforeach; ?>	
	
	<?php echo (isset($pagination)) ? '<div class="pagination-container">'.$pagination->getPagesLinks().'</div>' : ''; ?>	
<?php } else { ?>

	<?php if (isset($filter) && count($filter) > 0) { ?>
		<div style="border:1px solid #00CCFF; padding:20px; background-color:#CCFFFF">
		<?php echo JText::_('CC NO RESULT FROM CUSTOM SEARCH');?>
		</div>
	<?php } ?>

<?php } ?>

<script type="text/javascript" src="<?php echo JURI::root(); ?>/components/com_community/templates/blackout/js/script.js"></script>