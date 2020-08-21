<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpThirdpartyAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpLockoutThirdpartyModuleWooCommerce' ) ) :

final class MywpLockoutThirdpartyModuleWooCommerce extends MywpThirdpartyAbstractModule {

  protected static $id = 'woocommerce';

  protected static $base_name = 'woocommerce/woocommerce.php';

  protected static $name = 'WooCommerce';

  protected static function after_init() {

    add_action( 'wp_loaded' , array( __CLASS__ , 'wp_loaded' ) );

  }

  public static function wp_loaded() {

    if( ! MywpThirdparty::is_plugin_activate( self::$base_name ) ) {

      return false;

    }

    add_filter( 'woocommerce_process_registration_errors' , array( __CLASS__ , 'woocommerce_process_registration_errors' ) , 10 , 4 );

    add_action( 'validate_password_reset' , array( __CLASS__ , 'validate_password_reset' ) );

  }

  public static function woocommerce_process_registration_errors( $validation_error , $username , $password , $email ) {

    if( MywpLockoutApi::is_weak_password( $password ) ) {

      $validation_error->add( 'weak_password' , __( 'This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) );

    }

    return $validation_error;

  }

  public static function validate_password_reset( $errors ) {

    if( empty( $_REQUEST['woocommerce-reset-password-nonce'] ) ) {

      return $errors;

    }

    $password = false;

    if( ! empty( $_POST['password_1'] ) ) {

      $password = $_POST['password_1'];

    }

    if( empty( $password ) ) {

      return $errors;

    }

    if( MywpLockoutApi::is_weak_password( $password ) ) {

      $errors->add( 'weak_password' , __( 'This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) );

    }

    return $errors;

  }

}

MywpLockoutThirdpartyModuleWooCommerce::init();

endif;
