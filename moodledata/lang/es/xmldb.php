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
 * Strings for component 'xmldb', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   xmldb
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actual'] = 'Real';
$string['aftertable'] = 'Después de la tabla:';
$string['back'] = 'Atrás';
$string['backtomainview'] = 'Volver al principal';
$string['binaryincorrectlength'] = 'Longitud incorrecta del campo binario';
$string['cannotuseidfield'] = 'No se puede insertar el campo "id". Es una columna autonumerada';
$string['change'] = 'Cambiar';
$string['charincorrectlength'] = 'Longitud incorrecta del campo char';
$string['checkbigints'] = 'Comprobar Bigints';
$string['check_bigints'] = 'Buscar enteros DB incorrectos';
$string['checkdefaults'] = 'Comprobar valores por defecto';
$string['check_defaults'] = 'Buscar valores por defecto inconsistentes';
$string['checkindexes'] = 'Comprobar índices';
$string['check_indexes'] = 'Buscar índices BD ausentes';
$string['completelogbelow'] = '(ver abajo el registro completo de la búsqueda)';
$string['confirmcheckbigints'] = 'Esta funcionalidad buscará <a href="http://tracker.moodle.org/browse/MDL-11038">potential wrong integer fields</a> en su servidor Moodle, generando (¡pero no ejecutando!) automáticamente las acciones SQL necesarias para tener todos los enteros de su base de datos adecuadamente definidos.<br /><br />
Una vez generados, puede copiarlas y ejecutarlas con seguridad en su interfaz SQL preferida (no olvide hacer una copia de seguridad de sus datos antes de hacerlo).<br /><br />Se recomienda ejecutar la última (+) versión de Moodle disponible (1.8, 1.9, 2.x ...) antes de llevar a cabo la búsqueda de enteros erróneos.<br /><br />Esta funcionalidad no ejecuta ninguna acción contra la BD (únicamente la lee), de modo que puede ser realizada con seguridad en cualquier momento.';
$string['confirmcheckdefaults'] = 'Esta funcionalidad buscará valores por defecto inconsistentes en su servidor Moodle, y generará (pero no ejecutará) los comandos SQL necesarios para hacer que todos los valores por defecto se definan apropiadamente.<br /><br />Una vez generados, puede copiar tales comandos y ejecutarlos con seguridad en su interfaz SQL favorita (no olvide hacer una copia de sus datos antes).<br /><br />Es muy recomendable ejecutar la última versión (+version) disponible de Moodle (1.8, 1.9, 2x...) antes de ejecutar la búsqueda de valores por defecto inconsistentes.<br /><br />
Esta funcionalidad no ejecuta ninguna acción contra la base de datos (simplemente la lee), de modo que puede ejecutarse con seguridad en cualquier momento.';
$string['confirmcheckindexes'] = 'Esta funcionalidad buscará potenciales índices ausentes en su servidor Moodle, generando (no ejecutando) automáticamente los comandos SQL necesarios para mantener todo actualizado. Una vez generados, puede copiar los comandos y ejecutarlos con seguridad con su interfaz SQL favorita.<br /><br />
Es muy recomendable ejecutar la última versión disponible de Moodle (1.8, 1.9, 2.x ...) antes de llevar a cabo la búsqueda de los índices ausentes.<br /><br />
Esta funcionalidad no ejecuta ninguna acción contra la BD (simplemente lee en ella), de modo que puede ejecutarse con seguridad en cualquier momento.';
$string['confirmdeletefield'] = '¿Está totalmente seguro de eliminar el campo:';
$string['confirmdeleteindex'] = '¿Está totalmente seguro de eliminar el índice:';
$string['confirmdeletekey'] = '¿Está totalmente seguro de eliminar la clave:';
$string['confirmdeletesentence'] = '¿Está totalmente seguro de eliminar ela frase:';
$string['confirmdeletestatement'] = '¿Está totalmente seguro de eliminar la declaración y todas sus frases:';
$string['confirmdeletetable'] = '¿Está totalmente seguro de eliminar la tabla:';
$string['confirmdeletexmlfile'] = '¿Está totalmente seguro de eliminar el archivo:';
$string['confirmrevertchanges'] = '¿Está totalmente seguro de que quiere revertir los cambios realizados sobre:';
$string['create'] = 'Crear';
$string['createtable'] = 'Crear tabla:';
$string['defaultincorrect'] = 'Valor por defecto incorrecto';
$string['delete'] = 'Eliminar';
$string['delete_field'] = 'Eliminar campo';
$string['delete_index'] = 'Eliminar índice';
$string['delete_key'] = 'Eliminar clave';
$string['delete_sentence'] = 'Eliminar frase';
$string['delete_statement'] = 'Eliminar declaración';
$string['delete_table'] = 'Eliminar tabla';
$string['delete_xml_file'] = 'Eliminar archivo XML';
$string['down'] = 'Abajo';
$string['duplicate'] = 'Duplicar';
$string['duplicatefieldname'] = 'Ya existe otro campo con ese nombre';
$string['duplicatekeyname'] = 'Ya existe otra clave con ese nombre';
$string['edit'] = 'Edición';
$string['edit_field'] = 'Editar campo';
$string['edit_index'] = 'Editar índice';
$string['edit_key'] = 'Editar clave';
$string['edit_sentence'] = 'Editar frase';
$string['edit_statement'] = 'Editar declaración';
$string['edit_table'] = 'Editar tabla';
$string['edit_xml_file'] = 'Editar archivo XML';
$string['enumvaluesincorrect'] = 'Valores incorrectos del campo enum';
$string['expected'] = 'Esperado';
$string['field'] = 'Campo';
$string['fieldnameempty'] = 'Nombre del campo vacío';
$string['fields'] = 'Campos';
$string['fieldsusedinkey'] = 'Este campo se usa como clave.';
$string['filenotwriteable'] = 'Archivo no escribible';
$string['floatincorrectdecimals'] = 'Número incorrecto de decimales del campo float';
$string['floatincorrectlength'] = 'Longitud incorrecta del campo float';
$string['gotolastused'] = 'Ir al último archivo usado';
$string['incorrectfieldname'] = 'Nombre incorrecto';
$string['index'] = 'Índice';
$string['indexes'] = 'Índices';
$string['integerincorrectlength'] = 'Longitud incorrecta del campo integer';
$string['key'] = 'Clave';
$string['keys'] = 'Claves';
$string['listreservedwords'] = 'Lista de palabra reservadas<br/>(usada para mantener <a href="http://docs.moodle.org/en/XMLDB_reserved_words" target="_blank">XMLDB_reserved_words</a> actualizado)';
$string['load'] = 'Cargar';
$string['main_view'] = 'Vista principal';
$string['missing'] = 'Ausente';
$string['missingfieldsinsentence'] = 'Campos ausentes en la frase';
$string['missingindexes'] = 'Se han encontrado índices ausentes';
$string['missingvaluesinsentence'] = 'Valores ausentes en la frase';
$string['mustselectonefield'] = 'Debe seleccionar un campo para ver las acciones relacionadas con el campo.';
$string['mustselectoneindex'] = 'Debe seleccionar un índice para ver las acciones relacionadas con el índice.';
$string['mustselectonekey'] = 'Debe seleccionar una clave para ver las acciones relacionadas con la clave.';
$string['mysqlextracheckbigints'] = 'Bajo MySQL busca también bigints firmados incorrectamente, generando el SQL necesario para corregirlos.';
$string['newfield'] = 'Nuevo campo';
$string['newindex'] = 'Nuevo índice';
$string['newkey'] = 'Nueva clave';
$string['newsentence'] = 'Nueva frase';
$string['newstatement'] = 'Nueva declaración';
$string['new_statement'] = 'Nueva declaración';
$string['newtable'] = 'Nueva tabla';
$string['newtablefrommysql'] = 'Nueva tabla desde MySQL';
$string['new_table_from_mysql'] = 'Nueva tabla desde MySQL';
$string['nomissingindexesfound'] = 'No se han encontrado índices ausentes: su BD no requiere acciones adicionales.';
$string['nowrongdefaultsfound'] = 'No se han encontrado valores por defecto inconsistentes. Su base de datos no necesita acciones adicionales.';
$string['nowrongintsfound'] = 'No se han encontrado enteros erróneos: su BD no necesita más acciones.';
$string['numberincorrectdecimals'] = 'Número incorrecto de decimales en el campo numérico';
$string['numberincorrectlength'] = 'Longitud incorrecta del campo numérico';
$string['reserved'] = 'Reservado';
$string['reservedwords'] = 'Palabras reservadas';
$string['revert'] = 'Revertir';
$string['revert_changes'] = 'Revertir cambios';
$string['save'] = 'Guardar';
$string['searchresults'] = 'Buscar resultados';
$string['selectaction'] = 'Seleccionar acción:';
$string['selectdb'] = 'Seleccionar base de datos:';
$string['selectfieldkeyindex'] = 'Seleccionar Campo/Clave/Índice:';
$string['selectonecommand'] = 'Por favor, seleccione una acción de la lista para ver el código PHP';
$string['selectonefieldkeyindex'] = 'Por favor, seleccione un Campo/Clave/Índice de la lista para ver el código PHP';
$string['selecttable'] = 'Seleccionar tabla:';
$string['sentences'] = 'Frases';
$string['statements'] = 'Declaraciones';
$string['statementtable'] = 'Tabla de declaraciones:';
$string['statementtype'] = 'Tipo de declaración:';
$string['table'] = 'Tabla';
$string['tables'] = 'Tablas';
$string['test'] = 'Test';
$string['textincorrectlength'] = 'Longitud incorrecta del campo de texto';
$string['unload'] = 'Descargar';
$string['up'] = 'Arriba';
$string['view'] = 'Ver';
$string['viewedited'] = 'Ver edición';
$string['vieworiginal'] = 'Ver original';
$string['viewphpcode'] = 'Ver código PHP';
$string['view_reserved_words'] = 'Ver palabras reservadas';
$string['viewsqlcode'] = 'Ver código SQL';
$string['view_structure_php'] = 'Ver estructura PHP';
$string['view_structure_sql'] = 'Ver estructura SQL';
$string['view_table_php'] = 'Ver tabla PHP';
$string['view_table_sql'] = 'Ver tabla SQL';
$string['wrong'] = 'Erróneo';
$string['wrongdefaults'] = 'Se han encontrado valores por defecto erróneos';
$string['wrongints'] = 'Se han encontrado enteros erróneos';
$string['wronglengthforenum'] = 'Longitud incorrecta del campo enum';
$string['wrongnumberoffieldsorvalues'] = 'Número incorrecto de campos o valores en la frase';
$string['wrongreservedwords'] = 'Palabras reservadas usadas actualmente<br />(note que los nombres de la tabla no son importantes si se usa $CFG->prefix)';
$string['yesmissingindexesfound'] = 'En su BD se han encontrado algunos índices ausentes. Aquí puede ver sus detalles, así como los comandos SQL a ejecutar con su interfaz SQL favorita para crearlos.<br /><br /> Una vez que lo haya hecho, es muy recomendable que ejecute de nuevo esta utilidad para comprobar que no se encuentran más índices ausentes.';
$string['yeswrongdefaultsfound'] = 'En su base de datos se han encontrado algunos valores por defecto inconsistentes. Aquí se presentan sus detalles y las acciones SQL que deben ejecutarse en su interfaz SQL favorita para crearlos (no olvide hacer una copia de seguridad de sus datos).<br /><br />Una vez realizado, se recomienda ejecutar de nuevo esta utilidad para comprobar que no se encuentran más valores por defecto inconsistentes.';
$string['yeswrongintsfound'] = 'Se han encontrado algunos enteros erróneos en su BD. Aquí se presentan sus detalles y las acciones SQL que deben ejecutarse en su interfaz SQL favorita para crearlos (no olvide hacer una copia de seguridad de sus datos).<br /><br />Una vez realizado, se recomienda ejecutar de nuevo esta utilidad para comprobar que no se encuentran más enteros erróneos.';
