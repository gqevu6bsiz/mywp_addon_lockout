<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpThirdpartyAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpLockoutThirdpartyModuleWPSHideLogin' ) ) :

final class MywpLockoutThirdpartyModuleWPSHideLogin extends MywpThirdpartyAbstractModule {

  protected static $id = 'wps-hide-login';

  protected static $base_name = 'wps-hide-login/wps-hide-login.php';

  protected static $name = 'WPS Hide Login';

  protected static function after_init() {

    add_action( 'wp_loaded' , array( __CLASS__ , 'wp_loaded' ) , 9 );

  }

  public static function current_pre_plugin_activate( $is_plugin_activate ) {

    if( class_exists( 'WPS\WPS_Hide_Login\Plugin' ) ) {

      return true;

    }

    return $is_plugin_activate;

  }

  public static function wp_loaded() {

    global $pagenow;

    if( $pagenow !== 'wp-login.php' ) {

      return false;

    }

    if( ! MywpThirdparty::is_plugin_activate( self::$base_name ) ) {

      return false;

    }

    remove_action( 'mywp_wp_loaded' , array( 'MywpControllerModuleLockout' , 'mywp_wp_loaded' ) , 1000 );

    MywpControllerModuleLockout::mywp_wp_loaded();

  }

}

MywpLockoutThirdpartyModuleWPSHideLogin::init();

endif;
