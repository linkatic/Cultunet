Fx.Opacity = Fx.Style.extend({initialize: function(el, options){this.now = 1;this.parent(el, 'opacity', options);},toggle: function(){return (this.now > 0) ? this.start(1, 0) : this.start(0, 1);},show: function(){return this.set(1);}});

window.addEvent("load",function(){
	$$(".gk_is_wrapper").each(function(el){
		var elID = el.getProperty("id");
		var wrapper = $(elID);
		var $G = $Gavick[elID];
		var opacity = $G['text_block_opacity'];
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
						
					slides.each(function(elmts){
						elmts.addEvent("mouseenter", function(){})
					});
				});
				
				$G['actual_slide'] = 0;
				
				if($G['autoanim']){
					$G['actual_animation'] = (function(){
						gk_is_style2_anim(wrapper, contents, slides, $G['actual_slide']+1, $G);
					}).periodical($G['anim_interval']+$G['anim_speed']);
				}
				
				if($E('.gk_is_overlay', wrapper)){
					var overlay = $E('.gk_is_overlay', wrapper);
					var fx = new Fx.Opacity(overlay,{duration:$G['overlay_speed'], wait:true});
					overlay.setStyle("display", "block");
					overlay.setOpacity(0);
					wrapper.addEvent("mouseenter", function(){
						fx.start($G['overlay_opacity']);
					});
					wrapper.addEvent("mouseleave", function(){
						fx.start(0);
					});
					overlay.addEvent("click", function(){
						window.location = overlay.getProperty("title");
					});
					overlay.setProperty("title", slides[0].getProperty("alt"));
				}
			}
		}).periodical(250);
	});
});

function gk_is_style2_anim(wrapper, contents, slides, which, $G){
	if(which != $G['actual_slide']){
		var max = slides.length-1;
		if(which > max) which = 0;
		var actual = $G['actual_slide'];
		
		$G['actual_slide'] = which;
		slides[$G['actual_slide']].setStyle("z-index",max+1);
		new Fx.Opacity(slides[actual],{duration: $G['anim_speed']}).start(1,0);
		new Fx.Opacity(slides[which],{duration: $G['anim_speed']}).start(0,1);
		$E('.gk_is_overlay', wrapper).setProperty("title", slides[which].getProperty("alt"));
		
		switch($G['anim_type']){
			case 'opacity': break;
			case 'top': new Fx.Style(slides[which],'margin-top',{duration: $G['anim_speed']}).start((-1)*slides[which].getSize().size.y,0);break;
			case 'left': new Fx.Style(slides[which],'margin-left',{duration: $G['anim_speed']}).start((-1)*slides[which].getSize().size.x,0);break;
			case 'bottom': new Fx.Style(slides[which],'margin-top',{duration: $G['anim_speed']}).start(slides[which].getSize().size.y,0);break;
			case 'right': new Fx.Style(slides[which],'margin-left',{duration: $G['anim_speed']}).start(slides[which].getSize().size.x,0);break;
		}
	
		(function(){slides[$G['actual_slide']].setStyle("z-index",$G['actual_slide']);}).delay($G['anim_speed']);
		
		
	}
}