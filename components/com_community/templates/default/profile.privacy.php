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


<form method="post" action="<?php echo CRoute::getURI();?>" name="jsform-profile-privacy">

<div class="ctitle"><h2><?php echo JText::_('CC EDIT YOUR PRIVACY');?></h2></div>
<p><?php echo JText::_('CC EDIT PRIVACY DESCRIPTION');?></p>

<table class="formtable" cellspacing="1" cellpadding="0">
<tr>
	<td colspan="2"><?php echo $beforeFormDisplay;?></td>
</tr>
<!-- profile privacy -->
<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY PROFILE FIELD');?></label>
	</td>
	<td class="value">
    	<input name="privacyProfileView" type="radio" id="profile-public" value="0" <?php if($params->get('privacyProfileView') == 0) { ?>checked="checked" <?php } ?>/>
        <label for="profile-public" class="lblradio"><?php echo JText::_('CC PRIVACY PUBLIC');?></label>
        
        <input type="radio" value="20" id="profile-members" name="privacyProfileView" <?php if($params->get('privacyProfileView') == 20) { ?>checked="checked" <?php } ?> />
        <label for="profile-members" class="lblradio"><?php echo JText::_('CC PRIVACY SITE MEMBERS');?></label>
        
        <!-- begin: COMMUNITY_FREE_VERSION -->
        <?php if( !COMMUNITY_FREE_VERSION ){ ?>
        <input type="radio" value="30" id="profile-friends" name="privacyProfileView" <?php if($params->get('privacyProfileView') == 30)  { ?>checked="checked" <?php } ?>/>
        <label for="profile-friends" class="lblradio"><?php echo JText::_('CC PRIVACY FRIENDS'); ?></label>
        <?php } ?>
        <!-- end: COMMUNITY_FREE_VERSION -->
	</td>
</tr>


<!-- friends privacy -->
<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY FRIENDS FIELD'); ?></label>
	</td>
	<td class="value">
    	<input type="radio" value="0" id="friends-public" name="privacyFriendsView" <?php if($params->get('privacyFriendsView') == 0) { ?>checked="checked" <?php } ?>/>
        <label for="friends-public" class="lblradio"><?php echo JText::_('CC PRIVACY PUBLIC'); ?></label>

		<input type="radio" value="20" id="friends-members" name="privacyFriendsView" <?php if($params->get('privacyFriendsView') == 20) { ?>checked="checked" <?php } ?>/>
		<label for="friends-members" class="lblradio"><?php echo JText::_('CC PRIVACY SITE MEMBERS'); ?></label>
        
        <!-- begin: COMMUNITY_FREE_VERSION -->
        <?php if( !COMMUNITY_FREE_VERSION ){ ?>
		<input type="radio" value="30" id="friends-friends" name="privacyFriendsView" <?php if($params->get('privacyFriendsView') == 30)  { ?>checked="checked" <?php } ?>/>
	    <label for="friends-friends" class="lblradio"><?php echo JText::_('CC PRIVACY FRIENDS'); ?></label>
        
	    <input type="radio" value="40" id="friends-me" name="privacyFriendsView" <?php if($params->get('privacyFriendsView') == 40) { ?>checked="checked" <?php } ?>/>
	    <label for="friends-me" class="lblradio"><?php echo JText::_('CC PRIVACY ME'); ?></label>
        <?php } ?>
        <!-- end: COMMUNITY_FREE_VERSION -->
	</td>
</tr>


<!-- photos privacy -->
<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY PHOTOS FIELD'); ?></label>
	</td>
	<td class="value">
    	<input type="radio" value="0" id="photo-public" name="privacyPhotoView" <?php if($params->get('privacyPhotoView') == 0) { ?>checked="checked" <?php } ?> />
        <label for="photo-public" class="lblradio"><?php echo JText::_('CC PRIVACY PUBLIC'); ?></label>

		<input type="radio" value="20" id="photo-members" name="privacyPhotoView" <?php if($params->get('privacyPhotoView') == 20) { ?>checked="checked" <?php } ?> />
		<label for="photo-members" class="lblradio"><?php echo JText::_('CC PRIVACY SITE MEMBERS'); ?></label>
        
        <!-- begin: COMMUNITY_FREE_VERSION -->
        <?php if( !COMMUNITY_FREE_VERSION ){ ?>
		<input type="radio" value="30" id="photo-friends" name="privacyPhotoView" <?php if($params->get('privacyPhotoView') == 30)  { ?>checked="checked" <?php } ?> />
	    <label for="photo-friends" class="lblradio"><?php echo JText::_('CC PRIVACY FRIENDS'); ?></label>
        
	    <input type="radio" value="40" id="photo-me" name="privacyPhotoView" <?php if($params->get('privacyPhotoView') == 40) { ?>checked="checked" <?php } ?> />
	    <label for="photo-me" class="lblradio"><?php echo JText::_('CC PRIVACY ME'); ?></label>
        <?php } ?>
        <!-- end: COMMUNITY_FREE_VERSION -->
	</td>
