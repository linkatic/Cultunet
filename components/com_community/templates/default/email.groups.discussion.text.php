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

{actor} recientemente ha iniciado una nueva discusión en el grupo <?php echo $this->escape($group->name); ?>. Abajo hay un fragmento de la discusión creada.

Tema:
<?php echo $subject; ?>

Mensaje:
<?php echo $message; ?>


Para publicar una respuesta, puedes visitar el website en el siguiente enlace <?php echo $url; ?>
