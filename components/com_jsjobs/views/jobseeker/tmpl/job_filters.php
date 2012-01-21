<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/filters.php
 ^ 
 * Description: template view for list jobs of a category
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.model');

 global $mainframe;

jimport('joomla.filter.output');
$filter_address_fields_width = $this->config['filter_address_fields_width'];
?>

<?php if ($this->config['filter'] == 1) { ?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbfilters">
		<tr><td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
				<tr><td colspan="6" height="10"></td></tr>
				<?php if ($this->config['filter_address'] == 1) { ?>
					<tr>
								<td width="2"></td>
						        <td id="filter_country" width="25%">
								      <?php echo $this->filterlists['country']; ?>
						        </td>
						        <td id="filter_state" width="40">
								<?php
									if ((isset($this->filterlists['state'])) && ($this->filterlists['state']!='')){
										echo $this->filterlists['state']; 
									} else{ ?>
										<input class="inputbox" type="text" name="txtfilter_state" style="width:<?php echo $this->config['filter_address_fields_width']; ?>px;" size="25" maxlength="50" value="<?php if(isset($this->filtervalues)) echo $this->filtervalues['state']; ?>" />
									<?php } ?>
								</td>
						        <td id="filter_county" width="40"><?php 
									if ((isset($this->filterlists['county'])) && ($this->filterlists['county']!='')){
										echo $this->filterlists['county']; 
									} else{ ?>
										<input class="inputbox" type="text" name="txtfilter_county" style="width:<?php echo $this->config['filter_address_fields_width']; ?>px;" size="25" maxlength="50" value="<?php if(isset($this->filtervalues)) echo $this->filtervalues['county']; ?>" />
									<?php } ?>
						        </td>
						        <td id="filter_city" width="40"><?php 
									if((isset($this->filterlists['city'])) && ($this->filterlists['city']!='')){
										echo $this->filterlists['city']; 
									} else{ ?>
										<input class="inputbox" type="text" name="txtfilter_city" style="width:<?php echo $this->config['filter_address_fields_width']; ?>px;" size="25" maxlength="50" value="<?php if(isset($this->filtervalues)) echo $this->filtervalues['city']; ?>" />
									<?php } ?>
						        </td>
								<td width="2"></td>
						
					</tr>
					<tr><td colspan="6" height="2"></td></tr>
				<?php } ?>
				<tr>
					<td ></td>
					<td> <?php if ($this->config['filter_category'] == 1)  echo $this->filterlists['jobcategory']; ?></td>
					<td> <?php if ($this->config['filter_jobtype'] == 1) echo $this->filterlists['jobtype']; ?></td>
					<td> <?php if ($this->config['filter_heighesteducation'] == 1) echo $this->filterlists['heighestfinisheducation']; ?></td>
					<td> <?php if ($this->config['filter_salaryrange'] == 1) echo $this->filterlists['jobsalaryrange']; ?></td>
					<td ></td>
				<tr>
				<tr><td colspan="6" height="4"></td></tr>
				<tr>
					<td></td>
					<td colspan="2" align="left">
						<?php if (isset($this->uid) && (isset($this->userrole->rolefor))){ ?>
							<?php if (isset($this->filterid)) { ?>
								<button class="button" onclick="deleteSearch();"><?php echo JText::_( 'JS_THIS_FILTER_DELETE' ); ?></button>
							<?php } else { ?>
								<button class="button" onclick="saveSearch();"><?php echo JText::_( 'JS_THIS_FILTER_SAVE' ); ?></button>
							<?php } ?>	
						<?php } ?>	
					</td>
					<td colspan="2" align="center">
						<button class="button" onclick="this.form.submit();"><?php echo JText::_( 'JS_GO' ); ?></button>
						&nbsp;<button class="button" onclick="myReset();"><?php echo JText::_( 'JS_RESET' ); ?></button>
					</td>
					<td></td>
				<tr><td colspan="6" height="10"></td></tr>
			</table>
		</td></tr>
		</table>	
		<input type="hidden" name="id" value="<?php echo $this->filterid; ?>">
		<input type="hidden" name="formaction" value="">
		

<script language=Javascript>
function saveSearch(){
	document.adminForm.formaction.value = document.adminForm.action;
// alert(document.adminForm.action);
	document.adminForm.action = 'index.php?option=com_jsjobs&c=jsjobs&task=savefilter';
 document.adminForm.submit();
 
 }

function deleteSearch(){
	document.adminForm.formaction.value = document.adminForm.action;
// alert(document.adminForm.action);
	document.adminForm.action = 'index.php?option=com_jsjobs&c=jsjobs&task=deletefilter';
 document.adminForm.submit();
 }
function myReset(){
// alert('reset');
 if (testIsValidObject(document.adminForm.cmbfilter_country)) document.adminForm.cmbfilter_country.value = '';
 if (testIsValidObject(document.adminForm.cmbfilter_state)) document.adminForm.cmbfilter_state.value = '';
 if (testIsValidObject(document.adminForm.cmbfilter_county)) document.adminForm.cmbfilter_county.value = '';
 if (testIsValidObject(document.adminForm.cmbfilter_city)) document.adminForm.cmbfilter_city.value = '';
 if (testIsValidObject(document.adminForm.txtfilter_country)) document.adminForm.txtfilter_country.value = '';
 if (testIsValidObject(document.adminForm.txtfilter_state)) document.adminForm.txtfilter_state.value = '';
 if (testIsValidObject(document.adminForm.txtfilter_county)) document.adminForm.txtfilter_county.value = '';
 if (testIsValidObject(document.adminForm.txtfilter_city)) document.adminForm.txtfilter_city.value = '';
 
 document.adminForm.filter_jobcategory.value = '';
 document.adminForm.filter_jobtype.value = '';
 document.adminForm.filter_jobsalaryrange.value = '';
 document.adminForm.filter_heighesteducation.value = '';
 document.adminForm.submit();
 
//alert('reset 2');
 }

function testIsValidObject(objToTest) {
		if (null == objToTest) {
			return false;
		}
		if ("undefined" == typeof(objToTest) ) {
			return false;
		}
		return true;

}

//function dochange(curscr, myname, nextname, src, val){
function filter_dochange(src, val){
	document.getElementById(src).innerHTML="Loading ...";
	var xhr; 
	<?php echo 'var field_width = '.$filter_address_fields_width; ?>

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

			if(src =='filter_state'){
				countyhtml = "<input class='inputbox' type='text' name='txtfilter_county' style='width:"+field_width+"px'  size='25' maxlength='50'  />";
				cityhtml = "<input class='inputbox' type='text' name='txtfilter_city' style='width:"+field_width+"px' size='25' maxlength='50'  />";
				document.getElementById('filter_county').innerHTML=countyhtml; //retuen value
				document.getElementById('filter_city').innerHTML=cityhtml; //retuen value
			}else if(src =='filter_county'){
				cityhtml = "<input class='inputbox' type='text' name='txtfilter_city' style='width:"+field_width+"px' size='25' maxlength='50'  />";
				document.getElementById('filter_city').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
//	xhr.open("GET","index2.php?option=com_jsjobs&task=listempaddressdata&name="+curscr+"&myname="+myname+"&nextname="+nextname+"&data="+src+"&val="+val,true);
	xhr.open("GET","index2.php?option=com_jsjobs&task=listfilteraddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}

//window.onLoad=dochange('country', -1);         // value in first dropdown
</script>

<?php } ?>