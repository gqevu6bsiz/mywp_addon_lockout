<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpControllerAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpControllerModuleLockoutFrontendArchive' ) ) :

final class MywpControllerModuleLockoutFrontendArchive extends MywpControllerAbstractModule {

  static protected $id = 'lockout_frontend_archive';

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

    add_action( 'mywp_controller_after_do_frontend_author_archive_disable_archive' , array( __CLASS__ , 'mywp_controller_after_do_frontend_author_archive_disable_archive' ) );

    add_action( 'mywp_controller_after_do_frontend_taxonomy_archive_disable_archive' , array( __CLASS__ , 'mywp_controller_after_do_frontend_taxonomy_archive_disable_archive' ) );

    add_action( 'mywp_controller_after_do_frontend_date_archive_disable_archive' , array( __CLASS__ , 'mywp_controller_after_do_frontend_date_archive_disable_archive' ) );

  }

  private static function do_lockedout( $args ) {

    MywpControllerModuleLockout::set_lockout_remote_data( $args );

    MywpControllerModuleLockout::do_lockedout();

  }

  public static function mywp_controller_after_do_frontend_author_archive_disable_archive() {

    if( ! self::is_do_function( __FUNCTION__ ) ) {

      return false;

    }

    $args = array( 'reason' => 'Disable Author Archive' );

    self::do_lockedout( $args );

  }

  public static function mywp_controller_after_do_frontend_taxonomy_archive_disable_archive() {

    if( ! self::is_do_function( __FUNCTION__ ) ) {

      return false;

    }

    $args = array( 'reason' => 'Disable Taxonomy Archive' );

    self::do_lockedout( $args );

  }

  public static function mywp_controller_after_do_frontend_date_archive_disable_archive() {

    if( ! self::is_do_function( __FUNCTION__ ) ) {

      return false;

    }

    $args = array( 'reason' => 'Disable Date Archive' );

    self::do_lockedout( $args );

  }

}

MywpControllerModuleLockoutFrontendArchive::init();

endif;
