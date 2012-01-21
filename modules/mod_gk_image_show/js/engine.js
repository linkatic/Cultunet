Fx.Opacity = Fx.Style.extend({initialize: function(el, options){this.now = 1;this.parent(el, 'opacity', options);},toggle: function(){return (this.now > 0) ? this.start(1, 0) : this.start(0, 1);},show: function(){return this.set(1);}});

window.addEvent("load",function(){
	$$(".gk_is_wrapper").each(function(el){
		var elID = el.getProperty("id");
		var wrapper = $(elID);
		var $G = $Gavick[elID];
		var opacity = $G['text_block_opacity'];
		var thumbs_array = $ES('.gk_is_thumb', wrapper);
		var slides = [];
		var contents = [];
		var links = [];
		var loadedImages = ($E('.gk_is_preloader', wrapper)) ? false : true;
	
		if(!loadedImages){
			var imagesToLoad = [];
			
			$ES('.gk_is_slide', wrapper).each(function(el,i){
				links.push(el.getFirst().getProperty('href'));
				var newImg = new Element('img',{
					"title":el.getProperty('title'),
					"class":el.getProperty('class'),
					"style":el.getProperty('style')
				});
				
				newImg.setProperty('alt',el.getChildren()[0].getProperty('href'));
				el.getFirst().remove();
				newImg.setProperty("src",el.innerHTML);
				imagesToLoad.push(newImg);
				newImg.injectAfter(el);
				el.remove();
			});
			
			var time = (function(){
				var process = 0;				
				imagesToLoad.each(function(el,i){
					if(el.complete) process++;
 				});
 				
				if(process == imagesToLoad.length){
					$clear(time);
					loadedImages = process;
					(function(){new Fx.Opacity($E('.gk_is_preloader', wrapper)).start(1,0);}).delay(400);
				}
			}).periodical(200);
		}
		
		var time_main = (function(){
			if(loadedImages){
				$clear(time_main);
				
				if(window.ie){
					$E('.gk_is_text_bg', wrapper).setOpacity($G['opacity']);
				}
				
				wrapper.getElementsBySelector(".gk_is_slide").each(function(elmt,i){
					slides[i] = elmt;
					if($G['slide_links']){
						elmt.addEvent("click", function(){window.location = elmt.getProperty('alt');});
						elmt.setStyle("cursor", "pointer");
					}
				});
				
				slides.each(function(el,i){
					if(i != 0) 
						el.setOpacity(0);
				});
				
				if($E(".gk_is_text_bg")){
					var text_block = $E(".gk_is_text_bg");
					wrapper.getElementsBySelector(".gk_is_text_item").each(function(elmt,i){
						contents[i] = elmt.innerHTML;
					});
				}
				
				$G['actual_slide'] = 0;
				if(thumbs_array) thumbs_array[0].toggleClass('active');
				
				if(wrapper.getElementsBySelector(".gk_is_text")[0]) 
					wrapper.getElementsBySelector(".gk_is_text")[0].innerHTML = contents[0];
				
				if($G['autoanim']){
					$G['actual_animation'] = (function(){
						gk_is_style1_anim(wrapper, contents, slides, thumbs_array, $G['actual_slide']+1, $G);
					}).periodical($G['anim_interval']+$G['anim_speed']);
				}
				
				thumbs_array.each(function(thumb, i){
					thumb.addEvent("click", function(){
						$clear($G['actual_animation']);
						gk_is_style1_anim(wrapper, contents, slides, thumbs_array, i, $G);
						$G['actual_animation'] = (function(){
							gk_is_style1_anim(wrapper, contents, slides, thumbs_array, $G['actual_slide']+1, $G);
						}).periodical($G['anim_interval']+$G['anim_speed']);
					});
				});
				
				if($G['thumbs_tooltips']){
					new Tips($$('.gk_is_tt'), {
    					initialize:function(){
							this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
						},
						onShow: function(toolTip) {
							this.fx.start(1);
						},
						onHide: function(toolTip) {
							this.fx.start(0);
						},
						className: 'is_style1'
   					});

				}
			}
		}).periodical(250);
	});
});

function gk_is_style1_text_anim(wrapper, contents, which, $G){
	var txt = $E(".gk_is_text");
	new Fx.Opacity(txt,{duration: $G['anim_speed']/2}).start(1,0);
	(function(){
		new Fx.Opacity(txt,{duration: $G['anim_speed']/2}).start(0,1);
		txt.innerHTML = contents[which];
	}).delay($G['anim_speed']);
}

function gk_is_style1_anim(wrapper, contents, slides, thumbs_array, which, $G){
	if(which != $G['actual_slide']){
		var max = slides.length-1;
		if(which > max) which = 0;
		var actual = $G['actual_slide'];
		
		$G['actual_slide'] = which;
		slides[$G['actual_slide']].setStyle("z-index",max+1);
		new Fx.Opacity(slides[actual],{duration: $G['anim_speed']}).start(1,0);
		new Fx.Opacity(slides[which],{duration: $G['anim_speed']}).start(0,1);
		if($E(".gk_is_text")) gk_is_style1_text_anim(wrapper, contents, which, $G);	
			
		switch($G['anim_type']){
			case 'opacity': break;
			case 'top': new Fx.Style(slides[which],'margin-top',{duration: $G['anim_speed']}).start((-1)*slides[which].getSize().size.y,0);break;
			case 'left': new Fx.Style(slides[which],'margin-left',{duration: $G['anim_speed']}).start((-1)*slides[which].getSize().size.x,0);break;
			case 'bottom': new Fx.Style(slides[which],'margin-top',{duration: $G['anim_speed']}).start(slides[which].getSize().size.y,0);break;
			case 'right': new Fx.Style(slides[which],'margin-left',{duration: $G['anim_speed']}).start(slides[which].getSize().size.x,0);break;
		}
				
		if(thumbs_array){
			thumbs_array[actual].setProperty('class','gk_is_thumb gk_is_tt');	
			thumbs_array[which].setProperty('class','gk_is_thumb gk_is_tt active');
		}
		(function(){slides[$G['actual_slide']].setStyle("z-index",$G['actual_slide']);}).delay($G['anim_speed']);
	}
}