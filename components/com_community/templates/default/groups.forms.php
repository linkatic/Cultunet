<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	categories Array	An array of categories
 */
defined('_JEXEC') or die();
?>
<form method="post" action="<?php echo CRoute::getURI(); ?>" id="createGroup" name="jsform-groups-create" class="community-form-validate">
<div id="community-groups-wrap">
<?php if($isNew) { ?>
	<p>
		<?php echo JText::_('CC CREATE GROUP DESCRIPTION'); ?>		 
	</p>
	<?php
	if( $groupCreationLimit != 0 )
	{
	?>
	<div class="hints">
		<?php echo JText::sprintf('CC GROUP CREATION LIMIT STATUS', $groupCreated, $groupCreationLimit );?>
	</div>
	<?php
	}
	?>
<?php } ?>

	<table class="formtable" cellspacing="1" cellpadding="0">
	<tr>
		<td colspan="2"><?php echo $beforeFormDisplay;?></td>
	</tr>
	<!-- group name -->
	<tr>
		<td class="key">
			<label for="name" class="label title jomTips" title="<?php echo JText::_('CC GROUP NAME');?>::<?php echo JText::_('CC GROUP NAME TIPS'); ?>">
				*<?php echo JText::_('CC GROUP NAME'); ?>
			</label>
		</td>
		<td class="value">
			<input name="name" id="name" type="text" size="45" class="required inputbox" value="<?php echo $this->escape($group->name); ?>" />
		</td>
	</tr>
	<!-- group description -->
	<tr>
		<td class="key">
			<label for="description" class="label title jomTips" title="<?php echo JText::_('CC GROUP DESCRIPTION');?>::<?php echo JText::_('CC GROUP DESCRIPTION TIPS');?>">
				*<?php echo JText::_('CC GROUP DESCRIPTION');?>
			</label>
		</td>
		<td class="value">
			<textarea name="description" id="description" class="required inputbox"><?php echo $this->escape($group->description); ?></textarea>
		</td>
	</tr>
	<!-- group category -->
	<tr>
		<td class="key">
			<label for="categoryid" class="label title jomTips" title="<?php echo JText::_('CC GROUP CATEGORY');?>::<?php echo JText::_('CC GROUP CATEGORY TIPS');?>">
				*<?php echo JText::_('CC GROUP CATEGORY');?>
			</label>
		</td>
		<td class="value">
			<select name="categoryid" id="categoryid" class="required inputbox">
			<?php
			foreach( $categories as $category )
			{
				$selected	= ( $group->categoryid == $category->id ) ? ' selected="selected"' : '';
			?>
				<option value="<?php echo $category->id; ?>"<?php echo $selected;?>><?php echo JText::_( $this->escape($category->name) ); ?></option>
			<?php
			}
			?>
			</select>
		</td>
	</tr>
	<!-- group type -->
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC GROUP TYPE');?>::<?php echo JText::_('CC GROUP REQUIRE APPROVAL TIPS');?>">
				<?php echo JText::_('CC GROUP TYPE'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="approvals" id="approve-open" value="0"<?php echo ($group->approvals == COMMUNITY_PUBLIC_GROUP ) ? ' checked="checked"' : '';?> />
				<label for="approve-open" class="label lblradio"><?php echo JText::_('CC OPEN GROUP');?></label>
			</div>
			<div style="margin-bottom: 10px;" class="small">
				<?php echo JText::_('CC OPEN GROUP DESCRIPTION');?>
			</div>
			
			<div>
				<input type="radio" name="approvals" id="approve-private" value="1"<?php echo ($group->approvals == COMMUNITY_PRIVATE_GROUP ) ? ' checked="checked"' : '';?> />
				<label for="approve-private" class="label lblradio"><?php echo JText::_('CC PRIVATE GROUP');?></label>
			</div>
			<div class="small">
				<?php echo JText::_('CC PRIVATE GROUP DESCRIPTION');?>
			</div>
		</td>
	</tr>
	
	
	<!-- group ordering -->
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC DISCUSS ORDERING');?>::<?php echo JText::_('CC DISCUSS ORDERING TIPS');?>">
				<?php echo JText::_('CC DISCUSS ORDERING'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="discussordering" id="discussordering-lastreplied" value="0"<?php echo ($params->get('discussordering') == 0 ) ? ' checked="checked"' : '';?> />
				<label for="discussordering-lastreplied" class="label lblradio"><?php echo JText::_('CC DISCUSS ORDER BY LAST REPLIED');?></label>
			</div>
			<div>
				<input type="radio" name="discussordering" id="discussordering-creation" value="1"<?php echo ($params->get('discussordering') == 1 ) ? ' checked="checked"' : '';?> />
				<label for="discussordering-creation" class="label lblradio"><?php echo JText::_('CC DISCUSS ORDER BY CREATION');?></label>
			</div>
		</td>
	</tr>	
	
	<?php if($config->get('enablephotos') && $config->get('groupphotos')): ?>
	<!-- group photos -->
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC PHOTOS');?>::<?php echo JText::_('CC GROUP PHOTOS PERMISSION TIPS');?>">
				<?php echo JText::_('CC PHOTOS'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="photopermission" id="photopermission-disabled" value="-1"<?php echo ($params->get('photopermission') == GROUP_PHOTO_PERMISSION_DISABLE ) ? ' checked="checked"' : '';?> />
				<label for="photopermission-disabled" class="label lblradio"><?php echo JText::_('CC GROUP PHOTO DISABLED');?></label>
			</div>
			<div>
				<input type="radio" name="photopermission" id="photopermission-members" value="0"<?php echo ($params->get('photopermission') == GROUP_PHOTO_PERMISSION_MEMBERS ) ? ' checked="checked"' : '';?> />
				<label for="photopermission-members" class="label lblradio"><?php echo JText::_('CC GROUP PHOTO ALLOW MEMBERS UPLOAD');?></label>
			</div>
			<div>
				<input type="radio" name="photopermission" id="photopermission-admin" value="1"<?php echo ($params->get('photopermission') == GROUP_PHOTO_PERMISSION_ADMINS ) ? ' checked="checked"' : '';?> />
				<label for="photopermission-admin" class="label lblradio"><?php echo JText::_('CC GROUP PHOTO ALLOW ONLY ADMINS UPLOAD');?></label>
			</div>		
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="grouprecentphotos-admin" class="label title jomTips" title="<?php echo JText::_('CC GROUP RECENT PHOTOS LIMIT');?>::<?php echo JText::_('CC GROUP RECENT PHOTOS LIMIT TIPS');?>">
				<?php echo JText::_('CC GROUP RECENT PHOTOS LIMIT');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="grouprecentphotos" id="grouprecentphotos-admin" size="1" value="<?php echo $params->get('grouprecentphotos', GROUP_PHOTO_RECENT_LIMIT);?>" />
		</td>
	</tr>
	<?php endif;?>
	<?php if($config->get('enablevideos') && $config->get('groupvideos')): ?>
	<!-- group videos -->
	<tr>
		<td class="key">
			<label for="discussordering" class="label title jomTips" title="<?php echo JText::_('CC VIDEOS');?>::<?php echo JText::_('CC GROUP VIDEOS PERMISSION TIPS');?>">
				<?php echo JText::_('CC VIDEOS'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="videopermission" id="videopermission-disabled" value="-1"<?php echo ($params->get('videopermission') == GROUP_VIDEO_PERMISSION_DISABLE ) ? ' checked="checked"' : '';?> />
				<label for="videopermission-disabled" class="label lblradio"><?php echo JText::_('CC GROUP VIDEO DISABLED');?></label>
			</div>
			<div>
				<input type="radio" name="videopermission" id="videopermission-members" value="0"<?php echo ($params->get('videopermission') == GROUP_VIDEO_PERMISSION_MEMBERS ) ? ' checked="checked"' : '';?> />
				<label for="videopermission-members" class="label lblradio"><?php echo JText::_('CC GROUP VIDEO ALLOW MEMBERS UPLOAD');?></label>
			</div>
			<div>
				<input type="radio" name="videopermission" id="videopermission-admin" value="1"<?php echo ($params->get('videopermission') == GROUP_VIDEO_PERMISSION_ADMINS ) ? ' checked="checked"' : '';?> />
				<label for="videopermission-admin" class="label lblradio"><?php echo JText::_('CC GROUP VIDEO ALLOW ONLY ADMINS UPLOAD');?>
			</div>
			
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="grouprecentvideos-admin" class="label title jomTips" title="<?php echo JText::_('CC GROUP RECENT VIDEO LIMIT');?>::<?php echo JText::_('CC GROUP RECENT VIDEO LIMIT TIPS');?>">
				<?php echo JText::_('CC GROUP RECENT VIDEO LIMIT');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="grouprecentvideos" id="grouprecentvideos-admin" size="1" value="<?php echo $params->get('grouprecentvideos', GROUP_VIDEO_RECENT_LIMIT);?>" />
		</td>
	</tr>
	<?php endif;?>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC GROUP NEW MEMBER NOTIFICATION');?>::<?php echo JText::_('CC GROUP NEW MEMBER NOTIFICATION TIPS');?>">
				<?php echo JText::_('CC GROUP NEW MEMBER NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="newmembernotification" id="newmembernotification-enable" value="1"<?php echo ($params->get('newmembernotification', '1') == true ) ? ' checked="checked"' : '';?> />
				<label for="newmembernotification-enable" class="label lblradio"><?php echo JText::_('CC ENABLE');?></label>
			</div>
			<div>
				<input type="radio" name="newmembernotification" id="newmembernotification-disable" value="0"<?php echo ($params->get('newmembernotification', '1') == false ) ? ' checked="checked"' : '';?> />
				<label for="newmembernotification-disable" class="label lblradio"><?php echo JText::_('CC DISABLE');?></label>
			</div>			
		</td>
	</tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC GROUP JOIN REQUEST NOTIFICATION');?>::<?php echo JText::_('CC GROUP JOIN REQUEST NOTIFICATION TIPS');?>">
				<?php echo JText::_('CC GROUP JOIN REQUEST NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="joinrequestnotification" id="joinrequestnotification-enable" value="1"<?php echo ($params->get('joinrequestnotification', '1') == true ) ? ' checked="checked"' : '';?> />
				<label for="joinrequestnotification-enable" class="label lblradio"><?php echo JText::_('CC ENABLE');?></label>
			</div>
			<div>
				<input type="radio" name="joinrequestnotification" id="joinrequestnotification-disable" value="0"<?php echo ($params->get('joinrequestnotification', '1') == false ) ? ' checked="checked"' : '';?> />
				<label for="joinrequestnotification-disable" class="label lblradio"><?php echo JText::_('CC DISABLE');?></label>
			</div>			
		</td>
	</tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('CC GROUP WALL POST NOTIFICATION');?>::<?php echo JText::_('CC GROUP WALL POST NOTIFICATION TIPS');?>">
				<?php echo JText::_('CC GROUP WALL POST NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="wallnotification" id="wallnotification-enable" value="1"<?php echo ($params->get('wallnotification', '1') == true ) ? ' checked="checked"' : '';?> />
				<label for="wallnotification-enable" class="label lblradio"><?php echo JText::_('CC ENABLE');?></label>
			</div>
			<div>
				<input type="radio" name="wallnotification" id="wallnotification-disable" value="0"<?php echo ($params->get('wallnotification', '1') == false ) ? ' checked="checked"' : '';?> />
				<label for="wallnotification-disable" class="label lblradio"><?php echo JText::_('CC DISABLE');?></label>
			</div>			
		</td>
	</tr>
	<?php if(! $isNew): ?>
	<tr>
		<td class="key">
			<label for="removeactivities" class="label title jomTips" title="<?php echo JText::_('CC REMOVE GROUP ACTIVITIES');?>::<?php echo JText::_('CC REMOVE GROUP ACTIVITIES TIPS');?>">
				<?php echo JText::_('CC REMOVE GROUP ACTIVITIES');?>
			</label>
		</td>
		<td class="value">
			<input type="checkbox" name="removeactivities" id="removeactivities" value="1" />
			<div class="small"><?php echo JText::_('CC REMOVE GROUP ACTIVITIES TIPS');?></div>
		</td>
	</tr>
	<?php endif;?>
	<!-- group hint -->
	<tr>
		<td class="key"></td>
		<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $afterFormDisplay;?></td>
	</tr>	
	
	<!-- group buttons -->
	<tr>
		<td class="key"></td>
		<td class="value">
			<?php if($isNew): ?>
			<input name="action" type="hidden" value="save" />
			<?php endif;?>
			<input type="hidden" name="groupid" value="<?php echo $group->id;?>" />
			<input type="submit" value="<?php echo ($isNew) ? JText::_('CC BUTTON CREATE GROUP') : JText::_('CC BUTTON SAVE');?>" class="button validateSubmit" />
			<input type="button" class="button" onclick="history.go(-1);return false;" value="<?php echo JText::_('CC BUTTON CANCEL');?>" /> 
			<?php echo JHTML::_( 'form.token' ); ?> 
		</td>
	</tr>
	</table>

</div>

</form>
<script type="text/javascript">
	cvalidate.init();
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>