<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 */
defined('_JEXEC') or die(); ?>

<script type="text/javascript">joms.filters.bind();</script>

<?php $this->renderModules( 'js_profile_top' ); ?>

<?php echo $adminControlHTML; ?>
<div class="page-actions">
	<?php echo $blockUserHTML;?>
    <?php echo $reportsHTML;?>
    <?php echo $bookmarksHTML;?>
    <div class="clr"></div>
</div>

<div class="profile-right">
    <!-- Avatar -->
    <div class="profile-avatar" style="margin: 0 0 10px;">
        <img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" width="160" />
    </div>
    <?php if( $config->get('enablevideos') ){ ?>
    <?php if( $config->get('enableprofilevideo') ){ ?>
	<div style="text-align: center;"><a class="icon-videos" onclick="joms.videos.playProfileVideo( <?php echo $videoid; ?> , <?php echo $user->id; ?> )" href="javascript:void(0);"><?php echo JText::_('CC MY PROFILE VIDEO');?></a></div>
	<?php } ?>
        <?php } ?>
    <div>&nbsp;</div>
    <?php $this->renderModules( 'js_side_top' ); ?>
    <?php $this->renderModules( 'js_profile_side_top' ); ?>
	<?php echo $sidebarTop; ?>
    
    
    <?php echo $friends; ?>
    <?php echo $groups; ?>
    
    
    <?php echo $sidebarBottom; ?>
	<?php $this->renderModules( 'js_profile_side_bottom' ); ?>
    <?php $this->renderModules( 'js_side_bottom' ); ?>
</div>

<div class="profile-main">
    <?php echo @$header; ?>

    <div id="user-info-button">
        <?php if($config->get('enablekarma')){ ?>
        <div class="user-green">
            <div class="user-green-inner">
                <div class="number"><?php echo $user->_points; ?></div>
                <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($user->_points)) ? 'CC POINTS' : 'CC SINGULAR POINT' ); ?></div>
            </div>
        </div>
        <?php } ?>
        <div class="user-blue">
            <div class="user-blue-inner">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=mygroups&userid='.$user->id); ?>">
                    <div class="number"><?php echo $totalgroups; ?></div>
                    <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalgroups)) ? 'CC GROUPS' : 'CC SINGULAR GROUP' ); ?></div>
                </a>
            </div>
        </div>
    
        <div class="user-grey">
            <div class="user-grey-inner">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid='.$user->id); ?>">
                    <div class="number"><?php echo $totalfriends; ?></div>
                    <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalfriends)) ? 'CC FRIENDS' : 'CC SINGULAR FRIEND' ); ?></div>
                </a>
            </div>
        </div>
        <?php if( $config->get('enablephotos') ) { ?>
        <div class="user-orange">
            <div class="user-orange-inner">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$user->id); ?>">
                    <div class="number"><?php echo $totalphotos; ?></div>
                    <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalphotos)) ? 'CC PHOTOS' : 'CC SINGULAR PHOTO' ); ?></div>
                </a>
            </div>
        </div>
        <?php } ?>
        <div class="user-red">
            <div class="user-red-inner">
                <div class="number"><?php echo (!$totalactivities == 0) ? $totalactivities : 0; ?></div>
                <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalactivities)) ? 'CC ACTIVITIES' : 'CC ACTIVITY' ); ?></div>
            </div>
        </div>
        <div class="clr"></div>
    </div>   
    
    <?php echo $about; ?>
    <?php $this->renderModules( 'js_profile_feed_top' ); ?>    
    <div id="activity-stream-nav" class="see-all" style="position: relative;">
        <a class="p-active-profile-and-friends-activity active-state" href="javascript:void(0);"><?php echo JText::sprintf('CC PROFILE OWNER AND FRIENDS' , $profileOwnerName );?></a>
        <a class="p-active-profile-activity" href="javascript:void(0);"><?php echo $profileOwnerName ?></a>
        <div class="loading" style="display: none; position: absolute; top: 6px; right: 180px;"></div>
    </div>
    <div style="position: relative;">
        <div id="activity-stream-container">
        <?php echo $newsfeed; ?>
        </div>
    </div>
    <?php $this->renderModules( 'js_profile_feed_bottom' ); ?>    
    <?php echo $content; ?>
</div>

<div class="clr"></div>

<?php $this->renderModules( 'js_profile_bottom' ); ?>

<?php /* Insert plugin javascript at the bottom */ echo $jscript; ?>