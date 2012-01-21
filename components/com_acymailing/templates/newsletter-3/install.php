<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
$name = 'Rounders and corners';
$description = '<img src="components/com_acymailing/templates/newsletter-3/newsletter-3.png" />';
$body = JFile::read(dirname(__FILE__).DS.'index.html');
$styles['acymailing_title'] = 'color:#8a8a8a;border-bottom:6px solid #d3d09f;';
$styles['tag_h1'] = 'margin-bottom:0;margin-top:0;font-family:Tahoma, Geneva, Kalimati, sans-serif;font-size:26px;color:#d47e7e;vertical-align:top;';
$styles['tag_h2'] = $styles['acymailing_title'];
$styles['tag_h3'] = 'color:#8a8a8a;font-weight:normal;font-size:100%;margin:0;';
$styles['tag_h6'] = 'background-color:#d3d09f;margin:0;';
$styles['color_bg'] = '#dfe6e8';