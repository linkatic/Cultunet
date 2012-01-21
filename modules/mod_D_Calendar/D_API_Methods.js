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



function D_Calendar_New_Event(startYear, startMonth, startDate, endYear, endMonth, endDate, startTime, endTime, title)
{
	d_calendar.events.push(new D_Event(startYear, startMonth, startDate,endYear, endMonth, endDate, startTime, endTime, title));
	reinitializeCells()
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


function D_Calendar_New_Event(startYear, startMonth, startDate, endYear, endMonth, endDate, startTime, endTime, title, eventStatus)
{
	thisDate = parseInt(startDate);
	thisMonth = parseInt(startMonth);
	thisYear = parseInt(startYear);
	
	
	
	while(thisDate != (parseInt(endDate) + 1) || thisMonth != parseInt(endMonth) || thisYear != parseInt(endYear))
	{
		d_calendar.events.push(new D_Event(thisYear, thisMonth, thisDate, endYear, endMonth, endDate, startTime, endTime, title, eventStatus));
		//alert(thisDate + "/" + thisMonth + "/" + thisYear);
		//alert(endDate + "/" + endMonth + "/" + endYear);
		
		if(parseInt(endDate) == 0)
		{
			break;
		}
		
		if(thisDate == parseInt(endDate) && thisMonth == parseInt(endMonth) && thisYear == parseInt(endYear))
	        {

        	    break;

       		}

		
		thisDate++;
		if(thisDate > 31)
		{
			thisDate = 1;
			thisMonth++;
		}
		if(thisMonth > 12)
		{
			break;
			thisMonth = 1;
			thisYear++;
		}
		
	}
	
	//for(thisDate = parseInt(startDate); thisDate <= parseInt(endDate); thisDate++)
	//{ 
	//	d_calendar.events.push(new D_Event(startYear, startMonth, thisDate, endYear, endMonth, endDate, startTime, endTime, title, eventStatus));
	//}
	
	
	
	reinitializeCells();
	prepareDays();
	D_Print(d_calendar.html());
	markToday();
	loadTooltips();
}