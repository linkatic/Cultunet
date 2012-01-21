window.addEvent("load",function(){	
	$$('.gk_news_show_table').each(function(el,i){
		var TID = el.getProperty('id');
		var conf = new Hash.Cookie('gk_ns_amount', {duration: 14,path: "/"});
		var main = $(TID);
		//
		if(el.getElements('.gk_news_show_panel_amount')){
			var TRs = main.getElements('tr');
			var counter = el.getElements('.gk_news_show_panel_amount_value');
			var NC = TRs[0].getElements('td').length;
			var NR = TRs.length-2;
			var NV = 0;
			//
			TRs.each(function(el){(el.getStyle("display") == 'none') ? NV++ : NV = NV;});
			counter.set('html', NR-NV);
			var list = el.getElements('.gk_news_show_list_floated') || el.getElements('.gk_news_show_list') || false;
			//
			if(list){
				var listOfLi = list.getElements('li');
				var amountOfLi = listOfLi.length;
			}
			//
			el.getElements('.gk_news_show_panel_amount_minus').addEvent('click',function(){
				if(NV < NR){
					TRs[(NR-NV)-1].setStyle('display','none');
					TRs[(NR-NV)-1].setProperty('class','gk_news_show_tablerow_invisible');
					NV++;
					counter.set('html', NR-NV);
					conf.set(TID, (NR-NV));
					//	
					if(list){
						for(var l=0;l<NC;l++){
							if((((NR-NV)*NC)+l >= 0) && (((NR-NV)*NC)+l < amountOfLi)) listOfLi[((NR-NV)*NC)+l].setStyle('display','block');
						}
					}
				}
			});
			//
			el.getElements('.gk_news_show_panel_amount_plus').addEvent('click',function(){
				if(NV > 0){
					TRs[(NR-NV)].setStyle('display','');
					TRs[(NR-NV)].setProperty('class','gk_news_show_tablerow');
					NV--;
					counter.set('html', NR-NV);
					conf.set(TID, (NR-NV));
					//	
					if(list){
						for(var k=0;k<NC;k++){
							if(((NR-NV)*NC)-(1+k) < amountOfLi) listOfLi[((NR-NV)*NC)-(1+k)].setStyle('display','none');
						}
					}
				}
			});
		}
	});
});