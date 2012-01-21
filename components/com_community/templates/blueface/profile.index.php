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

<!-- begin: #cProfileWrapper -->
<div id="cProfileWrapper">

    <!-- begin: .cLayout -->
    <div class="cLayout clrfix">
    
        <?php $this->renderModules( 'js_profile_top' ); ?>
		<?php echo $adminControlHTML; ?>
        <div class="page-actions">
        	<?php echo $blockUserHTML;?>
            <?php echo $reportsHTML;?>
            <?php echo $bookmarksHTML;?>
            <div class="clr"></div>
        </div>

        <!-- begin: .cSidebar -->
        <div class="cSidebar clrfix">
            <div class="profile-avatar"><img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>"/></div>
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
        <!-- end: .cSidebar -->

        <div class="cMain clrfix">

            <?php echo @$header; ?>

        <div style="padding-bottom: 20px;">
        <table cellpadding="3" cellspacing="3" border="0" width="100%" class="table-info">
                <tr>
                    <?php if($config->get('enablekarma')){ ?>
                    <td align="center" valign="top" style="width: 20%">
                        <div class="number"><?php echo $user->_points; ?></div>
                        <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($user->_points)) ? 'CC POINTS' : 'CC SINGULAR POINT' ); ?></div>
                    </td>
                    <?php } ?>
                    <td align="center" valign="top" style="width: 20%">
                        <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&userid='.$user->id); ?>">
                            <div class="number"><?php echo $totalgroups; ?></div>
                            <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalgroups)) ? 'CC GROUPS' : 'CC SINGULAR GROUP' ); ?></div>
                        </a>
                    </td>
                    
                    <td align="center" valign="top" style="width: 20%">
                        <a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid='.$user->id); ?>">
                            <div class="number"><?php echo $totalfriends; ?></div>
                            <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalfriends)) ? 'CC FRIENDS' : 'CC SINGULAR FRIEND' ); ?></div>
                        </a>
                    </td>
                    <?php
                        if( $config->get('enablephotos') )
                        {
                    ?>
                    <td align="center" valign="top" style="width: 20%">
                        <a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$user->id); ?>">
                            <div class="number"><?php echo $totalphotos; ?></div>
                            <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalphotos)) ? 'CC PHOTOS' : 'CC SINGULAR PHOTO' ); ?></div>
                        </a>
                    </td>
                    <?php
                        }
                    ?>
                    <td align="center" valign="top" style="width: 20%">
                        <div class="number">
                        <?php
                        if ( !$totalactivities == '' OR $totalactivities > 0 ) {
                            echo $totalactivities;
                        }
                        else {
                            echo 0;
                        }
                         ?>
                         </div>
                         <div class="text"><?php echo JText::sprintf( (CStringHelper::isPlural($totalactivities)) ? 'CC ACTIVITIES' : 'CC ACTIVITY' ); ?></div>
                    </td>
                </tr>
            </table>
            </div>

            <?php echo $about; ?>            

            <!-- begin: Activity Stream -->
            <div class="app-box">
                <div class="app-box-header">
                    <h2 class="app-box-title"><?php echo JText::_('CC RECENT ACTIVITIES'); ?></h2>
                </div>
                <div class="app-box-content">
                    <?php $this->renderModules( 'js_profile_feed_top' ); ?>
                    <div id="activity-stream-nav" class="filterlink">
                        <a class="p-active-profile-and-friends-activity active-state" href="javascript:void(0);"><?php echo JText::sprintf('CC PROFILE OWNER AND FRIENDS' , $profileOwnerName );?></a>
                        <a class="p-active-profile-activity" href="javascript:void(0);"><?php echo $profileOwnerName ?></a>
                        <div class="loading"></div>
                    </div>
                    <div id="activity-stream-container"><?php echo $newsfeed; ?></div>
                    <?php $this->renderModules( 'js_profile_feed_bottom' ); ?>
                </div>
            </div>
            <!-- end: Activity Stream -->
            
            <?php echo $content; ?>
                    
        </div>
        
        <?php $this->renderModules( 'js_profile_bottom' ); ?>
    </div>
    <!-- end: .cLayout -->

</div>
<!-- end: #cProfileWrapper -->

<?php /* Insert plugin javascript at the bottom */ echo $jscript; ?>