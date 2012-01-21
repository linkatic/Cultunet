<?php
/**
 * DioneSoft Company
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the DioneSoft EULA that is bundled with
 * this package in the file GPL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@dionesoft.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.dionesoft.com/ for more information
 * or send an email to sales@dionesoft.com
 *
 * @category   DioneSoft
 * @package    Dione Magic Calendar
* @copyright Copyright (C) 2010 DioneSoft Company (http://www.dionesoft.com)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();

class JElementHeading extends JElement
{
	var	$_name = 'Heading';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value) {
			return '<div style="background: #d0d0d0; color: #404040; padding:5px; margin:0;"><strong>' . JText::_($value) . '</strong></div>';
		} else {
			return '<hr />';
		}
	}
}
?>