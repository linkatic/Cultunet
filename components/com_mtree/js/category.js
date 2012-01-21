function loadcat(){
	cc(this.value);
}
function addSecCat(){
	if(active_cat>=0){
		if(jQuery('#other_cats').val()!=''){
			jQuery('#other_cats').val(jQuery('#other_cats').val()+','+active_cat);
		}else{
			jQuery('#other_cats').val(active_cat);
		}
		var newLi = document.createElement("LI");
		newLi.id = 'lc'+active_cat;
		var newLink=document.createElement("A");
		newLink.href="javascript:remSecCat("+active_cat+")";
		newLink.appendChild(document.createTextNode(txtRemove));
	    newLi.appendChild(newLink);
	    var liTxt = document.createTextNode(jQuery('#mc_active_pathway').text());
	    newLi.appendChild(liTxt);
		gebid('linkcats').appendChild(newLi);					
	}		
	toggleMcBut(active_cat);
}
function remSecCat(cat_id){
	var oc=jQuery('#other_cats').val().split(',');
	if(oc!=''){
		var new_oc=new Array();
		for (var i=0; i < oc.length; i++) {
			if(oc[i]!=cat_id) {
				new_oc.push(oc[i]);
			}
		};
	}
	document.adminForm.other_cats.value=new_oc.join(',');
	var li=gebid('lc'+cat_id);
	li.parentNode.removeChild(li);
	toggleMcBut(active_cat);
			
}
function updateMainCat(){

	var newLi = document.createElement("LI");
    var liTxt = document.createTextNode(jQuery('#mc_active_pathway').text());
	newLi.id = 'lc'+active_cat;
    newLi.appendChild(liTxt);
	var i=0;
	do {
		var oldLi = gebid('linkcats').childNodes[i++];
	} while(oldLi.nodeType != 1)
	gebid('linkcats').replaceChild(newLi,oldLi);
	jQuery('#lc'+active_cat).html(jQuery('#lc'+active_cat).html());
	document.adminForm.cat_id.value=active_cat;
	toggleMcBut(active_cat);
	togglemc();
	
	//this.updateFormAddRecurso();
}

function updateFormAddRecurso()
{
	/*
	 * field1 -> Título
	 * field2 -> Descripción
	 * field3 -> Autor
	 * field4 -> Dirección 
	 * field5 -> Ciudad
	 * field6 -> Provincia
	 * field7 -> País
	 * field8 -> Código Postal
	 * field9 -> Teléfono
	 * field10 -> Fax
	 * field11 -> Email
	 * field12 -> Website
	 * field13 ->
	 * field14 ->
	 * field15 ->
	 * field16 ->
	 * field17 ->
	 * field18 ->
	 * field19 ->
	 * field20 ->
	 * field21 ->
	 * field22 ->
	 * field23 ->
	 * field24 -> Fichero
	 * field25 -> 
	 * field26 ->
	 * field27 ->
	 * field28 ->
	 * field29 -> Persona de contacto
	 * field30 -> Nombre de director
	 * field31 -> Entidad
	 * field32 -> Área
	 * field33 -> Fecha inicio
	 * field34 -> Fecha Fin
	 * field35 -> Facebook
	 * field36 -> Twitter
	 * field37 -> Tuenti
	 * field38 -> Cultenet
	 * field39 ->
	 * field40 ->
	 * field41 ->
	 * field42 ->
	 * field43 ->
	 * field44 -> Prácticas
	 * field45 -> Tipo de convocatoria
	 * field46 -> Importe
	 * field47 -> Número de plazas
	 * field48 -> Año de fundación
	 * field49 -> Institución de la que depende
	 * field50 -> Tipo de equipamiento (equipamientos culturales)
	 * field51 -> Tipo de organización
	 * field52 -> Titularidad Pública
	 * field53 -> Número de metros cuadrados
	 * field54 -> Horario
	 * field55 -> Tipo de equipamiento (centros de recursos y bibliotecas)
	 * field56 -> Área de actuación
	 * field57 -> Servicios/Productos
	 * field58 -> Número de expositores
	 * field59 -> Precio medio
	 * field60 -> Número medio de asistentes
	 * field61 -> Duración
	 * field62 -> Tipo de institución
	 * field63 -> Tengo los derechos de autoría
	 * field64 -> Idioma
	 * field65 -> Audio
	 * field66 -> Video
	 * field67 -> Autor
	 * 
	 */
	
	/*
	var Agenda = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field29","field31","field32", 
	"field33","field34","field35","field36","field37","field38", "field46","field47" 
	];

	var Mediateca = [
	"field11","field32", "field36","field37","field38",
	"field48","field63","field64","field65","field66", "field67"
	];
	
	var CentrosRecursosBibliotecas = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field30","field32","field35",
	"field36","field37","field38","field44", "field49","field52","field53","field54","field55" 
	];
	var Convocatorias = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field31","field32", "field33",
	"field34", "field35","field36","field37","field38","field45", "field46", "field47"
	];
	var Empresas = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field30","field32","field35",
	"field36","field37","field38","field44","field48","field54","field56", "field57"
	];
	var EquipamientosCulturales = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field30","field32","field35",
	"field36","field37","field38","field44","field48", "field49", "field50", "field51",
	"field52","field53","field54"
	];
	var Estudios = ["field1"];
	
	var FeriasComerciales = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field32", "field33",
	"field34", "field35","field36","field37","field38", "field48", "field53",
	"field54", "field58","field59"
	];
	var Festivales = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field32", "field33",
	"field34", "field35","field36","field37","field38","field54","field59","field60","field61"
	];
	var InstitucionesCulturales = [
	"field4","field5","field6","field7","field8","field9",
	"field10","field11","field12","field24","field29","field30","field32","field35",
	"field36","field37","field38","field44", "field49", "field52", "field54", "field62"
	];
	
	var categoria = document.getElementById('linkcats');
	
	//Ocultamos inicialmente campos no comunes
	/* var fields = document.getElementById('addlisting-form').getElementsByTagName('tr');
	for(var i=4; i<fields.length; i++) {
  		var field = fields[i];
		field.style.display = 'none';
	} */
	
	/*
	
	var fields = document.getElementById('addlisting-form').getElementsByTagName('tr');
	var arrayDeleteElements = new Array();
	
	for(var i=4; i<fields.length; i++) {
		var field = fields[i];
		
		//Agenda	
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Agenda")
		{
				var fieldVisible = Agenda.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}

		//Mediateca		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Mediateca")
		{
				var fieldVisible = Mediateca.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Centros de Recursos y Bibliotecas
			
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Centros de Recusos y Bibliotecas")
		{	
				var fieldVisible = CentrosRecursosBibliotecas.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
		
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Convocatorias
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Convocatorias")
		{
				var fieldVisible = Convocatorias.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Empresas
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Empresas")
		{
				var fieldVisible = Empresas.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Equipamientos Culturales
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Equipamientos Culturales")
		{
				var fieldVisible = EquipamientosCulturales.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Ferias Comerciales
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Ferias Comerciales")
		{
				var fieldVisible = FeriasComerciales.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Festivales
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Festivales")
		{
				var fieldVisible = Festivales.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
		
		//Instituciones Culturales
		
		if(categoria.getElementsByTagName('li')['0'].textContent == "Directorio  >Instituciones Culturales")
		{
				var fieldVisible = InstitucionesCulturales.some(function(elemento,indice)
				{
					return elemento == field.id;
					
				});
				
				if (!fieldVisible) {
					var nodo = document.getElementById(field.id);
					arrayDeleteElements.push(nodo);
				} 
		}
	
	}//fin for	
	
	//Borramos los elementos que no deben mostrarse en la categoría
	for(i=0;i<arrayDeleteElements.length; i++)
	{
		arrayDeleteElements[i].parentNode.removeChild(arrayDeleteElements[i]);	
	}
	
	*/
	
}

