<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm">
<table class="adminlist">
<thead>
	<tr>
		<th width="5" class="title">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Template Name' ); ?>
		</th>
		<th width="10%" align="center">
			<?php echo JText::_( 'Version' ); ?>
		</th>
		<th width="15%" class="title">
			<?php echo JText::_( 'Date' ); ?>
		</th>
		<th width="25%"  class="title">
			<?php echo JText::_( 'Author' ); ?>
		</th>
	</tr>
</thead>
<?php $i = 0; ?>
<?php foreach( $this->templates as $row ): ?>
<tr>
	<td>
		<?php echo ( $i + 1 ); ?>
	</td>
	<td>
		<a href="index.php?option=com_community&view=templates&layout=edit&override=<?php echo $row->override ? 1 : 0;?>&id=<?php echo $row->element;?>"><?php echo $row->element;?></a>
	</td>
	<td align="center">
		<?php echo ($row->info) ? $row->info['version'] : 'N/A';?>
	</td>
	<td align="center">
		<?php echo ($row->info) ? $row->info['creationdate'] : 'N/A';?>
	</td>
	<td align="center">
		<?php echo ($row->info) ? $row->info['author'] : 'N/A';?>
	</td>
</tr>
	<?php $i++;?>
<?php endforeach; ?>
</table>
<input type="hidden" name="view" value="templates" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
</form>