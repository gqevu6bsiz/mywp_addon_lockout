<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpControllerAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpControllerModuleLockout' ) ) :

final class MywpControllerModuleLockout extends MywpControllerAbstractModule {

  static protected $id = 'lockout';

  static protected $network = true;

  private static $current_date = array();

  private static $remote_ip = false;

  private static $lockout_remote_data = array();

  private static $input_fields = array();

  private static $lockout_reason = false;

  public static function mywp_controller_initial_data( $initial_data ) {

    $initial_data['expire_timeout'] = '';
    $initial_data['specific_get_lockout'] = '';
    $initial_data['specific_post_lockout'] = '';
    $initial_data['specific_file_lockout'] = '';
    $initial_data['unknown_plugin_theme_lockout'] = '';

    $initial_data['send_mail'] = '';
    $initial_data['send_to_email'] = '';
    $initial_data['send_email_with_input'] = '';
    $initial_data['send_email_with_server'] = '';

    $initial_data['lockout_page'] = '';

    return $initial_data;

  }

  public static function mywp_controller_default_data( $default_data ) {

    $default_data['expire_timeout'] = '10';
    $default_data['specific_get_lockout'] = false;
    $default_data['specific_post_lockout'] = false;
    $default_data['specific_file_lockout'] = false;
    $default_data['unknown_plugin_theme_lockout'] = false;

    $default_data['send_mail'] = false;
    $default_data['send_to_email'] = '';
    $default_data['send_email_with_input'] = '';
    $default_data['send_email_with_server'] = '';

    $default_data['lockout_page'] = MywpLockoutApi::get_lockout_default_page();

    return $default_data;

  }

