<?php defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );?>
<form action="index.php" method="post" name="adminForm">
	<div id="header">
		<div id="filterbox" style="float: left;">
		<table>
		  <tr>
			 <td align="left" width="100%">
				<?php echo JText::_('Filter'); ?>:
				<?php echo $this->lists['templatetype']; ?>
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.adminForm.templatetype.value='';"><?php echo JText::_('Reset'); ?></button>
			 </td>
		  </tr>
		</table>
		</div>
	</div>
	<br clear="all" />
	<div id="templateslist" style="text-align: left;">
		<table id="templates" class="adminlist">
			<thead>
			<tr>
				<th width="5%"></th>
				<th><?php echo JText::_('Name'); ?></th><th><?php echo JText::_('Number of fields'); ?></th><th><?php echo JText::_('Type'); ?></th><th><?php echo JText::_('FIELDS'); ?></th>
			</tr>
			</thead>
			<?php
			$rowcolor = 0;
			$i=0;
			foreach ($this->templateslist as $id => $details) {
				$link = JRoute::_( 'index.php?option=com_csvimproved&controller=templates&task=edittemplate&template_id='. $details->id);
				?>
                <tr>
                <?php
				/* Checkbox */
				echo "<td><input type=\"radio\" id=\"cb".$i++."\" name=\"template_id\" value=\"".$details->id."\" onclick=\"isChecked(this.checked);\" /></td>";
				/* Name */
				echo "<td><a href=".$link.">".$details->name."</a></td>";
				/* Number of fields */
				echo "<td>".$details->nrfields."</td>";
				/* Template type */
				echo "<td>".JText::_($details->type)."</td>\n";
				/* Edit button */  ?>
				<td>
					<?php echo JHTML::_('link', $_SERVER['PHP_SELF'], '<img src="'.JURI::root().'administrator/components/com_csvimproved/assets/images/csvivirtuemart_edit_16.png" alt="'.JText::_('EDIT').'" />', 'onClick="document.adminForm.cb'.($i-1).'.checked = true; submitbutton(\'fieldstemplate\'); return false;"');?>
				</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="templates" />
	<input type="hidden" name="boxchecked" value="0" />
	<!-- Only used to reset the entries counter in the page navigation -->
	<input type="hidden" name="fromtemplatelist" value="1" />
	<input type="hidden" name="controller" value="templates" />
</form>
<script type="text/javascript">
	jQuery("table#templates tr:even").addClass("row0");
	jQuery("table#templates tr:odd").addClass("row1");
</script>
