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

  protected static function after_init() {

    $setting_data = self::get_setting_data();

    if( ! empty( $setting_data['already_early_lockout'] ) ) {

      add_action( 'mywp_setup_theme' , array( __CLASS__ , 'fast_lockout' ) , 1000 );

    }

  }

  private static function get_is_allowlist() {

    $is_allowlist = false;

    $is_allowlist = apply_filters( 'mywp_lockout_is_allowlist' , $is_allowlist );

    return $is_allowlist;

  }

  public static function fast_lockout() {

    $is_allowlist = self::get_is_allowlist();

    if( $is_allowlist ) {

      return false;

    }

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_exist_lockout' ) , 20 );

    add_action( 'mywp_lockout_do_lockout' , array( __CLASS__ , 'lockout_send_email' ) , 20 );

    self::lockout();

  }

  public static function mywp_controller_initial_data( $initial_data ) {

    $initial_data['expire_timeout'] = '';
    $initial_data['already_early_lockout'] = '';

    $initial_data['weak_password_lockout'] = '';
    $initial_data['specific_get_lockout'] = '';
    $initial_data['specific_post_lockout'] = '';
    $initial_data['specific_file_lockout'] = '';
    $initial_data['specific_uri_lockout'] = '';
    $initial_data['unknown_plugin_theme_lockout'] = '';

    $initial_data['week_password_validate'] = '';
    $initial_data['comment_validate_lockout'] = '';
    $initial_data['sleep_lockout'] = '';
    $initial_data['send_mail'] = '';
    $initial_data['send_to_email'] = '';
    $initial_data['send_email_with_input'] = '';
    $initial_data['send_email_with_server'] = '';

    $initial_data['lockout_page'] = '';

    return $initial_data;

  }

  public static function mywp_controller_default_data( $default_data ) {

    $default_data['expire_timeout'] = '';
    $default_data['already_early_lockout'] = false;

    $default_data['weak_password_lockout'] = false;
    $default_data['specific_get_lockout'] = false;
    $default_data['specific_post_lockout'] = false;
    $default_data['specific_file_lockout'] = false;
    $default_data['specific_uri_lockout'] = false;
    $default_data['unknown_plugin_theme_lockout'] = false;

    $default_data['week_password_validate'] = false;
    $initial_data['comment_validate_lockout'] = false;
    $default_data['sleep_lockout'] = 0;
    $default_data['send_mail'] = false;
    $default_data['send_to_email'] = '';
    $default_data['send_email_with_input'] = false;
    $default_data['send_email_with_server'] = false;

    $default_data['lockout_page'] = MywpLockoutApi::get_lockout_default_content();

    return $default_data;

  }

  public static function mywp_wp_loaded() {

    if( ! self::is_do_controller() ) {

      return false;

    }

    $is_allowlist = self::get_is_allowlist();

    if( $is_allowlist ) {

      return false;

    }

    //add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_logged_in' ) , 5 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_exist_lockout' ) , 20 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_login_lockout' ) , 30 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_action_data_lockout' ) , 40 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_search_data_lockout' ) , 50 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_get_data_lockout' ) , 60 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_post_data_lockout' ) , 70 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_file_data_lockout' ) , 80 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_uri_lockout' ) , 90 );

    add_filter( 'mywp_lockout_is_lockout' , array( __CLASS__ , 'is_unknown_plugin_theme_lockout' ) , 100 );

    add_action( 'mywp_lockout_do_lockout' , array( __CLASS__ , 'lockout_send_email' ) , 20 );

    self::lockout();

  }

  public static function lockout() {

    $setting_data = self::get_setting_data();

    self::$current_date = array( 'date' => current_time( 'mysql' ) , 'timestamp' => current_time( 'timestamp' ) );

    if( ! empty( $setting_data['expire_timeout'] ) ) {

      self::$remote_ip = MywpLockoutApi::get_remote_ip();

    }

    $is_lockout = apply_filters( 'mywp_lockout_is_lockout' , false );

    if( ! $is_lockout ) {

      return false;

    }

    $delay_lockout_seconds = 0;

    if( ! empty( $setting_data['sleep_lockout'] ) ) {

      $sleep_lockout = (int) $setting_data['sleep_lockout'];

      if( ! empty( $sleep_lockout ) ) {

        $delay_lockout_seconds = $sleep_lockout;

        $max_lockout_seconds = MywpLockoutApi::get_max_lockout_seconds();

        if( ! empty( $max_lockout_seconds ) ) {

          if( $max_lockout_seconds < $sleep_lockout ) {

            $delay_lockout_seconds = $max_lockout_seconds;

          }

        }

      }

    }

    if( $delay_lockout_seconds > 0 ) {

      sleep( $delay_lockout_seconds );

    }

    self::do_lockedout();

  }

  public static function do_lockedout() {

    do_action( 'mywp_lockout_do_lockout' );

    add_filter( 'mywp_lockout_do_lockout_content' , 'wptexturize' );
    add_filter( 'mywp_lockout_do_lockout_content' , 'convert_smilies' , 20 );
    add_filter( 'mywp_lockout_do_lockout_content' , 'wpautop' );
    add_filter( 'mywp_lockout_do_lockout_content' , 'shortcode_unautop' );
    add_filter( 'mywp_lockout_do_lockout_content' , 'prepend_attachment' );

    $lockout_header = 'HTTP/1.1 403 Forbidden';

    $lockout_header = apply_filters( 'mywp_lockout_do_lockout_header' , $lockout_header );

    if( ! empty( $lockout_header ) ) {

      header( $lockout_header );

    }

    $lockout_content = apply_filters( 'mywp_lockout_do_lockout_content' , MywpLockoutApi::get_lockout_content() );

    echo $lockout_content;

    exit;

  }

  private static function get_lockout_remote_data() {

    if( ! empty( self::$lockout_remote_data ) ) {

      return self::$lockout_remote_data;

    }

    if( empty( self::$remote_ip ) ) {

      return false;

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

  private static function mask_input_fields( $input_fields ) {

    if( ! is_array( $input_fields ) ) {

      return $input_fields;

    }

    $mask_fields = MywpLockoutApi::get_mask_fields();

    if( empty( $mask_fields ) ) {

      return $input_fields;

    }

    foreach( $input_fields as $key => $val ) {

      if( in_array( $key , $mask_fields , true ) ) {

        $input_fields[ $key ] = '*****MASKED*****';

      }

      if( is_array( $input_fields[ $key ] ) ) {

        self::mask_input_fields( $input_fields[ $key ] );

      }

    }

    return $input_fields;

  }

  public static function set_lockout_remote_data( $data = false ) {

    if( ! empty( $data['reason'] ) ) {

      self::$lockout_reason = strip_tags( $data['reason'] );

    }

    $transient_key = sprintf( 'mywp_lockout_%s' , self::$remote_ip );

    $lockout_expire = 0;

    $setting_data = self::get_setting_data();

    if( ! empty( $setting_data['expire_timeout'] ) ) {

      $lockout_expire = (int) ( (int) $setting_data['expire_timeout'] * MINUTE_IN_SECONDS );

    }

    if( empty( $lockout_expire ) ) {

      return false;

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

  /*
  public static function is_logged_in( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    if( is_user_logged_in() ) {

      return false;

    }

    return $is_lockout;

  }
  */

  public static function is_exist_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $lockout_remote_data = self::get_lockout_remote_data();

    $get_data = false;

    if( ! empty( $_GET ) ) {

      $get_data = $_GET;

    }

    $post_data = false;

    if( ! empty( $_POST ) ) {

      $post_data = $_POST;

    }

    self::$input_fields = array( 'get_data' => self::mask_input_fields( $get_data ) , 'post_data' => self::mask_input_fields(  $post_data ) );

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

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['weak_password_lockout'] ) ) {

      return $is_lockout;

    }

    $login = MywpLockoutApi::get_login();

    if( empty( $login['name'] ) && empty( $login['password'] ) ) {

      return $is_lockout;

    }

    self::$input_fields = self::mask_input_fields( array( 'login_name' => $login['name'] , 'password' => $login['password'] ) );

    if( MywpLockoutApi::is_weak_password( $login['password'] , $login['name'] ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Weak password' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_action_data_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_get_lockout'] ) && empty( $setting_data['specific_post_lockout'] ) ) {

      return $is_lockout;

    }

    $request_data = false;

    if( ! empty( $_REQUEST ) ) {

      $request_data = $_REQUEST;

    }

    self::$input_fields = array( 'request_data' => self::mask_input_fields( $request_data ) );

    if( MywpLockoutApi::is_denylist_action_data( $request_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist action' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_search_data_lockout( $is_lockout ) {

    if( is_admin() ) {

      return $is_lockout;

    }

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_get_lockout'] ) ) {

      return $is_lockout;

    }

    $get_data = false;

    if( ! empty( $_GET ) ) {

      $get_data = $_GET;

    }

    self::$input_fields = array( 'get_data' => self::mask_input_fields( $get_data ) );

    if( MywpLockoutApi::is_denylist_search_data( $get_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist search' , 'input_fields' => self::$input_fields ) );

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

    self::$input_fields = array( 'get_data' => self::mask_input_fields( $get_data ) );

    if( MywpLockoutApi::is_denylist_get_data( $get_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist Get Data' , 'input_fields' => self::$input_fields ) );

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

    self::$input_fields = array( 'post_data' => self::mask_input_fields( $post_data ) );

    if( MywpLockoutApi::is_denylist_post_data( $post_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist Post Data' , 'input_fields' => self::$input_fields ) );

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

    if( MywpLockoutApi::is_denylist_file_data( $file_data ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist Files Data' , 'input_fields' => self::$input_fields ) );

      return true;

    }

    return $is_lockout;

  }

  public static function is_uri_lockout( $is_lockout ) {

    if( $is_lockout ) {

      return $is_lockout;

    }

    $setting_data = self::get_setting_data();

    if( empty( $setting_data['specific_uri_lockout'] ) ) {

      return $is_lockout;

    }

    if( empty( $_SERVER['REQUEST_URI'] ) ) {

      return $is_lockout;

    }

    $request_uri = $_SERVER['REQUEST_URI'];

    self::$input_fields = array( 'request_uri' => $request_uri );

    if( MywpLockoutApi::is_denylist_uri( $request_uri ) ) {

      self::set_lockout_remote_data( array( 'reason' => 'Denylist URI' , 'input_fields' => self::$input_fields ) );

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

    if( empty( $setting_data['send_mail'] ) ) {

      return false;

    }

    $to = false;

    if( ! empty( $setting_data['send_to_email'] ) ) {

      $to = $setting_data['send_to_email'];

    }

    if( ! is_email( $to ) ) {

      return false;

    }

    $to = apply_filters( 'mywp_lockout_send_email_to' , $to );

    $subject = sprintf( '%s - %s' , self::$lockout_reason , MYWP_LOCKOUT_NAME );

    $subject = apply_filters( 'mywp_lockout_send_email_subject' , $subject );

    $messages = '';

    $messages .= self::$lockout_reason . "\n\n";

    $site_name = do_shortcode( '[mywp_site field="name"]' );

    if( empty( $site_name ) or $site_name === '[mywp_site field="name"]' ) {

      $site_name = get_option( 'blogname' );

    }

    $messages .= sprintf( 'Site: %s' , $site_name ) . "\n\n";

    if( is_multisite() ) {

      $messages .= sprintf( 'Blog ID: %s' , get_current_blog_id() ) . "\n\n";

    }

    $messages .= sprintf( 'Date: %s (%s)' , self::$current_date['date'] , get_option( 'timezone_string' ) ) . "\n\n";

    $messages .= sprintf( 'Remote IP: %s' , self::$remote_ip ) . "\n\n";

    if( ! empty( $setting_data['send_email_with_input'] ) ) {

      $input_fields = map_deep( self::$input_fields , 'esc_html' );

      $messages .= sprintf( 'Input: %s' , print_r( $input_fields , true ) ) . "\n\n";

    }

    if( ! empty( $setting_data['send_email_with_server'] ) ) {

      $messages .= sprintf( 'Server: %s' , print_r( $_SERVER , true ) ) . "\n\n";

    }

    $messages = apply_filters( 'mywp_lockout_send_email_messages' , $messages );

    $headers = apply_filters( 'mywp_lockout_send_email_headers' , array() );

    $switch_blog = false;

    if( is_multisite() ) {

      if( ! is_main_site() ) {

        $switch_blog = true;

      }

    }

    if( $switch_blog ) {

      switch_to_blog( 1 );

    }

    wp_mail( $to , $subject , $messages , $headers );

    if( $switch_blog ) {

      restore_current_blog();

    }

  }

}

MywpControllerModuleLockout::init();

endif;
