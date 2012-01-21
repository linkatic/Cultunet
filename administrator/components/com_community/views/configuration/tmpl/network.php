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
<div>
	<div style="float: left; width: 55%">
		<?php require_once( dirname(__FILE__) . DS . 'network_form.php' ); ?>
	</div>
	<div style="float: right; width: 45%">
		<?php require_once( dirname(__FILE__) . DS . 'network_info.php' ); ?>
	</div>
	<div style="clear: both;"></div>
</div>