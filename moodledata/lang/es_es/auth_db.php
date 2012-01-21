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
 * Strings for component 'auth_db', language 'es_es', branch 'MOODLE_20_STABLE'
 *
 * @package   auth_db
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_dbdescription'] = 'Este método utiliza una tabla de una base de datos externa para comprobar si un determinado usuario y contraseña son válidos. Si la cuenta es nueva, la información de otros campos puede también ser copiada en Moodle.';
$string['auth_dbextrafields'] = 'Estos campos son opcionales. Usted puede elegir pre-rellenar algunos campos del usuario de Moodle con información desde los <strong>campos de la base de datos externa</strong> que especifique aquí. <p>Si deja esto en blanco, se tomarán los valores por defecto</p>.<p>En ambos casos, el usuario podrá editar todos estos campos después de entrar</p>.';
$string['auth_dbfieldpass'] = 'Nombre del campo que contiene las contraseñas';
$string['auth_dbfielduser'] = 'Nombre del campo que contiene los nombres de usuario';
$string['auth_dbhost'] = 'El ordenador que hospeda el servidor de la base de datos.';
$string['auth_dbname'] = 'Nombre de la base de datos';
$string['auth_dbpass'] = 'La contraseña correspondiente al nombre de usuario anterior';
$string['auth_dbpasstype'] = 'Especifique el formato que usa el campo de contraseña. La encriptación MD5 es útil para conectar con otras aplicaciones web como PostNuke.';
$string['auth_dbtable'] = 'Nombre de la tabla en la base de datos';
$string['auth_dbtitle'] = 'Usar una base de datos externa';
$string['auth_dbtype'] = 'El tipo de base de datos (Vea la <a href="../lib/adodb/readme.htm#drivers">documentación de ADOdb</a> para obtener más detalles)';
$string['auth_dbuser'] = 'Usuario con acceso de lectura a la base de datos';
