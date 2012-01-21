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
 * Strings for component 'glossary', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   glossary
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addcomment'] = 'Agregar comentario';
$string['addentry'] = 'Agregar entrada';
$string['addingcomment'] = 'Agregar un comentario';
$string['alias'] = 'Palabra clave';
$string['aliases'] = 'Palabra(s) clave';
$string['aliases_help'] = '<p>Cada entrada del glosario puede tener asociada una lista de palabras clave o alias.</p>

<p><b>Introduzca cada alias en una nueva línea</b> (sin separarlos con comas).</p>

<p>Las palabras y frases con alias pueden utilizarse como método alternativo para referirse
   a la entrada. Por ejemplo, si usted está utilizando el filtro de auto-enlace del glosario,
   entonces se usarán los alias (así como el nombre principal de la entrada) para decidir
   qué palabras enlazar a esta entrada.</p>';
$string['allcategories'] = 'Todas las categorías';
$string['allentries'] = 'TODAS';
$string['allowcomments'] = 'Permitir comentar las entradas';
$string['allowcomments_help'] = '<p>Es posible permitir a los alumnos que agreguen comentarios a las entradas del glosario.</p>

<p>Usted puede escoger habilitar o no esta característica.</p>

<p>Los profesores siempre pueden añadir comentarios a las entradas del glosario</p>';
$string['allowduplicatedentries'] = 'Permitir entradas duplicadas';
$string['allowduplicatedentries_help'] = '<P>Si usted habilita esta opción, un mismo conceptolas
podrá tener múltiples entradas.';
$string['allowprintview'] = 'Permitir vista impresión';
$string['allowprintview_help'] = '<p>Se puede permitir que los estudiantes usen la vista de impresión del glosario.</p>

<p>Usted puede elegir si desea activar o desactivar esta opción.</p>

<p>Los profesores pueden utilizar la vista de impresión en todo momento.</p>';
$string['allowratings'] = 'Permitir calificar las entradas';
$string['answer'] = 'Respuesta';
$string['approve'] = 'Aprobar';
$string['areyousuredelete'] = '¿Realmente desea borrarla?';
$string['areyousuredeletecomment'] = '¿Está seguro de que desea borrar este comentario?';
$string['areyousureexport'] = '¿Realmente desea exportar esta entrada a';
$string['ascending'] = '(ascendiente)';
$string['attachment'] = 'Adjunto';
$string['attachment_help'] = '<p>De forma opcional, usted puede adjuntar UN archivo desde su propio
   ordenador a cualquier entrada del glosario. Este archivo se sube
   al servidor y se almacena con su entrada.</p>

<p>Esta opción resulta útil cuando usted desea, por ejemplo, compartir
   una imagen o un documento Word.</p>

<p>El archivo en cuestión puede ser de cualquier tipo, pero se recomienda
   que se denomine utilizando los sufijos estándar de 3 letras usados en
   internet, tales como .doc para un documento Word, .jpg o .png para una
   imagen, y así sucesivamente. Esto facilitará a otras personas descargar
   y ver en sus navegadores el archivo enviado.</p>

<p>Si usted reedita una entrada y adjunta un nuevo archivo, quedarán
   reemplazados cualesquiera archivos asociados a dicha entrada.</p>

<p>Si usted reedita una entrada con un archivo adjunto y deja este espacio
   en blanco, entonces el archivo adjunto original se conservará.</p>';
$string['author'] = 'autor';
$string['authorview'] = 'Buscar por autor';
$string['back'] = 'Volver';
$string['cantinsertcat'] = 'No se puede insertar la categoría';
$string['cantinsertrec'] = 'No se puede insertar el registro';
$string['cantinsertrel'] = 'No se puede insertar la relación registro-categoría';
$string['casesensitive'] = 'Esta entrada es en Mayúsculas y minúsculas';
$string['casesensitive_help'] = '<P>Esta variable especifica si las mayúsculas y minúsculas deben coincidir
exactamente para crear el enlace automático a las entradas.</p>

