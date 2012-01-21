<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<form action="index.php" method="post" name="adminForm">
	<?php
	echo $this->tabs->startPane("settingsPane");
	echo $this->tabs->startPanel(JText::_('GENERAL SETTINGS'), "general-page");
		echo $this->loadTemplate('general');
	echo $this->tabs->endPanel();
	echo $this->tabs->startPanel(JText::_('WRITING'), "writing-page");
		echo $this->loadTemplate('writing');
	echo $this->tabs->endPanel();
	echo $this->tabs->startPanel(JText::_('CONTENT'), "content-page");
		echo $this->loadTemplate('content');
	echo $this->tabs->endPanel();
	echo $this->tabs->startPanel(JText::_('FEED'), "feed-page");
		echo $this->loadTemplate('feed');
	echo $this->tabs->endPanel();
	echo $this->tabs->startPanel(JText::_('DISCUSSION'), "discussion-page");
		echo $this->loadTemplate('discussion');
	echo $this->tabs->endPanel();
	echo $this->tabs->endPane();
	?>


	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>