<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=autonews" method="post" name="adminForm">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->pageInfo->search;?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->filters->list; ?>
				<?php echo $this->filters->creator; ?>
			</td>
		</tr>
	</table>
	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_( 'ACY_NUM' );?>
				</th>
				<th class="title titlebox">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('JOOMEXT_SUBJECT'), 'a.subject', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titledate">
					<?php echo JHTML::_('grid.sort', JText::_('NEXT_GENERATE'), 'a.senddate', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titledate">
					<?php echo JHTML::_('grid.sort', JText::_('FREQUENCY'), 'a.frequency', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titlesender">
					<?php echo JHTML::_('grid.sort', JText::_('SENDER_INFORMATIONS'), 'a.fromname', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titlesender">
					<?php echo JHTML::_('grid.sort', JText::_('CREATOR'), 'b.name', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_('ACY_PUBLISHED'), 'a.published', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'a.mailid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
					<?php echo $this->pagination->getResultsCounter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$k = 0;
				for($i = 0,$a = count($this->rows);$i<$a;$i++){
					$row =& $this->rows[$i];
					$publishedid = 'published_'.$row->mailid;
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->mailid ); ?>
					</td>
					<td>
						<a href="<?php echo acymailing::completeLink('autonews&task=edit&cid[]='.$row->mailid); ?>">
							<?php echo $row->subject; ?>
						</a>
					</td>
					<td align="center">
						<?php echo acymailing::getDate($row->senddate); ?>
					</td>
					<td align="center">
						<?php echo JText::_('EVERY').' '.$this->delay->display($row->frequency); ?>
					</td>
					<td align="center">
						<?php
						if(empty($row->fromname)) $row->fromname = $this->config->get('from_name');
						if(empty($row->fromemail)) $row->fromemail = $this->config->get('from_email');
						if(empty($row->replyname)) $row->replyname = $this->config->get('reply_name');
						if(empty($row->replyemail)) $row->replyemail = $this->config->get('reply_email');
						if(!empty($row->fromname)){
							$text = '<b>'.JText::_('FROM_NAME').' : </b>'.$row->fromname;
							$text .= '<br/><b>'.JText::_('FROM_ADDRESS').' : </b>'.$row->fromemail;
							$text .= '<br/><br/><b>'.JText::_('REPLYTO_NAME').' : </b>'.$row->replyname;
							$text .= '<br/><b>'.JText::_('REPLYTO_ADDRESS').' : </b>'.$row->replyemail;
							echo acymailing::tooltip($text, '', '', $row->fromname);
						}
						?>
					</td>
					<td align="center">
						<?php
						if(!empty($row->name)){
							$text = '<b>'.JText::_('NAME',true).' : </b>'.$row->name;
							$text .= '<br/><b>'.JText::_('USERNAME',true).' : </b>'.$row->username;
							$text .= '<br/><b>'.JText::_('EMAIL',true).' : </b>'.$row->email;
							$text .= '<br/><b>'.JText::_('ID',true).' : </b>'.$row->userid;
							echo acymailing::tooltip($text, $row->name, '', $row->name,'index.php?option=com_users&task=edit&cid[]='.$row->userid);
						}
						?>
					</td>
					<td align="center">
						<span id="<?php echo $publishedid ?>" class="spanloading"><?php echo $this->toggleClass->toggle($publishedid,(int) $row->published,'mail') ?></span>
					</td>
					<td width="1%" align="center">
						<?php echo $row->mailid; ?>
					</td>
				</tr>
			<?php
					$k = 1-$k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
