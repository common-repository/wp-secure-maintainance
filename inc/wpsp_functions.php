<?php

/**
 * @author Mohammad Mursaleen
 * function to add PIN protection functionality
 */

 
if ( !is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )   ) :
    
    function wpsp_display_security_form(){
        
        $options = get_option( 'wp-secure-settings_options' ); 
         $color = get_option( 'wpsp-background-color' );
         $new_image = get_option( 'wpsp-background-image' );
         $old_image = get_option( '_background_image' );
        // echo '<pre>';
        // var_dump($options);
        // var_dump($color);
        // echo '</pre>';
        // die();
      
        if(isset($old_option) && $old_option == 'on' && !is_array($options) && !isset($options['wpsp-enable-maintenance-mode'])) {
            $value = 1;
        } else {
            $value = ( isset( $options['wpsp-enable-maintenance-mode'] ) ) ? $options['wpsp-enable-maintenance-mode'] : false;
        }
        if( $value != 1 )  // check if this functionality is enabled
        return false;

        $old_pin = get_option( '_pin' );
        $new_pin = $options[ 'wpsp-password' ];  // Get saved PIN
        $pin = wpsp_get_option( $options, $old_pin, 'wpsp-password' );


        if ( isset($_COOKIE['wpsp_pass']) && $pin == $_COOKIE['wpsp_pass'] ) // to check if PIN cookie exist
        return false;
        if (( isset($_POST['page_password']) && $pin == $_POST['page_password']) ) {
            setcookie('wpsp_pass', $pin , 0 , '/' );  // set password in cookie
            return false;
        } else {
           
            $new_color = ( isset( $color['wpsp-background-color'] ) ) ? $color['wpsp-background-color'] : ''; // Get saved background color
            $old_color = get_option( '_crb_background' );
            $color = wpsp_get_option( $options, $old_color, 'wpsp-background-color' );

            $new_background_image = ( isset( $new_image['wpsp-background-image'] ) ) ? $new_image['wpsp-background-image'] : ''; // Get saved background color
            $old_background_image = ( isset( $old_image['_background_image'] ) ) ? $old_image['_background_image'] : ''; // Get saved background color
    
            if(isset($old_background_image) && !isset($new_background_image)) {
                $urls = wp_get_attachment_url($old_background_image);
            } else {
                $urls = ( isset( $options['wpsp-background-image'] ) ) ? wp_get_attachment_url(attachment_url_to_postid($options['wpsp-background-image']) ? attachment_url_to_postid($options['wpsp-background-image']) : $options['wpsp-background-image']) : plugins_url( 'img/secure.jpg', dirname(__FILE__));
            }
	  ?>
      <style>
          
      <?php if($color) { ?>
        body {		
            background-color: <?php echo $color; ?> !important
        }
    <?php } else { ?>
       body {
        background-color: rgba(95, 158, 190, 0.22) !important
    }
<?php	}?>


img.wpsp-logo {
    border-radius: 50%;
    box-shadow: 10px 10px 10px rgb(255 255 255 / 58%);
    text-align: center;
    margin: 0px auto 0px auto;
    position: relative;
    width: 450px;
    length: 50px;
}

.style input[type="password"] {
    padding: 10px 0px 5px 0px;
}

.objects_password_form {
    margin-bottom: 20px;
    text-align: center;
    font-size: 13px;
    font-family: sans-serif;
    font-weight: bold;
}
.container{
    width: 100%;
    margin: 0 auto;
}
/*for button*/
.wpsp-submit-button {
    background: #313131;
    border-radius: 5px;
    padding: 10px 20px;
    color: #FFFFFF;
    font-size: 17px;
    margin-top: 20px;
}
.wpsp-logo-div {
    text-align: center;
    margin-bottom: 20px;
}
input#user_pass{
    /* color: rgba(255, 255, 255, 0.89) !important;*/
}
input#user_pass , input#user_pass:focus {
    background: none;
    border: none;
    border-bottom: 1px solid #000000;
}
input#user_pass {
    font-size: 15px;
}
textarea:focus, input:focus{
    outline: none;
}
.title_front {
    color: white !important;
}
.desc_front {
    margin: 30px;
    color: white !important;
}

.wrapper {
    padding: 20px;
    background-color: #fff;
    border: 1px solid #fff;
    width: 300px;
    max-width: 80%;
    margin: 20px auto 0 auto;
    box-shadow: 10px 10px 15px #a5a5a5;
}
<?php echo $options['wpsp-custom-css']; ?>
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="container" >
    <div class="wpsp-logo-div">
    <?php 
        $new_logo = get_option('wpsp-logo');
        $old_logo = get_option( '_logo_image' );
       

        if(isset($old_logo) && !isset($new_logo)) {
            $url = wp_get_attachment_url($old_logo);
        } else {
            $url = ( isset( $options['wpsp-logo'] ) ) ? wp_get_attachment_url(attachment_url_to_postid($options['wpsp-logo']) ? attachment_url_to_postid($options['wpsp-logo']) : $options['wpsp-logo']) : plugins_url( 'img/default-logo.png', dirname(__FILE__));
        }

    

        $old_width = get_option('_logo_width');
        $width = wpsp_get_option( $options, $old_width, 'wpsp-logo-width' );

        $old_height = get_option( '_logo_height' );
        $height = wpsp_get_option( $options, $old_height, 'wpsp-logo-height' );

        $old_placeholder = get_option( '_pin_placeholder' );
        $placeholder = wpsp_get_option( $options, $old_placeholder, 'wpsp-placeholder-text' );

        $old_submit = get_option( '_submit_label' );
        $submit = wpsp_get_option( $options, $old_submit, 'wpsp-label-submit-button' );

        $old_error_message = get_option( '_try_again_error' );
        $error_message = wpsp_get_option( $options, $old_error_message, 'wpsp-error-message' );

        $old_description = get_option( '_description' );
        $description = wpsp_get_option( $options, $old_description, 'wpsp-description' );

        $old_title = get_option( '_title_field' );
        $title = wpsp_get_option( $options, $old_title, 'wpsp-title-field' );

    ?>
        <div
  class="bg-image"
  style="background-image: url(<?php echo esc_url($urls); ?>);
         height: 100%;
         background-position: center;
         background-repeat: no-repeat;
         background-size: cover;"
>
  <div class="card-body text-white">
  <h1 class="title_front"> <?php echo esc_attr($title); ?></h1>
  <img class="wpsp-logo" style="height:<?php echo esc_attr($height); ?>  !important; width:<?php echo esc_attr($width); ?> !important;" src="<?php echo esc_url($url); ?>" alt="" >
  <div class="desc_front"> <?php echo ($description); ?></div>


    </div>
    <div class="wrapper">
        <div class="objects_password_form style">
            <form action="" method="post" >
                <!-- <label> &nbsp;</label>-->
                
                <input id="user_pass" placeholder="<?php echo esc_attr($placeholder); ?>" type="password" name="page_password">
                <br>
                <input  type="submit" class="wpsp-submit-button" value="<?php echo esc_attr($submit); ?>">
            </form>
            <?php
            if(isset($_POST['page_password']))
                echo '<p class="error" style="color:#ff5348;">'.esc_attr($error_message) . '</p>'. '<br>';?>
        </div>
    </div>
</div>
</div>
</div>
<?php
die();
}
}
add_action('init','wpsp_display_security_form',20 );
function wpsp_get_option( $new_value, $old_value, $field_name ) {
    if( isset( $old_value ) && !isset( $new_value[$field_name] ) ) {
        return $old_value;
    } else {
        return $new_value[$field_name];
    }
}
endif;
