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
 * Strings for component 'question', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   question
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['adminreport'] = 'Informe sobre posibles problemas en su base de datos de preguntas.';
$string['availableq'] = '¿Disponible?';
$string['badbase'] = 'Base errónea antes de **: {$a}**';
$string['broken'] = 'Éste es un enlace roto: apunta a un archivo inexistente.';
$string['byandon'] = 'por <em>{$a->user}</em> en <em>{$a->time}</em>';
$string['cannotcopybackup'] = 'No se ha podido copiar el archivo de copia de seguridad';
$string['cannotcreate'] = 'No se ha podido crear una nueva entrada en la tabla question_attempts';
$string['cannotcreatepath'] = 'No se puede crear la ruta: {$a}';
$string['cannotdeletecate'] = 'No puede eliminar la categoría porque es la categoría por defecto para este contexto.';
$string['cannotenable'] = 'El tipo de pregunta {$a} no se puede crear directamente';
$string['cannotfindcate'] = 'No se ha podido encontrar el registro de la categoría';
$string['cannotfindquestionfile'] = 'No se ha podido encontrar el archivo de preguntas en el zip';
$string['cannotgetdsfordependent'] = 'No se puede conseguir el conjunto de datos especificado para la pregunta dependiente (pregunta:{$a[0]}, datasetitem: {a[1]})';
$string['cannotgetdsforquestion'] = 'No se puede conseguir el conjunto de datos para una pregunta calculada (pregunta: {$a})';
$string['cannothidequestion'] = 'No se ha podido ocultar la pregunta';
$string['cannotimportformat'] = 'Lo sentimos, aún no está implementada la importación en este formato.';
$string['cannotinsertquestion'] = 'No se ha podido insertar una nueva pregunta';
$string['cannotinsertquestioncatecontext'] = 'No se ha podido insertar la nueva categoría de preguntas {$a->cat} illegal contextid {$a->ctx}';
$string['cannotloadquestion'] = 'No se ha podido cargar la pregunta';
$string['cannotmovequestion'] = 'No puede usar este script para trasladar preguntas que tienen archivos asociados provenientes de distintas áreas.';
$string['cannotopenforwriting'] = '{$a}';
$string['cannotpreview'] = 'No puede previsualizar estas preguntas.';
$string['cannotretrieveqcat'] = 'Se ha podido recuperar la categoría de preguntas';
$string['cannotunhidequestion'] = 'Error al descubrir la pregunta.';
$string['cannotunzip'] = 'No se ha podido descomprimir el archivo.';
$string['cannotwriteto'] = '{$a}';
$string['categorycurrent'] = 'Categoría actual';
$string['categorycurrentuse'] = 'Usar esta categoría';
$string['categorydoesnotexist'] = 'Esta categoría no existe';
$string['categorymoveto'] = 'Guardar en categoría';
$string['changepublishstatuscat'] = '<a href="{$a->caturl}">La categoría "{$a->name}"</a> en curso "{$a->coursename}" cambiará su estatus de intercambio <strong>{$a->changefrom} a {$a->changeto}</strong>.';
$string['chooseqtypetoadd'] = 'Elija un tipo de pregunta a agregar';
$string['clicktoflag'] = 'Clic para marcar esta pregunta';
$string['clicktounflag'] = 'Clic para desmarcar esta pregunta';
$string['contexterror'] = 'No debería estar aquí si no está moviendo una categoría a otro contexto.';
$string['copy'] = 'Copiar de {$a} y cambiar los enlaces.';
$string['created'] = 'Creado';
$string['createdby'] = 'Creado por';
$string['createdmodifiedheader'] = 'Creado / Último guardado';
$string['createnewquestion'] = 'Crear una nueva pregunta...';
$string['cwrqpfs'] = 'Preguntas aleatorias seleccionando preguntas de sub-categorías.';
$string['cwrqpfsinfo'] = '<p>Durante la actualización a Moodle 1.9 separaremos las categorías de pregunta en diferentes contextos. Algunas categorías y preguntas de su sitio verán su estatus de intercambio modificado. Esto es necesario en los raros casos en que una o más preguntas aleatorias se seleccionan a partir de una mezcla de categorías compartidas y no compartidas (como sucede en el caso de este sitio). Esto sucede cuando una pregunta aleatoria se ajusta para seleccionar a partir de subcategorías, y una o más subcategorías tienen diferente estatus de intercambio con la categoría padre en la que se crea la pregunta aleatoria.</p>
<p>Las siguientes categorías, de las cuales las preguntas aleatorias de las categorías superiores seleccionan las preguntas, tendrán -en la actualización a Moodle 1.9- su estatus de intercambio modificado al mismo estatus que la categoría que contiene la pregunta aleatoria. Las categorías que aparecen a continuación tendrán su estatus de intercambio modificado. Las preguntas afectadas continuarán funcionando en todos los cuestionarios existentes hasta que usted las elimine de tales cuestionarios.';
$string['cwrqpfsnoprob'] = 'No existen categorías en su sitio afectadas por la opción \'Preguntas aleatorias seleccionando preguntas de subcategorías\'.';
$string['defaultfor'] = 'Valor por defecto para {$a}';
$string['defaultinfofor'] = 'Categoría por defecto para preguntas compartidas en el contexto {$a}.';
$string['deletecoursecategorywithquestions'] = 'Hay preguntas en el banco de preguntas asociadas con esta categoría de curso. Si continúa, serán eliminadas. Quizás quiera trasladarlas primero, usando la interfaz del banco de preguntas.';
$string['disabled'] = 'Desactivado';
$string['disterror'] = 'La distribución {$a} ha causado problemas';
$string['donothing'] = 'No copie o mueva archivos ni cambie enlaces.';
$string['editcategories'] = 'Editar categorías';
$string['editcategories_help'] = '<p>En lugar de guardar todas sus preguntas en una lista, puede crear categorías para distribuirlas mejor.</p>

