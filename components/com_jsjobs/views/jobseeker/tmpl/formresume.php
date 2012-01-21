<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/formemp.php
 ^ 
 * Description: template for long employment application form
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
JHTML :: _('behavior.calendar');
JHTMLBehavior::formvalidation();
//JHTML::_('behavior.formvalidation') 

 global $mainframe;
 
$pane =& JPane::getInstance('tabs');
$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;

$section_personal = 1;
$section_basic = 1;
$section_addresses = 0;
$section_sub_address = 0;
$section_sub_address1 = 0;
$section_sub_address2 = 0;
$section_education = 0;
$section_sub_institute = 0;
$section_sub_institute1 = 0;
$section_sub_institute2 = 0;
$section_sub_institute3 = 0;
$section_employer = 0;
$section_sub_employer = 0;
$section_sub_employer1 = 0;
$section_sub_employer2 = 0;
$section_sub_employer3 = 0;
$section_skills = 0;
$section_resumeeditor = 0;
$section_references = 0;
$section_sub_reference = 0;
$section_sub_reference1 = 0;
$section_sub_reference2 = 0;
$section_sub_reference3 = 0;

foreach($this->fieldsordering as $field){ 
	switch ($field->field){
		case "section_addresses" :	$section_addresses = $field->published;	break;
		case "section_sub_address" :	$section_sub_address = $field->published;	break;
		case "section_sub_address1" :	$section_sub_address1 = $field->published;	break;
		case "section_sub_address2" :	$section_sub_address2 = $field->published;	break;
		case "section_education" :	$section_education = $field->published;	break;
		case "section_sub_institute" :	$section_sub_institute = $field->published;	break;
		case "section_sub_institute1" : $section_sub_institute1 = $field->published; break;
		case "section_sub_institute2" :	$section_sub_institute2 = $field->published; break;
		case "section_sub_institute3" :	$section_sub_institute3 = $field->published; break;
		case "section_employer" :	$section_employer = $field->published; break;
		case "section_sub_employer" :	$section_sub_employer = $field->published; break;
		case "section_sub_employer1" :	$section_sub_employer1 = $field->published;	break;
		case "section_sub_employer2" :	$section_sub_employer2 = $field->published;	break;
		case "section_sub_employer3" :	$section_sub_employer3 = $field->published; break;
		case "section_skills" :	$section_skills = $field->published; break;
		case "section_resumeeditor" :	$section_resumeeditor = $field->published; break;
		case "section_references" :	$section_references = $field->published; break;
		case "section_sub_reference" :	$section_sub_reference = $field->published; break;
		case "section_sub_reference1" :	$section_sub_reference1 = $field->published; break;
		case "section_sub_reference2" :	$section_sub_reference2 = $field->published; break;
		case "section_sub_reference3" :	$section_sub_reference3 = $field->published; break;
	}
}
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2" class="active"><?php echo JText::_('JS_ADD_CV'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid=3" class="active"><?php echo JText::_('JS_ADD_CARTA'); ?></a></li> */ ?>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=3"><?php echo JText::_('JS_CV_GUARDADOS'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=3"><?php echo JText::_('JS_CARTAS_GUARDADAS'); ?></a></li> */ ?>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=3"><?php echo JText::_('JS_SOLICITUDES'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid=3"><?php echo JText::_('JS_OFERTAS_PREFERIDAS'); ?></a></li> */ ?>
		</ul>
	</div>
