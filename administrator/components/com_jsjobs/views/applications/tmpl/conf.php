<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/application/tmpl/conf.php
 ^ 
 * Description: Form for configuration
 ^ 
 * History:		NONE
 ^ 
 */

 JRequest :: setVar('layout', 'conf');
$_SESSION['cur_layout']='conf';

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

$theme = array(
	'0' => array('value' => JText::_('jsjobs01.css'),
					'text' => JText::_('JS_JS_JOBS_DEFAULT_THEME')),
	'1' => array('value' => JText::_('jsjobs02.css'),
					'text' => JText::_('JS_VIOLET_THEME')),
	'2' => array('value' => JText::_('jsjobs03.css'),
					'text' => JText::_('JS_PEACH_ORANGE_THEME')),
	'3' => array('value' => JText::_('jsjobs04.css'),
					'text' => JText::_('JS_GRASS_THEME')),
	'4' => array('value' => JText::_('jsjobs05.css'),
					'text' => JText::_('JS_PASTEL_PINK_THEME')),
	'5' => array('value' => JText::_('templatetheme.css'),
					'text' => JText::_('JS_TEMPLATE_THEME')),);

$yesno = array(
	'0' => array('value' => JText::_(1),
					'text' => JText::_('Yes')),
	'1' => array('value' => JText::_(0),
					'text' => JText::_('No')),);

$yesnobackup = array(
	'0' => array('value' => JText::_(1),
					'text' => JText::_('JS_YES_RECOMMENDED')),
	'1' => array('value' => JText::_(0),
					'text' => JText::_('No')),);

$showhide = array(
	'0' => array('value' => JText::_(1),
					'text' => JText::_('Show')),
	'1' => array('value' => JText::_(0),
					'text' => JText::_('Hide')),);

$offline = JHTML::_('select.genericList', $yesno, 'offline', 'class="inputbox" '. '', 'value', 'text', $this->config['offline']);

$themes = JHTML::_('select.genericList', $theme, 'theme', 'class="inputbox" '. '', 'value', 'text', $this->config['theme']);

$backup = JHTML::_('select.genericList', $yesnobackup, 'backuponuninstall', 'class="inputbox" '. '', 'value', 'text', $this->config['backuponuninstall']);

