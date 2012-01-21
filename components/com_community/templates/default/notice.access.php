<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') OR DIE();

$my = CFactory::getUser();
?>

<?php $this->renderModules( 'js_noaccess_top' ); ?>

<div class="community-restricted">
	<strong><?php echo $notice;?></strong>
</div>

<?php if($my->id == 0) { ?>
<div class="community-restricted-note">
	<?php echo JText::sprintf('CC NOTICE NO ACCESS' , CRoute::_('index.php?option=com_community&view=frontpage') , CRoute::_('index.php?option=com_community&view=register') );?>
</div>
<?php } ?>

<?php $this->renderModules( 'js_noaccess_bottom' ); ?>