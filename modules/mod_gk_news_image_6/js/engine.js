Fx.Height = Fx.Style.extend({initialize: function(el, options){$(el).setStyle('overflow', 'hidden');this.parent(el, 'height', options);},toggle: function(){var style = this.element.getStyle('height').toInt();return (style > 0) ? this.start(style, 0) : this.start(0, this.element.scrollHeight);},show: function(){return this.set(this.element.scrollHeight);}});Fx.Opacity = Fx.Style.extend({initialize: function(el, options){this.now = 1;this.parent(el, 'opacity', options);},toggle: function(){return (this.now > 0) ? this.start(1, 0) : this.start(0, 1);},show: function(){return this.set(1);}});
//
window.addEvent("load",function(){
	document.getElementsBySelector(".gk_ni_6_wrapper").each(function(el){
		// generowanie rdzenia
		var mainwrap = el;
		var elID = el.getProperty("id");
		var $G = $Gavick[elID]; 
		var wrap = $(elID);
		var mouseIsOver = false;
		var scrollValue = 0;
		//
		var addWidth = $E("div",el).getStyle("padding-left").toInt() + $E("div",el).getStyle("padding-right").toInt() + $E("div",el).getStyle("margin-right").toInt();
		//
		el.setStyle("width",(el.getStyle("width").toInt() + addWidth) + "px");
		//
		$G["actual_slide"] = -1;
		$G["actual_anim"] = false;
		$G["actual_anim_p"] = false;
		//
		var slides = [];
		var contents = [];
		var pasek = false;
		//
		if(window.ie && $E(".gk_ni_6_text_bg", wrap)) $E(".gk_ni_6_text_bg",wrap).setOpacity($G["opacity"].toFloat());
		//
		wrap.getElementsBySelector(".gk_ni_6_slide").each(function(elmt,i){slides[i] = elmt;});
		slides.each(function(el,i){if(i != 0) el.setOpacity(0);});
		//
		if($E(".gk_ni_6_text_bg", wrap)){
			var text_block = $E(".gk_ni_6_text_bg",wrap);
			$ES(".gk_ni_6_news_text",wrap).each(function(el,i){contents[i] = el.innerHTML;});
		}
		// animacje
		var amount_c = contents.length-1;
		if($E(".gk_ni_6_text", wrap)) $E(".gk_ni_6_text",wrap).innerHTML = contents[0];
		//
		var loadedImages = ($E('.gk_ni_6_preloader', wrap)) ? false : true;
		//
		if($E('.gk_ni_6_preloader', wrap)){
			var imagesToLoad = [];
			//
			$ES('.gk_ni_6_slide',wrap).each(function(el,i){
				var newImg = new Element('img',{
					"src" : el.innerHTML,
					"alt" : el.getProperty('title')
				});
				imagesToLoad.push(newImg);
				el.innerHTML = '';
				newImg.injectInside(el);
			});
			//
			var timerrr = (function(){
				var process = 0;				
				imagesToLoad.each(function(el,i){
					if(el.complete) process++;
 				});
 				//
				if(process == imagesToLoad.length){
					$clear(timerrr);
					loadedImages = process;
					(function(){new Fx.Opacity($E('.gk_ni_6_preloader', wrap)).start(1,0);}).delay(400);
				}
			}).periodical(200);
		}
		
		var timerrr2 = (function(){
			if(loadedImages){
			$clear(timerrr2);
			// ----------	
			var NI2 = new news_image_6();
			//
			$ES(".gk_ni_6_tab",mainwrap).each(function(elx,index){				
				var hover = $E(".gk_ni_6_hover" , elx);
				var opacity_anim = new Fx.Opacity(hover, {duration: 250, wait: false});
				//
				elx.addEvent("mouseenter",function(){
					hover.setStyle("display", "block");
					opacity_anim.start(1);	
				});
				//
				elx.addEvent("mouseleave",function(){
					opacity_anim.start(0);
					(function(){hover.setStyle("display", "none");}).delay(250);	
				});
				//
				elx.addEvent("click", function(){
					if(!$G["actual_anim_p"]){
						$E(".gk_ni_6_tab_active",mainwrap).setProperty("class","gk_ni_6_tab");
						elx.setProperty("class","gk_ni_6_tab_active");
					}
					//
					NI2.image_anim(elID,mainwrap,wrap,slides,index,contents,$G,false);
				});
			});
			
			$E(".gk_ni_6_tab",mainwrap).setProperty("class","gk_ni_6_tab_active");
			NI2.image_anim(elID,mainwrap,wrap,slides,0,contents,$G,($G["autoanim"]==1));
			/** Slider implementation **/
			if($E('.gk_ni_6_tabsbar_slider',mainwrap)){
				var $offset = $E(".gk_ni_6_tab",mainwrap).getStyle("height").toInt() + $E(".gk_ni_6_tab",mainwrap).getStyle("margin-bottom").toInt();
				var scrollArea = $E('.gk_ni_6_tabsbar_wrap', mainwrap);
				var scrollableArea = $E('.gk_ni_6_tabsbar', mainwrap);
				var scrollAreaH = scrollArea.getSize().size.y;
				var scrollableAreaH = scrollableArea.getSize().size.y;
				var scroller_up = new Fx.Scroll(scrollArea, {duration: 250, wait: true, transition: Fx.Transitions.Circ.easeIn, onComplete: function(){scrollValue -= $offset;}});
				var scroller_down = new Fx.Scroll(scrollArea, {duration: 250, wait: true, transition: Fx.Transitions.Circ.easeIn, onComplete: function(){scrollValue += $offset;}});
				//
				$E('.gk_ni_6_tabsbar_up', mainwrap).addEvent("click",function(){				
					if(scrollValue > 0) { scroller_up.scrollTo(0, scrollValue-$offset);}
				});
				//
				$E('.gk_ni_6_tabsbar_down', mainwrap).addEvent("click",function(){				
					if((scrollValue < (scrollableAreaH-scrollAreaH))) { scroller_down.scrollTo(0, scrollValue+$offset); }
				});
			}
		}}).periodical(250);
	});
});
//
var news_image_6 = new Class({
	//
	text_anim : function(wrap,contents,$G){
		var txt = $E(".gk_ni_6_text",wrap);
		if(txt){
			if($G["anim_type_t"] == 0){	
				new Fx.Opacity(txt,{duration: $G["anim_speed"]/2}).start(1,0);
				(function(){
					new Fx.Opacity(txt,{duration: $G["anim_speed"]/2}).start(0,1);txt.innerHTML = contents[$G["actual_slide"]];
				}).delay($G["anim_speed"]);
			}	
			else txt.innerHTML = contents[$G["actual_slide"]];
		}
	},
	//
	image_anim : function(elID,mainwrap,wrap,slides,n,contents,$G,play){
		var max = slides.length-1;
		var links = $ES('.gk_ni_6_news_link', mainwrap);
		var readon = $E('.gk_ni_6_readmore_button a', mainwrap);
	
		if(!$G["actual_anim_p"] && n != $G["actual_slide"]){
			$G["actual_anim_p"] = true;
			if(readon) readon.setProperty("href", links[n].innerHTML);
			var actual_slide = $G["actual_slide"];
			$G["actual_slide"] = n;
			slides[n].setStyle("z-index",max+1);
		
			if(actual_slide != -1) new Fx.Opacity(slides[actual_slide],{duration: $G["anim_speed"]}).start(1,0);
			new Fx.Opacity(slides[n],{duration: $G["anim_speed"]}).start(0,1);
			this.text_anim(wrap,contents,$G);	
				
			switch($G["anim_type"]){
				case 0: break;
				case 1: new Fx.Style(slides[n],'margin-top',{duration: $G["anim_speed"]}).start((-1)*slides[n].getSize().size.y,0);break;
				case 2: new Fx.Style(slides[n],'margin-left',{duration: $G["anim_speed"]}).start((-1)*slides[n].getSize().size.x,0);break;
				case 3: new Fx.Style(slides[n],'margin-top',{duration: $G["anim_speed"]}).start(slides[n].getSize().size.y,0);break;
				case 4: new Fx.Style(slides[n],'margin-left',{duration: $G["anim_speed"]}).start(slides[n].getSize().size.x,0);break;
			}
			
			if(play){
				$E(".gk_ni_6_tab_active",mainwrap).setProperty("class","gk_ni_6_tab");
				$ES(".gk_ni_6_tab",mainwrap)[n].setProperty("class","gk_ni_6_tab_active");
			}
		
			(function(){slides[n].setStyle("z-index",n);}).delay($G["anim_speed"]);
			(function(){$G["actual_anim_p"] = false;}).delay($G["anim_speed"]);
			
			var $this = this;
			
			if(!play) this.image_pause($G);
			if((play || $G["autoanim"] == 1) && ($G["actual_anim"] == false)){
				$G["actual_anim"] = (function(){
					n = (n < max) ? n+1 : 0;
					$this.image_anim(elID,mainwrap,wrap,slides,n,contents,$G,true);
				}).periodical($G["anim_speed"] * 2 + $G["anim_interval"]);
			}
		}
	},
	//
	image_pause : function($G) { $clear($G["actual_anim"]); $G["actual_anim"] = false; }
});