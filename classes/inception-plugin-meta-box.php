<?php

class InceptionPlugin_MetaBox
{

    function __construct()
    {
        add_action('add_meta_boxes', array(&$this,'add_meta_box_head') );
        add_action('save_post', array(&$this,'save_postdata'));
        add_action('wp_ajax_inc_segments', array(&$this,'ajax_segments_handler') );
        add_action('wp_ajax_inc_get_meta', array(&$this,'ajax_get_meta_handler') );
    }


    function add_meta_box_head() {
        add_meta_box('inception_plugin_plaza_section','Plaza',array(&$this,'add_meta_box_plaza'), 'post','side','high');
        add_meta_box('inception_plugin_plaza_section','Plaza',array(&$this,'add_meta_box_plaza'), 'page','side','high');

        add_meta_box('inception_plugin_segmentacion_section','Segmentaci&oacute;n',array(&$this,'add_meta_box_segment'), 'post','normal','high');
        add_meta_box('inception_plugin_segmentacion_section','Segmentaci&oacute;n',array(&$this,'add_meta_box_segment'), 'page','normal','high');
    }

    function ajax_segments_handler(){
    	global $wpdb;
    	$t = $_POST["t"];
    	$id = $_POST["id"];
    	$table_name = $wpdb->prefix;
    	$sql;
    	switch ($t) {
    		case "cue":
    			$table_name = $wpdb->prefix . 'inc_subarea';
	    		$sql = "SELECT sar_id AS id, sar_nombre AS nombre FROM $table_name WHERE cue_id = $id";
	    		break;
	    	case "sar":
    			$table_name = $wpdb->prefix . 'inc_servicio';
	    		$sql = "SELECT srv_id AS id, srv_nombre AS nombre FROM $table_name WHERE sar_id = $id";
	    		break;
	    	case "suc":
    			$table_name = $wpdb->prefix . 'inc_site';
	    		$sql = "SELECT site_id AS id, site_nombre AS nombre FROM $table_name WHERE suc_id = $id";
	    		break;
	    	case "site":
    			$table_name = $wpdb->prefix . 'inc_call';
	    		$sql = "SELECT call_id AS id, call_nombre AS nombre FROM $table_name WHERE site_id = $id";
	    		break;
    	}
    	echo json_encode($wpdb->get_results($sql));
    	wp_die();
    }

    function ajax_get_meta_handler(){
    	echo json_encode(get_post_meta($_POST["post_id"], $_POST["m"],true));
    	wp_die();
    }

    function add_meta_box_segment(){
    	global $wpdb;
	    global $post;

	    if( !is_object($post) ) 
        return;

    	//Para validar que el contenido del formulario provenga de la ubicación actual y no de otro lado (XSS)
	    wp_nonce_field( 'inc_meta_box_segment', 'inc_meta_box_segment_nonce' );

	    $table_name = $wpdb->prefix . "inc_cuenta";

	    $inc_cuenta = get_post_meta($post->ID, 'inc_cuenta',true);

	    $cuentas = $wpdb->get_results("SELECT cue_id,cue_nombre from $table_name");
	    ?>
	    <div id="postdata" data-id="<?php echo $post->ID; ?>"></div>
	    <div id="inc-loading">
			<div class="a inc-anim-load" style="--n: 5;">
			  <div class="dot" style="--i: 0;"></div>
			  <div class="dot" style="--i: 1;"></div>
			  <div class="dot" style="--i: 2;"></div>
			  <div class="dot" style="--i: 3;"></div>
			  <div class="dot" style="--i: 4;"></div>
			</div>
		</div>
	    <div class="inc-column-segment">
	    	<h3>Cuenta</h3><small>&nbsp;si no selecciona ninguna, será visible por todos</small>
	    	<div class="inc-list-values">
		    <?php
		    foreach ($cuentas as $c) {
		    	echo '<input type="checkbox" class="inc-cuenta" name="inc-cuenta-field[]" data-t="cue" value="'. $c->cue_id. '" ';
				if (!empty($inc_cuenta) && in_array($c->cue_id, $inc_cuenta,true)) { echo 'checked="checked"';} 
				echo '/>'. $c->cue_nombre. '<br>';
		    }
		    ?>
		</div>
		</div>
		<div class="inc-column-segment">
			<h3>Sub Area</h3>
			<div class="inc-list-values" id="sar_list"></div>
		</div>
		<div class="inc-column-segment">
			<h3>Servicio</h3>
			<div class="inc-list-values" id="srv_list"></div>
		</div>
		<br>
		<br>
		<div class="inc-column-segment">
	    	<h3>Sociedad</h3><small>&nbsp;si no selecciona ninguna, será visible por todos</small>
	    	<div class="inc-list-values">
		    <?php
		    $table_name = $wpdb->prefix . "inc_sociedad";

	    	$inc_sociedad = get_post_meta($post->ID, 'inc_sociedad',true);

	   		$sociedades = $wpdb->get_results("SELECT sociedad_id,sociedad_nombre from $table_name");

		    foreach ($sociedades as $s) {
		    	echo '<input type="checkbox" class="inc-sociedad" name="inc-sociedad-field[]" value="'. $s->sociedad_id. '" ';
				if (!empty($inc_sociedad) && in_array($s->sociedad_id, $inc_sociedad,true)) { echo 'checked="checked"';} 
				echo '/>'. $s->sociedad_nombre. '<br>';
		    }
		    ?>
			</div>
		</div>
		<br>
		<br>
		<div class="inc-column-segment">
	    	<h3>Sucursal</h3><small>&nbsp;si no selecciona ninguna, será visible por todos</small>
	    	<div class="inc-list-values">
		    <?php
		    $table_name = $wpdb->prefix . "inc_sucursal";

	    	$inc_sucursal = get_post_meta($post->ID, 'inc_sucursal',true);

	   		$sucursales = $wpdb->get_results("SELECT suc_id,suc_nombre from $table_name");

		    foreach ($sucursales as $s) {
		    	echo '<input type="checkbox" class="inc-sucursal" data-t="suc" name="inc-sucursal-field[]" value="'. $s->suc_id. '" ';
				if (!empty($inc_sucursal) && in_array($s->suc_id, $inc_sucursal,true)) { echo 'checked="checked"';} 
				echo '/>'. $s->suc_nombre. '<br>';
		    }
		    ?>
			</div>
		</div>
		<div class="inc-column-segment">
			<h3>Site</h3>
			<div class="inc-list-values" id="site_list"></div>
		</div>
		<div class="inc-column-segment">
			<h3>Call</h3>
			<div class="inc-list-values" id="call_list"></div>
		</div>
	    <?php
    }


