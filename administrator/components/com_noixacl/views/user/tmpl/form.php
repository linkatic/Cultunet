<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<?php
	$cid = JRequest::getVar( 'cid', array(0) );
	$edit		= JRequest::getVar('edit',true);
	$text = intval($edit) ? JText::_( 'NOIXACL_VIEW_USER_TEXT_EDIT' ) : JText::_( 'NOIXACL_VIEW_USER_TEXT_NEW' );
	$option = JRequest::getCMD( 'option' );
	JToolBarHelper::title( JText::_( 'NOIXACL_VIEW_USER_TITLE_USER' ) . ': <small><small>[ '. $text .' ]</small></small>' , 'user.png' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	if ( $edit ) {
		/**
                 *  for existing items the button is renamed `close`
                 */
		JToolBarHelper::cancel( 'cancel', 'Close' );
	} else {
		JToolBarHelper::cancel();
	}
	JToolBarHelper::help( 'screen.users.edit' );
	$cparams = JComponentHelper::getParams ('com_media');
?>

<?php
	/**
         *  clean item data
         */
	JFilterOutput::objectHTMLSafe( $user, ENT_QUOTES, '' );

	if ($this->user->get('lastvisitDate') == "0000-00-00 00:00:00") {
		$lvisit = JText::_( 'Never' );
	} else {
		$lvisit	= JHTML::_('date', $this->user->get('lastvisitDate'), '%Y-%m-%d %H:%M:%S');
	}
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");

		/**
                 * do field validation
                 */
		if (trim(form.name.value) == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_USER_VALIDATE_NAME', true ); ?>" );
		} else if (form.username.value == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_USER_VALIDATE_LOGIN', true ); ?>" );
		} else if (r.exec(form.username.value) || form.username.value.length < 2) {
			alert( "<?php echo JText::_( 'WARNLOGININVALID', true ); ?>" );
		} else if (trim(form.email.value) == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_USER_VALIDATE_EMAIL', true ); ?>" );
		} else if (form.gid.value == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_USER_VALIDATE_GROUP', true ); ?>" );
		} else if (((trim(form.password.value) != "") || (trim(form.password2.value) != "")) && (form.password.value != form.password2.value)){
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_USER_VALIDATE_PASSWORD_MATCH', true ); ?>" );
		} else if (form.gid.value == "29") {
			alert( "<?php echo JText::_( 'WARNSELECTPF', true ); ?>" );
		} else if (form.gid.value == "30") {
			alert( "<?php echo JText::_( 'WARNSELECTPB', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}

	function gotocontact( id ) {
		var form = document.adminForm;
		form.contact_id.value = id;
		submitform( 'contact' );
	}
	
	function disableCheckMultiGroup(){
		var selected = document.getElementById('gid').options[ document.getElementById('gid').selectedIndex ].value;
		
		for(var n = 0; n < <?php echo count($this->groups); ?> ; n++){
			var elemName = 'cb' + n;
			if( document.getElementById(elemName) ){
				document.getElementById(elemName).disabled = '';
			}
			
		}

		for(var n = 0; n < <?php echo count($this->groups); ?> ; n++){
			var elemName = 'cb' + n;
			if( document.getElementById(elemName) ){
				if( document.getElementById(elemName).value == selected ){
					document.getElementById(elemName).checked = '';
					document.getElementById(elemName).disabled = 'disabled';
				}
			}
			
		}
		

		
	}
</script>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
	<div class="col width-45">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'NOIXACL_VIEW_USER_LEGEND_USER_DATAILS' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="150" class="key">
						<label for="name">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_NAME' ); ?>
						</label>
					</td>
					<td>
						<input type="text" name="name" id="name" class="inputbox" size="40" value="<?php echo $this->user->get('name'); ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="username">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_USERNAME' ); ?>
						</label>
					</td>
					<td>
						<input type="text" name="username" id="username" class="inputbox" size="40" value="<?php echo $this->user->get('username'); ?>" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="email">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_EMAIL' ); ?>
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="email" id="email" size="40" value="<?php echo $this->user->get('email'); ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="password">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_NEW_PASSWORD' ); ?>
						</label>
					</td>
					<td>
						<?php if(!$this->user->get('password')) : ?>
							<input class="inputbox disabled" type="password" name="password" id="password" size="40" value="" disabled="disabled" />
						<?php else : ?>
							<input class="inputbox" type="password" name="password" id="password" size="40" value=""/>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="password2">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_VERIFY_PASSWORD' ); ?>
						</label>
					</td>
					<td>
						<?php if(!$this->user->get('password')) : ?>
							<input class="inputbox disabled" type="password" name="password2" id="password2" size="40" value="" disabled="disabled" />
						<?php else : ?>
							<input class="inputbox" type="password" name="password2" id="password2" size="40" value=""/>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="gid">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_LABEL_GROUP' ); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['gid']; ?>
					</td>
				</tr>
				<?php if ($this->me->authorize( 'com_users', 'block user' )) { ?>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_BLOCK_USER' ); ?>
					</td>
					<td>
						<?php echo $this->lists['block']; ?>
					</td>
				</tr>
				<?php } if ($this->me->authorize( 'com_users', 'email_events' )) { ?>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_RECIVE_SYSTEM_EMAILS' ); ?>
					</td>
					<td>
						<?php echo $this->lists['sendEmail']; ?>
					</td>
				</tr>
				<?php } if( $this->user->get('id') ) { ?>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_REGISTER_DATE' ); ?>
					</td>
					<td>
						<?php echo JHTML::_('date', $this->user->get('registerDate'), '%Y-%m-%d %H:%M:%S');?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_LAST_VISIT_DATE' ); ?>
					</td>
					<td>
						<?php echo $lvisit; ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</fieldset>
	</div>
	<div class="col width-55">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'NOIXACL_VIEW_USER_LEGEND_PARAMS' ); ?></legend>
			<table class="admintable">
				<tr>
					<td>
						<?php
							$params = $this->user->getParameters(true);
							echo $params->render( 'params' );
						?>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'NOIXACL_VIEW_USER_LEGEND_CONTACT_INFORMATION' ); ?></legend>
		<?php if ( !$this->contact ) { ?>
			<table class="admintable">
				<tr>
					<td>
						<br />
						<span class="note">
							<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_NO_CONTACT_DETAILS_USER' ); ?>:
							<br />
							<?php echo JText::_( 'SEECOMPCONTACTFORDETAILS' ); ?>.
						</span>
						<br /><br />
					</td>
				</tr>
			</table>
		<?php } else { ?>
			<table class="admintable">
				<tr>
					<td width="120" class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_NAME' ); ?>
					</td>
					<td>
						<strong>
							<?php echo $this->contact[0]->name;?>
						</strong>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_POSITION' ); ?>
					</td>
					<td >
						<strong>
							<?php echo $this->contact[0]->con_position;?>
						</strong>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_TELEPHONE' ); ?>
					</td>
					<td >
						<strong>
							<?php echo $this->contact[0]->telephone;?>
						</strong>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_FAX' ); ?>
					</td>
					<td >
						<strong>
							<?php echo $this->contact[0]->fax;?>
						</strong>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_MISC' ); ?>
					</td>
					<td >
						<strong>
							<?php echo $this->contact[0]->misc;?>
						</strong>
					</td>
				</tr>
				<?php if ($this->contact[0]->image) { ?>
				<tr>
					<td class="key">
						<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_IMAGE' ); ?>
					</td>
					<td valign="top">
						<img src="<?php echo JURI::root() . $cparams->get('image_path') . '/' . $this->contact[0]->image; ?>" align="middle" alt="<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_CONTACT' ); ?>" />
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td class="key">&nbsp;</td>
					<td>
						<div>
							<br />
							<input class="button" type="button" value="<?php echo JText::_( 'NOIXACL_VIEW_USER_TEXT_CHANGE_CONTACT_DETAILS' ); ?>" onclick="gotocontact( '<?php echo $this->contact[0]->id; ?>' )" />
							<i>
								<br /><br />
								'<?php echo JText::_( 'Components -> Contact -> Manage Contacts' ); ?>'
							</i>
						</div>
					</td>
				</tr>
			</table>
			<?php } ?>
		</fieldset>
	</div>
	<div class="clr"></div>
    <?php if( count($this->groups) > 0 ): ?>
	<fieldset class="adminform">
    <legend><?php echo JText::_( 'NOIXACL_VIEW_USER_LEGEND_MULTIGROUP' ); ?></legend>
        <table class="adminlist" cellpadding="1">
        <thead>
			<tr>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->groups); ?>);" />
				</th>
				<th class="title">
					<?php echo JTEXT::_('NOIXACL_VIEW_USER_TEXT_GROUP_NAME'); ?>
				</th>
			</tr>
		</thead>
        <tbody>
        <?php
			$i = 0;
			foreach($this->groups as $groupValue => $groupName)
			{
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
                	<?php if( $groupValue <= 30 ): ?>
                    <input type="checkbox" disabled="disabled" />
                    <?php else: ?>
					<input type="checkbox" name="multigroups[]" <?php if($groupValue == $this->user->get('gid')){ echo "disabled=\"disabled\""; } if( in_array($groupValue,$this->UserMultiGroup) ){ echo "checked=\"checked\""; } ?> id="cb<?php echo $i; ?>" value="<?php echo $groupValue; ?>" />
                    <?php endif; ?>
				</td>
				<td>
					<?php echo $groupName; ?>
				</td>
			</tr>
			<?php
				$i++;
			}
			?>
		</tbody>
	</table>
    </fieldset>
    <?php endif; ?>
    <div class="clr"></div>
	<input type="hidden" name="id" value="<?php echo $this->user->get('id'); ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->user->get('id'); ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="controller" value="aclusers" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="contact_id" value="" />
    <input type="hidden" name="boxchecked" value="0" />
	<?php if (!$this->me->authorize( 'com_users', 'email_events' )) { ?>
	<input type="hidden" name="sendEmail" value="0" />
	<?php } ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br clear="all" />
<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."version.php"; ?>