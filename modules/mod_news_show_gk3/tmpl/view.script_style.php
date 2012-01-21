<?php

/**
* Gavick News Show GK3 - script template
* @package Joomla!
* @Copyright (C) 2008 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 3.2 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php if($this->useMoo == 1) : ?><script type="text/javascript" src="<?php echo $uri->root(); ?>modules/mod_news_show_gk3/scripts/mootools_<?php echo $this->mootools_version; ?>.js"></script><?php endif; ?>
<?php if($this->useScript == 1) : ?><script type="text/javascript" src="<?php echo $uri->root(); ?>modules/mod_news_show_gk3/scripts/engine_<?php echo $this->mootools_version; ?><?php echo ($this->compress_js == 1) ? '_compressed' : '' ?>.js"></script><?php endif; ?>