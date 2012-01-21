<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$validPassword = JText::sprintf( JText::_( 'VALID_AZ09', true ), JText::_( 'Password', true ), 4 );
?>
<script language="javascript" type="text/javascript">
function submitbutton() {	
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	
	//hide all the error messsage span 1st
	joms.jQuery('#name').removeClass('invalid');
	joms.jQuery('#jspassword').removeClass('invalid');
	joms.jQuery('#jspassword2').removeClass('invalid');
	joms.jQuery('#jsemail').removeClass('invalid');
	
	joms.jQuery('#errnamemsg').hide();
	joms.jQuery('#errnamemsg').html('&nbsp');	

	joms.jQuery('#errpasswordmsg').hide();
	joms.jQuery('#errpasswordmsg').html('&nbsp');
	
	joms.jQuery('#errjsemailmsg').hide();
	joms.jQuery('#errjsemailmsg').html('&nbsp');
	
	joms.jQuery('#password').val(joms.jQuery('#jspassword').val());
	joms.jQuery('#password2').val(joms.jQuery('#jspassword2').val());
	
	// do field validation
	var isValid	= true;
	
	if (joms.jQuery('#name').val() == "") {
		isValid = false;
		joms.jQuery('#errnamemsg').html('<?php echo addslashes(JText::_( 'Please enter your name', true ));?>');
		joms.jQuery('#errnamemsg').show();
		joms.jQuery('#name').addClass('invalid');
	}
	
	if(joms.jQuery('#jsemail').val() !=  joms.jQuery('#email').val())
	{
		regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
	   	isValid = regex.test(joms.jQuery('#jsemail').val());
	   	
		var fieldname = joms.jQuery('#jsemail').attr('name');;			       
		if(isValid == false){
			cvalidate.setMessage(fieldname, '', 'CC INVALID EMAIL');
			joms.jQuery('#jsemail').addClass('invalid');
		}	   	
   	}
	
	if(joms.jQuery('#password').val().length > 0 || joms.jQuery('#password2').val().length > 0) {
		//check the password only when the password is not empty!
		if(joms.jQuery('#password').val().length < 6 ){
			isValid = false;
			joms.jQuery('#jspassword').addClass('invalid');
			alert('<?php echo addslashes(JText::_( 'CC PASSWORD TOO SHORT' ));?>');		
		} else if (((joms.jQuery('#password').val() != "") || (joms.jQuery('#password2').val() != "")) && (joms.jQuery('#password').val() != joms.jQuery('#password2').val())){
			isValid = false;			
			joms.jQuery('#jspassword').addClass('invalid');
			joms.jQuery('#jspassword2').addClass('invalid');
			var err_msg = "<?php echo addslashes(JText::_( 'CC PASSWORD NOT SAME' )); ?>";
			alert(err_msg);
		} else if (r.exec(joms.jQuery('#password').val())) {
			isValid = false;		
			joms.jQuery('#errpasswordmsg').html('<?php echo $validPassword; ?>');
			joms.jQuery('#errpasswordmsg').show();
			
			joms.jQuery('#jspassword').addClass('invalid');
		}
	}
		
	if(isValid) {
		//replace the email value.
		joms.jQuery('#email').val(joms.jQuery('#jsemail').val());
		joms.jQuery('#jomsForm').submit();
	}
}
</script>

<div id="profile-edit-details">
<div class="ctitle">
	<h2><?php echo JText::_('CC YOUR DETAILS');?></h2>
</div>

