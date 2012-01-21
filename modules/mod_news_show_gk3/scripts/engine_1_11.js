window.addEvent("load",function(){	
	$$('.gk_news_show_table').each(function(el,i){
		var TID = el.getProperty('id');
		var conf = new Hash.Cookie('gk_ns_amount', {duration: 14,path: "/"});
		var main = $(TID);
		//
		if($E('.gk_news_show_panel_amount',el)){
			var TRs = main.getElementsBySelector('tr');
			var counter = $E('.gk_news_show_panel_amount_value',el);
			var NC = TRs[0].getElementsBySelector('td').length;
			var NR = TRs.length-2;
			var NV = 0;
			//
			TRs.each(function(el){(el.getStyle("display") == 'none') ? NV++ : NV = NV;});
			counter.setHTML(NR-NV);
			var list = $E('.gk_news_show_list_floated',el) || $E('.gk_news_show_list',el) || false;
			//
			if(list){
				var listOfLi = list.getElementsBySelector('li');
				var amountOfLi = listOfLi.length;
			}
			//
			$E('.gk_news_show_panel_amount_minus',el).addEvent('click',function(){
				if(NV < NR){
					TRs[(NR-NV)-1].setStyle('display','none');
					TRs[(NR-NV)-1].setProperty('class','gk_news_show_tablerow_invisible');
					NV++;
					counter.setHTML(NR-NV);
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
			$E('.gk_news_show_panel_amount_plus',el).addEvent('click',function(){
				if(NV > 0){
					TRs[(NR-NV)].setStyle('display','');
					TRs[(NR-NV)].setProperty('class','gk_news_show_tablerow');
					NV--;
					counter.setHTML(NR-NV);
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