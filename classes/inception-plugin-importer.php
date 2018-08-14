<?php

class InceptionPlugin_Importer
{
	const CUENTA_SUBAREA_SERVICIO = "inc_CuentaSubAreaServicio";
	const SOCIEDAD = "inc_sociedad";
	const SUCURSAL = "inc_sucursal";
	const UBICACION = "inc_ubicacion";

	static function reset_data()
	{
		global $wpdb;
    	
    	$table_name = $wpdb->prefix . 'inc_cuenta';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_subarea';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_servicio';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_sociedad';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_sucursal';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_site';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	$table_name = $wpdb->prefix . 'inc_call';
    	$wpdb->query("TRUNCATE TABLE $table_name");

    	self::run_importer();
	}

	static function run_importer()
    {
		// Cuentas, Subareas, Servicios
		$respond = file_get_contents(API_URI.self::CUENTA_SUBAREA_SERVICIO);

		$json = json_decode($respond, true);

		$cuentas = array();
		$subareas = array();
		$servicios = array();

		//Se recorre el json y se guardan en los array los valores no repetidos, para luego ingresarlos en la tabla.
		foreach ($json as $val) {
			$save_cue = true;
			$save_sar = true;
			$save_srv = true;
			foreach ($cuentas as $key => $cuenta) {
				if($val['cue_id'] == $cuenta['cue_id']){
					$save_cue = false;
				}
			}
			foreach ($subareas as $key => $subarea) {
				if($val['sar_id'] == $subarea['sar_id']){
					$save_sar = false;
				}
			}
			foreach ($servicios as $key => $servicio) {
				if($val['srv_id'] == $servicio['srv_id']){
					$save_srv = false;
				}
			}
			if($save_cue){
				$cuentas[] = array('cue_id'=>$val['cue_id'],'cue_nombre'=>$val['cue_nombre']);
			}
			if($save_sar){
				$subareas[] = array('sar_id'=>$val['sar_id'],'sar_nombre'=>$val['sar_nombre'],'cue_id'=>$val['cue_id']);
			}
			if($save_srv){
				$servicios[] = array('srv_id'=>$val['srv_id'],'srv_nombre'=>$val['srv_nombre'],'cue_id'=>$val['cue_id'],'sar_id'=>$val['sar_id']);
			}
		}

		global $wpdb;

		//Se ingresan los valores no repetidos en las distintas tablas

		$table_name = $wpdb->prefix . 'inc_cuenta';

    	$values = "";

		foreach ($cuentas as $key => $cuenta) {
			if ($values) $values .= ',';
			$values .= "(".$cuenta['cue_id'].",'".$cuenta['cue_nombre']."')";
		}

		$wpdb->query("INSERT INTO $table_name
            (cue_id,cue_nombre)
            VALUES ". $values);

		$table_name = $wpdb->prefix . 'inc_subarea';

    	$values = "";

		foreach ($subareas as $key => $subarea) {
			if ($values) $values .= ',';
			$values .= "(".$subarea['sar_id'].",'".$subarea['sar_nombre']."',".$subarea['cue_id'].")";
		}

		$wpdb->query("INSERT INTO $table_name
            (sar_id,sar_nombre,cue_id)
            VALUES ". $values);

		$table_name = $wpdb->prefix . 'inc_servicio';

    	$values = "";

		foreach ($servicios as $key => $servicio) {
			if ($values) $values .= ',';
			$values .= "(".$servicio['srv_id'].",'".$servicio['srv_nombre']."',".$servicio['cue_id'].",".$servicio['sar_id'].")";
		}

		$wpdb->query("INSERT INTO $table_name
            (srv_id,srv_nombre,cue_id,sar_id)
            VALUES ". $values);

		//Sociedades

		$respond = file_get_contents(API_URI.self::SOCIEDAD);

		$json = json_decode($respond, true);

		$table_name = $wpdb->prefix . 'inc_sociedad';

		$values = "";

		foreach ($json as $val) {
			if ($values) $values .= ',';
			$values .= "(".$val['sociedad_id'].",'".$val['sociedad_nombre']."')";
		}

		$wpdb->query("INSERT INTO $table_name
            (sociedad_id,sociedad_nombre)
            VALUES ". $values);

		//Sucursales

		$respond = file_get_contents(API_URI.self::SUCURSAL);

		$json = json_decode($respond, true);

		$table_name = $wpdb->prefix . 'inc_sucursal';

		$values = "";

		foreach ($json as $val) {
			if ($values) $values .= ',';
			$values .= "(".$val['suc_id'].",'".$val['suc_nombre']."')";
		}

		$wpdb->query("INSERT INTO $table_name
            (suc_id,suc_nombre)
            VALUES ". $values);

		//Sites, Calls

		$respond = file_get_contents(API_URI.self::UBICACION);

		$json = json_decode($respond, true);

		$sites = array();
		$calls = array();

		//Se recorre el json y se guardan en los array los valores no repetidos, para luego ingresarlos en la tabla.
		foreach ($json as $val) {
			$save_site = true;
			$save_call = true;

			foreach ($sites as $key => $site) {
				if($val['site_id'] == $site['site_id']){
					$save_site = false;
				}
			}
			foreach ($calls as $key => $call) {
				if($val['call_id'] == $call['call_id']){
					$save_call = false;
				}
			}
			
			if($save_site){
				$sites[] = array('site_id'=>$val['site_id'],'site_nombre'=>$val['site_nombre'],'suc_id'=>$val['suc_id']);
			}
			if($save_call){
				$calls[] = array('call_id'=>$val['call_id'],'call_nombre'=>$val['call_nombre'],'site_id'=>$val['site_id']);
			}
			
		}

		//Se ingresan los valores no repetidos en las distintas tablas

		$table_name = $wpdb->prefix . 'inc_site';

    	$values = "";

		foreach ($sites as $key => $site) {
			if ($values) $values .= ',';
			$values .= "(".$site['site_id'].",'".$site['site_nombre']."',".$site['suc_id'].")";
		}

		$wpdb->query("INSERT INTO $table_name
            (site_id,site_nombre,suc_id)
            VALUES ". $values);

		$table_name = $wpdb->prefix . 'inc_call';

    	$values = "";

		foreach ($calls as $key => $call) {
			if ($values) $values .= ',';
			$values .= "(".$call['call_id'].",'".$call['call_nombre']."',".$call['site_id'].")";
		}

		$wpdb->query("INSERT INTO $table_name
            (call_id,call_nombre,site_id)
            VALUES ". $values);

		//Se actualiza fecha de actualización
		$option = "inc_last_update_segment";
		$datetime = date('Y-m-d H:i:s');
		if(get_option($option)){
         update_option($option, $datetime);
	    }
	    else {
	      add_option($option, $datetime);
	    }

    }

}
?>