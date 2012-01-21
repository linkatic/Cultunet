<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/application/tmpl/formjob.php
 ^ 
 * Description: Form template for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTMLBehavior::formvalidation(); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/jsjobs01.css" />

<script language="javascript">
function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'save'){
		returnvalue = validate_form(document.adminForm);
	}else returnvalue  = true;
	
	if (returnvalue == true){
		try {
			  document.adminForm.onsubmit();
	        }
		catch(e){}
		document.adminForm.submit();
	}
}

function validate_form(f)
{
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php echo JUtility::getToken(); ?>';//send token
        }
        else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>

<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%" ><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top" align="left">


			<form action="index.php" method="POST" name="adminForm" id="adminForm">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				<?php
				if($this->msg != ''){
				?>
				 <tr>
			        <td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
			      </tr>
				  <tr><td colspan="2" height="10"></td></tr>	
				<?php
				}
				?>
		<?php
		$trclass = array("row0", "row1");
		$isodd = 1;
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobtitle":  $isodd = 1 - $isodd; ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td width="20%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_JOB_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
			          <td width="60%"><input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->title; ?>" />
			        </td>
			      </tr>
				<?php break;
				case "company":  $isodd = 1 - $isodd; ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="companymsg" for="company"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<font color="red">*</font></td>
			        <td><?php echo $this->lists['companies']; ?></td>
			      </tr>
				<?php break;
				case "jobcategory":  $isodd = 1 - $isodd; ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORY'); ?></td>
			        <td><?php echo $this->lists['jobcategory']; ?></td>
			      </tr>
				<?php break;
				case "jobtype":  $isodd = 1 - $isodd; ?>
				  <?php if ( $field->published == 1 ) { ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
			        <td><?php echo $this->lists['jobtype']; ?></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "jobstatus":  $isodd = 1 - $isodd; ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
			        <td><?php echo $this->lists['jobstatus']; ?></td>
			      </tr>
				<?php break;
				case "jobshift":  $isodd = 1 - $isodd; ?>
				  <?php if ( $field->published == 1 ) { ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
			        <td><?php echo $this->lists['shift']; ?></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "jobsalaryrange":  $isodd = 1 - $isodd; ?>
				  <?php if ( $field->published == 1 ) { ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
			        <td><?php echo $this->lists['jobsalaryrange'] . JText::_('JS_PERMONTH'); ?></td>
			      </tr>
				  <?php $isodd = 1 - $isodd; ?>
			      <tr  class="<?php echo $trclass[$isodd]; ?>" >
			        <td valign="top" align="right"><?php echo JText::_('JS_HIDE_SALARY'); ?></td>
			        <td><input type='checkbox' name='hidesalaryrange' value='1' <?php if(isset($this->job)) echo ($this->job->applyinfo == "1") ? "checked='checked'" : ""; ?> /></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "heighesteducation":  $isodd = 1 - $isodd; ?>
				    <?php if ( $field->published == 1 ) { ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
					<td><?php echo $this->lists['heighesteducation']; ?></td>
					</tr>
					<?php } ?>
				<?php break;
				case "noofjobs":  $isodd = 1 - $isodd; ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('JS_NOOFJOBS'); ?></label></td>
					<td><input class="inputbox  required validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if(isset($this->job)) echo $this->job->noofjobs; ?>" />
					</td>
					</tr>
				<?php break;
				case "experience":  $isodd = 1 - $isodd; ?>
				    <?php if ( $field->published == 1 ) { ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="experiencesmsg" for="experience"><?php echo JText::_('JS_EXPERIENCE'); ?></label></td>
			        <td><input class="inputbox validate-numeric" type="text" name="experience" id="experience" size="10" maxlength="2" value="<?php if(isset($this->job)) echo $this->job->experience; ?>" />
					<?php echo JText::_('JS_YEARS'); ?>
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "duration":  $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_DURATION'); ?></label></td>
			        <td><input class="inputbox" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if(isset($this->job)) echo $this->job->duration; ?>" />
			        <?php echo JText::_('JS_DURATION_DESC'); ?>
					</td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "startpublishing":  $isodd = 1 - $isodd; ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('JS_START_PUBLISHING'); ?></label></td>
			        <td><input class="inputbox required" type="text" name="startpublishing" id="startpublishing" onBlur="CheckDate('startpublishing');" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo $this->job->startpublishing; ?>" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('startpublishing','%Y-%m-%d');" onBlur="CheckDate('startpublishing');" />
					</td>
			      </tr>
				<?php break;
				case "stoppublishing":  $isodd = 1 - $isodd; ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></label></td>
			        <td><input class="inputbox required" type="text" name="stoppublishing" id="stoppublishing" onBlur="CheckDate('stoppublishing');" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo $this->job->stoppublishing; ?>" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishing','%Y-%m-%d');" onBlur="CheckDate('stoppublishing');" />
					</td>
			      </tr>

				<?php break;
				case "description":  $isodd = 1 - $isodd; ?>
					<?php //echo 'job editor'.$this->config['job_editor'];
					 if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
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
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
								<td><textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea></td>
							</tr>
					<?php } ?>
				<?php break;
				case "qualifications":  $isodd = 1 - $isodd; ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('JS_QUALIFICATIONS'); ?></strong></label></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
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
							<?php } ?>
					<?php }else{ ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><?php echo JText::_('JS_QUALIFICATIONS');?></td>
								<td><textarea class="inputbox" name="qualifications" id="qualifications" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->qualifications; ?></textarea></td>
							</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "prefferdskills":  $isodd = 1 - $isodd; ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></strong></label></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
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
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="prefferdskillsmsg" for="prefferdskills"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></label></td>
								<td>
									<textarea class="inputbox" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "country":  $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="countrymsg" for="country"><?php echo JText::_('JS_COUNTRY'); ?></label></td>
				        <td id="country">
						      <?php echo $this->lists['country']; ?>
				        </td>
				      </tr>
				<?php break;
				case "state":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="statemsg" for="state"><?php echo JText::_('JS_STATE'); ?></label></td>
				        <td id="state">
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
				case "county":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="countymsg" for="county"><?php echo JText::_('JS_COUNTY'); ?></label></td>
				        <td id="county"><?php 
							if ((isset($this->lists['county'])) && ($this->lists['county']!='')){
								echo $this->lists['county']; 
							} else{ ?>
								<input class="inputbox" type="text" name="county" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->county; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "city":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
				        <td id="city"><?php 
							if((isset($this->lists['city'])) && ($this->lists['city']!='')){
								echo $this->lists['city']; 
							} else{ ?>
								<input class="inputbox" type="text" name="city" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->city; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "sendemail":  $isodd = 1 - $isodd; ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
				        <td valign="top" align="right"><?php echo JText::_('JS_SEND_EMAIL'); ?></td>
				        <td><input type='checkbox' name='sendemail' value='1' <?php if(isset($this->job)) echo ($this->job->sendemail == "1") ? "checked='checked'" : ""; ?> />
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
						 $isodd = 1 - $isodd; 
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr class='".$trclass[$isodd]."'><td valign='top' align='right'>";
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
										echo '<input class="inputbox required" type="text" name="userfields_'.$i.'" id="userfields_'.$i.'" onBlur="CheckDate(\'userfields_'.$i.'\');" readonly class="Shadow Bold" size="10" value="'. $fvalue .'" />
										<input type="reset" class="button" value="..." onclick="return showCalendar(\'userfields_'.$i.'\',\'%Y-%m-%d\');" onBlur="CheckDate(\'userfields_'.$i.'\');" />';
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.'>'.$fvalue.'</textarea>';
										break;	
									case 'checkbox':
										echo '<input type="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
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
	  
			<?php if(isset($this->job)) {  $isodd = 1 - $isodd; ?>
			  <tr class="<?php echo $trclass[$isodd]; ?>">
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
			  </tr>
			<?php }else { ?>
				<input type="hidden" name="status" value="1" />
			<?php } ?>	
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE_JOB'); ?>" />
		</td>
	</tr>

			    </table>
			<?php 	
				if(isset($this->job)) {
					$uid = $this->job->uid;
					if (($this->job->created=='0000-00-00 00:00:00') || ($this->job->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->job->created;
				}else{
					$uid = $this->uid;
					$curdate = date('Y-m-d H:i:s');
				}	
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="layout" value="viewjob" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savejob" />
			
		  <input type="hidden" name="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />

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
				countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('county').innerHTML=countyhtml; //retuen value
				document.getElementById('city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('city').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}
			//window.onLoad=dochange('country', -1);         // value in first dropdown
			</script>

				
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