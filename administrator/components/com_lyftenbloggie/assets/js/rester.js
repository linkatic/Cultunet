var reseter = new Class(  
{  
	options:  {
		id: "",
		script_url: "index.php?option=com_lyftenbloggie&format=raw",
		task: ""
},

initialize: function( name, options )  
{  
	this.setOptions( options );
	this.name = name;
},  

reset: function( task, id, div, controler )
{
	var url = 'index.php?option=com_lyftenbloggie&controller=' + controler + '&task=' + task + '&id=' + id + '&format=raw';

	var resetajax = new Ajax(url, {
		method: 'get',
		update: div,
		evalScripts: false
		});
	resetajax.request();
}

});

reseter.implement( new Options, new Events );