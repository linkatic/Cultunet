<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php?option=com_noixacl" enctype="multipart/form-data" method="post" name="adminForm">
<table class="adminform">
<tr>
	<th colspan="2"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TEXT_TITLE' ); ?></th>
</tr>
<tr>
	<td width="120">
		<label for="install_package"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_PACKAGE_FILE' ); ?>:</label>
	</td>
	<td>
		<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
		<input class="button" type="button" value="<?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TEXT_UPLOAD_FILE' ); ?> &amp; <?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TEXT_BUTTON_INSTALL' ); ?>" onclick="submitbutton('install')" />
	</td>
</tr>
</table>
<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th class="title" width="10px"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_NUM' ); ?></th>
			<th class="title" nowrap="nowrap"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_CURRENTLY_INSTALLED' ); ?></th>
			<th class="title" width="5%" align="center"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_ENABLED' ); ?></th>
			<th class="title" width="10%" align="center"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_VERSION' ); ?></th>
			<th class="title" width="15%"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_DATE' ); ?></th>
			<th class="title" width="25%"><?php echo JText::_( 'NOIXACL_VIEW_ADAPTERS_TH_AUTHOR' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	$total = count( $this->items );
	for ($i=0, $n=$total; $i < $n; $i++)
	{
		$row 	=& $this->items[$i];
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $i+1+$this->pagination->limitstart;?>
			</td>
			<td>
				<input type="radio" id="cb<?php echo $i;?>" name="cid" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" />
				<span class="bold"><?php echo $row->title; ?></span>
			</td>
			<td align="center">
				<?php if (!$row->enabled) : ?>
					<a href="index.php?option=com_noixacl&amp;controller=adapters&amp;task=enable&amp;cid[]=<?php echo $row->id; ?>&amp;limitstart=<?php echo $this->pagination->limitstart; ?>"><img border="0" alt="Disabled" title="Enable" src="images/publish_x.png"/></a>
				<?php else : ?>
					<a href="index.php?option=com_noixacl&amp;controller=adapters&amp;task=disable&amp;cid[]=<?php echo $row->id; ?>&amp;limitstart=<?php echo $this->pagination->limitstart; ?>"><img src="images/tick.png" border="0" alt="Enabled" title="Disable" /></a>
				<?php endif; ?>
			</td>
			<td align="center"><?php echo($row->xmlData["version"]); ?></td>
			<td align="center"><?php echo($row->xmlData["creationdate"]); ?></td>
			<td align="center"><?php echo($row->xmlData["author"]); ?></td>
		</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>
<input type="hidden" name="controller" value="adapters" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br clear="all" />
<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."version.php"; ?>