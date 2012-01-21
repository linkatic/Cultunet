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
<ul class="cThumbList clrfix">
	<?php foreach($members as $member) { ?>
	<li><a href="<?php echo $member->profileLink;?>"><img class="avatar jomTips" src="<?php echo $member->avatar; ?>" title="<?php echo $member->tooltip; ?>" width="45" height="45" alt="<?php echo $this->escape( $member->displayName ) ?>"/></a></li>
	<?php } ?>
</ul>