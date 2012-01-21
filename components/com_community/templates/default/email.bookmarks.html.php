<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	{target}	string The name of the target
 * @param	$url		string	The URL to the specific group
 * @param	$user		string	The name of the user
 * @param	$group		string	The name of the group
 */
defined('_JEXEC') or die();
?>
Estimado/a {target},

Un usuario quiere compartir un enlace contigo.

Puedes ver el enlace en:
<a href="<?php echo $uri; ?>"><?php echo $uri; ?></a>

<?php
if( !empty($message) )
{
?>
Mensaje:
===============================================================================

<?php echo $message; ?>


===============================================================================
<?php
}
?>