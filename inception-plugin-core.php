<?php

if(!defined('ABSPATH')){
    exit;//Si se accede directamente.
}


if (!class_exists('Inception_plugin')){

	class Inception_plugin{

		function __construct()
		{
			include_once('inception-plugin-config.php');
			include_once('classes/inception-plugin-meta-box.php');
			include_once('classes/inception-plugin-filter.php');
			include_once('classes/inception-plugin-login.php');
			include_once('classes/inception-plugin-shortcodes.php');
			include_once('classes/inception-plugin-log.php');

		   	if (is_admin()){
		   		include_once('classes/inception-plugin-admin.php');
		   		$GLOBALS['inception_plugin_admin'] = new Inception_Plugin_Admin();
	            add_action('admin_menu', array(&$this, 'create_admin_menus'));
	        }
		}

		static function activate_handler()
	    {
	        //Solo se ejecuta cuando se activa el plugin.
	        include_once ('classes/inception-plugin-installer.php');
	        include_once ('classes/inception-plugin-importer.php');
	        InceptionPlugin_Installer::run_installer();
	    }


	     function create_admin_menus()
	    { 
	    	add_menu_page( 'Inception Plugin', 'Inception Plugin', 'edit_posts', 'inc_plazas', array("Inception_Plugin_Admin",'render_page') );
   			add_submenu_page( 'inc_plazas', 'Plazas', 'Plazas', 'edit_posts', 'inc_plazas', array("Inception_Plugin_Admin",'render_page') );
   			add_submenu_page( 'inc_plazas', 'Segmentacion', 'Segmentacion', 'edit_posts', 'inc_segment', array("Inception_Plugin_Admin",'render_page') );
   			if(SHOW_DEBUG){
   				add_submenu_page( 'inc_plazas', 'Log', 'Log', 'edit_posts', 'inc_log', array("Inception_Plugin_Admin",'render_page') );
   			}
	    }


	}//Fin clase

}//Fin check si la clase existe

$GLOBALS['inception_plugin'] = new Inception_plugin();
$GLOBALS['inception_plugin_meta_box'] = new InceptionPlugin_MetaBox();
$GLOBALS['inception_plugin_login'] = new InceptionPlugin_Login();
$GLOBALS['inception_plugin_shortcodes'] = new InceptionPlugin_Shortcodes();
$GLOBALS['inception_plugin_filter'] = new InceptionPlugin_Filter();