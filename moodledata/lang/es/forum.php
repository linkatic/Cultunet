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
 * Strings for component 'forum', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   forum
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addanewdiscussion'] = 'Colocar un nuevo tema de discusión aquí';
$string['addanewquestion'] = 'Agregar una nueva pregunta';
$string['addanewtopic'] = 'Agregar un nuevo tema';
$string['advancedsearch'] = 'Búsqueda avanzada';
$string['aggregateavg'] = 'Promedio de calificaciones';
$string['aggregatecount'] = 'Número de calificaciones';
$string['aggregatecountformat'] = '{$a->count} (calificación: {$a->grade})';
$string['aggregatemax'] = 'Calificación más alta';
$string['aggregatemin'] = 'Calificación más baja';
$string['aggregatenone'] = 'No calificaciones';
$string['aggregatesum'] = 'Suma de calificaciones';
$string['ajaxrating'] = 'Habilitar calificación AJAX';
$string['allforums'] = 'Todos los foros';
$string['allowdiscussions'] = 'Permitir que cualquier {$a} abra nuevos temas';
$string['allowratings'] = 'Permitir la calificación de los mensajes';
$string['allowsallsubscribe'] = 'Este foro permite que cualquiera elija suscribirse o no';
$string['allowsdiscussions'] = 'Foro abierto a todos';
$string['allsubscribe'] = 'Suscribir a todos los foros';
$string['allunsubscribe'] = 'Dar de baja de todos los foros';
$string['alreadyfirstpost'] = 'Éste es ya el primer mensaje del debate';
$string['anyfile'] = 'Cualquier archivo';
$string['attachment'] = 'Archivo adjunto';
$string['attachmentnopost'] = 'No puede exportar archivos adjuntos sin una id de mensaje';
$string['attachments'] = 'Archivos adjuntos';
$string['blockafter'] = 'Umbral de mensajes para bloqueo';
$string['blockafter_help'] = '<p>El concepto de mensajes monitorizados es muy simple.
Consiste en impedir a los usuarios enviar mensajes después
que hayan enviado cierto número de ellos en un período determinado y,
conforme se aproximan a ese número, se les advertirá de que se
están acercando al límite permitido.</p>

<p>Si se ajusta el umbral de advertencia a cero se desactivarán
los avisos, y si se ajusta el umbral de bloqueo a cero se desactivará
el bloqueo. Si se desactiva el bloqueo, también se desactivarán las
advertencias.</p>

<p>Ninguno de estos ajustes afecta a los mensajes enviados por los profesores.</p>';
$string['blockperiod'] = 'Período de tiempo para bloqueo';
$string['blockperioddisabled'] = 'No bloquear';
$string['blockperiod_help'] = '<p>El concepto de mensajes monitorizados es muy simple.
Consiste en impedir a los usuarios enviar mensajes después
que hayan enviado cierto número de ellos en un período determinado y,
conforme se aproximan a ese número, se les advertirá de que se
están acercando al límite permitido.</p>

<p>Si se ajusta el umbral de advertencia a cero se desactivarán
los avisos, y si se ajusta el umbral de bloqueo a cero se desactivará
el bloqueo. Si se desactiva el bloqueo, también se desactivarán las
advertencias.</p>

