<?php
/**
* Plugin Name: WP Secure Maintainance
* Plugin URI: https://wpexperts.io/products/wp-secure-maintenance/
* Description: Want to lock your site for Maintainance or Development? Then this is the right Plugin.
* Version:           1.7
* Requires at least: 5.2
* Requires PHP:      7.0
* Author: wpexpertsio
* Author URI: https://wpexperts.io/
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domaub: wpsp
*/

// Exit if accessed directly
// wp_die(plugin_dir_url(__FILE__) . 'inc/wpsp_functions.php');
require_once( plugin_dir_path(__FILE__) . 'inc/wpsp_functions.php' );
if ( !defined( 'ABSPATH' ) ) exit;


class WPSP_Settings {
   
    
	public function __construct() {

		add_action( 'admin_menu', array($this, 'wpsp_registerMenu') );
        add_action( 'admin_init', array($this, 'wpsp_settings') );
        add_action( 'admin_enqueue_scripts', array($this, 'wpsp_enqueue_scripts') );
        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_media();
        });
		
    }

    public function wpsp_registerMenu() {
        add_menu_page( 
            __( 'WP Secure Maintenance', 'wpsp' ),
            __( 'WP Secure Settings', 'wpsp' ),
            'manage_options',
            'wpsp-settings',
            array($this, 'settings_menu'),
            plugin_dir_url( __FILE__ ) . 'img/icon.png'
        );
        

    }
    
    public function wpsp_enqueue_scripts() {
        wp_enqueue_style( 'wpsp-styles', plugin_dir_url(__FILE__) . "css/style.css" );
        wp_enqueue_script( 'wpsp-scripts', plugin_dir_url(__FILE__) . "js/wpsp-scripts.js", array('jquery', 'media-editor'));
        wp_enqueue_script( 'js/jquery.min.js');
	

        wp_enqueue_script( 'media-upload' );
    }

	public function settings_menu() {
		require plugin_dir_path(__FILE__) . "inc/wpsp_options.php";
	}

    public function wpsp_settings() {
        register_setting( 'wp-secure-settings_options_group', 'wp-secure-settings_options',  array( $this, 'sanitize' ));
       
        //Genral

        add_settings_section( 'wpsp', 'General Settings', array($this, 'wpsp_settings_callback'), 'wpsp-settings' );

        add_settings_field( 'wpsp-enable-maintenance-mode', 'Enable', array($this, 'wpsp_enable_maintenance_mode'), 'wpsp-settings', 'wpsp' );
        add_settings_field( 'wpsp-title-field', 'Title', array($this, 'wpsp_title_field'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-password', 'Password <span style="color:red;">*</span>', array($this, 'wpsp_password'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-description', 'Description', array($this, 'wpsp_description'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-label-submit-button', 'Label For Submit Button (Optional)', array($this, 'wpsp_label_submit_button'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-placeholder-text', 'Placeholder Text (Optional)', array($this, 'wpsp_placeholder_text'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-error-message', 'Error Message (Optional)', array($this, 'wpsp_error_message'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wsps_general_settings_tab', 'General Setting Tab', array($this, 'wsps_general_settings_tab'), 'wpsp-settings' , 'wpsp' );
   
       //CSS-CUSTOM FEILD
       
        add_settings_section( 'wpsp-css', 'Custom CSS', array($this, 'wpsp_settings_callback'),'wpsp-settings&tab=custom_css' );

        add_settings_field( 'wpsp-custom-css', 'Custom CSS', array($this, 'wpsp_custom_css'), 'wpsp-settings&tab=custom_css' , 'wpsp-css' );
         add_settings_field( 'wsps_css_settings_tab', 'Custom CSS Tab', array($this, 'wsps_css_settings_tab'), 'wpsp-settings&tab=custom_css' , 'wpsp-css');
   
        //DISPLAY SETTING
        add_settings_section( 'wpsp-display-settings', ' Settings', array($this, 'wpsp_settings_callback'), 'wpsp-settings&tab=settings' );

        add_settings_field( 'wpsp-logo', 'Add Logo', array($this, 'wpsp_logo'), 'wpsp-settings&tab=settings', 'wpsp-display-settings'  );
        add_settings_field( 'wpsp-logo-height', 'Logo Height', array($this, 'wpsp_logo_height'), 'wpsp-settings&tab=settings', 'wpsp-display-settings');
        add_settings_field( 'wpsp-logo-width', 'Logo Width', array($this, 'wpsp_logo_width'), 'wpsp-settings&tab=settings' , 'wpsp-display-settings');
        add_settings_field( 'wpsp-background-image', 'Background Image', array($this, 'wpsp_background_image') , 'wpsp-settings&tab=settings' , 'wpsp-display-settings' );
        add_settings_field( 'wpsp-background-color', 'Background Color', array($this, 'wpsp_background_color'), 'wpsp-settings&tab=settings' , 'wpsp-display-settings' );
        add_settings_field( 'wsps_display_settings_tab', 'Display Setting', array($this, 'wsps_display_settings_tab') ,'wpsp-settings&tab=settings' , 'wpsp-display-settings'  );
     
    
}

    public function wpsp_settings_callback() {
        
    }
    /**
     * hidden field to detect tab in sanitize function.
     */
    public function wsps_general_settings_tab() {
        echo '<input id="settings_tab" name="wp-secure-settings_options[settings_tab]" type="hidden" value="general_Settings" />';
    }

    /**
     * hidden field to detect tab in sanitize function.
     */
    public function wsps_css_settings_tab() {
        echo '<input id="wsps_css_tab" name="wp-secure-settings_options[settings_tab]" type="hidden" value="custom_css" />';
    }

    /**
     * hidden field to detect tab in sanitize function.
     */
    public function wsps_display_settings_tab() {
        echo '<input id="wsps_display_tab" name="wp-secure-settings_options[settings_tab]" type="hidden" value="display_settings" />';
    }
    /**
    * Remove special character
    */
    public function remove_special_character( $input ) {
        return preg_replace('/[^A-Za-z0-9]/', ' ', $input);
    }
    /**
     * Sanitize the plugin settings
     */
    public function sanitize( $input ) {

        $current = get_option( 'wp-secure-settings_options' ,array());
       
        if ( isset($input['settings_tab']) && 'general_Settings' == $input['settings_tab'] ) {
  
            if ( isset($input['wpsp-enable-maintenance-mode']) && '1' == $input['wpsp-enable-maintenance-mode'] ) {
                $current['wpsp-enable-maintenance-mode'] = 1;
                    
            } else {
                $current['wpsp-enable-maintenance-mode'] = 0;
            }
             if ( isset($input['wpsp-title-field']) ) {
                $current['wpsp-title-field'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-title-field'] ));
            }
             if ( isset($input['wpsp-password']) && !empty($input['wpsp-password']) ) {
                $current['wpsp-password'] =  sanitize_text_field( $input['wpsp-password'] );
            }
             if ( isset($input['wpsp-description']) ) {
                $current['wpsp-description'] =  sanitize_text_field( $input['wpsp-description'] );
            }
             if ( isset($input['wpsp-label-submit-button']) ) {
                $current['wpsp-label-submit-button'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-label-submit-button'] ));
            }
             if ( isset($input['wpsp-placeholder-text']) ) {
                $current['wpsp-placeholder-text'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-placeholder-text'] ));
            }
             if ( isset($input['wpsp-error-message']) ) {
                $current['wpsp-error-message'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-error-message'] ));
            }
        } else if ( isset($input['settings_tab']) && 'display_settings' == $input['settings_tab'] ) {
           
            if ( isset($input['wpsp-logo']) ) {
                $current['wpsp-logo'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-logo'] ));
            }
             if ( isset($input['wpsp-logo-height']) ) {

                $current['wpsp-logo-height'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-logo-height'] ));
            }
             if ( isset($input['wpsp-logo-width']) ) {
                $current['wpsp-logo-width'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-logo-width'] ));
            }
             if ( isset($input['wpsp-background-image']) ) {
                $current['wpsp-background-image'] =  $this->remove_special_character(sanitize_text_field( $input['wpsp-background-image'] ));
            }
             if ( isset($input['wpsp-background-color']) ) {
                $current['wpsp-background-color'] =  sanitize_text_field( $input['wpsp-background-color'] );
            }

            
        } else if ( isset($input['settings_tab']) && 'custom_css' == $input['settings_tab'] ) {

             if ( isset($input['wpsp-custom-css']) ) {
                $current['wpsp-custom-css'] =  sanitize_text_field( $input['wpsp-custom-css'] );
            }
        }

        return $current;
    }

  
   
    public function wpsp_enable_maintenance_mode() {

        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_enable' );

        if(isset($old_option) && $old_option == 'on' && !is_array($options) && !isset($options['wpsp-enable-maintenance-mode'])) {
            $value = 1;
        } else {
            $value = ( isset( $options['wpsp-enable-maintenance-mode'] ) ) ? $options['wpsp-enable-maintenance-mode'] : false;
        }

        echo '<label class="switch"><input type="checkbox" value="1" name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"'.checked( $value, 1, false ).'><span class="slider round"></span></label>';
    }

    public function wpsp_background_image() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_background_image' );
        if(isset($old_option) && !isset($options['wpsp-background-image'])) {
            $url = wp_get_attachment_url($old_option);
        } else {
            $url = ( isset( $options['wpsp-background-image'] ) ) ? (attachment_url_to_postid($options['wpsp-background-image']) ? attachment_url_to_postid($options['wpsp-background-image']) : $options['wpsp-background-image']) : plugin_dir_url( __FILE__ ) . 'img/secure.jpg';
        }

        echo '<input class="wp-secure-settings-background_image" type="hidden" name="wp-secure-settings_options[wpsp-background-image]" value="'.$url.'">';
        echo '<div class="container"><div class="avatar-upload"><div class="avatar-edit"><input type="file"  id="wpsp-background-imageUpload"  accept=".png, .jpg, .jpeg" /><label class="onetarek-background-upload-button" for="imageUpload"><span style="margin: 7px 7px;" class="dashicons dashicons-edit"></span></label></div><div class="avatar-preview"><div id="bgimagePreview" style="background-image: url('. wp_get_attachment_url($url) .');"><label class="onetarek-background-delete-button" for="imageUpload"><span style="margin: 7px 7px;" class="dashicons dashicons-trash"></span></label></div></div></div></div>';
    }
  
    public function wpsp_logo() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_logo_image' );
        if(isset($old_option) && !isset($options['wpsp-logo'])) {
            $url = wp_get_attachment_url($old_option);
        } else {
            $url = ( isset( $options['wpsp-logo'] ) ) ? (attachment_url_to_postid($options['wpsp-logo']) ? attachment_url_to_postid($options['wpsp-logo']) : $options['wpsp-logo']) : plugin_dir_url( __FILE__ ) . 'img/icon.png';
        }

        echo '<input class="wp-secure-settings-logo_image" type="hidden" name="wp-secure-settings_options[wpsp-logo]" value="'.$url.'">';
        echo '<div class="container"><div class="avatar-upload"><div class="avatar-edit"><input type="file"  id="wpsp-imageUpload"  accept=".png, .jpg, .jpeg" /><label class="onetarek-upload-button" for="imageUpload"><span style="margin: 7px 7px;" class="dashicons dashicons-edit"></span></label></div><div class="avatar-preview"><div id="imagePreview" style="background-image: url('. wp_get_attachment_url($url) .');"><label class="onetarek-logo-delete-button" for="imageUpload"><span style="margin: 7px 7px;" class="dashicons dashicons-trash"></span></label></div></div></div></div>';
    }

    public function wpsp_logo_height() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_logo_height' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-logo-height' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-logo-height]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Logo Height</label></div>';
    }

    public function wpsp_logo_width() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option('_logo_width');
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-logo-width' );


        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-logo-width]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Logo Width</label></div>';
    }

    public function wpsp_title_field() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_title_field' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-title-field' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-title-field]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">TITLE</label></div>';
    }

    public function wpsp_password() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_pin' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-password' );
        
        if (empty($value)) {
            echo '<div class="field-container">
            <input class="field-input" value="" name="wp-secure-settings_options[wpsp-password]" type="text" placeholder=" ">
            <label class="field-placeholder" for="inputName">Password</label>
            </div>';
        } else {
            echo '<button type="button" class="button wp-generate-pw hide-if-no-js pass-field-toggle" aria-expanded="true">Set New Password</button>
            <div class="field-container pass-field-toggle-wrap" style="display: none;">
            <input class="field-input" value="" name="wp-secure-settings_options[wpsp-password]" type="text" placeholder=" ">
            <label class="field-placeholder" for="inputName">Password</label>
           </div>';
        } 
    }

    public function wpsp_description() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_description' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-description' );
        echo wp_editor( $value,'description_editor', array(
            'textarea_rows' => 10,
            'textarea_name' => 'wp-secure-settings_options[wpsp-description]',
            'tinymce'       => array(
                'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                'toolbar2'      => '',
                'toolbar3'      => ''
            )
            
        ));
    }
    public function wpsp_label_submit_button() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_submit_label' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-label-submit-button' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-label-submit-button]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Submit button label</label></div>';
    }

    public function wpsp_placeholder_text() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_pin_placeholder' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-placeholder-text' );
       
        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-placeholder-text]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Password field placeholder</label></div>';
    }

    public function wpsp_error_message() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_try_again_error' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-error-message' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-error-message]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Error Message</label></div>';
    }

    public function wpsp_background_color() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_crb_background' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-background-color' );

        echo '<input class="wpsp-color-picker" name="wp-secure-settings_options[wpsp-background-color]" type="color" value="'.$value.'">';
    }


    public function wpsp_custom_css() {
        $options = get_option( 'wp-secure-settings_options' );
        $value = ( isset( $options['wpsp-custom-css'] ) ) ? $options['wpsp-custom-css'] : '';
        
        echo '<textarea id="wpsp_custom_css" name="wp-secure-settings_options[wpsp-custom-css]" rows="10"  placeholder="Additional CSS">'.$value.'</textarea>';
    }

    private function wpsp_get_option( $new_value, $old_value, $field_name ) {
        if( isset( $old_value ) && !isset( $new_value[$field_name] ) ) {
            return $old_value;
        } else {
            return $new_value[$field_name];
        }
    }

}


$instance = new WPSP_Settings();


