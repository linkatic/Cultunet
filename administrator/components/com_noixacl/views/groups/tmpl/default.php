<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php  JHTML::_('behavior.tooltip');  ?>
<form action="index.php?option=com_noixacl" method="post" name="adminForm">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'NOIXACL_VIEW_GROUPS_TEXT_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'NOIXACL_VIEW_GROUPS_BUTTON_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_type').value='0';this.form.getElementById('filter_logged').value='0';this.form.submit();"><?php echo JText::_( 'NOIXACL_VIEW_GROUPS_BUTTON_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>

	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th width="2%" class="title">
					<?php echo JText::_( 'NOIXACL_VIEW_GROUPS_TH_NUM' ); ?>
				</th>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JTEXT::_('NOIXACL_VIEW_GROUPS_TH_NAME'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row 	=& $this->items[$i];
				
				if($row->id == 29 || $row->id == 30){
					$link = "";
				}
				else{
					$link 	= 'index.php?option=com_noixacl&amp;view=group&amp;task=edit&amp;cid[]='. $row->id. '';
				}
				

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td>
                	<?php if( !empty($link) ): ?>
                    	<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
                    <?php else: ?>
						<input type="checkbox" disabled="disabled" />
                    <?php endif; ?>
				</td>
				<td>
                	<?php if( !empty($link) ): ?><a href="<?php echo $link; ?>"><?php endif; ?>
						<?php echo $row->name; ?>
                    <?php if( !empty($link) ): ?></a><?php endif; ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				
			}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="com_noixacl" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br clear="all" />
<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."version.php"; ?>