<p>Ninguno de estos ajustes afecta a los mensajes enviados por los profesores.</p>';
$string['bynameondate'] = 'de {$a->name} - {$a->date}';
$string['cannotadd'] = 'No se ha podido agregar la discusión a este foro';
$string['cannotadddiscussion'] = 'Para agregar discusiones a este foro hay que ser miembro de un grupo.';
$string['cannotadddiscussionall'] = 'No tiene permiso para agregar un nuevo tema de discusión para todos los participantes.';
$string['cannotaddsubscriber'] = 'No se pudo agregar un suscriptor con la id {$a} a este foro.';
$string['cannotaddteacherforumto'] = 'No se ha podido convertir el ejemplo de foro de profesores a la sección 0 del curso';
$string['cannotcreatediscussion'] = 'No se pudo crear un debate nuevo';
$string['cannotcreateinstanceforteacher'] = 'No se pudo crear un nuevo ejemplo de móduno de curso para el foro de profesores';
$string['cannotdeleteforummodule'] = 'No se puede eliminar el módulo Foro.';
$string['cannotdeletepost'] = 'No puede eliminar este mensaje.';
$string['cannoteditposts'] = 'No puede eliminar los mensajes de otras personas.';
$string['cannotfinddiscussion'] = 'No se ha podido encontrar el debate en este foro';
$string['cannotfindfirstpost'] = 'No se pudo encontrar el primer mensaje en este foro';
$string['cannotfindorcreateforum'] = 'No se pudo encontrar o crear un foro principal de noticias en este sitio';
$string['cannotfindparentpost'] = 'No se pudo encontrar la categoría padre del mensaje {$a}';
$string['cannotmovefromsingleforum'] = 'No se puede mover un debate desde un foro de debate simple';
$string['cannotmovenotvisible'] = 'Foro no visible';
$string['cannotmovetonotexist'] = 'No puede mover nada a ese foro, no existe';
$string['cannotmovetonotfound'] = 'Foro de destino no encontrado en este curso.';
$string['cannotpurgecachedrss'] = 'No se han podido purgar los canales RSS en caché en los foros de partida y de destino: compruebe sus permisos';
$string['cannotremovesubscriber'] = 'No se pudo eliminar al suscriptor con id {$a} de este foro.';
$string['cannotreply'] = 'No puede replicar a este mensaje';
$string['cannotsplit'] = 'Los debates de este foro no pueden dividirse';
$string['cannotsubscribe'] = 'Lo sentimos, debe ser un miembro de un grupo para suscribirse.';
$string['cannottrack'] = 'No se pudo parar de rastrear ese foro';
$string['cannotunsubscribe'] = 'No se pudo darle de baja en ese foro';
$string['cannotupdatepost'] = 'No puede actualizar este mensaje';
$string['cannotviewpostyet'] = 'No puede leer las preguntas de otros estudiantes en esta discusión porque usted aún no ha enviado mensajes';
$string['cleanreadtime'] = 'Hora para marcar mensajes antiguos como leídos';
$string['completiondiscussions'] = 'El usuario debe crear discusiones:';
$string['completiondiscussionsgroup'] = 'Requerir discusiones';
$string['completiondiscussionshelp'] = 'se requieren discusiones para completar';
$string['completionposts'] = 'El usuario debe enviar discusiones o réplicas:';
$string['completionpostsgroup'] = 'Requerir mensajes';
$string['completionpostshelp'] = 'se requieren discusiones o réplicas para completar';
$string['completionreplies'] = 'El usuario debe enviar réplicas:';
$string['completionrepliesgroup'] = 'Requerir réplicas';
$string['completionreplieshelp'] = 'se requieren réplicas para completar';
$string['configajaxrating'] = 'La calificación AJAX mejora las calificaciones de los foros. Si se habilita, los usuarios pueden calificar los mensajes casi de modo instantáneo sin necesitar ir al final de la página y hacer clic en el botón \'Enviar mis últimas calificaciones\'. Este ajuste requiere asimismo que AJAX esté activado tanto en el sitio como en los perfiles de usuario.';
$string['configcleanreadtime'] = 'Hora del día para limpiar mensajes antiguos de la tabla de lectura.';
$string['configdisplaymode'] = 'Forma de visualización por defecto, si no se ha configurado ninguna.';
$string['configenablerssfeeds'] = 'Este interruptor permite canales RSS para todos los foros. Necesitará activar los canales manualmente en los ajustes de cada foro.';
$string['configenabletimedposts'] = 'Seleccione \'sí\' si desea permitir el ajuste de períodos en los que se mostrará una nueva discusión en el foro (Experimental y no probada totalmente)';
$string['configlogblocked'] = 'El cron del foro puede registrar los intentos bloqueados de enviar emails a los usuarios con el email deshabilitado.';
$string['configlongpost'] = 'Cualquier mensaje que exceda esta extensión (sin incluir código HTML) se considera largo.';
$string['configmanydiscussions'] = 'Máximo número de temas mostrados en una página de foro.';
$string['configmaxattachments'] = 'Máximo número de archivos adjuntos que se permiten por mensaje.';
$string['configmaxbytes'] = 'Tamaño máximo por defecto para los archivos adjuntos a los mensajes de los foros en este sitio (sujeto a los límites del curso y otras configuraciones del servidor)';
$string['configoldpostdays'] = 'Número de días para que un mensaje se considere leído.';
$string['configreplytouser'] = 'Cuando un mensaje del foro es enviado por correo electrónico, ¿debería contener la dirección del usuario de modo que los receptores pudieran responderle personalmente en lugar de hacerlo en el foro? Incluso aunque se seleccione \'Sí\', los usuarios pueden elegir en su perfil que su dirección de correo electrónico se mantenga en secreto.';
$string['configshortpost'] = 'Cualquier mensaje que no alcance esta extensión (sin incluir código HTML) se considera corto.';
$string['configtrackreadposts'] = 'Seleccione \'Sí\' si desea rastrear leído/no leído para cada usuario.';
$string['configusermarksread'] = 'Si elige \'sí\' el usuario debe marcar manualmente un mensaje como leído. Si \'no\', cuando el mensaje sea visto se marcará como leído.';
$string['couldnotadd'] = 'No se puede colocar su mensaje debido a un problema desconocido.';
$string['couldnotdeleteratings'] = 'Lo sentimos, no podemos borrar este mensaje debido a que ha sido calificado.';
$string['couldnotdeletereplies'] = 'Lo sentimos, no podemos borrar este mensaje debido a que tiene respuestas.';
$string['couldnotupdate'] = 'No se ha podido actualizar su mensaje debido a un error desconocido.';
$string['delete'] = 'Borrar';
$string['deleteddiscussion'] = 'El tema se ha borrado';
$string['deletedpost'] = 'El mensaje se ha borrado';
$string['deletedposts'] = 'Los mensajes han sido borrados';
$string['deletesure'] = '¿Está seguro de que desea borrar este mensaje?';
$string['deletesureplural'] = '¿Está seguro de que desea borrar este mensaje y todas las respuestas? ({$a} mensajes)';
$string['digestmailheader'] = 'Éste es su resumen diario por correo de los nuevos mensajes de los foros de {$a->sitename}. Si desea cambiar sus preferencias de foro por correo, hágalo en {$a->userprefs}.';
$string['digestmailprefs'] = 'su perfil de usuario';
$string['digestmailsubject'] = 'Resumen diario del foro de {$a}';
$string['digestsentusers'] = 'Resúmenes de correo enviados con éxito a {$a} usuarios.';
$string['disallowsubscribe'] = 'No se permiten suscripciones';
$string['disallowsubscribeteacher'] = 'No se permiten suscripciones (excepto para profesores)';
$string['discussion'] = 'Tema';
$string['discussionmoved'] = 'Este tema se ha movido a \'{$a}\'.';
$string['discussionmovedpost'] = 'Esta discusión ha sido trasladada a <a href="{$a->discusshref}">aquí</a> en el foro <a href="{$a->forumhref}">{$a->forumname}</a>';
$string['discussionname'] = 'Denominación';
$string['discussions'] = 'Temas';
$string['discussionsstartedby'] = 'Tema comenzado por {$a}';
$string['discussionsstartedbyrecent'] = 'Tema recientemente comenzado por {$a}';
$string['discussthistopic'] = 'Ver mensajes';
$string['displayend'] = 'Mostrar final';
$string['displayend_help'] = '<p>Puede elegir si los mensajes del foro se muestran a partir de una fecha determinada, expiran en una fecha concreta o son visibles sólo a lo largo de un determinado período.</p>

