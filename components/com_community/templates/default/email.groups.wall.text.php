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

{actor} acaba de realizar una nueva publicación en el muro del grupo <?php echo $this->escape($group); ?>


Mensaje:

<?php echo $this->escape($message); ?>

Puedes leer el mensaje en el siguiente enlace:


{url}

