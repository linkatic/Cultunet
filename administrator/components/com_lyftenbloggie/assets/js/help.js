/***************************
HelpTip v1.0 by Lyften Designs
http://www.lyften.com
****************************/

	function ShowHelp(item, title, desc)
	{
		item = document.getElementById(item);
		div = document.createElement('div');
		div.id = 'help';

		div.style.display = 'inline';
		div.style.position = 'absolute';
		div.style.width = '350';

		div.style.backgroundColor = '#FEFCD5';
		div.style.border = 'solid 1px #E7E3BE';
		div.style.padding = '10px';
		div.innerHTML = '<span class=helpTip><strong>' + title + '<\/strong><\/span><br /><div style="padding-left:10; padding-right:5" class=helpTip>' + desc + '<\/div>';

		var parent = item.parentNode;
		if(item.nextSibling)
			parent.insertBefore(div, item.nextSibling);
		else
			parent.appendChild(div)
	}

	function HideHelp(item)
	{
		item = document.getElementById(item);
		div = document.getElementById('help');
		if (div) {
			item.parentNode.removeChild(div);
		}
	}
