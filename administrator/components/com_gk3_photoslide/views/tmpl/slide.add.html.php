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

$actual_group = '';
$flag = false; 
$first = false;

?>

<link rel="stylesheet" type="text/css" href="<?php echo $uri->root(); ?>administrator/components/com_gk3_photoslide/interface/css/slide.add.css" media="all" />

<script type="text/javascript">
	window.addEvent("domready", function(){
		$E("#toolbar-save .toolbar").onclick = function(){
    		var alert_content = '';			
			if($("slide_name").getValue() == '') alert_content += '<?php echo JText::_('WRONG_SLIDE_NAME'); ?>\n'; 
			if($("slide_image").value == '') alert_content += '<?php echo JText::_('WRONG_SLIDE_IMAGE'); ?>\n'; 				
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
				submitbutton('add_slide');
			}
		}
	});
</script>

<div id="wrapper">

	<?php 
		ViewNavigation::generate(array(
			JText::_("GROUPS") => 'option=com_gk3_photoslide&c=group',
			JText::_("ADD_SLIDE") => 'option=com_gk3_photoslide&c=slide&task=add&gid='.$gid
		)); 
	?>
	
	<div id="groups">
		
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table class="adminlist">
				<tbody>
					<tr>
						<td><?php echo JText::_('SLIDE_NAME'); ?></td>
						<td><input type="text" name="name" value="" id="slide_name" /></td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_IMAGE'); ?></td>
						<td><input type="file" name="image" value="" id="slide_image" /></td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_ACCESS'); ?></td>
						<td>
							<select name="access">
								<option value="0" selected="selected"><?php echo JText::_('SLIDE_PUBLIC'); ?></option>
								<option value="1"><?php echo JText::_('SLIDE_REGISTRED'); ?></option>
								<option value="2"><?php echo JText::_('SLIDE_SPECIAL'); ?></option>
							</select>
						</td>
					</tr>
					
					
					<?php if($group_type == 'Image Show 1') : ?>
							
					<tr>
						<td><?php echo JText::_('SLIDE_TITLE'); ?></td>
						<td><input type="text" name="title" value="" id="slide_title" /></td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_TEXT'); ?></td>
						<td>
							<?php if($wysiwyg == 0) : ?>
							<textarea name="content" id="content" cols="50" rows="20"></textarea>
							<?php else : ?>
							
							<?php
       							$editor =& JFactory::getEditor();
       							echo $editor->display('content', '', '550', '400', '60', '20', true);
       						?>
       						
       						<?php endif; ?>	
						</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('SLIDE_LINKTYPE'); ?></td>
						<td>
							<select name="link_type" id="slide_link_type">
								<option value="1" selected="selected"><?php echo JText::_('SLIDE_ARTICLE_LINK'); ?></option>
								<option value="0"><?php echo JText::_('SLIDE_OWN_LINK'); ?></option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('SLIDE_LINKVALUE'); ?></td>
						<td><input type="text" name="link" value="" id="slide_link" maxlength="255" size="40" /></td>
					</tr>
					
					<?php else : ?>
					
					<tr>
						<td><?php echo JText::_('IMAGE_X_SIZE'); ?></td>
						<td><input type="text" name="image_x" value="0" id="slide_image_x" maxlength="255" size="40" />px</td>
					</tr>
					
					<tr>
						<td><?php echo JText::_('IMAGE_Y_SIZE'); ?></td>
						<td><input type="text" name="image_y" value="0" id="slide_image_y" maxlength="255" size="40" />px</td>
					</tr>
					
					<?php endif; ?>
					
					
							
					<tr>
						<td><?php echo JText::_('SLIDE_ARTICLE'); ?></td>
						<td>
							<select name="article" id="slide_article">
								<?php if($group_type == 'Image Show 1') : ?>
								<option value="0" selected="selected"><?php echo JText::_('SLIDE_OWN_ARTICLE'); ?></option>
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
										echo '<option '.(!$first ? 'selected="selected"' : '').' value="'.$art->id.'" /> '.$art->art_title;
										//
										if(!$first) $first = true;
									}
								?>	
							</select>
						</td>
					</tr>
							
					<tr>
						<td><?php echo JText::_('SLIDE_STYLE'); ?></td>
						<td>
							<select name="stretch">
								<option value="0" selected="selected"><?php echo JText::_('SLIDE_NONSTRETCH'); ?></option>
								<option value="1"><?php echo JText::_('SLIDE_STRETCH'); ?></option>
								<option value="2"><?php echo JText::_('SLIDE_CUT'); ?></option>
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
			<input type="hidden" name="option" value="com_gk3_photoslide" />
			<input type="hidden" name="client" value="<?php echo $client->id;?>" />
			<input type="hidden" name="c" value="slide" />
			<input type="hidden" name="gid" value="<?php echo $gid; ?>" />
			<input type="hidden" name="task" value="" />		
			<input type="hidden" name="boxchecked" value="0" />
		</form>	
	</div>
</div>