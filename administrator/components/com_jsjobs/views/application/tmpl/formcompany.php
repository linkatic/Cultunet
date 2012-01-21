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


<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onSubmit="return myValidate(this);" >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->msg != ''){ ?>
		 <tr>
			<td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>	
		<?php	}	?>
		<?php
		$trclass = array("row0", "row1");
		$isodd = 1;
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobcategory": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td valign="top" align="right"><label id="jobcategorymsg" for="jobcategory"><?php echo JText::_('JS_CATEGORY'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->lists['category']; ?></td>
				      </tr>
				<?php break;
				case "name": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td width="20%" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_COMPANYNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				          <td width="60%"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->company)) echo $this->company->name; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "url": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="urlmsg" for="url"><?php echo JText::_('JS_COMPANYURL'); ?></label></td>
						<td><input class="inputbox" type="text" name="url" size="40" maxlength="100" value="<?php if(isset($this->company)) echo trim ($this->company->url); ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactname": $isodd = 1 - $isodd;  ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="contactnamemsg" for="contactname"><?php echo JText::_('JS_CONTACTNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required" type="text" name="contactname" id="contactname" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactname; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "contactphone":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="contactphonemsg" for="contactphone"><?php echo JText::_('JS_CONTACTPHONE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="contactphone" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactphone; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactfax":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="companyfaxmsg" for="companyfax"><?php echo JText::_('JS_CONTACTFAX'); ?></label></td>
				        <td><input class="inputbox" type="text" name="companyfax" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->companyfax; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactemail":  $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="contactemailmsg" for="contactemail"><?php echo JText::_('JS_CONTACTEMAIL');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactemail; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "since": $isodd = 1 - $isodd;  ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="sincemsg" for="since"><?php echo JText::_('JS_SINCE'); ?></label></td>
			        <td><input class="inputbox" type="text" name="since" id="since" readonly size="10" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->since; ?>" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('since','%Y-%m-%d');" onBlur="CheckDate('since');" /></td>
			      </tr>
				<?php break;
				case "companysize":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="companysize" for="companysize"><?php echo JText::_('JS_COMPANY_SIZE'); ?></label></td>
			        <td><input class="inputbox" type="text" name="companysize" id="companysize" size="20" maxlength="20" value="<?php if(isset($this->company)) echo $this->company->companysize; ?>" />
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "income":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				       <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td valign="top" align="right"><label id="incomemsg" for="income"><?php echo JText::_('JS_INCOME'); ?></label></td>
				        <td><input class="inputbox" type="text" name="income" id="income" size="20" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->income; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "description":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if ( $this->config['comp_editor'] == '1' ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->company))
										echo $editor->display('description', $this->company->description, '550', '300', '60', '20', false);
									else
										echo $editor->display('description', '', '550', '300', '60', '20', false);
								?>	
								</td>
							</tr>
						<?php } else {?>
				       <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td>
							<textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->company)) echo $this->company->description; ?></textarea>
						</td>
				      </tr>
						<?php } ?>
					  <?php } ?>
				<?php break;
				case "country":  $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="countrymsg" for="country"><?php echo JText::_('JS_COUNTRY'); ?></label>&nbsp;<font color="red">*</font></td>
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
								<input class="inputbox" type="text" name="state" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->state; ?>" />
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
								<input class="inputbox" type="text" name="county" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->county; ?>" />
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
								<input class="inputbox" type="text" name="city" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->city; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "zipcode":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="zipcodemsg" for="zipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->zipcode; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "address1":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="address1msg" for="address1"><?php echo JText::_('JS_ADDRESS1'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address1" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address1; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "address2":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="address2msg" for="address2"><?php echo JText::_('JS_ADDRESS2'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address2" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address2; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "logo":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->logofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletelogo' value='1'><?php echo JText::_('JS_DELETE_LOGO_FILE') .'['.$this->company->logofilename.']'; ?></td></tr>
						<?php } ?>				
						<tr class="<?php echo $trclass[$isodd]; ?>">
							<td align="right" ><label id="logomsg" for="logo">	<?php echo JText::_('JS_COMPANY_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="logo" id="logo" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "smalllogo":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->smalllogofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletesmalllogo' value='1'><?php echo JText::_('JS_DELETE_SMALL_LOGO_FILE') .'['.$this->company->smalllogofilename.']'; ?></td></tr>
						<?php } ?>				
						<tr class="<?php echo $trclass[$isodd]; ?>">
							<td align="right" >	<label id="smalllogomsg" for="smalllogo"><?php echo JText::_('JS_COMPANY_SMALL_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="smalllogo" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "aboutcompany":  $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->aboutcompanyfilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deleteaboutcompany' value='1'><?php echo JText::_('JS_DELETE_ABOUT_COMPANY_FILE') .'['.$this->company->aboutcompanyfilename.']'; ?></td></tr>
						<?php } ?>				
						<tr class="<?php echo $trclass[$isodd]; ?>">
							<td align="right" >	<label id="aboutcompanymsg" for="aboutcompany"><?php echo JText::_('JS_ABOUT_COMPANY'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="aboutcompany" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
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
									case 'emailaddress':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
									    ?><input type="reset" class="button" value="..." onclick="return showCalendar('userfields_<?php echo $i; ?>','%Y-%m-%d');" /><?php
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
										break;	
									case 'editortext':
										$editor =& JFactory::getEditor();
										if(isset($this->company))
											echo $editor->display("userfields_$i", $fvalue, '550', '300', '60', '20', false);
										else
											echo $editor->display("userfields_$i", '', '550', '300', '60', '20', false);
									
								}
								echo '</td></tr>';
							}
						}
						
					}
					
			}
			
		} 
		echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
		?>
			<?php if(isset($this->company)) {  $isodd = 1 - $isodd; ?>
			  <tr class="<?php echo $trclass[$isodd]; ?>">
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
			  </tr>
			<?php }else { ?>
				<input type="hidden" name="status" value="1" />
			<?php } ?>	
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_COMPANY'); ?>" />
		</td>
	</tr>
    </table>


	<?php 
				if(isset($this->company)) {
					$uid = $this->company->uid;
					if (($this->company->created=='0000-00-00 00:00:00') || ($this->company->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->company->created;
				}else{
					$uid = $this->uid;
					$curdate = date('Y-m-d H:i:s');
				}
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savecompany" />
			
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  <input type="hidden" name="id" value="<?php if(isset($this->company)) echo $this->company->id; ?>" />
		  
		  
			<script language=Javascript>
			function dochange(src, val){
				document.getElementById(src).innerHTML="Loading ...";
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