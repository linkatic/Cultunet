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

ini_set('max_execution_time', '120');

/**
 * Method to extract zip files
 */
function BloggieExtractZip($src, $destDir)
{
	$destDir 	=  JPath::clean($destDir);
	$src 		=  JPath::clean($src);
	$error 		= JArchive::extract($src, $destDir);
	$error 		= @unlink($src);
	return true;
}

$version 			= '1.1.0B4';
$patches 			= '30,34';
$errors 			= '';
$db					= &JFactory::getDBO();
$default_settings 	= false;

// Old database?
$db->setQuery("SELECT COUNT(e.id) FROM `#__bloggies_entries` AS e");
$db_update = $db->loadResult();

// Installed Version
$db->setQuery("SELECT s.value FROM `#__bloggies_settings` AS s WHERE s.name = 'version'");
$old_version = $db->loadResult();

echo '<center><table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
			<td valign="middle">
	    		<img src="components/com_lyftenbloggie/assets/images/logo.png" align="left" />
					<p>LyftenBloggie is created by Daniel Stainback.</p>
					<p>Please visit <a href="http://www.lyften.com">www.lyften.com </a>to find out more. </p>
				</td>
				<td valign="top" width="100%">
		       	 	<strong>Install Status</strong><br/>';

	//Upgrade v1.0.x to v1.1.0
	if(file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'libraries'))
	{
		jimport( 'joomla.filesystem.folder' );

		// disable old stuff
		$query = "UPDATE #__modules AS m SET m.published = '0' WHERE m.module IN ('mod_lb_latestpop', 'mod_lb_latestblog', 'mod_lb_calendar', 'mod_lb_authors', 'mod_lb_tagcloud', 'mod_lb_categories')";
		$db->SetQuery($query);
		$db->query();
		echo '<img src="images/tick.png"> <b style="color:red;">Old modules disabled</b> <a href="http://www.lyften.com/products/2-lyftenbloggie/extensions.html" target="_blank">Get Updated Ones</a><br />';

		$queries 	= JFile::read( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'install'.DS.'upgrade1.0.sql' );
		$default_settings = true;

		echo '<img src="images/tick.png"> Updating Database';
		// Get rid of first line as it messes with the batch parser
		$db->setQuery( $queries );
		if (!$db->queryBatch()) {
			//$errors = '<li>'.$db->getErrorMsg().'</li>';
			echo '...ignored<br />';
		}else{
			echo '...done<br />';
		}

		// Update Access levels
		echo '<img src="images/tick.png"> Updating Access levels';
		$query = "UPDATE #__bloggies_entries AS e SET e.access = '18' WHERE e.access = '1'";
		$db->SetQuery($query);
		$db->query();
		$query = "UPDATE #__bloggies_entries AS e SET e.access = '19' WHERE e.access = '2'";
		$db->SetQuery($query);
		$db->query();
		echo '...done<br />';

		//Old Folders must go!
		$folders 	= array();
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'addons';
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'assets';
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers';
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'libraries';
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'models';
		$folders[] = DS.'components'.DS.'com_lyftenbloggie'.DS.'views';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'views';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'tables';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'models';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'libraries';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'elements';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'controllers';
		$folders[] = DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'assets';

		echo '<img src="images/tick.png"> Removing Old Files/Folders';

		//No Longer Used
		if (file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'admin.lyftenbloggie.php')){
			@unlink(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'admin.lyftenbloggie.php');
		}

		//Remove Folders
		foreach($folders as $folder)
		{
			$path = JPATH_SITE.$folder;
			if (is_dir($path)){
			   JFolder::delete($path);
			}
		}
		echo '...done<br />';

	// Just Update Old Database
	}else if($db_update && !$old_version) {
		$queries 	= JFile::read( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'install'.DS.'upgrade1.0.sql' );
		$default_settings = true;

		echo '<img src="images/tick.png"> Updating Old Database';
		// Get rid of first line as it messes with the batch parser
		$db->setQuery( $queries );
		if (!$db->queryBatch()) {
			//$errors = '<li>'.$db->getErrorMsg().'</li>';
			echo '...ignored<br />';
		}else{
			echo '...done<br />';
		}

	// Install Default Data for New Install
	}else if(!file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'lyftenbloggie.php')) {
		echo '<img src="images/tick.png"> Install Default Data';

		$queries 	= JFile::read( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'install'.DS.'data.sql' );
		$default_settings = true;

		// Get rid of first line as it messes with the batch parser
		$db->setQuery( $queries );
		if (!$db->queryBatch()) {
			//$errors = '<li>'.$db->getErrorMsg().'</li>';
			echo '...ignored<br />';
		}else{
			echo '...done<br />';
		}

	// Update Beta
	}else if(file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'config.php')) {
		echo '<img src="images/tick.png"> Updating beta';

		$queries = JFile::read( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'install'.DS.'upgrade1.1b.sql' );

		// Get rid of first line as it messes with the batch parser
		$db->setQuery( $queries );
		if (!$db->queryBatch()) {
			echo '...ignored<br />';
		}else{
			echo '...done<br />';
		}
	}

	// Install Default Settings
	if ($default_settings) {
		echo '<img src="images/tick.png"> Loading default configurations';

		$queries 	= JFile::read( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'install'.DS.'default.sql' );

		// Get rid of first line as it messes with the batch parser
		$db->setQuery( $queries );
		if (!$db->queryBatch()) {
			echo '...ignored<br />';
		}else{
			echo '...done<br />';
		}
	}

	echo '<img src="images/tick.png"> Create Author\'s Image Directory<br/>';	
	$path = JPATH_SITE.DS.'images'.DS.'lyftenbloggie';
	if (!is_dir($path)){
		@mkdir($path, 0755);
	}
			
	if (!file_exists($path.DS.'index.html')) {
		chmod($path, 0777);
		$handle = fopen($path.DS."index.html", 'x+');
		fclose($handle);
	}

    // Unzip full administrator files
    $AdminZip	= JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'admin.zip';
    $AdminDest	= JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS;
    echo '<img src="images/tick.png"> Install Administrator Files...done<br />';		
	$error = BloggieExtractZip($AdminZip, $AdminDest);
	
	// Unzip full site files
	$SiteZip  = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'site.zip';
	$SiteDest = JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS;
    echo '<img src="images/tick.png"> Installing Site Files...done<br />';		
	$error = BloggieExtractZip($SiteZip, $SiteDest);

	// Install Plugins
	$plugins = &$this->manifest->getElementByPath('plugins');
	if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children()))
	{
		foreach ($plugins->children() as $plugin)
		{
			$pname		= $plugin->attributes('plugin');
			$pgroup		= $plugin->attributes('group');

			// Set the installation path
			if (!empty($pname) && !empty($pgroup)) {
				$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
			} else {
				$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::_('INSTALL_PLUGIN_FILE_MISSING'));
				continue;
			}

			// If the plugin directory does not exist, lets create it
			if (!file_exists($this->parent->getPath('extension_root'))) {
				if (!JFolder::create($this->parent->getPath('extension_root'))) {
					$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::sprintf('INSTALL_PLUGIN_PATH_CREATE_FAILURE', $this->parent->getPath('extension_root')));
					continue;
				}else{
					$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
				}
			}

			// Copy all necessary files
			$element = &$plugin->getElementByPath('files');
			if ($this->parent->parseFiles($element, -1) === false) {
				// Install failed, roll back changes
				$this->parent->abort();
				continue;
			}

			// Copy all necessary files
			$element = &$plugin->getElementByPath('languages');
			if ($this->parent->parseLanguages($element, 1) === false) {
				// Install failed, roll back changes
				$this->parent->abort();
				continue;
			}

			// Check to see if a plugin by the same name is already installed
			$query = 'SELECT `id`' .
					' FROM `#__plugins`' .
					' WHERE folder = '.$db->Quote($pgroup) .
					' AND element = '.$db->Quote($pname);
			$db->setQuery($query);
			if (!$db->Query()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.$db->stderr(true));
				continue;
			}
			$id = $db->loadResult();

			// Was there a plugin already installed with the same name?
			if ($id) {
				if (!$this->parent->getOverwrite())
				{
					// Install failed, roll back changes
					$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::sprintf('INSTALL_PLUGIN_ALREADY_EXISTS', $pname));
					continue;
				}
			} else {
				$row =& JTable::getInstance('plugin');
				$row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname));
				$row->ordering = 0;
				$row->folder = $pgroup;
				$row->iscore = 0;
				$row->access = 0;
				$row->client_id = 0;
				$row->element = $pname;
				$row->published = 0;
				$row->params = '';

				if (!$row->store()) {
					// Install failed, roll back changes
					$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.$db->stderr(true));
					continue;
				}
			}

			echo '<img src="images/tick.png"> '.$plugin->attributes('name').'...installed<br/>';
		}
	}

	// Insert/update version into settings.
	$db->setQuery("SELECT COUNT(s.id) FROM `#__bloggies_settings` AS s WHERE s.name = 'version'");
	if ($db->loadResult() == 0)
	{
		$db->setQuery("INSERT IGNORE INTO `#__bloggies_settings` (`id`, `name`,`value`) VALUES ('', 'version', '".$version."')");
	}else{
		$db->setQuery("UPDATE #__bloggies_settings AS s SET s.value = '".$version."' WHERE s.name='version'");
	}
	$db->query();

	// Insert/update patches into settings.
	$db->setQuery("SELECT COUNT(s.id) FROM `#__bloggies_settings` AS s WHERE s.name = 'patches'");
	if ($db->loadResult() == 0)
	{
		$db->setQuery("INSERT IGNORE INTO `#__bloggies_settings` (`id`, `name`,`value`) VALUES ('', 'patches', '".$patches."')");
	}else{
		$db->setQuery("UPDATE #__bloggies_settings AS s SET s.value = '".$patches."' WHERE s.name='patches'");
	}
	$db->query();

	// Clear Caches
	$cache = JFactory::getCache();
	$cache->clean();
	
echo '<img src="images/tick.png"> Install Complete!';

if($errors) {
	echo '<br/><strong>Install Errors</strong><br/>';
	echo '<ul>'.$errors.'</ul>';
}
echo '</td></tr></table></center>';