<p>Deje sin seleccionar la(s) opcion(es) de deshabilitar para activar las fechas inicial y final en que son visibles los mensajes.</p>

<p>Note que los usuarios con acceso de Administrador verán los mensajes antes de su fecha de aparición y después de su fecha de expiración.</p>';
$string['displaymode'] = 'Mostrar modo';
$string['displayperiod'] = 'Mostrar período';
$string['displaystart'] = 'Mostrar inicio';
$string['displaystart_help'] = '<p>Puede elegir si los mensajes del foro se muestran a partir de una fecha determinada, expiran en una fecha concreta o son visibles sólo a lo largo de un determinado período.</p>

<p>Deje sin seleccionar la(s) opcion(es) de deshabilitar para activar las fechas inicial y final en que son visibles los mensajes.</p>

<p>Note que los usuarios con acceso de Administrador verán los mensajes antes de su fecha de aparición y después de su fecha de expiración.</p>';
$string['eachuserforum'] = 'Cada persona plantea un tema';
$string['edit'] = 'Editar';
$string['editedby'] = 'Editado por {$a->name} - {$a->date}';
$string['editing'] = 'Editando';
$string['emptymessage'] = 'Algo está mal con su mensaje. Tal vez tenga algún campo en blanco o el archivo adjunto, si lo hay, es demasiado grande. Sus cambios NO se han guardado.';
$string['errorwhiledelete'] = 'Ha ocurrido un error al eliminar el registro';
$string['everyonecanchoose'] = 'Todos pueden suscribirse';
$string['everyonecannowchoose'] = 'Ahora cualquiera puede elegir si se suscribe';
$string['everyoneisnowsubscribed'] = 'Ahora todos están suscritos a este foro';
$string['everyoneissubscribed'] = 'Todos están suscritos a este foro';
$string['existingsubscribers'] = 'Suscriptores existentes';
$string['forcessubscribe'] = 'Este foro fuerza la suscripción de todos';
$string['forum'] = 'Foro';
$string['forum:addnews'] = 'Agregar noticias';
$string['forumauthorhidden'] = 'Autor (oculto)';
$string['forumblockingalmosttoomanyposts'] = 'Usted se está aproximando al límite permitido de mensajes. Ha enviado {$a->numposts} mensajes en el último {$a->blockperiod} y el límite está en {$a->blockafter} mensajes.';
$string['forumbodyhidden'] = 'Usted no puede ver este mensaje, probablemente debido a que aún no ha enviado mensajes a esta discusión.';
$string['forum:createattachment'] = 'Crear archivos adjuntos';
$string['forum:deleteanypost'] = 'Eliminar cualquier mensaje (en cualquier momento)';
$string['forum:deleteownpost'] = 'Eliminar mensajes propios (antes de la fecha límite)';
$string['forum:editanypost'] = 'Editar cualquier mensaje';
$string['forum:exportdiscussion'] = 'Exportar discusión completa';
$string['forum:exportownpost'] = 'Exportar mensaje propio';
$string['forum:exportpost'] = 'Exportar mensaje';
$string['forum:initialsubscriptions'] = 'Suscripción inicial';
$string['forumintro'] = 'Introducción';
$string['forum:managesubscriptions'] = 'Gestionar suscripciones';
$string['forum:movediscussions'] = 'Trasladar discusiones';
$string['forumname'] = 'Nombre del foro';
$string['forumposts'] = 'Mensajes';
$string['forum:rate'] = 'Calificar mensajes';
$string['forum:replynews'] = 'Responder a noticias';
$string['forum:replypost'] = 'Replicar a mensajes';
$string['forums'] = 'Foros';
$string['forum:splitdiscussions'] = 'Dividir discusiones';
$string['forum:startdiscussion'] = 'Comenzar nuevas discusiones';
$string['forumsubjecthidden'] = 'Tema (oculto)';
$string['forum:throttlingapplies'] = 'Se aplica regulación';
$string['forumtracked'] = 'Se están rastreando los mensajes no leídos';
$string['forumtrackednot'] = 'Los mensajes no leídos no se están rastreando l';
$string['forumtype'] = 'Tipo de foro';
$string['forumtype_help'] = '<P>Hay varios tipos diferentes de foros entre los que elegir:

