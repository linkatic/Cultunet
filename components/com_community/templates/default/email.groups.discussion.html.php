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

{actor} recientemente ha iniciado una nueva discusi&oacute;n en el grupo <?php echo $this->escape($group->name); ?>. Abajo hay un fragmento de la discusi&oacute;n creada.

Tema:
<?php echo $subject; ?>

Mensaje:
<?php echo $message; ?>


Para publicar una respuesta, puedes visitar el website en el siguiente enlace <a href="{url}">{url}</a>

