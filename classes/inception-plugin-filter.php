<?php

class InceptionPlugin_Filter
{
	

	function __construct()
    {
    	add_action( 'init', array(&$this,'filter_posts'));
        add_action( 'pre_get_posts', array(&$this,'query_filter'));
    }

    function filter_posts(){
    	if(current_user_can('administrator')){
    		return;
    	}
    	if (!is_admin()){
    		//Si no hay sesión se inicia una
    		if (!session_id()) {
			    session_start();
			}

			//Si no existe el valor en la sesión se hace todo el proceso.
			if(!isset($_SESSION['plaza'])) {
				$ip_user = self::get_ip_user();

				InceptionPlugin_Log::write("Acceso anónimo IP: ".$ip_user);

	    		global $wpdb;
	    		
		        $table_name = $wpdb->prefix . "inc_subred";

		        //Se traen todo los segmentos IP de las plazas.
		        $rows = $wpdb->get_results("SELECT id,id_plaza,segmento from $table_name");

		        foreach ($rows as $row) {
		        	$plaza = -1;
		        	if(self::ip_in_range($ip_user,$row->segmento) == 1){
		        		//Se guarda en la sesión la plaza a la que pertenece el usuario para no rehacer el proceso.
		        		$_SESSION['plaza'] = $row->id_plaza;
		        	}
		        }
			}

    		//Si no existe el valor de sesión que guarda los ID de los elmentos que no se deben mostrar, se procesa.
			if(!isset($_SESSION['exclude_elements_plaza'])) {

				$_SESSION['exclude_elements_plaza'] = array();

				//Trae todos los elementos que tengan el meta inc_plaza
				 $args = array(
				   'meta_query' => array(
				       array(
				           'key' => 'inc_plaza',
				           'compare' => 'EXISTS',
				       )
				   )
				);

				$query_posts = new WP_Query($args);

				if ($query_posts->posts) {
					foreach( $query_posts->posts as $post ) {
						//Trae valor de meta inc_plaza.
						$inc_plaza = get_post_meta($post->ID, 'inc_plaza',true);

						if(!in_array($_SESSION['plaza'], $inc_plaza,false)){
							array_push($_SESSION['exclude_elements_plaza'],$post->ID);
						}	
					}
				}
			}

			//Si el usuario está logueado se filtra el contenido segun su segmentación.
			if(isset($_SESSION['inc_username'])) {
				//Para no volver a procesar todo.
				if(!isset($_SESSION['segment_user_signed'])) {

					$_SESSION['exclude_elements_segment'] = array();

					/*****************************************************
					*********************** CUENTA ***********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_cuenta
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_cuenta',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					 $query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['cue_id'])){
								//Trae valor de meta inc_plaza.
								$inc_cuenta = get_post_meta($post->ID, 'inc_cuenta',true);
								
								if(!in_array($_SESSION['cue_id'], $inc_cuenta,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					/*****************************************************
					*********************** SUBAREA **********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_subarea
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_subarea',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					 $query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['sar_id'])){
								//Trae valor de meta inc_subarea.
								$inc_subarea = get_post_meta($post->ID, 'inc_subarea',true);
								
								if(!in_array($_SESSION['sar_id'], $inc_subarea,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					/*****************************************************
					********************** SERVICIO **********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_servicio
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_servicio',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					$query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['srv_id'])){
								//Trae valor de meta inc_servicio.
								$inc_servicio = get_post_meta($post->ID, 'inc_servicio',true);
								
								if(!in_array($_SESSION['srv_id'], $inc_servicio,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}


					/*****************************************************
					********************** SOCIEDAD **********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_sociedad
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_sociedad',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					$query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['sociedad_id'])){
								//Trae valor de meta inc_sociedad.
								$inc_sociedad = get_post_meta($post->ID, 'inc_sociedad',true);
								
								if(!in_array($_SESSION['sociedad_id'], $inc_sociedad,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					/*****************************************************
					********************** SUCURSAL **********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_sucursal
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_sucursal',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					$query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['suc_id'])){
								//Trae valor de meta inc_sucursal.
								$inc_sucursal = get_post_meta($post->ID, 'inc_sucursal',true);
								
								if(!in_array($_SESSION['suc_id'], $inc_sucursal,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					/*****************************************************
					************************ SITIO ***********************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_site
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_site',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					$query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['site_id'])){
								//Trae valor de meta inc_site.
								$inc_site = get_post_meta($post->ID, 'inc_site',true);
								
								if(!in_array($_SESSION['site_id'], $inc_site,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					/*****************************************************
					************************ CALL ************************
					*****************************************************/

					//Trae todos los elementos que tengan el meta inc_call
					 $args = array(
					   'meta_query' => array(
					       array(
					           'key' => 'inc_call',
					           'compare' => 'EXISTS',
					       )
					   )
					);

					$query_posts = new WP_Query($args);

					if ($query_posts->posts) {
						foreach( $query_posts->posts as $post ) {
							//Si el valor existe ne la sesión del usuario se controla si lo puede ver o no.
							if(isset($_SESSION['call_id'])){
								//Trae valor de meta inc_call.
								$inc_call = get_post_meta($post->ID, 'inc_call',true);
								
								if(!in_array($_SESSION['call_id'], $inc_call,false)){
									array_push($_SESSION['exclude_elements_segment'],$post->ID);
								}
							}else{
								array_push($_SESSION['exclude_elements_segment'],$post->ID);
							}	
						}
					}

					$_SESSION['segment_user_signed'] = 1;
				}

			}else{
				//Se filtra todo ya que el usuario no está logueado.
				if(!isset($_SESSION['exclude_elements_segment'])) {

					$_SESSION['exclude_elements_segment'] = array();

					$args = array(
					   'meta_query' => array(
							'relation' => 'OR',
					       'query_cuenta' => array(
					           'key' => 'inc_cuenta',
					           'compare' => 'EXISTS'
					       ),
					       'query_sociedad' => array(
					           'key' => 'inc_sociedad',
					           'compare' => 'EXISTS'
					       ),
					       'query_sucursal' => array(
					           'key' => 'inc_sucursal',
					           'compare' => 'EXISTS'
					       )
					   )
					);

				 $query_posts = new WP_Query($args);

				 if ($query_posts->posts) {
				 	foreach( $query_posts->posts as $post ) {
				 		array_push($_SESSION['exclude_elements_segment'],$post->ID);
				 	}
				 }

				}
			}
    	}
    }

    function query_filter($query){
    	if(current_user_can('administrator')){
    		return;
    	}
    	if( $query->is_admin == 1 ) {
        	return;
	    }

	    $exclude_posts = array_merge($_SESSION['exclude_elements_plaza'], $_SESSION['exclude_elements_segment']);
		$query->set('post__not_in', $exclude_posts);

		return $query;
    }

    function get_ip_user(){
    	$ip = "";
    	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
    }

    function ip_in_range( $ip, $range ) {
	    if ( strpos( $range, '/' ) == false ) {
	        $range .= '/32';
	    }
	    list( $range, $netmask ) = explode( '/', $range, 2 );
	    $range_decimal = ip2long( $range );
	    $ip_decimal = ip2long( $ip );
	    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
	    $netmask_decimal = ~ $wildcard_decimal;
	    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
	}
}

?>