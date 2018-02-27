<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpControllerAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpControllerModuleLockoutAuthorArchive' ) ) :

final class MywpControllerModuleLockoutAuthorArchive extends MywpControllerAbstractModule {

  static protected $id = 'lockout_author_archive';

  static protected $is_do_controller = true;

  protected static function after_init() {

    add_filter( 'mywp_controller_model_' . self::$id , array( __CLASS__ , 'mywp_controller_model' ) );

  }

  public static function mywp_controller_model( $pre_model ) {

    $pre_model = true;

    return $pre_model;

  }

  public static function mywp_wp_loaded() {

    if( is_admin() ) {

      return false;

    }

    if( ! self::is_do_controller() ) {

      return false;

    }

    add_action( 'pre_get_posts' , array( __CLASS__ , 'lockout_author_archive' ) , 9 );

  }

  public static function lockout_author_archive( $wp_query ) {

    if( ! self::is_do_function( __FUNCTION__ ) ) {

      return $wp_query;

    }

    if( empty( $wp_query->is_author ) ) {

      return $wp_query;

    }

    $controller = MywpController::get_controller( 'frontend_author_archive' );

    if( empty( $controller['model'] ) ) {

      return $wp_query;

    }

    $mywp_model = $controller['model'];

    $setting_data = $mywp_model->get_setting_data();

    if( empty( $setting_data ) ) {

      return $wp_query;

    }

    MywpControllerModuleLockout::set_lockout_remote_data( array( 'reason' => 'Disable Author Archive' ) );

    MywpControllerModuleLockout::do_lockedout();

  }

}

MywpControllerModuleLockoutAuthorArchive::init();

endif;
