<?php

class InceptionPlugin_Installer
{
	static function run_installer()
    {
        InceptionPlugin_Installer::create_db_tables();
        InceptionPlugin_Installer::data_db_tables();
        InceptionPlugin_Importer::run_importer();
    }

    static function create_db_tables()
    {
    	global $wpdb;
    	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    	$charset_collate = $wpdb->get_charset_collate();

        //Tabla Plazas
    	$table_name = $wpdb->prefix . 'inc_plazas';

    	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql );

        //Tabla Subred
        $table_name = $wpdb->prefix . 'inc_subred';

        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        id_plaza mediumint(9) NOT NULL,
        segmento tinytext NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Cuenta
        $table_name = $wpdb->prefix . 'inc_cuenta';

        $sql = "CREATE TABLE $table_name (
        cue_id mediumint(9) NOT NULL,
        cue_nombre tinytext NOT NULL,
        PRIMARY KEY  (cue_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Subarea
        $table_name = $wpdb->prefix . 'inc_subarea';

        $sql = "CREATE TABLE $table_name (
        sar_id mediumint(9) NOT NULL,
        sar_nombre tinytext NOT NULL,
        cue_id mediumint(9) NOT NULL,
        PRIMARY KEY  (sar_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Servicio
        $table_name = $wpdb->prefix . 'inc_servicio';

        $sql = "CREATE TABLE $table_name (
        srv_id mediumint(9) NOT NULL,
        srv_nombre tinytext NOT NULL,
        cue_id mediumint(9) NOT NULL,
        sar_id mediumint(9) NOT NULL,
        PRIMARY KEY  (srv_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Sociedad
        $table_name = $wpdb->prefix . 'inc_sociedad';

        $sql = "CREATE TABLE $table_name (
        sociedad_id mediumint(9) NOT NULL,
        sociedad_nombre tinytext NOT NULL,
        PRIMARY KEY  (sociedad_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Sucursal
        $table_name = $wpdb->prefix . 'inc_sucursal';

        $sql = "CREATE TABLE $table_name (
        suc_id mediumint(9) NOT NULL,
        suc_nombre tinytext NOT NULL,
        PRIMARY KEY  (suc_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Sites
        $table_name = $wpdb->prefix . 'inc_site';

        $sql = "CREATE TABLE $table_name (
        site_id mediumint(9) NOT NULL,
        site_nombre tinytext NOT NULL,
        suc_id mediumint(9) NOT NULL,
        PRIMARY KEY  (site_id)
        ) $charset_collate;";

        dbDelta( $sql );

        //Tabla Call
        $table_name = $wpdb->prefix . 'inc_call';

        $sql = "CREATE TABLE $table_name (
        call_id mediumint(9) NOT NULL,
        call_nombre tinytext NOT NULL,
        site_id mediumint(9) NOT NULL,
        PRIMARY KEY  (call_id)
        ) $charset_collate;";

        dbDelta( $sql );
    }

    static function data_db_tables()
    {
    	global $wpdb;

    	$table_name = $wpdb->prefix . 'inc_plazas';

    	$wpdb->query("INSERT INTO $table_name
            (name)
            VALUES
            ('Plaza 1'),
            ('Plaza 2'),
            ('Plaza 3'),
            ('Plaza 4')");

        //Segmentos de las subredes, iniciales.

        $table_name = $wpdb->prefix . 'inc_subred';

        //Plaza 1
        $wpdb->query("INSERT INTO $table_name
            (id_plaza,segmento)
            VALUES
            (1,'90.128.130.104/29'),
            (1,'90.128.130.112/29'),
            (1,'15.200.1.0/24'),
            (1,'192.200.10.0/24'),
            (1,'172.69.13.0/24')");

        //Plaza 2
        $wpdb->query("INSERT INTO $table_name
            (id_plaza,segmento)
            VALUES
            (2,'192.200.168.0/24'),
            (2,'172.192.169.0/24'),
            (2,'172.61.11.0/24')");

        //Plaza 3
        $wpdb->query("INSERT INTO $table_name
            (id_plaza,segmento)
            VALUES
            (3,'10.204.20.0/24'),
            (3,'10.204.4.0/24'),
            (3,'172.68.15.0/24')");

        //Plaza 4
        $wpdb->query("INSERT INTO $table_name
            (id_plaza,segmento)
            VALUES
            (4,'172.0.26.0/24'),
            (4,'172.0.35.0/24'), 
            (4,'172.0.36.0/24')");

    }
}

?>