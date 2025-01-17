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

  public static function mywp_init() {

    add_action( 'mywp_wp_loaded' , array( __CLASS__ , 'mywp_wp_loaded' ) );

  }

  public static function mywp_wp_loaded() {

    add_filter( 'mywp_lockout_get_login' , array( __CLASS__ , 'mywp_lockout_get_login' ) );

    add_filter( 'woocommerce_process_registration_errors' , array( __CLASS__ , 'woocommerce_process_registration_errors' ) , 10 , 4 );

    add_filter( 'woocommerce_save_account_details_errors' , array( __CLASS__ , 'woocommerce_save_account_details_errors' ) , 10 , 2 );

    add_action( 'validate_password_reset' , array( __CLASS__ , 'validate_password_reset' ) );

  }

  private static function is_check_week_password() {

    $setting_data = MywpLockoutApi::get_lockout_setting_data();

    if( empty( $setting_data['week_password_validate'] ) ) {

      return false;

    }

    return true;

  }

  public static function mywp_lockout_get_login( $login ) {

    if( empty( $_POST ) ) {

      return $login;

    }

    if( ! empty( $_POST['woocommerce-login-nonce'] ) ) {

      if( ! empty( $_POST['username'] ) ) {

        $login['name'] = $_POST['username'];

      }

      if( ! empty( $_POST['password'] ) ) {

        $login['password'] = $_POST['password'];

      }

    }

    return $login;

  }

  public static function woocommerce_process_registration_errors( $validation_error , $username , $password , $email ) {

    if( ! self::is_check_week_password() ) {

      return $validation_error;

    }

    if( empty( $username ) ) {

      $username = wc_create_new_customer_username( $email );

    }

    if( MywpLockoutApi::is_weak_password( $password , $username ) ) {

      $validation_error->add( 'weak_password' , __( 'This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) );

    }

    return $validation_error;

  }

  public static function woocommerce_save_account_details_errors( $errors , $temp_user ) {

    if( ! self::is_check_week_password() ) {

      return $errors;

    }

    $user = get_user_by( 'id' , $temp_user->ID );

    if( MywpLockoutApi::is_weak_password( $temp_user->user_pass , $user->user_login ) ) {

      $errors->add( 'weak_password' , __( 'This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) );

    }

    return $errors;

  }

  public static function validate_password_reset( $errors ) {

    if( ! self::is_check_week_password() ) {

      return $errors;

    }

    if( empty( $_POST['woocommerce-reset-password-nonce'] ) ) {

      return $errors;

    }

    $password = false;

    if( ! empty( $_POST['password_1'] ) ) {

      $password = $_POST['password_1'];

    }

    if( empty( $password ) ) {

      return $errors;

    }

    $user_name = false;

    if( ! empty( $_POST['reset_login'] ) ) {

      $user_name = $_POST['reset_login'];

    }

    if( MywpLockoutApi::is_weak_password( $password , $user_name ) ) {

      $errors->add( 'weak_password' , __( 'This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) );

    }

    return $errors;

  }

}

MywpLockoutThirdpartyModuleWooCommerce::init();

endif;
