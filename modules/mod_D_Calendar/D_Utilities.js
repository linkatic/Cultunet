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



function D_bug() {

    this.debugElement = "debug";
    this.lineBreak = "<BR />";
    this.enabled = "true";
    this.print = function (txt) {
        if (this.enabled == "true") {
            document.getElementById(this.debugElement).innerHTML += txt + this.lineBreak;
        }
    }
}

function loadTooltips() {
    var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false });
}

function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.lastIndexOf('/'));
        return baseURL;
}

function D_Print(text) {
	try
	{
		document.getElementById(d_calendar.div).innerHTML = text;
	}
	catch(err)
	{
		document.getElementById("calendar").innerHTML = text;
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

function D_Config()
{
	this.date_format = "ymd";
	this.baseURL = "/";
	this.tooltip_position = 16;
}

function D_bug() {

    this.debugElement = "debug";
    this.lineBreak = "<BR />";
    this.enabled = "true";
    this.print = function (txt) {
        if (this.enabled == "true") {
            document.getElementById(this.debugElement).innerHTML += txt + this.lineBreak;
        }
    }
}

function loadTooltips() {
    var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false });
    var JTooltips = new Tips($$('.zoomTip'),
    { 
    	maxTitleChars: 50, fixed: true, showDelay:300, hideDelay:2000, className:'D', 
  	offsets:{
		'x': parseInt(d_config.tooltip_position),       //default is 16
		'y': 16        //default is 16
		}/*,
	
	onShow: function(toolTipElement){
    		toolTipElement.fade(0.8);
	},
	onHide: function(toolTipElement){
    		toolTipElement.fade(0);
	}*/

    } );
    	

}

function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.lastIndexOf('/'));
        return baseURL;
}

function D_Print(text) {
	try
	{
		document.getElementById(d_calendar.div).innerHTML = text;
	}
	catch(err)
	{
		document.getElementById("calendar").innerHTML = text;
	}
	
	//alert(document.getElementById("D_Calendar0").childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML);
	try
	{
		for(z = 0; z < 2; z++)
		{
			for(x = 1; x<=7; x++)
			{
				week = document.getElementById("D_Calendar" + z).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x];
			
				counting = 0;
				//for each day
				for(y = 0; y < 7; y++)
				{
					//alert(week.childNodes[y].innerHTML.indexOf("lightgray"));
					if(week.childNodes[y].innerHTML.indexOf("lightgray") >= 0 )
					{
						counting++;
					} 
				}
		
				if(counting == 7)
				{
					document.getElementById("D_Calendar" + z).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x].className= "hidden";
				}
			}
			for(x = 7; x>=1; x--)
			{
			    if(document.getElementById("D_Calendar" + z).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x].className == "hidden")
			    {
			        try
			        {
			              document.getElementById("D_Calendar" + z).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x].remove(0);
			        }
			        catch(err)
			        {
			            document.getElementById("D_Calendar" + z).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[x].removeNode(true);
			        }
			    }
			}
		}
		
	}
	catch(err)
	{}
}