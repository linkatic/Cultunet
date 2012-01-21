<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<table class="blockUnregister" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan="2" valign="top" align="center">
			<div class="message" style="margin-bottom: 10px;"><?php echo JText::_('CC PLEASE LOGIN OR REGISTER');?></div>
		</td>
	</tr>
	<tr>
		<td valign="top" width="55%">
	        <h3><?php echo JText::_('CC REGISTER NOW TO GET CONNECTED');?></h3>
	        <ul id="featurelist">
	            <li><?php echo JText::_('CC CONNECT AND EXPAND');?></li>
	            <li><?php echo JText::_('CC VEW PROFILES AND ADD FRIEND');?></li>
	            <li><?php echo JText::_('CC SHARE PHOTOS AND VIDEOS');?></li>
	            <li><?php echo JText::_('CC CREATE OWN GROUP OR JOIN');?></li>
	        </ul>
	        <div style="text-align: center;">
				<a id="joinButton2" href="<?php echo CRoute::_( 'index.php?option=com_community&view=register' , false );?>" title="<?php echo JText::_('CC JOIN US NOW');?>"><?php echo JText::_('CC JOIN US NOW');?></a>
			</div>
		</td>
		<td valign="top">
		    <div class="loginform" style="margin-left: 10px; padding-left: 30px;">
		    	<form action="<?php echo CRoute::getURI();?>" method="post" name="login" id="form-login" >
		        <h3><?php echo JText::_('CC MEMBER LOGIN');?></h3>
		            <label for="smallusername"><?php echo JText::_('CC USERNAME');?><br />
		                <input type="text" class="inputbox frontlogin" name="username" id="smallusername" />
		            </label>
		            <label form="smallpassword"><?php echo JText::_('CC PASSWORD');?>
		                <input type="password" class="inputbox frontlogin" name="passwd" id="smallpassword" />
		            </label>
					<label for="remember">
						<input type="checkbox" alt="<?php echo JText::_('CC REMEMBER MY DETAILS');?>" value="yes" id="smallremember" name="remember"/>
						<?php echo JText::_('CC REMEMBER MY DETAILS');?>
					</label>
					<div style="text-align: center;">
					    <input type="submit" value="<?php echo JText::_('CC LOGIN');?>" name="submit" id="smallsubmit" class="button" />
						<input type="hidden" name="option" value="com_user" />
						<input type="hidden" name="task" value="login" />
						<input type="hidden" name="return" value="<?php echo $uri;?>" />
						<?php echo JHTML::_( 'form.token' );?>
					</div>
		        </form>
		    </div>			
		</td>
	</tr>
</table>