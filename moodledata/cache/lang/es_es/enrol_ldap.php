<?php $this->cache['es_es']['enrol_ldap'] = array (
  'assignrole' => 'Assigning role \'{$a->role_shortname}\' to user \'{$a->user_username}\' into course \'{$a->course_shortname}\' (id {$a->course_id})',
  'assignrolefailed' => 'Failed to assign role \'{$a->role_shortname}\' to user \'{$a->user_username}\' into course \'{$a->course_shortname}\' (id {$a->course_id})
',
  'autocreate' => 'Los cursos pueden crearse automáticamente si existen matriculaciones en un curso que aún no existe en Moodle.',
  'autocreate_key' => 'Auto create',
  'autocreation_settings' => 'Ajustes para la creación automática de cursos',
  'bind_dn' => 'Si desea usar \'bind-user\' para buscar usuarios, especifíquelo aquí. Algo como \'cn=ldapuser,ou=public,o=org\'',
  'bind_dn_key' => 'Bind user distinguished name',
  'bind_pw' => 'Contraseña para \'bind-user\'.',
  'bind_pw_key' => 'Password',
  'bind_settings' => 'Bind settings',
  'cannotcreatecourse' => 'Cannot create course: missing required data from the LDAP record!',
  'category' => 'Categoría para cursos auto-creados.',
  'category_key' => 'Category',
  'contexts' => 'Contextos LDAP',
  'couldnotfinduser' => 'Could not find user \'{$a}\', skipping
',
  'coursenotexistskip' => 'Course \'{$a}\' does not exist and autocreation disabled, skipping
',
  'course_fullname' => 'Opcional: campo LDAP del que conseguir el nombre completo.',
  'course_fullname_key' => 'Full name',
  'course_idnumber' => 'Mapa del identificador único en LDAP, normalmente  <em>cn</em> or <em>uid</em>. Se recomienda bloquear el valor si se está utilizando la creación automática del curso.',
  'course_idnumber_key' => 'ID number',
  'course_search_sub' => 'Search group memberships from subcontexts',
  'course_search_sub_key' => 'Search subcontexts',
  'course_settings' => 'Ajustes de matriculación de Curso',
  'course_shortname' => 'Opcional: campo LDAP del que conseguir el nombre corto.',
  'course_shortname_key' => 'Short name',
  'course_summary' => 'Opcional: campo LDAP del que conseguir el sumario.',
  'course_summary_key' => 'Summary',
  'createcourseextid' => 'CREATE User enrolled to a nonexistant course \'{$a->courseextid}\'',
  'createnotcourseextid' => 'User enrolled to a nonexistant course \'{$a->courseextid}\'',
  'creatingcourse' => 'Creating course \'{$a}\'...',
  'editlock' => 'Bloquear valor',
  'emptyenrolment' => 'Empty enrolment for role \'{$a->role_shortname}\' in course \'{$a->course_shortname}\'
',
  'enrolname' => 'LDAP',
  'enroluser' => 'Enrol user \'{$a->user_username}\' into course \'{$a->course_shortname}\' (id {$a->course_id})',
  'enroluserenable' => 'Enabled enrolment for user \'{$a->user_username}\' in course \'{$a->course_shortname}\' (id {$a->course_id})',
  'explodegroupusertypenotsupported' => 'ldap_explode_group() does not support selected user type: {$a}
',
  'extcourseidinvalid' => 'The course external id is invalid!',
  'extremovedsuspend' => 'Disabled enrolment for user \'{$a->user_username}\' in course \'{$a->course_shortname}\' (id {$a->course_id})',
  'extremovedsuspendnoroles' => 'Disabled enrolment and removed roles for user \'{$a->user_username}\' in course \'{$a->course_shortname}\' (id {$a->course_id})',
  'extremovedunenrol' => 'Unenrol user \'{$a->user_username}\' from course \'{$a->course_shortname}\' (id {$a->course_id})',
  'failed' => 'Failed!
',
  'general_options' => 'Opciones generales',
  'group_memberofattribute' => 'Name of the attribute that specifies which groups a given user or group belongs to (e.g., memberOf, groupMembership, etc.)',
  'group_memberofattribute_key' => '\'Member of\' attribute',
  'host_url' => 'Especifique el host LDAP en formato URL, e.g.,  \'ldap://ldap.myorg.com/\'
or \'ldaps://ldap.myorg.com/\'',
  'host_url_key' => 'Host URL',
  'idnumber_attribute' => 'If the group membership contains distinguised names, specify the same attribute you have used for the user \'ID Number\' mapping in the LDAP authentication settings',
  'idnumber_attribute_key' => 'ID Number attribute',
  'ldap_encoding' => 'Specify encoding used by LDAP server. Most probably utf-8, MS AD v2 uses default platform encoding such as cp1252, cp1250, etc.',
  'ldap_encoding_key' => 'LDAP encoding',
  'ldap:manage' => 'Manage LDAP enrol instances',
  'memberattribute' => 'Atributo de miembro LDAP',
  'memberattribute_isdn' => 'If the group membership contains distinguised names, you need to specify it here. If it does, you also need to configure the remaining settings of this section',
  'memberattribute_isdn_key' => 'Member attribute uses dn',
  'nested_groups' => 'Do you want to use nested groups (groups of groups) for enrolment?',
  'nested_groups_key' => 'Nested groups',
  'nested_groups_settings' => 'Nested groups settings',
  'nosuchrole' => 'No such role: \'{$a}\'
',
  'objectclass' => 'objectClass usada para buscar cursos. Normalmente
\'posixGroup\'.',
  'objectclass_key' => 'Object class',
  'ok' => 'OK!
',
  'opt_deref' => 'If the group membership contains distinguised names, specify how aliases are handled during search. Select one of the following values: \'No\' (LDAP_DEREF_NEVER) or \'Yes\' (LDAP_DEREF_ALWAYS)',
  'opt_deref_key' => 'Dereference aliases',
  'phpldap_noextension' => '<em>The PHP LDAP module does not seem to be present. Please ensure it is installed and enabled if you want to use this enrolment plugin.</em>',
  'pluginname' => 'LDAP enrolments',
  'pluginname_desc' => '<p>Usted puede utilizar un servidor LDAP para coltrolar sus matriculaciones. Se asume que su árbol LDAP contiene grupos que apuntan a los cursos, y que cada uno de esos grupos o cursos contienen entradas de matriculación que hacen referencia a los estudiantes.</p>
<p>Se asume que los cursos están definidos como grupos en LDAP, de modo que cada grupo tiene múltiples campos de matriculación  (<em>member</em> or <em>memberUid</em>) que contienen una identificación única del usuario.</p>
<p>Para usar la matriculación LDAP, los usuarios <strong>deben</strong> tener un campo \'idnumber\' válido. Los grupos LDAP deben contener ese \'idnumber\' en los campos de membresía para que un usuario pueda matricularse en un curso. Esto normalmente funcionará bien si usted ya está usando la Autenticación LDAP.</p>
<p>Las matriculaciones se actualizarán cuando el usuario se identifica. Consulte en <em>enrol/ldap/enrol_ldap_sync.php</em>.</p>
<p>Este plugin puede también ajustarse para crear nuevos cursos de forma automática cuando aparecen nuevos grupos en LDAP.</p>',
  'pluginnotenabled' => 'Plugin not enabled!',
  'role_mapping' => '<p>For each rol that you want to assign from LDAP, you need to specify the list of contexts where the role courses\'s groups are located. Separate different contexts with \';\'.</p><p>You also need to specify the attribute your LDAP server uses to hold the members of a group. Usually \'member\' or \'memberUid\'</p>',
  'role_mapping_key' => 'Map roles from LDAP ',
  'roles' => 'Mapeo de roles',
  'server_settings' => 'Ajustes de Servidor LDAP',
  'synccourserole' => '== Synching course \'{$a->idnumber}\' for role \'{$a->role_shortname}\'
',
  'template' => 'Opcional: los cursos auto-creados pueden copiar sus ajustes a partir de un curso-plantilla.',
  'template_key' => 'Template',
  'unassignrole' => 'Unassigning role \'{$a->role_shortname}\' to user \'{$a->user_username}\' from course \'{$a->course_shortname}\' (id {$a->course_id})
',
  'unassignroleid' => 'Unassigning role id \'{$a->role_id}\' to user id \'{$a->user_id}\'
',
  'unassignrolefailed' => 'Failed to unassign role \'{$a->role_shortname}\' to user \'{$a->user_username}\' from course \'{$a->course_shortname}\' (id {$a->course_id})
',
  'updatelocal' => 'Actualizar datos locales',
  'user_attribute' => 'If the group membership contains distinguised names, specify the attribute used to name/search users. If you are using LDAP authentication, this value should match the attribute specified in the \'ID Number\' mapping in the LDAP authentication plugin',
  'user_attribute_key' => 'ID Number attribute',
  'user_contexts' => 'If the group membership contains distinguised names, specify the list of contexts where users are located. Separate different contexts with \';\'. For example: \'ou=users,o=org; ou=others,o=org\'',
  'user_contexts_key' => 'Contexts',
  'user_search_sub' => 'If the group membership contains distinguised names, specify if the search for users is done in subcontexts too',
  'user_search_sub_key' => 'Search subcontexts',
  'user_settings' => 'User lookup settings',
  'user_type' => 'If the group membership contains distinguished names, specify how users are stored in LDAP',
  'user_type_key' => 'User type',
  'version' => 'Versión del protocolo LDAP usado por el servidor.',
  'version_key' => 'Version',
);