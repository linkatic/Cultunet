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
<form action="index.php?option=com_community" method="post" name="adminForm">
<table class="adminlist" cellspacing="1">
	<thead>
		<tr class="title">
			<th width="1%"><?php echo JText::_('CC NUM'); ?></th>
			<th style="text-align: left;">
				<?php echo JText::_('CC MESSAGE'); ?>
			</th>
			<th width="10%" style="text-align: center;">
				<?php echo JText::_('CC CREATED BY'); ?>
			</th>
			<th align="center" width="5%">
				<?php echo JText::_('CC IP ADDRESS'); ?>
			</th>
			<th style="text-align: center;" width="10%">
				<?php echo JText::_('CC CREATED'); ?>
			</th>
		</tr>		
	</thead>
<?php
	if( !$this->reporters )
	{
?>
		<tr>
			<td colspan="7" align="center">
				<div><?php echo JText::_('CC NO REPORTS SUBMITTED YET'); ?></div>
			</td>
		</tr>
<?php
	}
	else
	{
		$count		= 0;
		
		foreach( $this->reporters as $row )
		{
			$count	= $count + 1;
			$user	=& JFactory::getUser( $row->created_by );
?>
		<tr id="row<?php echo $count;?>">
			<td><?php echo $count; ?></td>
			<td>
				<div>
					<?php echo $this->escape( $row->message );?>
				</div>
			</td>
			<td style="text-align: center;">
				<div>
					<a href="<?php echo JURI::root() . '/index.php?option=com_community&view=profile&userid=' . $user->id;?>" target="_blank">
					<?php echo $user->name;?>
					</a>
				</div>
			</td>
			<td align="center">
				<div>
					<?php echo $row->ip; ?>
				</div>
			</td>
			<td align="center">
				<div>
					<?php echo $row->created; ?>
				</div>
			</td>
		</tr>
<?php
		}
	}
?>
	<tfoot>
	<tr>
		<td colspan="5">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
</table>
<input type="hidden" name="view" value="reports" />
<input type="hidden" name="layout" value="childs" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="boxchecked" value="0" />
</form>