<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpControllerAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpControllerModuleLockoutFrontendComment' ) ) :

final class MywpControllerModuleLockoutFrontendComment extends MywpControllerAbstractModule {

  static protected $id = 'lockout_frontend_comment';

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

    add_action( 'mywp_request_frontend' , array( __CLASS__ , 'mywp_request_frontend' ) );

  }

  public static function mywp_request_frontend() {

    if( is_user_logged_in() ) {

      return false;

    }

    $setting_data = MywpLockoutApi::get_lockout_setting_data();

    if( empty( $setting_data['comment_validate_lockout'] ) ) {

      return false;

    }

    add_filter( 'pre_comment_approved' , array( __CLASS__ , 'pre_comment_approved' ) , 10 , 2 );

    add_filter( 'comment_form_default_fields' , array( __CLASS__ , 'comment_form_default_fields' ) );

  }

  private static function get_require_field_key() {

    $field_key = MywpLockoutForm::get_field_key( 'comment_require_field_key' );

    if( empty( $field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$field_key' , $called_text );

      return false;

    }

    return $field_key;

  }

  private static function get_dummy_field_key() {

    $field_key = MywpLockoutForm::get_field_key( 'comment_dummy_field_key' );

    if( empty( $field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$field_key' , $called_text );

      return false;

    }

    return $field_key;

  }

  public static function pre_comment_approved( $approved , $comment_data ) {

    if( is_user_logged_in() ) {

      return $approved;

    }

    if( is_wp_error( $approved ) ) {

      return $approved;

    }

    $require_field_key = self::get_require_field_key();

    if( empty( $require_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$require_field_key' , $called_text );

      return $approved;

    }

    $dummy_field_key = self::get_dummy_field_key();

    if( empty( $dummy_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$dummy_field_key' , $called_text );

      return $approved;

    }

    $WP_Error = new WP_Error();

    if( ! isset( $_POST[ $require_field_key ] ) ) {

      $WP_Error->add( 'mywp_lockout_frontend_comment_validate_require_field' , __( 'There are no required fields. Please submit your comments using the official comment form.' , 'mywp-lockout') , 403 );

      return $WP_Error;

    }

    if( ! MywpLockoutForm::is_validate_check_nonce( $_POST[ $require_field_key ] , $require_field_key ) ){

      $WP_Error->add( 'mywp_lockout_frontend_comment_validate_require_field' , __( 'There are no required fields. Please submit your comments using the official comment form.' , 'mywp-lockout') , 403 );

      return $WP_Error;

    }

    if( ! isset( $_POST[ $dummy_field_key ] ) ) {

      $WP_Error->add( 'mywp_lockout_frontend_comment_validate_require_field' , __( 'There are no required fields. Please submit your comments using the official comment form.' , 'mywp-lockout') , 403 );

      return $WP_Error;

    }

    if( empty( $_POST[ $dummy_field_key ] ) ) {

      return $approved;

    }

    $WP_Error->add( 'mywp_lockout_frontend_comment_validate_dummy_field' , __( 'Do not enter anything into the dummy field.' , 'mywp-lockout') , 403 );

    $args = array( 'reason' => 'Dummy field input on Comment form' );

    MywpControllerModuleLockout::set_lockout_remote_data( $args );

    MywpControllerModuleLockout::do_lockedout();

    return $WP_Error;

  }

  public static function comment_form_default_fields( $fields ) {

    if( is_user_logged_in() ) {

      return $fields;

    }

    $require_field_key = self::get_require_field_key();

    if( empty( $require_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$require_field_key' , $called_text );

      return $fields;

    }

    $dummy_field_key = self::get_dummy_field_key();

    if( empty( $dummy_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$dummy_field_key' , $called_text );

      return $fields;

    }

    $html = '';

    $html = sprintf( '<p class="comment-form-%s" style="display: none;">' , esc_attr( $require_field_key ) );

    $html .= sprintf( '<label for="%s">%s</label>' , esc_html( $require_field_key ) , esc_html( __( 'Require field' , 'mywp-lockout' ) ) );

    $html .= sprintf( '<input id="%1$s" name="%1$s" type="text" />' , esc_attr( $require_field_key ) );

    $html .= '</p>';

    $fields['mywp_lockout_comment_require_field'] = $html;

    $html = sprintf( '<p class="comment-form-%s" style="display: none;">' , esc_attr( $dummy_field_key ) );

    $html .= sprintf( '<label for="%s">%s</label>' , esc_html( $dummy_field_key ) , esc_html( __( 'Dummy field' , 'mywp-lockout' ) ) );

    $html .= sprintf( '<input id="%1$s" name="%1$s" type="text" />' , esc_attr( $dummy_field_key ) );

    $html .= '</p>';

    $html .= MywpLockoutForm::get_require_field_script( $require_field_key );

    $fields['mywp_lockout_comment_dummy_field'] = $html;

    return $fields;

  }

}

MywpControllerModuleLockoutFrontendComment::init();

endif;
