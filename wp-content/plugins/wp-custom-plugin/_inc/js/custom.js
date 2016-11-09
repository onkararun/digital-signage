var jq = jQuery;

jq(document).ready( function() {

    //Every one minute
    setInterval(function(){	

        var len = jq( ".loop-slide" ).length;
        /* if we take one slider in a page */
        if(len == 1) {
        	/* condition when time is set in the backend */
        	var from_hr = jq(".loop-slide").attr('data-from-hour');
	        var from_min = jq(".loop-slide").attr('data-from-min');
	        var to_hr = jq(".loop-slide").attr('data-to-hour');
	        var to_min = jq(".loop-slide").attr('data-to-min');
	        var sl_date =  jq(".loop-slide").attr('data-date');
	        var sl_date_trm = sl_date.substring(0, 10);

	        //detect is current slide is running or not
	        var current = jq(".loop-slide").attr('data-current');

            if(from_hr != "" && from_min != "" && to_hr != "" && to_min != "" ){

	        	var from = from_hr +":"+ from_min;
		        var fr = new Date('00/00/00' +" "+ from);
		        var convert_from_time_to_timestamp = fr.getTime();
		        var to = to_hr +":"+ to_min;
		        var to_tm = new Date('00/00/00' +" "+ to);
		        var convert_To_time_to_timestamp = to_tm.getTime();
		        var now  = new Date();
		        var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();
				if(dd<10) {
		    		dd='0'+dd
				} 
				if(mm<10) {
					mm='0'+mm
				} 
				today = dd+'-'+mm+'-'+yyyy;
		        var current_time = now.getHours() +':'+ now.getMinutes();
		        var current_time1 = new Date('00/00/00' +" "+ current_time);
		        var cur_tm = current_time1.getTime();

	            if(!current) {
	                /* when only time selected */
	                if(sl_date == "") {
				        if((convert_from_time_to_timestamp  <= cur_tm) && (convert_To_time_to_timestamp >= cur_tm)) {
				            var Id = jq(".loop-slide").attr('id');
				            jq('#'+Id).attr("data-current", 1);
				    	    jq('#'+Id).show();
				        }
				    }
				    else {
				    	/* when time and date both selected */
				    	if(today == sl_date_trm){
					        if((convert_from_time_to_timestamp  <= cur_tm) && (convert_To_time_to_timestamp >= cur_tm)) {
								var Id = jq(".loop-slide").attr('id');
								jq('#'+Id).attr("data-current", 1);
							    jq('#'+Id).show();		 
					        }  
					    }

				    }
				}
		    } 

		    /* when date or page selected */
		    else {
		    	 
		    	    var today = new Date();
				    var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!
					var yyyy = today.getFullYear();
					if(dd<10) {
		    			dd='0'+dd
					} 
					if(mm<10) {
						mm='0'+mm
					} 
					today = dd+'-'+mm+'-'+yyyy;
					
					if(!current) {
						/* when only date selected */
				        if(sl_date != ""){
				            if(today == sl_date_trm){
								var Id = jq(".loop-slide").attr('id');
								jq('#'+Id).attr("data-current", 1);
							    jq('#'+Id).show();		 
					                 
					        }
				        }
				        /* when only page selected */
				        else {
				        	    var Id = jq(".loop-slide").attr('id');
								jq('#'+Id).attr("data-current", 1);
							    jq('#'+Id).show();
				        }
			        }
		    }

        }
        /* if we take more than one slider in a page */
        if(len > 1) {

	       jq( ".loop-slide" ).each(function( index, obj ) {
	            var from_hr = jq(obj).attr('data-from-hour');
	            var from_min = jq(obj).attr('data-from-min');
	            var to_hr = jq(obj).attr('data-to-hour');
	            var to_min = jq(obj).attr('data-to-min');
	            var sl_date =  jq(obj).attr('data-date');
	            var sl_date_trm = sl_date.substring(0, 10);
	            var from = from_hr +":"+ from_min;
	            var fr = new Date('00/00/00' +" "+ from);
	            var convert_from_time_to_timestamp = fr.getTime();
	            var to = to_hr +":"+ to_min;
	            var to_tm = new Date('00/00/00' +" "+ to);
	            var convert_To_time_to_timestamp = to_tm.getTime();
	            var now  = new Date();
			    var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();
			    if(dd<10) {
    				dd='0'+dd
				} 
				if(mm<10) {
					mm='0'+mm
				} 
				today = dd+'-'+mm+'-'+yyyy;
	            var current_time = now.getHours() +':'+ now.getMinutes();
	            var current_time1 = new Date('00/00/00' +" "+ current_time);
	            var cur_tm = current_time1.getTime();

                /* when only time selected */ 
                if(sl_date == "") {	
		            if((convert_from_time_to_timestamp  <= cur_tm) && (convert_To_time_to_timestamp >= cur_tm)) {
		            	
			            if(jq(this).attr("data-from-hour") == from_hr) {	                	
							var Id = jq(this).attr('id');
							jq('#'+Id).attr("data-current", 1);
							jq('#'+Id).show();
						} 
					    
		            }
		        }
	            

	            /* when date and time selected for slider which will run at particular time in particular day, 
	            and if date is correct then it will not show anything */ 
                else {
                	if(today == sl_date_trm){
				        if((convert_from_time_to_timestamp  <= cur_tm) && (convert_To_time_to_timestamp >= cur_tm)) {
					        if (jq(this).attr("data-from-hour") == from_hr) {	                	
									var Id = jq(this).attr('id');
									jq('#'+Id).attr("data-current", 1);
									jq('#'+Id).show();
									   
						    } 
				        }
				    }
			    }
	                 
	            
	        });


        }
        //Check slider time frame is full fill then show the slide and other would be hide

    }, 60000);
});


