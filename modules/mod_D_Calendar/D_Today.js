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


function D_Today() {
    this.today = new Date();
    this.Day = this.today.getDay() - 1;
    if (this.Day == -1) {
        this.Day = 6; //Moves Sunday at the end of the array
    }
    this.Date = this.today.getDate();
    this.Month = this.today.getMonth() + 1;
    this.Year = this.today.getFullYear();

    this.getRow = function () {
        return parseInt((this.Date / 6) + 1);
    }


}

function updateReferenceDays() {
	d_calendar.referenceDay.Day = d_calendar.referenceDay.today.getDay() - 1;
	if (d_calendar.referenceDay.Day == -1) {
        d_calendar.referenceDay.Day = 6; //Moves Sunday at the end of the array
    	}
	d_calendar.referenceDay.Date = d_calendar.referenceDay.today.getDate();
	d_calendar.referenceDay.Month = d_calendar.referenceDay.today.getMonth() + 1; 
   	d_calendar.referenceDay.Year = d_calendar.referenceDay.today.getFullYear();
   	
   	d_calendar.referenceDayNextMonth.Day = d_calendar.referenceDayNextMonth.today.getDay() - 1;
	if (d_calendar.referenceDayNextMonth.Day == -1) {
        d_calendar.referenceDayNextMonth.Day = 6; //Moves Sunday at the end of the array
    	}
	d_calendar.referenceDayNextMonth.Date = d_calendar.referenceDayNextMonth.today.getDate();
	d_calendar.referenceDayNextMonth.Month = d_calendar.referenceDayNextMonth.today.getMonth() + 1; 
   	d_calendar.referenceDayNextMonth.Year = d_calendar.referenceDayNextMonth.today.getFullYear(); 
   	
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

function D_Today() {
    this.today = new Date();
    this.Day = this.today.getDay() - 1;
    if (this.Day == -1) {
        this.Day = 6; //Moves Sunday at the end of the array
    }
    this.Date = this.today.getDate();
    this.Month = this.today.getMonth() + 1;
    this.Year = this.today.getFullYear();

    this.getRow = function () {
        return parseInt((this.Date / 6) + 1);
    }


}

function updateReferenceDays() {
	d_calendar.referenceDay.Day = d_calendar.referenceDay.today.getDay() - 1;
	if (d_calendar.referenceDay.Day == -1) {
        d_calendar.referenceDay.Day = 6; //Moves Sunday at the end of the array
    	}
	d_calendar.referenceDay.Date = d_calendar.referenceDay.today.getDate();
	d_calendar.referenceDay.Month = d_calendar.referenceDay.today.getMonth() + 1; 
   	d_calendar.referenceDay.Year = d_calendar.referenceDay.today.getFullYear();
   	
   	d_calendar.referenceDayNextMonth.Day = d_calendar.referenceDayNextMonth.today.getDay() - 1;
	if (d_calendar.referenceDayNextMonth.Day == -1) {
        d_calendar.referenceDayNextMonth.Day = 6; //Moves Sunday at the end of the array
    	}
	d_calendar.referenceDayNextMonth.Date = d_calendar.referenceDayNextMonth.today.getDate();
	d_calendar.referenceDayNextMonth.Month = d_calendar.referenceDayNextMonth.today.getMonth() + 1; 
   	d_calendar.referenceDayNextMonth.Year = d_calendar.referenceDayNextMonth.today.getFullYear(); 
   	
}