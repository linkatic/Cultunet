var tabsClass = {
	tabSetArray: new Array(),
	classOn: "active",
	classOff: "notactive",
	
	addTabs: function (tabsContainer) {
		tabs = document.getElementById(tabsContainer).getElementsByTagName("div");
		for (x in tabs) {
			if (typeof(tabs[x].id) != "undefined") {
				this.tabSetArray.push(tabs[x].id);
			} else {}
		}
	},

	switchTab: function (element) {
		for (x in this.tabSetArray) {
			tabItem = this.tabSetArray[x];
			dataElement = document.getElementById(tabItem + "_data");
			if (dataElement) {
				if (dataElement.style.display != "none") {
					dataElement.style.display = "none";
				} else {}
			} else {}

			tabElement = document.getElementById(tabItem);
			if (tabElement) {
				if (tabElement.className != this.classOff) {
					tabElement.className = this.classOff;
				} else {}
			} else {}
		}

		document.getElementById(element.id + "_data").style.display = "";
		element.className = this.classOn;
	}
};
function ShowHelp(item, title, desc)
{
	item = document.getElementById(item);
	div = document.createElement('div');
	div.id = 'help';

	div.style.display = 'inline';
	div.style.position = 'absolute';
	div.style.width = '350';
	div.style.zIndex = 100000;

	div.style.backgroundColor = '#FEFCD5';
	div.style.border = 'solid 1px #E7E3BE';
	div.style.padding = '10px';
	div.innerHTML = '<span class=helpTip><strong>' + title + '<\/strong><\/span><br /><div style="padding-top: 4px" class=helpTip>' + desc + '<\/div>';

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
