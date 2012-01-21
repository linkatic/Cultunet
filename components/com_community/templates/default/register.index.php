<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	applications	An array of applications object
 * @param	pagination		JPagination object 
 */
defined('_JEXEC') or die();
?>

<form action="<?php echo CRoute::getURI(); ?>" method="post" id="jomsForm" name="jomsForm" class="community-form-validate">
<div class="ctitle">
	<h2><?php echo JText::_( 'CC_REG_TITLE_USER_INFO' ); ?></h2>
</div>
<table class="ccontentTable paramlist" cellspacing="1" cellpadding="0">
    <tbody>
    	<tr>
			<td class="paramlist_key">
				<label id="usertypemsg" for="usertype" class="label">*<?php echo JText::_( 'CC USERTYPE' ); ?></label>												
			</td>
			<td class="paramlist_value">
				<?php
				 $selected = array();
				 $data['html_field']['usertype']=='Particular' ? $selected[0] = " selected" : $selected[0] = "";
				 $data['html_field']['usertype']=='Empresa' ? $selected[1] = " selected" : $selected[1] = "";
				?>
				<select name="usertype" id="usertype">
					<option<?php echo $selected[0]; ?> onclick="document.getElementById('person').style.display = '';document.getElementById('company').style.display = 'none'" value="Particular"><?php echo JText::_( 'CC PARTICULAR' ); ?></option>
					<option<?php echo $selected[1]; ?> onclick="document.getElementById('person').style.display = 'none';document.getElementById('company').style.display = ''" value="Empresa"><?php echo JText::_( 'CC EMPRESA' ).' '.JText::_( 'CC O' ).' '.JText::_( 'CC INSTITUCION' ); ?></option>
				</select>
				<span id="errusertypemsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>
	
		
		<tr>
			<td class="paramlist_key">
				<label id="jsnamemsg" for="jsname" class="label">*
					<span id="person" style="display:inline-block;"><?php echo JText::_( 'CC NAME' ); ?></span>
					<span id="company" style="display:none"><?php echo JText::_( 'CC COMPANY NAME' ); ?></span>
				</label>												
			</td>
			<td class="paramlist_value">
			    <input type="text" name="jsname" id="jsname" size="40" value="<?php echo $data['html_field']['jsname']; ?>" class="inputbox required validate-name" maxlength="50" />
				<span id="errjsnamemsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>

		<tr>
			<td class="paramlist_key">
				<label id="jsemailmsg" for="jsemail" class="label">*<?php echo JText::_( 'CC EMAIL' ); ?></label>
			</td>
			<td class="paramlist_value">
			    <input type="text" id="jsemail" name="jsemail" size="40" value="<?php echo $data['html_field']['jsemail']; ?>" class="inputbox required validate-email" maxlength="100" />
			    <input type="hidden" name="emailpass" id="emailpass" value="N"/>
			    <span id="errjsemailmsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>

		<tr>
			<td class="paramlist_key">
				<label id="jsusernamemsg" for="jsusername" class="label">*<?php echo JText::_( 'CC USERNAME' ); ?></label>
			</td>
			<td class="paramlist_value">
			    <input type="text" id="jsusername" name="jsusername" size="40" value="<?php echo $data['html_field']['jsusername']; ?>" 
				       class="inputbox required validate-username" 
					   maxlength="25" />
			    <input type="hidden" name="usernamepass" id="usernamepass" value="N"/>							   
				<span id="errjsusernamemsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>

		<tr>
			<td class="paramlist_key">
				<label id="pwmsg" for="jspassword" class="label">*<?php echo JText::_( 'CC PASSWORD' ); ?></label>
			</td>
			<td class="paramlist_value">
			    <input class="inputbox required validate-password" type="password" id="jspassword" name="jspassword" size="40" value="" />
			    <span id="errjspasswordmsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>
		<tr>
			<td class="paramlist_key">
				<label id="pw2msg" for="jspassword2" class="label">*<?php echo JText::_( 'CC VERIFY PASSWORD' ); ?></label>
			</td>
			<td class="paramlist_value">
			    <input class="inputbox required validate-passverify" type="password" id="jspassword2" name="jspassword2" size="40" value="" />
			    <span id="errjspassword2msg" style="display:none;">&nbsp;</span>
			</td>
		</tr>
		<tr>
		    <td class="paramlist_key">&nbsp;</td>
			<td class="paramlist_value">						
				<?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?>
			</td>
		</tr>				
    </tbody>
</table>    
<?php
if( $config->get('enableterms') )
{
?>
<div class="ctitle">
<h2><?php echo JText::_( 'CC_REG_TITLE_TNC' ); ?></h2>
</div>

<table class="ccontentTable paramlist" cellspacing="1" cellpadding="0">
  <tbody>
	<tr>
		<td class="paramlist_key" id="tncmsg" for="tnc">
		    <input type="checkbox" name="tnc" id="tnc" value="Y"
			       class="inputbox required"/>
		
		</td>
		<td class="paramlist_value">
			<?php echo JText::_('CC I HAVE READ').' <a href="javascript:void(0);" onclick="joms.registrations.windowTitle=\'' . JText::_('CC TERMS AND CONDITION') . '\';joms.registrations.showTermsWindow();">'.JText::_('CC TERMS AND CONDITION').'</a>.';?>
		</td>
	</tr>
</tbody>
</table>			
<?php
}
?>
<?php 
if(!empty($recaptchaHTML))
{
?>
<table cellspacing="0" cellpadding="0">
  <tbody>
	<tr>
		<td class="paramlist_key">&nbsp;</td>
		<td>
			<?php echo $recaptchaHTML;?>
		</td>
	</tr>
</tbody>
</table>
<?php
}
?>

<table class="ccontentTable paramlist" cellspacing="1" cellpadding="0">
  <tbody>
	<tr>
		<td class="paramlist_key">&nbsp;</td>
		<td class="paramlist_value">
			<div id="cwin-wait" style="display:none;"></div>
			<input class="button validateSubmit" type="submit" id="btnSubmit" value="<?php echo JText::_('CC NEXT'); ?>" name="submit">
		</td>
	</tr>
</tbody>
</table>
<input type="hidden" name="task" value="register_save" />
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
<input type="hidden" id="authenticate" name="authenticate" value="0" />
<input type="hidden" id="authkey" name="authkey" value="" />
</form>
<script type="text/javascript">  
	cvalidate.init();
	cvalidate.noticeTitle	= '<?php echo addslashes(JText::_('CC NOTICE') );?>';
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
	cvalidate.setSystemText('JOINTEXT','<?php echo addslashes(JText::_("CC AND")); ?>');

joms.jQuery( '#jomsForm' ).submit( function(){
    joms.jQuery('#btnSubmit').hide();
	joms.jQuery('#cwin-wait').show();
	
	if(joms.jQuery('#authenticate').val() != '1')
	{
		joms.registrations.authenticate();
		return false;
	}
});

</script>
