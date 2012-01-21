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



function triggerDateSelected(year, month, day)
{
	try
	{
		D_Calendar_Date_Selected(year, month, day);
	}
	catch(err)
	{
	}
}

function triggerMonthChanged(year, month)
{
	try
	{
		D_Calendar_Month_Changed(year, month);
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

function triggerDateSelected(year, month, day)
{
	try
	{
		D_Calendar_Date_Selected(year, month, day);
	}
	catch(err)
	{
	}
}

function triggerMonthChanged(year, month)
{
	try
	{
		D_Calendar_Month_Changed(year, month);
	}
	catch(err)
	{
	}
}