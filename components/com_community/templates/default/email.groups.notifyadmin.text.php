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

{actor} recientemente ha creado un nuevo grupo llamado <?php echo $groupName;?>. Usted será el moderador del grupo.

Para acceder al area administrativa debes hacer click en el siguiente enlace: <a href="{url}">{url}</a>
