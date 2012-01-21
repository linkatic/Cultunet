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
        <div class="cFilterBar clrfix">
            <div class="filterGroup">
                <ul class="filterOptions">
                    <li id="filter_newestMember" class="filterOption active" onclick="joms_filters_activate(this);"><a href="javascript:void(0);"><?php echo JText::_('CC NEWEST MEMBERS') ?></a></li>
                    <li id="filter_featuredMember" class="filterOption" onclick="joms_filters_activate(this);"><a href="javascript:void(0);"><?php echo JText::_('CC FEATURED MEMBERS') ?></a></li>
                    <li id="filter_activeMember" class="filterOption" onclick="joms_filters_activate(this);"><a href="javascript:void(0);"><?php echo JText::_('CC ACTIVE MEMBERS') ?></a></li>
                    <li id="filter_popularMember" class="filterOption" onclick="joms_filters_activate(this);"><a href="javascript:void(0);"><?php echo JText::_('CC POPULAR MEMBERS') ?></a></li>
                </ul>
            </div>
        </div>
        <div id="latest-members-container"><?php echo $memberList ?></div>
        <div class="app-box-footer no-border">
            <a href="<?php echo CRoute::_('index.php?option=com_community&view=search&task=browse' ); ?>" class="app-title-link"><?php echo JText::sprintf('CC BROWSE ALL' , $totalMembers ); ?> &raquo;</a>
        </div>
	</div>
</div>