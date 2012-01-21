/**
Copyright 2010 - Kastaniotis Dimitris - D-Extensions.com
license GNU/GPL http://www.gnu.org/copyleft/gpl.html


This file is part of D Calendar.

D Calendar is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

D Calendar is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with D Calendar.  If not, see <http://www.gnu.org/licenses/>.



function D_Week(year, month) {
    this.month = month;
    this.days = new Array();
    this.days = [new D_Day(year, month, 1), new D_Day(year, month, 2), new D_Day(year, month, 3), new D_Day(year, month, 4), new D_Day(year, month, 5), new D_Day(year, month, 6), new D_Day(year, month, 7)];

    this.html = function () {
        var html = "<tr>";
        for (var x = 0; x < 7; x++) {
            html += this.days[x].html();
        }
        html += "</tr>";
        return html;
    }
}

**/

/**
Copyright 2010 - Kastaniotis Dimitris - D-Extensions.com
license GNU/GPL http://www.gnu.org/copyleft/gpl.html


This file is part of D Articles Calendar.

D Articles Calendar is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

D Articles Calendar is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with D Articles Calendar.  If not, see <http://www.gnu.org/licenses/>.

**/

function D_Week(year, month) {
    this.month = month;
    this.days = new Array();
    this.days = [new D_Day(year, month, 1), new D_Day(year, month, 2), new D_Day(year, month, 3), new D_Day(year, month, 4), new D_Day(year, month, 5), new D_Day(year, month, 6), new D_Day(year, month, 7)];

    this.html = function () {
        var html = "<tr>";
        for (var x = 0; x < 7; x++) {
            html += this.days[x].html();
        }
        html += "</tr>";
        return html;
    }
}