<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	isMine		boolean is this group belong to me
 * @params	members		An array of member objects 
 */
defined('_JEXEC') or die();
?>

<?php
if( !CStringHelper::isHTML($discussion->message) 
	&& $config->get('htmleditor') != 'none' 
	&& $config->getBool('allowhtml') )
{
	$discussion->message = CStringHelper::nl2br($discussion->message);
}

?>
<script type="text/javascript">
function saveContent()
{
	<?php echo $editor->save( 'message' ); ?>
	return true;
}
</script>
<form name="jsform-groups-editdiscussion" action="<?php echo CRoute::getURI(); ?>" method="post">
<table class="formtable">
	<tr>
		<td colspan="2"><?php echo $beforeFormDisplay;?></td>
	</tr>
	<?php if ( $config->get( 'htmleditor' ) == 'jce' ) : ?>

	<tr>
		<td class="key" colspan="2">
			<label for="title" class="label" style="text-align: left;">*<?php echo JText::_('CC DISCUSSION TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<td class="value" colspan="2">
			<input type="text" name="title" id="title" size="40" class="inputbox" style="width: 90%" value="<?php echo $discussion->title;?>" />
		</td>
	</tr>
	
	<tr>
		<td class="key" colspan="2">
			<label for="message" class="label" style="text-align: left;">*<?php echo JText::_('CC DISCUSSION MESSAGE'); ?></label>
		</td>
	</tr>
	<tr>
		<td class="value" colspan="2">			
			<?php if( $config->get( 'htmleditor' ) && $config->getBool( 'allowhtml' ) ) : ?>
				<?php echo $editor->display( 'message',  $discussion->message , '95%', '450', '10', '20' , false ); ?>
			<?php else : ?>
				<textarea rows="3" cols="40" name="message" id="message" class="inputbox" style="width: 90%"><?php echo $discussion->message;?></textarea>
			<?php endif; ?>
		</td>
	</tr>
	
	<?php else : ?>
	
	<tr>
		<td class="key">
			<label for="title" class="label">*<?php echo JText::_('CC DISCUSSION TITLE'); ?></label>
		</td>
		<td class="value">
			<input type="text" name="title" id="title" size="40" class="inputbox" style="width: 90%" value="<?php echo $discussion->title;?>" />
		</td>
	</tr>
	
	<tr>
		<td class="key">
			<label for="message" class="label">*<?php echo JText::_('CC DISCUSSION MESSAGE'); ?></label>
		</td>
		<td class="value">
			<?php 
			if( $config->get( 'htmleditor' ) == 'none' && $config->getBool('allowhtml') )
			{
			?>
			<div class="htmlTag"><?php echo JText::_('CC HTML TAGS ALLOWED');?></div>
			<?php
			}?>
			
			<?php if( $config->get( 'htmleditor' ) && $config->getBool( 'allowhtml' ) ) : ?>
				<?php echo $editor->display( 'message',  $discussion->message , '95%', '450', '10', '20' , false ); ?> 
			<?php else : ?>
				<textarea rows="3" cols="40" name="message" id="message" class="inputbox" style="width: 90%"><?php echo $discussion->message;?></textarea>
			<?php endif; ?>

		</td>
	</tr>
	
	<?php endif; ?>
	<tr>
		<td colspan="2"><?php echo $afterFormDisplay;?></td>
	</tr>
	<tr>
		<td class="key"></td>
		<td class="value">
			<span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span>
		</td>
	</tr>
	
	<tr>
		<td class="key"></td>
		<td class="value">
			<input type="hidden" value="<?php echo $group->id; ?>" name="groupid" />
			<input type="hidden" value="<?php echo $discussion->id;?>" name="topicid" />
			<input type="submit" class="button" value="<?php echo JText::_('CC SAVE');?>" onclick="saveContent();" />
			<input type="button" name="cancel" value="<?php echo JText::_('CC BUTTON CANCEL'); ?>" onclick="javascript:history.go(-1);return false;" class="button" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</td>
	</tr>
</table>
</form>