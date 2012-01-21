<?php
/**
 * @category 	Template
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
?>
<script type="text/javascript">
joms.jQuery(document).ready( function() {
	joms.jQuery('#community-wrap ul.submenu li a:last').css('border-right', '0');
});
</script>
<div class="cSubmenu clrfix">

		<ul class="submenu">
		<?php
		foreach($submenu as $menu)
		{
			$action		= (isset($menu->action) && ($menu->action) ) ? ' class="action"' : '';
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
			<li<?php echo $action;?>>
				<a <?php echo $link;?><?php echo $active;?>><?php echo $menu->title;?></a>
			</li>
		<?php
		}
		?>
		</ul>

</div>