<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/applications/tmpl/jobs.php
 ^ 
 * Description: Default template for jobs view
 ^ 
 * History:		NONE
 ^ 
 */
 
// this is the basic listing scene when you click on the component 
// in the component menu
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'companies');
$_SESSION['cur_layout']='companies';

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

?>
<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

			<form action="index.php" method="post" name="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap="nowrap">
						<?php echo JText::_( 'JS_COMPANY' ); ?> :
						<input type="text" name="searchcompany" id="searchcompany" value="<?php if(isset($this->lists['searchcompany'])) echo $this->lists['searchcompany'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;&nbsp;&nbsp;</td>
					<td nowrap="nowrap">
						<?php echo $this->lists['jobcategory'];?>
						<?php echo $this->lists['country'];?>
					&nbsp;&nbsp;&nbsp;</td>
					<td>
						<button onclick="document.getElementById('searchcompany').value='';this.form.getElementById('searchjobcategory').value='';this.form.getElementById('searchcountry').value=0;this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist">
				<thead>
					<tr>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
						</th>
						<th><?php echo JText::_('JS_COMPANYNAME'); ?></th>
						<th><?php echo JText::_('JOB_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_WEBSITE'); ?></th>
						<th><?php echo JText::_('JS_COUNTRY'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th><?php echo JText::_('JS_STATUS'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$option.'&task=edit&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
					</td>
					<td style="text-align: center;">
						<?php echo $row->cat_title; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->url; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->countryname; ?>
					</td>
					<td style="text-align: center;">
						<?php echo  $row->created; ?>
					</td>
					<td style="text-align: center;">
						<?php 
							if($row->status == 1) echo "<font color='green'>".$status[$row->status]."</font>";
							else echo "<font color='red'>".$status[$row->status]."</font>";
						?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
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