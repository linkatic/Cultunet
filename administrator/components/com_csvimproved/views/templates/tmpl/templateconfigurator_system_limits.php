<?php defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<table id="template_system_limits" class="adminform">
	<!-- Use system limits -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('USE_SYSTEM_LIMITS_TIP'), JText::_('USE_SYSTEM_LIMITS'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('USE_SYSTEM_LIMITS');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'use_system_limits', '', $this->template->use_system_limits); ?></td>
	</tr>
	<!-- Maximum execution time -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('MAX_EXECUTION_TIME_TIP'), JText::_('MAX_EXECUTION_TIME'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('MAX_EXECUTION_TIME');?></td>
		<td><input class="template_input" type="text" id="max_execution_time" name="max_execution_time" value="<?php echo $this->template->max_execution_time;?>"><br />
		<?php echo JText::_('Default'); ?>: <?php echo intval(ini_get('max_execution_time')); ?></td>
	</tr>
	<!-- Maximum input time -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('MAX_INPUT_TIME_TIP'), JText::_('MAX_INPUT_TIME'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('MAX_INPUT_TIME');?></td>
		<td><input class="template_input" type="text" id="max_input_time" name="max_input_time" value="<?php echo $this->template->max_input_time;?>"><br />
		<?php echo JText::_('Default'); ?>: <?php echo intval(ini_get('max_input_time')); ?></td>
	</tr>
	
	<!-- Maximum memory -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('MEMORY_LIMIT_TIP'), JText::_('MEMORY_LIMIT'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('MEMORY_LIMIT');?></td>
		<td><input class="template_input" type="text" id="memory_limit" name="memory_limit" value="<?php echo $this->template->memory_limit;?>"><br />
		<?php echo JText::_('Default'); ?>: <?php echo intval(ini_get('memory_limit')); ?></td>
	</tr>
	
	<!-- Maximum POST size -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('POST_MAX_SIZE_TIP'), JText::_('POST_MAX_SIZE'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('POST_MAX_SIZE');?></td>
		<td><input class="template_input" type="text" id="post_max_size" name="post_max_size" value="<?php echo $this->template->post_max_size;?>"><br />
		<?php echo JText::_('Default'); ?>: <?php echo intval(ini_get('post_max_size')); ?></td>
	</tr>
	
	<!-- Maximum Upload size -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('UPLOAD_MAX_FILESIZE_TIP'), JText::_('UPLOAD_MAX_FILESIZE'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('UPLOAD_MAX_FILESIZE');?></td>
		<td><input class="template_input" type="text" id="upload_max_filesize" name="upload_max_filesize" value="<?php echo $this->template->upload_max_filesize;?>"><br />
		<?php echo JText::_('Default'); ?>: <?php echo intval(ini_get('upload_max_filesize')); ?></td>
	</tr>
</table>
<script type="text/javascript">
	jQuery("table#template_system_limits tr:even").addClass("row0");
	jQuery("table#template_system_limits tr:odd").addClass("row1");
</script>
