<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/applications/tmpl/appqueue.php
 ^ 
 * Description: Default template for employment application in queue
 ^ 
 * History:		NONE
 ^ 
 */
 
// this is the basic listing scene when you click on the component 
// in the component menu
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'appqueue');
$_SESSION['cur_layout']='appqueue';

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

			<form action="index.php" method="post" name="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_TITLE' ); ?>:
						<input type="text" name="searchtitle" id="searchtitle" size="15" value="<?php if(isset($this->lists['searchtitle'])) echo $this->lists['searchtitle'];?>" class="text_area" onchange="document.adminForm.submit();" />
					&nbsp;</td>
					<td nowrap>
						<?php echo JText::_( 'JS_NAME' ); ?>:
						<input type="text" name="searchname" id="searchname" size="15" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;</td>
					<td>
						<?php echo $this->lists['jobcategory'];?>
					</td>
					<td>
						<?php echo $this->lists['jobtype'];?>
					</td>
					<td>
						<?php echo $this->lists['jobsalaryrange'];?>
					</td>
					<td>
						<button onclick="document.getElementById('searchtitle').value='';document.getElementById('searchname').value='';this.form.getElementById('searchjobcategory').value='';this.form.getElementById('searchjobtype').value='';this.form.getElementById('searchjobsalaryrange').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist">
				<thead>
					<tr>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
						</th>
						<th class="title"><?php echo JText::_('JS_TITLE'); ?></th>
						<th><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JOB_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_JOBTYPE'); ?></th>
						<th><?php echo JText::_('JS_SALARY'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th><?php echo JText::_('ACTIONS'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				$approvetask 	= 'empappapprove';
				$approveimg 	= 'tick.png';
				$rejecttask 	= 'empappreject';
				$rejectimg 	= 'publish_x.png';
				$approvealt 	= JText::_( 'Approve' );
				$rejectalt 	= JText::_( 'Reject' );
				//$alink = JFilterOutput::ampReplace('index.php?option='.$option.'&task=jobapprove&jobid='.$row->id);
				//$rlink = JFilterOutput::ampReplace('index.php?option='.$option.'&task=jobreject&jobid='.$row->id);

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
							<?php echo $row->application_title; ?></a>
						</td>
						<td>
							<?php 
							echo $row->first_name . ' ' . $row->last_name;
							?>
						</td>
						<td style="text-align: center;">
							<?php echo $row->cat_title; ?>
						</td>
						<td style="text-align: center;">
							<?php echo $row->jobtypetitle; ?>
						</td>
						<td style="text-align: center;">
							<?php echo $this->config['currency'] . $row->rangestart . ' - ' . $this->config['currency'] . $row->rangeend; ?>
						</td>
						<td style="text-align: center;">
							<?php echo  $row->create_date; ?>
						</td>
						<td style="text-align: center;">
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $approvetask;?>')">
								<img src="images/<?php echo $approveimg;?>" width="16" height="16" border="0" alt="<?php echo $approvealt; ?>" /></a>
							&nbsp;&nbsp; - &nbsp;&nbsp
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $rejecttask;?>')">
								<img src="images/<?php echo $rejectimg;?>" width="16" height="16" border="0" alt="<?php echo $rejectalt; ?>" /></a>
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