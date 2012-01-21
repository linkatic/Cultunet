<?php
/**
 * @version		$Id: install.mtimporter.php 843 2010-02-04 11:43:03Z CY $
 * @package		MT Importer
 * @copyright	(C) 2005-2010 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once( 'components/com_mtree/admin.mtree.class.php' );

function com_install() {
?>
<font size="+1">
<a href="index.php?option=com_mtimporter&amp;task=check_csv">Import data from .csv file</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_jcontent">Import Joomla's Content &amp; Weblinks</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_sobi2">Import SOBI2 2.9.x</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_mosdir">Import from mosDirectory 2.2</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_bookmarks">Import from Bookmarks 2.7</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_gossamerlinks">Import from Gossamer Links</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_esyndicate">Import from eSyndicate 2.2</a>
<br />
<a href="index.php?option=com_mtimporter&amp;task=check_remository">Import from Remository 3.5</a>
</font>
<?php
}

?>