<P><B>Un debate sencillo.</B> Es simplemente un intercambio de ideas sobre un
solo tema, todo en un página. Útil para debates cortos y muy concretos.

<P><B>El foro Normal, para uso general.</B> Es un foro abierto donde cualquiera
puede empezar un nuevo tema de debate cuando quiera. Este es el foro más adecuado
para uso general.

<P><B>Cada persona inicia un debate.</B> Cada persona puede plantear un nuevo tema
de debate (y todos pueden responder). Esta modalidad es útil cuando usted quiere
que cada estudiante empiece una discusión sobre, digamos, sus reflexiones sobre
el tema de la semana, y que todos los demás le respondan.

<P>(Habrá más modalidades en futuras versiones de Moodle)';
$string['forum:viewanyrating'] = 'Ver cualquier calificación';
$string['forum:viewdiscussion'] = 'Ver discusiones';
$string['forum:viewhiddentimedposts'] = 'Ver mensajes programados ocultos';
$string['forum:viewqandawithoutposting'] = 'Ver siempre mensajes de P y R';
$string['forum:viewrating'] = 'Ver calificaciones';
$string['forum:viewsubscribers'] = 'Ver suscriptores';
$string['generalforum'] = 'Foro para uso general';
$string['generalforums'] = 'Foros generales';
$string['inforum'] = 'en {$a}';
$string['intronews'] = 'Novedades y anuncios';
$string['introsocial'] = 'Foro abierto a todos los temas';
$string['introteacher'] = 'Foro exclusivo para profesores';
$string['invalidaccess'] = 'No se ha accedido correctamente a esta página';
$string['invaliddiscussionid'] = 'El ID de la discusión es incorrecto';
$string['invalidforumid'] = 'El ID del foro es incorrecto';
$string['invalidparentpostid'] = 'La ID del mensaje padre es incorrecta';
$string['invalidpostid'] = 'ID de mensaje no válido - {$a}';
$string['invalidrate'] = 'Calificación no válida ({$a})';
$string['lastpost'] = 'Último mensaje';
$string['learningforums'] = 'Foros de aprendizaje';
$string['logblocked'] = 'Registrar emails bloqueados';
$string['longpost'] = 'Mensaje largo';
$string['mailnow'] = 'Enviar ahora';
$string['manydiscussions'] = 'Debates por página';
$string['markalldread'] = 'Marcar como leídos todos los mensajes de este debate.';
$string['markallread'] = 'Marcar como leídos todos los mensajes de este foro.';
$string['markread'] = 'Marcar como leído';
$string['markreadbutton'] = 'Marcar<br />leídos';
$string['markunread'] = 'Marcar como no leído';
$string['markunreadbutton'] = 'Marcar<br />no leídos';
$string['maxattachments'] = 'Número máximo de archivos adjuntos';
$string['maxattachmentsize'] = 'Tamaño máximo del archivo adjunto';
$string['maxattachmentsize_help'] = '<P>El tamaño de los archivos adjuntos pueden ser limitado por la persona
   que configura el foro.

