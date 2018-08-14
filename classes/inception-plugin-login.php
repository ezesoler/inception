<?php

class InceptionPlugin_Login
{

	function __construct()
    {
        add_action('wp_head', array(&$this,'add_login_bar') );
        add_shortcode( 'inc_login_form', array(&$this,'render_login_form'));
    }

    function render_login_form(){
        ?>
            <div class="inc-content-login">
                <?php
                if(isset($_GET['e'])){
                ?>
                    <label class="inc-error-login">Usuario o contraseña incorrecto</label>
                <?php
                }
                ?>
                <form class="inc-form-login" action="<?php echo WP_PLUGIN_URL; ?>/inception-plugin/inception-plugin-ldap-connect.php" method="POST">
                    <p class="inc-login-field">
                        <input type="text" name="username" placeholder="Nombre de usuario" required>
                        <i class="icon icon-user3"></i>
                    </p>
                    <p class="inc-login-field">
                            <input type="password" name="password" placeholder="Password" required>
                            <i class="icon icon-lock3"></i>
                    </p>
                    <p class="inc-login-field">
                            <select name="domain" required>
                                <option disabled selected hidden value="">Dominio</option>
                                <option value="inception.ar">inception.ar</option>
                                <option value="inception.com.ar">inception.com.ar</option>
                            </select>
                            <i class="icon icon-network2"></i>
                    </p>
                    <p class="inc-login-submit">
                        <button type="submit" name="submit">LOGIN<i class="icon icon-arrow-right"></i></button>
                    </p>
                </form>
            <div>
        <?php
    }


    function add_login_bar()
    {
    	?>
    	<style type="text/css">
            .inc-loginbar{
                width: 100%;
                text-align: right;
                padding-right: 3em;
                padding-top: 2em;
            }

            .inc-icon-login{
                margin-right: 0.5em;
            }

            #inc-btn-loginform{
                color: #333333;
                font-weight: 400;
                font-size: 0.8em;
                text-decoration: none;
            }

            .inc-content-login{
                margin-top: 3em;
                width: 100%;
            }

            .inc-form-login{
                width: 400px;
                margin:0 auto;
            }

            .inc-login-field{
                position:relative;
                padding:0 0 0 20px;
                margin:0 20px;
            }

            .inc-login-field input,select{
                text-align: left;
                margin-left: 8px;
            }

            .inc-login-field i{
                position:absolute;
                margin-right: 8px;
                top:8px;
                left:0px;
            }

            .inc-login-submit{
                padding:0 0 0 20px;
                margin:0 48px;
                text-align: right;
            }

            .inc-error-login{
                width: 400px;
                margin: 0 auto;
                text-align: center;
                padding: 1em 1em;
                color: red;
            }

        </style>
        <script type="text/javascript">
            jQuery("ready",INCLoginBar);

            function INCLoginBar(){
                header = jQuery(".container").first();

                jQuery.post( "<?php echo WP_PLUGIN_URL; ?>/inception-plugin/inception-plugin-ldap-connect.php", {chek_session:1} )
                .done(function( data ) {
                    userdata = JSON.parse(data);
                    if(userdata.status == "OK"){
                        header.prepend( '<div class="inc-loginbar"><span>Hola <b>'+userdata.name+'</b>!</span><a id="inc-btn-loginform" href="<?php echo WP_PLUGIN_URL; ?>/inception-plugin/inception-plugin-ldap-connect.php?logout=1"> | Cerrar sesi&oacute;n</a></div>'); 
                    }else{
                       header.prepend( '<div class="inc-loginbar"><a id="inc-btn-loginform" href="?page_id=<?php echo ID_PAGE_LOGIN; ?>"><span class="inc-icon-login icon icon-user3"></span>LOGIN</a></div>' ); 
                    }
                });
            }
        </script>
    	<?php
    }

}