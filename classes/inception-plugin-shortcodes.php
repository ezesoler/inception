<?php

class InceptionPlugin_Shortcodes
{
	function __construct()
    {
        add_shortcode( 'inc_birthdays', array(&$this,'render_birthdays'));
        add_shortcode( 'inc_schedule', array(&$this,'render_schedules'));
    }


    function render_schedules(){
    	if(isset($_SESSION['inc_emp_id'])){
    		/**************************************************************************
			****************************** PRODUCCIÓN *********************************
			**************************************************************************/
			$schedules = file_get_contents(URL_SCHEDULES.$_SESSION['inc_emp_id']);
            ?>
            <div style="margin-top: 25px;margin-bottom: 25px;">
            <?php
			echo utf8_encode($schedules);
            ?>
            </div>
            <?php
    	}else{
    		?>
    		<div style="text-align: center;margin-top: 20px;font-size: 1.2em;">
    			Debes loguearte para poder ver tus horarios.
    		</div>
    		<?php
    		KonectaPlugin_Login::render_login_form();
    	}
    	
    }

    function render_birthdays(){
    	?>
    		<style>
    			.inc_birthdays_content{
    				width:380px;
    			}

    			.inc_birthdays_title{
    				text-align: center;
    				border-bottom: 1px solid #113662;
    				margin-bottom: 10px;
    				padding-bottom: 2px;
    			}

    			#cumple h1{
    				display: none;
    			}

    			#cumple ul {
    				list-style: none;
    			}

    			#cumple ul li{
    				color: #a30e3f !important;
    				font-size: 80%;
    				font-style: italic;
    				border-bottom: 1px solid #eeeeee;
    				padding: 5px 5px;
    				margin: 5px 5px;
    			}

    			#cumple ul li:before {    
					font-family: 'icomoon';
					content: '\f1fd';
					margin: 0 12px 0 12px;
					background: #479b99;
					color: #fff;
					width: auto !important;
 					height: auto !important;
    				vertical-align: top!important;
					font-size: 12px;
    				line-height: 20px;
    				font-style: normal !important;
    				padding: 5px;
    				border-radius: 40px;
				}

    			#cumple ul li strong{
    				font-weight: 600;
    				color: #444 !important;
    				font-size: 100% !important;
    				font-style: normal !important;
    			}
    		</style>
    		<div class="inc_birthdays_content">
	    		<div class="inc_birthdays_title">
	    			<img src="<?php echo wp_get_attachment_url(ID_IMG_BIRTHDAYS_TITLE); ?>" />
	    		</div>
	    		<?php 
	    		/**************************************************************************
				****************************** DESARROLLO *********************************
				**************************************************************************/

				/*
				
				<div id="cumple" style="height : 400px; overflow : auto; ">
				   <h1>Cumplea&ntilde;os de hoy!</h1>
				   <ul>
				      <li><strong>Christian</a></strong> <p>(C: Cuenta A)</p></li>
				      <li><strong>Cecilia</a></strong> (C: Cuenta A)</li>
				      <li><strong>Graciela</a></strong> (C: Cuenta A)</li>
				      <li><strong>Marquesa</a></strong> (C: Cuenta B)</li>
				      <li><strong>Julio</a></strong> (C: Cuenta A)</li>
				      <li><strong>Giannina</a></strong> (C: Cuenta A)</li>
				      <li><strong>Maximiliano</a></strong> (C: Cuenta B)</li>
				      <li><strong>Cintia</a></strong> (C: Cuenta C)</li>
				      <li><strong>Sergio</a></strong> (C: Cuenta A)</li>
				      <li><strong>Erika</a></strong> (C: Cuenta A)</li>
				      <li><strong>Jessica</a></strong> (C: Cuenta C)</li>
				      <li><strong>Marta</a></strong> (C: Cuenta C)</li>
				      <li><strong>Alejandra</a></strong> (C: Cuenta A)</li>
				      <li><strong>Rosa Meltroso</a></strong> (C: Cuenta B)</li>
				   </ul>
				</div>
				*/

				/**************************************************************************
				****************************** PRODUCCIÓN *********************************
				**************************************************************************/

				$birthdays = file_get_contents(URL_BIRTHDAYS);
				echo utf8_encode($birthdays);
	    		?>
	    		
			</div>
    	<?php
    }
}