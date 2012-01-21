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

$actual_group = '';
$flag = false; 

?>

<link rel="stylesheet" type="text/css" href="<?php echo $uri->root(); ?>administrator/components/com_gk3_photoslide/interface/css/tab.edit.css" media="all" />

<script type="text/javascript">
	window.addEvent("domready", function(){
		$E("#toolbar-save .toolbar").onclick = function(){
    		var alert_content = '';			
			if($("slide_name").getValue() == '') alert_content += '<?php echo JText::_('WRONG_SLIDE_NAME'); ?>\n'; 		
			if($("slide_image_x")){	
				if(isNaN($("slide_image_x").getValue()) || $("slide_image_x").getValue() < 0)  alert_content += '<?php echo JText::_('WRONG_SLIDE_IMAGE_X'); ?>\n';
				if(isNaN($("slide_image_y").getValue()) || $("slide_image_y").getValue() < 0)  alert_content += '<?php echo JText::_('WRONG_SLIDE_IMAGE_Y'); ?>\n'; 
			}
			//
			if(alert_content != '')
			{
				alert(alert_content)
			}
			else
			{
				try{tinyMCE.execCommand('mceRemoveControl', false, 'content');}catch(e){};
				submitbutton('edit_slide');
			}
		}
	});
</script>


<div id="wrapper">

	<?php 
		ViewNavigation::generate(array(
			JText::_("GROUPS") => 'option=com_gk3_photoslide&c=group',
			JText::_("EDIT_SLIDE") => 'option=com_gk3_photoslide&c=slide&task=edit&gid='.$gid.'&cid[]='.$cid[0]
		)); 
	?>
	
	<div id="groups">
		<form action="index.php" method="post" name="adminForm">
			<table class="adminlist">
				<tbody>
					
					<tr>
						<td><?php echo JText::_('SLIDE_NAME'); ?></td>
						<td><input type="text" name="name" value="<?php echo htmlspecialchars_decode($data[2]); ?>" id="slide_name" /></td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_ACCESS'); ?></td>
						<td>
							<select name="access">
								<option value="0" <?php if($data[9] == 0)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_PUBLIC'); ?></option>
								<option value="1" <?php if($data[9] == 1)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_REGISTRED'); ?></option>
								<option value="2" <?php if($data[9] == 2)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_SPECIAL'); ?></option>
							</select>
						</td>
					</tr>
					
					
					<?php if($group_type == 'Image Show 1') : ?>
							
					<tr>
						<td><?php echo JText::_('SLIDE_TITLE'); ?></td>
						<td><input type="text" name="title" value="<?php echo htmlspecialchars_decode($data[4]); ?>" id="slide_title" /></td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_TEXT'); ?></td>
						<td>
						
							<?php if($wysiwyg == 0) : ?>
							<textarea name="content" id="content" cols="50" rows="20"><?php echo htmlspecialchars_decode($data[7]); ?></textarea>
							<?php else : ?>
							
							<?php
       							$editor =& JFactory::getEditor();
       							echo $editor->display('content', htmlspecialchars_decode($data[7]), '550', '400', '60', '20', true);
       						?>
       						
       						<?php endif; ?>	
       						
						</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('SLIDE_LINKTYPE'); ?></td>
						<td>
							<select name="link_type" id="slide_link_type">
								<option value="1" <?php if($data[5] == 1)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_ARTICLE_LINK'); ?></option>
								<option value="0" <?php if($data[5] == 0)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_OWN_LINK'); ?></option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('SLIDE_LINKVALUE'); ?></td>
						<td><input type="text" name="link" value="<?php echo htmlspecialchars_decode($data[6]); ?>" id="slide_link" maxlength="255" size="40" /></td>
					</tr>
					
					<?php else : ?>
					
					<tr>
						<td><?php echo JText::_('IMAGE_X_SIZE'); ?></td>
						<td><input type="text" name="image_x" value="<?php echo htmlspecialchars_decode($data[11]); ?>" id="slide_image_x" maxlength="255" size="40" />px</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('IMAGE_Y_SIZE'); ?></td>
						<td><input type="text" name="image_y" value="<?php echo htmlspecialchars_decode($data[12]); ?>" id="slide_image_y" maxlength="255" size="40" />px</td>
					</tr>
					
					<?php endif; ?>
					
					
							
					<tr>
						<td><?php echo JText::_('SLIDE_ARTICLE'); ?></td>
						<td>
							<select name="article" id="slide_article">
								<?php if($group_type == 'Image Show 1') : ?>
								<option value="0" <?php if($data[3] == 0)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_OWN_ARTICLE'); ?></option>			
								<?php endif; ?>	
							
								<?php 
									//
									foreach($arts as $art){
										if($actual_group != $art->cat_name){ 
											if($flag) echo '</optgroup>'; else $flag = true;
											echo '<optgroup label="'.$art->cat_name.'">';
											$actual_group = $art->cat_name;
										}
										//
										echo '<option '.(($data[3] == $art->id) ? ' selected="selected"' : '').' value="'.$art->id.'" /> '.$art->art_title;
									}
								?>	
							</select>
						</td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_STYLE'); ?></td>
						<td>
							<select name="stretch">
								<option value="0" <?php if($data[13] == 0)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_NONSTRETCH'); ?></option>
								<option value="1" <?php if($data[13] == 1)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_STRETCH'); ?></option>
								<option value="2" <?php if($data[13] == 2)  echo ' selected="selected"'; ?>><?php echo JText::_('SLIDE_CUT'); ?></option>
							</select>
						</td>
					</tr>
											
				</tbody>
			</table>
			
			<?php if($group_type != 'Image Show 1') : ?>
			<input type="hidden" name="title" value="" />
			<input type="hidden" name="text" value="" />
			<input type="hidden" name="link_type" value="0" />
			<input type="hidden" name="link" value="" />	
			<input type="hidden" name="content" value="" />	
			<?php else : ?>
			<input type="hidden" name="image_x" value="0" />
			<input type="hidden" name="image_y" value="0" />
			<?php endif; ?>
			<input type="hidden" name="filename" value="<?php echo $data[14]; ?>" />
			<input type="hidden" name="option" value="com_gk3_photoslide" />
			<input type="hidden" name="client" value="<?php echo $client->id;?>" />
			<input type="hidden" name="c" value="slide" />
			<input type="hidden" name="cid[]" value="<?php echo $cid[0]; ?>" />
			<input type="hidden" name="gid" value="<?php echo $data[1]; ?>" />
			<input type="hidden" name="task" value="" />	
			<input type="hidden" name="boxchecked" value="0" />
		</form>	
	</div>
</div>