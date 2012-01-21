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
 * Strings for component 'chat', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   chat
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['ajax'] = 'Versión usando Ajax';
$string['autoscroll'] = 'Desplazamiento automático';
$string['beep'] = 'beep';
$string['cantlogin'] = 'No se pudo ingresar en la sala de chat';
$string['chat:chat'] = 'Charlar';
$string['chat:deletelog'] = 'Eliminar registros de chat';
$string['chat:exportparticipatedsession'] = 'Exportar participaciones en sesión de chat';
$string['chat:exportsession'] = 'Exportar sesión de chat';
$string['chatintro'] = 'Texto introductorio';
$string['chatname'] = 'Nombre de la sala';
$string['chat:readlog'] = 'Leer registros de chat';
$string['chatreport'] = 'Sesiones';
$string['chat:talk'] = 'Hablar en un chat';
$string['chattime'] = 'Próxima cita';
$string['configmethod'] = 'El método normal de chat implica que los usuarios contactan con el servidor de forma regular para llevar a cabo las actualizaciones. No requiere configuración y funciona en cualquier parte, pero puede ocasionar una sobrecarga en el servidor cuando hay muchos usuarios simultáneamente en la sala. Utilizar un \'daemon\' (i.e., proceso de ejecución independiente en segundo plano) en el servidor requiere el acceso shell (de intérprete de comandos) a Unix, pero resulta en un ambiente de chat más rápido y escalable.';
$string['confignormalupdatemode'] = 'Las actualizaciones de la sala de chat normalmente son servidas eficientemente utilizando la característica de HTTP 1.1 <em>Keep-Alive</em>, pero esto resulta una tarea bastante pesada para el servidor. Un método más avanzado consiste en utilizar la estrategia <em>Stream</em> para enviar actualizaciones a los usuarios. Al utilizar <em>Stream</em> se consigue un escalamiento mucho mejor (es similar al método chatd), pero es posible que su servidor no tenga disponible esta opción.';
$string['configoldping'] = '¿Después de cuánto tiempo de inactividad debemos considerar que el usuario se retiró?';
$string['configrefreshroom'] = '¿Cada cuántos segundos se debe actualizar la página del chat?
Un valor bajo permitirá una conversación más ágil, pero puede ser mucha carga para el servidor cuando hay mucha gente en la sala';
$string['configrefreshuserlist'] = '¿Cada cuántos segundos se debe actualizar la lista de usuarios presentes en el chat?';
$string['configserverhost'] = '\'Hostname\' (nombre de anfitrión) del ordenador en el que reside el \'daemon\' (proceso de ejecución independiente) del servidor';
$string['configserverip'] = 'Dirección IP numérica equivalente al \'hostname\' (nombre de anfitrión)';
$string['configservermax'] = 'Número máximo de usuarios';
$string['configserverport'] = 'Puerto que usa el \'daemon\' en el servidor';
$string['currentchats'] = 'Salas activas';
$string['currentusers'] = 'Usuarios';
$string['deletesession'] = 'Borrar esta sesión';
$string['deletesessionsure'] = '¿Está seguro de que desea borrar esta sesión?';
$string['donotusechattime'] = 'No publicar horas de chat';
$string['enterchat'] = 'Entrar a la sala';
$string['errornousers'] = '¡No puedo encontrar usuarios!';
$string['explaingeneralconfig'] = 'Estos ajustes están <strong>siempre</strong> en funcionamiento';
$string['explainmethoddaemon'] = 'Estos ajustes actúan <strong>solamente</strong> si ha seleccionado "Daemon servidor de chat" en chat_method';
$string['explainmethodnormal'] = 'Estos ajustes actúan <strong>solamente</strong> si ha seleccionado "Método normal" en chat_method';
$string['generalconfig'] = 'Configuración general';
$string['idle'] = 'Inactivo';
$string['inputarea'] = 'Área de entrada';
$string['invalidid'] = 'No se encontró esa sala de chat';
$string['list_all_sessions'] = 'Listar todas las sesiones.';
$string['list_complete_sessions'] = 'Listar sólo las sesiones completas.';
$string['listing_all_sessions'] = 'Listando todas las sesiones.';
$string['messagebeepseveryone'] = '{$a} envía un beep a todos';
$string['messagebeepsyou'] = '{$a} le acaba de enviar un beep';
$string['messageenter'] = '{$a} entró a la sala';
$string['messageexit'] = '{$a} salió de la sala';
$string['messages'] = 'Mensajes';
$string['method'] = 'Método de chat';
$string['methoddaemon'] = '\'Daemon\' del servidor de chat';
$string['methodnormal'] = 'Método normal';
$string['modulename'] = 'Chat';
$string['modulenameplural'] = 'Chats';
$string['neverdeletemessages'] = 'Nunca borrar mensajes';
$string['nextsession'] = 'Próxima sesión programada';
$string['nochat'] = 'No se encontró chat';
$string['no_complete_sessions_found'] = 'No se han encontrado sesiones completas.';
$string['noguests'] = 'El chat no está abierto a invitados';
$string['nomessages'] = 'Aún no hay mensajes';
$string['normalkeepalive'] = 'KeepAlive';
$string['normalstream'] = 'Stream';
$string['noscheduledsession'] = 'No hay sesión programada';
$string['notallowenter'] = 'No tiene permiso para entrar en la sala de chat';
$string['notlogged'] = 'No se ha autentificado';
$string['oldping'] = 'Desconectar tiempo de espera';
$string['pastchats'] = 'Sesiones de chat pasadas';
$string['pluginadministration'] = 'Administración del chat';
$string['pluginname'] = 'Chat';
$string['refreshroom'] = 'Refrescar sala';
$string['refreshuserlist'] = 'Refrescar lista de usuarios';
$string['removemessages'] = 'Eliminar todos los mensajes';
$string['repeatdaily'] = 'A la misma hora todos los días';
$string['repeatnone'] = 'Sin repeticiones, publicar sólo la hora especificada';
$string['repeattimes'] = 'Repetir sesiones';
$string['repeatweekly'] = 'A la misma hora todas las semanas';
$string['saidto'] = '<b>dicho a</b>';
$string['savemessages'] = 'Guardar sesiones pasadas';
$string['seesession'] = 'Ver esta sesión';
$string['send'] = 'Enviar';
$string['sending'] = 'Enviando';
$string['serverhost'] = 'Nombre del servidor';
$string['serverip'] = 'IP del servidor';
$string['servermax'] = 'Número máximo de usuarios';
$string['serverport'] = 'Puerto del servidor';
$string['sessions'] = 'Sesiones';
$string['strftimemessage'] = '%H:%M';
$string['studentseereports'] = 'Todos pueden ver las sesiones pasadas';
$string['talk'] = 'Charla';
$string['updatemethod'] = 'Actualizar método';
$string['updaterate'] = 'Tasa de Actualización:';
$string['userlist'] = 'Lista de usuarios';
$string['usingchat_help'] = '<p>Este módulo contiene algunas características para chatear de forma más agradable.</p>

<dl>
<dt><b>Emoticonos</b></dt>
<dd>Todos los emoticonos que se pueden escribir en cualquier lugar de Moodle
también se pueden colocar aquí. Por ejemplo: :-) = <img alt="" src="pix/s/smiley.gif" />  </dd>

<dt><b>Enlaces</b></dt>
<dd>Las direcciones de Internet se convertirán automáticamente en enlaces.</dd>

<dt><b>Emociones</b></dt>
<dd>Puede iniciar un línea con "/me" o ":" para mostrar una emoción. Por ejemplo,
si su nombre es Ana y escribe ": ríe" todos verán "Ana ríe"</dd>

<dt><b>Beeps</b></dt>
<dd>Puede enviar un pitido a otra persona haciendo clic en el enlace "beep"
junto al nombre.</dd>

<dt><b>HTML</b></dt>
<dd>Si sabe un poco de lenguaje HTML puede utilizarlo en su texto para insertar
imágenes, ejecutar sonidos o formatear texto, entre otras.
</dd>
</dl>';
$string['viewreport'] = 'Ver las sesiones anteriores';
