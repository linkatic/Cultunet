<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php 
 */
defined('_JEXEC') or die();
?>
<textarea id="wall-edit-<?php echo $id;?>" name="message" class="inputbox" style="width: 95%;"><?php echo $message; ?></textarea>
<input type="button" value="<?php echo JText::_('CC SAVE');?>" class="button" onclick="joms.walls.save('<?php echo $id;?>' , '<?php echo $editableFunc;?>');" />
<input type="button" value="<?php echo JText::_('CC CANCEL');?>" class="button" onclick="joms.walls.edit('<?php echo $id;?>' , '<?php echo $editableFunc;?>');" />