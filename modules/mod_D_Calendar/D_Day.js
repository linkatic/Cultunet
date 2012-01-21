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



function D_Day(year, month, day) {
    this.year = year;
    this.month = month;
    this.dayNumber = day;

    this.onclick = "setSelectedDay";

    this.cellPrefix = "day";
    this.linkPrefix = "link";
	
    this.mootips = "true";
    if(d_mootips == "0")
    {
    	this.mootips = "true";
    }
    else
    {
    	this.mootips = "false";
    }
    
    
    this.mooclass = "";
    if(this.mootips == "true")
    {
    	this.mooclass = "hasTip";
    }
    else
    {
    	this.mooclass = " ";
    }

    this.cellclass = "day";

    this.isActive = "false";
    this.activeStyle = " style='font-weight:bold;' ";

    	this.title = function() {
    		var txt = "";
    		txt = this.year + "/" + this.month + "/" + this.dayNumber;
    		
    		if(this.mootips == "true")
    		{
    			txt += "::";
    		}
    		
    		var entry = "";
    		if(this.mootips == "true")
    		{
    			//entry += "<ul>";
    		}
    		else
    		{
    			entry += ": \n";
    		}
    		
    		var tip = "";
    		for(var eventID = 0; eventID < d_calendar.events.length; eventID ++)
    		{
    			var thisEvent = d_calendar.events[eventID];
    			
    			if(thisEvent.startYear == this.year && thisEvent.startMonth == this.month && thisEvent.startDate == this.dayNumber)
    			{
    				if(this.mootips == "true")
    				{
    					tip += "<p><b>";//"<li><b>";
    				}
    				tip += thisEvent.startTime + " - " + thisEvent.endTime;
    				if(this.mootips == "true")
    				{
    					tip += "</b>: ";
    				}
    				else
    				{
    					tip += ": ";
    				}
    				tip += thisEvent.title;
    				if(this.mootips == "true")
    				{
    					tip += ".</p>";//".</li>";
    				}
    				else
    				{
    					tip += ". \n";
    				}
    				this.isActive = "true";
    			}
    			
    		}
    		
    		var outro = "";
    		if(this.mootips == "true")
    		{
    			//outro += "</ul>";
    		}
    		
    		if(tip != "")
    		{
    			txt += entry + tip + outro;
    		}
    		
    		return txt;
	}

    this.html = function () {
        
        var thisTitle = this.title(); //to init the isActive as well
        
        if (this.isActive == "true") {
            this.activeStyle = " style='font-weight:bold;' ";
        }
        else {
            this.activeStyle = "";
        }
        
        thisText = "<td class='" + this.cellclass + "'";
        if(this.dayNumber > 0)
        {
        	thisText +=  " id='" + this.cellPrefix + "_" + this.year + "_" + this.month + "_" + this.dayNumber + "'";
        }
        thisText += ">";
        if(this.dayNumber > 0)
        {
        	thisText += "<a  title='" + thisTitle + "' onclick='" + this.onclick + "(" + this.year + ", " + this.month + ", " + this.dayNumber + "); return false;' href='#' id='" + this.linkPrefix + "_" + this.year + "_" + this.month + "_" + this.dayNumber + "' class='" + this.mooclass + "' " + this.activeStyle + ">" + this.dayNumber + "</a>";
	}
	else
	{
		thisText += "<span style='color : lightgray;'>" + Math.abs(this.dayNumber) + "</span>";
	}
	thisText += " </td>";
        return thisText;
        
        
    }
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

function D_Day(year, month, day) {
    this.year = year;
    this.month = month;
    this.dayNumber = day;
    
    this.dayEventCount = 0;
    this.dayEventClosed = 0;
    this.dayEventClass = " ";

    this.onclick = "setSelectedDay";

    this.cellPrefix = "day";
    this.linkPrefix = "link";
	
    this.mootips = "true";
    if(d_mootips == "0")
    {
    	this.mootips = "true";
    }
    else
    {
    	this.mootips = "false";
    }
    
    
    this.mooclass = "";
    if(this.mootips == "true")
    {
    	this.mooclass = "zoomTip";
    }
    else
    {
    	this.mooclass = " ";
    }

    this.cellclass = "day";
//this.cellclass = "hidden";

    this.isActive = "false";
    this.activeStyle = " style='font-weight:bold;' ";

    	this.title = function() {
    		var txt = "";
    		//alert(d_config.date_format);
    		
    		if(d_config.date_format == "ymd")
    		{
    			txt = this.year + " / " + this.month + " / " + this.dayNumber;
    		}
    		
    		if(d_config.date_format == "mdy")
    		{
    			txt = this.month + " / " + this.dayNumber+ " / " + this.year;
    		}
    		if(d_config.date_format == "dmy")
    		{
    			txt = this.dayNumber + " / " + this.month + " / " + this.year;
    		}
    		
    		
    		if(this.mootips == "true")
    		{
    			txt += "::";
    		}
    		
    		var entry = "";
    		if(this.mootips == "true")
    		{
    			//entry += "<ul>";
    		}
    		else
    		{
    			entry += ": \n";
    		}
    		
    		var tip = "";
    		for(var eventID = 0; eventID < d_calendar.events.length; eventID ++)
    		{
    			var thisEvent = d_calendar.events[eventID];
    			
    			if(thisEvent.startYear == this.year && thisEvent.startMonth == this.month && thisEvent.startDate == this.dayNumber)
    			{
    				if(thisEvent.eventStatus == "free")
    				{
    					this.dayEventCount++;
    				}
    				if(thisEvent.eventStatus == "not-free")
    				{
    					this.dayEventCount++;
    					this.dayEventClosed++;
    				}
    				
    				if(this.mootips == "true")
    				{
    					tip += "<p><b>";//"<li><b>";
    				}
    				//tip += thisEvent.startTime + " - " + thisEvent.endTime;
    				if(this.mootips == "true")
    				{
    					tip += "</b> ";
    				}
    				else
    				{
    					tip += ": ";
    				}
    				tip += thisEvent.title;
    				if(this.mootips == "true")
    				{
    					//tip += ".</li>";
    				}
    				else
    				{
    					tip += ". \n";
    				}
    				this.isActive = "true";
    			}
    			
    		}
    		
    		//remove the mootip if there are no events
    		if(tip == "")
    		{
    			this.mooclass = " ";
    			txt = txt.replace("::", "");
    		}
    		
    		
    		var outro = "";
    		if(this.mootips == "true")
    		{
    			//outro += "</ul>";
    		}
    		
    		if(tip != "")
    		{
    			txt += entry + tip + outro;
    		}
    		
    		return txt;
	}

    this.html = function () {
        
        var thisTitle = this.title(); //to init the isActive as well
        
        if(this.dayEventCount > 0)
        {
        	if(this.dayEventClosed > (this.dayEventCount / 2) && this.dayEventClosed != this.dayEventCount)
        	{
        		this.dayEventClass = " restricted";
        	}
        	else if (this.dayEventClosed == this.dayEventCount)
        	{
        		this.dayEventClass = " not-free";
        	}
        	else
        	{
        		this.dayEventClass = " free";
        	}
        }
        else
        {
        	this.dayEventClass = " ";
        }
        
        if (this.isActive == "true") {
            this.activeStyle = " style='font-weight:bold;' ";
			this.dayEventClass = " event";
        }
        else {
            this.activeStyle = "";
        }
        
        thisText = "<td class='" + this.cellclass + this.dayEventClass + "'";
        if(this.dayNumber > 0)
        {
        	thisText +=  " id='" + this.cellPrefix + "_" + this.year + "_" + this.month + "_" + this.dayNumber + "'";
        }
        thisText += ">";
        if(this.dayNumber > 0)
        {
        	thisText += "<a  title='" + thisTitle + "' onclick='" + this.onclick + "(" + this.year + ", " + this.month + ", " + this.dayNumber + "); return false;' href='#' id='" + this.linkPrefix + "_" + this.year + "_" + this.month + "_" + this.dayNumber + "' class='" + this.mooclass + "' " + this.activeStyle + ">" + this.dayNumber + "</a>";
	}
	else
	{
		thisText += "<span style='color : lightgray;'>" + Math.abs(this.dayNumber) + "</span>";
	}
	thisText += " </td>";
        return thisText;
        
        
    }
}

