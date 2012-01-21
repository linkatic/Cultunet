<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 2nd, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsjobssearch.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');

JHTML :: _('behavior.calendar');


$sh_areaprofesional = $params->get('areaprofesional', 1);
$sh_areagestion = $params->get('areagestion', 1);
$sh_category = $params->get('category', 1);
$sh_jobtype = $params->get('jobtype', 1);
$sh_jobstatus = $params->get('jobstatus', 1);
$sh_salaryrange = $params->get('salaryrange', 1);
$sh_heighesteducation = $params->get('heighesteducation', 1);
$sh_shift = $params->get('shift', 1);
$sh_experience = $params->get('experience', 1);
$sh_durration = $params->get('durration', 1);
$sh_startpublishing = $params->get('startpublishing', 1);
$sh_stoppublishing = $params->get('stoppublishing', 1);
$sh_company = $params->get('company', 1);
$sh_addresses = $params->get('addresses', 1);

$itemid =  JRequest::getVar('Itemid');
$db =& JFactory::getDBO();

	//Area Profesional *********************************************
	if ($sh_areaprofesional == 1){
		$query = "SELECT id, fieldtitle FROM ".$db->nameQuote('#__js_job_userfieldvalues')." WHERE field = 7 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$areaprofesional = array();
			$areaprofesional[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)
				$areaprofesional[] =  array('value' => JText::_($row->id),'text' => JText::_($row->fieldtitle));
		}	
		$job_areaprofesional = JHTML::_('select.genericList', $areaprofesional, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}

	//Area Gestion *********************************************
	if ($sh_areagestion == 1){
		$query = "SELECT id, fieldtitle FROM ".$db->nameQuote('#__js_job_userfieldvalues')." WHERE field = 6 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$areagestion = array();
			$areagestion[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)
				$areagestion[] =  array('value' => JText::_($row->id),'text' => JText::_($row->fieldtitle));
		}	
		$job_areagestion = JHTML::_('select.genericList', $areagestion, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

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
		$job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	//Companies *********************************************
	if ($sh_company == 1){
		$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$companies = array();
			$companies[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)
				$companies[] =  array('value' => $row->id,'text' => $row->name);
		}	
		$search_companies = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	
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
				$jobtype[] =  array('value' => JText::_($row->id),'text' => JText::_($row->title));
		}	
		$job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Job Status *********************************************
	if ($sh_jobstatus == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobstatus')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$jobstatus = array();
			$jobstatus[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)	
				$jobstatus[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}	
		$job_status = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

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
	
	//Shifts *********************************************
	if ($sh_shift == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_shifts')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$shifts = array();
			$shifts[] =  array('value' => JText::_(''),'text' => JText::_('JS_SEARCH_ALL'));
			foreach($rows as $row)	
				$shifts[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}						
		$search_shift = JHTML::_('select.genericList', $shifts, 'shift', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
		
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
		$job_salaryrange = JHTML::_('select.genericList', $jobsalaryrange, 'jobsalaryrange', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
?>
<!--
<form action="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" method="post" name="adminForm" id="adminForm">
-->
<div id="buscador_empleo">
    <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='. $itemid);?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="isjobsearch" value="1" />
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <tr>
      	<td width="33%" align="left"><?php echo JText::_('JS_JOB_TITLE'); ?>
		<br><input class="inputbox" type="text" name="title" size="27" maxlength="255"  /> </td> 
	     <?php if ( $sh_category == 1 ) { ?>
			<td width="33%" valign="top" align="left"><?php echo JText::_('JS_CATEGORIES'); ?>
			<br><?php echo $job_categories; ?></td>
       	<?php } ?>
		<?php if ( $sh_areagestion == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_AREAGESTION'); ?>
        <br><?php echo $job_areagestion; ?></td>
       <?php } ?>

	  </tr>
	  <tr>
		<?php if ( $sh_areaprofesional == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_AREAPROFESIONAL'); ?>
        <br><?php echo $job_areaprofesional; ?></td>
       <?php } ?>
	  	<?php if ( $sh_shift == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_SHIFT'); ?>
        <br><?php echo $search_shift; ?></td>
       <?php } ?>
      <?php if ( $sh_salaryrange == 1 ) { ?>
	  <td valign="top" align="left"><?php echo JText::_('JS_SALARYRANGE'); ?>
        <br><?php echo $job_salaryrange; ?></td>
       <?php } ?>
	  </tr>
	  <tr>
      <?php if ( $sh_jobstatus == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_JOBSTATUS'); ?>
        <br><?php echo $job_status; ?></td>
       <?php } ?>

      <?php if ( $sh_experience == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_EXPERIENCE'); ?>
        <br><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>
       <?php } ?>
      <?php if ( $sh_durration == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_DURATION'); ?>
        <br><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>
       <?php } ?>
	  </tr>
	  <tr>
		  	 <?php /* if ( $sh_heighesteducation == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_HEIGHTESTEDUCATION'); ?>
        <br><?php echo $heighest_finisheducation; ?></td>
       <?php } */ ?>
	   		<?php if ( $sh_jobtype == 1 ) { ?>
      		<td width="33%" valign="top" align="left"><?php echo JText::_('JS_JOBTYPE'); ?>
	  		<br><?php echo $job_type; ?></td>
       	<?php } ?>
	  	      <?php if ( $sh_startpublishing == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_START_PUBLISHING'); ?>
        <br><input class="inputbox" type="text" name="startpublishing" id="startpublishingsr" readonly class="Shadow Bold" size="10" value="" />
		<input type="reset" class="button" value="..." onclick="return showCalendar('startpublishingsr','%Y-%m-%d');"  /></td>
       <?php } ?>
      <?php if ( $sh_stoppublishing == 1 ) { ?>
        <td valign="top" align="left"><?php echo JText::_('JS_STOP_PUBLISHING'); ?>
        <br><input class="inputbox" type="text" name="stoppublishing" id="stoppublishingsr" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishingsr','%Y-%m-%d');"  /></td>
       <?php } ?>

      <?php if ( $sh_company == 1 ) { ?>
        <td align="left"><?php echo JText::_('JS_COMPANYNAME'); ?>
        <br><?php echo $search_companies; ?>
        </td>
       <?php } ?>
	  </tr>
	  <tr>
	  	<td>
	  	<table>
	  		<tr>
			      <?php if ( $sh_addresses == 1 ) { ?>
			        <td align="left"><?php echo JText::_('JS_COUNTRY'); ?>
			        <br><span id="country">
					      <?php //echo $this->options['country']; ?>
						  <input class="inputbox" type="text" name="country" size="27" maxlength="100" />
			        </td>
			        <td align="left"><?php echo JText::_('JS_STATE'); ?>
			        <br><span id="state">
							<input class="inputbox" type="text" name="state" size="27" maxlength="100" />
					</td>
			        <td align="left"><?php echo JText::_('JS_COUNTY'); ?>
			        <br><span id="county">
							<input class="inputbox" type="text" name="county" size="27" maxlength="100" />
					 </td>
			        <td align="left"><?php echo JText::_('JS_CITY'); ?>
			        <br><span id="city">
							<input class="inputbox" type="text" name="city" size="27" maxlength="100"  />
			        </td>
			        <td align="left"><?php echo JText::_('JS_ZIPCODE'); ?>
			        <br><input class="inputbox" type="text" name="zipcode" size="27" maxlength="100"  />
			        </td>
			
			       <?php } ?>
		   </tr>
	  	</table>
		</td>
	  </tr>
	
 




	<tr>
		<td colspan="3" align="center">
		<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />
		</td>
	</tr>
    </table>

			<input type="hidden" name="view" value="jobseeker" />
			<input type="hidden" name="layout" value="job_searchresults" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="com_jsjobs" />
			
		  
		  
<script language=Javascript>
function dochange(src, val){
	var xhr; 
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e) 
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2) 
		{
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
   
      if(xhr.readyState == 4 && xhr.status == 200){
 
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
			countyhtml = "<input class='inputbox' type='text' name='county' size='27' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
			document.getElementById('county').innerHTML=countyhtml; //retuen value
			document.getElementById('city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
				document.getElementById('city').innerHTML=cityhtml; //retuen value
			}
 
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listmodsearchaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);

}

window.onLoad=dochange('country', -1);         // value in first dropdown
</script>
			  
		</form>
</div><!-- Fin buscador empleo -->
