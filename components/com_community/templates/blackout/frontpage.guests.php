<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();

$script = '
<script type="text/javascript">
joms.jQuery(document).ready( function() {
	joms.jQuery("#login-form input").focus( function() {
	    if ( joms.jQuery(this).attr("type") === "text" || joms.jQuery(this).attr("type") === "password" ) {
			joms.jQuery(this).addClass("input-hover");
		}
	});
	joms.jQuery("#login-form input").blur( function() {
	   	joms.jQuery(this).removeClass("input-hover");
	});
	
	/* Button Hover */
	joms.jQuery("#login-form input.button").hover( function() {
	   	joms.jQuery(this).addClass("button-hover");
	}, function() {
        joms.jQuery(this).removeClass("button-hover");
	});
});
</script>';
$mainframe =& JFactory::getApplication();
$mainframe->addCustomHeadTag( $script );
?>
<div class="greybox">
	<div>
	    <div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			    <tr>
			        <td valign="top">
					    <div class="introduction">
					        <h1 class="componentheading"><?php echo JText::_('CC GET CONNECTED TITLE'); ?></h1>
					        <ul id="featurelist">
					            <li class="connect"><?php echo JText::_('CC CONNECT AND EXPAND'); ?></li>
					            <li class="profile"><?php echo JText::_('CC VEW PROFILES AND ADD FRIEND'); ?></li>
					            <li class="photo"><?php echo JText::_('CC SHARE PHOTOS AND VIDEOS'); ?></li>
					            <li class="group"><?php echo JText::_('CC CREATE OWN GROUP OR JOIN'); ?></li>
					        </ul>
					        <div class="joinbutton">
								<a id="joinButton" href="<?php echo CRoute::_( 'index.php?option=com_community&view=register' , false ); ?>" title="<?php echo JText::_('CC JOIN US NOW'); ?>">
								    <?php echo JText::_('CC JOIN US NOW'); ?>
								</a>
							</div>
					    </div>
			        </td>
			        <td width="200">
					    <div id="login-form" class="loginform">
					    	<form action="<?php echo CRoute::getURI();?>" method="post" name="login" id="form-login" >
					        <h2><?php echo JText::_('CC MEMBER LOGIN'); ?></h2>
					            <label for="username"><?php echo JText::_('CC USERNAME'); ?></label>
					            <input type="text" class="inputbox frontlogin" name="username" id="username" />

					            <label for="password"><?php echo JText::_('CC PASSWORD'); ?><br /></label>
					            <input type="password" class="inputbox frontlogin" name="passwd" id="password" />
					            
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

<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/templates/blackout/js/script.js"></script>