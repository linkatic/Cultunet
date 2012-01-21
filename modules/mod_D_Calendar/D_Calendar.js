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


var d_bug;
	
var d_calendar;

var d_mootips;

function D_Calendar(thismode)
{
	this.numberOfDaysInMonth = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	this.mode = thismode;
	this.months = new Array();
	this.d_today = new D_Today();
	this.referenceDay = new D_Today();
	this.referenceDayNextMonth = new D_Today(); 
	this.referenceDayNextMonth.today.setMonth(this.referenceDayNextMonth.today.getMonth()+1);
	this.referenceDayNextMonth.Month = this.referenceDayNextMonth.today.getMonth(); 
	this.referenceDayNextMonth.Year = this.referenceDayNextMonth.today.getFullYear();
	
	this.selectedID = "";
	this.selectedClass = "";
	
	this.div = "calendar";
	

	this.events = [new D_Event("2000", "1", "1", "2000", "1", "1", "start time", "end time", "Initializing event.. if you ever se this ignore this :D")];
		
	if(this.mode == "week")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,1)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.d_today.Year);
	}
	
	if(this.mode == "month")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,7)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.d_today.Year);
		
	}
		
	if(this.mode == "bimonth")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,7),new D_Month(this.referenceDayNextMonth.Year,this.referenceDayNextMonth.Month+1,7)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.referenceDay.Year);
		this.months[1].initialize(this.referenceDayNextMonth.Date, this.referenceDayNextMonth.Month, this.referenceDayNextMonth.Year);
	}
	
	
	
	this.html = function() {
		var html = "<div class='D_Calendar'>";
		if(this.mode != "week")
		{
			html += "<table width='100%'>";
				html += "<tr>";
					html += "<td class='month_down' id='month_down'>";
						html += "<a onclick='minusMonth();return false;' href='#'>";
							html += "<img border='0' alt='Previous Month' src='" +  getBaseURL() + "/modules/mod_D_Calendar/images/left-arrow-32.png' class='month_down_img' id='month_down_img'>";
						html += "</a>";
					html += "</td>";
					html += "<td class='month_text' id='month_text'>" + d_calendar.months[0].monthText() + " " + d_calendar.months[0].year + "</td>";
					html += "<td class='month_up' id='month_up'>";
						html += "<a onclick='plusMonth();return false;' href='#'>";
							html += "<img border='0' alt='Next Month' src='" +  getBaseURL() + "/modules/mod_D_Calendar/images/right-arrow-32.png' class='month_up_img' id='month_up_img'>";
						html += "</a>";
					html += "</td>";
				html += "</tr>";
			html += "</table>";
		}
		else
		{
			html += "<table width='100%'><tr>";
			html += "<td class='month_text' id='month_text'>" + d_calendar.months[0].monthText() + " " + d_calendar.months[0].year + "</td>";
			html += "</tr></table>";
		}
		
		
		
		for(var x = 0; x < this.months.length; x++)
		{
			if(x == 1)
			{	
				html += "<hr />";
				html += "<table width='100%'><tr>";
				html += "<td class='month_text' id='month_text'>" + d_calendar.months[1].monthText() + " " + d_calendar.months[1].year + "</td>";
				html += "</tr></table>";
			}
			
			html += "<div id='D_Calendar" + x + "' ><table width='100%'><tr><td>" + this.months[x].html() +  "</td></tr></table></div>";
			
		}
		html += "</div>";
		return html;
	}
}

