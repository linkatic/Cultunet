Dione Magic Calendar is free highly configurable Joomla!  plugin that adds calendar functionality to your pages.
You can customize the date format and language, restrict the selectable date ranges and add in buttons and other navigation options easily.

Features:
* Joomla 1.5 Native Extension
* Uses CSS 3 feature - nice viewing of images in all modern browsers - IE, Firefox, Safari, Chrome, Opera
* Support of three various modes of viewing of the calendar
* Possibility to customize the date format and language
* Absolute complete process control of the calendar's viewing by means of many parameters
* Nice navigation of the calendar viewing
* Pure JS and PHP codes (no Flash)
* All customisations of the plugin can be customised both on the Back-End side, and in the code of your HTML-page
* Generates W3C valid XHTML and adds no JS global variables & passes JSLint
  
Used parameters:
All customisations of the Dione Magic Calendar Slider can be made on the Back-End.

* showOn  				Mode of viewing ('focus' for popup on focus, 'button' for trigger button, or 'both' for either).
* lang 					Language of the interface.
* appendText	   		Display text following the input box, e.g. showing the format.
* nextText	   			Inner text for 'next' button. Default value is 'Next'.
* buttonText  			Text for trigger button.
* buttonImage 	   		URL for trigger button image.
* buttonImageOnly	   	True if the image appears alone, false if it appears on a button.
* changeMonth	   		True if month can be selected directly, false if only prev/next.
* changeYear	       	True if month can be selected directly, false if only prev/next.
* yearRange	       		Range of years to display in drop-down, either relative to today's year (-nn:+nn), relative to currently displayed year (c-nn:c+nn), absolute (nnnn:nnnn), or a combination of the above (nnnn:-n).
* showOtherMonths   	True to show dates in other months, false to leave blank.
* selectOtherMonths		True to allow selection of dates in other months, false for unselectable.
* showWeek   			True to show week of the year, false to not show it.
* shortYearCutoff	  	Short year values < this are in the current century, > this are in the previous century, string value starting with '+' for current year + value.
* numberOfMonths   		Number of months to show at a time.
* showCurrentAtPos	  	The position in multipe months at which to show the current month (starting at 0).
* stepMonths   			Number of months to step back/forward.
* stepBigMonths	  		The position in multipe months at which to show the current month (starting at 0).
* autoSize   			True to size the input for the date format, false to leave as is.

Example of usage:
For usage of the plugin you should specify on you page this template:
   {dionemagiccalendar id=".." parameters=".."} 
   {/dionemagiccalendar}

For usage of this plugin you should define the tag 'dionemagiccalendar' on your page.
For the option 'parameters' of the tag 'dionemagiccalendar' you can use all parameters, which are available on the Joomla!'s Back-End side (the description of usage of these parameters is placed above).

The plugin creates this construction on your page, which you can use in your next processing:
<input type="text" id="magiccalendar_[YOUR_TEMPLATE'S ID]>

Example
 If you wish to view the calendar in the basic mode use the following example. In this case you will use all defaults parameters:
  {dionemagiccalendar id="1" parameters=""}
  {/dionemagiccalendar}