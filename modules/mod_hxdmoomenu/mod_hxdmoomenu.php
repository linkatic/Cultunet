<?php
/**
 * @version 1.1 Beta $Id: mod_hxdmoomenu.php 2009-08-16 HxD $
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

require_once (dirname(__FILE__).DS.'helper.php');

$hxdmenu = modHxDMooMenuHelper::getHxDMenu($params);
require(JModuleHelper::getLayoutPath('mod_hxdmoomenu'));