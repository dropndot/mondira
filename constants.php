<?php
/**
* Initial contstants for mondira
*
* @since version 1.0
* @last modified 28 Feb, 2015
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
*/
    define('CHILD_THEME_DIR', get_stylesheet_directory());

    define('THEME_OPTIONS', THEME_DIR . '/options');
    define('CHILD_THEME_OPTIONS', CHILD_THEME_DIR . '/options');

    define('THEME_DOCS', THEME_DIR . '/docs');
    define('CHILD_THEME_DOCS', CHILD_THEME_DIR . '/docs');

    define('FRAMEWORK_GENERAL_FUNCTIONS', THEME_FRAMEWORK . '/functions'); //Used
    define('FRAMEWORK_ADMIN', THEME_FRAMEWORK . '/admin');
    define('FRAMEWORK_ADMIN_URI', THEME_FRAMEWORK_URI . '/admin');
    define('FRAMEWORK_ADMIN_HELPERS', FRAMEWORK_ADMIN . '/helpers');
    define('FRAMEWORK_ADMIN_GENERAL_CORE', FRAMEWORK_ADMIN . '/lib/core');
    define('FRAMEWORK_ADMIN_GENERAL_FUNCTIONS', FRAMEWORK_ADMIN . '/lib/functions');
    define('FRAMEWORK_ADMIN_RESOURCES', FRAMEWORK_ADMIN . '/resources');
    define('FRAMEWORK_ADMIN_RESOURCES_URI', FRAMEWORK_ADMIN_URI . '/resources');
    define('FRAMEWORK_ADMIN_CSS', FRAMEWORK_ADMIN_RESOURCES_URI . '/css');
    define('FRAMEWORK_ADMIN_JS', FRAMEWORK_ADMIN_RESOURCES_URI . '/js');
    define('FRAMEWORK_ADMIN_IMAGES', FRAMEWORK_ADMIN_RESOURCES_URI . '/images');