<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
Estimado/a {target},

{actor} te agrego como amigo. Necesitar&aacute;s aprobar esta solicitud antes de concretar tu amistad.

<?php 
	if(!empty($msg))
	{
		echo $msg;
	}
?>

Para agregar a {actor} como amigo, solo debes ir a la p&aacute;gina de Solicitud de Amistad haciendo click en el siguiente enlace:

<a href="{url}">{url}</a>
