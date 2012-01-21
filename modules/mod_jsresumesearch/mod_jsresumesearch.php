<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 29th, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jssresumeearch.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');

$sh_title = $params->get('title', 1);
$sh_name = $params->get('name', 1);
$sh_nationality = $params->get('natinality', 1);
$sh_gender = $params->get('gender', 1);
$sh_iamavailable = $params->get('iamavailable', 1);

$sh_category = $params->get('category', 1);
$sh_jobtype = $params->get('jobtype', 1);
$sh_salaryrange = $params->get('salaryrange', 1);
$sh_heighesteducation = $params->get('heighesteducation', 1);
$sh_experience = $params->get('experience', 1);

$itemid =  JRequest::getVar('Itemid');
$db =& JFactory::getDBO();



	// Gender *********************************************
	if ($sh_gender == 1){
		$genders = array(
			'0' => array('value' => '','text' => JText::_('JS_SEARCH_ALL')),
			'1' => array('value' => 1,'text' => JText::_('JS_MALE')),
			'2' => array('value' => 2,'text' => JText::_('JS_FEMALE')),);
		$gender = JHTML::_('select.genericList', $genders, 'gender','class="inputbox" style="width:160px;" '.'', 'value', 'text', '');
	}
	// Natinality *********************************************
	if ($sh_nationality == 1){
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$countries = array();
			$countries[] =  array('value' => JText::_(''),'text' => JText::_('JS_CHOOSE_COUNTRY'));
			foreach($rows as $row)	{
				$countries[] =  array('value' => $row->code,'text' => JText::_($row->name));
			}
		}	
		$nationality = JHTML::_('select.genericList', $countries, 'nationality','class="inputbox" style="width:160px;" '.'', 'value', 'text', '');
	}

	// Categories *********************************************
	if ($sh_category == 1){
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_categories')." WHERE isactive = 1 ORDER BY cat_title ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$jobcategories = array();
			$jobcategories[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)
				$jobcategories[] =  array('value' => JText::_($row->id),'text' => JText::_($row->cat_title));
		}	
		$job_categories = JHTML::_('select.genericList', $jobcategories, 'job_category', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	//Job Types *********************************************
	if ($sh_jobtype == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobtypes')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$jobtype = array();
			$jobtype[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)
				$obtype[] =  array('value' => JText::_($row->id),'text' => JText::_($row->title));
		}	
		$job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Job Heighest Education  *********************************************
	if ($sh_heighesteducation == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_heighesteducation')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$heighesteducation = array();
			$heighesteducation[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)	
				$heighesteducation[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}	
		$heighest_finisheducation = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	// Salary Rnage *********************************************
	if ( $sh_salaryrange == 1 ) { 
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config')." WHERE configname = 'currency' ";
		$db->setQuery( $query );
		$cur = $db->loadObject();
		if ($cur) $currency = $cur->configvalue;
		else $currency = 'Rs';
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_salaryrange')." ORDER BY 'id' ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$jobsalaryrange = array();
			$jobsalaryrange[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row){
				$salrange = $currency . $row->rangestart.' - '.$currency . $row->rangeend;
				$jobsalaryrange[] =  array('value' => JText::_($row->id),'text' => JText::_($salrange));
			}
		}	
		$salary_range = JHTML::_('select.genericList', $jobsalaryrange, 'jobsalaryrange', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
?>
<!--
<form action="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" method="post" name="adminForm" id="adminForm">
-->
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$itemid); ?>" method="post" name="adminForm" id="adminForm">
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <?php if ( $sh_title == 1 ) { ?>
      <tr>
        <td width="100%" align="left"><?php echo JText::_('JS_APPLICATION_TITLE'); ?>
		<br><input class="inputbox" type="text" name="title" size="27" maxlength="255"  />
        </td>
      </tr>
       <?php } ?>
      <?php if ( $sh_name == 1 ) { ?>
      <tr>
        <td align="left"><?php echo JText::_('JS_NAME'); ?>
		<br><input class="inputbox" type="text" name="name" size="27" maxlength="255"  />
        </td>
      </tr>
       <?php } ?>
	      <?php if ( $sh_nationality == 1 ) { ?>
      <tr>
        <td align="left"><?php echo JText::_('JS_NATIONALITY'); ?>
		<br><?php echo $nationality; ?>
        </td>
      </tr>
       <?php } ?>
      <?php if ( $sh_gender == 1 ) { ?>
		<tr>
			<td  align="left" class="textfieldtitle">	<?php echo JText::_('JS_GENDER');  ?>	
			<br><?php echo $gender;	?>	</td>
		</tr>
       <?php } ?>

      <?php if ( $sh_category == 1 ) { ?>
     <tr>
        <td valign="top" align="left"><?php echo JText::_('JS_CATEGORIES'); ?>
		<br><?php echo $job_categories; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $sh_jobtype == 1 ) { ?>
      <tr>
        <td valign="top" align="left"><?php echo JText::_('JS_JOBTYPE'); ?>
		<br><?php echo $job_type; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $sh_salaryrange == 1 ) { ?>
	  <tr>
        <td valign="top" align="left"><?php echo JText::_('JS_SALARYRANGE'); ?>
		<br><?php echo $salary_range; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $sh_heighesteducation == 1 ) { ?>
	   <tr>
        <td valign="top" align="left"><?php echo JText::_('JS_HEIGHTESTEDUCATION'); ?>
		<br><?php echo $heighest_finisheducation; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $sh_experience == 1 ) { ?>
	   <tr>
        <td valign="top" align="left"><?php echo JText::_('JS_EXPERIENCE'); ?>
		<br><input class="inputbox" type="text" name="experience" size="27" maxlength="25"  /></td>
      </tr>
       <?php } ?>

      <?php if ( $sh_iamavailable == 1 ) { ?>
			<tr>
				<td valign="top" align="left"><?php echo JText::_('JS_AVAILABLE'); ?>
				<input type='checkbox' name='iamavailable' value='1'  /></td>
			</tr>
       <?php } ?>
	<tr>
		<td colspan="2" align="center">
		<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />
		</td>
	</tr>
    </table>

 			<input type="hidden" name="isresumesearch" value="1" />
			<input type="hidden" name="view" value="employer" />
			<input type="hidden" name="layout" value="resume_searchresults" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="com_jsjobs" />
			
		  
		  
			  
		</form>

