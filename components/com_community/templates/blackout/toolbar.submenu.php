<?php
/**
 * @category 	Template
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

$script = '
<script type="text/javascript">
joms.jQuery(document).ready( function() {
	joms.jQuery("#community-wrap ul.submenu li a:last").css("border-right", "0");
});
</script>';

$mainframe =& JFactory::getApplication();
$mainframe->addCustomHeadTag( $script );
?>

<div id="cSubmenu">
	<ul class="submenu-items clrfix">
	<?php
	foreach($submenu as $menu)
	{
		$action		= (isset($menu->action) && ($menu->action) ) ? ' style="float: right;" class="no-right-border submenu-item"' : 'class="submenu-item"';
		$active		= '';
		
		if( isset( $menu->onclick ) && !empty( $menu->onclick) )
		{
			$link	= 'href="javascript:void(0);" onclick="' . $menu->onclick . '"';
		}
		else
		{
			$active		= ( JString::strtolower( $menu->view ) == JString::strtolower($view) && JString::strtolower( $menu->task ) == JString::strtolower($task) ) ? ' class="active"' : '';
			$link		= 'href="' . CRoute::_( $menu->link ) . '"';
		}
	?>
		<li <?php echo $action;?>>
			<a <?php echo $link;?><?php echo $active;?>><?php echo $menu->title;?></a>
		</li>
	<?php
	}
	?>
	</ul>
</div>