<p>Por ejemplo, si se encuentra habilitada, la palabra "html" en un mensaje de
un foro no generará un hiperenlace a la entrada "HTML".</p>';
$string['cat'] = 'cat';
$string['categories'] = 'Categorías';
$string['category'] = 'Categoría';
$string['categorydeleted'] = 'Categoría eliminada';
$string['categoryview'] = 'Vista por Categoría';
$string['changeto'] = 'cambiar a {$a}';
$string['cnfallowcomments'] = 'Define si el glosario aceptará por defecto comentarios a las entradas';
$string['cnfallowdupentries'] = 'Define si el glosario aceptará por defecto entradas duplicadas';
$string['cnfapprovalstatus'] = 'Define el estado por defecto de las entradas colocadas por los estudiantes';
$string['cnfcasesensitive'] = 'Define si una entrada, cuando es hiperenlace, es sensible al uso de mayúsculas y minúsculas';
$string['cnfdefaulthook'] = 'Seleccione la visualización por defecto para presentar al glosario por primera vez';
$string['cnfdefaultmode'] = 'Seleccione el marco por defecto para presentar al glosario por primera vez';
$string['cnffullmatch'] = 'Define si una entrada, cuando es hiperenlace, debe por defecto corresponder exactamente en sus mayúsculas y minúsculas con el texto';
$string['cnflinkentry'] = 'Define si una entrada debe ser automáticamente convertida en hiperenlace';
$string['cnflinkglossaries'] = 'Define si el glosario debe ser automáticamente convertido en hiperenlace';
$string['cnfrelatedview'] = 'Seleccione el formato de visualización para el hiperenlace automático.';
$string['cnfshowgroup'] = 'Especifique si mostrar o no el grupo.';
$string['cnfsortkey'] = 'Seleccione la clave de organización por defecto.';
$string['cnfsortorder'] = 'Seleccione el orden por defecto.';
$string['cnfstudentcanpost'] = 'Define si por defecto los estudiantes pueden o no colocar entradas';
$string['comment'] = 'Comentario';
$string['commentdeleted'] = 'Se ha borrado el comentario.';
$string['comments'] = 'Comentarios';
$string['commentson'] = 'Comentarios sobre';
$string['commentupdated'] = 'Este comentario se ha actualizado';
$string['concept'] = 'Concepto';
$string['concepts'] = 'Conceptos';
$string['configenablerssfeeds'] = 'Este interruptor posibilita canales RSS para todos los glosarios. Necesitará activar los canales manualmente en los ajustes de cada glosario.';
$string['current'] = 'Actualmente ordenados {$a}';
$string['currentglossary'] = 'Glosario actual';
$string['date'] = 'fecha';
$string['dateview'] = 'Buscar por fecha';
$string['defaultapproval'] = 'Estado de aprobación por defecto';
$string['defaultapproval_help'] = '<p>Usted puede elegir si las entradas del estudiante se inicorporarán
automáticamente o si éstas deben ser previamente aprobadas por el maestro.</p>

<p>Usted puede especificar el estado por defecto cuando un estudiante somete
una nueva entrada.</p>';
$string['defaulthook'] = '\'Hook\' por defecto';
$string['defaultmode'] = 'Modo por defecto';
$string['defaultsortkey'] = 'Clave de clasificación por defecto';
$string['defaultsortorder'] = 'Orden de clasificación por defecto';
$string['definition'] = 'Definición';
$string['definitions'] = 'Definiciones';
$string['deleteentry'] = 'Borrar entrada';
$string['deletenotenrolled'] = 'Eliminar entradas de usuarios no matriculados';
$string['deletingcomment'] = 'Borrando comentario';
$string['deletingnoneemptycategory'] = 'Eliminar una categoría no eliminará las entradas. Si desea eliminar las entradas tendrá que hacerlo manualmente.';
$string['descending'] = '(descendente)';
$string['destination'] = 'Destino';
$string['destination_help'] = '<P>Puede especificar dónde desea importar las entradas:</p>
<ul>
<li><strong>Glosario actual: </strong>Las entradas importadas se añadirán
  en el glosario abierto.</li>
<li><strong>Glosario nuevo: </strong>Se creará un nuevo glosario basado en la
  información encontrada en el archivo importado y luego las entradas serán
  añadidas al mismo.</li>