<P>En ocasiones es posible subir un archivo de un tamaño mayor al especificado,
   pero en este caso no se guardará en el servidor y aparecerá un mensaje de error.';
$string['maxtimehaspassed'] = 'Lo sentimos, pero el tiempo máximo para editar ({$a}) ya venció.';
$string['message'] = 'Mensaje';
$string['messageprovider:digests'] = 'Compendios suscritos del foro';
$string['messageprovider:posts'] = 'Mensajes suscritos del foro';
$string['missingsearchterms'] = 'Los siguientes términos de búsqueda sólo tienen lugar en la marca HTML de este mensaje:';
$string['modeflatnewestfirst'] = 'Ordenar desde el más reciente';
$string['modeflatoldestfirst'] = 'Ordenar desde el más antiguo';
$string['modenested'] = 'Mostrar respuestas anidadas';
$string['modethreaded'] = 'Mostrar respuestas por rama';
$string['modulename'] = 'Foro';
$string['modulename_help'] = '<p><img alt="" src="<?php echo $CFG->wwwroot?>/mod/forum/icon.gif" />&nbsp;<b>Foro</b></p>
<div class="indent">
<p>Esta actividad tal vez sea la más importante -es aquí donde se dan la mayor parte de
   los debates-. Los foros pueden estructurarse de diferentes maneras, y pueden incluir
   la evaluación de cada mensaje por los compañeros. Los mensajes también se pueden
   ver de varias maneras, incluir mensajes adjuntos e imágenes incrustadas.</p>
<p>Al suscribirse a un foro los participantes recibirán copias de cada mensaje en su
   buzón de correo electrónico. El profesor puede forzar la suscripción a todos los
   integrantes del curso si así lo desea.</p>
</div>';
$string['modulenameplural'] = 'Foros';
$string['more'] = 'más';
$string['movedmarker'] = '(Trasladada)';
$string['movethisdiscussionto'] = 'Mover este tema a...';
$string['mustprovidediscussionorpost'] = 'Debe proporcionar una ID de discusión o mensaje para exportar';
$string['namenews'] = 'Novedades';
$string['namenews_help'] = '<p>El foro de noticias es un foro especial que se crea automáticamente en cada curso
y en la portada del sitio; se trata de un lugar para anuncios de carácter general.
Sólo se permite un foro de noticias por curso.</p>

