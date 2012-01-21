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



function D_Month(year, month, rows) {

	this.monthNumber = 1;
	this.year = 1000;
	this.day = 0;
	

    this.month = month;
    this.numberOfWeeks = rows;
    this.weeks = new Array();
    if (this.numberOfWeeks == 1) {
        this.weeks = [new D_Week(year, month, 1)];
    }
    if (this.numberOfWeeks == 7) {
        this.weeks = [new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month)];
    }
    
    
    this.html = function () {
        var html = "<table width='100%' class='day_table'><tr>";
        html += "<td class='day_name' id='day_name_0'>L</td>";
        html += "<td class='day_name' id='day_name_1'>M</td>";
        html += "<td class='day_name' id='day_name_2'>X</td>";
        html += "<td class='day_name' id='day_name_3'>J</td>";
        html += "<td class='day_name' id='day_name_4'>V</td>";
        html += "<td class='day_name' id='day_name_5'>S</td>";
        html += "<td class='day_name' id='day_name_6'>D</td>";
        html += "</tr>";

        for (var x = 0; x < rows; x++) {
            html += this.weeks[x].html();
        }
        html += "</table>";
        return html;
    }
    
    this.previousMonth = function () {
    	if(this.monthNumber == 1)
    	{
    		return 12;
    	}
    	else
    	{
    		return this.monthNumber - 1;
    	}
    }
    
    this.nextMonth = function () {
    	if(this.monthNumber == 12)
    	{
    		return 1;
    	}
    	else
    	{
    		return this.monthNumber + 1;
    	}
    }
    
    this.nextMonthsYear = function() {
    	var thisYear = this.year;

    	if(this.monthNumber == 12)
    	{
    		thisYear = this.year + 1;

    	}
    	else
    	{
    		thisYear = this.year;

    	}
    	return thisYear;
    }
    
    this.previousMonthsYear = function() {
    	var thisYear = this.year;

    	if(this.monthNumber == 1)
    	{
    		thisYear = this.year - 1;

    	}
    	else
    	{
    		thisYear = this.year;

    	}
    	return thisYear;
    }
    
    
    this.monthText = function () {
    	var thisText = "";
    	switch(this.monthNumber)
    	{
    		case 1:
    		thisText = "Enero";
    		break;
    		case 2:
    		thisText = "Febrero";
    		break;
    		case 3:
    		thisText = "Marzo";
    		break;
    		case 4:
    		thisText = "Abril";
    		break;
    		case 5:
    		thisText = "Mayo";
    		break;
    		case 6:
    		thisText = "Junio";
    		break;
    		case 7:
    		thisText = "Julio";
    		break;
    		case 8:
    		thisText = "Agosto";
    		break;
    		case 9:
    		thisText = "Septiembre";
    		break;
    		case 10:
    		thisText = "Octubre";
    		break;
    		case 11:
    		thisText = "Noviembre";
    		break;
    		case 12:
    		thisText = "Diciembre";
    		break;

    	}
    	return thisText;
    }
    
    this.initialize = function (day, month, year) {
    	this.day = day;
    	this.monthNumber = month;
    	this.year = year;
    }
    
    this.up = function () {
    	if(this.monthNumber == 12)
    	{
    		this.monthNumber = 1;
    		this.year++;
    	}
    	else
    	{
    		this.monthNumber++;
    	}
    }
    
    this.down = function () {
    	if(this.monthNumber == 1)
    	{
    		this.monthNumber = 12;
    		this.year--;
    	}
    	else
    	{
    		this.monthNumber--;
    	}
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

function D_Month(year, month, rows) {

	this.monthNumber = 1;
	this.year = 1000;
	this.day = 0;
	

    this.month = month;
    this.numberOfWeeks = rows;
    this.weeks = new Array();
    if (this.numberOfWeeks == 1) {
        this.weeks = [new D_Week(year, month, 1)];
    }
    if (this.numberOfWeeks == 7) {
        this.weeks = [new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month), new D_Week(year, month)];
    }
    
    
    this.html = function () {
        var html = "<table width='100%' class='day_table'><tr>";
        html += "<td class='day_name' id='day_name_0'>L</td>";
        html += "<td class='day_name' id='day_name_1'>M</td>";
        html += "<td class='day_name' id='day_name_2'>X</td>";
        html += "<td class='day_name' id='day_name_3'>J</td>";
        html += "<td class='day_name' id='day_name_4'>V</td>";
        html += "<td class='day_name' id='day_name_5'>S</td>";
        html += "<td class='day_name' id='day_name_6'>D</td>";
        html += "</tr>";

        for (var x = 0; x < rows; x++) {
            html += this.weeks[x].html();
        }
        html += "</table>";
        return html;
    }
    
    this.previousMonth = function () {
    	if(this.monthNumber == 1)
    	{
    		return 12;
    	}
    	else
    	{
    		return this.monthNumber - 1;
    	}
    }
    
    this.nextMonth = function () {
    	if(this.monthNumber == 12)
    	{
    		return 1;
    	}
    	else
    	{
    		return this.monthNumber + 1;
    	}
    }
    
    this.nextMonthsYear = function() {
    	var thisYear = this.year;

    	if(this.monthNumber == 12)
    	{
    		thisYear = this.year + 1;

    	}
    	else
    	{
    		thisYear = this.year;

    	}
    	return thisYear;
    }
    
    this.previousMonthsYear = function() {
    	var thisYear = this.year;

    	if(this.monthNumber == 1)
    	{
    		thisYear = this.year - 1;

    	}
    	else
    	{
    		thisYear = this.year;

    	}
    	return thisYear;
    }
    
    
    this.monthText = function () {
    	var thisText = "";
    	switch(this.monthNumber)
    	{
    		case 1:
    		thisText = "Enero";
    		break;
    		case 2:
    		thisText = "Febrero";
    		break;
    		case 3:
    		thisText = "Marzo";
    		break;
    		case 4:
    		thisText = "Abril";
    		break;
    		case 5:
    		thisText = "Mayo";
    		break;
    		case 6:
    		thisText = "Junio";
    		break;
    		case 7:
    		thisText = "Julio";
    		break;
    		case 8:
    		thisText = "Agosto";
    		break;
    		case 9:
    		thisText = "Septiembre";
    		break;
    		case 10:
    		thisText = "Octubre";
    		break;
    		case 11:
    		thisText = "Noviembre";
    		break;
    		case 12:
    		thisText = "Diciembre";
    		break;

    	}
    	return thisText;
    }
    
    this.initialize = function (day, month, year) {
    	this.day = day;
    	this.monthNumber = month;
    	this.year = year;
    }
    
    this.up = function () {
    	if(this.monthNumber == 12)
    	{
    		this.monthNumber = 1;
    		this.year++;
    	}
    	else
    	{
    		this.monthNumber++;
    	}
    }
    
    this.down = function () {
    	if(this.monthNumber == 1)
    	{
    		this.monthNumber = 12;
    		this.year--;
    	}
    	else
    	{
    		this.monthNumber--;
    	}
    }
}