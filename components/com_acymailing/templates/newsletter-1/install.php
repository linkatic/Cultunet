<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
$name = 'White Shadow Red';
$description = '<img src="components/com_acymailing/templates/newsletter-1/newsletter-1.png" />';
$body = JFile::read(dirname(__FILE__).DS.'index.html');
$styles['acymailing_title'] = 'color:#8a8a8a;font-weight:normal;font-size:14px;margin:0;border-bottom:5px solid #d39f9f;';
$styles['acymailing_unsub'] = 'font-weight:bold;color:#000000;';
$styles['acymailing_readmore'] = 'color:#d39f9f;';
$styles['acymailing_online'] = 'font-weight:bold;color:#000000;';
$styles['tag_h1'] = 'margin-bottom:0;margin-top:0;font-family:Tahoma, Geneva, Kalimati, sans-serif;font-size:26px;color:#d47e7e;vertical-align:top;';
$styles['tag_h2'] = $styles['acymailing_title'];
$styles['tag_h6'] = 'background-color:#d39f9f;margin:0;';
$styles['tag_h3'] = 'color:#8a8a8a;font-weight:normal;font-size:100%;margin:0;';
$styles['color_bg'] = '#e2e8df';