</ul>';
$string['displayformat'] = 'Formato de muestra de entradas';
$string['displayformatcontinuous'] = 'Continuo sin autor';
$string['displayformatdictionary'] = 'Simple, estilo diccionario';
$string['displayformatencyclopedia'] = 'Enciclopedia';
$string['displayformatentrylist'] = 'Lista de entradas';
$string['displayformatfaq'] = 'FAQ';
$string['displayformatfullwithauthor'] = 'Completo con autor';
$string['displayformatfullwithoutauthor'] = 'Completo sin autor';
$string['displayformat_help'] = '<P>Esta variable define la manera cómo se mostrará cada entrada dentro del glosario.
Los formatos disponibles son:</p>
<blockquote>
<dl>
<dt><b>Diccionario simple</b>:</dt>
<dd>Luce como un diccionario convencional, con las entradas separadas. No se
muestra a ningún autor y los archivos adjuntos aparecen como enlaces.</dd>
<dt><b>Contínuo</b>:</dt>
<dd>Presenta las entradas una después de la otra sin otra separación que los
iconos de separación.</dd>
<dt><b>Completo con autor</b>:</dt>
<dd>Un formato semajante al de los foros, que muestra la información del autor
de cada entrada. Los archivos adjuntos aparecen como enlaces.</dd>
<dt><b>Completo sin autor</b>:</dt>
<dd>Un formato semajante al de los foros, que no muestra la información del autor
de cada entrada. Los archivos adjuntos aparecen como enlaces.</dd>
<dt><b>Enciclopedia</b>:</dt>
<dd>Semejante a \'Completo con autor\' pero en el que las imágenes adjuntas se muestran
en línea.</dd>
<dt><b>FAQ</b>:</dt>
<dd>Útil para presentar listas de Preguntas Frecuentemente Respondidas. Automáticamente
añade las palabras Pregunta y Respuesta en el concepto y la definición respectivamente.</dd>
</dl>
</blockquote>

<hr />
<p>Los administradores de Moodle pueden crear nuevos formatos siguiendo las instrucciones que
aparecen en el archivo <b>mod/glossary/formats/README.txt</b> de su instalación.</p>';
$string['displayformats'] = 'Formatos de visualización';
$string['displayformatssetup'] = 'Ajustar formatos de visualización';
$string['duplicateentry'] = 'Entrada duplicada';
$string['editalways'] = 'Editar siempre';
$string['editalways_help'] = '<P>Esta opción permite decidir si los estudiantes pueden editar sus entradas
en cualquier momento.

<P>Usted puede seleccionar:

<UL>
<LI><B>Sí:</B> Las entradas pueden editarse siempre.

<LI><B>No:</B> Las entradas son editables durante un período especificado.
</UL>';
$string['editcategories'] = 'Editar categorías';
$string['editentry'] = 'Editar entrada';
$string['editingcomment'] = 'Editando un comentario';
$string['entbypage'] = 'Entradas por página';
$string['entries'] = 'Entradas';
$string['entrieswithoutcategory'] = 'Entradas sin categoría';
$string['entry'] = 'Entrada';
$string['entryalreadyexist'] = 'Esta entrada ya existe.';
$string['entryapproved'] = 'Esta entrada ha sido aprobada';
$string['entrydeleted'] = 'Entrada borrada';
$string['entryexported'] = 'Entrada exportada con éxito';
$string['entryishidden'] = '(esta entrada está oculta)';
$string['entryleveldefaultsettings'] = 'Ajustes por defecto del nivel de entrada';
$string['entrysaved'] = 'Esta entrada ha sido guardada';
$string['entryupdated'] = 'Esta entrada ha sido actualizada';
$string['entryusedynalink'] = 'Esta entrada será enlazada automáticamente';
$string['entryusedynalink_help'] = '<P>Activando esta funcionalidad la entrada será automáticamente enlazada, en cuanto las palabras y frases
   del concepto aparezcan a lo largo del resto del curso. Esto incluye los mensajes en los foros,
   los materiales internos, resúmenes, diarios, etc.</p>

<p>Si no deseas que un texto en particular sea sea enlazado (por ejemplo, en un mensaje de los foros) entonces
   debes encerrar el texto entre las etiquetas &lt;nolink&gt; y &lt;/nolink&gt;.</p>