$companyautoapprove = JHTML::_('select.genericList', $yesno, 'companyautoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['companyautoapprove']);
$jobautoapprove = JHTML::_('select.genericList', $yesno, 'jobautoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['jobautoapprove']);
$empautoapprove = JHTML::_('select.genericList', $yesno, 'empautoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['empautoapprove']);

$curlocation = JHTML::_('select.genericList', $yesno, 'cur_location', 'class="inputbox" '. '', 'value', 'text', $this->config['cur_location']);
$showemployerlink = JHTML::_('select.genericList', $yesno, 'showemployerlink', 'class="inputbox" '. '', 'value', 'text', $this->config['showemployerlink']);
$job_editor = JHTML::_('select.genericList', $yesno, 'job_editor', 'class="inputbox" '. '', 'value', 'text', $this->config['job_editor']);

$search_job_showsave = JHTML::_('select.genericList', $yesno, 'search_job_showsave', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_showsave']);
$search_job_durration = JHTML::_('select.genericList', $showhide, 'search_job_durration', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_durration']);
$search_job_experience = JHTML::_('select.genericList', $showhide, 'search_job_experience', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_experience']);
$search_job_heighesteducation = JHTML::_('select.genericList', $showhide, 'search_job_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_heighesteducation']);
$search_job_salaryrange = JHTML::_('select.genericList', $showhide, 'search_job_salaryrange', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_salaryrange']);
$search_job_shift = JHTML::_('select.genericList', $showhide, 'search_job_shift', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_shift']);
$search_job_startpublishing = JHTML::_('select.genericList', $showhide, 'search_job_startpublishing', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_startpublishing']);
$search_job_stoppublishing = JHTML::_('select.genericList', $showhide, 'search_job_stoppublishing', 'class="inputbox" '. '', 'value', 'text', $this->config['search_job_stoppublishing']);

$search_resume_showsave = JHTML::_('select.genericList', $yesno, 'search_resume_showsave', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_showsave']);
$search_resume_available = JHTML::_('select.genericList', $showhide, 'search_resume_available', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_available']);
$search_resume_experience = JHTML::_('select.genericList', $showhide, 'search_resume_experience', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_experience']);
$search_resume_gender = JHTML::_('select.genericList', $showhide, 'search_resume_gender', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_gender']);
$search_resume_heighesteducation = JHTML::_('select.genericList', $showhide, 'search_resume_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_heighesteducation']);
$search_resume_name = JHTML::_('select.genericList', $showhide, 'search_resume_name', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_name']);
$search_resume_nationality = JHTML::_('select.genericList', $showhide, 'search_resume_nationality', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_nationality']);
$search_resume_salaryrange = JHTML::_('select.genericList', $showhide, 'search_resume_salaryrange', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_salaryrange']);
$search_resume_title = JHTML::_('select.genericList', $showhide, 'search_resume_title', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_title']);


$filter = JHTML::_('select.genericList', $showhide, 'filter', 'class="inputbox" '. '', 'value', 'text', $this->config['filter']);
$filter_address = JHTML::_('select.genericList', $showhide, 'filter_address', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_address']);
$filter_category = JHTML::_('select.genericList', $showhide, 'filter_category', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_category']);
$filter_jobtype = JHTML::_('select.genericList', $showhide, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_jobtype']);
$filter_heighesteducation = JHTML::_('select.genericList', $showhide, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_heighesteducation']);
$filter_salaryrange = JHTML::_('select.genericList', $showhide, 'filter_salaryrange', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_salaryrange']);

//$jpsalaryrange = JHTML::_('select.genericList', $showhide, 'jpsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->config['jpsalaryrange']);
//$jpqualification = JHTML::_('select.genericList', $showhide, 'jpqualification', 'class="inputbox" '. '', 'value', 'text', $this->config['jpqualification']);

$resumeaddress = JHTML::_('select.genericList', $showhide, 'resumeaddress', 'class="inputbox" '. '', 'value', 'text', $this->config['resumeaddress']);
$resumeeducation = JHTML::_('select.genericList', $showhide, 'resumeeducation', 'class="inputbox" '. '', 'value', 'text', $this->config['resumeeducation']);
$resumeemployer = JHTML::_('select.genericList', $showhide, 'resumeemployer', 'class="inputbox" '. '', 'value', 'text', $this->config['resumeemployer']);
?>
 <link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/jsjobs01.css" />

<script language="javascript" type="text/javascript">
/*	function submitbutton(pressbutton) {
		var form = document.adminForm;
			if (pressbutton == 'saveconf') {
				if (confirm ("<?php echo JText::_('JS_AREYOUSURE'); ?>")) {
					submitform( pressbutton );
				}
			} else {
				document.location.href = 'index.php';
			}
	}
*/</script>

<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

			<form action="index.php" method="POST" name="adminForm">
			<?php
			//echo '<br> item ' . $conf;
			?>
			  
			    <table cellpadding="2" cellspacing="4" border="0" width="100%" >
					<tr><td width="50%" valign="top">
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_SITE_SETTINGS'); ?>
								</td>
							</tr>
						  <tr align="center" valign="middle">
					         <td align="right" valign="top" width="50%"><?php echo JText::_('JS_TITLE'); ?></td>
					         <td align="left" valign="top"><input type="text" name="title" value="<?php echo $this->config['title']; ?>" class="inputbox" size="50" maxlength="255" /></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_OFFLINE'); ?></td>
					         <td align="left" valign="top"><?php if ($this->config['actk'] != '0') echo $offline; else echo '<a href="index.php?option=com_jsjobs&task=view&layout=updates">'.JText::_('JS_ACTIVATE_JSJOBS').'</a>';?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_OFFLINE_MESSAGE'); ?></td>
					         <td align="left" valign="top"><textarea name="offline_text" cols="25" rows="3" class="inputbox"><?php echo $this->config['offline_text']; ?></textarea> </td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_BACKUP_UNINSTALL'); ?></td>
					         <td align="left" valign="top"><?php echo $backup; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_DEFAULT_COUNTRY'); ?></td>
					         <td align="left" valign="top"><?php echo $this->lists['defaultcountry']; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_EMPLOYER_ALLOW'); ?></td>
					         <td align="left" valign="top"><?php echo $showemployerlink; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_EMPLOYER_DEFAULT_ROLE'); ?></td>
					         <td align="left" valign="top"><?php echo $this->lists['employerdefaultrole']; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_JOBSEEKER_DEFAULT_ROLE'); ?></td>
					         <td align="left" valign="top"><?php echo $this->lists['jobseekerdefaultrole']; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_COMPANY_AUTO_APPROVE'); ?></td>
					         <td align="left" valign="top"><?php echo $companyautoapprove; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_JOB_AUTO_APPROVE'); ?></td>
					         <td align="left" valign="top"><?php echo $jobautoapprove; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_RESUME_AUTO_APPROVE'); ?></td>
					         <td align="left" valign="top"><?php echo $empautoapprove; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_JOB_SHOW_EDITOR'); ?></td>
					         <td align="left" valign="top"><?php echo $job_editor; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_SHOW_CUR_LOCATION'); ?></td>
					         <td align="left" valign="top"><?php echo $curlocation; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_CURRENCY'); ?></td>
					         <td align="left" valign="top"><input type="text" name="currency" value="<?php echo $this->config['currency']; ?>" class="inputbox" size="5" maxlength="7" /></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_ITEMS_NEW_FOR'); ?></td>
					         <td align="left" valign="top"><input type="text" name="newdays" value="<?php echo $this->config['newdays']; ?>" class="inputbox" size="5" maxlength="5" /> &nbsp;<?php echo JText::_('JS_DAYS'); ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_COMPANY_LOGO_MAXIMUM_SIZE'); ?></td>
					         <td align="left" valign="top"><input type="text" name="company_logofilezize" value="<?php echo $this->config['company_logofilezize']; ?>" class="inputbox" size="5" maxlength="5" /> &nbsp;KB</td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_JOB_SEEKER_PHOTO_MAXIMUM_SIZE'); ?></td>
					         <td align="left" valign="top"><input type="text" name="resume_photofilesize" value="<?php echo $this->config['resume_photofilesize']; ?>" class="inputbox" size="5" maxlength="5" /> &nbsp;KB</td>
					      </tr>
						</table>
						<br>
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">
							
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_THEMES'); ?>
								</td>
							</tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_THEME'); ?></td>
					         <td align="left" valign="top"><?php echo $themes; ?></td>
					      </tr>
						</table>
						<br>
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">	
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_EMAIL_SETTINGS'); ?>
								</td>
							</tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top" width="40%"><?php echo JText::_('JS_MAIL_FROM_NAME'); ?></td>
					         <td align="left" valign="top"><input type="text" name="mailfromname" value="<?php echo $this->config['mailfromname']; ?>" class="inputbox" size="50" maxlength="255" /></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_MAIL_FROM_ADDRESS'); ?></td>
					         <td align="left" valign="top"><input type="text" name="mailfromaddress" value="<?php echo $this->config['mailfromaddress']; ?>" class="inputbox" size="50" maxlength="255" /></td>
					      </tr>
						</table>
					</td>		
					<td width="50%" valign="top">
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_SEARCH_JOB_SEETINGS'); ?>
								</td>
							</tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_ALLOW_SAVE_SEARCH'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_showsave; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_DURATION'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_durration; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_experience; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_heighesteducation; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_SALARY_RANGE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_salaryrange; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_SHIFT'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_shift; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_START_PUBLISHING'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_startpublishing; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_stoppublishing; ?></td>
					      </tr>
						</table>
						<br>
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">
							
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_SEARCH_RESUME_SETTINGS'); ?>
								</td>
							</tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_ALLOW_SAVE_SEARCH'); ?></td>
					         <td align="left" valign="top"><?php echo $search_job_showsave; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_TITLE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_title; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_NAME'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_name; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_GENDER'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_gender; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_AVAILABLE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_available; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_experience; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_heighesteducation; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_SALARY_RANGE'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_salaryrange; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_NATIONALITY'); ?></td>
					         <td align="left" valign="top"><?php echo $search_resume_nationality; ?></td>
					      </tr>
						</table>
						<br>
						<table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminlist">
							<tr>
								<td width="100%" colspan="2" align="center" class="sectionheadline">
									<?php echo JText::_('JS_FILTER_SETTINGS'); ?>
								</td>
							</tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_FILTER'); ?></td>
					         <td align="left" valign="top"><?php echo $filter; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_ADDRESS'); ?></td>
					         <td align="left" valign="top"><?php echo $filter_address; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_FILTER_ADDRESS_FIELDS_WIDTH'); ?></td>
					         <td align="left" valign="top"><input type="text" name="filter_address_fields_width" value="<?php echo $this->config['filter_address_fields_width']; ?>" class="inputbox" size="5" maxlength="5" /> &nbsp;px</td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_CATEGORY'); ?></td>
					         <td align="left" valign="top"><?php echo $filter_category; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_JOB_TYPE'); ?></td>
					         <td align="left" valign="top"><?php echo $filter_jobtype; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" width="50%" valign="top"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
					         <td align="left" valign="top"><?php echo $filter_heighesteducation; ?></td>
					      </tr>
					      <tr align="center" valign="middle">
					         <td align="right" valign="top"><?php echo JText::_('JS_SALARY_RANGE'); ?></td>
					         <td align="left" valign="top"><?php echo $filter_salaryrange; ?></td>
					      </tr>
						</table>

					</td></tr>
					<tr><td>
					</td><td valign="top">

					</td></tr>		
			    </table>
			  

				<input type="hidden" name="task" value="saveconf" />
				<input type="hidden" name="version" value="<?php echo $conf->version; ?>" />
				<input type="hidden" name="view" value="application" />
				<input type="hidden" name="layout" value="conf" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />


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
