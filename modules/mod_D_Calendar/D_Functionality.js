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



function minusMonth()
{
	d_calendar.referenceDay.today.setMonth(d_calendar.referenceDay.today.getMonth()-1);
	d_calendar.referenceDayNextMonth.today.setMonth(d_calendar.referenceDayNextMonth.today.getMonth()-1);
	
	updateReferenceDays();
	reinitializeCells();
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	triggerMonthChanged(d_calendar.referenceDay.Year, d_calendar.referenceDay.Month);
}

function plusMonth()
{
	d_calendar.referenceDay.today.setMonth(d_calendar.referenceDay.today.getMonth()+1);
	d_calendar.referenceDayNextMonth.today.setMonth(d_calendar.referenceDayNextMonth.today.getMonth()+1);
	
	updateReferenceDays();
	reinitializeCells();
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	triggerMonthChanged(d_calendar.referenceDay.Year, d_calendar.referenceDay.Month);
}

function markToday()
{
	try
	{
		document.getElementById( "day_" + d_calendar.d_today.Year + "_" + d_calendar.d_today.Month + "_" + d_calendar.d_today.Date).className = "today";
	}
	catch(err)
	{
	}
}

function setSelectedDay(year, month, date)
{
	triggerDateSelected(year, month, date);
	try
	{
		document.getElementById(d_calendar.selectedID).className = d_calendar.selectedClass;
		
	}
	catch(err)
	{
	}
	try
	{
		var thisClass = document.getElementById( "day_" + year + "_" + month + "_" + date).className;
		document.getElementById( "day_" + year + "_" + month + "_" + date).className = "selected";
		d_calendar.selectedID = "day_" + year + "_" + month + "_" + date;
		d_calendar.selectedClass = thisClass;
	}
	catch(err)
	{
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

function minusMonth()
{
	d_calendar.referenceDay.today.setMonth(d_calendar.referenceDay.today.getMonth()-1);
	d_calendar.referenceDayNextMonth.today.setMonth(d_calendar.referenceDayNextMonth.today.getMonth()-1);
	
	updateReferenceDays();
	reinitializeCells();
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	triggerMonthChanged(d_calendar.referenceDay.Year, d_calendar.referenceDay.Month);
}

function plusMonth()
{
	d_calendar.referenceDay.today.setMonth(d_calendar.referenceDay.today.getMonth()+1);
	d_calendar.referenceDayNextMonth.today.setMonth(d_calendar.referenceDayNextMonth.today.getMonth()+1);
	
	updateReferenceDays();
	reinitializeCells();
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	triggerMonthChanged(d_calendar.referenceDay.Year, d_calendar.referenceDay.Month);
}

function markToday()
{
	try
	{
		document.getElementById( "day_" + d_calendar.d_today.Year + "_" + d_calendar.d_today.Month + "_" + d_calendar.d_today.Date).className += " today";
	}
	catch(err)
	{
	}
}

function setSelectedDay(year, month, date)
{
	triggerDateSelected(year, month, date);
	try
	{
		document.getElementById(d_calendar.selectedID).className = d_calendar.selectedClass;
		
	}
	catch(err)
	{
	}
	try
	{
		var thisClass = document.getElementById( "day_" + year + "_" + month + "_" + date).className;
		document.getElementById( "day_" + year + "_" + month + "_" + date).className += " selected";
		d_calendar.selectedID = "day_" + year + "_" + month + "_" + date;
		d_calendar.selectedClass = thisClass;
	}
	catch(err)
	{
	}
	
}