    function add_meta_box_plaza(){
    ?>
	    <h4>Seleccione la plaza en la que debe ser visible este contenido<br>
	    <small>si no selecciona ninguna, será visible por todos</small></h4>
	    <?php
	    global $wpdb;
	    global $post;

	    if( !is_object($post) ) 
        return;

	    //Para validar que el contenido del formulario provenga de la ubicación actual y no de otro lado (XSS)
	    wp_nonce_field( 'inc_meta_box_plaza', 'inc_meta_box_plaza_nonce' );

	    $table_name = $wpdb->prefix . "inc_plazas";

	    $inc_plaza = get_post_meta($post->ID, 'inc_plaza',true);

	    $plazas = $wpdb->get_results("SELECT id,name from $table_name");

	    foreach ($plazas as $s) {
	    	echo '<input type="checkbox" name="inc-plaza-field[]" value="'. $s->id. '" ';
			if (!empty($inc_plaza) && in_array($s->id, $inc_plaza,true)) { echo 'checked="checked"';} 
			echo '/>'. $s->name. '<br>';
	    }
    }

    function save_postdata($post_id){

    	//Se chequea que el nonce este.
    	if ( ! isset( $_POST['inc_meta_box_plaza_nonce'] ) ) {
			return;
		}

		//Se chequea si el nonce es válido.
		if ( ! wp_verify_nonce( $_POST['inc_meta_box_plaza_nonce'], 'inc_meta_box_plaza' ) ) {
			return;
		}

		//Si es autoguardado, no se hace nada.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Se chequean permisos 
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		//Si no llegan datos de la plaza se elimina el meta.
		if(isset($_POST['inc-plaza-field'])){
			update_post_meta( $post_id, 'inc_plaza', $_POST['inc-plaza-field'] );
		}else{
			delete_post_meta($post_id, 'inc_plaza');
		}

		//Se chequea que el nonce este.
    	if ( ! isset( $_POST['inc_meta_box_segment_nonce'] ) ) {
			return;
		}

		//Se chequea si el nonce es válido.
		if ( ! wp_verify_nonce( $_POST['inc_meta_box_segment_nonce'], 'inc_meta_box_segment' ) ) {
			return;
		}


		//Se guardan todas las categorias de segmentacion
		//Cuenta
		if(isset($_POST['inc-cuenta-field'])){
			update_post_meta( $post_id, 'inc_cuenta', $_POST['inc-cuenta-field'] );
		}else{
			delete_post_meta($post_id, 'inc_cuenta');
		}

		//Subarea
		if(isset($_POST['inc-subarea-field'])){
			update_post_meta( $post_id, 'inc_subarea', $_POST['inc-subarea-field'] );
		}else{
			delete_post_meta($post_id, 'inc_subarea');
		}

		//Servicio
		if(isset($_POST['inc-servicio-field'])){
			update_post_meta( $post_id, 'inc_servicio', $_POST['inc-servicio-field'] );
		}else{
			delete_post_meta($post_id, 'inc_servicio');
		}

		//Sociedad
		if(isset($_POST['inc-sociedad-field'])){
			update_post_meta( $post_id, 'inc_sociedad', $_POST['inc-sociedad-field'] );
		}else{
			delete_post_meta($post_id, 'inc_sociedad');
		}

		//Sucursal
		if(isset($_POST['inc-sucursal-field'])){
			update_post_meta( $post_id, 'inc_sucursal', $_POST['inc-sucursal-field'] );
		}else{
			delete_post_meta($post_id, 'inc_sucursal');
		}

		//Site
		if(isset($_POST['inc-site-field'])){
			update_post_meta( $post_id, 'inc_site', $_POST['inc-site-field'] );
		}else{
			delete_post_meta($post_id, 'inc_site');
		}

		//Call
		if(isset($_POST['inc-call-field'])){
			update_post_meta( $post_id, 'inc_call', $_POST['inc-call-field'] );
		}else{
			delete_post_meta($post_id, 'inc_call');
		}
    }
}
?>