<p>Las categorías pueden crearse o eliminarse a voluntad. Pero:
<ul><li> Debe haber al menos una categoría en cada contexto. De este modo, usted no puede eliminar la última categoría de un contexto.</li>
<li>Cuando intente eliminar una categoría que contiene preguntas, se le pedirá que especifique otra categoría a la que trasladarlas.</li></ul></p>

<p>Usted puede ordenar sus categorías en una jerarquía de modo que resulten de fácil manejo. La edición de categorías se hace en la pestaña \'Categorías\' en el banco de preguntas.</p>

   <ul><li>En la página principal bajo la pestaña \'Categorías\' en el banco de preguntas:
   <ul><li>las flechas arriba y abajo cambian el orden en que se listan las categorías que pertenecen al mismo nivel.</li>
   <li>En la pestaña \'Categorías\' del banco de preguntas, podrá asimismo trasladar una categoría a un nuevo contexto mediante las flechas arriba y abajo.</li>
   <li>Las flechas izquierda y derecha se usan para cambiar la categoría padre de una categoría determinada.</li></ul></li>
   <li>Tal vez una forma más rápida de mover las categorías sea pulsar en el icono de edición de la pestaña \'Categorías\' del banco de preguntas y usar seguidamente la casilla de selección para seleccionar una nueva categoría padre.</li></ul></p>

<p>Vea también:</p>
<ul>
  <li><a href="help.php?module=question&amp;file=categorycontexts.html">Contextos de categorías</a></li>
  <li><a href="help.php?module=question&amp;file=permissions.html">Permisos (preguntas)</a></li>
  <li><a href="http://docs.moodle.org/en/Question_categories">Ayuda sobre categorías de preguntas en Moodle Docs</a></li>
