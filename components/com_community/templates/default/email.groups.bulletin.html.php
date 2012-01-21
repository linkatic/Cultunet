<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
Estimado/a {target},

<?php echo $this->escape($group->name); ?> el grupo acaba de publicar un nuevo bolet&iacute;n.

Tema: <?php echo $this->escape($subject); ?>


Puedes leer el nuevo mensaje haciendo click en el siguiente enlace:


<a href="{url}">{url}</a>


