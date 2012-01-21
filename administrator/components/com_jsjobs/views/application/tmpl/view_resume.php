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
 global $mainframe;
JRequest :: setVar('layout', 'view_resume');
$_SESSION['cur_layout']='view_resume';

 $isodd = 0; 
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
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />

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
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="oi" value="<?php echo $this->jobid; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>

<?php
$printform = 1;
	$printform = 1;
	if ((isset($this->resume)) &&($this->resume->id != 0)) { // not new form
		if ($this->resume->status == 1) { // Employment Application is actve
			$printform = 1;
		}	
	}

if ($printform == 1) {

?>

			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
			<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
				<?php echo JText::_('JS_VIEW_RESUME'); ?>
			</td></tr>
			<tr><td height="3"></td></tr>
			<?php if (($_GET['vm'] == '2') || ($_GET['vm'] == '3')) { ?>
			<?php  if (isset($_GET["jobid"])) $jobid = $_GET["jobid"]; else $jobid ='';
			$clink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletters&vts=1&clu='.$this->resume->uid.'&rd='.$this->resume->id.'&jobid='.$jobid.'&Itemid='.$this->Itemid; ?>
			<tr><td align="right"><a href="<?php echo $clink; ?>" class="pageLink"><?php echo JText::_('JS_VIEW_COVER_LETTERS') ?></a></td></tr>
			<?php } ?>
			<tr><td>
			<?php  echo $pane->startPane('myPane');  ?>
			<?php
				$trclass = array("odd", "even");
				$i = 0;
				foreach($this->fieldsordering as $field){ 
					//echo '<br> uf'.$field->field;
					//$sec = substr($row->field, 0,8); 
					//if ($sec == 'section_') {
					switch ($field->field) {
						case "section_personal": ?>
								<?php echo $pane->startPanel(JText::_('JS_PERSONAL'),'personal'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_PERSONAL_INFORMATION'); ?>
										</td>
									</tr>
						
						<?php break;
						case "applicationtitle": $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle">
									<?php echo JText::_('JS_APPLICATION_TITLE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->application_title;?>
								</td>
							</tr>
						<?php break;
						case "firstname":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_FIRST_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->first_name;?>
								</td>
							</tr>
						<?php break;
						case "middlename":  $isodd = 1 - $isodd; ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_MIDDLE_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->middle_name;?>
								</td>
							</tr>
						<?php } ?>
						<?php break;
						case "lastname":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_LAST_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->last_name;?>
								</td>
							</tr>
						<?php break;
						case "emailaddress":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_EMAIL_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->email_address;?>
								</td>						
							</tr>
						<?php break;
						case "homephone":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_HOME_PHONE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->home_phone;?>
								</td>						
							</tr>
						<?php break;
						case "workphone":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_WORK_PHONE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->work_phone;?>
								</td>						
							</tr>
						<?php break;
						case "cell":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CELL'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->cell;?>
								</td>						
							</tr>
						<?php break;
						case "gender":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_GENDER');  ?>:
								</td>
								<td><?php if(isset($this->resume)) echo ($this->resume->gender == 1) ? JText::_('JS_MALE') : JText::_('JS_FEMALE'); ?>	</td>
							</tr>
						<?php break;
						case "Iamavailable":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
								<td><?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? JText::_('JS_A_YES') : JText::_('JS_A_NO'); ?> </td>
							</tr>
					<?php break;
					case "photo":  $isodd = 1 - $isodd; ?>
								<?php if (isset($this->resume)) 
											if($this->resume->photo != '') {?>
												<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td valign="top" align="right"><?php echo JText::_('JS_PHOTO'); ?></td><td style="max-width:150px;max-height:150px;overflow:hidden;text-overflow:ellipsis">
													<img src="components/com_jsjobs/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
												</td></tr>
								<?php } ?>				
						<?php break;
						case "nationality":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_NATIONALITY_COUNTRY');  ?>:
								</td>
								<td><?php echo $this->resume->nationalitycountry; ?></td>
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
						case "category":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CATEGORY');  ?>:
								</td>
								<td>
									<?php
										echo $this->resume->categorytitle;
									?>
								</td>
							</tr>
						<?php break;
						case "salary":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="100"align="right" class="textfieldtitle">
									<?php echo JText::_('JS_DESIRED_SALARY'); ?>:
								</td>
								<td colspan="2" >
									<?php echo $this->config['currency'].$this->resume->rangestart.' - '.$this->config['currency'].$this->resume->rangeend .' '. JText::_('JS_PERMONTH'); ?>
								</td>
							</tr>
						<?php break;
						case "jobtype":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_WORK_PREFERENCE'); ?>:	</td>
								<td colspan="2" valign="top" >
									<?php echo $this->resume->jobtypetitle; ?>
								</td>
							</tr>
						<?php break;
						case "heighesteducation":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?>:</td>
								<td colspan="2" valign="top" >
									<?php
										//echo $this->resumelists['work_preferences'];
										echo $this->resume->heighesteducationtitle; 
									?>
								</td>
							</tr>
						<?php break;
						case "totalexperience":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_TOTAL_EXPERIENCE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->total_experience;?>
								</td>						
							</tr>
						<?php break;
						case "startdate":  $isodd = 1 - $isodd; ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_CAN_START'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->date_start;?>
								</td>						
							</tr>
					<?php break;
						case "section_addresses": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_ADDRESSES'),'addresses'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_ADDRESS'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "address_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address_state">
									<?php if ($this->resume->address_state2 !='') echo $this->resume->address_state2; else echo $this->resume->address_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address_county">
									<?php if ($this->resume->address_county2 !='') echo $this->resume->address_county2; else echo $this->resume->address_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address_city">
									<?php if ($this->resume->address_city2 !='') echo $this->resume->address_city2; else echo $this->resume->address_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address1":  ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS1'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address1_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address1_state">
									<?php if ($this->resume->address1_state2 !='') echo $this->resume->address1_state2; else echo $this->resume->address1_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address1_county">
									<?php if ($this->resume->address1_county2 !='') echo $this->resume->address1_county2; else echo $this->resume->address1_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address1_city">
									<?php if ($this->resume->address1_city2 !='') echo $this->resume->address1_city2; else echo $this->resume->address1_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address1_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address1;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address2":  ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS2'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address2_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address2_state">
									<?php if ($this->resume->address2_state2 !='') echo $this->resume->address2_state2; else echo $this->resume->address2_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTY'); ?>:
								</td>
								<td id="address2_county">
									<?php if ($this->resume->address2_county2 !='') echo $this->resume->address2_county2; else echo $this->resume->address2_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address2_city">
									<?php if ($this->resume->address2_city2 !='') echo $this->resume->address2_city2; else echo $this->resume->address2_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address2_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address2;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_education": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_EDUCATIONS'),'education'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_HIGH_SCHOOL'); ?>
												</td>
											</tr>
										<?php } ?>

										
					<?php break;
						case "institute_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute_country">
									<?php echo $this->resume2->institute_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute_state">
										<?php if ($this->resume2->institute_state2 !='') echo $this->resume2->institute_state2; else echo $this->resume->institute_state;?>
								</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute_county">
									<?php if ($this->resume2->institute_county2 !='') echo $this->resume2->institute_county2; else echo $this->resume->institute_county;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute_city">
											<?php if ($this->resume2->institute_city2 !='') echo $this->resume2->institute_city2; else echo $this->resume->institute_city;?>
										</td>
								</tr>
							<?php } ?>

					<?php break;
						case "section_sub_institute1":  ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_UNIVERSITY'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute1_country">
									<?php echo $this->resume2->institute1_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute1_state">
										<?php if ($this->resume2->institute1_state2 !='') echo $this->resume2->institute1_state2; else echo $this->resume->institute1_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute1_county">
										<?php if ($this->resume2->institute1_county2 !='') echo $this->resume2->institute1_county2; else echo $this->resume->institute1_county;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute1_city">
											<?php if ($this->resume2->institute1_city2 !='') echo $this->resume2->institute1_city2; else echo $this->resume->institute1_city;?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute2": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_GRADE_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute2_country">
									<?php echo $this->resume2->institute2_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute2_state">
										<?php if ($this->resume2->institute2_state2 !='') echo $this->resume2->institute2_state2; else echo $this->resume->institute2_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute2_county">
										<?php if ($this->resume2->institute2_county2 !='') echo $this->resume2->institute2_county2; else echo $this->resume->institute2_county;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute2_city">
									<?php if ($this->resume2->institute2_city2 !='') echo $this->resume2->institute2_city2; else echo $this->resume->institute2_city;?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute3": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_OTHER_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute3_country">
									<?php echo $this->resume->institute3_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute3_state">
									<?php if ($this->resume->institute3_state2 !='') echo $this->resume->institute3_state2; else echo $this->resume->institute3_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
									<td id="institute3_county">
										<?php if ($this->resume->institute3_county2 !='') echo $this->resume->institute3_county2; else echo $this->resume->institute3_county;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute3_city">
											<?php if ($this->resume->institute3_city2 !='') echo $this->resume->institute3_city2; else echo $this->resume->institute3_city;?>
										</td>
								</tr>
							<?php } ?>

							

					<?php break;
						case "section_employer": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_EMPLOYERS'),'employer'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_RECENT_EMPLOYER'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "employer_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer_country">
									<?php echo $this->resume2->employer_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer_state">
										<?php if ($this->resume2->employer_state2 !='') echo $this->resume2->employer_state2; else echo $this->resume->employer_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer_county">
										<?php if ($this->resume2->employer_county2 !='') echo $this->resume2->employer_county; else echo $this->resume->employer_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer_city">
										<?php if ($this->resume2->employer_city2 !='') echo $this->resume2->employer_city2; else echo $this->resume->employer_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_phone;?>
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
						case "employer1_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer1_country">
									<?php echo $this->resume2->employer1_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer1_state">
										<?php if ($this->resume2->employer1_state2 !='') echo $this->resume2->employer1_state2; else echo $this->resume->employer1_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer1_county">
										<?php if ($this->resume2->employer1_county2 !='') echo $this->resume2->employer1_county2; else echo $this->resume->employer1_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer1_city">
										<?php if ($this->resume2->employer1_city2 !='') echo $this->resume2->employer1_city2; else echo $this->resume->employer1_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer1_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_phone;?>
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
						case "employer2_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer2_country">
									<?php echo $this->resume2->employer2_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer2_state">
										<?php if ($this->resume2->employer2_state2 !='') echo $this->resume2->employer2_state2; else echo $this->resume->employer2_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer2_county">
										<?php if ($this->resume2->employer2_county2 !='') echo $this->resume2->employer2_county2; else echo $this->resume->employer2_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer2_city">
										<?php if ($this->resume2->employer2_city2 !='') echo $this->resume2->employer2_city2; else echo $this->resume->employer2_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_address;?>
								</td>
							</tr>
						<?php } ?>	
				<?php break;
						case "employer2_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_phone;?>
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
						case "employer3_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer3_country">
									<?php echo $this->resume2->employer3_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer3_state">
										<?php if ($this->resume2->employer3_state2 !='') echo $this->resume2->employer3_state2; else echo $this->resume->employer3_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:	</td>
								<td id="employer3_county">
										<?php if ($this->resume2->employer3_county2 !='') echo $this->resume2->employer3_county2; else echo $this->resume->employer3_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer3_city">
										<?php if ($this->resume2->employer3_city2 !='') echo $this->resume2->employer3_city2; else echo $this->resume->employer3_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer3_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_phone;?>
								</td>
							</tr>
							<?php } ?>
							

					<?php break;
						case "section_skills": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
							<?php echo $pane->startPanel(JText::_('JS_SKILLS'),'skills'); ?>
								<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_SKILLS'); ?>
										</td>
									</tr>
					<?php break;
						case "driving_license":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="250" align="right" class="textfieldtitle"><?php echo JText::_('JS_HAVE_DRIVING_LICENSE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->driving_license; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_no":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_NO'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->license_no;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_country":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_COUNTRY'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->license_country;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "skills":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SKILLS'); ?>:</td>
								<td>
									<?php if(isset($this->resume)) echo $this->resume->skills; ?>
								</td>
							</tr>
							<?php } ?>
										
										
					<?php break;
						case "section_resumeeditor": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
							<?php echo $pane->startPanel(JText::_('JS_RESUME'),'resume'); ?>
								<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_RESUME'); ?>
										</td>
									</tr>

							
					<?php break;
						case "editor": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr>
									<td colspan="2">
									    <?php
												echo $this->resume->resume;
			                                ?>

									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "fileupload": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<?php if (isset($this->resume)) 
											if($this->resume->filename != '') {?>
										<tr height="21"><td colspan="2"></td></tr>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_RESUME_FILE'); ?>
												</td>
											</tr>
												<?php $link = '../components/com_jsjobs/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename; ?>
												<tr><td colspan="2" align="center"><a class="pageLink" href="<?php echo $link ?>"><?php echo JText::_('JS_DOWNLOAD'); ?></a> </td></tr>
								<?php } ?>				
							<?php } ?>
						
						
					<?php break;
						case "section_references": ?>
							</table>	
							<?php  echo $pane->endPanel(); ?>
								<?php echo $pane->startPanel(JText::_('JS_REFERENCES'),'preferences'); ?>
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
										<tr>
											<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
												<?php echo JText::_('JS_REFERENCE1'); ?>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "reference_name":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_resumeeditor == 1) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_name;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference_country">
								<?php echo $this->resume3->reference_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference_state">
										<?php if ($this->resume3->reference_state2 !='') echo $this->resume3->reference_state2; else echo $this->resume->reference_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference_county">
										<?php if ($this->resume3->reference_county2 !='') echo $this->resume3->reference_county2; else echo $this->resume->reference_county;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference_city">
										<?php if ($this->resume3->reference_city2 !='') echo $this->resume3->reference_city2; else echo $this->resume->reference_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_address;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_phone;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_relation;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference1": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE2'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference1_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference1_country">
								<?php echo $this->resume3->reference1_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference1_state">
										<?php if ($this->resume3->reference1_state2 !='') echo $this->resume3->reference1_state2; else echo $this->resume->reference1_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference1_county">
										<?php if ($this->resume3->reference1_county2 !='') echo $this->resume3->reference1_county2; else echo $this->resume->reference1_county;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference1_city">
										<?php if ($this->resume3->reference1_city2 !='') echo $this->resume3->reference1_city2; else echo $this->resume->reference1_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference2": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE3'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference2_country">
								<?php echo $this->resume3->reference2_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference2_state">
										<?php if ($this->resume3->reference2_state2 !='') echo $this->resume3->reference2_state2; else echo $this->resume->reference2_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference2_county">
										<?php if ($this->resume3->reference2_county2 !='') echo $this->resume3->reference2_county2; else echo $this->resume->reference2_county;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference2_city">
										<?php if ($this->resume3->reference2_city2 !='') echo $this->resume3->reference2_city2; else echo $this->resume->reference2_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference2_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference3": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE4'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference3_country">
								<?php echo $this->resume3->reference3_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference3_state">
										<?php if ($this->resume3->reference3_state2 !='') echo $this->resume3->reference3_state2; else echo $this->resume->reference3_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_county":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTY'); ?>:</td>
								<td id="reference3_county">
										<?php if ($this->resume3->reference3_county2 !='') echo $this->resume3->reference3_county2; else echo $this->resume->reference3_county;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference3_city">
										<?php if ($this->resume3->reference3_city2 !='') echo $this->resume3->reference3_city2; else echo $this->resume->reference3_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference3_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_years;?>
								</td>
							</tr>
						<?php } ?>	
							
							

										
					<?php } ?>	
				<?php } ?>	
							
						</table>	
					<?php  echo $pane->endPanel(); ?>

			</td></tr>
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
<?php
}

?>
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