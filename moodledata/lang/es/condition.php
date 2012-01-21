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
 * Strings for component 'condition', language 'es', branch 'MOODLE_20_STABLE'
 *
 * @package   condition
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addcompletions'] = 'Agregar {no} condiciones de actividad al formulario';
$string['addgrades'] = 'Agregar {no} condiciones de calificación al formulario';
$string['availabilityconditions'] = 'Restringir disponibilidad';
$string['availablefrom'] = 'Sólo disponible en';
$string['availableuntil'] = 'Sólo disponible hasta el final';
$string['badavailabledates'] = 'Fechas no válidas. Si usted ajusta ambas fechas, la fecha \'disponible desde\' debería ser anterior a la fecha \'hasta\'.';
$string['completion_complete'] = 'debe marcarse como completada';
$string['completioncondition'] = 'Condición de finalización de actividad';
$string['completioncondition_help'] = 'Este ajuste determina cualesquiera condiciones de finalización de la actividad, que deben cumplirse para que la actividad esté disponible. Note que el rastreo de finalización debe ajustarse antes de que se pueda determinar la condición de finalización de la actividad.

Si se desea, se pueden ajustar múltiples condiciones de finalización de la actividad. Si se hace así, la actividad sólo estará disponible cuando se cumplan TODAS las condiciones de finalización.';
$string['completion_fail'] = 'debe estar completa con calificación de suspenso';
$string['completion_incomplete'] = 'no debe estar marcada como completa';
$string['completion_pass'] = 'debe estar completa con calificación de aprobado';
$string['configenableavailability'] = 'Si se activa, esta opción le permite fijar las condiciones (basadas en la fecha, la calificación o el grado de finalización) que controlan si una actividad está disponible.';
$string['enableavailability'] = 'Habilitar disponibilidad condicional';
$string['grade_atleast'] = 'debe ser al menos';
$string['gradecondition'] = 'Condición de calificación';
$string['gradecondition_help'] = 'Este ajuste determina cualesquiera condiciones de calificación que deben cumplirse para que la actividad esté disponible.

Si se desea, se pueden ajustar múltiples condiciones de calificación de la actividad. Si se hace así, la actividad sólo estará disponible cuando se cumplan TODAS las condiciones de calificación.';
$string['grade_upto'] = 'y menos que';
$string['none'] = '(ninguna)';
$string['notavailableyet'] = 'Aún no disponible';
$string['requires_completion_0'] = 'No disponible a menos que la actividad <strong>{$a}</strong> esté incompleta.';
$string['requires_completion_1'] = 'No disponible hasta que la actividad <strong>{$a}</strong> esté marcada como completa.';
$string['requires_completion_2'] = 'No disponible hasta que la actividad <strong>{$a}</strong> esté completa y aprobada.';
$string['requires_completion_3'] = 'No disponible a menos que la actividad <strong>{$a}</strong> esté completa y suspensa.';
$string['requires_date'] = 'Disponible en {$a}.';
$string['requires_date_before'] = 'Disponible hasta {$a}.';
$string['requires_date_both'] = 'Disponible desde {$a->from} hasta {$a->until}.';
$string['requires_grade_any'] = 'No disponible hasta que usted tenga una calificación en <strong>{$a}</strong>.';
$string['requires_grade_max'] = 'No disponible a menos que usted consiga una calificación apropiada en <strong>{$a}</strong>.';
$string['requires_grade_min'] = 'Not available until you achieve a required score in <strong>{$a}</strong>.';
$string['requires_grade_range'] = 'Not available unless you get a particular score in <strong>{$a}</strong>.';
$string['showavailability'] = 'Está disponible antes de la actividad';
$string['showavailability_hide'] = 'Ocultar completamente la actividad';
$string['showavailability_show'] = 'Mostrar actividad en gris, sin información de restricción';
$string['userrestriction_hidden'] = 'Restringido (completamente oculto, no mensaje): ‘{$a}’';
$string['userrestriction_visible'] = 'Restringido:: ‘{$a}’';