</div>
<?php if ($this->config['offline'] == '1'){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	<?php echo $this->config['title']; ?></td></tr>
	<tr><td height="25"></td></tr>
	<tr><td class="jsjobsmsg">
		<?php echo $this->config['offline_text']; ?>
	</td></tr>
</table>	
<?php }else{ ?>
<script language="javascript">
function myValidate(f) {
    if (document.formvalidator.isValid(f)) {
                f.check.value='<?php echo JUtility::getToken(); ?>';//send token
        }else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>
<?php

/*
 * 
 * Ocultamos la barra de administración específica del componente
 * 
 */
 
 /*

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php if ($this->vm == '1'){ //my resume ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_RESUME'); ?></a> > <?php echo JText::_('JS_RESUME_FORM'); ?>
		<?php }else { ?>	
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_RESUME_FORM'); ?>
		<?php }?>	
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<span <?php if($lnk[2] == 1)echo 'class="first"'; elseif($lnk[2] == -1)echo 'class="last"';  ?>>
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php }
				echo '<ul></div>';			
			} 
		?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_RESUME_FORM'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>


*/

?>
<?php
$printform = 1;
if ($this->userrole->rolefor == 2) { // job seeker
 
	$printform = 1;
	if ((isset($this->resume)) &&($this->resume->id != 0)) { // not new form
		if ($this->resume->status == 1) { // Employment Application is actve
			$printform = 1;
		}else if($this->resume->status == 0){ // not allowed job posting
			$printform = 0;
			echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_WAIT_APPROVAL') . "</strong></font>";
		} else{ // not allowed job posting
			$printform = 0;
			echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_REJECT') . "</strong></font>";
		}
	}
}else{ // not allowed job posting
	$printform = 0;
	echo "<font color='red'><strong>" . JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW') . "</strong></font>";
}

if ($printform == 1) {
if ($this->canaddnewresume == 1) { // add new resume, in edit case always 1

?>
<div id="form-jobseeker">
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate"  onSubmit="return myValidate(this);">
			<?php  echo $pane->startPane('myPane');  ?>
			<?php
				$i = 0;
				foreach($this->fieldsordering as $field){ 
					//echo '<br> uf'.$field->field;
					//$sec = substr($row->field, 0,8); 
					//if ($sec == 'section_') {
					switch ($field->field) {
						case "section_personal": ?>
								<?php echo $pane->startPanel(JText::_('JS_PERSONAL'),'personal'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td width="200" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_PERSONAL_INFORMATION'); ?>
										</td>
									</tr>
						
						<?php break;
						case "applicationtitle": ?>
							<tr>
								<td align="right" class="textfieldtitle">
									<label id="application_titlemsg" for="application_title"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></label>&nbsp;<span class="requerido">*</span>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="application_title" id="application_title" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->application_title;?>" />
								</td>
							</tr>
						<?php break;
						case "firstname": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="first_namemsg" for="first_name"><?php echo JText::_('JS_FIRST_NAME'); ?></label>&nbsp;<span class="requerido">*</span>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="first_name" id="first_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->first_name;?>" />
								</td>
							</tr>
						<?php break;
						case "middlename": ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_MIDDLE_NAME'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="middle_name" id="middle_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->middle_name;?>" />
								</td>
							</tr>
						<?php } ?>
						<?php break;
						case "lastname": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="last_namemsg" for="last_name"><?php echo JText::_('JS_LAST_NAME'); ?></label>&nbsp;<span class="requerido">*</span>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="last_name" id="last_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->last_name;?>" />
								</td>
							</tr>
						<?php break;
						case "emailaddress": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="email_addressmsg" for="email_address"><?php echo JText::_('JS_EMAIL_ADDRESS'); ?></label>&nbsp;<span class="requerido">*</span>:
								</td>
								<td>
									<input class="inputbox required validate-email" type="text" name="email_address" id="email_address" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->email_address;?>" />
								</td>						
							</tr>
						<?php break;
						case "homephone": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_HOME_PHONE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="home_phone" id="home_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->home_phone;?>" />
								</td>						
							</tr>
						<?php break;
						case "workphone": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_WORK_PHONE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="work_phone" id="work_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->work_phone;?>" />
								</td>						
							</tr>
						<?php break;
						case "cell": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CELL'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="cell" id="cell" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->cell;?>" />
								</td>						
							</tr>
						<?php break;
						case "gender": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_GENDER');  ?>:
								</td>
								<td><?php echo $this->resumelists['gender'];	?>	</td>
							</tr>
						<?php break;
						case "Iamavailable": ?>
							<tr>
								<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
								<td><input type='checkbox' class="checkbox" name='iamavailable' value='1' <?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> /></td>
							</tr>
					<?php break;
					case "photo": ?>
							<tr>
								<?php if (isset($this->resume)) 
											if($this->resume->photo != '') {?>
												<tr><td></td><td style="max-width:150px;max-height:150px;overflow:hidden;text-overflow:ellipsis">
													<img src="components/com_jsjobs/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
												</td></tr>
												<tr><td></td><td><input type='checkbox' class="checkbox"  name='deletephoto' value='1'><?php echo JText::_('JS_DELETE_PHOTO'); ?></td></tr>
								<?php } ?>				
								<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_PHOTO');  ?>:
								</td>
									<td>
										<input type="file" class="inputbox" name="photo" size="20" maxlenght='30'/>
										<br><small><?php echo JText::_('JS_WIDTH');?> : 150px; <?php echo JText::_('JS_HEIGHT');?> : 150px</small>
										<br><small><?php echo JText::_('JS_MAXIMUM_FILE_SIZE').' ('.$this->config['resume_photofilesize']; ?>KB)</small>
									</td>
							</tr>
						<?php break;
						case "nationality": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_NATIONALITY_COUNTRY');  ?>:
								</td>
								<td><?php echo $this->resumelists['nationality']; ?></td>
							</tr>
						<?php break;
						case "section_basic": ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_BASIC_INFORMATION'); ?>
								</td>
							</tr>
						<?php break;
						case "category": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CATEGORY');  ?>:
								</td>
								<td>
									<?php
										echo $this->resumelists['job_category'];
									?>
								</td>
							</tr>
						<?php break;
						case "salary": ?>
							<tr>
								<td width="100"align="right" class="textfieldtitle">
									<?php echo JText::_('JS_DESIRED_SALARY'); ?>:
								</td>
								<td colspan="2" >
									<?php echo $this->resumelists['jobsalaryrange'] . JText::_('JS_PERMONTH'); ?>
								</td>
							</tr>
						<?php break;
						case "jobtype": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_WORK_PREFERENCE'); ?>:	</td>
								<td colspan="2" valign="top" >
									<?php echo $this->resumelists['jobtype']; ?>
								</td>
							</tr>
						<?php break;
						case "heighesteducation": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?>:</td>
								<td colspan="2" valign="top" >
									<?php
										//echo $this->resumelists['work_preferences'];
										echo $this->resumelists['heighestfinisheducation']; 
									?>
								</td>
							</tr>
						<?php break;
						case "totalexperience": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_TOTAL_EXPERIENCE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="total_experience" id="total_experience" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->total_experience;?>" />
								</td>						
							</tr>
						<?php break;
						case "startdate": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_CAN_START'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="date_start" id="date_start" readonly size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->date_start;?>" />
									<img class="calendar" src="/gestionycultura.com/templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('date_start','%d-%m-%Y');" id="date_start">
								</td>						
							</tr>
					<?php break;
						case "section_addresses": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_ADDRESSES'),'addresses'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_ADDRESS'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "address_country": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resumelists['address_country']; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_state": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address_state">
								<?php
									if((isset($this->resumelists['address_state'])) && ($this->resumelists['address_state']!='')){
										echo $this->resumelists['address_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address_state" id="address_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address_state;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_county": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address_county">
								<?php 
									if((isset($this->resumelists['address_county'])) && ($this->resumelists['address_county']!='')){
										echo $this->resumelists['address_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address_county" id="address_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address_county;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address_city">
								<?php 
									if((isset($this->resumelists['address_city'])) && ($this->resumelists['address_city']!='')){
										echo $this->resumelists['address_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address_city" id="address_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address_city;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address_zipcode" id="address_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address" id="address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address1": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS1'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address1_country": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resumelists['address1_country']; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_state": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address1_state">
								<?php
									if((isset($this->resumelists['address1_state'])) && ($this->resumelists['address1_state']!='')){
										echo $this->resumelists['address1_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address1_state" id="address1_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address1_state;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_county": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address1_county">
								<?php 
									if((isset($this->resumelists['address1_county'])) && ($this->resumelists['address1_county']!='')){
										echo $this->resumelists['address1_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address1_county" id="address1_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address1_county;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address1_city">
								<?php 
									if((isset($this->resumelists['address1_city'])) && ($this->resumelists['address1_city']!='')){
										echo $this->resumelists['address1_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address1_city" id="address1_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address1_city;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address1_zipcode" id="address1_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address1_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address1" id="address1" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address1;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address2": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS2'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address2_country": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resumelists['address2_country']; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_state": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address2_state">
								<?php
									if((isset($this->resumelists['address2_state'])) && ($this->resumelists['address2_state']!='')){
										echo $this->resumelists['address2_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address2_state" id="address2_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address2_state;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_county": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address2_county">
								<?php 
									if((isset($this->resumelists['address2_county'])) && ($this->resumelists['address2_county']!='')){
										echo $this->resumelists['address2_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address2_county" id="address2_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->address2_county;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address2_city">
								<?php 
									if((isset($this->resumelists['address2_city'])) && ($this->resumelists['address2_city']!='')){
										echo $this->resumelists['address2_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address2_city" id="address2_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address2_city;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address2_zipcode" id="address2_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address2_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address2" id="address2" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address2;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_education": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_EDUCATIONS'),'education'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_HIGH_SCHOOL'); ?>
												</td>
											</tr>
										<?php } ?>

										
					<?php break;
						case "institute_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute" id="institute" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute_certificate_name" id="institute_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute_study_area" id="institute_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_country": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute_country">
									<?php echo $this->resumelists['institute_country']; ?>
										<!--<input class="textfield" type="text" name="institute_country" id="institute_country" size="<?php echo $sml_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_country;?>" />-->
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_state": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute_state">
									<?php
										if((isset($this->resumelists['institute_state'])) && ($this->resumelists['institute_state']!='')){
											echo $this->resumelists['institute_state']; 
										} else{ ?>
										<input class="inputbox" type="text" name="institute_state" id="institute_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_state;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_county": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute_county">
									<?php
										if((isset($this->resumelists['institute_county'])) && ($this->resumelists['institute_county']!='')){
											echo $this->resumelists['institute_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="institute_county" id="institute_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_county;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute_city">
										<?php
											if((isset($this->resumelists['institute_city'])) && ($this->resumelists['institute_city']!='')){
												echo $this->resumelists['institute_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute_city" id="institute_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute_city;?>" />
										<?php } ?>
										</td>
								</tr>
							<?php } ?>

					<?php break;
						case "section_sub_institute1": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_UNIVERSITY'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute1" id="institute1" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute1;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute1_certificate_name" id="institute1_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute1_study_area" id="institute1_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_country": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute1_country">
									<?php echo $this->resumelists['institute1_country']; ?>
										<!--<input class="textfield" type="text" name="institute_country" id="institute_country" size="<?php echo $sml_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_country;?>" />-->
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_state": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute1_state">
									<?php
										if((isset($this->resumelists['institute1_state'])) && ($this->resumelists['institute1_state']!='')){
											echo $this->resumelists['institute1_state']; 
										} else{ ?>
										<input class="inputbox" type="text" name="institute1_state" id="institute1_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_state;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_county": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute1_county">
									<?php
										if((isset($this->resumelists['institute1_county'])) && ($this->resumelists['institute1_county']!='')){
											echo $this->resumelists['institute1_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="institute1_county" id="institute1_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_county;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute1_city">
										<?php
											if((isset($this->resumelists['institute1_city'])) && ($this->resumelists['institute1_city']!='')){
												echo $this->resumelists['institute1_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute1_city" id="institute1_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_city;?>" />
										<?php } ?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute2": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_GRADE_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute2" id="institute2" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute2;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute2_certificate_name" id="institute2_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute2_study_area" id="institute2_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_country": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute2_country">
									<?php echo $this->resumelists['institute2_country']; ?>
										<!--<input class="textfield" type="text" name="institute_country" id="institute_country" size="<?php echo $sml_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_country;?>" />-->
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_state": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute2_state">
									<?php
										if((isset($this->resumelists['institute2_state'])) && ($this->resumelists['institute2_state']!='')){
											echo $this->resumelists['institute2_state']; 
										} else{ ?>
										<input class="inputbox" type="text" name="institute2_state" id="institute2_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_state;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_county": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute2_county">
									<?php
										if((isset($this->resumelists['institute2_county'])) && ($this->resumelists['institute2_county']!='')){
											echo $this->resumelists['institute2_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="institute2_county" id="institute2_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_county;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute2_city">
										<?php
											if((isset($this->resumelists['institute2_city'])) && ($this->resumelists['institute2_city']!='')){
												echo $this->resumelists['institute2_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute2_city" id="institute2_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_city;?>" />
										<?php } ?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute3": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_OTHER_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute3" id="institute3" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute3;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute3_certificate_name" id="institute3_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute3_study_area" id="institute3_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_country": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute3_country">
									<?php echo $this->resumelists['institute3_country']; ?>
										<!--<input class="textfield" type="text" name="institute_country" id="institute_country" size="<?php echo $sml_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute_country;?>" />-->
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_state": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute3_state">
									<?php
										if((isset($this->resumelists['institute3_state'])) && ($this->resumelists['institute3_state']!='')){
											echo $this->resumelists['institute3_state']; 
										} else{ ?>
										<input class="inputbox" type="text" name="institute3_state" id="institute3_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_state;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_county": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute3_county">
									<?php
										if((isset($this->resumelists['institute3_county'])) && ($this->resumelists['institute3_county']!='')){
											echo $this->resumelists['institute3_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="institute3_county" id="institute3_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_county;?>" />
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute3_city">
										<?php
											if((isset($this->resumelists['institute3_city'])) && ($this->resumelists['institute3_city']!='')){
												echo $this->resumelists['institute3_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute3_city" id="institute3_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_city;?>" />
										<?php } ?>
										</td>
								</tr>
							<?php } ?>

							

					<?php break;
						case "section_employer": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_EMPLOYERS'),'employer'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_RECENT_EMPLOYER'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "employer_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer" id="employer" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_position" id="employer_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_resp" id="employer_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_pay_upon_leaving" id="employer_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_supervisor" id="employer_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_from_date" id="employer_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer_to_date" id="employer_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_leave_reason" id="employer_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_country": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer_country">
									<?php echo $this->resumelists['employer_country']; ?>
									<!--<input class="textfield" type="text" name="employer_country" id="employer_country" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_country;?>" />-->
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_state": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer_state">
									<?php
										if((isset($this->resumelists['employer_state'])) && ($this->resumelists['employer_state']!='')){
											echo $this->resumelists['employer_state']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer_state" id="employer_state" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_state;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_county": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer_county">
									<?php
										if((isset($this->resumelists['employer_county'])) && ($this->resumelists['employer_county']!='')){
											echo $this->resumelists['employer_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer_county" id="employer_county" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_county;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer_city">
									<?php
										if((isset($this->resumelists['employer_city'])) && ($this->resumelists['employer_city']!='')){
											echo $this->resumelists['employer_city']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer_city" id="employer_city" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_city;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_zip" id="employer_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_address" id="employer_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_phone" id="employer_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_phone;?>" />
								</td>
							</tr>
							<?php } ?>


					<?php break;
						case "section_sub_employer1": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_1'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1" id="employer1" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_position" id="employer1_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_resp" id="employer1_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_pay_upon_leaving" id="employer1_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_supervisor" id="employer1_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_from_date" id="employer1_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer1_to_date" id="employer1_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_leave_reason" id="employer1_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_country": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer1_country">
									<?php echo $this->resumelists['employer1_country']; ?>
									<!--<input class="textfield" type="text" name="employer_country" id="employer_country" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_country;?>" />-->
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_state": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer1_state">
									<?php
										if((isset($this->resumelists['employer1_state'])) && ($this->resumelists['employer1_state']!='')){
											echo $this->resumelists['employer1_state']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer1_state" id="employer1_state" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_state;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_county": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer1_county">
									<?php
										if((isset($this->resumelists['employer1_county'])) && ($this->resumelists['employer1_county']!='')){
											echo $this->resumelists['employer1_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer1_county" id="employer1_county" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_county;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer1_city">
									<?php
										if((isset($this->resumelists['employer1_city'])) && ($this->resumelists['employer1_city']!='')){
											echo $this->resumelists['employer1_city']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer1_city" id="employer1_city" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_city;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_zip" id="employer1_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_address" id="employer1_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer1_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_phone" id="employer1_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer2": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_2'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2" id="employer2" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_position" id="employer2_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_resp" id="employer2_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_pay_upon_leaving" id="employer2_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_supervisor" id="employer2_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_from_date" id="employer2_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer2_to_date" id="employer2_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_leave_reason" id="employer2_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_country": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer2_country">
									<?php echo $this->resumelists['employer2_country']; ?>
									<!--<input class="textfield" type="text" name="employer_country" id="employer_country" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_country;?>" />-->
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_state": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer2_state">
									<?php
										if((isset($this->resumelists['employer2_state'])) && ($this->resumelists['employer2_state']!='')){
											echo $this->resumelists['employer2_state']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer2_state" id="employer2_state" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_state;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_county": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer2_county">
									<?php
										if((isset($this->resumelists['employer2_county'])) && ($this->resumelists['employer2_county']!='')){
											echo $this->resumelists['employer2_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer2_county" id="employer2_county" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_county;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer2_city">
									<?php
										if((isset($this->resumelists['employer2_city'])) && ($this->resumelists['employer2_city']!='')){
											echo $this->resumelists['employer2_city']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer2_city" id="employer2_city" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_city;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_zip" id="employer2_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_address" id="employer2_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_address;?>" />
								</td>
							</tr>
						<?php } ?>	
				<?php break;
						case "employer2_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_phone" id="employer2_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer3": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_3'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3" id="employer3" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_position" id="employer3_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_resp" id="employer3_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_pay_upon_leaving" id="employer3_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_supervisor" id="employer3_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_from_date" id="employer3_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer3_to_date" id="employer3_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_leave_reason" id="employer3_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_country": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer3_country">
									<?php echo $this->resumelists['employer3_country']; ?>
									<!--<input class="textfield" type="text" name="employer_country" id="employer_country" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_country;?>" />-->
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_state": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer3_state">
									<?php
										if((isset($this->resumelists['employer3_state'])) && ($this->resumelists['employer3_state']!='')){
											echo $this->resumelists['employer3_state']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer3_state" id="employer3_state" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_state;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_county": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer3_county">
									<?php
										if((isset($this->resumelists['employer3_county'])) && ($this->resumelists['employer3_county']!='')){
											echo $this->resumelists['employer3_county']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer3_county" id="employer3_county" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_county;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer3_city">
									<?php
										if((isset($this->resumelists['employer3_city'])) && ($this->resumelists['employer3_city']!='')){
											echo $this->resumelists['employer3_city']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer3_city" id="employer3_city" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_city;?>" />
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_zip" id="employer3_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_address" id="employer3_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer3_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_phone" id="employer3_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_phone;?>" />
								</td>
							</tr>
							<?php } ?>
							

					<?php break;
						case "section_skills": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
							<?php echo $pane->startPanel(JText::_('JS_SKILLS'),'skills'); ?>
								<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_SKILLS'); ?>
										</td>
									</tr>
					<?php break;
						case "driving_license": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_HAVE_DRIVING_LICENSE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="driving_license" id="driving_license" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->driving_license;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_no": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_NO'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="license_no" id="license_no" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->license_no;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_country": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_COUNTRY'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="license_country" id="license_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->license_country;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "skills": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SKILLS'); ?>:</td>
								<td>
									<textarea class="inputbox" name="skills" id="skills" cols="60" rows="9"><?php if(isset($this->resume)) echo $this->resume->skills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
										
										
					<?php break;
						case "section_resumeeditor": ?>
							</table>	

							<?php  echo $pane->endPanel(); ?>
							<?php echo $pane->startPanel(JText::_('JS_RESUME_EDITOR'),'editor'); ?>
								<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_RESUME_EDITOR'); ?>
										</td>
									</tr>


							
					<?php break;
						case "editor": ?>
						<?php /*
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr>
									<td colspan="2">
									    <?php
			                          //print editorAreaJx( 'editor1',  $this->resume->resume, 'resume', '40', '10' );
									               $editor =& JFactory::getEditor();
			                                        if(isset($this->resume))
														echo $editor->display('resume', $this->resume->resume, '550', '400', '60', '20', false);
													else
														echo $editor->display('resume', '', '550', '400', '60', '20', false);
			                                ?>

									</td>
								</tr>
							<?php } ?>
							*/ ?>
					<?php break;
						case "fileupload": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr><td></td><td><strong><?php echo JText::_('JS_ALSO_RESUME_FILE'); ?></strong></td></tr>
								<?php if (isset($this->resume)) 
											if($this->resume->filename != '') {?>
												<tr><td></td><td><input type='checkbox' class="checkbox"  name='deleteresumefile' value='1'><?php echo JText::_('JS_DELETE_RESUME_FILE') .'['.$this->resume->filename.']'; ?></td></tr>
								<?php } ?>				
								<tr>
									<td width="150" align="right" class="textfieldtitle">
										<?php echo JText::_('JS_RESUME_FILE'); ?>:
									</td>
									<td>
										<input type="file" class="inputbox" name="resumefile" size="20" maxlenght='30'/>
										<input type='hidden' maxlenght=''/>
									</td>
								</tr>
							<?php } ?>
						
						
					<?php break;
						case "section_references": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_REFERENCES'),'references'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
										<tr>
											<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
												<?php echo JText::_('JS_REFERENCE1'); ?>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "reference_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference_name" id="reference_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_name;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_country": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference_country">
								<?php echo $this->resumelists['reference_country']; ?>
									<!--<input class="textfield" type="text" name="address_country" idaddress_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address_country;?>" /> -->
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_state": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference_state">
								<?php
									if((isset($this->resumelists['reference_state'])) && ($this->resumelists['reference_state']!='')){
										echo $this->resumelists['reference_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference_state" id="reference_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference_state;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_county": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference_county">
						        <?php 
									if((isset($this->resumelists['reference_county'])) && ($this->resumelists['reference_county']!='')){
										echo $this->resumelists['reference_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference_county" id="reference_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference_county;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference_city">
								<?php 
									if((isset($this->resumelists['reference_city'])) && ($this->resumelists['reference_city']!='')){
										echo $this->resumelists['reference_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference_city" id="reference_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference_city;?>" />
								<?php } ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference_zipcode" id="reference_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference_address" id="reference_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference_address;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_phone" id="reference_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_relation" id="reference_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_relation;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_years" id="reference_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference1": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE2'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference1_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference1_name" id="reference1_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_country": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference1_country">
								<?php echo $this->resumelists['reference1_country']; ?>
									<!--<input class="textfield" type="text" name="address_country" idaddress_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address_country;?>" /> -->
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_state": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference1_state">
								<?php
									if((isset($this->resumelists['reference1_state'])) && ($this->resumelists['reference1_state']!='')){
										echo $this->resumelists['reference1_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference1_state" id="reference1_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_state;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_county": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference1_county">
						        <?php 
									if((isset($this->resumelists['reference1_county'])) && ($this->resumelists['reference1_county']!='')){
										echo $this->resumelists['reference1_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference1_county" id="reference1_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_county;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference1_city">
								<?php 
									if((isset($this->resumelists['reference1_city'])) && ($this->resumelists['reference1_city']!='')){
										echo $this->resumelists['reference1_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference1_city" id="reference1_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_city;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference1_zipcode" id="reference1_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference1_address" id="reference1_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_phone" id="reference1_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_relation" id="reference1_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_years" id="reference1_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference2": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE3'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference2_name" id="reference2_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_country": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference2_country">
								<?php echo $this->resumelists['reference2_country']; ?>
									<!--<input class="textfield" type="text" name="address_country" idaddress_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address_country;?>" /> -->
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_state": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference2_state">
								<?php
									if((isset($this->resumelists['reference2_state'])) && ($this->resumelists['reference2_state']!='')){
										echo $this->resumelists['reference2_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference2_state" id="reference2_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_state;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_county": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference2_county">
						        <?php 
									if((isset($this->resumelists['reference2_county'])) && ($this->resumelists['reference2_county']!='')){
										echo $this->resumelists['reference2_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference2_county" id="reference2_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_county;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference2_city">
								<?php 
									if((isset($this->resumelists['reference2_city'])) && ($this->resumelists['reference2_city']!='')){
										echo $this->resumelists['reference2_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference2_city" id="reference2_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_city;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference2_zipcode" id="reference2_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference2_address" id="reference2_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_phone" id="reference2_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_relation" id="reference2_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_years" id="reference2_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference3": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE4'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference3_name" id="reference3_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_country": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference3_country">
								<?php echo $this->resumelists['reference3_country']; ?>
									<!--<input class="textfield" type="text" name="address_country" idaddress_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address_country;?>" /> -->
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_state": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference3_state">
								<?php
									if((isset($this->resumelists['reference3_state'])) && ($this->resumelists['reference3_state']!='')){
										echo $this->resumelists['reference3_state']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference3_state" id="reference3_state" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_state;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_county": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference3_county">
						        <?php 
									if((isset($this->resumelists['reference3_county'])) && ($this->resumelists['reference3_county']!='')){
										echo $this->resumelists['reference3_county']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference3_county" id="reference3_county" size="<?php echo $med_field_width; ?>" maxlength="50" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_county;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference3_city">
								<?php 
									if((isset($this->resumelists['reference3_city'])) && ($this->resumelists['reference3_city']!='')){
										echo $this->resumelists['reference3_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="reference3_city" id="reference3_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_city;?>" />
								<?php } ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference3_zipcode" id="reference3_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference3_address" id="reference3_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_phone" id="reference3_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_relation" id="reference3_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_years" id="reference3_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_years;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php		
						break;		
					?>
										
					<?php } ?>	
				<?php } ?>	
							
						</table>	
					<?php  echo $pane->endPanel(); ?>
					<?php  echo $pane->endPane(); ?>

				<table width="100%" >


					<tr><td colspan="2" height="10"></td></tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" class="button"  name="save_app" value="<?php echo JText::_('JS_SAVE_RESUME'); ?>" />

				</td>
					</tr>
				</table>
			<?php 
				if(isset($this->resume)) {
					if (($this->resume->create_date=='0000-00-00 00:00:00') || ($this->resume->create_date==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->resume->create_date;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="create_date" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="id" value="<?php if (isset($this->resume)) echo $this->resume->id; ?>" />
			<input type="hidden" name="layout" value="empview" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="saveresume" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />

<script language=Javascript>
function dochange(curscr, myname, nextname, src, val){
	document.getElementById(curscr).innerHTML="Loading ...";
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
        	document.getElementById(curscr).innerHTML=xhr.responseText; //retuen value
			cleanFields(curscr);
      }
    }

	xhr.open("GET","index2.php?option=com_jsjobs&task=listempaddressdata&name="+curscr+"&myname="+myname+"&nextname="+nextname+"&data="+src+"&val="+val,true);
	xhr.send(null);
}

function cleanFields(curscr) {
	
	switch(curscr){
		case "address_state":
			countyhtml = "<input class='inputbox' type='text' name='address_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='address_city' size='40' maxlength='100'  />";
			document.getElementById('address_county').innerHTML=countyhtml; //retuen value
			document.getElementById('address_city').innerHTML=cityhtml; //retuen value
			break;
		case "address_county":
			cityhtml = "<input class='inputbox' type='text' name='address_city' size='40' maxlength='100'  />";
			document.getElementById('address_city').innerHTML=cityhtml; //retuen value
			break;
		case "address1_state":
			document.getElementById('address1_county').innerHTML = "<input class='inputbox' type='text' name='address1_county' size='40' maxlength='100'  />";
			document.getElementById('address1_city').innerHTML = "<input class='inputbox' type='text' name='address1_city' size='40' maxlength='100'  />";
			break;
		case "address1_county":
			document.getElementById('address1_city').innerHTML = "<input class='inputbox' type='text' name='address1_city' size='40' maxlength='100'  />";
			break;
		case "address2_state":
			document.getElementById('address2_county').innerHTML = "<input class='inputbox' type='text' name='address2_county' size='40' maxlength='100'  />";
			document.getElementById('address2_city').innerHTML = "<input class='inputbox' type='text' name='address2_city' size='40' maxlength='100'  />";
			break;
		case "address2_county":
			document.getElementById('address2_city').innerHTML = "<input class='inputbox' type='text' name='address2_city' size='40' maxlength='100'  />";
			break;
		case "institute_state":
			countyhtml = "<input class='inputbox' type='text' name='institute_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute_city' size='40' maxlength='100'  />";
			document.getElementById('institute_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute_county":
			cityhtml = "<input class='inputbox' type='text' name='institute_city' size='40' maxlength='100'  />";
			document.getElementById('institute_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute1_state":
			countyhtml = "<input class='inputbox' type='text' name='institute1_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute1_city' size='40' maxlength='100'  />";
			document.getElementById('institute1_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute1_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute1_county":
			cityhtml = "<input class='inputbox' type='text' name='institute1_city' size='40' maxlength='100'  />";
			document.getElementById('institute1_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute2_state":
			countyhtml = "<input class='inputbox' type='text' name='institute2_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute2_city' size='40' maxlength='100'  />";
			document.getElementById('institute2_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute2_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute2_county":
			cityhtml = "<input class='inputbox' type='text' name='institute2_city' size='40' maxlength='100'  />";
			document.getElementById('institute2_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute3_state":
			countyhtml = "<input class='inputbox' type='text' name='institute3_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute3_city' size='40' maxlength='100'  />";
			document.getElementById('institute3_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute3_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute3_county":
			cityhtml = "<input class='inputbox' type='text' name='institute3_city' size='40' maxlength='100'  />";
			document.getElementById('institute3_city').innerHTML=cityhtml; //retuen value
			break;
		case "employer_state":
			document.getElementById('employer_county').innerHTML = "<input class='inputbox' type='text' name='employer_county' size='40' maxlength='100'  />";
			document.getElementById('employer_city').innerHTML = "<input class='inputbox' type='text' name='employer_city' size='40' maxlength='100'  />";
			break;
		case "employer_county":
			document.getElementById('employer_city').innerHTML = "<input class='inputbox' type='text' name='employer_city' size='40' maxlength='100'  />";
			break;
		case "employer1_state":
			document.getElementById('employer1_county').innerHTML = "<input class='inputbox' type='text' name='employer1_county' size='40' maxlength='100'  />";
			document.getElementById('employer1_city').innerHTML = "<input class='inputbox' type='text' name='employer1_city' size='40' maxlength='100'  />";
			break;
		case "employer1_county":
			document.getElementById('employer1_city').innerHTML = "<input class='inputbox' type='text' name='employer1_city' size='40' maxlength='100'  />";
			break;
		case "employer2_state":
			document.getElementById('employer2_county').innerHTML = "<input class='inputbox' type='text' name='employer2_county' size='40' maxlength='100'  />";
			document.getElementById('employer2_city').innerHTML = "<input class='inputbox' type='text' name='employer2_city' size='40' maxlength='100'  />";
			break;
		case "employer2_county":
			document.getElementById('employer2_city').innerHTML = "<input class='inputbox' type='text' name='employer2_city' size='40' maxlength='100'  />";
			break;
		case "employer3_state":
			document.getElementById('employer3_county').innerHTML = "<input class='inputbox' type='text' name='employer3_county' size='40' maxlength='100'  />";
			document.getElementById('employer3_city').innerHTML = "<input class='inputbox' type='text' name='employer3_city' size='40' maxlength='100'  />";
			break;
		case "employer3_county":
			document.getElementById('employer3_city').innerHTML = "<input class='inputbox' type='text' name='employer3_city' size='40' maxlength='100'  />";
			break;
		case "reference_state":
			document.getElementById('reference_county').innerHTML = "<input class='inputbox' type='text' name='reference_county' size='40' maxlength='100'  />";
			document.getElementById('reference_city').innerHTML = "<input class='inputbox' type='text' name='reference_city' size='40' maxlength='100'  />";
			break;
		case "reference_county":
			document.getElementById('reference_city').innerHTML = "<input class='inputbox' type='text' name='reference_city' size='40' maxlength='100'  />";
			break;
		case "reference1_state":
			document.getElementById('reference1_county').innerHTML = "<input class='inputbox' type='text' name='reference1_county' size='40' maxlength='100'  />";
			document.getElementById('reference1_city').innerHTML = "<input class='inputbox' type='text' name='reference1_city' size='40' maxlength='100'  />";
			break;
		case "reference1_county":
			document.getElementById('reference1_city').innerHTML = "<input class='inputbox' type='text' name='reference1_city' size='40' maxlength='100'  />";
			break;
		case "reference2_state":
			document.getElementById('reference2_county').innerHTML = "<input class='inputbox' type='text' name='reference2_county' size='40' maxlength='100'  />";
			document.getElementById('reference2_city').innerHTML = "<input class='inputbox' type='text' name='reference2_city' size='40' maxlength='100'  />";
			break;
		case "reference2_county":
			document.getElementById('reference2_city').innerHTML = "<input class='inputbox' type='text' name='reference2_city' size='40' maxlength='100'  />";
			break;
		case "reference3_state":
			document.getElementById('reference3_county').innerHTML = "<input class='inputbox' type='text' name='reference3_county' size='40' maxlength='100'  />";
			document.getElementById('reference3_city').innerHTML = "<input class='inputbox' type='text' name='reference3_city' size='40' maxlength='100'  />";
			break;
		case "reference3_county":
			document.getElementById('reference3_city').innerHTML = "<input class='inputbox' type='text' name='reference3_city' size='40' maxlength='100'  />";
			break;
	}
}
//window.onLoad=dochange('country', -1);         // value in first dropdown


</script>
		
		
		
		</form>
		
<?php
} else{ // can not add new resume
	echo "<strong><font color='red'>".JText::_('JS_RESUME_LIMIT_EXCEED')." <a href='#'>".JText::_('JS_RESUME_LIMIT_EXCEED_ADMIN')."</a></font></strong>";
}
}
}//ol

?>	
</div><!-- Fin form-jobseeker -->
<div width="100%">
<?php 
if($this->config['fr_cr_txsh']) {
	echo 
	'<table width="100%" style="table-layout:fixed;">
		<tr><td height="15"></td></tr>
		<tr><td style="vertical-align:top;" align="center">'.$this->config['fr_cr_txa'].$this->config['fr_cr_txb'].'</td></tr>
	</table>';
}	
?>
</div>
