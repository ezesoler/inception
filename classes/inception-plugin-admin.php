<?php

class Inception_Plugin_Admin
{
	function __construct()
    {
        add_action('admin_print_styles', array(&$this, 'admin_styles'));
        add_action('admin_print_scripts', array(&$this, 'admin_scripts'));
    }

    function admin_styles() 
    {
        wp_enqueue_style('inception-plugin-admin-css', WP_PLUGIN_URL. '/inception-plugin/css/style.css');
        wp_enqueue_style('inception-plugin-admin-icons', WP_PLUGIN_URL. '/inception-plugin/css/icons.css');
    }

    function admin_scripts()
    {
    	wp_enqueue_script('inception-plugin-admin-js', WP_PLUGIN_URL. '/inception-plugin/js/inception-plugin-admin.js');
    	if(SHOW_DEBUG){
    		wp_enqueue_script('inception-plugin-in-js', WP_PLUGIN_URL. '/inception-plugin/js/in.js');
    	}
    }

    function render_page(){
    	if($_GET['action']=="new_plaza"){
	    	Inception_Plugin_Admin::create_plaza();
	    }
	    else if($_GET['action']=="edit_plaza"){
	    	Inception_Plugin_Admin::update_plaza();
	    }
	    else if($_GET['action']=="del_plaza"){
	    	Inception_Plugin_Admin::delete_plaza();
	    }
	    else if($_GET['action']=="list_subred"){
	    	Inception_Plugin_Admin::list_subred();
	    }
	    else if($_GET['action']=="edit_segmento"){
	    	Inception_Plugin_Admin::update_segmento();
	    }
	    else if($_GET['action']=="del_segmento"){
	    	Inception_Plugin_Admin::delete_segmento();
	    }
	    else if($_GET['action']=="new_segmento"){
	    	Inception_Plugin_Admin::create_segmento();
	    }
	    else if($_GET['action']=="update_data"){
	    	Inception_Plugin_Admin::update_segmentacion();
	    }
	    else if($_GET['action']=="del_log"){
	    	Inception_Plugin_Admin::delete_log();
	    }
	    else if($_GET['page']=="inc_segment"){
	    	Inception_Plugin_Admin::show_segmentacion();
	    }
	    else if($_GET['page']=="inc_log"){
	    	Inception_Plugin_Admin::show_log();
	    }
	    else{
	    	Inception_Plugin_Admin::list_plazas();
	    }
    }

    public function show_log(){
    	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		if ( !DEBUG )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		?>
		<h2>LOG</h2>
		<small><?php echo InceptionPlugin_Log::get_location_log(); ?></small>
		<textarea class="inc-log" readonly><?php echo trim(InceptionPlugin_Log::get_log()); ?></textarea>
		<br>
		<a class="dellog" href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=del_log'); ?>" onclick="return confirm('&iquest;Est&aacute;s seguro de borrar todo el log?')"><span class="icon-bin"></span> BORRAR LOG</a>
		<?php
    }

    public function delete_log(){
    	if ( !DEBUG )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		InceptionPlugin_Log::truncate_log();
		?>
		<h2>LOG</h2>
    	<div class="updated" style="display:block !important"><p>El log fue borrado correctamente.</p></div>
    	<br>
        <a href="<?php echo admin_url('admin.php?page='.$_GET["page"]) ?>">&laquo; Volver al log</a>
        <?php
    }

    public function show_segmentacion()
    {
    	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		?>
		<h2>Segmentaci&oacute;n</h2>
		<div class="message-segment">
			Los datos para la segmentaci&oacute;n de noticias son traidos del sistema e incorporados a la base de datos interna. Si se agregaron o modificaron las areas de segmentaci&oacute;n, deber&aacute; actualizar los datos presionando el siguiente bot&oacute;n.
		</div>
		<div class="update-action">
			<a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=update_data'); ?>"><span class="icon-loop2"></span>Importar datos de Meucci</a>
		</div>
		<?php
		if(get_option("inc_last_update_segment")){
         ?>
         <div class="lastupdate">
         	Ultima actualizaci&oacute;n: <?php 
         	$date = strtotime( get_option("inc_last_update_segment") );
			echo date( 'd/m/Y H:i:s', $date ); 
         	?>
         </div>
         <?php
	    }
    }

    function update_segmentacion(){
    	include_once('inception-plugin-importer.php');

    	InceptionPlugin_Importer::reset_data();
    	?>
    	<h2>Segmentaci&oacute;n</h2>
    	<div class="updated" style="display:block !important"><p>Los datos fueron actualizados correctamente.</p></div>
    	<br>
        <a href="<?php echo admin_url('admin.php?page='.$_GET["page"]) ?>">&laquo; Volver a segmentaci&oacute;n</a>
    	<?php
    }

	public function list_plazas()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		?>
	    <div class="wrap">
	        <h2>Plazas</h2>
	        <div class="tablenav top">
	            <div class="alignleft actions">
	                <a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=new_plaza'); ?>"><span class="icon-plus"></span>Nueva Plaza</a>
	            </div>
	            <br class="clear">
	        </div>
	        <?php
	        global $wpdb;

