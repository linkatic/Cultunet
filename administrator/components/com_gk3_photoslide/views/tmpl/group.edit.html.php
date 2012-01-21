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
 * Info html.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// getting client variable
$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

if (!function_exists('htmlspecialchars_decode')) {
        function htmlspecialchars_decode($str, $options="") {
                $trans = get_html_translation_table(HTML_SPECIALCHARS, $options);

                $decode = ARRAY();
                foreach ($trans AS $char=>$entity) {
                        $decode[$entity] = $char;
                }

                $str = strtr($str, $decode);

                return $str;
        }
}

?>

<link rel="stylesheet" type="text/css" href="<?php echo $uri->root(); ?>administrator/components/com_gk3_photoslide/interface/css/group.edit.css" media="all" />


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
		if($("form_default_image")){
			if($('group_type').value == 'Image Show 1'){
				$("form_default_image").setStyle("display", "none");
			}
		}
		
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
			(alert_content != '') ? alert(alert_content) : submitbutton('edit_group');
		}
		
		$('group_type').addEvent("change", function(){
			switch($('group_type').value)
			{
				case 'Image Show 1' : 
					$("form_thumb_x").setStyle("display",(!window.ie) ? "table-row" : "block");
					$("form_thumb_y").setStyle("display",(!window.ie) ? "table-row" : "block");
					if($("form_default_image")){
						$("form_default_image").setStyle("display", "none");
					}
				break;
				
				case 'News Show' :
				case 'News Show Pro' : 
					$("form_thumb_x").setStyle("display","none");
					$("form_thumb_y").setStyle("display","none");
					$("group_thumb_x").value = '0';
					$("group_thumb_y").value = '0';		
					if($("form_default_image")){
						$("form_default_image").setStyle("display",(!window.ie) ? "table-row" : "block");
					}	
				break;
			}
		});
	});
</script>

<div id="wrapper">

	<?php 
		ViewNavigation::generate(array(
			JText::_("GROUPS") => 'option=com_gk3_photoslide&c=group',
			JText::_("EDIT_GROUP") => 'option=com_gk3_photoslide&c=group&task=edit&cid[]='.$data[0]
		)); 
	?>
	
	<div id="groups">
		<form action="index.php" method="post" name="adminForm">
			<table class="adminlist">
				<tbody>
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_NAME'); ?></td>
						<td><input type="text" name="name" id="group_name" maxlength="100" value="<?php echo htmlspecialchars_decode($data[1]); ?>" /></td>
					</tr>
		
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_DESCRIPTION'); ?></td>
						<td><textarea name="desc" id="group_desc" ><?php echo htmlspecialchars_decode($data[2]); ?></textarea></td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_SELECT_GROUP_TYPE'); ?></td>
						<td>
							<select name="type" id="group_type">
								<option value="Image Show 1" <?php if($data[3] == "Image Show 1") echo 'selected="selected"'; ?>>Group for Image Show</option>
								<option value="News Show Pro" <?php if($data[3] == "News Show Pro") echo 'selected="selected"'; ?>>Group for News Show Pro GK1 (version &gt;= 1.1.0)</option>
							</select>
						</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_IMAGE_X'); ?></td>
						<td><input type="text" name="image_x" value="<?php echo $data[6]; ?>" id="group_image_x" maxlength="100" />px</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_IMAGE_Y'); ?></td>
						<td><input type="text" name="image_y" value="<?php echo $data[7]; ?>" id="group_image_y" maxlength="100" />px</td>	
					</tr>					

					<tr id="form_thumb_x">
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_THUMB_X'); ?></td>
						<td><input type="text" name="thumb_x" value="<?php echo $data[4]; ?>" id="group_thumb_x" maxlength="100" />px</td>	
					</tr>
					
					<tr id="form_thumb_y">
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_THUMB_Y'); ?></td>
						<td><input type="text" name="thumb_y" value="<?php echo $data[5]; ?>" id="group_thumb_y" maxlength="100" />px</td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_BACKGROUND'); ?></td>
						<td><input type="text" name="background" value="<?php echo $data[8]; ?>" id="group_background" maxlength="100" /></td>	
					</tr>
					
					<tr>
						<td align="right"><?php echo JText::_('EDIT_GROUP_GROUP_DEFAULT_QUALITY'); ?></td>
						<td><input type="text" name="default_quality" value="<?php echo $data[9]; ?>" id="group_default_quality" maxlength="100" />%</td>	
					</tr>
					
					<?php if(count($slide_list) > 0) : ?>
					<tr id="form_default_image">
						<td align="right"><?php echo JText::_('EDIT_GROUP_SELECT_DEFAULT_IMAGE'); ?></td>
						<td>
							<select name="default_image" id="group_default_image">
								<option value="0" <?php if($data[10] == 0) echo 'selected="selected"'; ?>>none</option>
								<?php foreach($slide_list as $item) :	?>
								<option value="<?php echo $item->id; ?>" <?php if($data[10] == $item->id) echo 'selected="selected"'; ?>><?php echo $item->name; ?></option>
								<?php endforeach;?>
							</select>
						</td>	
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			
			<input type="hidden" name="option" value="com_gk3_photoslide" />
			<?php if(count($slide_list) == 0) : ?><input type="hidden" name="default_image" value="0" /><?php endif; ?>
			<input type="hidden" name="client" value="<?php echo $client->id;?>" />
			<input type="hidden" name="c" value="group" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="id" value="<?php echo $data[0]; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>	
	</div>
</div>