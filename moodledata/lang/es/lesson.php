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
 * Strings for component 'lesson', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   lesson
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['accesscontrol'] = 'Control de acceso';
$string['actionaftercorrectanswer'] = 'Acción posterior a la respuesta correcta';
$string['actionaftercorrectanswer_help'] = '<p>La acción normal es seguir el salto de página tal como se ha especificado en la respuesta.
    En la mayoría de los casos se mostrará la página siguiente de la lección. Se conduce al
    estudiante a través de la lección siguiendo un camino lógico desde el principio hasta el final.</p>

<p>Sin embargo, el módulo Lección puede también usarse como si fuera una tarea a base de <i>tarjetas</i> (<I>flash-cards</I>).
    Se muestra (opcionalmente) al estudiante alguna información y se le formula una pregunta
    habitualmente de forma aleatoria. No hay ni principio ni final establecidos, sino simplemente
    un conjunto de <i>fichas</i> que se muestran unas junto a otras sin ajustarse a un orden
    particular.</p>

<p>Esta opción permite dos variantes muy similares al comportamiento de las tarjetas. La opción
    "Ir a una página no vista" nunca muestra dos veces la misma página (incluso aunque el estudiante
    <b>no</b> haya contestado correctamente la pregunta asociada con la página o la tarjeta. La otra
    opción ("Mostrar unan página no contestada") permite al estudiante ver páginas que pueden haber
    aparecido antes, pero sólo si ha contestado erróneamente a la pregunta asociada.</p>

<p>En las lecciones a base de tarjetas el profesor puede decidir si utiliza bien todas las páginas/tarjeta
    de la lección o sólo un subconjunto aleatorio. Esto se lleva a cabo a través del parámetro &quot; Número
    de Páginas (Tarjetas) a mostrar&quot;.</p>';
$string['actions'] = 'Acciones';
$string['activitylink'] = 'Enlace a una actividad';
$string['activitylink_help'] = '<p>El menú emergente contiene todas las actividades del curso. Si se selecciona una de ellas, al final de la        lección aparecerá un enlace a dicha actividad.</p>';
$string['activitylinkname'] = 'Ir a: {$a}';
$string['addabranchtable'] = 'Añadir una tabla de ramificaciones';
$string['addanendofbranch'] = 'Añadir un final de ramificación';
$string['addaquestionpage'] = 'Añadir una página de preguntas';
$string['addaquestionpagehere'] = 'Agregar aquí una página de preguntas';
$string['addcluster'] = 'Añadir un cluster';
$string['addedabranchtable'] = 'Añadida tabla de ramificaciones';
$string['addedanendofbranch'] = 'Añadido final de ramificación';
$string['addedaquestionpage'] = 'Añadida página de preguntas';
$string['addedcluster'] = 'Añadido cluster';
$string['addedendofcluster'] = 'Añadido final de cluster';
$string['addendofcluster'] = 'Añadir un final de cluster';
$string['addpage'] = 'Agregar una página';
$string['anchortitle'] = 'Comienzo del contenido principal';
$string['and'] = 'Y';
$string['answer'] = 'Respuesta';
$string['answeredcorrectly'] = 'respondidas correctamente.';
$string['answersfornumerical'] = 'Las respuestas a preguntas numéricas deberían ser pares de valores máximo y mínimo';
$string['arrangebuttonshorizontally'] = '¿Disponer los botones de ramificación horizontal en modo pase de diapositivas?';
$string['attempt'] = 'Intento: {$a}';
$string['attempts'] = 'Intentos';
$string['attemptsdeleted'] = 'Intentos eliminados';
$string['attemptsremaining'] = 'Tiene {$a} intento(s) pendiente(s)';
$string['available'] = 'Disponible desde';
$string['averagescore'] = 'Puntuación promedio';
$string['averagetime'] = 'Tiempo promedio';
$string['branchtable'] = 'Tabla de ramificaciones';
$string['cancel'] = 'Cancelar';
$string['canretake'] = 'Permitir que el {$a} pueda retomar la lección';
$string['casesensitive'] = 'Mayúsculas y minúsculas';
$string['casesensitive_help'] = '<p>Algunos tipos de pregunta tienen una opción que se activa con un clic en cuadro de verificación.  Los tipos de la pregunta y el significado de las opciones son detallados abajo.

<ol>
<li><p><b>Elección múltiple</b> Es una variante de las preguntas de Opción
Múltiple llamada de <b>&quot;Elección múltiple y respuestas múltiples"</b>.  Si escoge esta opción en la pregunta entonces requieren que el estudiante seleccione todas las respuestas correctas del conjunto de respuestas.  La pregunta puede o no decir al estudiante <i>cuántas</i> respuestas correctas existen. Por ejemplo "¿Cuáles han sido presidentes de
EE.UU.?", o "Seleccione a dos presidentes de EE.UU. de la siguiente lista.".  El número de respuestas correctas pueden ser de <b>una</b> hasta todas las opciones.  (Una pregunta de Elección múltiples y respuestas múltiples con una respuesta correcta <b>es</b> diferente de una pregunta de Opción Múltiple pues la primera permite al estudiante la posibilidad de elegir más de una respuesta mientras que la última no lo permite.)</p></li>

<li><p><b>Respuesta corta</b> Por defecto, en las comparaciones no se tiene en cuenta si el texto está mayúsculas o minúsculas. Si se selecciona Opción de pregunta  las comparaciones tiene en cuenta si el texto está en mayúsculas o minúsculas.</p></li>

<p>En los otros tipos de preguntas no afecta la Opción de pregunta.</p>';
$string['checkbranchtable'] = 'Comprobar tabla de ramifiaciones';
$string['checkedthisone'] = 'comprobado ésta.';
$string['checknavigation'] = 'Revisar navegación';
$string['checkquestion'] = 'Revisar pregunta';
$string['classstats'] = 'Estadísticas de clase';
$string['clicktodownload'] = 'Haga clic en el siguiente enlace para descargar el archivo.';
$string['clicktopost'] = 'Haga clic aquí para enviar su calificación a la lista de mejores puntuaciones.';
$string['clusterjump'] = 'Pregunta no vista dentro de un cluster';
$string['clustertitle'] = 'Cluster';
$string['collapsed'] = 'Colapsado';
$string['comments'] = 'Sus comentarios';
$string['completed'] = 'Completado';
$string['completederror'] = 'Completar la lección';
$string['completethefollowingconditions'] = 'Para seguir, deberá completar la(s) siguiente(s) condición(es) en la lección <b>{$a}</b>.';
$string['conditionsfordependency'] = 'Condición(es) para la dependencia';
$string['confirmdeletionofthispage'] = 'Confirme que desea eliminar esta página';
$string['congratulations'] = 'Enhorabuena, ha llegado al final de la lección';
$string['continue'] = 'Continuar';
$string['continuetoanswer'] = 'Continuar a cambiar respuestas.';
$string['correctanswerjump'] = 'Salto a respuesta correcta';
$string['correctanswerscore'] = 'Puntuación de respuesta correcta';
$string['correctresponse'] = 'Comentario (correcto)';
$string['credit'] = 'Crédito';
$string['customscoring'] = 'Puntuación personalizada';
$string['customscoring_help'] = '<p>Esta opción le permite asignar un valor numérico a cada respuesta. Las respuestas
   pueden tener valores negativos o positivos. Se asignará automáticamente a las preguntas
   importadas 1 punto a cada respuesta correcta y 0 puntos a cada respuesta incorrecta,
   si bien usted puede cambiar esto después de importarlas.</p>';
$string['deadline'] = 'Fecha final';
$string['defaultessayresponse'] = 'Su ensayo será calificado por el instructor del curso.';
$string['deleteallattempts'] = 'Eliminar todos los intentos de resolver la lección';
$string['deletedefaults'] = 'Eliminada {$a} x lección por defecto';
$string['deletedpage'] = 'Página eliminada';
$string['deleting'] = 'Eliminando';
$string['deletingpage'] = 'Eliminando página: {$a}';
$string['dependencyon'] = 'Dependiente de';
$string['dependencyon_help'] = '<p>Esta opción permite que la lección actual dependa del rendimiento de los estudiantes en otra lección del         mismo curso. Si no alcanza el rendimiento exigido, el estudiante no podrá acceder a esta lección.</p>

<p>Las condiciones de la dependencia incluyen:
    <ul>
        <li><b>Tiempo empleado:</b> el estudiante debe emplear en la lección el tiempo que aquí se señale.</li>
        <li><b>Completa:</b> el estudiante debe completar la lección.</li>
        <li><b>Calificación superior a:</b> el estudiante debe alcanzar en la lección una calificación superior     a la especificada en esta opción.</li>
    </ul>
    Puede usarse cualquier combinación de las opciones anteriores.
</p>';
$string['description'] = 'Descripción';
$string['detailedstats'] = 'Estadísticas detalladas';
$string['didnotanswerquestion'] = 'No ha contestado a esta pregunta.';
$string['didnotreceivecredit'] = 'No ha recibido crédito';
$string['displaydefaultfeedback'] = 'Mostrar retroalimentación por defecto';
$string['displaydefaultfeedback_help'] = '<p align="center"><strong>Mostrar retroalimentación por defecto</strong></p>

<p>Si se ajusta esta opción a <strong>Sí</strong>, cuando no se encuentre una respuesta a una pregunta en particular, se usará por defecto el comentario "Esa es la respuesta correcta" y "Esa es la respuesta incorrecta".</p>
<p>Si la opción se ajusta a <strong>No</strong>, cuando no se encuentre una respuesta a una pregunta en particular, no se mostrarán comentarios de retroalimentación. El usuario que está realizando la lección pasará directamente a la siguiente página de la lección.</p>';
$string['displayhighscores'] = 'Mostrar mejores puntuaciones';
$string['displayinleftmenu'] = '¿Mostrar en menú de la izquierda?';
$string['displayleftif'] = 'y mostrar sólo si {$a} tiene una calificación mayor que:';
$string['displayleftmenu'] = 'Mostrar menú de la izquierda';
$string['displayleftmenu_help'] = '<p>Esta opción muestra una lista de las páginas (tablas de ramas) de la lección. Las
páginas de preguntas, las páginas de conglomerados, etc., no se mostrarán por defecto
(usted puede elegir el mostrar las páginas de preguntas marcando esa opción en la
pregunta).</p>';
$string['displayofgrade'] = 'Mostrar calificación (sólo para estudiantes)';
$string['displayreview'] = 'Mostrar botón Revisar';
$string['displayreview_help'] = '<p>Esta opción muestra un botón después de una pregunta contestada incorrectamente,
permitiendo al estudiante intentar responderla de nuevo. No es compatible con las
preguntas de ensayo, de modo que deberá dejarla sin seleccionar si usted está
utilizando este tipo de preguntas.</p>';
$string['displayscorewithessays'] = 'Usted ha obtenido una puntuación de {$a->score} sobre {$a->tempmaxgrade} en las preguntas calificadas automáticamente.<br>La(s) {$a->essayquestions} pregunta(s) de su ensayo serán calificadas y añadidas<br>a su calificación final en una fecha posterior.<br><br>Su calificación actual sin contar esa(s) pregunta(s) es de is {$a->score} sobre {$a->grade}';
$string['displayscorewithoutessays'] = 'Su puntuación es {$a->score} (sobre {$a->grade}).';
$string['edit'] = 'Edición';
$string['editlessonsettings'] = 'Editar los ajustes de Esta lección';
$string['editpagecontent'] = 'Editar el contenido de esta página';
$string['email'] = 'Email';
$string['emailallgradedessays'] = 'Enviar por email TODOS los<br>ensayos calificados';
$string['emailgradedessays'] = 'Enviar por email los ensayos calificados';
$string['emailsuccess'] = 'Email(s) enviado(s) con éxito';
$string['endofbranch'] = 'Fin de ramificación';
$string['endofclustertitle'] = 'Fin de cluster';
$string['endoflesson'] = 'Fin de la lección';
$string['enteredthis'] = 'introducido ésta.';
$string['entername'] = 'Escriba un apodo para la lista de mejores puntuaciones';
$string['enterpassword'] = 'Por favor, escriba la contraseña:';
$string['eolstudentoutoftime'] = 'Atención: Usted ha sobrepasado el tiempo fijado para esta lección. Su última respuesta puede no haber sido contabilizada si ha sido dada con el tiempo finalizado.';
$string['eolstudentoutoftimenoanswers'] = 'No ha contestado a ninguna pregunta. En esta lección ha obtenido 0 puntos.';
$string['essay'] = 'Ensayo';
$string['essayemailmessage'] = '<p>Ensayo:<blockquote>{$a->question}</blockquote></p><p>Su respuesta:<blockquote><em>{$a->response}</em></blockquote></p><p>{$a->comment} del profesor:<blockquote><em>{$a->comment}</em></blockquote></p><p>Usted ha recibido {$a->earned} sobre un total de {$a->outof} en esta pregunta de ensayo.</p><p>Su calificación en la lección ha cambiado a {$a->newgrade}%.</p>';
$string['essayemailsubject'] = 'Su calificación para la pregunta {$a}';
$string['essays'] = 'Ensayos';
$string['essayscore'] = 'Puntuación del ensayo';
$string['fileformat'] = 'Formato de archivo';
$string['firstanswershould'] = 'La primera respuesta debería saltar a la página "correcta"';
$string['firstwrong'] = 'Lo sentimos, usted no puede obtener este punto porque su respuesta no es correcta. ¿Desea seguir intentándolo? (únicamente para aprender, no para ganar el punto).';
$string['flowcontrol'] = 'Control de Flujo';
$string['full'] = 'Expandido';
$string['general'] = 'General';
$string['grade'] = 'Calificación';
$string['gradebetterthan'] = 'Calificación superior a (%)';
$string['gradebetterthanerror'] = 'Obtener una calificación superior al {$a} por ciento';
$string['gradeessay'] = 'Calificar preguntas de ensayo ({$a->notgradedcount} no calificadas y {$a->notsentcount} no enviadas)';
$string['gradeis'] = 'La calificación es {$a}';
$string['gradeoptions'] = 'Opciones de Calificación';
$string['handlingofretakes'] = 'Manejo de nuevos intentos';
$string['handlingofretakes_help'] = '<p>Cuando se permite a los estudiantes retomar o repetir la lección, esta opción
permite elegir al profesor la clase de calificación final del alumno, por ejemplo, en
la página de calificaciones. Puede ser la <b>media</b>, la <b>primera</b> o la
<b>mejor</b> calificación de las obtenidas en todos los intentos o repeticiones de la lección.</p>

<p>Esta opción puede cambiarla en cualquier momento.</p>';
$string['havenotgradedyet'] = 'Aún no calificado.';
$string['here'] = 'aquí';
$string['highscore'] = 'Puntuación alta';
$string['highscores'] = 'Puntuaciones altas';
$string['hightime'] = 'Tiempo alto';
$string['importcount'] = 'Importando {$a} preguntas';
$string['importppt'] = 'Importar PowerPoint';
$string['importppt_help'] = '<p> FORMA DE USARLO</p>

<p>Todas las diapositivas PowerPoint se importan como Tablas de Rama con las respuestas Anterior y Siguente.</p>

<p>

<ol>

<li>Abra su presentación PowerPoint.</li>

<li>Guárdela como Página Web (sin opciones especiales)</li>

<li>El resultado del paso 3 debería ser un archivo HTML y una carpeta con todas las diapositivas convertidas a páginas web.<br />

  COMPRIMA sólo LA CARPETA.</li>

<li>Vaya a su sitio Moodle y agrege una nueva lección.</li>

<li>Tras haber guardado los ajustes de la lección, usted debería ver 4 opciones bajo &quot;¿Qué desea hacer primero?&quot; Haga clic en &quot;Importar PowerPoint&quot;</li>

<li>Use el botón &quot;Navegar...&quot; para encontrar el archivo ZIP to find your zip hecho en el paso 3. Haga clic a continuación en &quot;Subir este archivo&quot;</li>

<li>ISi todo ha ido bien, la próxima pantalla mostrará el botón Continuar.</li>

</ol>

</p>

<p>Si la presentación en PowerPoint contenía imágenes, deberán haber sido guardadas como archivos del curso en moddata/XY donde X es el nombre de la lección e Y es un número (normalmente 0). Asimismo, durante el proceso de importación, se crearán archivos en el directorio de datos de Moodle, dentro de temp/lesson. Estos archivos por el momento no son eliminados por import.php.</p>

<p align="center">&nbsp;</p>';
$string['importquestions'] = 'Importar preguntas';
$string['importquestions_help'] = '<P>Esta función permite importar preguntas de archivos de texto, subidos mediante un formulario.

<P>Los formatos permitidos son:

<P><B>GIFT</B></P>
<ul>
<p>GIFT es el formato de importación de preguntas más amigable para Moodle de todos los formatos disponible.  Su diseño permite que los profesores pueden escribir las preguntas en un archivo de texto de forma fácil. Permite preguntas de opción múltiple, Verdadero o Falso, Cortas, Emparejar y numéricas, también permite las preguntas de rellenar huecos
insertándolas como  _____ . Se pueden mezclar Varios tipos de preguntas en un solo archivo del texto, y este formato también permite líneas comentarios, los nombres de la pregunta, retroalimentación y los porcentaje-peso (ponderar) calificaciones.  Veamos algunos ejemplos:</p>
<pre>Who\'s buried in Grant\'s tomb?{~Grant ~Jefferson =no one}

Grant is {~buried =entombed ~living} in Grant\'s tomb.

Grant is buried in Grant\'s tomb.{FALSE}

Who\'s buried in Grant\'s tomb?{=no one =nobody}

When was Ulysses S. Grant born?{#1822}
</pre>

<p align=right><a href="help.php?file=formatgift.html&amp;module=quiz">Más información sobre el formato "GIFT" </a></p>
</ul>

<P><B>Aiken</B></P>
<ul>
<p>El formato Aiken es una manera simple de crear preguntas de opción múltiple usando un
formato de fácil lectura. Un ejemplo del formato:</p>
<pre>¿Qué objetivo tienen los primeros auxilios?
A. Salvar la vida, prevenir más lesiones, mantener el buen estado de salud.
B. Dar tratamiento médico o sanitario
C. prevenir más lesiones
D. Ayudar a las victimas que pedir auxilio
ANSWER: A
</pre>

<p align=right><a href="help.php?file=formataiken.html&amp;module=quiz">Más información sobre el formato "Aiken" </a></p>
</ul>


<P><B>Llenar el hueco</B></P>
<UL>
<P>Este formato sólo soporta preguntas de opción múltiple. Cada pregunta se separa con un tilde (~), y la respuesta correcta se precede con un signo de igual (=). Un ejemplo:


<BLOCKQUOTE>Cuando comenzamos a explorar las partes de nuestro cuerpo nos convertimos en estudiosos de: {=anatomía y fisiología ~reflexología ~la ciencia ~los experimentos}, y en cierto sentido somos estudiantes de la vida.

</BLOCKQUOTE>

<p align=right><a href="help.php?file=formatmissingword.html&amp;module=quiz">Más información sobre el formato "Llenar
el hueco&quot;</a></p>
</UL>


<P><B>AON</B></P>
<UL>
<P>Este es el mismo caso de llenar el hueco, excepto que luego de ser importadas, todas las preguntas se convierten en grupos de cuatro preguntas de seleccionar la correcta.
</P>
<p>Además, las respuestas de opción múltiple son mezcladas  aleatoriamente en la importación.
<p>Se le llama así en honor a una empresa que impulsó el desarrollo de muchas
características para los cuestionarios.
</p>
</UL>


<P><B>Blackboard</B></P>
<UL>
<P>Este módulo puede importar preguntas guardadas con la característica de exportar preguntas del programa Blackboard. Se apoya en la capacidad de compilar funciones XML en sus correspondientes PHP.</P>

<p align=right><a href="help.php?file=formatblackboard.html&amp;module=quiz">Más información sobre el formato "Blackboard"</a></p>
</UL>

<P><B>Course Test Manager</B></P>
<UL>
<P>Este módulo puede importar las preguntas guardadas en una banco de preguntas de Course Test Manager.  Tenemos varias formas de acceder al banco de preguntas en formato
Access de Microsoft, dependiendo si Moodle está funcionando en un servidor de Windows o de Linux:</P>
<p>Desde Windows debemos subir la base de datos como cualquier otro archivo para importar sus datos.</p>
<p>Desde Linux, debemos instalar el software ODBC Socket Server, que utiliza XML para transferir datos a
Moodle desde el servidor Linux.</p>  <p>Por favor, lea todo el archivo de ayuda antes de importar este tipo de formato.</p>


<p align=right><a href="help.php?file=formatctm.html&amp;module=quiz">Más información sobre el formato "CTM"</a></p>
</UL>

<P><B>Personal</B></P>
<UL>
<P>Si tiene su propio formato que desea importar, puede implementarlo editando mod/quiz/format/custom.php


<P>La cantidad de código nuevo necesaria es bastante pequeña - será suficiente con analizar una sola pregunta del texto-.

<p align=right><a href="help.php?file=formatcustom.html&amp;module=quiz">Más información sobre el formato "Personal"</a></p>
</UL>


<P>Se están desarrollando más formatos, incluyendo WebCT, IMS QTI y cualquier otro que los usuarios de Moodle quieran aportar.</p>';
$string['insertedpage'] = 'Página insertada';
$string['jump'] = 'Saltar';
$string['jumps'] = 'Saltos';
$string['jumps_help'] = '<p>Cada respuesta tiene un salto de página hacia un enlace. Cuando se elige una
respuesta se muestra el refuerzo al estudiante. Después de ver el mensaje de
refuerzo se produce el salto de página hacia el enlace. Este enlace puede ser
absoluto o relativo. Los enlaces relativos son <b>Esta página </b>y <b>Siguiente página</b>. <b>Esta página</b> significa que el estudiante ve la misma página otra vez. La <b>Siguiente página </b>muestra la página que le sigue en el orden lógico de las páginas. Un enlace Absoluto se determina eligiendo el título de la página.</p>
<p>Nota: si cambia el orden de  las páginas el salto de página (relativo) <b>Siguiente página</b> puede mostrar una página diferente. Cuando se usa un enlace absoluto con el título de la página siempre mostrará la página seleccionada aunque las cambie de orden</p>
<p>&nbsp;</p>';
$string['jumpsto'] = 'Saltos a <em>{$a}</em>';
$string['leftduringtimed'] = 'Se ha interrumpido una lección con tiempo fijo.<br>Por favor, haga clic en Continuar para volver a empezar la lección.';
$string['leftduringtimednoretake'] = 'Se ha interrumpido una lección con tiempo fijo y<br>no se permite volver a empezar o continuar la lección.';
$string['lessonattempted'] = 'Lección intentada';
$string['lessonclosed'] = 'Esta lección se cerró el {$a}.';
$string['lessoncloses'] = 'La lección se cierra';
$string['lessoncloseson'] = 'La lección se cierra el {$a}';
$string['lesson:edit'] = 'Editar una actividad de lección';
$string['lessonformating'] = 'Formateado de la Lección';
$string['lesson:manage'] = 'Gestionar una actividad de lección';
$string['lessonmenu'] = 'Menú Lección';
$string['lessonnotready'] = 'Esta lección no está lista para practicar. Por favor, contacte con su {$a}.';
$string['lessonopen'] = 'Esta lección se abrirá el {$a}.';
$string['lessonopens'] = 'La lección se abre';
$string['lessonpagelinkingbroken'] = 'No se encuentra la primera página. El enlace a la página de la lección debe estar roto. Por favor, contacte con el administrador.';
$string['lessonstats'] = 'Estadísticas de la lección';
$string['linkedmedia'] = 'Medios enlazados';
$string['loginfail'] = 'Acceso fallido, por favor pruebe de nuevo...';
$string['lowscore'] = 'Puntuación baja';
$string['lowtime'] = 'Tiempo bajo';
$string['manualgrading'] = 'Calificar ensayos';
$string['matchesanswer'] = 'Concuerda con la respuesta';
$string['maxgrade_help'] = '<p>Este valor determina la máxima calificación que se puede obtener con la
lección.    El rango va de&nbsp; 0 a 100%. Este valor puede cambiarse en
cualquier momento. Los cambios tendrán un efecto inmediato en la página de
calificaciones y los alumnos podrán ver sus calificaciones en diferentes
listas.</p>';
$string['maxhighscores'] = 'Número de puntuaciones más altas para mostrar';
$string['maximumnumberofanswersbranches'] = 'Número máximo de respuestas/ramificaciones';
$string['maximumnumberofanswersbranches_help'] = '<p>Este valor determina máximo número de respuestas que usará el profesor. El
valor por defecto es 4. Si una lección solo utilizará preguntas de VERDADERO o
FALSO podemos asignarle el valor de 2.</p>

<p>Este valor también se usa para asignar el máximo número de capítulos que se
usarán en la lección.</p>

<p>Puede editar, de forma segura, este valor en las lecciones ya creadas. De hecho,
será necesario si desea añadir un pregunta con muchas opciones o ampliar el
número de capítulos. Después de que haya añadido las preguntas o capítulos
también puede reducir el valor a uno más &quot;estándar&quot;.</p>';
$string['maximumnumberofattempts'] = 'Número máximo de intentos';
$string['maximumnumberofattempts_help'] = '<p>Este valor determina el número máximo de intentos que tienen los estudiantes para responder <b>cualquiera</b> de las preguntas de una lección. En los casos de preguntas que no tienes respuestas, por ejemplo preguntas cortas o numéricas, este valor indica el número de veces que puede responder antes de que lo envíe a la siguiente página de la lección. </p>

<p>El valor por defecto es 5. Valores bajos pueden desalentar al estudiante antes de resolver la pregunta. Valores altos pueden producir
frustración.</p>

<p>Si asignamos el valor 1 damos al estudiante una única opción para responder cada pregunta. Esto produce un comportamiento similar a los cuestionarios excepto que cada pregunta se presenta en una página individual.</p>

<p>Advierta que este valor es un parámetro global y que se aplica a todas las preguntas de la lección sin tener en cuenta su tipo de pregunta.</p>

<p>Recuerde que este parámetro <b>no</b> se aplica cuando los profesores comprueban las preguntas o cuando navegan por la lección. No son registrados en la base de datos el número de intentos realizados ni las calificaciones obtenidas por los profesores. ¡Los profesores deberían después de todo conocer todas las respuestas!</p>';
$string['maximumnumberofattemptsreached'] = 'Se ha alcanzado el número máximo de intentos. Traslado a la página siguiente';
$string['maxtime'] = 'Límite de tiempo (minutos)';
$string['maxtimewarning'] = 'Dispone de {$a} minuto(s) para terminar la lección.';
$string['mediaclose'] = 'Mostrar botón de cierre:';
$string['mediafile'] = 'Archivo multimedia';
$string['mediafile_help'] = '<p>Esta opción crea una ventana emergente al comienzo de la lección a un archivo (e.g., mp3) o página web. Asimismo, en cada página de la lección aparecerá un enlace que abre de nuevo la ventana emergente si fuera necesario.</p>

<p>Opcionalmente aparecerá un botón de "Cerrar ventana" al final de la ventana emergente; pueden ajustarse asimismo la altura y anchura de la ventana.</p>';
$string['mediafilepopup'] = 'Haga clic aquí para ver el archivo multimedia de esta lección';
$string['mediaheight'] = 'Altura de la ventana:';
$string['mediawidth'] = 'anchura:';
$string['minimumnumberofquestions'] = 'Número mínimo de preguntas';
$string['minimumnumberofquestions_help'] = '<p>Cuando una lección contiene unas o más tablas de rama el profesor debe fijar este parámetro. Este valor determina el número mínimo de preguntas vistas para que se calcule la calificación, pero <B>no</B> fuerza a los estudiantes a que contesten a muchas preguntas de la lección.</p>

<p>En el caso de fijar este parámetro, por ejemplo, a 20, se asegurará que las calificaciones se dan sólo si los estudiantes han visto por lo menos este número de preguntas. Tomemos el caso de un estudiante que vea solamente una sola rama en una lección con, por ejemplo, 5 páginas y conteste a todas las preguntas asociadas correctamente. Y que después elige terminar la lección (se asume, lo que es bastante razonable, que existe esa opción en la Tabla rama del nivel superior). Si no hemos ajustado este parámetro, su calificación sería 5 de 5, i.e., 100%.  Sin embargo, si fijamos el valor en 20, su calificación quedaría reducida a 5 sobre 20, i.e., 25%.  En el caso de otro estudiante que pasa a través de todas las ramas y ve, por ejemplo, 25 páginas y contesta a todas las preguntas correctamente excepto a dos, su calificación sería 23 sobre 25, i.e., 92%.</p>

<p>Si se utiliza este parámetro, entonces la página de la apertura de la lección debería decir algo como:</p>

<p><blockquote>En esta lección se espera que responda por lo menos a n preguntas.  Usted puede responder a más si lo desea.  Sin embargo, si usted responde menos de n preguntas su calificación será calculada como si hubiera respondido a n.</blockquote></p>

<p>Obviamente "n" es substituido  por el valor real que se ha dado a este parámetro.</p>

<p>Este parámetro indica a los estudiantes cuántas preguntas han respondido y cuántas se espera que respondan.';
$string['missingname'] = 'Por favor, escriba un \'nick\'';
$string['modattempts'] = 'Permitir revisión al estudiante';
$string['modattempts_help'] = '<p>Esta opción permite al estudiante volver atrás para cambiar sus respuestas.</p>';
$string['modattemptsnoteacher'] = 'La revisión del estudiante sólo está disponible para los estudiantes.';
$string['modulename'] = 'Lección';
$string['modulename_help'] = '<p><img alt="" src="<?php echo $CFG->wwwroot?>/mod/lesson/icon.gif" />&nbsp;<b>Lección</b></p>
<div class="indent">
<p>Una lección proporciona contenidos de forma interesante y flexible. Consiste en una
    serie de páginas. Cada una de ellas normalmente termina con una pregunta y un
    número de respuestas posibles. Dependiendo de cuál sea la elección del estudiante,
    progresará a la próxima página o volverá a una página anterior. La navegación a
    través de la lección puede ser simple o compleja, dependiendo en gran medida de
    la estructura del material que se está presentando.</p>
</div>';
$string['modulenameplural'] = 'Lecciones';
$string['movedpage'] = 'Página movida';
$string['movepagehere'] = 'Mover la página aquí';
$string['moving'] = 'Moviendo página: {$a}';
$string['multianswer'] = 'Multirrespuesta';
$string['multianswer_help'] = '<p>Algunos tipos de pregunta tienen una opción que se activa con un clic en cuadro de verificación.  Los tipos de la pregunta y el significado de las opciones son detallados abajo.

<ol>
<li><p><b>Elección múltiple</b> Es una variante de las preguntas de Opción
Múltiple llamada de <b>&quot;Elección múltiple y respuestas múltiples"</b>.  Si escoge esta opción en la pregunta entonces requieren que el estudiante seleccione todas las respuestas correctas del conjunto de respuestas.  La pregunta puede o no decir al estudiante <i>cuántas</i> respuestas correctas existen. Por ejemplo "¿Cuáles han sido presidentes de
EE.UU.?", o "Seleccione a dos presidentes de EE.UU. de la siguiente lista.".  El número de respuestas correctas pueden ser de <b>una</b> hasta todas las opciones.  (Una pregunta de Elección múltiples y respuestas múltiples con una respuesta correcta <b>es</b> diferente de una pregunta de Opción Múltiple pues la primera permite al estudiante la posibilidad de elegir más de una respuesta mientras que la última no lo permite.)</p></li>

<li><p><b>Respuesta corta</b> Por defecto, en las comparaciones no se tiene en cuenta si el texto está mayúsculas o minúsculas. Si se selecciona Opción de pregunta  las comparaciones tiene en cuenta si el texto está en mayúsculas o minúsculas.</p></li>

<p>En los otros tipos de preguntas no afecta la Opción de pregunta.</p>';
$string['multipleanswer'] = 'Respuesta múltiple';
$string['nameapproved'] = 'Nombre aprobado';
$string['namereject'] = 'Lo sentimos, su nombre ha sido rechazado por el filtro.<br>Por favor, pruebe con otro nombre.';
$string['nextpage'] = 'Siguiente página';
$string['noanswer'] = 'No se ha dado respuesta';
$string['noattemptrecordsfound'] = 'No se encontraron registros de intentos. Sin calificación';
$string['nobranchtablefound'] = 'No se ha encontrado tabla de ramas';
$string['nocommentyet'] = 'Aún no comentado.';
$string['nocoursemods'] = 'No se encuentran actividades';
$string['nocredit'] = 'No crédito';
$string['nodeadline'] = 'No fecha límite';
$string['noessayquestionsfound'] = 'No se encuentran preguntas de ensayo en esta lección.';
$string['nohighscores'] = 'No puntuaciones más altas';
$string['nolessonattempts'] = 'No se han hecho intentos de practicar esta lección.';
$string['nooneansweredcorrectly'] = 'Nadie ha contestado correctamente.';
$string['nooneansweredthisquestion'] = 'Nadie ha contestado esta pregunta.';
$string['noonecheckedthis'] = 'Nadie ha comprobado esto.';
$string['nooneenteredthis'] = 'Nadie ha introducido esto.';
$string['noonehasanswered'] = 'Nadie ha contestado aún a una pregunta de ensayo.';
$string['noretake'] = 'No se le permite retomar esta lección.';
$string['normal'] = 'Normal -- seguir el flujo de la lección';
$string['notcompleted'] = 'No completado';
$string['notdefined'] = 'Sin definir';
$string['nothighscore'] = 'No ha fijado la lista {$a} de puntuaciones más altas.';
$string['notitle'] = 'Sin título';
$string['numberofcorrectanswers'] = 'Número de respuestas correctas: {$a}';
$string['numberofcorrectmatches'] = 'Número de emparejamientos correctos: {$a}';
$string['numberofpagestoshow'] = 'Número de páginas (tarjetas) a mostrar';
$string['numberofpagestoshow_help'] = '<p>Este valor se usa solamente en las lecciones de tipo Tarjeta (<I>Flash Card</I>). Su
valor por defecto es cero y significa que todas las Páginas/Tarjeta serán
mostradas en la lección. Cuando el valor es distinto de cero se mostrarán ese
número de páginas. Después de mostrar ese número de&nbsp; Páginas/Tarjeta viene
el final de la lección y se muestra la calificación obtenida por el estudiante.</p>

<p>Si el valor que se asigna es superior al número de páginas de la lección se
mostrarán todas las páginas.</p>';
$string['numberofpagesviewed'] = 'Número de páginas vistas: {$a}';
$string['numberofpagesviewednotice'] = 'Número de preguntas contestadas: {$a->nquestions}; (Debería contestar al menos: {$a->minquestions})';
$string['ongoing'] = 'Mostrar puntuación acumulada';
$string['ongoingcustom'] = 'Esta es una lección de {$a->score} puntos. Usted ha obtenido {$a->score} punto(s) sobre {$a->currenthigh} hasta ahora.';
$string['ongoing_help'] = '<p>Cuando se activa esta opción, cada página mostrará los puntos que el estudiante ha obtenido del total de          puntos posible. Por ejemplo, si un estudiante ha contestado correctamente cuatro preguntas de 5 puntos y ha      fallado una pregunta, la puntuación provisional será de 15/20 puntos.</p>';
$string['ongoingnormal'] = 'Usted ha respondido correctamente {$a->correct} pregunta(s) de un total de {$a->viewed} pregunta(s).';
$string['or'] = 'O';
$string['ordered'] = 'Ordenado';
$string['other'] = 'Otro';
$string['outof'] = 'Fuera de {$a}';
$string['overview'] = 'Revisión';
$string['overview_help'] = '<p align="center"><b>Generalidades</b>

<ol>
    <LI>Una lección se compone de un número de <B>páginas</B> y, opcionalmente, <B>tablas de rama</B>.
    <LI>Cada página contiene algún tipo de <B>contenido</B> y, por lo general,
    termina con una <B>pregunta</B>.
    <LI>Cada página ofrece, por lo general, un número de <B>respuestas</B>.
    <LI>Cada respuesta puede contener un fragmento de texto que se despliega en el
    caso de que se escoja esa opción. Este fragmento de texto se llama <B>resultado</B>.
    <LI>Asociado a cada opción hay un <B>salto</B>. Este salto puede ser relativo
    -esta página, siguiente página- o absoluto -especificando una de las páginas
    de la lección o el final de la lección-.
    <LI>Por defecto, la primera respuesta (desde el punto de vista del profesor)
    conduce a la <B>página siguiente</B> de la lección. Las otras respuestas conducen
    a la misma página. Es decir, que si no escoge la primera respuesta, el alumno
    regresa a la misma página de la lección.
    <LI>La siguiente página es determinada por el <B>orden lógico</B> de la lección.
    Este orden se determina según el criterio del profesor. Para alterar este orden
    hay que mover las páginas dentro de la lección.
    <LI>La lección también tiene un <B>orden de navegación</B>. Este es el orden en el
    que las páginas son vistas por los alumnos, y es determinado por los saltos
    especificados para las respuestas individuales y puede ser diferente del
    orden lógico. (Aunque si a los saltos <I>no</I> se les cambia sus valores
    por defecto, ambos estarán muy relacionados).
    El profesor tiene la opción de revisar el orden lógico.
    <LI>Cuando se muestran al alumno, las respuestas siempre están intercambiadas.
    Es decir, la respuesta que el profesor ve en primer lugar no es necesariamente
    la que aparece en el primer lugar de la lista que ve el alumno. (Además, cada vez
    que se muestra un conjunto de respuestas, estas aparecen en diferente orden).
    <LI>El número de respuestas puede variar de una página a otra. Por ejemplo, una página
    puede terminar con una pregunta del tipo verdadero/falso, mientras otras pueden
    tener preguntas donde aparece una respuesta correcta y tres distractoras.
    <LI>Es posible crear páginas sin respuestas. Al estudiante se le muestra un
    enlace <B>Continuar</B> en lugar del grupo de respuestas.
    <LI>Con el propósito de evaluar las lecciones, las respuestas <B>correctas</B>
    son aquellas que conducen a la página <I>siguiente</I> en el orden lógico. Las
    respuestas <B>incorrectas</B> son las que conducen a la misma página,
    o a la página <I>anterior</I> en el orden lógico. Así, si <I>no</I> se cambian
    los saltos, la primera respuesta sería la correcta y las otras serían las incorrectas.
    <LI>Una pregunta puede tener más de una respuesta correcta. Por ejemplo: si dos
    de las respuestas conducen a la página siguiente, ambas respuestas se consideran
    correctas. (Aunque se muestra la misma página destino a los estudiantes, el texto
    del resultado puede ser diferente para cada una de las respuestas.)
    <LI>El profesor ve la lección con las respuestas correctas subrayadas
    y con su Etiqueta de Respuesta.

<li>Las <b>Tablas de rama</b> son simplemente páginas que tienen un conjunto de enlaces
    a otras páginas de la lección. Normalmente una lección comienza con una tabla de rama
    que actúa como <B>Tabla de Contenidos</B>.
<li>Cada enlace de una tabla de rama tiene dos componentes: una descripción y el título
    de la página de destino.
<li>Una tabla de rama divide la lección en un conjunto de <B>ramas</B> (o secciones). Cada
    rama puede contener varias páginas (usualmente referidas todas al mismo tema). El final
    de una rama normalmente se señala con una página <B>Final de Rama</B>. Ésta es una página
    especial que, por defecto, hace que el estudiante regrese a la tabla de rama precedente.
    El &quot;retorno&quot; en una página Final de Rama puede modificarse, si así se desea,
    editando la página.)
<li>En una lección puede haber más de una tabla de rama. Por ejemplo, una lección podría
    estructurarse de forma útil de modo que los puntos especializados fueran sub-ramas de las
    ramas principales.
<li>Es importante dar a los estudiantes un medio de terminar la lección. Esto puede hacerse
    incluyendo un enlace de &quot;Finalizar Lección&quot; en la tabla de rama principal. Esta
    acción saltaría a la página (imaginaria) <B>Final de Lección</B>. Otra opción consiste en usar
    la última rama de la lección (aquí, el término &quot;última&quot; se usa en el sentido
    de orden lógico) simplemente continuando hasta el fin de la lección, esto es, la lección
    <I>no</I> termina con una página de Fin de Rama.
<li>Cuando una lección incluye una o más tablas de rama, conviene ajustar el &quot;Número mínimo
    de preguntas&quot; a un valor razonable. Así se ajusta el límite más bajo del número de
    páginas vistas cuando se calcula la calificación. Si no se ajusta este parámetro, el estudiante
    podría visitar una sola rama de la lección, contestar a todas las preguntas correctamente y
    abandonar la lección habiendo obtenido la calificación máxima.
<li>Además, cuando está presente una tabla de rama, el estudiante tiene la oportunidad de
    volver a visitar la misma rama más de una vez. Sin embargo, la calificación se calcula
    utilizando el número de respuestas <I>únicas</I> contestadas, de modo que contestar
    repetidamente al mismo conjunto de preguntas no aumenta la calificación. (En realidad,
    ocurre lo contrario: la calificación baja en la medida en que el número de páginas vistas
    se utiliza como denominador cuando el cálculo de la calificación incluye las repeticiones).
    Con el fin de proporcionar a los estudiantes una idea clara de su progreso en la lección,
    se les muestran detalles de cuántas preguntas han contestado correctamente, el número de
    páginas vistas y su calificación actual en cada página.
    <LI>Se llega al <B>fin de la lección</B> saltando en forma explícita hasta allí,
    o saltando a la página siguiente desde la última página (orden lógico) de la lección.
    Cuando el alumno llega al fin de la lección recibe un mensaje de felicitaciones
    y se le muestra su calificación. La calificación es igual al número de respuestas
    correctas dividido por el número de páginas vistas y multiplicado por la
    calificación asignada a la lección.
    <LI>Si el alumno <I>no</I> completa la lección, cuando regrese a la misma
    se le dará la opción de comenzar desde el principio o desde la última
    respuesta correcta.
    <LI>En una lección que permite Retomar, el alumno puede repetir la lección hasta
    conseguir la nota más alta.
</OL>';
$string['page'] = 'Página: {$a}';
$string['pagecontents'] = 'Contenido de la página';
$string['pages'] = 'Páginas';
$string['pagetitle'] = 'Título de la página';
$string['password'] = 'Contraseña';
$string['passwordprotectedlesson'] = '{$a} es una lección protegida con contraseña.';
$string['pleasecheckoneanswer'] = 'Seleccione una respuesta';
$string['pleasecheckoneormoreanswers'] = 'Seleccione una o más respuestas';
$string['pleaseenteryouranswerinthebox'] = 'Por favor, escriba su respuesta en la caja';
$string['pleasematchtheabovepairs'] = 'Encuentre la relación entre estos pares';
$string['pluginname'] = 'Lección';
$string['pointsearned'] = 'Puntos ganados';
$string['postsuccess'] = 'Mensaje exitoso';
$string['practice'] = 'Lección de práctica';
$string['practice_help'] = '<p>Las lecciones de práctica no se mostrarán en el libro de calificaciones.</p>';
$string['preview'] = 'Previsualizar';
$string['previewlesson'] = 'Previsualizar {$a}';
$string['previouspage'] = 'Página anterior';
$string['progressbar'] = 'Barra de progreso';
$string['progressbar_help'] = '<p>Muestra una barra de progreso al final de la lección.
Por el momento, la barra de progreso tiene más precisión cuando las lecciones son lineales.</p>
<p>Al calcular el porcentaje completado, las Tablas de ramificación y las páginas de preguntas contestadas correctamente contribuyen al progreso de la lección. Al calcular el número total de páginas de la lección, los conglomerados y las páginas incluídas en los conglomerados sólo cuentan como una página única, excluyéndose las páginas de final de conglomerado y final de tabla de ramificación. El resto de las páginas cuentan para calcular el total de páginas de la lección.</p>
<p>Nota: los estilos por defecto de la barra de progreso no impresionan ;)  Todos los estilos (e.g.: colores, imágenes de fondo, etc.)
de la barra de progreso pueden modificarse en mod/lesson/styles.php.';
$string['progressbarteacherwarning'] = 'La barra de progreso no muestra {$a}';
$string['qtype'] = 'Tipo de página';
$string['question'] = 'Pregunta';
$string['questionoption'] = 'Opción de pregunta';
$string['questiontype'] = 'Tipo de pregunta';
$string['randombranch'] = 'Página de ramificación aleatoria';
$string['randompageinbranch'] = 'Pregunta aleatoria dentro de una ramificación';
$string['rank'] = 'Rango';
$string['rawgrade'] = 'Calificación en bruto';
$string['receivedcredit'] = 'Crédito recibido';
$string['redisplaypage'] = 'Volver a mostrar página';
$string['report'] = 'Informe';
$string['reports'] = 'Informes';
$string['response'] = 'Comentario';
$string['retakesallowed_help'] = '<p>Esta opción determina si los alumnos pueden tomar una lección más de una vez.
   El profesor puede decidir que la lección contiene material que los alumnos
     deben conocer en profundidad, en cuyo caso se debería permitir que el alumno repita
     la lección. Por otro lado, si el material se utiliza como examen esto no debería
     permitirse.
