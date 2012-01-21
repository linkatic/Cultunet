<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<div id="cpanel">
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&view=files&task=addfolder&tmpl=component&'.$this->what.'='.$this->id.'&function='.$this->function); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/files.png', JText::_('RSM_TYPE_FOLDER')); ?>
				<span><?php echo JText::_('RSM_TYPE_FOLDER'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=module&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/module.png', JText::_('RSM_TYPE_MODULE')); ?>
				<span><?php echo JText::_('RSM_TYPE_MODULE'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=menu&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/menu.png', JText::_('RSM_TYPE_MENU')); ?>
				<span><?php echo JText::_('RSM_TYPE_MENU'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=article&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/content.png', JText::_('RSM_TYPE_ARTICLE')); ?>
				<span><?php echo JText::_('RSM_TYPE_ARTICLE'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=section&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/section.png', JText::_('RSM_TYPE_SECTION')); ?>
				<span><?php echo JText::_('RSM_TYPE_SECTION'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=category&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/category.png', JText::_('RSM_TYPE_CATEGORY')); ?>
				<span><?php echo JText::_('RSM_TYPE_CATEGORY'); ?></span>
			</a>
		</div>
	</div>
	<div style="float: left">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=url&tmpl=component&'.$this->what.'='.$this->id); ?>">
				<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/url.png', JText::_('RSM_TYPE_URL')); ?>
				<span><?php echo JText::_('RSM_TYPE_URL'); ?></span>
			</a>
		</div>
	</div>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>