function prepareDays()
{
	updateReferenceDays();
	d_calendar.months[0].year =  d_calendar.referenceDay.Year;
	d_calendar.months[0].monthNumber = d_calendar.referenceDay.Month;
	
	var rowsToFill = 0;
	if(d_calendar.mode == "week"){
		rowsToFill = 0;
	}
	else{	
		rowsToFill = d_calendar.referenceDay.getRow();
	}
	var daysInMonth0 = d_calendar.numberOfDaysInMonth[d_calendar.months[0].monthNumber - 1]; 
	var previousMonth0 = d_calendar.months[0].monthNumber - 2;
	if(previousMonth0 == -1){
		previousMonth0 = 11;
	}
	var daysInPreviousMonth0 = d_calendar.numberOfDaysInMonth[previousMonth0]; 
	var daysInNextMonth0 = 1; 
			//care for leap years.
			if((d_calendar.months[0].year % 4 == 0) && (d_calendar.months[0].monthNumber == 2)){
				daysInMonth0++;
			}
			if((d_calendar.months[0].year % 4 == 0) && (previousMonth0  == 1)){
				daysInPreviousMonth0++;
			}
	var startPoint = d_calendar.referenceDay.Date - (7 * rowsToFill + d_calendar.referenceDay.Day); //count back to the start of the calendar
	for( var x = 0; x < d_calendar.months[0].weeks.length; x++)
	{
		for( var y = 0; y < 7; y++)
		{
			
			if(startPoint < 1)
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = 0 - (daysInPreviousMonth0 + startPoint);//"";
			}
			else if(startPoint > daysInMonth0)//d_calendar.numberOfDaysInMonth[d_calendar.months[0].monthNumber - 1])
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = 0 - daysInNextMonth0;//"";
				daysInNextMonth0++;
			}
			else
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = startPoint;
			}
			startPoint++;
		}
	}
	
	if(d_calendar.mode == "bimonth")
	{
		d_calendar.months[1].year =  d_calendar.referenceDayNextMonth.Year;
		d_calendar.months[1].monthNumber = d_calendar.referenceDayNextMonth.Month;
	
		var rowsToFill2 = 0;
		if(d_calendar.mode == "week"){
			rowsToFill2 = 0;
		}
		else{	
			rowsToFill2 = d_calendar.referenceDayNextMonth.getRow();
		}
		var daysInMonth1 = d_calendar.numberOfDaysInMonth[d_calendar.months[1].monthNumber - 1]; 
		var previousMonth1 = d_calendar.months[1].monthNumber - 2;
		if(previousMonth1 == -1){
			previousMonth1 = 11;
		}
		var daysInPreviousMonth1 = d_calendar.numberOfDaysInMonth[previousMonth1]; 
		var daysInNextMonth1 = 1; 
	
		//care for leap years.
			if((d_calendar.months[1].year % 4 == 0) && (d_calendar.months[1].monthNumber == 2))
			{
				daysInMonth1++;
			}
			if((d_calendar.months[1].year % 4 == 0) && (previousMonth1  == 1)){
				daysInPreviousMonth1++;
			}
		
		var startPoint2 = d_calendar.referenceDayNextMonth.Date - (7 * rowsToFill2 + d_calendar.referenceDayNextMonth.Day); //count back to the start of the calendar
		for( var x = 0; x < d_calendar.months[1].weeks.length; x++)
		{
			for( var y = 0; y < 7; y++)
			{
				if(startPoint2 < 1)
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = 0 - (daysInPreviousMonth1 + startPoint2);//"";
				
				}
				else if(startPoint2 > daysInMonth1)//d_calendar.numberOfDaysInMonth[d_calendar.months[1].monthNumber - 1])
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = 0 - daysInNextMonth1;//"";
					daysInNextMonth1++;
				}
				else
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = startPoint2;
				}
				startPoint2++;
			}
		}
	}
	
	
}

function reinitializeCells()
{
	if(d_calendar.mode == "week")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,1)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.d_today.Year);
	}
	
	if(d_calendar.mode == "month")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,7)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.d_today.Year);
		
	}
		
	if(d_calendar.mode == "bimonth")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,7),new D_Month(d_calendar.referenceDayNextMonth.Year,d_calendar.referenceDayNextMonth.Month,7)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.referenceDay.Year);
		d_calendar.months[1].initialize(d_calendar.referenceDayNextMonth.Date, d_calendar.referenceDayNextMonth.Month, d_calendar.referenceDayNextMonth.Year);
	}	

}
function createCalendar(thisMode, thisLocation, thisTooltips)
{
	d_mootips = thisTooltips;
	d_calendar = new D_Calendar(thisMode);
	
	d_calendar.div = thisLocation;
	
}

function loadCalendar()
{
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	


}

**/

