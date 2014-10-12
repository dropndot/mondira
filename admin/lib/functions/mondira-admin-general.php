<?php
/*
* @return boolean true if it is option or settings page of mondira
*/
if (!function_exists('is_on_options_interface')) {
    function is_on_options_interface() {
        if ('themes.php' == basename($_SERVER['PHP_SELF']) || 'mondira_settings' == $_GET['page']) {
            return true;
        }
        return false;
    }
}

/*
* Check if WordPress version installed as specified by parameters
* @return bool
* @since version 1.0.0
*/
if ( ! function_exists( 'is_version' ) ) {
    function is_version( $version = '3.9' ) {
        global $wp_version;
        if ( version_compare( $wp_version, $version, '>=' ) ) {
            return true;
        }
        return false;
    }
}

