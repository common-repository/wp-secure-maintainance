<div class="wrap">
    <h1>WP Secure Maintenance</h1>
    <form action="options.php" method="post" enctype=”multipart/form-data”> 
    <h2 class="nav-tab-wrapper">
    <?php
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
    ?>
        <a href="?page=wpsp-settings" class="nav-tab <?php echo ($tab == '') ? 'nav-tab-active' : '' ?> "><?php echo __('General Settings'); ?></a>
        <a href="?page=wpsp-settings&tab=settings" class="nav-tab <?php echo ($tab == 'settings') ? 'nav-tab-active' : '' ?> "><?php echo __('Display Settings'); ?></a>
        <a href="?page=wpsp-settings&tab=custom_css" class="nav-tab <?php echo ($tab == 'custom_css') ? 'nav-tab-active' : '' ?> "><?php echo __('Custom CSS'); ?></a>
    </h2>
    <?php
        if( isset( $_GET[ 'tab' ] ) ) {
            $active = sanitize_text_field($_GET[ 'tab' ]);
            if( $active === 'custom_css' ) { ?>
                <div class="import-container">
                    <?php
                        settings_fields( 'wp-secure-settings_options_group' );
                        do_settings_sections( 'wpsp-settings&tab=custom_css','wpsp-css' );
                        submit_button();
                    ?>
                </div>
            <?php 
            } else if( $active === 'settings' ) {
                settings_fields( 'wp-secure-settings_options_group' );
                do_settings_sections( 'wpsp-settings&tab=settings','wpsp-suttings' );
                submit_button();
            }
        } else { ?>
            <div class="export-container">
                <?php
                    settings_fields( 'wp-secure-settings_options_group' );
                    do_settings_sections( 'wpsp-settings','wpsp' ); 
                    submit_button();
                ?>
            </div>
        <?php }
        
    
        // do_settings_sections( 'wpsp-settings', 'wpsp' );
        // settings_fields( 'wp-secure-settings_options_group' );

        // submit_button();
?>
    </form>
    </div>

