<?php
include_once '../../../wp-load.php';
include_once('inception-plugin-config.php');
include_once('classes/inception-plugin-log.php');
include_once('vendor/adLDAP.php');

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['domain'])){
	
	/**************************************************************************
	****************************** PRODUCCIÓN *********************************
	**************************************************************************/
	
	/*
	$adldap = null;

	if($_POST['domain'] == "inception.ar"){
		$adldap = new adLDAP("@inception.ar","DC=inception,DC=ar",array("192.22.1.115"));
	}else if($_POST['domain'] == "inception.com.ar"){
		$adldap = new adLDAP("@inception.com.ar","DC=inception,DC=com,DC=ar",array("172.60.7.118"));
	}else{
		echo json_encode(array("status"=>"NOK","error"=>"No domain"));
		die();
	}

	$authUser = $adldap->authenticate($_POST['username'], $_POST['password']);
	*/

	/**************************************************************************
	****************************** DESARROLLO *********************************
	**************************************************************************/

	if($_POST['username'] == "test" && $_POST['password'] == "necronomicron"){
		$authUser = true;
	}
	

	if ($authUser == true) {

		//Si no hay sesión se inicia una
		if (!session_id()) {
		    session_start();
		}
		try{

			$respond = file_get_contents(API_URI."obtener?userName=".$_POST['username']."&domainName=".$_POST['domain']);

			if ($respond === false) {
				$_SESSION['inc_username'] = $_POST['username'];
				$_SESSION['inc_domain'] = $_POST['domain'];
				$_SESSION['inc_name'] = $_POST['username'];

				$_SESSION['cue_id'] = "null";
				$_SESSION['sar_id'] = "null";
				$_SESSION['srv_id'] = "null";

				$_SESSION['sociedad_id'] = "null";
				
				$_SESSION['suc_id'] = "null";
				$_SESSION['site_id'] = "null";
				$_SESSION['call_id'] = "null";

				InceptionPlugin_Log::write("Usuario no existente en Sistema: ".$_POST['username']." dominio: ".$_POST['domain']);

	    	}else{
	    		$json = json_decode($respond, true);
				$_SESSION['inc_username'] = $_POST['username'];
				$_SESSION['inc_domain'] = $_POST['domain'];
				$_SESSION['inc_name'] = $json["Nombre"];

				$_SESSION['cue_id'] = $json["cue_id"];
				$_SESSION['sar_id'] = $json["sar_id"];
				$_SESSION['srv_id'] = $json["srv_id"];

				$_SESSION['sociedad_id'] = $json["sociedad_id"];
				
				$_SESSION['suc_id'] = $json["suc_id"];
				$_SESSION['site_id'] = $json["site_id"];
				$_SESSION['call_id'] = $json["call_id"];
	    	}

    	} catch (Exception $e) {
    		InceptionPlugin_Log::write("Error al recuperar los datos en Sistema: ".$_POST['username']." dominio: ".$_POST['domain']);
		    wp_redirect(home_url()."?page_id=".ID_PAGE_LOGIN."&e=1");
		}

		InceptionPlugin_Log::write("Usuario logueado: ".$_POST['username']." dominio: ".$_POST['domain']);

		wp_redirect(home_url());
		exit();
		
	}else{
		InceptionPlugin_Log::write("Login fallido: ".$_POST['username']." dominio: ".$_POST['domain']);

		wp_redirect(home_url()."?page_id=".ID_PAGE_LOGIN."&e=1");
		exit();
	}
}else if(isset($_POST['chek_session'])){
	if(isset($_SESSION['inc_username'])) {
		echo json_encode(array("status"=>"OK","username"=>$_SESSION['inc_username'],"name"=>$_SESSION['inc_name']));
		die();
	}else{
		echo json_encode(array("status"=>"NOK"));
		die();
	}
}else if(isset($_GET['logout'])){
	session_destroy();
	wp_redirect(home_url());
	exit();
}else{
	die();
}

?>