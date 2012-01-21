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
 * Strings for component 'enrol_ldap', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   enrol_ldap
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['autocreate'] = 'Los cursos pueden crearse automáticamente si existen matriculaciones en un curso que aún no existe en Moodle.';
$string['autocreation_settings'] = 'Ajustes para la creación automática de cursos';
$string['bind_dn'] = 'Si desea usar \'bind-user\' para buscar usuarios, especifíquelo aquí. Algo como \'cn=ldapuser,ou=public,o=org\'';
$string['bind_pw'] = 'Contraseña para \'bind-user\'.';
$string['category'] = 'Categoría para cursos auto-creados.';
$string['contexts'] = 'Contextos LDAP';
$string['course_fullname'] = 'Opcional: campo LDAP del que conseguir el nombre completo.';
$string['course_idnumber'] = 'Mapa del identificador único en LDAP, normalmente  <em>cn</em> or <em>uid</em>. Se recomienda bloquear el valor si se está utilizando la creación automática del curso.';
$string['course_settings'] = 'Ajustes de matriculación de Curso';
$string['course_shortname'] = 'Opcional: campo LDAP del que conseguir el nombre corto.';
$string['course_summary'] = 'Opcional: campo LDAP del que conseguir el sumario.';
$string['editlock'] = 'Bloquear valor';
$string['enrolname'] = 'LDAP';
$string['general_options'] = 'Opciones generales';
$string['host_url'] = 'Especifique el host LDAP en formato URL, e.g.,  \'ldap://ldap.myorg.com/\'
or \'ldaps://ldap.myorg.com/\'';
$string['memberattribute'] = 'Atributo de miembro LDAP';
$string['objectclass'] = 'objectClass usada para buscar cursos. Normalmente
\'posixGroup\'.';
$string['pluginname_desc'] = '<p>Usted puede utilizar un servidor LDAP para coltrolar sus matriculaciones. Se asume que su árbol LDAP contiene grupos que apuntan a los cursos, y que cada uno de esos grupos o cursos contienen entradas de matriculación que hacen referencia a los estudiantes.</p>
<p>Se asume que los cursos están definidos como grupos en LDAP, de modo que cada grupo tiene múltiples campos de matriculación  (<em>member</em> or <em>memberUid</em>) que contienen una identificación única del usuario.</p>
<p>Para usar la matriculación LDAP, los usuarios <strong>deben</strong> tener un campo \'idnumber\' válido. Los grupos LDAP deben contener ese \'idnumber\' en los campos de membresía para que un usuario pueda matricularse en un curso. Esto normalmente funcionará bien si usted ya está usando la Autenticación LDAP.</p>
<p>Las matriculaciones se actualizarán cuando el usuario se identifica. Consulte en <em>enrol/ldap/enrol_ldap_sync.php</em>.</p>
<p>Este plugin puede también ajustarse para crear nuevos cursos de forma automática cuando aparecen nuevos grupos en LDAP.</p>';
$string['roles'] = 'Mapeo de roles';
$string['server_settings'] = 'Ajustes de Servidor LDAP';
$string['template'] = 'Opcional: los cursos auto-creados pueden copiar sus ajustes a partir de un curso-plantilla.';
$string['updatelocal'] = 'Actualizar datos locales';
$string['version'] = 'Versión del protocolo LDAP usado por el servidor.';
