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
 * Strings for component 'auth_ldap', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   auth_ldap
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_ldap_ad_create_req'] = 'No se puede crear la nueva cuenta en el Directorio Activo. Asegúrese de que cumple todos los requisitos (conexión LDAPS, \'bind user\' con derechos adecuados, &c.)';
$string['auth_ldap_attrcreators'] = 'Lista de grupos o contextos cuyos miembros están habilitados para crear atributos. Separe los grupos con \',\'. Por ejemplo: \'cn=profesores,ou=personal,o=miorg\'';
$string['auth_ldap_attrcreators_key'] = 'Creadores de atributo';
$string['auth_ldap_auth_user_create_key'] = 'Crear usuarios externamente';
$string['auth_ldap_bind_dn'] = 'Si quiere usar \'bind-user\' para buscar usuarios, especifíquelo aquí. Algo como \'cn=ldapuser,ou=public,o=org\'';
$string['auth_ldap_bind_dn_key'] = 'Nombre distinguido';
$string['auth_ldap_bind_pw'] = 'Contraseña para bind-user.';
$string['auth_ldap_bind_pw_key'] = 'Contraseña';
$string['auth_ldap_bind_settings'] = 'Fijar ajustes';
$string['auth_ldap_changepasswordurl_key'] = 'URL para cambio de contraseña';
$string['auth_ldap_contexts'] = 'Lista de contextos donde están localizados los usuarios. Separe contextos diferentes con \';\'. Por ejemplo: \'ou=usuarios,o=org; ou=otros,o=org\'';
$string['auth_ldap_contexts_key'] = 'Contextos';
$string['auth_ldap_create_context'] = 'Si habilita la creación de usuario con confirmación por medio de correo electrónico, especifique el contexto en el que se crean los usuarios. Este contexto debe ser diferente de otros usuarios para prevenir problemas de seguridad. No es necesario añadir este contexto a Idap_context-variable, Moodle buscará automáticamente los usuarios de este contexto.';
$string['auth_ldap_create_context_key'] = 'Contexto para nuevos usuarios';
$string['auth_ldap_create_error'] = 'Error al crear usuario en LDAP.';
$string['auth_ldap_creators'] = 'Lista de grupos cuyos miembros están autorizados para crear nuevos cursos. Pueden separarse varios grupos con \';\'. Normalmente así: \'cn=teachers,ou=staff,o=myorg\'';
$string['auth_ldap_creators_key'] = 'Creadores';
$string['auth_ldapdescription'] = 'Este método proporciona autenticación contra un servidor LDAP externo.
Si el nombre de usuario y contraseña facilitados son válidos, Moodle crea una nueva entrada para el usuario en su base de datos. Este módulo puede leer atributos de usuario desde LDAP y prerrellenar los campos requeridos en Moodle. Para las entradas sucesivas sólo se comprueba el usuario y la contraseña.';
$string['auth_ldap_expiration_desc'] = 'Seleccione No para deshabilitar comprobar si la contraseña ha caducado, o LDAP para leer el tiempo de caducidad de la contraseña directamente de LDAP.';
$string['auth_ldap_expiration_key'] = 'Expiración';
$string['auth_ldap_expiration_warning_desc'] = 'Número de días antes de que aparezca la advertencia de caducidad de la contraseña.';
$string['auth_ldap_expiration_warning_key'] = 'Advertencia de expiración';
$string['auth_ldap_expireattr_desc'] = 'Opcional: anula el atributo ldap que almacena el tiempo de caducidad de la contraseña PasswordExpirationTime';
$string['auth_ldap_expireattr_key'] = 'Atributo de expiración';
$string['auth_ldapextrafields'] = 'Estos campos son opcionales. Usted puede elegir pre-rellenar algunos campos de usuario en Moodle con información de los <strong>campos LDAP</strong> que especifique aquí. <p>Si deja estos campos en blanco, entonces no se transferirá nada desde LDAP y se usará el sistema predeterminado en Moodle.</p><p>En ambos casos, los usuarios podrán editar todos estos campos después de entrar.</p>';
$string['auth_ldap_graceattr_desc'] = 'Opcional: Anula el atributo gracelogin';
$string['auth_ldap_gracelogin_key'] = 'Atributo de entrada libre';
$string['auth_ldap_gracelogins_desc'] = 'Activar el soporte de entrada libre LDAP. Una ves que la contraseña ha caducado, el usuario puede entrar hasta que la cuenta de acceso libre llega a cero. Si se activa esta opción se mostrará un mensaje de acceso libre en el caso de que la contraseña haya caducado.';
$string['auth_ldap_gracelogins_key'] = 'Entradas libres';
$string['auth_ldap_groupecreators'] = 'Lista de grupos o contextos cuyos miembros están habilitados para crear grupos. Separe los grupos con \',\'. Por ejemplo: \'cn=profesores,ou=personal,o=miorg\'';
$string['auth_ldap_groupecreators_key'] = 'Creadores de grupo';
$string['auth_ldap_host_url'] = 'Especificar el host LDAP en forma de URL como \'ldap://ldap.myorg.com/\' o \'ldaps://ldap.myorg.com/\'';
$string['auth_ldap_host_url_key'] = 'URL  del host';
$string['auth_ldap_ldap_encoding'] = 'Especifique la codificación usada por el servidor LDAP. Muy probablemente utf-8, MS AD v2 utiliza codificación de plataforma por defecto como cp1252, cp1250, etc.';
$string['auth_ldap_ldap_encoding_key'] = 'Codificación LDAP';
$string['auth_ldap_login_settings'] = 'Ajustes de entrada';
$string['auth_ldap_memberattribute'] = 'Especificar el atributo para nombre de usuario, cuando los usuarios se integran en un grupo. Normalmente \'miembro\'';
$string['auth_ldap_memberattribute_isdn'] = 'Opcional: Anula el manejo de valores de atributos de los miembros, es 0 ó 1';
$string['auth_ldap_memberattribute_isdn_key'] = 'Atributos de miembro utilizan dn';
$string['auth_ldap_memberattribute_key'] = 'Atributo de miembro';
$string['auth_ldap_noconnect'] = 'El módulo LDAP no se puede conectar al servidor: {$a}';
$string['auth_ldap_noconnect_all'] = 'El módulo LDAP no puede conectarse a ninguno de los servidores: {$a}';
$string['auth_ldap_noextension'] = 'Advertencia: El módulo LDAP de PHP no parece estar presente. Por favor asegúrese que esté instalado y activado.';
$string['auth_ldap_no_mbstring'] = 'Necesita la extensión mbstring para crear usuarios en el Directorio Activo.';
$string['auth_ldapnotinstalled'] = 'No se puede utilizar autenticación LDAP. El módulo LDAP de PHP no está instalado.';
$string['auth_ldap_objectclass'] = 'Filtro usado para usuarios name/search. Normalmente deberá ajustarlo a algo parecido a objectClass=posixAccount. Valores por defecto para objectClass=* que devolverán todos los objetos desde LDAP.';
$string['auth_ldap_objectclass_key'] = 'Clase de objetos';
$string['auth_ldap_opt_deref'] = 'Determina cómo se manejan los alias durante la búsqueda. Seleccione uno de los siguientes valores: "No" (LDAP_DEREF_NEVER) o "Sí" (LDAP_DEREF_ALWAYS)';
$string['auth_ldap_opt_deref_key'] = 'Alias de referencia';
$string['auth_ldap_passtype'] = 'Especifique el formato de las contraseñas nuevas o cambiadas en el servidor LDAP';
$string['auth_ldap_passtype_key'] = 'Formato de contraseña';
$string['auth_ldap_passwdexpire_settings'] = 'Ajustes de caducidad de la contraseña LDAP.';
$string['auth_ldap_preventpassindb'] = 'Seleccione \'sí\' para evitar que las contraseñas se almacenen en la base de datos de Moodle.';
$string['auth_ldap_preventpassindb_key'] = 'Ocultar contraseñas';
$string['auth_ldap_search_sub'] = 'Ponga el valor <> 0 si quiere buscar usuarios desde subcontextos.';
$string['auth_ldap_search_sub_key'] = 'Buscar subcontextos';
$string['auth_ldap_server_settings'] = 'Ajustes de servidor LDAP';
$string['auth_ldap_unsupportedusertype'] = 'auth: ldap user_create() no admite el tipo de usuario seleccionado: usertype: {$a} (..aún)';
$string['auth_ldap_update_userinfo'] = 'Actualizar información del usuario (nombre, apellido, dirección..) desde LDAP a Moodle. Mire en /auth/ldap/attr_mappings.php para información de mapeado';
$string['auth_ldap_user_attribute'] = 'El atributo usado para nombrar/buscar usuarios. Normalmente \'cn\'.';
$string['auth_ldap_user_attribute_key'] = 'Atributo de usuario';
$string['auth_ldap_user_exists'] = 'Ya existe ese nombre de usuario LDAP';
$string['auth_ldap_user_settings'] = 'Ajustes de búsqueda de usuario';
$string['auth_ldap_user_type'] = 'Seleccione cómo se almacenarán los usuarios en LDAP. Este ajuste también especifica cómo funcionarán la caducidad del acceso, los accesos libres y la creación de usuarios.';
$string['auth_ldap_user_type_key'] = 'Tipo de usuario';
$string['auth_ldap_usertypeundefined'] = 'config.user_type no está definido o la función ldap_expirationtime2unix no admite el tipo seleccionado';
$string['auth_ldap_usertypeundefined2'] = 'config.user_type no está definido o la función ldap_unixi2expirationtime no admite el tipo seleccionado';
$string['auth_ldap_version'] = 'La versión del protocolo LDAP que su servidor está utilizando.';
$string['auth_ldap_version_key'] = 'Versión';
$string['auth_ntlmsso'] = 'NTLM SSO';
$string['auth_ntlmsso_enabled'] = 'Seleccione Sí para intentar Single Sign On con el dominio NTLM. <strong>Nota:</strong> esto requiere un ajuste adicional en el servidor web para trabajar; vea <a href="http://docs.moodle.org/en/NTLM_authentication">http://docs.moodle.org/en/NTLM_authentication</a>';
$string['auth_ntlmsso_enabled_key'] = 'Habilitar';
$string['auth_ntlmsso_subnet'] = 'Si se selecciona, sólo intentará el SSO con clientes de esta sub-red. Formato: xxx.xxx.xxx.xxx/bitmask';
$string['auth_ntlmsso_subnet_key'] = 'Sub-red';
$string['ntlmsso_attempting'] = 'Intentando Single Sign On vía NTLM...';
$string['ntlmsso_failed'] = 'Falló el acceso automático; intente con la página de acceso normal...';
$string['ntlmsso_isdisabled'] = 'NTLM SSO está desactivado.';
$string['pluginname'] = 'Usar un servidor LDAP';
