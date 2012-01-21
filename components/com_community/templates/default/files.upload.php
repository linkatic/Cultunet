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

<div>
	<span><?php echo JText::_('CC SUPPORTED EXTENSIONS');?></span>
	<span>.pdf .txt .zip .html</span>
</div>
<form name="newalbum" id="newalbum" method="post" action="<?php echo CRoute::getURI(); ?>" enctype="multipart/form-data" class="community-form-validate">
<div>
	<span style="float: left; width: 30%;">*<?php echo JText::_('CC UPLOAD'); ?>:</span>
	<span><input type="file" name="filedata" class="required" /></span>
</div>
<div>
	<button class="button validateSubmit"><?php echo JText::_('CC BUTTON START UPLOAD'); ?></button>
</div>
<div>
	<span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span>
</div>
</form>
<script type="text/javascript">
	cvalidate.init();
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>