</ul>';
$string['editingcategory'] = 'Edición de una categoría';
$string['editingquestion'] = 'Edición de una pregunta';
$string['editthiscategory'] = 'Editar esta categoría';
$string['emptyxml'] = 'Error desconocido - imsmanifest.xml vacío';
$string['enabled'] = 'Activado';
$string['erroraccessingcontext'] = 'No se puede acceder al contexto';
$string['errordeletingquestionsfromcategory'] = 'Error al eliminar preguntas de la categoría {$a}.';
$string['errorduringpost'] = 'Ha ocurrido un error durante el post-procesamiento';
$string['errorduringpre'] = 'Ha ocurrido un error durante el pre-procesamiento';
$string['errorduringproc'] = 'Ha ocurrido un error durante el procesamiento';
$string['errorduringregrade'] = 'No se ha podido recalificar la pregunta {$a->qid}, se va al estado {$a->stateid}.';
$string['errorfilecannotbecopied'] = 'Error: no se puede copiar el archivo {$a}.';
$string['errorfilecannotbemoved'] = 'Error: no se puede mover el archivo {$a}.';
$string['errorfileschanged'] = 'Los archivos de error enlazados desde preguntas han cambiado desde que se ha mostrado el formulario.';
$string['errormanualgradeoutofrange'] = 'La calificación {$a->grade} no está entre 0 y {$a->maxgrade} para la pregunta {$a->name}. La puntuación y el comentario no se han guardado.';
$string['errormovingquestions'] = 'Error al trasladar preguntas con IDs {$a}.';
$string['errorpostprocess'] = 'Ha ocurrido un error durante el post-procesamiento';
$string['errorpreprocess'] = 'Ha ocurrido un error durante el pre-procesamiento';
$string['errorprocess'] = 'Ha ocurrido un error durante el procesamiento';
$string['errorprocessingresponses'] = 'Ha ocurrido un error al procesar sus respuestas.';
$string['errorsavingcomment'] = 'Error al guardar el comentario para la pregunta {$a->name} en la base de datos.';
$string['errorupdatingattempt'] = 'Error al actualizar el intento {$a->id} en la base de datos.';
$string['exportcategory'] = 'Exportar categoría';
$string['exportcategory_help'] = '<p align="center"><b>Categoría de exportación</b></p>

<p>Se utiliza el menú emergente <b>Categoría:</b> para seleccionar la categoría de la que se tomarán las preguntas exportadas.</p>

<p>Algunos formatos de importación (GIFT y XML) permiten incluir la categoría en el archivo escrito, posibilitando así que las categorías puedan opcionalmente ser recreadas al importarlas. Para que esto suceda, es preciso marcar la casilla <b>a un archivo</b>.</p>';
$string['exporterror'] = 'Ha ocurrido un error durante la exportación';
$string['exportquestions'] = 'Exportar preguntas a un archivo';
$string['exportquestions_help'] = '<P>Esta función permite exportar una categoría completa de preguntas a un
   archivo de texto.</p>

<p>Por favor, advierta que en muchos formatos de archivo se pierde alguna
   información cuando se exportan las preguntas. Esto se debe a que muchos
   formatos no poseen todas las características existentes en las preguntas
   de Moodle. No puede esperarse exportar preguntas y luego importarlas de
   modo que ambas sean idénticas. Asimismo, algunos tipos de preguntas no
   pueden exportarse en absoluto. Compruebe los datos exportados antes de
   usarlos en un contexto de producción.</p>

<P>Los formatos posibles actualmente son:</p>

<P><B>Formato GIFT</B></P>
<ul>
<p>GIFT es el formato de importación/exportación más comprensivo de que se
   dispone para exportar preguntas Moodle a un archivo de texto. Fue diseñado
   para que los profesores escribieran fácilmente preguntas en un archivo de
   texto. Soporta los formatos de elección múltiple, verdadero-falso, respuesta
   corta, emparejamiento, preguntas numéricas, así como la inserción de _______
   en el formato de "palabra perdida". Advierta que las preguntas incrustadas
   ("cloze") no se incluyen por el momento. En un archivo de texto pueden
   mezclarse preguntas de distinto tipo, y el formato soporta asimismo comentarios,
   nombres de las preguntas, retroalimentación y calificaciones ponderadas (en
   porcentajes). He aquí algunos ejemplos:</p>
