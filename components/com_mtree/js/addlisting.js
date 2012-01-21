jQuery(document).ready(function(){
	toggleMcBut(active_cat);
	jQuery('#browsecat').click(function(){
		cc(jQuery(this).val());
	});
	if(jQuery("#sortableimages").length>0)
	{
		jQuery("#sortableimages").sortable();
		jQuery('li input[@type=checkbox]').click(function(){
			enforceImageLimit();
			if(!jQuery(this).attr('checked')) {
				jQuery(this).parent().css('opacity',0.3);
			} else {
				jQuery(this).parent().css('opacity',1);
			}
		});
		enforceImageLimit();
	}
});
function isEmpty(element){
	var fe=document.adminForm.elements[element];
	if(typeof fe == 'undefined') {
		fe=document.adminForm.elements[element+'[]'];
	}
	if(fe.type==undefined){
		for(var i=0;i<fe.length;i++) {
			if(fe[i].checked){return false;}
		}
		return true;
	} else if ((fe.type=='radio'||fe.type=='checkbox') && fe.checked==false) {
		return true;	
	} else if (fe.value=='') {
		return true;
	} else {
		return false;
	}
}
function addAtt() {
	if((attCount + jQuery('li input[@type=checkbox][@checked]').length)<=maxAtt) {
		var newLi = document.createElement("LI");
		newLi.id="att"+attNextId;
/*		newLi.style.marginRight="5px";
		newLi.style.position="relative";
		newLi.style.left="17px";
*/
		newLi.style.float="none";
		var newFile=document.createElement("INPUT");
		newFile.className="text_area";
		newFile.name="image[]";
		newFile.type="file";
		newFile.size="28";
		newLi.appendChild(newFile);
		var newLink=document.createElement("A");
		newLink.href="javascript:remAtt("+attNextId+")";
		removeText=document.createTextNode(txtRemove);
		newLink.appendChild(removeText);
		newLi.appendChild(newLink);
		gebid('uploadimages').appendChild(newLi);
		attCount++;
		attNextId++;
	}
	enforceImageLimit();
}
function remAtt(id) {gebid('uploadimages').removeChild(gebid('att'+id));attCount--;enforceImageLimit();}
function enforceImageLimit() {
	var attTotal = attCount + jQuery('li input[@type=checkbox][@checked]').length;
	if( typeof maxAtt != 'undefined' && attTotal>=maxAtt) {
		jQuery('#add_att').text('');
		if(attTotal>maxAtt) {
			remAtt(--attNextId);
		}
	} else {
		jQuery('#add_att').text(msgAddAnImage);
	}
}
function hasExt(string,ext){
	var ext=string.match(new RegExp("("+ext+")$","i"));
	if(string != '' && ext==null){
		return false;
	}else{
		return true;
	}
}
function checkImgExt(attCount,img){
	if(attCount==1) {
		if(img.val() != '' && !hasExt(img.val(),'png|jpe?g|gif')){
			return false;
		}
	} else {
		img.each(function(i){
			if(jQuery(this).val() != '' && !hasExt(jQuery(this).val(),'png|jpe?g|gif')){
				return false;
			}
		 });

	}
	return true;
}