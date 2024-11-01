jQuery(document).ready(function() {
    jQuery('.onetarek-background-delete-button').click(function(){
        jQuery('.wp-secure-settings-background_image').val('');
        jQuery('#bgimagePreview').css('background-image', 'none');
    });
    jQuery('.pass-field-toggle').click(function(){  
        jQuery('.pass-field-toggle-wrap').toggle('fast');
    });
    jQuery('.onetarek-logo-delete-button').click(function(){
        jQuery('.wp-secure-settings-logo_image').val('');
        jQuery('#imagePreview').css('background-image', 'none');
    });

    jQuery("#imageUpload").change(function() {
        wpsp_read_fike_url(this);
    });

    jQuery("input.wpsp-color-picker").change(function() {
        var $color = jQuery(this).val();
        jQuery("input:checked + .slider").css("background-color", $color);
    });

    if(jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').is(':checked')) {
        var disabled = false;
        wpsp_wp_media_upload();
    } 
    else {
        var disabled = true;
    }
    wpsp_wp_media_upload();
    wpsp_disable_fields(disabled);

    jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').change(function() {
        if(jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').is(':checked')) {
            wpsp_wp_media_upload();
            var disabled = false;
            wpsp_disable_fields(disabled);
        } else {
            var disabled = true;
            wpsp_disable_fields(disabled);
            jQuery(this).css("background-color", "#cccccc!important");
        }
    });
});

function wpsp_disable_fields(disabled) {
    // jQuery("input.field-input").attr("readonly", disabled);
    // jQuery("input.wpsp-color-picker").attr("disabled", disabled);
    // jQuery("textarea#wpsp_custom_css").attr("readonly", disabled);
}


function wpsp_wp_media_upload() {
    var file_frame;
    jQuery('label.onetarek-upload-button').on('click', function( event ){
        event.preventDefault();
    
        var that = jQuery(this);
    
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'WP Secure Maintenance Logo',
          button: {
            text: 'Upload',
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });
        file_frame.open();
    
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
    
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();
            jQuery("#imagePreview").css("background-image", "url(" + attachment.url + ")");
            jQuery("input[name='wp-secure-settings_options[wpsp-logo]']").val( attachment.id );
            // jQuery('<input class="button delete-img remove" type="submit" name="remove_bg" value="x" />');

          
        });
    
        // Finally, open the modal
        file_frame.open();
    });


    var file_frames;
    jQuery('label.onetarek-background-upload-button').on('click', function( events ){
        events.preventDefault();
    
        var thats = jQuery(this);
    
        // Create the media frame.
        file_frames = wp.media.frames.file_frame = wp.media({
          title: 'WP Secure Maintenance Background Image',
          button: {
            text: 'Upload',
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });
        file_frames.open();
    
        // When an image is selected, run a callback.
        file_frames.on( 'select', function() {
    
          // We set multiple to false so only get one image from the uploader
          attachments = file_frames.state().get('selection').first().toJSON();
            jQuery("#bgimagePreview").css("background-image", "url(" + attachments.url + ")");
            jQuery("input[name='wp-secure-settings_options[wpsp-background-image]']").val( attachments.id );
            // jQuery('<input class="button delete-img remove" type="submit" name="remove_bg" value="x" />');

            // console.log(attachments);
          
        });
    
        // Finally, open the modal
        file_frames.open();
    });
}
 

function wpsp_read_fike_url(input) {
    console.log(input);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            jQuery('#imagePreview').css('background-image', 'url('+e.target.result +')');
            jQuery('#imagePreview').hide();
            jQuery('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
