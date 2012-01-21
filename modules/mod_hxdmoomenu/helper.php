<?php
/**
 * @version 1.1 Beta $Id: helper.php 2009-08-16 HxD $
 * @package    HXD MooMenu
 * @subpackage Modules
 * @link http://www.hexadesigners.com
 * @license	GNU/GPL, see LICENSE.php
 * HXD MooMenu is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * HXD MooMenu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with HXD MooMenu; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modHxDMooMenuHelper
{
	function getHxDMenu(&$params)
	{
		global $mainframe, $Itemid;

		JHTML::_( 'behavior.mootools' );

		//menu & module options
		$menu_name			= $params->get('menutype', 'mainmenu');
		$tag_id				= $params->get('tag_id', 'hxdmenu');
		$class_sfx			= $params->get('class_sfx', 'hxdmenu');
		$moduleclass_sfx	= $params->get('moduleclass_sfx', '');

		$mtype				= $params->get('menustyle', 'moomenu'); // TO DO - Suckerfish Menu, CSS Menu

		// moomenu options
		$moo_bgiframe		= $params->get('moo_bgiframe', 0);
		$moo_delay			= $params->get('moo_delay', 500);
		$moo_duration		= $params->get('moo_duration', 300);
		$moo_fps			= $params->get('moo_fps', 100);
		$moo_transition		= $params->get('moo_transition', 'Back.easeInOut');
		$moo_effects		= $params->get('moo_effects', 'height');

		$effects		= str_replace (',','\',\'',$moo_effects);

		// Generate menu code
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer( 'module' );
		$options	 = array( 'style' => "raw" );
		$module	 = JModuleHelper::getModule( 'mod_mainmenu' );
		$topnav = false; $subnav = false;

		$module->params		 = "menutype=$menu_name\nshowAllChildren=1\ntag_id=$tag_id";
		$module->params		.= "\nmoduleclass_sfx=$moduleclass_sfx\nclass_sfx=$class_sfx";
		$hxdmenu = $renderer->render( $module, $options );
		// make sure subnav is empty
		if (strlen($subnav) < 10) $subnav = false;

		$document->addStyleSheet(JURI::base() . "modules/mod_hxdmoomenu/assets/css/hxdmoomenu.css");
		$document->addScript(JURI::base() . "modules/mod_hxdmoomenu/assets/js/hxdmoomenu.js");
		$document->addScript(JURI::base() . "modules/mod_hxdmoomenu/assets/js/mootools.bgiframe.js");
		$bgiframe = ($moo_bgiframe)?"true":"false";
		echo $script = "
		<script language=\"javascript\">
	<!--
	window.addEvent('domready', function() {
		new HxDMenu(\$E('ul.menu".$class_sfx."'), {
			bgiframe: ". $bgiframe .",
			delay: ". $moo_delay .",
			animate: {
				props: ['".$effects."'],
				opts: {
					duration:". $moo_duration .",
					fps: ". $moo_fps .",
					transition: Fx.Transitions.". $moo_transition ."
				}
			}
		});
	});
	-->
	</script>
	";

		//$document->addScriptDeclaration($script);
		return $hxdmenu;
	}
}