<p>El bloque "Últimas noticias" mostrará las discusiones recientes de este foro
especial (incluso si usted le da otro nombre). Por esta razón el foro se volverá a
crear automáticamente en Moodle si usted está utilizando el bloque LatestNews.</p>';
$string['namesocial'] = 'Foro social';
$string['nameteacher'] = 'Foro de profesores';
$string['newforumposts'] = 'Nuevos mensajes';
$string['noattachments'] = 'No hay archivos adjuntos a este mensaje';
$string['nodiscussions'] = 'Aún no hay temas en este foro';
$string['nodiscussionsstartedby'] = 'Este usuario no ha comenzado ningún debate';
$string['noguestpost'] = 'Lo sentimos, los invitados no pueden escribir aquí';
$string['noguestrate'] = 'Los invitados no pueden calificar entradas.';
$string['noguestsubscribe'] = 'Lo sentimos, no se permite a los invitados suscribirse al foro o recibir mensajes por correo electrónico.';
$string['noguesttracking'] = 'Lo sentimos, los invitados no pueden ajustar opciones de rastreo.';
$string['nomorepostscontaining'] = 'No se encontraron más mensajes que contengan {$a}';
$string['nonews'] = 'Sin novedades aún';
$string['nopermissiontosubscribe'] = 'No tiene permiso para ver los suscriptores del foro';
$string['nopermissiontoview'] = 'No tiene permiso para ver este mensaje';
$string['nopostforum'] = 'Lo sentimos, no puede enviar mensajes a este foro';
$string['noposts'] = 'No hay mensajes';
$string['nopostscontaining'] = 'No se encontraron mensajes con \'{$a}\'';
$string['noquestions'] = 'Aún no hay preguntas en este foro';
$string['norate'] = 'No se permite calificar elementos';
$string['noratinggiven'] = 'No se ha dado calificación';
$string['noresult'] = 'No hay calificaciones de este mensaje: {$a}';
$string['nosubscribers'] = 'Nadie se ha suscrito aún a este foro';
$string['notexists'] = 'El debate ya no existe';
$string['nothingnew'] = 'Nada nuevo para {$a}';
$string['notingroup'] = 'Lo sentimos, pero debe formar parte del grupo para poder ver este foro.';
$string['notinstalled'] = 'El módulo foro no está instalado';
$string['notpartofdiscussion'] = 'Este mensaje no es parte de ningún debate';
$string['notrackforum'] = 'No rastrear mensajes no leídos';
$string['noviewanyrate'] = 'Sólo puede mirar los resultados de sus mensajes';
$string['noviewdiscussionspermission'] = 'No dispone de permiso para ver las discusiones de este foro';
$string['noviewrate'] = 'No puede ver las calificaciones de los mensajes';
$string['nowallsubscribed'] = 'Todos los foros en {$a} están suscritos.';
$string['nowallunsubscribed'] = 'Todos los foros en {$a} están dados de baja.';
$string['nownotsubscribed'] = '{$a->name} no recibirá copias de \'{$a->forum}\' por correo.';
$string['nownottracking'] = '{$a->name} ya no está rastreando \'{$a->forum}\'.';
$string['nowsubscribed'] = '{$a->name} recibirá copias de \'{$a->forum}\' por correo.';
$string['nowtracking'] = '{$a->name} está rastreando \'{$a->forum}\' en este momento.';
$string['numposts'] = '{$a} mensajes';
$string['olderdiscussions'] = 'Mensajes anteriores';
$string['oldertopics'] = 'Temas antiguos';
$string['oldpostdays'] = 'Leer después de días';
$string['openmode0'] = 'No se pueden colocar temas ni respuestas';
$string['openmode1'] = 'No se pueden colocar temas, sólo respuestas';
$string['openmode2'] = 'Permitir nuevos temas y respuestas';
$string['overviewnumpostssince'] = '{$a} mensajes desde la última entrada';
$string['overviewnumunread'] = '{$a} totales no leídos';
$string['parent'] = 'Mostrar mensaje anterior';
$string['parentofthispost'] = 'Anterior a este mensaje';
$string['pluginadministration'] = 'Administración del foro';
$string['pluginname'] = 'Foro';
$string['postadded'] = 'Su mensaje se ha colocado con éxito.<P>Tiene {$a} para hacer cualquier cambio.';
$string['postaddedsuccess'] = 'Su mensaje se ha agregado con éxito.';
$string['postaddedtimeleft'] = 'Dispone de {$a} para editarlo si quiere hacer cualquier cambio.';
$string['postincontext'] = 'Ver el mensaje en su contexto';
$string['postmailinfo'] = 'Esta es una copia del mensaje publicado en {$a}.

Para responder a este mensaje diríjase a:';
$string['postmailnow'] = '<p>Este mensaje será enviado inmediatamente a todos los suscritos al foro.</p>';
$string['postrating1'] = 'Muy individualista';
$string['postrating2'] = 'Término medio';
$string['postrating3'] = 'Muy comunicativo';
$string['posts'] = 'Mensajes';
$string['posttoforum'] = 'Enviar al foro';
$string['postupdated'] = 'Su mensaje se ha actualizado';
$string['potentialsubscribers'] = 'Suscriptores potenciales';
$string['processingdigest'] = 'Procesando el resumen por correo para el usuario {$a}';
$string['processingpost'] = 'Procesando {$a}';
$string['prune'] = 'Partir';
$string['prunedpost'] = 'Se ha creado un nuevo debate a partir de ese mensaje';
$string['pruneheading'] = 'Partir el mensaje y pasar a una nueva discusión';
$string['qandaforum'] = 'Foro P y R';
$string['qandanotify'] = 'Éste es un foro de Preguntas y Respuestas. Para ver otras respuestas a estas Preguntas, debe primero enviar su Respuesta';
$string['rate'] = 'Calificar';
$string['rating'] = 'Calificación';
$string['ratingeveryone'] = 'Todos pueden calificar los mensajes';
$string['ratingno'] = 'Sin calificación';
$string['ratingonlyteachers'] = 'Sólo los {$a} pueden calificar los mensajes';
$string['ratingpublic'] = 'Los {$a} pueden ver las calificaciones de todos';
$string['ratingpublicnot'] = 'Los {$a} sólo pueden ver sus propias calificaciones';
$string['ratings'] = 'Calificaciones';
$string['ratingssaved'] = 'Calificación guardada';
$string['re'] = 'Re:';
$string['readtherest'] = 'Ver el resto del tema';
$string['replies'] = 'Respuestas';
$string['repliesmany'] = '{$a} respuestas';
$string['repliesone'] = '{$a} respuesta';
$string['reply'] = 'Responder';
$string['replyforum'] = 'Responder al foro';
$string['replytouser'] = 'Usar dirección email en réplica';
$string['resetforums'] = 'Eliminar de los foros todos los mensajes de este tipo';
$string['resetforumsall'] = 'Eliminar todos los mensajes';
$string['resetsubscriptions'] = 'Eliminar las suscripciones al foro';
$string['resettrackprefs'] = 'Eliminar todas las preferencias de rastreo de los foros';
$string['rssarticles_help'] = '<P>Esta opción le permite seleccionar el número de artículos a incluir
   en el Canal RSS.