<p>Para poder activar esta funcionalidad, el enlace automático debe estar activado a nivel de glosario.</p>';
$string['errcannoteditothers'] = 'No puede editar entradas de otras personas.';
$string['errconceptalreadyexists'] = 'Este concepto ya existe. En este glosario no se permiten duplicados.';
$string['errdeltimeexpired'] = 'No puede eliminar esto: el tiempo ha expirado.';
$string['erredittimeexpired'] = 'El tiempo de edición de esta entrada ha expirado.';
$string['errorparsingxml'] = 'Error al analizar el fichero. Asegúrese de que la sintaxis XML es válida.';
$string['explainaddentry'] = 'Agregar una nueva entrada al glosario actual.<br />Concepto y Definición son campos obligatorios.';
$string['explainall'] = 'Muestra TODAS las entradas en una página.';
$string['explainalphabet'] = 'Navegue por el glosario usando este índice.';
$string['explainexport'] = 'Se ha generado un archivo.<br />Descárguelo y guárdelo. Podrá importarlo en cualquier momento en éste u otro curso.';
$string['explainimport'] = 'Debe especificar el archivo a importar y definir el criterio del proceso.<p>Someta su solicitud y revise los resultados.</p>';
$string['explainspecial'] = 'Muestra las entradas que no comienzan con una letra';
$string['exportedentry'] = 'Entrada exportada';
$string['exportentries'] = 'Exportar entradas';
$string['exportentriestoxml'] = 'Exportar entradas a archivo XML';
$string['exportfile'] = 'Exportar entradas a un archivo';
$string['exportglossary'] = 'Exportar glosario';
$string['exporttomainglossary'] = 'Exportar al glosario principal';
$string['filetoimport'] = 'Archivo a importar';
$string['filetoimport_help'] = '<P ALIGN=CENTER><b>Archivos a importar</b></P>

<P>Seleccione en su computadora los archivos XML que contienen las entradas a
importar.</p>';
$string['fillfields'] = 'Los campos Concepto y Definición son obligatorios.';
$string['filtername'] = 'Auto-enlace de glosario';
$string['fullmatch'] = 'Sólo enlazar palabras completas';
$string['fullmatch_help'] = '<p>Esta variable define si el enlace automático se hará con palabras completas o no.

<p>Por ejemplo, si está habilitada, la entrada "casa" no se enlazará automáticamente
con la palabra "casamiento".</p>';
$string['glossary:approve'] = 'Aprobar entradas pendientes';
$string['glossary:comment'] = 'Crear comentarios';
$string['glossary:export'] = 'Exportar entradas';
$string['glossary:exportentry'] = 'Exportar una sola entrada';
$string['glossary:exportownentry'] = 'Exportar una sola entrada de las suyas';
$string['glossary:import'] = 'Importar entradas';
$string['glossaryleveldefaultsettings'] = 'Ajustes por defecto del nivel de glosario';
$string['glossary:managecategories'] = 'Gestionar categorías';
$string['glossary:managecomments'] = 'Gestionar comentarios';
$string['glossary:manageentries'] = 'Gestionar entradas';
$string['glossary:rate'] = 'Clasificar entradas';
$string['glossarytype'] = 'Tipo de glosario';
$string['glossarytype_help'] = '<P>El sistema del glosario le permite exportar las entradas de cualquier
glosario secundario al principal del curso.</p>

<p>Para hacer esto, usted debe especificar cuál glosario es el principal.</p>

<p>Nota: Usted puede tener sólo un glosario principal por curso y sólo los
maestros pueden actuializarlo.</p>';
$string['glossary:view'] = 'Ver glosario';
$string['glossary:viewrating'] = 'Ver calificaciones';
$string['glossary:write'] = 'Crear nuevas entradas';
$string['guestnoedit'] = 'Los invitados no pueden editar glosarios';
$string['importcategories'] = 'Importar categorías';
$string['importedcategories'] = 'Categorías importadas';
$string['importedentries'] = 'Entradas importadas';
$string['importentries'] = 'Importar entradas';
$string['importentriesfromxml'] = 'Importar entradas desde archivo XML';
$string['includegroupbreaks'] = 'Incluir segmentaciones de grupo';
$string['invalidrate'] = 'Calificación del glosario no válida ({$a})';
$string['isglobal'] = '¿Este es el glosario global?';
$string['isglobal_help'] = '<P>El sistema del glosario le permite definir conceptos que podrían estar
disponibles en todo el sitio. Son los Glosarios Globales.</p>

