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
<script type="text/javascript" language="javascript">
/**
 * This function needs to be here because, Joomla calls it
 **/ 
function submitbutton(action)
{	
	submitform(action);
}
</script>
<form action="index.php?option=com_community" method="post" name="adminForm">
<p>
	Jom Social actually uses "<strong>cron jobs</strong>" or "<strong>scheduled tasks</strong>" to perform the sending of mails. If you are unsure on how to go about setting this up,
	head on to <a href="http://www.jomsocial.com/docs/Configuration" target="_blank">Our documention section</a>.
</p>
<table class="adminlist" cellspacing="1">
	<thead>
		<tr class="title">
			<th width="1%"><?php echo JText::_('CC NUM'); ?></th>
			<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->mailqueues ); ?>);" /></th>
			<th width="5%" style="text-align: left;">
				<?php echo JText::_('CC RECIPIENT'); ?>
			</th>
			<th style="text-align: left;">
				<?php echo JText::_('CC SUBJECT'); ?>
			</th>
			<th>
				<?php echo JText::_('CC CONTENT'); ?>
			</th>
			<th align="center" width="10%">
				<?php echo JText::_('CC CREATED'); ?>
			</th>
			<th align="center" width="5%">
				<?php echo JText::_('CC STATUS'); ?>
			</th>
		</tr>		
	</thead>
<?php
	if( !$this->mailqueues )
	{
?>
		<tr>
			<td colspan="7" align="center">
				<div><?php echo JText::_('CC NO MAIL QUEUE HAS BEEN ADDED YET'); ?></div>
			</td>
		</tr>
<?php
	}
	else
	{
		$i		= 0;
		
		$mainframe	=& JFactory::getApplication();
		
		foreach( $this->mailqueues as $queue )
		{
			$created	=& JFactory::getDate( $queue->created );
			$created->setOffSet($mainframe->getCfg('offset') );
?>
		<tr>
			<td><?php echo $i + 1; ?></td>
			<td><?php echo JHTML::_('grid.id', $i++, $queue->id); ?></td>
			<td>
				<div>
					<?php echo $queue->recipient; ?>
				</div>
			</td>
			<td>
				<div>
					<?php echo $queue->subject; ?>
				</div>
			</td>
			<td>
				<div>
					<?php echo $queue->body; ?>
				</div>
			</td>
			<td align="center">
				<div>
					<?php echo $created->toFormat(); ?>
				</div>
			</td>
			<td align="center">
				<?php echo $this->getStatusText( $queue->status ); ?>
			</td>
		</tr>
<?php
		}
	}
?>
	<tfoot>
	<tr>
		<td colspan="7">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
</table>
<input type="hidden" name="view" value="mailqueue" />
<input type="hidden" name="task" value="mailqueue" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="boxchecked" value="0" />
</form>