<form name="jsforms-profile-editdetails" id="jomsForm" action="" method="POST">
<?php echo $beforeFormDisplay;?>
<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
<tbody>
	
	<!-- username -->
	<tr>
	    <td class="key"><label class="label" for="username"><?php echo JText::_( 'User Name' ); ?></label></td>
	    <td class="value">
			<div class="inputbox halfwidth"><?php echo $this->escape($user->get('username')); ?></div>
	    </td>
	</tr>
	<!-- fullname -->
	<tr>
	    <td class="key"><label class="label" for="name"><?php echo JText::_( 'Your Name' ); ?></label></td>
	    <td class="value">
			<input class="inputbox halfwidth" type="text" id="name" name="name" value="<?php echo $this->escape($user->get('name'));?>" />
	    </td>
	</tr>
	<!-- email -->
	<tr>
	    <td class="key"><label class="label" for="jsemail"><?php echo JText::_( 'email' ); ?></label></td>
	    <td class="value">
			<input type="text" class="inputbox halfwidth" id="jsemail" name="jsemail" value="<?php echo $this->escape( $user->get('email') ); ?>" />
			<input type="hidden" id="email" name="email" value="<?php echo $user->get('email'); ?>" />
		    <input type="hidden" id="emailpass" name="emailpass" id="emailpass" value="<?php echo $this->escape( $user->get('email') ); ?>"/>
		    <span id="errjsemailmsg" style="display:none;">&nbsp;</span>
	    </td>
	</tr>
	<?php if ( !$associated ) : ?>
	<?php     if ( $user->get('password') ) : ?>
	<!-- password -->
	<tr>
	    <td class="key"><label class="label" for="jspassword"><?php echo JText::_( 'Password' ); ?></label></td>
	    <td class="value">
			<input id="jspassword" name="jspassword" class="inputbox halfwidth" type="password" value="" />
			<span id="errjspasswordmsg" style="display: none;"> </span>
	    </td>
	</tr>
	<!-- 2nd password -->
	<tr>
	    <td class="key"><label class="label" for="jspassword2"><?php echo JText::_( 'Verify Password' ); ?></label></td>
	    <td class="value">
			<input id="jspassword2" name="jspassword2" class="inputbox halfwidth" type="password" value="" />
			<span id="errjspassword2msg" style="display:none;"> </span>
			<div style="clear:both;"></div>
			<span id="errpasswordmsg" style="display:none;">&nbsp;</span>
	    </td>
	</tr>	
	<?php     endif; ?>
	<?php endif; ?>
</tbody>
</table>
<?php if(isset($params)) :  echo $params->render( 'params' ); endif; ?>
<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
<tbody>

	<!-- DST -->
	<tr>
	    <td class="key">
			<label class="jomTips label" title="<?php echo JText::_( 'CC DST TIME OFFSET' );?>::<?php echo JText::_('CC DAYLIGHT SAVING OFFSET TOOLTIP');?>" for="daylightsavingoffset">
				<?php echo JText::_( 'CC DAYLIGHT SAVING OFFSET' ); ?>
			</label>
		</td>
	    <td class="value">
			<?php echo $offsetList; ?>
	    </td>
	</tr>
	<!-- group buttons -->
	<tr>
		<td class="key"></td>
		<td class="value">			
			<input type="hidden" name="id" value="<?php echo $user->get('id');?>" />
			<input type="hidden" name="gid" value="<?php echo $user->get('gid');?>" />
			<input type="hidden" name="option" value="com_community" />
			<input type="hidden" name="view" value="profile" />
			<input type="hidden" name="task" value="save" />
			<input type="hidden" id="password" name="password" />
			<input type="hidden" id="password2" name="password2" />		
			<?php echo JHTML::_( 'form.token' ); ?>	
			<input type="submit" name="frmSubmit" onclick="submitbutton(); return false;" class="button" value="<?php echo JText::_('CC BUTTON SAVE'); ?>" />
		</td>
	</tr>
</table>
<?php echo $afterFormDisplay;?>
<?php
if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') )
{
?>
	<div class="ctitle"><h2><?php echo JText::_('CC ASSOCIATE FACEBOOK LOGIN' );?></h2></div>
<?php
	if( $isAdmin )
	{
?>
	<div class="small facebook"><?php echo JText::_('CC ADMIN NOT ALLOWED TO ASSOCIATE FACEBOOK');?></div>
<?php
	}
	else
	{
		if( $associated )
		{
		?>
			<div class="small facebook"><?php echo JText::_('CC ACCOUNT ALREADY MERGED');?></div>
		<?php
		}
		else
		{
			echo $fbHtml;
		}
	}
}
?>
</div>
</form>