<p>Para hacer esto, usted debe especificar cuál es el glosario global.</p>

<p>Usted puede tener tantos glosarios globales como desee, y pueden pertenecer
a cualquier curso. Todas las otras reglas también son válidas para los glosarios globales.</p>

<p>Nota: Sólo los administradores pueden crear glosarios globales.</p>';
$string['letter'] = 'letra';
$string['linkcategory'] = 'Enlazar automáticamente esta categoría';
$string['linkcategory_help'] = '<P ALIGN=CENTER><b>Categorías con enlaces automáticos</b></P>

<P>Puede especificar si desea que las categorías tengan enlaces automáticos o
no.</p>

<P>Nota: Las categorías tienen enlaces basados en la sensibilidad del caso,
como el emparejamiento.</p>';
$string['linking'] = 'Auto-enlace';
$string['mainglossary'] = 'Glosario principal';
$string['maxtimehaspassed'] = 'Lo sentimos, pero ya excedió el tiempo máximo de edición.';
$string['modulename'] = 'Glosario';
$string['modulenameplural'] = 'Glosarios';
$string['newentries'] = 'Nuevas entradas al glosario';
$string['newglossary'] = 'Glosario nuevo';
$string['newglossarycreated'] = 'Se ha creado nuevo glosario.';
$string['newglossaryentries'] = 'Nuevas entradas en el glosario:';
$string['nocomment'] = 'No se encontraron comentarios';
$string['nocomments'] = '(Entrada sin comentarios)';
$string['noconceptfound'] = 'No se encontraron conceptos o definiciones.';
$string['noentries'] = 'No se encontraron entradas en esta sección';
$string['noentry'] = 'No se econtró ninguna entrada.';
$string['nopermissiontodelcomment'] = 'No puede eliminar los comentarios de otras personas.';
$string['nopermissiontodelinglossary'] = 'No puede agregar comentarios a este glosario.';
$string['nopermissiontoviewresult'] = 'Sólo puede mirar los resultados de sus propias entradas';
$string['notcategorised'] = 'No clasificado';
$string['numberofentries'] = 'Número de entradas';
$string['onebyline'] = '(uno por línea)';
$string['pluginadministration'] = 'Administración del glosario';
$string['pluginname'] = 'Glosario';
$string['popupformat'] = 'Formato \'popup\'';
$string['printerfriendly'] = 'Versión para impresión';
$string['printviewnotallowed'] = 'No se permite vista de impresión';
$string['question'] = 'Pregunta';
$string['rate'] = 'Calificar';
$string['rating'] = 'Calificación';
$string['ratingeveryone'] = 'Todos pueden calificar las entradas';
$string['ratingno'] = 'Sin calificaciones';
$string['ratingonlyteachers'] = 'Sólo {$a} puede calificar las entradas';
$string['ratingonlywithpermissions'] = 'Sólo clasifican entradas personas con permiso';
$string['ratings'] = 'Calificaciones';
$string['ratingssaved'] = 'Calificaciones guardadas';
$string['ratingtime'] = 'Restringir las calificaciones a las entradas cuando las fechas estén en este rango:';
$string['rejectedentries'] = 'Entradas rechazadas';
$string['rejectionrpt'] = 'Informe rechazado';
$string['resetglossaries'] = 'Eliminar entradas de';
$string['resetglossariesall'] = 'Eliminar entradas de todos los glosarios';
$string['rssarticles_help'] = '<P>Esta opción le permite seleccionar el número de artículos a incluir en el Canal RSS.

<P>Un número entre 5 y 20 debería ser apropiado para la mayoría de los glosarios. Auméntelo si se trata de un glosario muy utilizado.';
$string['rsssubscriberss'] = 'Mostrar canal RSS para los conceptos \'{$a}\'';
$string['rsstype_help'] = '<P>Esta opción le permite habilitar los canales RSS en este glosario.

