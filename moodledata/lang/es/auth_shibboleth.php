<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_shibboleth', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   auth_shibboleth
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_shibboleth_contact_administrator'] = 'En el caso de que usted no esté asociado con las organizaciones suministradas y necesite acceso a un curso de este servidor, contacte por favor con el';
$string['auth_shibbolethdescription'] = 'Con este método puede conectarse a un servidor Shibboleth para comprobar y crear nuevas cuentas';
$string['auth_shibboleth_errormsg'] = 'Por favor, seleccione la organización a la que pertenece.';
$string['auth_shibboleth_login'] = 'Acceso Shibboleth';
$string['auth_shibboleth_login_long'] = 'Acceso a Moodle vía Shibboleth';
$string['auth_shibboleth_manual_login'] = 'Acceso manual';
$string['auth_shibboleth_select_member'] = 'Soy un miembro de...';
$string['auth_shibboleth_select_organization'] = 'Para autentificarse vía Shibboleth, seleccione por favor su organización en la lista desplegable.';
$string['auth_shib_changepasswordurl'] = 'URL para cambio de contraseña';
$string['auth_shib_convert_data'] = 'API de modificación de datos';
$string['auth_shib_convert_data_description'] = 'Puede usar este API para modificar más adelante los datos proporcionados por Shibboleth. Lea <a href="../auth/shibboleth/README.txt" target="_blank">README</a> para ver instrucciones adicionales.';
$string['auth_shib_convert_data_warning'] = 'El archivo no existe o no es legible por el proceso del servidor.';
$string['auth_shib_instructions'] = 'Utilice el <a href="{$a}">login Shibboleth</a> para acceder vía Shibboleth si su institución lo admite.<br />En caso contrario, utilice el formulario de entrada normal que aquí se muestra.';
$string['auth_shib_instructions_help'] = 'Aquí debería proporcionar a sus usuarios instrucciones personalizadas para explicar Shibboleth. Deberían aparecer en la sección de instrucciones de la página de acceso, e incluir un enlace a "<b>{$a}</b>", de modo que los usuarios de Shibboleth pudieran acceder con facilidad. Si lo deja en blanco, se utilizarán las instrucciones estándar (no las específicas de Shibboleth)';
$string['auth_shib_no_organizations_warning'] = 'Si desea usar el servicio integrado WAYF, debe proporcionar una lista con valores separados por comas de las entityIDs proveedoras de identidades, sus nombres y, opcionalmente, un iniciador de sesión.';
$string['auth_shib_only'] = 'Sólo Shibboleth';
$string['auth_shib_only_description'] = 'Marque esta opción si desea forzar la autenticación Shibboleth';
$string['auth_shib_username_description'] = 'Nombre de la variable contextual del servidor Shibboleth que se utilizará como nombre de usuario en Moodle';
$string['pluginname'] = 'Shibboleth';
$string['shib_no_attributes_error'] = 'Usted parece haber sido autentificado por Shibboleth, pero Moodle no ha recibido ningún atributo de usuario. Por favor, compruebe que su proveedor de identidad envía los atributos necesarios ({$a}) al Proveedor de Servidios en el que Moodle se está ejecutando, o informe al webmaster de este servidor.';
$string['shib_not_all_attributes_error'] = 'Moodle necesita ciertos atributos Shibboleth que no están presentes en su caso. Los atributos son: {$a}<br />Por favor, contacte con el webmaster de este servidor o con su proveedor de identidad.';
$string['shib_not_set_up_error'] = 'La autentificación Shibboleth no parece ser correcta debido a que en esta página no están presentes las variables contextuales Shibboleth. Por favor, consulte el archivo <a href="README.txt">README</a> si desea más instrucciones sobre cómo definir la autentificación Shibboleth, o contacte con el webmaster de esta instalación de Moodle.';
