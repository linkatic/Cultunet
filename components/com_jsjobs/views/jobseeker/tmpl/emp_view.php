<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/empview.php
 ^ 
 * Description: template view for a employment application
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 
 $jobtype = array(
		'0' => JText::_('JS_JOBTYPE_FULLTIME'),
		'1' => JText::_('JS_JOBTYPE_PARTTIME'),
		'2' => JText::_('JS_JOBTYPE_INTERNSHIP'));
$heighesteducation = array(
	'0' => JText::_('JS_JOBEDUCATION_UNIVERSITY'),
	'1' => JText::_('JS_JOBEDUCATION_COLLEGE'),
	'2' => JText::_('JS_JOBEDUCATION_HIGH_SCHOOL'),
	'3' => JText::_('JS_JOBEDUCATION_NO_SCHOOL'));

?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<script language="javascript">
<!--//
function myPopup(url,windowname,w,h,x,y){
window.open(url,windowname,"resizable=no,toolbar=no,scrollbars=no,menubar=no,status=no,directories=no,width="+w+",height="+h+",left="+x+",top="+y+"");
}
//-->
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<tr><td>
		<?php 
			if (sizeof($this->joblinks) != 0){	
				foreach($this->joblinks as $lnk)	{ ?>
				<?php echo $lnk; ?>
			<?php }
			} ?>
	</td></tr>
	<tr><td height="1"></td></tr>
	<tr><td>
		<?php 
			if (sizeof($this->emplinks) != 0){	
				foreach($this->emplinks as $lnk)	{ ?>
				<?php echo $lnk; ?>
			<?php } 
		}?>
	</td></tr>
	<tr><td height="13"></td></tr>
	<tr><td class="curloc">
		<?php if ($_GET['vea'] == '1'){ ?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_JOBS_APPLIED_APPLICATIONS'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=jobappliedapplications&jobid=<?php echo $_GET['jobid']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?></a> > <?php echo JText::_('JS_VIEW_EMP_APP'); ?>			
		<?php } else if ($_GET['vea'] == '2'){ 
					if (!isset($this->application)){
						$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$this->Itemid);
					}else{ ?>
					<?php echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_VIEW_EMP_APP'); ?>			
				<?php }
		} ?>
	</td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_VIEW_EMP_APP'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
      <tr>
        <td colspan="3" height="5"></td>
      </tr>
	  <?php  if ($_GET['vea'] == '2'){ ?>
		<tr height="31" valign="middle">
			<td colspan="2" align="left" class="sectionheadline">
			</td>
			<td align="right" class="sectionheadline">
			<?php
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&uid='.$this->application->uid.'&Itemid='.$this->Itemid;
			?>
			<a href="<?php echo $link ?>"><?php echo JText::_('JS_EDIT'); ?></a> 
			</td>
		</tr>
		<?php } ?>
      <tr><td colspan="3" height="5"></td></tr>
		<tr>
			<td colspan="2" height="35" valign="middle" align="center" class="sectionheadline">
			<?php if($this->application->resume != ''){ ?>
				<?php echo JText::_('JS_RESUME'); 
				$link = 'index2.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=resumeview&rq='.$this->application->id.'&Itemid='.$this->Itemid; ?>
				&nbsp;&nbsp;<a href="javascript:myPopup('<?php echo $link ?>', '<?php echo JText::_('JS_RESUME'); ?>','700','600','100','200')"><?php echo JText::_('JS_VIEW'); ?></a>				

			<?php } ?>

			</td>
			<td align="center" class="sectionheadline">
			<?php if($this->application->filename != ''){ ?>
				<b><?php echo JText::_('JS_RESUME_FILE'); ?></b>
			<?php if($this->application->filename != ''){ ?>
				<?php 
				if($this->application->filecontent == '')
					$link = $mainframe->getBasePath().'components/com_jsjobs/resume/'.$this->application->uid.'/'.$this->application->filename;
				else
					$link = 'index2.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=resumedownload&rq='.$this->application->id.'&Itemid='.$this->Itemid; ?>
				&nbsp;&nbsp;<a href="<?php echo $link ?>"><?php echo JText::_('JS_DOWNLOAD'); ?></a> 
			<?php } ?>
			<?php } ?>
			</td>
		</tr>
      <tr><td colspan="3" height="5"></td></tr>
		<tr>
			<td colspan="3" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
				<?php echo JText::_('JS_PERSONAL_INFORMATION'); ?>
			</td>
		</tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
        <td class="maintext" width="30%"><b><?php echo JText::_('JS_TITLE'); ?></b></td>
		<td class="maintext"><?php echo $this->application->application_title; 
		?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_FIRST_NAME'); ?></b></td>
		<td class="maintext"><?php echo $this->application->first_name; ?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_MIDDLE_NAME'); ?></b></td>
		<td class="maintext"><?php echo $this->application->middle_name; ?></td>
      </tr>
      <tr>
        <td colspan="3" height="1"></td>
      </tr>
     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_LAST_NAME'); ?></b></td>
		<td class="maintext"><?php echo $this->application->last_name; ?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_EMAIL_ADDRESS'); ?></b></td>
		<td class="maintext"><?php echo $this->application->email_address; ?></td>
      </tr>
      <tr>
        <td colspan="3" height="1"></td>
      </tr>
     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_HOME_PHONE'); ?></b></td>
		<td class="maintext"><?php echo $this->application->home_phone; ?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_WORK_PHONE'); ?></b></td>
		<td class="maintext"><?php echo $this->application->work_phone; ?></td>
      </tr>
      <tr>
        <td colspan="3" height="1"></td>
      </tr>
      <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_CELL'); ?></b></td>
		<td class="maintext"><?php echo $this->application->cell; ?></td>
      </tr>
		<tr height="21"><td colspan="3"></td></tr>
		<tr>
			<td width="100" colspan="3" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
				<?php echo JText::_('JS_BASIC_INFORMATION'); ?>
			</td>
		</tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
        <td class="maintext" width="30%"><b><?php echo JText::_('JS_CATEGORY'); ?></b></td>
		<td class="maintext"><?php echo $this->application->job_category; 
		?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_DESIRED_SALARY'); ?></b></td>
		<td class="maintext"><?php echo $salaryrange = $this->config['currency'] . $this->application->rangestart . ' - ' . $this->config['currency'] . $this->application->rangeend . JText::_('JS_PERMONTH'); ?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
        <td class="maintext" width="30%"><b><?php echo JText::_('JS_JOBTYPE') ?></b></td>
		<td class="maintext"><?php echo $jobtype[$this->application->jobtype-1]; 
		?></td>
      </tr>
      <tr><td colspan="3" height="1"></td></tr>
     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></b></td>
		<td class="maintext"><?php echo $heighesteducation[$this->application->heighestfinisheducation-1]; ?></td>
      </tr>
		<?php if ($this->config['resumeaddress'] == '1') { ?>
				<tr height="21"><td colspan="3"></td></tr>
				<tr>
					<td width="100" colspan="3" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
						<?php echo JText::_('JS_ADDRESS'); ?>
					</td>
				</tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address_city; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address_county; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address_state; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address_country; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->address_zipcode; ?></td>
		      </tr>
			
		<?php } ?>
		<?php if ($this->config['resumeeducation'] == '1') { ?>
				<tr height="21"><td colspan="3"></td></tr>
				<tr>
					<td width="100" colspan="3" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
						<?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?>
					</td>
				</tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_SCH_COL_UNI'); ?></b></td>
				<td class="maintext"><?php echo $this->application->institute; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_CRT_DEG_OTH'); ?></b></td>
				<td class="maintext"><?php echo $this->application->certificate_name; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_AREA_OF_STUDY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->study_area; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->institute_city; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->institute_county; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->institute_state; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->institute_country; 
				?></td>
		      </tr>
		<?php } ?>
		<?php if ($this->config['resumeemployer'] == '1') { ?>
				<tr height="21"><td colspan="3"></td></tr>
				<tr>
					<td width="100" colspan="3" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
						<?php echo JText::_('JS_RECENT_EMPLOYER'); ?>
					</td>
				</tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_EMPLOYER'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_POSITION'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_position; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_RESPONSIBILITIES'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_resp; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_FROM_DATE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_from_date; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['odd']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_TO_DATE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_to_date; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['even']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_LEAVING_REASON'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_leave_reason; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_city; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['even']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_county; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_state; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['even']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_country; 
				?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		     <tr class="<?php echo $this->theme['odd']; ?>"><td></td>
		        <td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_zip; ?></td>
		      </tr>
		      <tr><td colspan="3" height="1"></td></tr>
		      <tr class="<?php echo $this->theme['even']; ?>"><td width="5%">&nbsp;</td>
		        <td class="maintext" width="30%"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php echo $this->application->employer_phone; 
				?></td>
		      </tr>
		<?php } ?>
</table>
<div width="100%">
<?php include_once('components/com_jsjobs/views/fr_jscr.php'); ?>
</div>


