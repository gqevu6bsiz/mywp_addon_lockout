<?php
/*
Plugin Name: My WP Add-on Lockout
Plugin URI: https://mywpcustomize.com/add_ons/my-wp-add-on-lockout/
Description: My WP Add-on Lockout is blocks to specific requests and weak login passwords.
Version: 1.11.0
Author: gqevu6bsiz
Author URI: https://mywpcustomize.com
Text Domain: mywp-lockout
Domain Path: /languages
My WP Test working: 1.24
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockout' ) ) :

final class MywpLockout {

  public static function init() {

    self::define_constants();
    self::include_core();

    add_action( 'mywp_start' , array( __CLASS__ , 'mywp_start' ) );

  }

  private static function define_constants() {

    define( 'MYWP_LOCKOUT_NAME' , 'My WP Add-On Lockout' );
    define( 'MYWP_LOCKOUT_VERSION' , '1.11.0' );
    define( 'MYWP_LOCKOUT_PLUGIN_FILE' , __FILE__ );
    define( 'MYWP_LOCKOUT_PLUGIN_BASENAME' , plugin_basename( MYWP_LOCKOUT_PLUGIN_FILE ) );
    define( 'MYWP_LOCKOUT_PLUGIN_DIRNAME' , dirname( MYWP_LOCKOUT_PLUGIN_BASENAME ) );
    define( 'MYWP_LOCKOUT_PLUGIN_PATH' , plugin_dir_path( MYWP_LOCKOUT_PLUGIN_FILE ) );
    define( 'MYWP_LOCKOUT_PLUGIN_URL' , plugin_dir_url( MYWP_LOCKOUT_PLUGIN_FILE ) );

  }

  private static function include_core() {

    $dir = MYWP_LOCKOUT_PLUGIN_PATH . 'core/';

    require_once( $dir . 'class.api.php' );
    require_once( $dir . 'class.list.php' );
    require_once( $dir . 'class.form.php' );

  }

  public static function mywp_start() {

    add_action( 'mywp_plugins_loaded', array( __CLASS__ , 'mywp_plugins_loaded' ) );

    add_action( 'init' , array( __CLASS__ , 'wp_init' ) );

  }

  public static function mywp_plugins_loaded() {

    add_filter( 'mywp_controller_plugins_loaded_include_modules' , array( __CLASS__ , 'mywp_controller_plugins_loaded_include_modules' ) );

    add_filter( 'mywp_setting_plugins_loaded_include_modules' , array( __CLASS__ , 'mywp_setting_plugins_loaded_include_modules' ) );

    add_filter( 'mywp_thirdparty_plugins_loaded_include_modules' , array( __CLASS__ , 'mywp_thirdparty_plugins_loaded_include_modules' ) );

  }

  public static function wp_init() {

    load_plugin_textdomain( 'mywp-lockout' , false , MYWP_LOCKOUT_PLUGIN_DIRNAME . '/languages' );

  }

  public static function mywp_controller_plugins_loaded_include_modules( $includes ) {

    $dir = MYWP_LOCKOUT_PLUGIN_PATH . 'controller/modules/';

    $includes['lockout_frontend_archive'] = $dir . 'mywp.controller.module.frontend-archive.php';
    $includes['lockout_frontend_comment'] = $dir . 'mywp.controller.module.frontend-comment.php';
    $includes['lockout_main_general']     = $dir . 'mywp.controller.module.main.general.php';
    $includes['lockout_setting']          = $dir . 'mywp.controller.module.lockout.php';
    $includes['lockout_validate']         = $dir . 'mywp.controller.module.validate.php';
    $includes['lockout_updater']          = $dir . 'mywp.controller.module.updater.php';

    return $includes;

  }

  public static function mywp_setting_plugins_loaded_include_modules( $includes ) {

    $dir = MYWP_LOCKOUT_PLUGIN_PATH . 'setting/modules/';

    $includes['lockout_setting'] = $dir . 'mywp.setting.lockout.php';

    return $includes;

  }

  public static function mywp_thirdparty_plugins_loaded_include_modules( $includes ) {

    $dir = MYWP_LOCKOUT_PLUGIN_PATH . 'thirdparty/modules/';

    $includes['lockout_wps_hide_login'] = $dir . 'wps-hide-login.php';
    $includes['lockout_woocommerce']    = $dir . 'woocommerce.php';
    $includes['lockout_contact_form_7'] = $dir . 'contact-form-7.php';

    return $includes;

  }

}

MywpLockout::init();

endif;