/**
Copyright 2010 - Kastaniotis Dimitris - D-Extensions.com
license GNU/GPL http://www.gnu.org/copyleft/gpl.html


This file is part of D Articles Calendar.

D Calendar Articles is free software: you can redistribute it and/or modify
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

var d_bug;
	
var d_calendar;

var d_mootips;

function D_Calendar(thismode)
{
	this.numberOfDaysInMonth = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	this.mode = thismode;
	this.months = new Array();
	this.d_today = new D_Today();
	this.referenceDay = new D_Today();
	this.referenceDayNextMonth = new D_Today(); 
	this.referenceDayNextMonth.today.setMonth(this.referenceDayNextMonth.today.getMonth()+1);
	this.referenceDayNextMonth.Month = this.referenceDayNextMonth.today.getMonth(); 
	this.referenceDayNextMonth.Year = this.referenceDayNextMonth.today.getFullYear();
	
	this.selectedID = "";
	this.selectedClass = "";
	
	this.div = "calendar";
	

	this.events = [new D_Event("2000", "1", "1", "2000", "1", "1", "start time", "end time", "Initializing event.. if you ever se this ignore this :D", "free")];
		
	if(this.mode == "week")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,1)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.d_today.Year);
	}
	
	if(this.mode == "month")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,7)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.d_today.Year);
		
	}
		
	if(this.mode == "bimonth")
	{
		this.months = [new D_Month(this.referenceDay.Year,this.referenceDay.Month,7),new D_Month(this.referenceDayNextMonth.Year,this.referenceDayNextMonth.Month+1,7)];
		this.months[0].initialize(this.referenceDay.Date, this.referenceDay.Month, this.referenceDay.Year);
		this.months[1].initialize(this.referenceDayNextMonth.Date, this.referenceDayNextMonth.Month, this.referenceDayNextMonth.Year);
	}
	
	
	
	this.html = function() {
		var html = "<div class='D_Calendar'>";
		if(this.mode != "week")
		{
			html += "<table width='100%'>";
				html += "<tr>";
					html += "<td class='month_down' id='month_down'>";
						html += "<a onclick='minusMonth();return false;' href='#'>";
							html += "<img border='0' alt='Previous Month' src='" +  d_config.baseURL + "modules/mod_D_Calendar/images/left-arrow-32.png' class='month_down_img' id='month_down_img'>";
						html += "</a>";
					html += "</td>";
					html += "<td class='month_text' id='month_text'>" + d_calendar.months[0].monthText() + " " + d_calendar.months[0].year + "</td>";
					html += "<td class='month_up' id='month_up'>";
						html += "<a onclick='plusMonth();return false;' href='#'>";
							html += "<img border='0' alt='Next Month' src='" +  d_config.baseURL + "modules/mod_D_Calendar/images/right-arrow-32.png' class='month_up_img' id='month_up_img'>";
						html += "</a>";
					html += "</td>";
				html += "</tr>";
			html += "</table>";
		}
		else
		{
			html += "<table width='100%'><tr>";
			html += "<td class='month_text' id='month_text'>" + d_calendar.months[0].monthText() + " " + d_calendar.months[0].year + "</td>";
			html += "</tr></table>";
		}
		
		
		
		for(var x = 0; x < this.months.length; x++)
		{
			if(x == 1)
			{	
				html += "<hr />";
				html += "<table width='100%'><tr>";
				html += "<td class='month_text' id='month_text'>" + d_calendar.months[1].monthText() + " " + d_calendar.months[1].year + "</td>";
				html += "</tr></table>";
			}
			
			html += "<div id='D_Calendar" + x + "' ><table width='100%'><tr><td>" + this.months[x].html() +  "</td></tr></table></div>";
			
		}
		html += "</div>";
		return html;
	}
}

function prepareDays()
{
	updateReferenceDays();
	d_calendar.months[0].year =  d_calendar.referenceDay.Year;
	d_calendar.months[0].monthNumber = d_calendar.referenceDay.Month;
	
	var rowsToFill = 0;
	if(d_calendar.mode == "week"){
		rowsToFill = 0;
	}
	else{	
		rowsToFill = d_calendar.referenceDay.getRow();
	}
	var daysInMonth0 = d_calendar.numberOfDaysInMonth[d_calendar.months[0].monthNumber - 1]; 
	var previousMonth0 = d_calendar.months[0].monthNumber - 2;
	if(previousMonth0 == -1){
		previousMonth0 = 11;
	}
	var daysInPreviousMonth0 = d_calendar.numberOfDaysInMonth[previousMonth0]; 
	var daysInNextMonth0 = 1; 
			//care for leap years.
			if((d_calendar.months[0].year % 4 == 0) && (d_calendar.months[0].monthNumber == 2)){
				daysInMonth0++;
			}
			if((d_calendar.months[0].year % 4 == 0) && (previousMonth0  == 1)){
				daysInPreviousMonth0++;
			}
	var startPoint = d_calendar.referenceDay.Date - (7 * rowsToFill + d_calendar.referenceDay.Day); //count back to the start of the calendar
	for( var x = 0; x < d_calendar.months[0].weeks.length; x++)
	{
		for( var y = 0; y < 7; y++)
		{
			
			if(startPoint < 1)
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = 0 - (daysInPreviousMonth0 + startPoint);//"";
			}
			else if(startPoint > daysInMonth0)//d_calendar.numberOfDaysInMonth[d_calendar.months[0].monthNumber - 1])
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = 0 - daysInNextMonth0;//"";
				daysInNextMonth0++;
			}
			else
			{
				d_calendar.months[0].weeks[x].days[y].dayNumber = startPoint;
			}
			startPoint++;
		}
	}
	
	if(d_calendar.mode == "bimonth")
	{
		d_calendar.months[1].year =  d_calendar.referenceDayNextMonth.Year;
		d_calendar.months[1].monthNumber = d_calendar.referenceDayNextMonth.Month;
	
		var rowsToFill2 = 0;
		if(d_calendar.mode == "week"){
			rowsToFill2 = 0;
		}
		else{	
			rowsToFill2 = d_calendar.referenceDayNextMonth.getRow();
		}
		var daysInMonth1 = d_calendar.numberOfDaysInMonth[d_calendar.months[1].monthNumber - 1]; 
		var previousMonth1 = d_calendar.months[1].monthNumber - 2;
		if(previousMonth1 == -1){
			previousMonth1 = 11;
		}
		var daysInPreviousMonth1 = d_calendar.numberOfDaysInMonth[previousMonth1]; 
		var daysInNextMonth1 = 1; 
	
		//care for leap years.
			if((d_calendar.months[1].year % 4 == 0) && (d_calendar.months[1].monthNumber == 2))
			{
				daysInMonth1++;
			}
			if((d_calendar.months[1].year % 4 == 0) && (previousMonth1  == 1)){
				daysInPreviousMonth1++;
			}
		
		var startPoint2 = d_calendar.referenceDayNextMonth.Date - (7 * rowsToFill2 + d_calendar.referenceDayNextMonth.Day); //count back to the start of the calendar
		for( var x = 0; x < d_calendar.months[1].weeks.length; x++)
		{
			for( var y = 0; y < 7; y++)
			{
				if(startPoint2 < 1)
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = 0 - (daysInPreviousMonth1 + startPoint2);//"";
				
				}
				else if(startPoint2 > daysInMonth1)//d_calendar.numberOfDaysInMonth[d_calendar.months[1].monthNumber - 1])
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = 0 - daysInNextMonth1;//"";
					daysInNextMonth1++;
				}
				else
				{
					d_calendar.months[1].weeks[x].days[y].dayNumber = startPoint2;
				}
				startPoint2++;
			}
		}
	}
	
	
}

function reinitializeCells()
{
	if(d_calendar.mode == "week")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,1)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.d_today.Year);
	}
	
	if(d_calendar.mode == "month")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,7)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.d_today.Year);
		
	}
		
	if(d_calendar.mode == "bimonth")
	{
		d_calendar.months = [new D_Month(d_calendar.referenceDay.Year,d_calendar.referenceDay.Month,7),new D_Month(d_calendar.referenceDayNextMonth.Year,d_calendar.referenceDayNextMonth.Month,7)];
		d_calendar.months[0].initialize(d_calendar.referenceDay.Date, d_calendar.referenceDay.Month, d_calendar.referenceDay.Year);
		d_calendar.months[1].initialize(d_calendar.referenceDayNextMonth.Date, d_calendar.referenceDayNextMonth.Month, d_calendar.referenceDayNextMonth.Year);
	}	

}
function createCalendar(thisMode, thisLocation, thisTooltips)
{
	d_mootips = thisTooltips;
	d_calendar = new D_Calendar(thisMode);
	
	d_calendar.div = thisLocation;
	
}

function loadCalendar()
{
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
	
	//for each week
	/*
	for(x = 1; x<=7; x++)
	{
		week = document.getElementById("D_Calendar0").childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x];
		
		counting = 0;
		//for each day
		for(y = 0; y < 7; y++)
		{
			counting += week.childNodes[y].innerHTML.indexOf("color: lightgray;");
		}
		
		if(counting == 91)
		{
			week.className = "hidden";
		}
	}
	*/
	//alert(" ");
}
