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

JRequest :: setVar('layout', 'jobappliedresume');
$_SESSION['cur_layout']='jobappliedresume';

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';


$status = array(
	'1' => JText::_('JOB_APPROVED'),
	'-1' => JText::_('JOB_REJECTED'));

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
						<?php echo JText::_( 'JS_NAME' ); ?> :
						<input type="text" name="searchname" id="searchname" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;&nbsp;&nbsp;</td>
					<td nowrap="nowrap">
						<?php echo $this->lists['jobtype'];?>
					&nbsp;&nbsp;&nbsp;</td>
					<td>
						<button onclick="document.getElementById('searchtitle').value='';document.getElementById('searchcompany').value='';this.form.getElementById('searchjobcategory').value='';this.form.getElementById('searchjobtype').value='';this.form.getElementById('searchjobstatus').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			
			<table class="adminlist">
				<thead>
					<tr>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
						</th>
						<th class="title"><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_PREFFERD'); ?></th>
						<th><?php echo JText::_('JS_SALARY_RANGE'); ?></th>
						<th><?php echo JText::_('JS_APPLIED_DATE'); ?></th>
						<th><?php echo JText::_('JS_CONTACT_EMAIL'); ?></th>
						<th>s</th>
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
				$resumelink = 'index.php?option=com_jsjobs&view=application&layout=view_resume&rd='.$row->appid.'&oi='.$this->oi;
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $resumelink; ?>">
						<?php echo $row->first_name.' '.$row->last_name; ?></a>
					</td>
					<td>
						<?php 
						echo $row->cat_title;
						?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->jobtypetitle; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $this->config['currency'].$row->rangestart.' - '.$this->config['currency'].$row->rangeend; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->apply_date; ?>
					</td>
					<td style="text-align: center;">
						<?php echo  $row->email_address; ?>
					</td>
					<td style="text-align: center;">
						<?php 
							echo "<a href='".$resumelink ."'>".JText::_('JS_RESUME').$row->totalresume."  </a>";
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
			<input type="hidden" name="oi" value="<?php echo $this->oi; ?>" />
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