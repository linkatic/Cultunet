<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Installation file.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Back function
function BackToInstall($e)
{
	// show error in alert
	echo '<script> alert("'.$e.'");window.history.go(-1);</script>';
	// stop script
	exit();
}

// install function
function com_install() 
{
	// creatung database interface
	$database = & JFactory::getDBO();
	// Swhowing header
	echo "<h2>Photoslide GK3</h2>";
	// component database actualization
	$database->setQuery('
	UPDATE 
		#__components 
	SET 
		`admin_menu_img` = "../administrator/components/com_gk3_photoslide/interface/images/com_logo_gk3.png"
	WHERE 
		`name` = "gk3_tabs_manager" 
		AND 
		`option` = "com_gk3_tabs_manager"
	');
	// when error - go back
	if (!$database->query()) BackToInstall($database->getErrorMsg());
	// actualization of link database
	$database->setQuery('
	UPDATE 
		#__components 
	SET 
		`link` = "option=com_gk3_photoslide" 
	WHERE 
		`name` = "gk3_photoslide" 
		AND 
		`option` = "com_gk3_photoslide"
	');
	// when error - go back
	if (!$database->query()) BackToInstall($database->getErrorMsg());
	// when all is OK - show info about successfull installation
	echo '<p>So much to say… </p><p>Well, it’s free! It’s amazing! It’s yours!</p><p>With reputation come new challenges, with new visions come new options. Our production team brings the new version of PhotoSlide GK3 Component, showing we can push our limits; find the edge of our capabilities and extend it even more.</p><p>GavickPro PhotoSlide GK3 is the most advanced free joomla component, incredibly in image show presentation, giving that eye-catching way to present your articles, products, stories or events in your website, with dazzling slideshow images integrated with your features content. Provided with different slide show effects with fantastic high quality design, success is guaranteed if you want to captivate full attention of your website visitors. </p><p>With new options available, the new PhotoSlide release a huge amount of possibilities, making use of a user friendly administration and powerful front end presentation.</p><p>So, ready for a new challenge?</p><p><strong>An overview of PhotoSlide GK3 key features:</strong></p><ul><li>Joomla! 1.5 Native</li> <li>Javascript Framework Mootools</li> <li>Option for compressed engine script use</li> <li>New technique of assets JavaScripts files</li><li>Image Slide display with interactive content articles from sections or categories</li> <li>Custom content display or own link option.</li><li>WYSIWYG editor for content production.</li><li>Easy administration with Modalbox effect display.</li><li>Check system tool for all necessary conditions.</li><li>High style quality design module 100% css based provided</li> <li>Customize User CSS formatting.</li><li>Module style with image and thumbnails block</li><li>Module style with image rotator</li><li>Module with template style</li><li>Extensions support interface with component administration</li> <li>Multilanguage support.</li><li>Lightweight, modern and fast-loading design.</li> <li>Image quality control system for loading optimization</li> <li>Easy and friendly administration </li><li>Support GIF, JPG and PNG normal format image (or transparent background).</li><li>SEF URLs </li><li>Different slide navigation tools available</li> <li>Control panel - users can control the display of amount of news image</li> <li>Level Access for slide display control </li><li>AJAX support tools </li><li>W3C XHTML 1.0 Transitional. W3C CSS Valid.</li> <li>Fully compatible IE7, Firefox 2+, Flock 0.7+, Netscape, Safari, Opera 9.5</li></ul>'; 
}

/* End of file install.php */
/* Location: ./install.php */