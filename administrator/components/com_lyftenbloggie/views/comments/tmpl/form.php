<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.author.value == ""){
		alert( "<?php echo JText::_( 'ADD COMMENT AUTHOR' ); ?>" );
	} else if (form.state.value == ""){
		alert( "<?php echo JText::_( 'YOU MUST SELECT A STATE', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
if(!empty($this->reports)) {
	echo $this->tabs->startPane("CommnetTabs");
	echo $this->tabs->startPanel(JText::_('COMMENT DETAILS'), "commentdetails-page");
}
?>
<table class="adminform admintable" style="border: 1px solid rgb(233, 233, 233);">
	<tr>
		<td class="key">
			<label for="title">
				<?php echo JText::_( 'COMMENT DATE' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo JHTML::_('date', $this->row->date, '%b %d, %Y'); ?>
		</td>
	</tr>
<?php if($this->row->type == 2) : ?>	
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'COMMENT TYPE' ).':'; ?>
			</label>
		</td>
		<td>
			Trackback
		</td>
	</tr>
<?php endif; ?>	
	<tr>
		<td class="key">
			<label for="title">
				<?php echo JText::_( 'AUTHOR NAME' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['author']; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'AUTHOR EMAIL' ).':'; ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="author_email" id="author_email" size="50" maxlength="100" value="<?php echo $this->row->author_email; ?>" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'AUTHOR URL' ).':'; ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="author_url" id="author_url" size="50" maxlength="100" value="<?php echo stripslashes($this->row->author_url); ?>" />
		</td>
	</tr>
<?php if($this->row->author_ip) : ?>	
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'AUTHOR IP' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo $this->row->author_ip; ?>
		</td>
	</tr>
<?php endif; ?>		
<?php if($this->row->agent) : ?>	
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'AGENT' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo $this->row->agent; ?>
		</td>
	</tr>
<?php endif; ?>		
	<tr>		
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'STATE' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['state']; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'KARMA' ).':'; ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="karma" id="karma" size="50" maxlength="100" value="<?php echo $this->row->karma; ?>" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'DESCRIPTION' ).':'; ?>
			</label>
		</td>
		<td>
			<textarea name="content" id="content" rows="5" cols="50" style="width: 97%;"><?php echo $this->row->content; ?></textarea>
		</td>
	</tr>
</table>
<?php
if(!empty($this->reports)) {
	echo $this->tabs->endPanel();
	echo $this->tabs->startPanel(JText::_('COMMENT REPORTS'), "commentreports-page");
?>
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'ACTIONS' ); ?>:
				<button onclick="submitbutton('delreports');"><?php echo JText::_( 'DELETE' ); ?></button>
			</td>
		</tr>
	</table>
	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->reports ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'Comment', 'r.content', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'Reporter', 'reporter', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Date', 'r.date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'r.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->reports);
		foreach ($this->reports as $report) {
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $report->id; ?>" onclick="isChecked(this.checked);" type="checkbox"></td>
			<td align="left"><?php echo substr($report->reason, 0, 500); ?></td>
			<td align="center"><?php echo $report->reporter; ?></td>
			<td align="center"><?php echo JHTML::_('date', $report->date, '%b %d, %Y'); ?></td>
			<td align="center"><?php echo $report->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		} 
		?>
	</tbody>

	</table>
<?php
	echo $this->tabs->endPanel();
	echo $this->tabs->endPane();
}
?>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="comments" />
<input type="hidden" name="view" value="comment" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>