<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>

<!-- begin: COMMUNITY_FREE_VERSION -->
<?php if( !COMMUNITY_FREE_VERSION ) { ?>
<div id="fb-root"></div>
<script type="text/javascript">
var count	= 1;
window.fbAsyncInit = function() {
    FB.init({appId: '<?php echo $config->get('fbconnectkey');?>', status: false, cookie: true, xfbml: true});

    /* All the events registered */
    FB.Event.subscribe('auth.login', function(response) {
    	if( count == 1 )
			joms.connect.update();
			
		count++;
    });
//         FB.Event.subscribe('auth.logout', function(response) {
//         	console.log('test');
//         });
};
(function() {
    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.src = document.location.protocol +
        '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
}());
</script>
<fb:login-button autologoutlink="true" perms="read_stream,publish_stream,offline_access,email,user_birthday,status_update,user_status"><?php echo JText::_('CC SIGN IN WITH FACEBOOK');?></fb:login-button>
<?php } ?>
<!-- end: COMMUNITY_FREE_VERSION -->