  public static function mywp_wp_loaded() {

    if( ! self::is_do_controller() ) {

      return false;

    }

    $is_whitelist = apply_filters( 'mywp_lockout_is_whitelist' , false );

    if( $is_whitelist ) {

      return false;

    }

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_logged_in' ) , 5 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_exist_lockout' ) , 20 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_login_lockout' ) , 30 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_get_data_lockout' ) , 40 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_post_data_lockout' ) , 50 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_file_data_lockout' ) , 60 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_unknown_plugin_theme_lockout' ) , 70 );

    add_action( 'mywp_lockout_do_lockout' , array( __CLASS__ , 'lockout_send_email' ) , 20 );

    self::lockout();

  }

  private static function lockout() {

    self::$current_date = array( 'date' => current_time( 'mysql' ) , 'timestamp' => current_time( 'timestamp' ) );

    self::$remote_ip = MywpLockoutApi::get_remote_ip();

    $is_lockout = apply_filters( 'mywp_lockout_is_lockout' , false );

    if( $is_lockout ) {

      self::do_lockedout();

    }

    do_action( 'mywp_lockout_through_lockout' );

  }

  public static function do_lockedout() {

    do_action( 'mywp_lockout_do_lockout' );

    add_filter( 'mywp_do_lockout_page' , 'wptexturize' );
    add_filter( 'mywp_do_lockout_page' , 'convert_smilies' , 20 );
    add_filter( 'mywp_do_lockout_page' , 'wpautop' );
    add_filter( 'mywp_do_lockout_page' , 'shortcode_unautop' );
    add_filter( 'mywp_do_lockout_page' , 'prepend_attachment' );

    $lockout_header = 'HTTP/1.1 403 Forbidden';

    $lockout_header = apply_filters( 'mywp_do_lockout_page_header' , $lockout_header );

    if( ! empty( $lockout_header ) ) {

      header( $lockout_header );

    }

    $lockout_page = apply_filters( 'mywp_do_lockout_page' , MywpLockoutApi::get_lockout_page() );

    echo $lockout_page;

    exit;

  }

  private static function get_lockout_remote_data() {

    if( empty( self::$remote_ip ) ) {

      return false;

    }

    if( ! empty( self::$lockout_remote_data ) ) {

      return self::$lockout_remote_data;

    }

    $transient_key = sprintf( 'mywp_lockout_%s' , self::$remote_ip );

    if( is_multisite() ) {

      $lockout_remote_data = get_site_transient( $transient_key );

    } else {

      $lockout_remote_data = get_transient( $transient_key );

    }

    self::$lockout_remote_data = $lockout_remote_data;

    return $lockout_remote_data;

  }

  public static function set_lockout_remote_data( $data = false ) {

    if( ! empty( $data['reason'] ) ) {

      self::$lockout_reason = strip_tags( $data['reason'] );

    }

    $transient_key = sprintf( 'mywp_lockout_%s' , self::$remote_ip );

    $lockout_expire = 10 * MINUTE_IN_SECONDS;

    $setting_data = self::get_setting_data();

    if( ! empty( $setting_data['expire_timeout'] ) ) {

      $lockout_expire = intval( $setting_data['expire_timeout'] ) * MINUTE_IN_SECONDS;

    }

    $remote_data = array(
      'create_date' => self::$current_date['date'],
      'create_timestamp' => self::$current_date['timestamp'],
    );

    $remote_data = wp_parse_args( $data , $remote_data );

    if( is_multisite() ) {

      set_site_transient( $transient_key , $remote_data , $lockout_expire );

    } else {

      set_transient( $transient_key , $remote_data , $lockout_expire );

    }

  }

  public static function is_logged_in( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    if( is_user_logged_in() ) {

      return false;

    }

    return $is_lockout;

  }

  public static function is_exist_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $lockout_remote_data = self::get_lockout_remote_data();

    if( ! empty( $lockout_remote_data ) ) {

      self::$lockout_reason = 'Already Lockedout';

      return true;

    }

    return $is_lockout;

  }

  public static function is_login_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    if( ! isset( $_POST['log'] ) ) {

      return $is_lockout;

    }

    $login_name = $_POST['log'];

    if( ! isset( $_POST['pwd'] ) ) {

      return $is_lockout;

    }

    $password = $_POST['pwd'];

    self::$input_fields = array( 'login_name' => $login_name , 'password' => $password );

    if( $login_name === $password ) {

      self::set_lockout_remote_data( array( 'reason' => 'Same Login name and Password' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    if( MywpLockoutApi::is_weak_password( $password ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Weak password' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    $login_name .= '123';

    if( $login_name === $password ) {

      self::set_lockout_remote_data( array( 'reason' => 'Similar Login name and Password' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_get_data_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_get_lockout'] ) ) {

      return $is_lockout;

    }

    if( empty( $_GET ) ) {

      return $is_lockout;

    }

    $get_data = $_GET;

    self::$input_fields = array( 'get_data' => $get_data );

    if( MywpLockoutApi::is_blacklist_get_data( $get_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Blacklist Get Data' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_post_data_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_post_lockout'] ) ) {

      return $is_lockout;

    }

    if( empty( $_POST ) ) {

      return $is_lockout;

    }

    $post_data = $_POST;

    self::$input_fields = array( 'post_data' => $post_data );

    if( MywpLockoutApi::is_blacklist_post_data( $post_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Blacklist Post Data' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_file_data_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_file_lockout'] ) ) {

      return $is_lockout;

    }

    if( empty( $_FILES ) && ! isset( $_FILES[0] ) ) {

      return $is_lockout;

    }

    $file_data = $_FILES;

    self::$input_fields = array( 'file_data' => $file_data );

    if( MywpLockoutApi::is_blacklist_file_data( $file_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Blacklist Files Data' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_unknown_plugin_theme_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['unknown_plugin_theme_lockout'] ) ) {

      return $is_lockout;

    }

    if( empty( $_SERVER['REQUEST_URI'] ) ) {

      return false;

    }

    $request_uri = wp_parse_url( $_SERVER['REQUEST_URI'] );

    if( empty( $request_uri['path'] ) or strpos( $request_uri['path'] , '.php' ) === false ) {

      return false;

    }

    $request_path = $request_uri['path'];

    $plugin_theme_path = false;

    if( strpos( $request_path , 'wp-content/plugins/' ) !== false ) {

      $str_num = ( strpos( $request_path , 'wp-content/plugins/' ) + strlen( 'wp-content/plugins/' ) );

      $plugin_theme_path = WP_PLUGIN_DIR . '/' . substr( $request_path , $str_num );

    } elseif( strpos( $request_path , 'wp-content/themes/' ) !== false ) {

      $str_num = ( strpos( $request_path , 'wp-content/themes/' ) + strlen( 'wp-content/themes/' ) );

      $plugin_theme_path = get_theme_root() . '/' . substr( $request_path , $str_num );

    }

    if( empty( $plugin_theme_path ) ) {

      return false;

    }

    $post_data = $_POST;

    self::$input_fields = array( 'post_data' => $post_data );

    if( ! file_exists( $plugin_theme_path ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Unknown Plugin/Theme Access' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function lockout_send_email() {

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['send_mail'] ) or empty( $setting_data['send_to_email'] ) ) {

      return false;

    }

    $to = $setting_data['send_to_email'];

    if( ! is_email( $to ) ) {

      return false;

    }

    $to = apply_filters( 'mywp_lockout_send_email_to' , $to );

    $subject = sprintf( '%s - %s' , self::$lockout_reason , MYWP_LOCKOUT_NAME );

    $subject = apply_filters( 'mywp_lockout_send_email_subject' , $subject );

    $messages = '';

    $messages .= self::$lockout_reason . "\n\n";

    $messages .= sprintf( 'Site: %s' , do_shortcode( '[mywp_site field="name"]' ) ) . "\n\n";

    $messages .= sprintf( 'Date: %s' , self::$current_date['date'] ) . "\n\n";

    $messages .= sprintf( 'Remote IP: %s' , self::$remote_ip ) . "\n\n";

    $input_fields = map_deep( self::$input_fields , 'esc_html' );

    if( ! empty( $setting_data['send_email_with_input'] ) ) {

      $messages .= sprintf( 'Input: %s' , print_r( $input_fields , true ) ) . "\n\n";

    }

    if( ! empty( $setting_data['send_email_with_server'] ) ) {

      $messages .= sprintf( 'Server: %s' , print_r( $_SERVER , true ) ) . "\n\n";

    }

    $messages = apply_filters( 'mywp_lockout_send_email_messages' , $messages );

    $headers = apply_filters( 'mywp_lockout_send_email_headers' , array() );

    wp_mail( $to , $subject , $messages , $headers );

  }

}

MywpControllerModuleLockout::init();

endif;