</tr>

</table>




<div class="ctitle"><h2><?php echo JText::_('CC EDIT EMAIL PRIVACY'); ?></h2></div>


<table class="formtable" cellspacing="1" cellpadding="0">

<!-- system email -->
<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY RECEIVE SYSTEM MAIL'); ?></label>
	</td>
	<td class="value">
    	<input name="notifyEmailSystem" id="email-privacy-yes" type="radio" value="1" <?php if($params->get('notifyEmailSystem') == 1) { ?>checked="checked" <?php } ?> />
        <label for="email-privacy-yes" class="lblradio"><?php echo JText::_('CC PRIVACY YES'); ?></label>
        
        <input type="radio" value="0" id="email-privacy-no" name="notifyEmailSystem" <?php if($params->get('notifyEmailSystem') == 0) { ?>checked="checked" <?php } ?> />
        <label for="email-privacy-no" class="lblradio"><?php echo JText::_('CC PRIVACY NO'); ?></label>
	</td>
</tr>

<!-- apps email -->
<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY RECEIVE APPLICATION MAIL'); ?></label>
	</td>
	<td class="value">
    	<input type="radio" value="1" id="email-apps-yes" name="notifyEmailApps" <?php if($params->get('notifyEmailApps') == 1) { ?>checked="checked" <?php } ?>/>
        <label for="email-apps-yes" class="lblradio"><?php echo JText::_('CC PRIVACY YES'); ?></label>
		        
        <input type="radio" value="0" id="email-apps-no" name="notifyEmailApps" <?php if($params->get('notifyEmailApps') == 0) { ?>checked="checked" <?php } ?>/>
        <label for="email-apps-no" class="lblradio"><?php echo JText::_('CC PRIVACY NO'); ?></label>
	</td>
</tr>

<tr>
	<td class="key" style="width: 200px;">
		<label class="label"><?php echo JText::_('CC PRIVACY RECEIVE COMMENT MAIL'); ?></label>
	</td>
	<td class="value">
    	<input type="radio" value="1" id="email-wallcomment-yes" name="notifyWallComment" <?php if($params->get('notifyWallComment') == 1) { ?>checked="checked" <?php } ?>/>
        <label for="email-wallcomment-yes" class="lblradio"><?php echo JText::_('CC PRIVACY YES'); ?></label>
		        
        <input type="radio" value="0" id="email-wallcomment-no" name="notifyWallComment" <?php if($params->get('notifyWallComment') == 0) { ?>checked="checked" <?php } ?>/>
        <label for="email-wallcomment-no" class="lblradio"><?php echo JText::_('CC PRIVACY NO'); ?></label>
	</td>
</tr>
<tr>
	<td colspan="2"><?php echo $afterFormDisplay;?></td>
</tr>
<tr>
	<td class="key"></td>
	<td class="value">
		<input type="hidden" value="save" name="action" />
		<input type="submit" class="button" value="<?php echo JText::_('CC BUTTON SAVE'); ?>" />
	</td>
</tr>
</table>

</form>

<div id="community-banlists-wrap" style="padding-top: 20px;">
	<div id="community-banlists-news-items" class="app-box" style="width: 100%; float: left;margin-top: 0px;">
		<div class="app-box">
			<div class="app-box-l">
				<div class="app-box-r"><h2 class="expand app-box-title"><?php echo JText::_('CC MY BLOCK LIST');?></h2></div>
			</div>
		</div>
		<ul id="friends-list">
		<?php
			foreach( $blocklists as $row )
			{
				$user	= CFactory::getUser( $row->blocked_userid );
		?>
			<li id="friend-<?php echo $user->id;?>" class="friend-list">
				<span><img width="45" height="45" src="<?php echo $user->getThumbAvatar();?>" alt="" /></span>
				<span class="friend-name">
					<?php echo $user->getDisplayName(); ?>
					<a class="remove" href="javascript:void(0);" onclick="joms.users.unBlockUser('<?php echo $row->blocked_userid;  ?>','privacy');">
					   <?php echo JText::_('CC BLOCK'); ?>
					</a>
				</span>
			</li>
		<?php
			}
		?>
		</ul>
	</div>
</div>
