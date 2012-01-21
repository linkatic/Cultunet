<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/formjob.php
 ^ 
 * Description: template for job posting form
 ^ 
 * History:		NONE
 ^ 
 */
 

defined('_JEXEC') or die('Restricted access');

 global $mainframe;

jimport('joomla.html.pane');

$editor = & JFactory :: getEditor();
JHTML :: _('behavior.calendar');

$document = &JFactory::getDocument();
?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
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
		var returnvalue = true;
		
/*		if (f.id.value == ''){
			if (CheckDate()== false) returnvalue = false; 
		}
*/		if (document.formvalidator.isValid(f)) {
                f.check.value='<?php echo JUtility::getToken(); ?>';//send token
        } else {
                alert('Some values are not acceptable.  Please retry.');
				returnvalue = false;
        }
		if (returnvalue == true)
			return true;
		else{	
			return false;
		}	
}

function CheckDate() {
//alert('date');
	f = document.adminForm;
	var returnvalue = true;
	var today=new Date()
	if ((today.getMonth()+1) < 10)
		var tomonth = "0"+(today.getMonth()+1);
	else
		var tomonth = (today.getMonth()+1);
	
	if ((today.getDate()) < 10)
		var day = "0"+(today.getDate());
	else
		var day = (today.getDate());

		var todate = (today.getYear()+1900)+"-"+tomonth+"-"+day;
	
//alert(todate);
		if(f.startpublishing.value != ""){
			if (todate > f.startpublishing.value ){
				alert('Please enter a valid start publishing date');
				f.startpublishing.value="";
				returnvalue = false;
			}
		}		
		if(f.startpublishing.value >= f.stoppublishing.value){
			alert("Please enter a valid stop publishing date");
			f.stoppublishing.value="";
			returnvalue = false;
		}
		return returnvalue;
	
}
</script>

