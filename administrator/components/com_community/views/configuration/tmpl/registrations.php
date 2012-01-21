<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'CC REGISTRATIONS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE TERMS' ); ?>::<?php echo JText::_('CC ENABLE TERMS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE TERMS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enableterms' , null , $this->config->get('enableterms') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC REGISTRATION TERMS' ); ?>::<?php echo JText::_('CC REGISTRATION TERMS TIPS'); ?>">
					<?php echo JText::_( 'CC REGISTRATION TERMS' ); ?>
					</span>
				</td>
				<td valign="top">
					<textarea name="registrationTerms" cols="30" rows="5"><?php echo $this->config->get('registrationTerms');?></textarea>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE RECAPTCHA' ); ?>::<?php echo JText::_('CC ENABLE RECAPTCHA TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE RECAPTCHA' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'recaptcha' , null , $this->config->get('recaptcha') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECAPTCHA PUBLIC KEY' ); ?>::<?php echo JText::_('CC RECAPTCHA PUBLIC KEY TIPS'); ?>">
						<?php echo JText::_( 'CC RECAPTCHA PUBLIC KEY' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="recaptchapublic" value="<?php echo $this->config->get('recaptchapublic'); ?>" size="35" />
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECAPTCHA PRIVATE KEY' ); ?>::<?php echo JText::_('CC RECAPTCHA PRIVATE KEY TIPS'); ?>">
						<?php echo JText::_( 'CC RECAPTCHA PRIVATE KEY' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="recaptchaprivate" value="<?php echo $this->config->get('recaptchaprivate'); ?>" size="35" />
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECAPTCHA THEME' ); ?>::<?php echo JText::_('CC RECAPTCHA THEME TIPS'); ?>">
						<?php echo JText::_( 'CC RECAPTCHA THEME' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="recaptchatheme">
						<option value="red"<?php echo $this->config->get('recaptchatheme') == 'red' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC RED');?></option>
						<option value="white"<?php echo $this->config->get('recaptchatheme') == 'white' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC WHITE');?></option>
						<option value="blackglass"<?php echo $this->config->get('recaptchatheme') == 'blackglass' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC BLACKGLASS');?></option>
						<option value="clean"<?php echo $this->config->get('recaptchatheme') == 'clean' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC CLEAN');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECAPTCHA LANGUAGE' ); ?>::<?php echo JText::_('CC RECAPTCHA LANGUAGE TIPS'); ?>">
						<?php echo JText::_( 'CC RECAPTCHA LANGUAGE' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="recaptchalang">
						<option value="en"<?php echo $this->config->get('recaptchalang') == 'en' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC ENGLISH');?></option>
						<option value="nl"<?php echo $this->config->get('recaptchalang') == 'nl' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC DUTCH');?></option>
						<option value="fr"<?php echo $this->config->get('recaptchalang') == 'fr' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC FRENCH');?></option>
						<option value="de"<?php echo $this->config->get('recaptchalang') == 'de' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC GERMAN');?></option>
						<option value="pt"<?php echo $this->config->get('recaptchalang') == 'pt' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC PORTUGUESE');?></option>
						<option value="ru"<?php echo $this->config->get('recaptchalang') == 'ru' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC RUSSIAN');?></option>
						<option value="es"<?php echo $this->config->get('recaptchalang') == 'es' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC SPANISH');?></option>
						<option value="tr"<?php echo $this->config->get('recaptchalang') == 'tr' ? ' selected="selected"' : ''; ?>><?php echo JText::_('CC TURKISH');?></option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>