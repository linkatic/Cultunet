<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<h3>+ Suscríbete a g+c mailing</h3>
<div class="greyblock">
<?php if(empty($this->subscriber->subid)) { ?>
	<p>g+c mailing es el primer mailing especializado de gestión cultural, a través del cual es posible mantenerse informado/a de 
		las novedades que se van produciendo referentes a la gestión cultural. Si quieres recibir convocatorias nacionales e internacionales 
		de ayudas públicas, subvenciones, ofertas de empleo, becas, etc. y todavía no recibes g+c mailing, sólo tienes que introducir tu nombre, e-mail y 
		seleccionar el área concreta que te interese. <strong>Recomendamos apuntarse como máximo a tres listas</strong>, debes tener en cuenta que te enviaremos un correo 
		electrónico por cada listado en el que te encuentres apuntado. Si necesitas una información más general, apúntate sólo al listado "Convocatorias y empleo - Todas las áreas"</p>
	<p><br />Si ya estás suscrito a alguno de nuestros boletines y quieres modificar tus preferencias, rellena los campos nombre y correo electrónico y pulsa en el botón <strong>modificar suscripciones</strong>. 
		Te enviararemos un correo a esa misma dirección para verificar tu identidad. 
		Una vez realizada la comprobación podrás cambiar tus suscripciones desde esta misma pantalla.</p> 
<?php } else { ?>
	<p>Comprobada tu identidad puedes hacer las modificaciones que consideres en tus suscripciones y guardar los cambios.</p> 
<?php } ?>
<form id="acymodifyform" action="<?php echo acymailing::completeLink('user'); ?>" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend>
			<?php 
				if(empty($this->subscriber->subid)) echo JText::_( 'USER_INFORMATIONS_' ); 
				else echo JText::_( 'USER_INFORMATIONS' ); 
			?>
		</legend>
		<table cellspacing="1" align="center" width="100%" id="acyuserinfo">
			<tr id="trname">
				<td width="150" class="key">
					<label for="field_name">
					<?php echo JText::_( 'JOOMEXT_NAME' ); ?>
					</label>
				</td>
				<td>
				<?php
				if(empty($this->subscriber->userid)){
						echo '<input type="text" name="data[subscriber][name]" id="field_name" class="inputbox" size="40" value="'.$this->escape(@$this->subscriber->name).'" />';
				}else{
					echo $this->subscriber->name;
				}
				?>
				</td>
			</tr>
			<tr id="tremail">
				<td class="key">
					<label for="field_email">
					<?php echo JText::_( 'JOOMEXT_EMAIL' ); ?>
					</label>
				</td>
				<td>
					<?php
					if(empty($this->subscriber->userid)){
						echo '<input class="inputbox" type="text" name="data[subscriber][email]" id="field_email" size="40" value="'.$this->escape(@$this->subscriber->email).'" />';
					}else{
						echo $this->subscriber->email;
					}
					?>
				</td>
			</tr>
			<input id="user_html1" type="hidden" value="1" name="data[subscriber][html]">
			<?php /*
			<tr id="trhtml">
				<td class="key">
					<?php echo JText::_( 'RECEIVE' ); ?>
				</td>
				<td>
				  <?php echo JHTML::_('select.booleanlist', "data[subscriber][html]" , '',$this->subscriber->html,JText::_('HTML'),JText::_('JOOMEXT_TEXT'),'user_html'); ?>
				</td>
			</tr> */
			?>
		<?php 
		if(empty($this->subscriber->subid) AND $this->config->get('captcha_enabled')){ ?>
			<tr id="trcaptcha">
				<td class="captchakeycomponent">
					<img class="captchaimagecomponent" src="<?php echo rtrim(JURI::root(),'/').'/index.php?option=com_acymailing&ctrl=captcha&val='.rand(0,10000); ?>" alt="captcha" />
				</td>
				<td class="captchafieldcomponent">
					<input id="user_captcha" class="inputbox captchafield" type="text" name="acycaptcha" size="10" />
				</td>
			</tr>
			<?php }
			?>
		</table>
	</fieldset>
	<?php if($this->displayLists){?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'SUBSCRIPTION_' ); ?></legend>
		<table cellspacing="1" align="center" width="100%" id="acyusersubscription">
			<thead>
				<tr>
					<th  class="title" nowrap="nowrap" align="center" width="150">
					<?php echo JText::_( 'SUBSCRIBE' );?>
					</th>
					<th  class="title" nowrap="nowrap" align="center">
					<?php echo JText::_( 'LIST' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				foreach($this->subscription as $row){
					if(empty($row->published) OR !$row->visible) continue;
					?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" valign="top">
						<?php if(empty($this->subscriber->subid)) { ?>
						<?php echo $this->status->display("data[listsub][".$row->listid."][status]",''); ?>
						<?php } else { ?>
						<?php echo $this->status->display("data[listsub][".$row->listid."][status]",@$row->status); ?>
						<?php } ?>
					</td>
					<td valign="top">
						<div class="list_name"><?php echo $row->name ?></div>
						<div class="list_description"><?php echo $row->description ?></div>
					</td>
				</tr>
				<?php
					$k = 1 - $k;
				} ?>
			</tbody>
		</table>
	</fieldset>
	<?php } ?>
	<br/>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="savechanges" />
	<input type="hidden" name="ctrl" value="user" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="subid" value="<?php echo $this->subscriber->subid; ?>" />
	<?php if(JRequest::getCmd('tmpl') == 'component'){ ?><input type="hidden" name="tmpl" value="component" /><?php } ?>
	<input type="hidden" name="key" value="<?php echo $this->subscriber->key; ?>" />
	<input class="button" type="submit" onclick="return checkChangeForm();" value="<?php echo empty($this->subscriber->subid) ? JText::_('SUBSCRIBE',true) : JText::_('SAVE_CHANGES',true)?>"/>
	<?php if(empty($this->subscriber->subid)) : ?>
		<input class="button" type="submit" onclick="return checkChangeForm();" value="Modificar suscripción"/>
	<?php endif; ?>
</form>
</div>