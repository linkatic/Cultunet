<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	$app	Application object
 */
defined('_JEXEC') or die();
?>
<dl class="2cols">
    <dt style="margin: 0pt 0pt 10px;" class="col-left"><?php echo JText::_('CC APPLICATION NAME');?></dt>
    	<dd style="margin: 0pt 0pt 10px;" class="col-right"><?php echo $this->escape($app->name); ?></dd>
    <dt class="col-left"><?php echo JText::_('CC APPLICATION AUTHOR');?></dt>
    	<dd class="col-right"><?php echo $this->escape($app->author); ?></dd>
    <dt class="col-left"><?php echo JText::_('CC APPLICATION VERSION');?></dt>
    	<dd class="col-right"><?php echo $this->escape($app->version); ?></dd>
    <dt class="col-left"><?php echo JText::_('CC APPLICATION DESCRIPTION');?></dt>
    	<dd class="col-right"><?php echo $this->escape($app->description); ?></dd>
</dl>