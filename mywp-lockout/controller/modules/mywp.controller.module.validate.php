<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpControllerAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpControllerModuleLockoutValidate' ) ) :

final class MywpControllerModuleLockoutValidate extends MywpControllerAbstractModule {

  static protected $id = 'lockout_validate';

  protected static function after_init() {

    add_filter( 'mywp_controller_pre_get_model_' . self::$id , array( __CLASS__ , 'mywp_controller_pre_get_model' ) );

  }

  public static function mywp_controller_pre_get_model( $pre_model ) {

    $pre_model = true;

    return $pre_model;

  }

  public static function mywp_wp_loaded() {

    if( ! self::is_do_controller() ) {

      return false;

    }

    add_action( 'user_profile_update_errors' , array( __CLASS__ , 'user_profile_update_errors' ) , 10 , 3 );

    add_action( 'validate_password_reset' , array( __CLASS__ , 'validate_password_reset' ) , 10 , 2 );

  }

  private static function is_check_week_password() {

    $setting_data = MywpLockoutApi::get_lockout_setting_data();

    if( empty( $setting_data['week_password_validate'] ) ) {

      return false;

    }

    return true;

  }

  public static function user_profile_update_errors( $errors , $update , $user ) {

    if( ! self::is_check_week_password() ) {

      return $errors;

    }

    $password = false;

    if( ! empty( $user->user_pass ) ) {

      $password = $user->user_pass;

    } elseif( ! empty( $_POST['pass1'] ) ) {

      $password = $_POST['pass1'];

    }

    if( empty( $password ) ) {

      return $errors;

    }

    if( MywpLockoutApi::is_weak_password( $password , $user->user_login ) ) {

      $errors->add( 'weak_password' , __( '<strong>ERROR</strong>: This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) , array( 'form-field' => 'pass1' ) );

    }

    return $errors;

  }

  public static function validate_password_reset( $errors , $user ) {

    if( empty( $_POST['wp-submit'] ) ) {

      return $errors;

    }

    if( empty( $_POST['rp_key'] ) ) {

      return $errors;

    }

    if( ! self::is_check_week_password() ) {

      return $errors;

    }

    $password = false;

    if( ! empty( $_POST['pass1'] ) ) {

      $password = $_POST['pass1'];

    }

    if( empty( $password ) ) {

      return $errors;

    }

    if( MywpLockoutApi::is_weak_password( $password , $user->user_login ) ) {

      $errors->add( 'weak_password' , __( '<strong>ERROR</strong>: This password is weak. Please enter a stronger password.' , 'mywp-lockout' ) , array( 'form-field' => 'pass1' ) );

    }

    return $errors;

  }

}

MywpControllerModuleLockoutValidate::init();

endif;
