<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Group add html
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// getting client variable
$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

?>

<link rel="stylesheet" type="text/css" href="<?php echo $uri->root(); ?>administrator/components/com_gk3_photoslide/interface/css/group.add.css" media="all" />

<?php if($colorpickers == 1) : ?>		
<script type="text/javascript" src="components/com_gk3_photoslide/interface/colorpicker/js/jquery.js"></script>

<link rel="stylesheet" media="screen" type="text/css" href="components/com_gk3_photoslide/interface/colorpicker/css/colorpicker.css" />

<script type="text/javascript" src="components/com_gk3_photoslide/interface/colorpicker/js/colorpicker.js"></script>

<script type="text/javascript">
	jQuery.noConflict();
	window.addEvent("load",function(){	
		jQuery('#group_background').ColorPicker({
			onSubmit: function(hsb, hex, rgb) {
				jQuery('#group_background').val('#'+hex);
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value.substr(1));
			}
		}).bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
		});
	});
</script>		
<?php endif; ?>

		
<script type="text/javascript">
	window.addEvent("domready", function(){
		$E("#toolbar-save .toolbar").onclick = function(){
    		var hexre = new RegExp(/^#([0-9a-fA-F]){3}(([0-9a-fA-F]){3})?$/);
			var alert_content = '';
			if($("group_name").getValue() == '') alert_content += '<?php echo JText::_('WRONG_GROUP_NAME'); ?>\n';
			if($("group_desc").getValue() == '') alert_content += '<?php echo JText::_('WRONG_GROUP_DESC'); ?>\n';
				
			if($("group_image_x").getValue() == '' || isNaN($("group_image_x").getValue()) || $("group_image_x").getValue() < 0) alert_content += '<?php echo JText::_('WRONG_GROUP_IMAGE_X'); ?>\n';
			if($("group_image_y").getValue() == '' || isNaN($("group_image_y").getValue()) || $("group_image_y").getValue() < 0) alert_content += '<?php echo JText::_('WRONG_GROUP_IMAGE_Y'); ?>\n';
			if($('group_type').value == 'Image Show 1'){
				if($("group_thumb_x").getValue() == '' || isNaN($("group_thumb_x").getValue()) || $("group_thumb_x").getValue() < 0) alert_content += '<?php echo JText::_('WRONG_GROUP_THUMB_X'); ?>\n';
				if($("group_thumb_y").getValue() == '' || isNaN($("group_thumb_y").getValue()) || $("group_thumb_y").getValue() < 0) alert_content += '<?php echo JText::_('WRONG_GROUP_THUMB_Y'); ?>\n';
			}
			
			if($("group_background").getValue() == '' || (!hexre.test($("group_background").getValue()) && $("group_background").getValue() != 'transparent')) alert_content += '<?php echo JText::_('WRONG_GROUP_BACKGROUND'); ?>\n';
			if($("group_default_quality").getValue() == '' || isNaN($("group_default_quality").getValue()) || $("group_default_quality").getValue() < 0) alert_content += '<?php echo JText::_('WRONG_GROUP_QUALITY'); ?>\n';
			(alert_content != '') ? alert(alert_content) : submitbutton('add_group');
		}
		
		$('group_type').addEvent("change", function(){
			switch($('group_type').value)
			{
				case 'Image Show 1' : 
					$("form_thumb_x").setStyle("display",(!window.ie) ? "table-row" : "block");
					$("form_thumb_y").setStyle("display",(!window.ie) ? "table-row" : "block");
				break;
				
				case 'News Show' :
				case 'News Show Pro' : 
					$("form_thumb_x").setStyle("display","none");
					$("form_thumb_y").setStyle("display","none");
					$("group_thumb_x").value = '0';
					$("group_thumb_y").value = '0';			
				break;
			}
		});
	});
</script>

<div id="wrapper">

	<?php 
		ViewNavigation::generate(array(
			JText::_("GROUPS") => 'option=com_gk3_photoslide&c=group',
			JText::_("ADD_GROUP") => 'option=com_gk3_photoslide&c=group&task=add'
		)); 
	?>
	
	<div id="groups">
		<form action="index.php" method="post" name="adminForm">
			<table class="adminlist">
				<tbody>
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_NAME'); ?></td>
						<td><input type="text" name="name" value="" id="group_name" maxlength="100" /></td>
					</tr>
		
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_DESCRIPTION'); ?></td>
						<td><textarea name="desc" id="group_desc" ></textarea></td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_SELECT_GROUP_TYPE'); ?></td>
						<td>
							<select name="type" id="group_type">
								<option value="Image Show 1" selected="selected">Group for Image Show</option>
								<option value="News Show Pro">Group for News Show Pro GK1 (version &gt;= 1.1.0)</option>
							</select>
						</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_IMAGE_X'); ?></td>
						<td><input type="text" name="image_x" value="" id="group_image_x" maxlength="100" />px</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_IMAGE_Y'); ?></td>
						<td><input type="text" name="image_y" value="" id="group_image_y" maxlength="100" />px</td>	
					</tr>					

					<tr id="form_thumb_x">
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_THUMB_X'); ?></td>
						<td><input type="text" name="thumb_x" value="0" id="group_thumb_x" maxlength="100" />px</td>	
					</tr>
					
					<tr id="form_thumb_y">
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_THUMB_Y'); ?></td>
						<td><input type="text" name="thumb_y" value="0" id="group_thumb_y" maxlength="100" />px</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_BACKGROUND'); ?></td>
						<td><input type="text" name="background" value="" id="group_background" maxlength="100" /></td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('ADD_GROUP_GROUP_DEFAULT_QUALITY'); ?></td>
						<td><input type="text" name="default_quality" value="" id="group_default_quality" maxlength="100" />%</td>	
					</tr>					
					
				</tbody>
			</table>
			
			<input type="hidden" name="option" value="com_gk3_photoslide" />
			<input type="hidden" name="default_image" value="0" />
			<input type="hidden" name="client" value="<?php echo $client->id;?>" />
			<input type="hidden" name="c" value="group" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>	
	</div>
</div>