<pre>
¿En qué mes de 1492 Colón descubrió América?{~Noviembre ~Septiembre =Octubre}

Colón descubrió América el 12 de {~noviembre =octubre ~septiembre} de 1492.

Colón descubrió América el 12 de noviembre de 1492.{FALSE}

¿Quién descubrió América el 12 de octubre de 1492?{=Colón =Cristóbal Colón}

¿En qué año llegó Colón a América?{#1492}
</pre>

<p class="moreinfo"><a href="help.php?file=formatgift.html&amp;module=quiz">Más sobre el formato "GIFT"</a></p>
</ul>

<p><b>Formato XML Moodle XML</b></p>
<ul>
<p>Este formato específico de Moodle exporta preguntas en formato simple XML. Esas preguntas pueden
luego importarse a cualquier categoría del cuestionario, o usarse en cualquier otro proceso, tal como
una transformación XSLT.</p>
</ul>

<p><b>IMS QTI 2.0</b></p>
<ul>
<p>Las preguntas se exportan en el formato IMS QTI estándar (version 2.0) format. Note que este modo de
exportación genera un grupo de archivos dentro de un único archivo \'zip\'.</p>
<p class="moreinfo"><a href="http://www.imsglobal.org/question/" target="_qti">Más información sobre el sitio IMS QTI</a>
 (sitio externo en una ventana nueva)</p>
</ul>

<p><b>XHTML</b></p>
<ul>
<p>Exporta la categoría en una única página de XHTML \'estricto\'. Cada una de las preguntas es ubicada en su propia marca
&lt;div&gt;. Si desea usar esta página tal cual, necesitará al menos editar la marca &lt;form&gt; al comienzo de la
sección &lt;body&gt; para posibilitar acciones tales como \'mailto\'.</p>
</ul>

<P>¡Pronto se dispondrá de más formatos, incluyendo WebCT y cualesquiera otros
   que los usuarios de Moodle quieran incorporar! </p>';
$string['filecantmovefrom'] = 'Los archivos de preguntas no se pueden mover porque usted no tiene permiso para trasladar archivos del lugar desde el que está intentando hacerlo.';
$string['filecantmoveto'] = 'Los archivos de preguntas no se pueden mover o copiar porque usted no tiene permiso para añadir archivos del lugar al que está intentando hacerlo.';
$string['filesareacourse'] = 'área de archivos del curso';
$string['filesareasite'] = 'área de archivos del sitio';
$string['filestomove'] = '¿Mover / copiar archivos a {$a}?';
$string['flagged'] = 'Marcadas';
$string['flagthisquestion'] = 'Marcar esta pregunta';
$string['formquestionnotinids'] = 'Pregunta contenida en formulario que no está en questionids.';
$string['fractionsnomax'] = 'Una de las respuestas debería tener una puntuación del 100% de modo que sea posible conseguir la calificación máxima en esta pregunta.';
$string['getcategoryfromfile'] = 'Obtener categoría de archivo';
$string['getcontextfromfile'] = 'Obtener contexto de archivo';
$string['ignorebroken'] = 'Pasar por alto enlaces rotos';
$string['importcategory'] = 'Importar categoría';
$string['importcategory_help'] = '<p>Se utiliza el menú emergente <b>Categoría:</b> para seleccionar la categoría en la que irán las preguntas importadas.</p>

<p>Algunos formatos de importación (GIFT y XML) permiten especificar la categoría dentro del archivo de importación. Para que esto suceda, debe estar marcada la casilla <b>desde archivo</b>. En caso contrario, la pregunta irá a la categoría seleccionada independientemente de las instrucciones del archivo.</p>

<p>Cuando se especifican las categorías dentro de un archivo de importación, se crearán en el caso de que no existan.</p>';
$string['importquestions'] = 'Importar preguntas de un archivo';
$string['importquestions_help'] = 'Esta función posibilita la importación de preguntas en distintos formatos por medio de un archivo de texto. Advierta que el archivo debe tener la codificación UTF-8.';
$string['impossiblechar'] = 'Se ha detectado un carácter imposible {$a} como carácter de paréntesis';
$string['invalidarg'] = 'No se han suministrado argumentos válidos, o la configuración del servidor es incorrecta';
$string['invalidcategoryidforparent'] = 'El ID de la categoría padre no es válido.';
$string['invalidcategoryidtomove'] = 'El ID de la categoría a mover no es válido.';
$string['invalidconfirm'] = 'La cadena de confirmación es incorrecta';
$string['invalidcontextinhasanyquestions'] = 'Contexto no válido pasado a question_context_has_any_questions.';
$string['invalidwizardpage'] = 'La página asistente es incorrecta o no está especificada.';
$string['lastmodifiedby'] = 'Última modificación por';
$string['linkedfiledoesntexist'] = 'El archivo enlazado {$a} no existe';
$string['makechildof'] = 'Hacer Hijo de \'{$a}\'';
$string['maketoplevelitem'] = 'Mover al nivel superior';
$string['matchgrades'] = 'Emparejar calificaciones';
$string['matchgrades_help'] = '<p>Las calificaciones importadas <b>deben</b> corresponderse con alguna de las que figuran en la lista fija de calificaciones válidas, de este modo:</p>

<ul>
  <li>100%</li>
  <li>90%</li>
  <li>80%</li>
  <li>75%</li>
  <li>70%</li>
  <li>66.666%</li>
  <li>60%</li>
  <li>50%</li>
  <li>40%</li>
  <li>33.333</li>
  <li>30%</li>
  <li>25%</li>
  <li>20%</li>
  <li>16.666%</li>
  <li>14.2857</li>
  <li>12.5%</li>
  <li>11.111%</li>
  <li>10%</li>
  <li>5%</li>
  <li>0%</li>
</ul>

<p>se admiten asimismo los valores negativos de la lista anterior.</p>

<p>Esta opción tiene dos posibilidades, que afectan a la forma en que la rutina de importación trata los valores que no se corresponden <strong>exactamente</strong> con cualquiera de los valores de la lista</p>

<ul>
  <li><strong>Error si la calificación no está en la lista</strong><br />
  Si una pregunta contiene cualesquiera calificaciones que no se correspondan con los valores de la lista, se mostrará un mensaje de error y esa pregunta no se importará.</li>
  <li><strong>Calificación más próxima si no está en la lista</strong><br />
  Si se encuentra una calificación que no se corresponde con uno de los valores de la lista, se toma el valor más próximo de la lista</li>
</ul>

<p><i>Nota: algunos formatos de importación personalizados pueden escribir directamente en la base de datos y no quedar afectados por esta comprobación</i></p>';
$string['missingcourseorcmid'] = 'Es necesario proporcionar courseid o cmid a print_question';
$string['missingcourseorcmidtolink'] = 'Es necesario proporcionar courseid o cmid a get_question_edit_link';
$string['missingimportantcode'] = 'Este tipo de pregunta carece de un código importante: {$a}.';
$string['missingoption'] = 'La pregunta de cierre {$a} no tiene las opciones necesarias';
$string['modified'] = 'Último guardado';
$string['move'] = 'Mover desde {$a} y cambiar enlaces.';
$string['movecategory'] = 'Mover categoría';
$string['movedquestionsandcategories'] = 'Trasladadas preguntas y categorías de preguntas de {$a->oldplace} a {$a->newplace}.';
$string['movelinksonly'] = 'Limitarse a cambiar el lugar al que apuntan los enlaces, no mover ni copiar archivos.';
$string['moveq'] = 'Mover pregunta(a)';
$string['moveqtoanothercontext'] = 'Mover pregunta a otro contexto.';
$string['movingcategory'] = 'Moviendo categoría';
$string['movingcategoryandfiles'] = '¿Está seguro de que quiere mover la categoría {$a->name} y todas sus categorías subordinadas al contexto de "{$a->contextto}"?<br /> Hemos detectado {$a->urlcount} archivos enlazados desde preguntas en {$a->fromareaname}; ¿desea copiarlas o moverlas a {$a->toareaname}?';
$string['movingcategorynofiles'] = '¿Está seguro de que quiere mover la categoría "{$a->name}" y todas sus categorías subordinadas al contexto para "{$a->contextto}"?';
$string['movingquestions'] = 'Moviendo preguntas y cualquier archivo';
$string['movingquestionsandfiles'] = '¿Está seguro de que quiere mover la(s) pregunta(s) {$a->questions} al contexto de <strong>"{$a->tocontext}"</strong>?<br /> Hemos detectado <strong>{$a->urlcount} archivos</strong> enlazados con esta(s) pregunta(s) en {$a->fromareaname}; ¿desea copiarlos o moverlos a {$a->toareaname}?';
$string['movingquestionsnofiles'] = '¿Está seguro de que quiere mover la(s) pregunta(s) {$a->questions} al contexto de <strong>"{$a->tocontext}"</strong>?<br /> No hay <strong>ningún archivo</strong> enlazado desde esta(s) pregunta(s) en {$a->fromareaname}.';
$string['needtochoosecat'] = 'Necesita elegir una categoría para mover esta pregunta o presionar \'cancelar\'.';
$string['nocate'] = 'No existe esa categoría {$a}';
$string['nopermissionadd'] = 'No tiene permiso para agregar preguntas aquí.';
$string['nopermissionmove'] = 'Usted no tiene permiso para trasladar preguntas desde este lugar. Debe guardar la pregunta en esta categoría o guardarla como pregunta nueva.';
$string['noprobs'] = 'No se han encontrado problemas en su base de datos de preguntas.';
$string['notenoughdatatoeditaquestion'] = 'No se ha especificado ni el id de la pregunta ni el de la categoría y tipo de pregunta.';
$string['notenoughdatatomovequestions'] = 'Necesita proporcionar los ids de las preguntas que quiere mover.';
$string['notflagged'] = 'No marcadas';
$string['novirtualquestiontype'] = 'No existe un tipo de pregunta virtual para el tipo de pregunta {$a}';
$string['parentcategory'] = 'Categoría padre';
$string['parentcategory_help'] = '<h2>Padre</h2>

<p>Categoría en la que se colocará. \'Superior\' significa que esta categoría no está contenida en ninguna otra categoría.</p>

<p>Normalmente verá algunos \'contextos\' de categoría en negrita; advierta que cada contexto contiene la jerarquía de su propia categoría. Más abajo hay más información sobre los contextos. Si usted no ve varios contextos, puede deberse a que no tiene permiso para acceder a otros contextos.</p>

<p>Si en un contexto hay una sola categoría, no podrá mover dicha categoría, toda vez que debe haber al menos una categoría en cada contexto.</p>

<p>Vea también:</p>
<ul>
  <li><a href="help.php?module=question&amp;file=categories.html">Categorías de pregunta</a></li>
  <li><a href="help.php?module=question&amp;file=categorycontexts.html">Contextos de categorías</a></li>
  <li><a href="help.php?module=question&amp;file=permissions.html">Permisos (preguntas)</a></li>
</ul>';
$string['parenthesisinproperclose'] = 'El paréntesis antes de ** no se ha cerrado correctamente en {$a}**';
$string['parenthesisinproperstart'] = 'El paréntesis antes de ** no se ha abierto correctamente en {$a}**';
$string['penaltyfactor'] = 'Factor de penalización';
$string['penaltyfactor_help'] = '<p>Puede especificar qué fracción de la puntuación obtenida debería substraerse por cada respuesta errónea. Esto sólo resulta relevante si el cuestionario de ejecuta en modo adaptativo, de forma que se permite al estudiante repetir las respuestas a la pregunta. El factor de penalización debería ser un número entre 0 y 1. Un factor de penalización de 1 significa que el estudiante ha de dar la respuesta correcta al primer intento para conseguir la calificación máxima. Un factor de penalización de 0 significa que el estudiante puede intentar responder cuantas veces quiera y aun así puede conseguir la calificación máxima.</p>';
$string['permissionedit'] = 'Editar esta pregunta';
$string['permissionmove'] = 'Mover esta pregunta';
$string['permissionsaveasnew'] = 'Guardar esto como pregunta nueva';
$string['permissionto'] = 'Usted tiene permiso para:';
$string['published'] = 'publicado';
$string['qtypeveryshort'] = 'T';
$string['questionaffected'] = '<a href="{$a->qurl}">La pregunta "{$a->name}" ({$a->qtype})</a> está en esta categoría, pero está también siendo usada en <a href="{$a->qurl}">el cuestionario "{$a->quizname}"</a> en otro curso "{$a->coursename}".';
$string['questionbank'] = 'Banco de preguntas';
$string['questioncategory'] = 'Categoría de pregunta';
$string['questioncatsfor'] = 'Categorías de pregunta para \'{$a}\'';
$string['questiondoesnotexist'] = 'Esta pregunta no existe.';
$string['questionname'] = 'Nombre de la pregunta';
$string['questionsaveerror'] = 'Se han producido errores al guardar la pregunta - ({$a})';
$string['questionsmovedto'] = 'Preguntas aún en uso trasladadas a "{$a}" en la categoría de curso padre.';
$string['questionsrescuedfrom'] = 'Preguntas guardadas del contexto {$a}.';
$string['questionsrescuedfrominfo'] = 'Estas preguntas (alguna de las cuales puede estar oculta) se han guardado cuando el contexto {$a} fue eliminado debido a que aún están siendo utilizadas por algún cuestionario o por otras actividades.';
$string['questiontype'] = 'Tipo de pregunta';
$string['questionuse'] = 'Usar pregunta en esta actividad';
$string['saveflags'] = 'Guardar el estado en las marcas';
$string['selectacategory'] = 'Seleccionar una categoría:';
$string['selectaqtypefordescription'] = 'Seleccionar un tipo de pregunta para ver su descripción.';
$string['selectquestionsforbulk'] = 'Seleccionar preguntas de acciones en masa';
$string['shareincontext'] = 'Compartir en contexto para {$a}';
$string['stoponerror'] = 'Detenerse si se produce un error';
$string['stoponerror_help'] = 'Esta opción determina si el proceso de importación se detiene cuando se detecta un error (lo que resulta en que no se importan preguntas), o si cualesquiera preguntas que contengan errores se pasen por alto y se importen sólo preguntas válidas.';
$string['tofilecategory'] = 'Escribir categoría a archivo';
$string['tofilecontext'] = 'Escribir contexto a archivo';
$string['unknown'] = 'Desconocido';
$string['unknownquestiontype'] = 'Tipo de pregunta desconocido: {$a}.';
$string['unknowntolerance'] = 'Tipo de tolerancia desconocido: {$a}.';
$string['unpublished'] = 'sin publicar';
$string['upgradeproblemcategoryloop'] = 'Se ha detectado un problema al actualizar las categorías de preguntas. Hay un bucle (\'loop\') en el árbol de categorías. Las IDs de categorías afectadas son {$a}.';
$string['upgradeproblemcouldnotupdatecategory'] = 'No se ha podido actualizar la categoría de pregunta {$a->name} ({$a->id}).';
$string['upgradeproblemunknowncategory'] = 'Se ha detectado un problema al actualizar las categorías de preguntas. La catogoría {$a->id} se refiere al padre {$a->parent}, que no existe. Se ha cambiado el padre para solucionar el problema.';
$string['wrongprefix'] = 'Prefino de nombre formateado erróneamente {$a}.';
$string['youmustselectaqtype'] = 'Debe seleccionar un tipo de pregunta';
$string['yourfileshoulddownload'] = 'Su archivo de exportación debería comenzar a descargarse en seguida. De no ser así, por favor <a href="{$a}">haga clic aquí</a>. Se ha cambiado el padre para solucionar el problema.';
