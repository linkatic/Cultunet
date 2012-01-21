<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<script language="javascript" type="text/javascript">
<?php if(version_compare(JVERSION,'1.6.0','<')){ ?>
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if(pressbutton != 'cancel' && form.email){
			form.email.value = form.email.value.replace(/ /g,"");
			var filter = /^([a-z0-9_'&\.\-\+])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,10})+$/i;
			if(!filter.test(form.email.value)) {
				alert( "<?php echo JText::_( 'VALID_EMAIL', true ); ?>" );
				return false;
			}
		}
		submitform( pressbutton );
	}
<?php }else{ ?>
	Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;
		if(pressbutton != 'cancel' && form.email){
			form.email.value = form.email.value.replace(/ /g,"");
			var filter = /^([a-z0-9_'&\.\-\+])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,10})+$/i;
			if(!filter.test(form.email.value)) {
				alert( "<?php echo JText::_( 'VALID_EMAIL', true ); ?>" );
				return false;
			}
		}
		Joomla.submitform(pressbutton,form);
	};
<?php } ?>
</script>
<div id="acy_content" >
<div id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=subscriber" method="post" name="adminForm" autocomplete="off">
	<table width="100%">
		<tr><td valign="top">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'USER_INFORMATIONS' ); ?></legend>
			<table width="100%">
				<tr>
					<td width="50%" align="left">
						<table class="admintable" cellspacing="1">
							<tr>
								<td width="150" class="key">
									<label for="name">
									<?php echo JText::_( 'JOOMEXT_NAME' ); ?>
									</label>
								</td>
								<td>
								<?php
								if(empty($this->subscriber->userid)){
										echo '<input type="text" name="data[subscriber][name]" id="name" class="inputbox" size="40" value="'.$this->escape(@$this->subscriber->name).'" />';
								}else{
									echo $this->subscriber->name;
								}
								?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="email">
									<?php echo JText::_( 'JOOMEXT_EMAIL' ); ?>
									</label>
								</td>
								<td>
									<?php
									if(empty($this->subscriber->userid)){
										echo '<input class="inputbox required" type="text" name="data[subscriber][email]" id="email" size="40" value="'.$this->escape(@$this->subscriber->email).'" />';
									}else{
										echo $this->subscriber->email;
									}
									?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="created">
									<?php echo JText::_( 'CREATED_DATE' ); ?>
									</label>
								</td>
								<td>
									<?php echo acymailing::getDate($this->subscriber->created);?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="ip">
									<?php echo JText::_( 'IP' ); ?>
									</label>
								</td>
								<td>
									<?php echo $this->subscriber->ip;?>
								</td>
							</tr>
					<?php
						if(!empty($this->subscriber->userid)){
					?>
							<tr>
								<td class="key">
									<label for="username">
									<?php echo JText::_( 'ACY_USERNAME' ); ?>
									</label>
								</td>
								<td>
									<?php echo $this->subscriber->username;?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="userid">
									<?php echo JText::_( 'USER_ID' ); ?>
									</label>
								</td>
								<td>
									<?php echo $this->subscriber->userid;?>
								</td>
							</tr>
					<?php
							}
					?>
						</table>
					</td>
					<td align="left" >
						<table class="admintable" cellspacing="1">
							<tr>
								<td class="key">
									<label for="html">
									<?php echo JText::_( 'RECEIVE' ); ?>
									</label>
								</td>
								<td>
								  <?php echo JHTML::_('select.booleanlist', "data[subscriber][html]" , '',$this->subscriber->html,JText::_('HTML'),JText::_('JOOMEXT_TEXT')); ?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="confirmed">
									<?php echo JText::_( 'CONFIRMED' ); ?>
									</label>
								</td>
								<td>
								  <?php echo JHTML::_('select.booleanlist', "data[subscriber][confirmed]" , '',$this->subscriber->confirmed); ?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="block">
									<?php echo JText::_( 'ENABLED' ); ?>
									</label>
								</td>
								<td>
								  <?php echo JHTML::_('select.booleanlist', "data[subscriber][enabled]" , '',$this->subscriber->enabled); ?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="accept">
									<?php echo JText::_( 'ACCEPT_EMAIL' ); ?>
									</label>
								</td>
								<td>
								  <?php echo JHTML::_('select.booleanlist', "data[subscriber][accept]" , '',$this->subscriber->accept); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
<?php if(!empty($this->extraFields)){
	echo '</td><td valign="top">';
	include(dirname(__FILE__).DS.'extrafields.'.basename(__FILE__));
	} ?>
	</td></tr></table>
	<div>
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'SUBSCRIPTION' ); ?></legend>
			<table class="adminlist" cellspacing="1" align="center">
				<thead>
					<tr>
						<th class="title titlenum">
						<?php echo JText::_( 'ACY_NUM' );?>
						</th>
						<th class="title titlecolor">
						</th>
						<th  class="title" nowrap="nowrap">
						<?php echo JText::_( 'LIST_NAME' ); ?>
						</th>
						<th  class="title" nowrap="nowrap" width="480">
						<?php echo JText::_( 'STATUS' ); echo '<span style="font-style:italic;margin-left:50px">'.$this->filters->statusquick.'</span>';?>
						</th>
						<th  class="title titledate">
						<?php echo JText::_( 'SUBSCRIPTION_DATE' ); ?>
						</th>
						<th  class="title titledate">
						<?php echo JText::_( 'UNSUBSCRIPTION_DATE' ); ?>
						</th>
						<th  class="title titleid">
							<?php echo JText::_( 'ID' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$k = 0;
					for($i = 0,$a = count($this->subscription);$i<$a;$i++){
						$row =& $this->subscription[$i]; ?>
					<tr class="<?php echo "row$k"; ?>" >
						<td align="center">
							<?php echo $i +1; ?>
						</td>
						<td width="12">
						<?php echo '<div class="roundsubscrib rounddisp" style="background-color:'.$row->color.'"></div>'; ?>
						</td>
						<td>
							<?php
							$text = str_replace(array("'",'"'),array("&#039;",'&quot;'),$row->description);
							$title = str_replace(array("'",'"'),array("&#039;",'&quot;'),$row->name);
							echo JHTML::_('tooltip', $text, $title, 'tooltip.png', $title);
							 ?>
						</td>
						<td align="center">
							<?php echo $this->statusType->display('data[listsub]['.$row->listid.'][status]',@$row->status); ?>
						</td>
						<td align="center">
							<?php if(!empty($row->subdate)) echo acymailing::getDate($row->subdate); ?>
						</td>
						<td align="center">
							<?php if(!empty($row->unsubdate)) echo acymailing::getDate($row->unsubdate); ?>
						</td>
						<td align="center">
							<?php echo $row->listid; ?>
						</td>
					</tr>
					<?php
						$k = 1 - $k;
					} ?>
				</tbody>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo @$this->subscriber->subid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="subscriber" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>