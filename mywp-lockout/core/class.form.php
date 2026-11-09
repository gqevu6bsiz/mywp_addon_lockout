<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutForm' ) ) :

final class MywpLockoutForm {

  private static $nonce_field_base_name = 'mywp_lockout_require_field_';

  public static function get_random_key() {

    $str = 'abcdefghijklmnopqrstuvwxyz0123456789';

    $str_shuffle = str_shuffle( $str );

    $random_num = wp_rand( 8 , 12 );

    $random_key = substr( $str_shuffle , 0 , $random_num );

    return $random_key;

  }

  public static function get_field_key( $field_key_name = false ) {

    $called_text = sprintf( '%s::%s( $field_key_name )' , __CLASS__ , __FUNCTION__ );

    $field_key_name = MywpHelper::sanitize_text( $field_key_name );

    if( empty( $field_key_name ) ) {

      MywpHelper::error_require_message( '$field_key_name' , $called_text );

      return false;

    }

    $MywpTransient = new MywpTransient( 'lockout_' . $field_key_name , 'other' );

    $transient_data = $MywpTransient->get_data();

    if( ! empty( $transient_data ) ) {

      return $transient_data;

    }

    $random_key = self::get_random_key();

    $MywpTransient->update_data( $random_key , 2 * HOUR_IN_SECONDS );

    return $random_key;

  }

  public static function get_require_field_script( $require_field_key = false ) {

    $called_text = sprintf( '%s::%s( $require_field_key )' , __CLASS__ , __FUNCTION__ );

    $require_field_key = MywpHelper::sanitize_text( $require_field_key );

    if( empty( $require_field_key ) ) {

      MywpHelper::error_require_message( '$require_field_key' , $called_text );

      return false;

    }

    $nonce_key = self::$nonce_field_base_name . $require_field_key;

    $require_field_value = wp_create_nonce( $nonce_key );

    $require_field_script = '
    <script>
    window.addEventListener("load", function() {

      window.setTimeout( function() {
        document.getElementsByName( "' . esc_js( $require_field_key ) . '" )[0].value = "' . esc_js( $require_field_value ) . '";
      }, 3000 );

    });
    </script>
    ';

    $require_field_script = str_replace( ' ' , '' , $require_field_script );

    $require_field_script = str_replace( "\n" , '' , $require_field_script );

    return $require_field_script;

  }

  public static function is_validate_check_nonce( $require_field_value = false , $require_field_key = false ) {

    $called_text = sprintf( '%s::%s( $require_field_value , $require_field_key )' , __CLASS__ , __FUNCTION__ );

    $require_field_value = MywpHelper::sanitize_text( $require_field_value );

    if( empty( $require_field_value ) ) {

      return false;

    }

    $require_field_key = MywpHelper::sanitize_text( $require_field_key );

    if( empty( $require_field_key ) ) {

      return false;

    }

    return wp_verify_nonce( $require_field_value , self::$nonce_field_base_name . $require_field_key );

  }

}

endif;