<P>Puede escoger entre dos tipos:

<UL>
<LI><B>Con autor:</B> Con esta opción, los datos generados incluirán el nombre del autor en cada artículo.

<LI><B>Sin autor:</B> Con esta opción, los datos generados no incluirán el nombre del autor en cada artículo.
</UL>';
$string['searchindefinition'] = '¿Buscar en conceptos y definiciones?';
$string['secondaryglossary'] = 'Glosario secundario';
$string['sendinratings'] = 'Enviar en mis últimas calificaciones';
$string['showall'] = 'Mostrar enlace \'TODAS\'';
$string['showall_help'] = '<P>Usted puede personalizar la manera en que un usuario puede buscar en un
glosario. La función de búsqueda por categoría o fecha siempre está disponible,
pero usted puede definir tres opciones más:</p>

<p><b>MOSTRAR ESPECIAL</b> Habilita la búsqueda con caracteres especiales como @, #, etc.</p>

<p><b>MOSTRAR ALFABETO</b> Habilita la búsqueda por letras iniciales.</p>

<p><b>MOSTRARO TODO</b> Habilita o no todas las características de una vez.</p>';
$string['showalphabet'] = 'Mostrar alfabeto';
$string['showalphabet_help'] = '<P>Usted puede personalizar la manera en que un usuario puede buscar en un
glosario. La función de búsqueda por categoría o fecha siempre está disponible,
pero usted puede definir tres opciones más:</p>

<p><b>MOSTRAR ESPECIAL</b> Habilita la búsqueda con caracteres especiales como @, #, etc.</p>

<p><b>MOSTRAR ALFABETO</b> Habilita la búsqueda por letras iniciales.</p>

<p><b>MOSTRARO TODO</b> Habilita o no todas las características de una vez.</p>';
$string['showspecial'] = 'Mostrar enlace \'Especial\'';
$string['showspecial_help'] = '<P>Usted puede personalizar la manera en que un usuario puede buscar en un
glosario. La función de búsqueda por categoría o fecha siempre está disponible,
pero usted puede definir tres opciones más:</p>

<p><b>MOSTRAR ESPECIAL</b> Habilita la búsqueda con caracteres especiales como @, #, etc.</p>

<p><b>MOSTRAR ALFABETO</b> Habilita la búsqueda por letras iniciales.</p>

<p><b>MOSTRARO TODO</b> Habilita o no todas las características de una vez.</p>';
$string['sortby'] = 'Ordenar por';
$string['sortbycreation'] = 'Por fecha de creación';
$string['sortbylastupdate'] = 'Por fecha de actualización';
$string['sortchronogically'] = 'Buscar cronológicamente';
$string['special'] = 'Especial';
$string['standardview'] = 'Vista Normal';
$string['studentcanpost'] = 'Los estudiantes pueden agregar entradas';
$string['totalentries'] = 'Total de entradas';
$string['usedynalink'] = 'Hiperenlace automático';
$string['usedynalink_help'] = '<P>Activando esta funcionalidad permitirá que las entradas individuales sean enlazadas automáticamente cuando
   las palabras y frases de los conceptos aparezcan en el resto del mismo curso. Esto incluye mensajes
   en los foros, materiales internos, resúmenes, diarios, etc.</p>

<p>Nota: Activar el enlazado para el glosario no activa automáticamente el enlazado para cada entrada -
   éste debe ser configurado individualmente para cada una de ellas.</p>

<p>Si no deseas que un texto en particular sea enlazado (por ejemplo, en un mensaje de los foros) entonces debes
   encerrar el texto entre las etiquetas &lt;nolink&gt; y &lt;/nolink&gt;.</p>

<p>Los nombres de las categorías también son enlazados.</p>';
$string['waitingapproval'] = 'Esperando aprobación';
$string['warningstudentcapost'] = '(Esto sólo aplica si el glosario no es el principal)';
$string['withauthor'] = 'Conceptos con autor';
$string['withoutauthor'] = 'Conceptos sin autor';
$string['writtenby'] = 'por';
$string['youarenottheauthor'] = 'Dado que usted no es el autor de este comentario, no está autorizado a modificarlo.';
