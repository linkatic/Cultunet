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

<!-- Featured Members -->
<div class="app-box" id="latest-members">
	<div class="app-box-header">
    <div class="app-box-header">    
    	<h2 class="app-box-title"><?php echo JText::_('CC MEMBERS'); ?></h2>
        <div class="app-box-menus">
            <div class="app-box-menu toggle">
                <a class="app-box-menu-icon"
                   href="javascript: void(0)"
                   onclick="joms.apps.toggle('#latest-members');">
                    <span class="app-box-menu-title"><?php echo JText::_('CC EXPAND');?></span>
                </a>
            </div>
        </div>
    </div>
	</div>

    <div class="app-box-content">
    	<!-- begin: COMMUNITY_FREE_VERSION -->
    	<?php if( !COMMUNITY_FREE_VERSION ) { ?>
        <div id="latest-members-nav" class="filterlink">
            <div style="float: right;">
                <a class="newest-member active-state" href="javascript:void(0);"><?php echo JText::_('CC NEWEST MEMBERS') ?></a>
                <a class="featured-member" href="javascript:void(0);"><?php echo JText::_('CC FEATURED MEMBERS') ?></a>
                <a class="active-member" href="javascript:void(0);"><?php echo JText::_('CC ACTIVE MEMBERS') ?></a>
                <a class="popular-member" href="javascript:void(0);"><?php echo JText::_('CC POPULAR MEMBERS') ?></a>
            </div>
            <div class="loading"></div>
        </div>
        <?php } ?>
        <!-- end: COMMUNITY_FREE_VERSION -->
                    
        <div id="latest-members-container"><?php echo $memberList ?></div>
	</div>
    
    <div class="app-box-footer">
        <a href="<?php echo CRoute::_('/index.php?option=com_community&view=search&task=browse' ); ?>" class="app-title-link">
			<?php echo JText::_( 'CC BROWSE ALL' ); ?> (<?php echo $totalMembers;?>)
		</a>
    </div>
</div>
<!-- Featured Members -->