<P>Un número comprendido entre 5 y 20 debería ser apropiado para la mayoría
   de los foros. Auméntelo si se trata de un foro muy utilizado.';
$string['rsssubscriberssdiscussions'] = 'Mostrar el canal RSS para los temas \'{$a}\'';
$string['rsssubscriberssposts'] = 'Mostrar el canal RSS para los mensajes \'{$a}\'';
$string['rsstype_help'] = '<P>Esta opción le permite habilitar los canales RSS en este foro.

<P>Puede escoger entre dos tipos de foros:
<UL>
  <LI><B>Debates:</B> Con esta opción, los datos generados incluirán nuevas
         discusiones en el foro con su mensaje inicial.
  <LI><B>Mensajes:</B> Con esta opción, los datos generados incluirán cada
         nuevo mensaje en el foro.
</UL>';
$string['search'] = 'Buscar';
$string['searchdatefrom'] = 'Los mensajes deben ser más recientes que éste';
$string['searchdateto'] = 'Los mensajes deben ser más antiguos que éste';
$string['searchforumintro'] = 'Por favor, introduzca los términos de búsqueda en uno o más de los campos siguientes:';
$string['searchforums'] = 'Buscar en foros';
$string['searchfullwords'] = 'Estas palabras deberían aparecer como palabras completas';
$string['searchnotwords'] = 'Estas palabras NO deberían incluirse';
$string['searcholderposts'] = 'Buscar mensajes antiguos...';
$string['searchphrase'] = 'En el mensaje debería aparecer esta frase exacta';
$string['searchresults'] = 'Resultado';
$string['searchsubject'] = 'Estas palabras deberían figurar en el asunto';
$string['searchuser'] = 'Este nombre debería corresponder al del autor';
$string['searchuserid'] = 'ID del autor en Moodle';
$string['searchwhichforums'] = 'Elegir en qué foros buscar';
$string['searchwords'] = 'Estas palabras pueden aparecer en cualquier lugar del mensaje';
$string['seeallposts'] = 'Ver todos los mensajes de este usuario';
$string['sendinratings'] = 'Enviar mis últimas calificaciones';
$string['shortpost'] = 'Mensaje corto';
$string['showsubscribers'] = 'Mostrar/editar suscriptores actuales';
$string['singleforum'] = 'Debate sencillo';
$string['startedby'] = 'Comenzado por';
$string['subject'] = 'Asunto';
$string['subscribe'] = 'Suscribirse a este foro';
$string['subscribeall'] = 'Suscribir a todos a este foro';
$string['subscribed'] = 'Suscrito';
$string['subscribenone'] = 'Dar de baja a todos de este foro';
$string['subscribers'] = 'Suscriptores';
$string['subscribersto'] = 'Suscriptores de \'{$a}\'';
$string['subscribestart'] = 'Deseo recibir copias de este foro por correo';
$string['subscribestop'] = 'No deseo recibir copias de este foro por correo';
$string['subscription'] = 'Suscripción';
$string['subscription_help'] = '<P>Cuando alguien se subscribe a un foro recibirá por correo electrónico una
   copia de cada mensaje enviado a ese foro (los mensajes son enviados
   <?PHP echo $CFG->maxeditingtime/60 ?> aproximadamente 30 minutos después
   de haber sido escritos).

<P>Los participantes normalmente pueden escoger si desean o no suscribirse a cada uno
   de los foros.

<P>Sin embargo, si un profesor fuerza la suscripción a un foro concreto, esta
   posibilidad de elección es anulada y todos recibirán copias por
   correo electrónico de los mensajes de ese foro.

<P>Esto es especialmente útil en el foro de Noticias y en los foros iniciados
   al principio del curso (antes de que todos sepan que pueden suscribirse por sí mismos).';
$string['subscriptionmode_help'] = '<P>Cuando alguien se subscribe a un foro recibirá por correo electrónico una
   copia de cada mensaje enviado a ese foro (los mensajes son enviados
   <?PHP echo $CFG->maxeditingtime/60 ?> aproximadamente 30 minutos después
   de haber sido escritos).

<P>Los participantes normalmente pueden escoger si desean o no suscribirse a cada uno
   de los foros.

<P>Sin embargo, si usted elige forzar la suscripción a un foro en particular,
   entonces todos los alumnos del curso quedarán automáticamente suscritos, incluso
   aquellos que se matriculen más tarde.
