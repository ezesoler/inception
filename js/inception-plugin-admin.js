jQuery(document).on("ready",inc_init);

var loading;

function inc_init(e){
	loading = jQuery("#inc-loading");
	load_values(jQuery(".inc-cuenta"),".inc-subarea","inc_subarea");
	
	jQuery(".inc-cuenta, .inc-sucursal").on("click",set_check);
}

function set_check(e){
	var c = jQuery(e.currentTarget);
	var t = c.attr("data-t");
	var id = c.val();
	if(c.is(":checked")){
		loading.show();
		jQuery.post(
		    ajaxurl, 
		    {
		        'action': 'inc_segments',
		        't':   t,
		        'id':  id
		    }, 
		    function(response){
		    	render_values(t,id,JSON.parse(response));
		    	loading.hide();
		    }
		);
	}else{
		delete_items(t,id);
	}
	
}

function render_values(t,id,values){
	var l;
	var c;
	var n;
	var datat;
	var datar = id;
	switch (t) {
		case "cue":
			c = "inc-subarea";
			n = "inc-subarea-field[]";
			datat = "sar";
			l = jQuery("#sar_list");
			break;
		case "sar":
			c = "inc-servicio";
			n = "inc-servicio-field[]";
			datat = "srv";
			l = jQuery("#srv_list");
			break;
		case "suc":
			c = "inc-site";
			n = "inc-site-field[]";
			datat = "site";
			l = jQuery("#site_list");
			break;
		case "site":
			c = "inc-call";
			n = "inc-call-field[]";
			datat = "call";
			l = jQuery("#call_list");
			break;
		default:
    		console.log("No hay tipo de dato");
	}
	jQuery.each( values, function( key, value ) {
	  l.append( "<spam class='"+c+"_"+datar+"'><input type='checkbox' class='"+c+"' name='"+n+"' data-t='"+datat+"' value='"+value.id+"' data-r='"+datar+"' />"+value.nombre+"<br></spam>");
	});
	jQuery("."+c).on("click",set_check);
	loading.hide();
}

function delete_items(t,id){
	var i;
	switch (t) {
		case "cue":
			i = ".inc-subarea";
			jQuery(i).each(function( key, obj ) {
				if(jQuery(obj).is(":checked")){
					delete_items(jQuery(obj).attr("data-t"),jQuery(obj).val());
				}
			});
			break;
		case "sar":
			i = ".inc-servicio";
			break;
		case "suc":
			i = ".inc-site";
			jQuery(i).each(function( key, obj ) {
				if(jQuery(obj).is(":checked")){
					delete_items(jQuery(obj).attr("data-t"),jQuery(obj).val());
				}
			});
			break;
		case "site":
			i = ".inc-call";
			break;
		default:
    		console.log("No hay tipo de dato");
	}
	jQuery(i+"_"+id).remove();
	jQuery(i).off("click",set_check);
	jQuery(i).on("click",set_check);

}

function load_values(element,child,meta,lastlevel = false){
	element.each(function( key, obj ) {
		if(jQuery(obj).is(":checked")){
			var t = jQuery(obj).attr("data-t");
			var id = jQuery(obj).val();
			loading.show();
			jQuery.post(
			    ajaxurl, 
			    {
			        'action': 'inc_segments',
			        't':   t,
			        'id':  id
			    }, 
			    function(response){
			    	render_values(t,id,JSON.parse(response));
			    	checked_values(child,meta,lastlevel);
			    }
			);
		}
	});
}

function checked_values(element,meta,lastlevel){
	jQuery.post(
		    ajaxurl, 
		    {
		        'action': 'inc_get_meta',
		        'post_id': jQuery("#postdata").attr("data-id"),
		        'm':   meta
		    }, 
		    function(response){
		    	jQuery(element).each(function( key, obj ) {
		    		jQuery.each(JSON.parse(response),function( metakey, value ) {
		    			if(Number(jQuery(obj).val()) == Number(value)){
		    				jQuery(obj).attr('checked',true);
		    			}
		    		});
				});
		    	if(!lastlevel){
		    		if(element == ".inc-subarea"){
		    			load_values(jQuery(element),".inc-servicio","inc_servicio",true);
		    		}else{
		    			load_values(jQuery(element),".inc-call","inc_call",true);
		    		}	
		    	}else{
		    		if(element == ".inc-servicio"){
		    			load_values(jQuery(".kinc-sucursal"),".inc-site","inc_site");
		    		}else{
		    			loading.hide();
		    		}
		    		
		    	}
		    }
		);
	
}
