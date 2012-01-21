/**
 * JavaScript Document
 */
function showAdapterTasks(elem)
{
    if( $(elem).style.display == 'block' ){
        $(elem).style.display = 'none';
    }
    else{
        $(elem).style.display = 'block';
    }
}

function closeAdapterTasks(elem)
{
	$(elem).style.display = 'none';
}

function writeAdapterTask(divElem,taskName)
{
    if( $(divElem).innerHTML.match(/None*/) ){
        $(divElem).innerHTML = taskName;
    } else {
        $(divElem).innerHTML += ","+taskName;
    }
}

function eraseAdapterTask(divElem,taskName)
{
    if( trim($(divElem).innerHTML) == taskName ){
        $(divElem).innerHTML = 'None';
    }
    else{
        var expressao = $(divElem).innerHTML.split(",");
        var total = expressao.length;
        
        for( var num = 0 ; num < total ; num++ ){
            if( trim(expressao[0]) == taskName && num == 0 ){
                $(divElem).innerHTML = $(divElem).innerHTML.replace(taskName+",",'');
                break;
            }
            else{
                $(divElem).innerHTML = $(divElem).innerHTML.replace(","+taskName,'');
                break;
            }
        }
    }
}

function checkAll( n, fldName,toggleName )
{
	if (!fldName) {
		fldName = 'cb';
	}

	if(!toggleName){
		toggleName = 'toogle';
	}
    
	var f = document.adminForm;
	var c = document.getElementById(toggleName).checked;
	var n2 = 0;

	for (i=0; i < n; i++) {

		cb = eval( 'f.' + fldName + '' + i );
		if ( cb && cb.disabled == false ) {
			cb.checked = c;
			n2++;
		}
	}

	if (c) {
		document.adminForm.boxchecked.value = new Number(document.adminForm.boxchecked.value) + new Number(n2);
	} else {
		if( document.adminForm.boxchecked.value > 0 ){
			document.adminForm.boxchecked.value = new Number(document.adminForm.boxchecked.value) - n;
		}
		else {
			document.adminForm.boxchecked.value = 0;
		}
	}
}

function writeEraseAdapterTasks(n, fldName, toggleName, divElem)
{
    var f = document.adminForm;
	var c = document.getElementById(toggleName).checked;
	var n2 = 0;

	for ( var i=0 ; i < n ; i++ ) {

		cb = eval( 'f.' + fldName + '' + i );
		if ( cb && cb.disabled == false ) {
			if( cb.checked ){
                eraseAdapterTask(divElem,cb.value);
                writeAdapterTask(divElem,cb.value);
            }
            else{
                eraseAdapterTask(divElem,cb.value);
            }

			n2++;
		}
	}
}

function defaultAdaptersMenuExecute(id,total) {
    for(var n = 0; n <= total ; n++){
            var divDisplay = "Adapter" + n;
            if( document.getElementById(divDisplay) ){
                document.getElementById(divDisplay).style.display = 'none';
            }
    }
    var divDisplay = "Adapter" + id;
    document.getElementById(divDisplay).style.display = 'block';
}
