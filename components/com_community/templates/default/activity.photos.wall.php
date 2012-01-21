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
<ul class ="cDetailList clrfix">
	<li>
		<!-- avatar -->
		<div class="avatarWrap">
		<?php
		if( $url )
		{
			echo '<a href="' . $param->get('url') . '">';
		}
		?>
		<img src="<?php echo $photo->getThumbURI();?>" class="avatar" />
		<?php
		if( $url )
		{
		?>
		</a>
		<?php
		}
		?>
		</div>
		<!-- avatar -->
		<!-- details -->
		<div class="detailWrap alpha"><?php echo JString::substr($act->content, 0, $config->getInt('streamcontentlength'));?></div>
		<!-- details -->
	</li>
</ul>