<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid=3" class="active"><?php echo JText::_('JS_ADD_OFERTA'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=3"><?php echo JText::_('JS_ADD_MIS_OFERTAS'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=3"><?php echo JText::_('JS_ADD_CVS_RECIBIDOS'); ?></a></li>
		</ul>
	</div>
</div>

<?php 

/*
 * 
 * Ocultamos la barra de administración específica del componente
 * 
 */
 
 /*
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php if (isset($this->job)&& ($this->job->id == '')){	?>
			<?php echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_NEW_JOB_INFO'); ?>
		<?php }else{	?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_JOBS'); ?></a> > <?php echo JText::_('JS_EDIT_JOB_INFO'); ?>
		<?php }	?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			if (sizeof($this->employerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->employerlinks as $lnk)	{ ?>
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
		<?php echo JText::_('JS_JOB_INFO'); ?>
	</td></tr>
	<tr><td height="10"></td></tr>
</table>

*/
?>
<?php
if ($this->userrole->rolefor == 1) { // employer
if ($this->canaddnewjob == 1) { // add new job, in edit case always 1
?>
<div id="form-jobs">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobtitle": ?>
				  <tr>
			        <td width="20%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_JOB_TITLE'); ?></label>&nbsp;<span class="requerido">*</span></td>
			          <td width="60%"><input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->title; ?>" />
			        </td>
			      </tr>
				<?php break;
				case "company": ?>
				  <tr>
			        <td valign="top" align="right"><label id="companymsg" for="company"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<span class="requerido">*</span></td>
			        <td><select class="inputbox required" id="companyid" name="companyid">
			        	<option value="<?php echo $this->empresa[id]; ?>"><?php echo $this->empresa[name]; ?></option> 
			        </select></td>
			      </tr>
				<?php break;
				case "jobcategory": ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
			        <td><?php echo $this->lists['jobcategory']; ?></td>
			      </tr>
				<?php break;
				case "jobtype": ?>
			      <?php if ( $field->published == 1 ) { ?>
				  <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
			        <td><?php echo $this->lists['jobtype']; ?></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "jobstatus": ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
			        <td><?php echo $this->lists['jobstatus']; ?></td>
			      </tr>
				<?php break;
				case "jobshift": ?>
			      <?php if ( $field->published == 1 ) { ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
			        <td><?php echo $this->lists['shift']; ?></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "jobsalaryrange": ?>
			      <?php if ( $field->published == 1 ) { ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
			        <td><?php echo $this->lists['jobsalaryrange'] . JText::_('JS_PERMONTH'); ?></td>
			      </tr>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_HIDE_SALARY'); ?></td>
			        <td><input type='checkbox' class="checkbox"  name='hidesalaryrange' value='1' <?php if(isset($this->job)) echo ($this->job->hidesalaryrange == "1") ? "checked='checked'" : ""; ?> /></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "heighesteducation": ?>
			      <?php if ( $field->published == 1 ) { ?>
					<tr>
					<td valign="top" align="right"><?php echo JText::_('JS_HEIGHTEST_EDUCATION'); ?></td>
					<td><?php echo $this->lists['heighesteducation']; ?></td>
					</tr>
					<?php } ?>
				<?php break;
				case "noofjobs": ?>
					<tr>
					<td valign="top" align="right"><label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('JS_NOOFJOBS'); ?></label>&nbsp;<span class="requerido">*</span></td>
					<td><input class="inputbox  required validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if(isset($this->job)) echo $this->job->noofjobs; ?>" />
					</td>
					</tr>
				<?php break;
				case "experience": ?>
			      <?php if ( $field->published == 1 ) { ?>
			       <tr>
			        <td valign="top" align="right"><label id="experiencesmsg" for="experience"><?php echo JText::_('JS_EXPERIENCE'); ?></label></td>
			        <td><input class="inputbox validate-numeric" type="text" name="experience" id="experience" size="10" maxlength="2" value="<?php if(isset($this->job)) echo $this->job->experience; ?>" />
					<?php echo JText::_('JS_YEARS'); ?>
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "duration": ?>
			      <?php if ( $field->published == 1 ) { ?>
			       <tr>
			        <td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_DURATION'); ?></label></td>
			        <td><input class="inputbox" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if(isset($this->job)) echo $this->job->duration; ?>" />
			        <?php echo JText::_('JS_DURATION_DESC'); ?>
					</td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "startpublishing": ?>
			       <tr>
			        <td valign="top" align="right"><label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('JS_START_PUBLISHING'); ?></label>&nbsp;<span class="requerido">*</span></td>
			        <td><input class="inputbox required" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo $this->job->startpublishing; ?>" />
			        <img class="calendar" src="/templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('job_startpublishing','%Y-%m-%d');" id="job_startpublishing">
					</td>
			      </tr>
				<?php break;
				case "stoppublishing": ?>
			       <tr>
			        <td valign="top" align="right"><label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></label>&nbsp;<span class="requerido">*</span></td>
			        <td><input class="inputbox required" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo $this->job->stoppublishing; ?>" />
			        <img class="calendar" src="/templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('job_stoppublishing','%Y-%m-%d');" id="job_stoppublishing">
					</td>
			      </tr>

				<?php break;
				case "description": ?>
					<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<span class="requerido">*</span></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('description', $this->job->description, '550', '300', '60', '20', false);
									else
										echo $editor->display('description', '', '550', '300', '60', '20', false);

								?>	
									<!--<textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea>-->
								</td>
							</tr>
					<?php }else{ ?>
							<tr>
								<td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<span class="requerido">*</span></td>
								<td><textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea></td>
							</tr>
					<?php } ?>
				<?php break;
				case "qualifications": ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('JS_QUALIFICATIONS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('qualifications', $this->job->qualifications, '550', '300', '60', '20', false);
									else
										echo $editor->display('qualifications', '', '550', '300', '60', '20', false);

								?>	
								</td>
							</tr>
					<?php }else{ ?>
					      <?php if ( $field->published == 1 ) { ?>
							<tr>
								<td valign="top" align="right"><?php echo JText::_('JS_QUALIFICATIONS');?></td>
								<td><textarea class="inputbox" name="qualifications" id="qualifications" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->qualifications; ?></textarea></td>
							</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "prefferdskills": ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('prefferdskills', $this->job->prefferdskills, '550', '300', '60', '20', false);
									else
										echo $editor->display('prefferdskills', '', '550', '300', '60', '20', false);
								?>	
								</td>
							</tr>
							<?php } ?>

					<?php }else{ ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr>
								<td valign="top" align="right"><label id="prefferdskillsmsg" for="prefferdskills"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></label></td>
								<td>
									<textarea class="inputbox" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
					<?php } ?>
					<?php break;
				case "country": ?>
				      <tr>
				        <td align="right"><label id="countrymsg" for="country"><?php echo JText::_('JS_COUNTRY'); ?></label></td>
				        <td id="job_country">
						      <?php //echo $this->lists['country']; //Falla (atención lo ponemos a mano) ?>
							  <select onchange="dochange('state', this.value)" style="width: 160px;" class="inputbox" name="country">
<option value="">==== todo ====</option>
<option value="AF">Afghanistan</option> 
<option value="AI">Anguilla</option> 
<option value="AM">Armenia</option> 
<option value="AW">Aruba</option> 
<option value="AU">Australia</option> 
<option value="AT">Austria</option> 
<option value="AZ">Azerbaijan</option> 
<option value="BS">Bahamas</option> 
<option value="BH">Bahrain</option> 
<option value="BD">Bangladesh</option> 
<option value="BB">Barbados</option> 
<option value="BY">Belarus</option> 
<option value="BE">Belgium</option> 
<option value="BZ">Belize</option> 
<option value="BJ">Benin</option> 
<option value="BM">Bermuda</option> 
<option value="BT">Bhutan</option> 
<option value="BO">Bolivia</option> 
<option value="BV">Bouvet Islands</option> 
<option value="BR">Brazil</option> 
<option value="IO">British Indian Ocean Territory</option> 
<option value="VI">British Virgin Islands</option> 
<option value="BN">Brunei</option> 
<option value="BG">Bulgaria</option> 
<option value="BF">Burkina Faso</option> 
<option value="BI">Burundi</option> 
<option value="KH">Cambodia</option> 
<option value="CM">Cameroon</option> 
<option value="CA">Canada</option> 
<option value="CV">Cape Verde</option> 
<option value="KY">Cayman Islands</option> 
<option value="CF">Central African Republic</option> 
<option value="TD">Chad</option> 
<option value="CL">Chile</option> 
<option value="CN">China</option> 
<option value="CO">Colombia</option> 
<option value="KM">Comoros</option> 
<option value="CG">Congo</option> 
<option value="CR">Costa Rica</option> 
<option value="CI">Cote DIvorie</option> 
<option value="HR">Croatia</option> 
<option value="CY">Cyprus</option> 
<option value="CZ">Czech Republic</option> 
<option value="DK">Denmark</option> 
<option value="DJ">Djibouti</option> 
<option value="DM">Dominica</option> 
<option value="DO">Dominican Republic</option> 
<option value="EG">Egypt</option> 
<option value="SV">El Salvador</option> 
<option value="EC">Equador</option> 
<option value="GQ">Equatorial Guinea</option> 
<option value="ER">Eritrea</option> 
<option value="EE">Estonia</option> 
<option value="ET">Ethiopia</option> 
<option value="FK">Falkland Islands</option> 
<option value="FO">Faroe Islands</option> 
<option value="FM">Federated States of Micronesia</option> 
<option value="FJ">Fiji</option> 
<option value="FI">Finland</option> 
<option value="FR">France</option> 
<option value="GF">French Guiana</option> 
<option value="PF">French Polynesia</option> 
<option value="GA">Gabon</option> 
<option value="GM">Gambia</option> 
<option value="GE">Georgia</option> 
<option value="DE">Germany</option> 
<option value="GH">Ghana</option> 
<option value="GI">Gibraltar</option> 
<option value="GR">Greece</option> 
<option value="GL">Greenland</option> 
<option value="GD">Grenada</option> 
<option value="GP">Guadeloupe</option> 
<option value="GU">Guam</option> 
<option value="GT">Guatemala</option> 
<option value="GN">Guinea</option> 
<option value="GW">Guinea-Bissau</option> 
<option value="GY">Guyana</option> 
<option value="HT">Haiti</option> 
<option value="HN">Honduras</option> 
<option value="HK">Hong Kong</option> 
<option value="HU">Hungary</option> 
<option value="IS">Iceland</option> 
<option value="IN">India</option> 
<option value="ID">Indonesia</option> 
<option value="IE">Ireland</option> 
<option value="IL">Israel</option> 
<option value="IT">Italy</option> 
<option value="JM">Jamaica</option> 
<option value="JP">Japan</option> 
<option value="JO">Jordan</option> 
<option value="KZ">Kazakhstan</option> 
<option value="KE">Kenya</option> 
<option value="KI">Kiribati</option> 
<option value="KW">Kuwait</option> 
<option value="KG">Kyrgyzstan</option> 
<option value="LA">Laos</option> 
<option value="LV">Latvia</option> 
<option value="LB">Lebanon</option> 
<option value="LS">Lesotho</option> 
<option value="LR">Liberia</option> 
<option value="LI">Liechtenstein</option> 
<option value="LT">Lithuania</option> 
<option value="LU">Luxembourg</option> 
<option value="MO">Macau</option> 
<option value="MG">Madagascar</option> 
<option value="MW">Malawi</option> 
<option value="MY">Malaysia</option> 
<option value="MV">Maldives</option> 
<option value="ML">Mali</option> 
<option value="MT">Malta</option> 
<option value="MH">Marshall Islands</option> 
<option value="MQ">Martinique</option> 
<option value="MR">Mauritania</option> 
<option value="YT">Mayotte</option> 
<option value="FX">Metropolitan France</option> 
<option value="MX">Mexico</option> 
<option value="MD">Moldova</option> 
<option value="MN">Mongolia</option> 
<option value="MA">Morocco</option> 
<option value="MZ">Mozambique</option> 
<option value="NA">Namibia</option> 
<option value="NR">Nauru</option> 
<option value="NP">Nepal</option> 
<option value="AN">Neterlands Antilles</option> 
<option value="NL">Netherlands</option> 
<option value="NC">New Caledonia</option> 
<option value="NZ">New Zealand</option> 
<option value="NI">Nicaragua</option> 
<option value="NE">Niger</option> 
<option value="NG">Nigeria</option> 
<option value="MP">Northern Mariana Islands</option> 
<option value="NO">Norway</option> 
<option value="OM">Oman</option> 
<option value="PK">Pakistan</option> 
<option value="PW">Palau</option> 
<option value="PA">Panama</option> 
<option value="PG">Papua New Guinea</option> 
<option value="PY">Paraguay</option> 
<option value="PE">Peru</option> 
<option value="PH">Philippines</option> 
<option value="PN">Pitcairn</option> 
<option value="PL">Poland</option> 
<option value="PT">Portugal</option> 
<option value="PR">Puerto Rico</option> 
<option value="QA">Qatar</option> 
<option value="KR">Republic of Korea</option> 
<option value="MK">Republic of Macedonia</option> 
<option value="RE">Reunion</option> 
<option value="RO">Romania</option> 
<option value="RU">Russia</option> 
<option value="ST">Sao Tome and Principe</option> 
<option value="SA">Saudi Arabia</option> 
<option value="SN">Senegal</option> 
<option value="SC">Seychelles</option> 
<option value="SL">Sierra Leone</option> 
<option value="SG">Singapore</option> 
<option value="SK">Slovakia</option> 
<option value="SI">Slovenia</option> 
<option value="SB">Solomon Islands</option> 
<option value="SO">Somalia</option> 
<option value="ZA">South Africa</option> 
<option value="ES">Spain</option> 
<option value="LK">Sri Lanka</option> 
<option value="SH">St. Helena</option> 
<option value="KN">St. Kitts and Nevis</option> 
<option value="LC">St. Lucia</option> 
<option value="VC">St. Vincent and the Grenadines</option> 
<option value="SD">Sudan</option> 
<option value="SR">Suriname</option> 
<option value="SJ">Svalbard and Jan Mayen Islands</option> 
<option value="SZ">Swaziland</option> 
<option value="SE">Sweden</option> 
<option value="CH">Switzerland</option> 
<option value="SY">Syria</option> 
<option value="TW">Taiwan</option> 
<option value="TJ">Tajikistan</option> 
<option value="TZ">Tanzania</option> 
<option value="TH">Thailand</option> 
<option value="TG">Togo</option> 
<option value="TO">Tonga</option> 
<option value="TT">Trinidad and Tobago</option> 
<option value="TR">Turkey</option> 
<option value="TM">Turkmenistan</option> 
<option value="TC">Turks and Caicos Islands</option> 
<option value="TV">Tuvalu</option> 
<option value="UG">Uganda</option> 
<option value="UA">Ukraine</option> 
<option value="AE">United Arab Emirates</option> 
<option value="GB">United Kingdom</option> 
<option value="US">United States</option> 
<option value="UY">Uruguay</option> 
<option value="UZ">Uzbekistan</option> 
<option value="VU">Vanuatu</option> 
<option value="VA">Vatican City</option> 
<option value="VE">Venezuela</option> 
<option value="VN">Vietnam</option> 
<option value="EH">Western Sahara</option> 
<option value="YE">Yemen</option> 
<option value="YU">Yugoslavia</option> 
<option value="ZR">Zaire</option> 
<option value="ZM">Zambia</option> 
<option value="ZW">Zimbabwe</option> 
</select>
				        </td>
				      </tr>
				<?php break;
				case "state": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="statemsg" for="state"><?php echo JText::_('JS_STATE'); ?></label></td>
				        <td id="job_state">
						<?php
							if ((isset($this->lists['state'])) && ($this->lists['state']!='')){
								echo $this->lists['state']; 
							} else{ ?>
								<input class="inputbox" type="text" name="state" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->state; ?>" />
							<?php } ?>
						</td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "county": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="countymsg" for="county"><?php echo JText::_('JS_COUNTY'); ?></label></td>
				        <td id="job_county"><?php 
							if ((isset($this->lists['county'])) && ($this->lists['county']!='')){
								echo $this->lists['county']; 
							} else{ ?>
								<input class="inputbox" type="text" name="county" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->county; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "city": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
				        <td id="job_city"><?php 
							if((isset($this->lists['city'])) && ($this->lists['city']!='')){
								echo $this->lists['city']; 
							} else{ ?>
								<input class="inputbox" type="text" name="city" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->city; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
			<?php break;
				case "sendemail": ?>
					<tr>
				        <td valign="top" align="right"><?php echo JText::_('JS_SEND_EMAIL'); ?></td>
				        <td><input type='checkbox' class="checkbox"  name='sendemail' value='1' <?php if(isset($this->job)) echo ($this->job->sendemail == 1) ? "checked" : ""; ?> />
						&nbsp;&nbsp;<?php echo JText::_('JS_JOB_APPLY_EMAIL_NOTIFICATION'); ?>
						</td>
				      </tr>
				      <tr>
				        <td colspan="2" height="5"></td>
				      <tr>
				<?php break;
				  
				default:
					//echo '<br> default uf '.$filed->field;
					if ( $field->published == 1 ) { 
					
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for=$userfield->name>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									$cssclass = "class ='inputbox required' ";
								}else{
									echo $userfield->title; $cssclass = "class='inputbox' ";
								}
								echo "</td><td>"	;
									
								$readonly = $userfield->readonly ? ' readonly="readonly"' : '';
		   						$maxlength = $userfield->maxlength ? 'maxlength="'.$userfield->maxlength.'"' : '';
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								echo '<input type="hidden" id="userfields_'.$i.'_id" name="userfields_'.$i.'_id"  value="'.$userfield->id.'"  />';
								echo '<input type="hidden" id="userdata_'.$i.'_id" name="userdata_'.$i.'_id"  value="'.$userdataid.'"  />';
								switch( $userfield->type ) {
									case 'text':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
									    echo '<input id="userfields_'.$i.'" type="text" size="10" readonly="" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />
									    <img id="userfields_'.$i.'" class="calendar" onclick="return showCalendar(\'userfields_'.$i.'\',\'%Y-%m-%d\');" alt="calendar" src="/templates/system/images/calendar.png">';
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.'>'.$fvalue.'</textarea>';
										break;	
									case 'checkbox':
										echo '<input type="checkbox" class="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;	
									case 'select':
										$htm = '<select name="userfields_'.$i.'" id="userfields_'.$i.'" >';
										if (isset ($ufield[2])){
											foreach($ufield[2] as $opt){
												if ($opt->id == $fvalue)
													$htm .= '<option value="'.$opt->id.'" selected="yes">'. $opt->fieldtitle .' </option>';
												else
													$htm .= '<option value="'.$opt->id.'">'. $opt->fieldtitle .' </option>';
											}
										}
										$htm .= '</select>';	
										echo $htm;
								}
								echo '</td></tr>';
							}
						}	 
					}	

			}
			
		} 
		echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
		?>
	<tr><td colspan="2" height="10"></td></tr>	  
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVEJOB'); ?>" />
		</td>
	</tr>
    </table>
			<?php 
				if(isset($this->job)) {
					if (($this->job->created=='0000-00-00 00:00:00') || ($this->job->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->job->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="layout" value="viewjob" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savejob" />
			<input type="hidden" name="check" value="" />
			
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  <input type="hidden" name="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />
		  
		  
<script language=Javascript>
function dochange(src, val){
	var pagesrc = 'job_'+src;
	document.getElementById(pagesrc).innerHTML="Loading ...";
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
        	document.getElementById(pagesrc).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
				countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('job_county').innerHTML=countyhtml; //retuen value
				document.getElementById('job_city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('job_city').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}
</script>
			  

		</form>
<?php 
} else{ // can not add new job
	echo "<strong><font color='red'>".JText::_('JS_JOB_LIMIT_EXCEED')." <a href='#'>".JText::_('JS_JOB_LIMIT_EXCEED_ADMIN')."</a></font></strong>";
}
} else{ // not allowed job posting
	echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
}
}//ol
?>
</div><!-- Fin form-jobs -->
<div width="100%">
<?php include_once('components/com_jsjobs/views/fr_jscr.php'); ?>
</div>
