<?php

/**
* Plugin Name: Inception Plugin
* Plugin URI: https://ezesoler.com
* Description: Plugin de integración con distintos servicios de Inception.
* Version: 1.0.0
* Author: Ezequiel Soler
* Author URI: https://ezesoler.com
* License: GPL2
* ˄˄ ˅˅ ˂˃ ˂˃ B A
*/

if(!defined('ABSPATH')){
    exit;//Si se accede directamente.
}

include_once('inception-plugin-core.php');
register_activation_hook(__FILE__,array('Inception_plugin','activate_handler'));//activation hook

?>