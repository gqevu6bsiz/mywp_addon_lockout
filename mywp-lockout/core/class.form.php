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

    $random_num = rand( 8 , 12 );

    $random_key = substr( $str_shuffle , 0 , $random_num );

    return $random_key;

  }

  public static function get_field_key( $field_key_name = false ) {

    if( empty( $field_key_name ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$field_key_name' , $called_text );

      return false;

    }

    if( ! is_string( $field_key_name ) ) {

      return false;

    }

    $field_key_name = strip_tags( $field_key_name );

    $MywpTransient = new MywpTransient( 'lockout_' . $field_key_name , 'other' );

    $transient_data = $MywpTransient->get_data();

    if( ! empty( $transient_data ) ) {

      return $transient_data;

    }

    $random_key = self::get_random_key();

    $MywpTransient->update_data( $random_key , 24 * HOUR_IN_SECONDS );

    return $random_key;

  }

  public static function get_require_field_script( $require_field_key = false ) {

    if( empty( $require_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$require_field_key' , $called_text );

      return false;

    }

    if( ! is_string( $require_field_key ) ) {

      return false;

    }

    $require_field_key = strip_tags( $require_field_key );

    $require_field_value = wp_create_nonce( self::$nonce_field_base_name . $require_field_key );

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

    if( empty( $require_field_key ) ) {

      return false;

    }

    if( empty( $require_field_key ) ) {

      return false;

    }

    return wp_verify_nonce( $require_field_value , self::$nonce_field_base_name . $require_field_key );

  }

}

endif;
