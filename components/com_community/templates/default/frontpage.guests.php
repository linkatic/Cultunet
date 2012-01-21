<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
?>
<div class="greybox">
	<div>
	    <div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			    <tr>
			        <td valign="top">
					    <div class="introduction">
					        <h1><?php echo JText::_('CC GET CONNECTED TITLE'); ?></h1>
					        <ul id="featurelist">
					            <li><?php echo JText::_('CC CONNECT AND EXPAND'); ?></li>
					            <li><?php echo JText::_('CC VEW PROFILES AND ADD FRIEND'); ?></li>
					            <li><?php echo JText::_('CC SHARE PHOTOS AND VIDEOS'); ?></li>
					            <li><?php echo JText::_('CC CREATE OWN GROUP OR JOIN'); ?></li>
					        </ul>
					        <div class="joinbutton">
								<a id="joinButton" href="<?php echo CRoute::_( 'index.php?option=com_community&view=register' , false ); ?>" title="<?php echo JText::_('CC JOIN US NOW'); ?>">
								    <?php echo JText::_('CC JOIN US NOW'); ?>
								</a>
							</div>
					    </div>
			        </td>
			        <td width="200">
					    <div class="loginform">
					    	<form action="<?php echo CRoute::getURI();?>" method="post" name="login" id="form-login" >
					        <h2><?php echo JText::_('CC MEMBER LOGIN'); ?></h2>
					            <label>
									<?php echo JText::_('CC USERNAME'); ?><br />
					                <input type="text" class="inputbox frontlogin" name="username" id="username" />
					            </label>

					            <label>
									<?php echo JText::_('CC PASSWORD'); ?><br />
					                <input type="password" class="inputbox frontlogin" name="passwd" id="password" />
					            </label>

                                <?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
								<label for="remember">
									<input type="checkbox" alt="<?php echo JText::_('CC REMEMBER MY DETAILS'); ?>" value="yes" id="remember" name="remember"/>
									<?php echo JText::_('CC REMEMBER MY DETAILS'); ?>
								</label>
								<?php endif; ?>

								<div style="text-align: center; padding: 10px 0 5px;">
								    <input type="submit" value="<?php echo JText::_('CC BUTTON LOGIN');?>" name="submit" id="submit" class="button" />
									<input type="hidden" name="option" value="com_user" />
									<input type="hidden" name="task" value="login" />
									<input type="hidden" name="return" value="<?php echo $return; ?>" />
									<?php echo JHTML::_( 'form.token' ); ?>
								</div>
								
								<span>
									<?php echo JText::_('CC FORGOT YOUR'); ?> <a href="<?php echo CRoute::_( 'index.php?option=com_user&view=reset' ); ?>" class="login-forgot-password"><span><?php echo JText::_('CC PASSWORD'); ?></span></a> /
									<a href="<?php echo CRoute::_( 'index.php?option=com_user&view=remind' ); ?>" class="login-forgot-username"><span><?php echo JText::_('CC USERNAME'); ?></span></a>?
								</span>
								<br />									
								<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=register&task=activation' ); ?>" class="login-forgot-username">
									<span><?php echo JText::_('CC RESEND ACTIVATION CODE'); ?></span>
								</a>
					        </form>
					        <?php echo $fbHtml;?>
					    </div>
			        </td>
			    </tr>
			</table>
	    </div>
	</div>
</div>