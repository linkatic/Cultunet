<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.module.helper' );

function modChrome_cultunet($module, &$params, &$attribs) { 

	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
	
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?><?php if(isset($attribs['additional_class'])) echo $attribs['additional_class']; ?>">
			<?php if ($module->showtitle) : ?>
				<h<?php echo $headerLevel; ?>><span><?php echo $module->title; ?></span></h<?php echo $headerLevel; ?>>
			<?php endif; ?>
   			<div class="moduletable_content">
                	<?php echo $module->content; ?>
			</div>
		</div>
	<?php endif;	
}

?>