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
<script type="text/javascript" language="javascript">
function submitbutton( action )
{
	if( action == 'purge' )
	{
		if(confirm('<?php echo JText::_('CC ARE YOU SURE YOU WANT TO PURGE ALL ACTIVITIES FROM THE SITE');?>'))
		{
			submitform( action );
		}
	}
	else
	{
		submitform( action );
	}
}
</script>
<table class="adminform" cellpadding="5">
	<tr>
		<td width="95%">
			<?php echo JText::_('CC FILTER BY ACTOR'); ?><input type="text" name="actor" onchange="submitform();" /><button onclick="submitform();"><?php echo JText::_('CC GO');?></button>
		</td>
		<td nowrap="nowrap" align="right">
			<select name="app" onchange="submitform();">
				<option value="none"<?php echo ( $this->currentApp == 'none' ) ? ' selected="selected"' : '';?>><?php echo JText::_('CC SELECT APPLICATION');?></option>
				<?php
				for( $i = 0; $i < count( $this->filterApps ); $i++ )
				{
				?>
					<option value="<?php echo $this->filterApps[ $i ]->app;?>"<?php echo ( $this->currentApp === $this->filterApps[ $i ]->app ) ? ' selected="selected"' : '';?>><?php echo $this->filterApps[ $i ]->app; ?></option>
				<?php
				}
				?>
			</select>

			<select name="archived" onchange="submitform();">
				<option value="0"<?php echo ( $this->currentArchive == 0 ) ? ' selected="selected"' : '';?>><?php echo JText::_('CC SELECT STATE');?></option>
				<option value="1"<?php echo ($this->currentArchive == 1 ) ? ' selected="selected"' : '';?>><?php echo JText::_('CC ACTIVE');?></option>
				<option value="2"<?php echo ($this->currentArchive == 2 ) ? ' selected="selected"' : '';?>><?php echo JText::_('CC ARCHIVED');?></option>
			</select>
		</td>
	</tr>
</table>

<table class="adminlist" cellspacing="1">
	<thead>
		<tr class="title">
			<th width="1%"><?php echo JText::_('CC NUM'); ?></th>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->activities ); ?>);" />
			</th>
			<th style="text-align: left;">
				<?php echo JText::_('CC TITLE'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('CC CREATED');?>
			</th>
		</tr>
	</thead>
<?php
	if( $this->activities )
	{
		$i	= 0;
		foreach($this->activities as $row )
		{
			$row->title	= JString::str_ireplace('{target}', $this->_getUserLink( $row->target ) , $row->title);
			$row->title	= preg_replace('/\{multiple\}(.*)\{\/multiple\}/i', '', $row->title);
			$search		= array('{single}','{/single}');
			$row->title	= JString::str_ireplace($search, '', $row->title);
			$row->title	= JString::str_ireplace('{actor}', $this->_getUserLink( $row->actor ) , $row->title);
			$row->title	= JString::str_ireplace('{app}', $row->app, $row->title); 
?>
	<tr>
		<td><?php echo ( $i + 1 ); ?></td>
		<td><?php echo JHTML::_('grid.id', $i++, $row->id); ?></td>
		<td><?php echo $row->title;?></td>
		<td align="center"><?php echo $row->created;?></td>
	</tr>
<?php
		}
?>
	<tfoot>
	<tr>
		<td colspan="5">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
<?php
	}
	else
	{
?>
	<tr>
		<td colspan="5" align="center"><?php echo JText::_('CC NO ACTIVITIES YET');?></td>
	</tr>
<?php
	}
?>
</table>
<input type="hidden" name="view" value="activities" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="task" value="activities" />
<input type="hidden" name="boxchecked" value="0" />
</form>