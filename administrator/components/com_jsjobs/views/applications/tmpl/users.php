<?php 
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/applications/tmpl/users.php
 ^ 
 * Description: Template for users view
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access'); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
?>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

			<form action="index.php?option=com_jsjobs" method="post" name="adminForm">
				<table>
					<tr>
						<td width="100%">
							<?php echo JText::_( 'Filter' ); ?>:
							<input type="text" name="searchname" id="searchname" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
							<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
							<button onclick="document.getElementById('searchname').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
						</td>
					</tr>
				</table>

				<table class="adminlist" cellpadding="1">
					<thead>
						<tr>
							<th width="2%" class="title">
								<?php echo JText::_( 'NUM' ); ?>
							</th>
							<th width="3%" class="title">
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							</th>
							<th class="title"><?php echo JText::_('Name'); ?></th>
							<th width="15%" class="title" ><?php echo JText::_('Username'); ?></th>
							<th width="5%" class="title" nowrap="nowrap"><?php echo JText::_('Enabled'); ?></th>
							<th width="15%" class="title"><?php echo JText::_('Group'); ?></th>
							<th width="1%" class="title" nowrap="nowrap"><?php echo JText::_('ID'); ?></th>
							<th width="15%" class="title"><?php echo JText::_('JS_ROLE'); ?></th>
							<th width="15%" class="title"></th>
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
							$img 	= $row->block ? 'publish_x.png' : 'tick.png';
							$task 	= $row->block ? 'unblock' : 'block';
							$alt 	= $row->block ? JText::_( 'Enabled' ) : JText::_( 'Blocked' );
							$link 	= 'index.php?option=com_jsjobs&amp;view=user&amp;task=edit&amp;cid[]='. $row->id. '';

						?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<?php echo $i+1+$this->pagination->limitstart;?>
							</td>
							<td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
							<td><?php echo $row->name; ?></td>
							<td><?php echo $row->username; ?>	</td>
							<td align="center">	<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />					</td>
							<td><?php echo JText::_( $row->groupname ); ?>	</td>
							<td><?php echo $row->id; ?>	</td>
							<td align="center"><?php echo $row->roletitle ; ?>
							</td>
							<td align="center"><a href="<?php echo $link; ?>" ><?php echo JText::_('JS_CHANGE_ROLE'); ?></a></td>
							
						</tr>
						<?php
							$k = 1 - $k;
							}
						?>
					</tbody>
				</table>

				<input type="hidden" name="option" value="com_jsjobs" />
				<input type="hidden" name="task" value="view" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				include_once('components/com_jsjobs/views/jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
	
</table>										