</P>

<P>Esto es especialmente útil en el foro de Noticias y en los foros iniciados
   al principio del curso (antes de que todos sepan que pueden suscribirse por sí mismos).
</P>

<p>Si usted selecciona la opción "Sí, inicialmente", todos los usuarios actuales
   y futuros del curso quedarán suscritos inicialmente, pero podrán darse de baja
   por sí mismos en cualquier momento. Si selecciona "Sí, siempre", no podrán
   darse de baja por sí mismos.
</p>

<p>Nótese cómo se comporta la opción "Sí, inicialmente" cuando usted actualiza
   un foro existente: cambiando de "Sí, inicialmente" a "No" no dará de baja a
   los usuarios existentes, sino que sólo afectará a los futuros usuarios del
   curso. De igual forma, cambiando más tarde a "Sí, inicialmente" no suscribirá
   al foro a los usuarios actuales del curso sino sólo a los que se matriculen
   más tarde.
</p>';
$string['subscriptions'] = 'Suscripciones';
$string['thisforumisthrottled'] = 'Este foro tiene un número limitado de mensajes para enviar en un cierto período de tiempo. El ajuste normalmente se hace en {$a->blockafter} mensaje(s) en {$a->blockperiod}';
$string['timedposts'] = 'Mensajes con asignación de tiempo';
$string['timestartenderror'] = 'La fecha final no puede ser anterior a la inicial';
$string['trackforum'] = 'Rastrear mensajes no leídos';
$string['tracking'] = 'Rastrear';
$string['trackingoff'] = 'Desconectado';
$string['trackingon'] = 'Conectado';
$string['trackingoptional'] = 'Opcional';
$string['trackingtype'] = '¿Leer rastreo de este foro?';
$string['trackingtype_help'] = '<p>Si los foros tienen activada la opción de seguimiento
   (forum_trackreadposts), los usuarios pueden realizar
   el seguimiento de mensajes leídos y no leídos
   en los foros y las discusiones. El profesor puede
   obligar a realizar cierto tipo de seguimiento
   en un foro utilizando este ajuste.</p>

<p>Existen tres posibilidades:</p>
<ul>
<li>Opcional [por defecto]: los estudiante pueden activar
    o desactivar el seguimiento a su discreción.</li>
<li>Conectado: El seguimiento siempre está activo.</li>
<li>Desconectado: El seguimiento siempre está inactivo.</li>
</ul>';
$string['unread'] = 'No leído';
$string['unreadposts'] = 'Mensajes no leídos';
$string['unreadpostsnumber'] = '{$a} mensajes no leídos';
$string['unreadpostsone'] = '1 mensaje no leído';
$string['unsubscribe'] = 'Darse de baja de este foro';
$string['unsubscribeall'] = 'Dar de baja de todos los foros';
$string['unsubscribeallconfirm'] = 'En este momento está suscrito a {$a} foros. ¿Realmente desea darse de baja de todos los foros y deshabilitar la auto-suscripción al foro?';
$string['unsubscribealldone'] = 'Se han eliminado todas sus suscripciones a los foros. Aun así, podría seguir recibiendo notificaciones de foros con suscripción forzosa. Si no desea recibir emails de este servidor, vaya por favor a su perfil y deshabilite allí la dirección email.';
$string['unsubscribeallempty'] = 'Lo sentimos, no está suscrito a ningún foro. Si no desea recibir amails de este servidor, vaya por favor a su perfil y deshabilite allí la dirección email.';
$string['unsubscribed'] = 'No suscrito';
$string['unsubscribeshort'] = 'Dar de baja';
$string['usermarksread'] = 'Marcar lectura de mensaje manual';
$string['viewalldiscussions'] = 'Ver todos los debates';
$string['warnafter'] = 'Umbral de mensajes para advertencia';
$string['warnafter_help'] = '<p>El concepto de mensajes monitorizados es muy simple.
Consiste en impedir a los usuarios enviar mensajes después
que hayan enviado cierto número de ellos en un período determinado y,
conforme se aproximan a ese número, se les advertirá de que se
están acercando al límite permitido.</p>

<p>Si se ajusta el umbral de advertencia a cero se desactivarán
los avisos, y si se ajusta el umbral de bloqueo a cero se desactivará
el bloqueo. Si se desactiva el bloqueo, también se desactivarán las
advertencias.</p>

<p>Ninguno de estos ajustes afecta a los mensajes enviados por los profesores.</p>';
$string['youratedthis'] = 'Usted calificó este mensaje';
$string['yournewquestion'] = 'Su nueva pregunta';
$string['yournewtopic'] = 'Su nuevo tema';
$string['yourreply'] = 'Su respuesta';