</p>

<p>Cuando a los alumnos se les permite repetir la lección, la <b>calificación</b> que aparece
    en la página Calificaciones corresponde bien al <B>promedio</B> de calificaciones, bien al <b>mejor</b> resultado obtenido en las repeticiones.
    El siguiente parámetro determina cuál de esas dos alternativas de calificación se utilizará.
</p>
<p>Advierta que el <b>Análisis de Pregunta</b> siempre utiliza las respuestas de los primeros intentos, y que las repeticiones subsiguientes no son tenidas en cuenta.</p>
<p>La opción por defecto es <b>Sí</b>, lo que significa que los alumnos pueden retomar la lección. Se
espera que sólo bajo condiciones excepcionales se seleccione la opción <b>No</b>.
</p>';
$string['returnto'] = 'Volver a {$a}';
$string['returntocourse'] = 'Volver al curso';
$string['review'] = 'Revisión';
$string['reviewlesson'] = 'Revisar lección';
$string['reviewquestionback'] = 'Sí, me gustaría probar de nuevo';
$string['reviewquestioncontinue'] = 'No, deseo pasar a la siguiente';
$string['sanitycheckfailed'] = 'Ha fallado el \'Sanity Check\': Este intento se ha eliminado';
$string['savechanges'] = 'Guardar cambios';
$string['savechangesandeol'] = 'Guardar todos los cambios e ir al final de la lección.';
$string['savepage'] = 'Guardar página';
$string['score'] = 'Puntuación';
$string['scores'] = 'Puntuaciones';
$string['secondpluswrong'] = 'No. ¿Desea probar de nuevo?';
$string['showanunansweredpage'] = 'Mostrar una página no respondida';
$string['showanunseenpage'] = 'Mostrar una página no vista';
$string['singleanswer'] = 'Una sola respuesta';
$string['skip'] = 'Pasar por alto la navegación';
$string['slideshow'] = 'Pase de diapositivas';
$string['slideshowbgcolor'] = 'Color de fondo del pase de diapositivas';
$string['slideshowheight'] = 'Altura del pase de diapositivas';
$string['slideshow_help'] = '<p>Esta opción permite mostrar la lección como una sesión de diapositivas, con una
anchura, altura y color de fondo personalizado fijos. Se mostrará una barra de
desplazamiento basada en CSS si el contenido de la página excede la anchura o la altura
seleccionadas. Por defecto, las preguntas se "desgajarán" del modo de pase de
diapositivas, y sólo las páginas (i.e., tablas de ramas) se mostrarán en una
diapositiva. Los botones etiquetados por el idioma por defecto como "Siguiente" y
"Anterior" aparecerán en los extremos derecho e izquierdo de la diapositiva si
tal opción es seleccionada en la página. El resto de los botones aparecerán centrados
debajo de la diapositiva.</p>';
$string['slideshowwidth'] = 'Anchura del pase de diapositivas';
$string['startlesson'] = 'Comenzar lección';
$string['studentattemptlesson'] = 'Intento número {$a->attempt} de {$a->lastname}, {$a->firstname}';
$string['studentname'] = '{$a} Nombre';
$string['studentoneminwarning'] = 'Atención: Le queda 1 minuto o menos para terminar la lección.';
$string['studentresponse'] = 'comentario de {$a}';
$string['submitname'] = 'Enviar nombre';
$string['teacherjumpwarning'] = 'Un salto {$a->cluster} o {$a->unseen} se está usando en esta lección. En su lugar se usará el salto a la página siguiente. Entre como estudiante para probar estos saltos.';
$string['teacherongoingwarning'] = 'La puntuación acumulada sólo se muestra al estudiante. Entre como estudiante para probar la puntuación acumulada.';
$string['teachertimerwarning'] = 'El temporizador sólo funciona con estudiantes. Entre como estudiante para probar el temporizador.';
$string['thatsthecorrectanswer'] = 'Esta es la respuesta correcta';
$string['thatsthewronganswer'] = 'Esta es la respuesta equivocada';
$string['thefollowingpagesjumptothispage'] = 'Las páginas siguientes saltan a esta página';
$string['thispage'] = 'Esta página';
$string['timeremaining'] = 'Tiempo restante';
$string['timespenterror'] = 'Dedicar al menos {$a} minutos a la lección';
$string['timespentminutes'] = 'Tiempo empleado (minutos)';
$string['timetaken'] = 'Tiempo empleado';
$string['topscorestitle'] = '{$a} puntuaciones para la lección.';
$string['unseenpageinbranch'] = 'Pregunta no vista dentro de una ramificación';
$string['unsupportedqtype'] = '¡Tipo de pregunta no admitido ({$a})!';
$string['updatedpage'] = 'Página actualizada';
$string['updatefailed'] = 'Actualización fallida';
$string['usemaximum'] = 'Utilizar el máximo';
$string['usemean'] = 'Utilizar la media';
$string['usepassword'] = 'Lección protegida con contraseña';
$string['usepassword_help'] = '<p>Si se selecciona esta opción, el estudiante no podrá acceder a la lección a menos que escriba la contraseña.</p>';
$string['viewgrades'] = 'Ver calificaciones';
$string['viewhighscores'] = 'Ver lista de puntuaciones más altas.';
$string['viewreports'] = 'Ver {$a->attempts} intentos {$a->student} completados';
$string['welldone'] = '¡Bien hecho!';
$string['whatdofirst'] = '¿Qué desea hacer primero?';
$string['wronganswerjump'] = 'Salto a respuesta errónea';
$string['wronganswerscore'] = 'Puntuación de respuesta errónea';
$string['wrongresponse'] = 'Comentario (erróneo)';
$string['xattempts'] = '{$a} intentos';
$string['youhaveseen'] = 'Usted ya ha visto más de una página de esta lección.<br />¿Desea comenzar desde la última página vista?';
$string['youmadehighscore'] = 'Lo ha hecho en la lista {$a} de puntuaciones más altas.';
$string['youranswer'] = 'Su respuesta';
$string['yourcurrentgradeis'] = 'Su calificación actual es {$a}';
$string['yourcurrentgradeisoutof'] = 'Su calificación actual es {$a->grade} sobre {$a->total}';
$string['youshouldview'] = 'Usted debería ver como mínimo: {$a}';
