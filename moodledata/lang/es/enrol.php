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
 * Strings for component 'enrol', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   enrol
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actenrolshhdr'] = 'Conectores disponibles de matriculación en el curso';
$string['addinstance'] = 'Agregar método';
$string['ajaxnext25'] = 'Siguientes 25...';
$string['ajaxoneuserfound'] = '1 usuario encontrado';
$string['ajaxxusersfound'] = 'Se han encontrado [usuarios]';
$string['assignnotpermitted'] = 'No tiene permiso o no puede asignar roles en este curso';
$string['configenrolplugins'] = 'Por favor, seleccione todos los conectores requeridos y colóquelos en orden adecuado.';
$string['custominstancename'] = 'Nombre del ejemplo personalizado';
$string['defaultenrol'] = 'Agregar ejemplo a cursos nuevos';
$string['defaultenrol_desc'] = 'Es posible agregar este conector a todos los cursos nuevos por defecto';
$string['deleteinstanceconfirm'] = '¿Realmente desea eliminar el ejemplo de plugin de matriculación "{$a->name}" con los usuarios matriculados {$a->users}?';
$string['durationdays'] = '%d días';
$string['enrol'] = 'Matricular';
$string['enrolcandidates'] = 'Usuarios no matriculados';
$string['enrolcandidatesmatching'] = 'Emparejar usuarios no matriculados';
$string['enrolcohort'] = 'Matricular cohorte';
$string['enrolcohortusers'] = 'Matricular usuarios';
$string['enrollednewusers'] = 'Nuevos usuarios {$a} matriculados con éxito';
$string['enrolledusers'] = 'Usuarios matriculados';
$string['enrolledusersmatching'] = 'Emparejar usuarios matriculados';
$string['enrolme'] = 'Matricularme en este curso';
$string['enrolmentinstances'] = 'Métodos de matriculación';
$string['enrolmentnew'] = 'Nueva matriculación en {$a}';
$string['enrolmentnewuser'] = '{$a->user} se ha matriculado en el curso "{$a->course}"';
$string['enrolmentoptions'] = 'Opciones de matriculación';
$string['enrolments'] = 'Matriculaciones';
$string['enrolnotpermitted'] = 'No tiene permiso o no puede realizar matriculaciones en este curso';
$string['enrolperiod'] = 'Período de vigencia de la matrícula';
$string['enroltimeend'] = 'La matriculación finaliza';
$string['enroltimestart'] = 'La matriculación comienza';
$string['enrolusage'] = 'Ejemplos / matriculaciones';
$string['enrolusers'] = 'Matricular usuarios';
$string['errajaxfailedenrol'] = 'No se ha podido matricular al usuario';
$string['errajaxsearch'] = 'Error al buscar usuarios';
$string['errorenrolcohort'] = 'Error al crear ejemplo de matriculación sync de cohorte en este curso.';
$string['errorenrolcohortusers'] = 'Error al matricular a los miembros de la cohorte en este curso.';
$string['extremovedaction'] = 'Acción de desmatriculación externa';
$string['extremovedaction_help'] = 'Seleccione una acción para llevar a cabo cuando la matriculación de los usuarios desaparece de la fuente de matriculación externa. Por favor, advierta que algunos datos y ajustes de los usuarios son purgados desde el curso durante la desmatriculación delcurso.';
$string['extremovedkeep'] = 'Mantener matriculado al usuario';
$string['extremovedsuspend'] = 'Deshabilitar la matriculación en el curso';
$string['extremovedsuspendnoroles'] = 'Deshabilitar la matriculación en el curso y eliminar los roles';
$string['extremovedunenrol'] = 'Dar de baja al usuario del curso';
$string['invalidenrolinstance'] = 'Ejemplo de matriculación no válido';
$string['invalidrole'] = 'Rol no válido';
$string['manageenrols'] = 'Gestionar plugins de matriculación';
$string['manageinstance'] = 'Gestionar';
$string['noexistingparticipants'] = 'No existen participantes';
$string['noguestaccess'] = 'Los invitados no pueden acceder a este curso, por favor intente autentificarse.';
$string['none'] = 'Ninguno';
$string['notenrollable'] = 'No se puede matricular en este curso.';
$string['notenrolledusers'] = 'Otros usuarios';
$string['otheruserdesc'] = 'Los siguientes usuarios no están matriculados en este curso pero tienen roles (heredados o asignados en el curso).';
$string['participationactive'] = 'Activo';
$string['participationstatus'] = 'Estatus';
$string['participationsuspended'] = 'Suspendido';
$string['periodend'] = 'hasta {$a}';
$string['periodstart'] = 'desde {$a}';
$string['periodstartend'] = 'desde {$a->start} hasta {$a->end}';
$string['rolefromcategory'] = '{$a->role} (Heredado de la categoría de curso)';
$string['rolefrommetacourse'] = '{$a->role} (Heredado del curso padre)';
$string['rolefromsystem'] = '{$a->role} (Asignado a nivel de sitio)';
$string['rolefromthiscourse'] = '{$a->role} (Asignado en este curso)';
$string['startdatetoday'] = 'Hoy';
$string['synced'] = 'Sincronizado';
$string['totalenrolledusers'] = '{$a} usuarios matriculados';
$string['totalotherusers'] = '{$a} otros usuarios';
$string['unassignnotpermitted'] = 'No tiene permiso para retirar roles en este curso';
$string['unenrol'] = 'Dar de baja';
$string['unenrolconfirm'] = '¿Realmente desea dar de baja al usuario "{$a->user}" del curso "{$a->course}"?';
$string['unenrolme'] = 'Darme de baja en {$a}';
$string['unenrolnotpermitted'] = 'No dispone de permiso o no puede dar de baja a este usuario de este curso.';
$string['unenrolroleusers'] = 'Dar de baja a usuarios';
$string['uninstallconfirm'] = 'Está a punto de eliminar completamente el plugin de matriculación \'{$a}\'. Esta acción eliminará completamente toda la información en la base de datos asociada con este tipo de matriculación. ¿Está SEGURO de que desea continuar?';
$string['uninstalldeletefiles'] = 'Todos los datos asociados con el plugin de matriculación \'{$a->plugin}\' han sido eliminados de la base de datos. Para completar la eliminación (y evitar que el plugin vuelva a instalarse automáticamente), debería eliminar ahora este directorio de su servidor: {$a->directory}';
$string['unknowajaxaction'] = 'Se ha solicitado una acción desconocida';
$string['unlimitedduration'] = 'Sin límite';
$string['usersearch'] = 'Buscar';