	        $table_name = $wpdb->prefix . "inc_plazas";

	        $rows = $wpdb->get_results("SELECT id,name from $table_name");
	        ?>
	        <table class='wp-list-table widefat fixed striped posts'>
	            <tr>
	                <th class="manage-column ss-list-width">ID</th>
	                <th class="manage-column ss-list-width">Nombre</th>
	                <th>&nbsp;</th>
	            </tr>
	            <?php foreach ($rows as $row) { ?>
	                <tr>
	                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
	                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
	                    <td><a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=edit_plaza&id=' . $row->id); ?>"><span class="icon-pencil"></span></a></td>
	                    <td><a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=del_plaza&id=' . $row->id); ?>" onclick="return confirm('&iquest;Est&aacute;s seguro de borrar este elemento?')"><span class="icon-bin"></span></a></td>
	                    <td><a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=list_subred&idp=' . $row->id); ?>">Ver Subred</a></td>
	                </tr>
	            <?php } ?>
	        </table>
	    </div>
	    <?php
	}

	public function create_plaza() {
	    $name = $_POST["name"];
	    //insert
	    if (isset($_POST['insert'])) {
	        global $wpdb;
	        $table_name = $wpdb->prefix . "inc_plazas";

	        $wpdb->insert(
	                $table_name, //table
	                array('name' => $name), //data
	                array('%s') //data format			
	        );

	        InceptionPlugin_Log::write("Plaza Creada: ".$name);

	        $message.="La Plaza $name fue agregada correctamente.";
	    }
	    ?>
	    <div class="wrap">
	        <h2>Agregar Nueva Plaza</h2>
	        <br>
	        <?php if (isset($message)): ?><div class="updated" style="display:block !important"><p><?php echo $message; ?></p></div><?php endif; ?>
	        <br>
	        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	            <table class='wp-list-table widefat fixed'>
	                <tr>
	                    <th class="ss-th-width">Nombre Plaza</th>
	                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
	                </tr>
	            </table>
	            <br>
	            <input type='submit' name="insert" value='Guardar' class='button'>
	            <a class="cancelbtn" href="<?php echo admin_url('admin.php?page='.$_GET["page"]); ?>">Cancelar</a>
	        </form>
	    </div>
	    <?php
	}

	public function update_plaza() {
		global $wpdb;
	    $table_name = $wpdb->prefix . "inc_plazas";
	    $id = $_GET["id"];
	    //update
	    if (isset($_POST['update'])) {
	    	$name = $_POST["name"];

	        $wpdb->update(
                $table_name, //table
                array('name' => $name), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        	);

        	InceptionPlugin_Log::write("Plaza Actualizada: ".$name);
    	}else{
    		$plazas = $wpdb->get_results($wpdb->prepare("SELECT id,name from $table_name where id=%s", $id));
	        foreach ($plazas as $s) {
	            $name = $s->name;
	        }
    	}
	    ?>
	    <div class="wrap">
	        <h2>Modificar Plaza</h2>
	        <br>
	        <?php if ($_POST['update']) { ?>
            	<div class="updated" style="display:block !important"><p>Plaza Actualizada</p></div>
            	<a href="<?php echo admin_url('admin.php?page=inception-plugin') ?>">&laquo; Volver a la lista de plazas</a>
        	<?php } else { ?>
	        <br>
	        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	            <table class='wp-list-table widefat fixed'>
	                <tr>
	                    <th class="ss-th-width">Nombre Plaza</th>
	                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
	                </tr>
	            </table>
	            <br>
	            <input type='submit' name="update" value='Guardar' class='button'>
	            <a class="cancelbtn" href="<?php echo admin_url('admin.php?page='.$_GET["page"]); ?>">Cancelar</a>
	        </form>
	        <?php } ?>
	    </div>
	    <?php
	}

	public function delete_plaza(){
		global $wpdb;
    	$table_name = $wpdb->prefix . "inc_plazas";
    	$id = $_GET["id"];

    	$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));

    	InceptionPlugin_Log::write("Plaza Eliminada: ".$id);
    	?>
    	<div class="updated" style="display:block !important"><p>Plaza Eliminada</p></div>
        <a href="<?php echo admin_url('admin.php?page='.$_GET["page"]) ?>">&laquo; Volver a la lista de plazas</a>
    	<?php
	}



	public function list_subred()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'No tienes los permisos para ver esta página.' ) );
		}
		global $wpdb;
		$table_name = $wpdb->prefix . "inc_plazas";
		$idp = $_GET["idp"];
		$plazas = $wpdb->get_results($wpdb->prepare("SELECT id,name from $table_name where id=%s", $idp));
        foreach ($plazas as $s) {
            $name = $s->name;
        }
		?>
	    <div class="wrap">
	        <h2>Subred de <?php echo $name; ?> </h2>
	        <div class="tablenav top">
	            <div class="alignleft actions">
	                <a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=new_segmento&idp='.$idp); ?>"><span class="icon-plus"></span>Nuevo Segmento</a>
	            </div>
	            <br class="clear">
	        </div>
	        <?php

	        $table_name = $wpdb->prefix . "inc_subred";

	        $rows = $wpdb->get_results($wpdb->prepare("SELECT id,id_plaza,segmento from $table_name WHERE id_plaza = %s", $idp));

	        ?>
	        <table class='wp-list-table widefat fixed striped posts'>
	            <tr>
	                <th class="manage-column ss-list-width">ID</th>
	                <th class="manage-column ss-list-width">Segmento</th>
	                <th>&nbsp;</th>
	            </tr>
	            <?php foreach ($rows as $row) { ?>
	                <tr>
	                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
	                    <td class="manage-column ss-list-width"><?php echo $row->segmento; ?></td>
	                    <td><a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=edit_segmento&idp='.$idp.'&id=' . $row->id); ?>"><span class="icon-pencil"></span></a></td>
	                    <td><a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=del_segmento&idp='.$idp.'&id=' . $row->id); ?>" onclick="return confirm('&iquest;Est&aacute;s seguro de borrar este elemento?')"><span class="icon-bin"></span></a></td>
	                </tr>
	            <?php } ?>
	        </table>
	        <br>
	        <a href="<?php echo admin_url('admin.php?page='.$_GET["page"]) ?>">&laquo; Volver a la lista de plazas</a>
	    </div>
	    <?php
	}


	public function create_segmento() {
		$idp = $_GET["idp"];
		$segmento = $_POST["segmento"];
	    //insert
	    if (isset($_POST['insert'])) {
	        global $wpdb;
	        $table_name = $wpdb->prefix . "inc_subred";

	        $wpdb->insert(
	                $table_name, //table
	                array('id_plaza' => $idp, 'segmento' => $segmento), //data
	                array('%d', '%s') //data format			
	        );
	        InceptionPlugin_Log::write("Segmento Creado: ".$segmento." ID Plaza: ".$idp);
	        $message.="El segmento $segmento fue agregado correctamente.";
	    }
	    ?>
	    <div class="wrap">
	        <h2>Agregar Nuevo Segmento</h2>
	        <br>
	        <?php if (isset($message)): ?><div class="updated" style="display:block !important"><p><?php echo $message; ?></p></div><?php endif; ?>
	        <br>
	        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	            <table class='wp-list-table widefat fixed'>
	                <tr>
	                    <th class="ss-th-width">Segmento</th>
	                    <td><input type="text" name="segmento" value="<?php echo $segmento; ?>" class="ss-field-width" /></td>
	                </tr>
	            </table>
	            <br>
	            <input type='submit' name="insert" value='Guardar' class='button'>
	            <a class="cancelbtn" href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=list_subred&idp=' . $idp); ?>">Cancelar</a>
	        </form>
	    </div>
	    <?php
	}

	public function update_segmento() {
		global $wpdb;
	    $table_name = $wpdb->prefix . "inc_subred";
	    $idp = $_GET["idp"];
	    $id = $_GET["id"];
	    //update
	    if (isset($_POST['update'])) {
	    	$segmento = $_POST["segmento"];

	        $wpdb->update(
                $table_name, //table
                array('segmento' => $segmento), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%d') //where format
        	);
        	InceptionPlugin_Log::write("Segmento Actualizado: ".$segmento." ID Plaza: ".$idp);
    	}else{
    		$subred = $wpdb->get_results($wpdb->prepare("SELECT id,segmento from $table_name where id=%d", $id));
	        foreach ($subred as $s) {
	            $name = $s->segmento;
	        }
    	}
	    ?>
	    <div class="wrap">
	        <h2>Modificar Segmento</h2>
	        <br>
	        <?php if ($_POST['update']) { ?>
            	<div class="updated" style="display:block !important"><p>Segmento actualizado</p></div>
            	<a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=list_subred&idp='. $idp ) ?>">&laquo; Volver a la subred</a>
        	<?php } else { ?>
	        <br>
	        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	            <table class='wp-list-table widefat fixed'>
	                <tr>
	                    <th class="ss-th-width">Nombre Plaza</th>
	                    <td><input type="text" name="segmento" value="<?php echo $name; ?>" class="ss-field-width" /></td>
	                </tr>
	            </table>
	            <br>
	            <input type='submit' name="update" value='Guardar' class='button'>
	            <a class="cancelbtn" href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=list_subred&idp=' . $idp); ?>">Cancelar</a>
	        </form>
	        <?php } ?>
	    </div>
	    <?php
	}

	public function delete_segmento(){
		global $wpdb;
    	$table_name = $wpdb->prefix . "inc_subred";
    	$idp = $_GET["idp"];
    	$id = $_GET["id"];

    	$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));

    	InceptionPlugin_Log::write("Segmento Eliminado: ".$segmento." ID Plaza: ".$idp);
    	?>
    	<div class="updated" style="display:block !important"><p>Segmento Eliminado</p></div>
        <a href="<?php echo admin_url('admin.php?page='.$_GET["page"].'&action=list_subred&idp='. $idp ) ?>">&laquo; Volver a la subred</a>
    	<?php
	}


}


?>