function cc(parent_cat_id){
	if(parent_cat_id >= 0 && parent_cat_id != '') {
		jQuery.ajax({
		  type: "POST",
		  url: mosConfig_live_site+"/index.php",
		  data: "option=com_mtree&task=getcats&cat_id="+parent_cat_id+"&format=raw&tmpl=component",
		  dataType: "html",
		  success: function(str){
				if(str != 'NA') {
					var cats = str.split("\n");
					totalcats = cats.length;
					if ( totalcats > 1 ) {
						clearList('browsecat');
						jQuery('#mc_active_pathway').html(cats[0]);
						for (c=1; c < totalcats; c++) {
							if(cats[c].length>2) {
								var s = cats[c].split("|");
								gebid('browsecat').options[c-1] = new Option(s[1],s[0]);
							}
						}
					}
					active_cat = parent_cat_id;
					switch(document.adminForm.task.value){
						case 'editlink':
						case 'savelisting':
						case 'editcat':
							toggleMcBut(parent_cat_id);
							break;
						case 'cats_copy':
						case 'cats_move':
						case 'links_move':
						case 'links_copy':
							document.adminForm.new_cat_parent.value=active_cat;
							break;
					}
				}
			}
		});	
	}
}
function toggleMcBut(cat_id){
	if(gebid('mcbut1') != null) {
		if(inOtherCats(cat_id)){
			gebid('mcbut1').disabled=true;
			if(gebid('mcbut2')) { gebid('mcbut2').disabled=true; }
			jQuery('#mc_active_pathway').css('background-color','#f9f9f9');
			jQuery('#mc_active_pathway').css('color','#C0C0C0');
		}else{
			gebid('mcbut1').disabled=false;
			if(gebid('mcbut2')) { gebid('mcbut2').disabled=false; }
			jQuery('#mc_active_pathway').css('background-color','#FFF');
			jQuery('#mc_active_pathway').css('color','#000');
		}		
	}
}
function inOtherCats(target){
	if(target==document.adminForm.cat_id.value) {
		return true;
	}
	var other_cats = jQuery('#other_cats').val();
	if(other_cats != ''){
		other_cats = other_cats.split(',');
		for (var i=0; i < other_cats.length; i++) {
			if(other_cats[i] == target){
				return true;
			}
		}
	}
	return false;
}
function togglemc() {
	if(jQuery('#mc_con').css('display')=='none'){
		jQuery('#mc_con').slideDown('slow');
	} else {
		jQuery('#mc_con').slideUp('slow');
	}
}
function gebid(id){return document.getElementById(id);}
function clearList(id) {
	var clength = gebid(id).length;
	for(var i=(clength-1);i>=0;i--) {gebid(id).remove(i);}
}

