<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutApi' ) ) :

final class MywpLockoutApi {

  private static $instance;

  private function __construct() {}

  public static function get_instance() {

    if ( !isset( self::$instance ) ) {

      self::$instance = new self();

    }

    return self::$instance;

  }

  private function __clone() {}

  private function __wakeup() {}

  public static function plugin_info() {

    $plugin_info = array(
      'admin_url' => admin_url( 'admin.php?page=mywp_add_on_lockout' ),
      'document_url' => 'https://mywpcustomize.com/add_ons/my-wp-add-on-lockout',
      'website_url' => 'https://mywpcustomize.com/',
      'github' => 'https://github.com/gqevu6bsiz/mywp_addon_lockout',
      'github_tags' => 'https://api.github.com/repos/gqevu6bsiz/mywp_addon_lockout/tags',
    );

    if( is_multisite() ) {

      $plugin_info['admin_url'] = network_admin_url( 'admin.php?page=mywp_add_on_lockout' );

    }

    $plugin_info = apply_filters( 'mywp_lockout_plugin_info' , $plugin_info );

    return $plugin_info;

  }

  public static function is_network_manager() {

    if( method_exists( 'MywpApi' , 'is_network_manager' ) ) {

      return MywpApi::is_network_manager();

    }

    return MywpApi::is_manager();

  }

  public static function is_manager() {

    return MywpApi::is_manager();

  }

  public static function get_remote_ip() {

    $remote_ip = false;

    if( isset( $_SERVER['REMOTE_ADDR'] ) ) {

      $remote_ip = $_SERVER['REMOTE_ADDR'];

    }

    $remote_ip = apply_filters( 'mywp_lockout_get_remote_ip' , $remote_ip );

    return $remote_ip;

  }

  public static function get_send_from_email() {

    $send_from_email = apply_filters( 'mywp_lockout_send_from_email' , get_option( 'admin_email' ) );

    return $send_from_email;

  }

  public static function get_lockout_default_page() {

    $lockout_default_page = '<h1>403 Forbidden</h1>';

    return $lockout_default_page;

  }

  public static function get_lockout_page() {

    $lockout_page = self::get_lockout_default_page();

    $mywp_controller = MywpController::get_controller( 'lockout' );

    if( empty( $mywp_controller ) ) {

      return $lockout_page;

    }

    $option = $mywp_controller['model']->get_option();

    if( empty( $option['lockout_page'] ) ) {

      return $lockout_page;

    }

    return $option['lockout_page'];

  }

  public static function is_weak_password( $password = false ) {

    if( $password === false ) {

      return false;

    }

    $password = strip_tags( $password );

    $weak_password_list = MywpLockoutList::get_weak_password_list();

    if( in_array( $password , $weak_password_list ) ) {

      return true;

    }

    $weak_password_pattern_list = MywpLockoutList::get_weak_password_pattern_list();

    $is_weak_password = false;

    foreach( $weak_password_pattern_list as $pattern ) {

      if( empty( $pattern['pattern'] ) && ! isset( $pattern['after_delimiter'] ) ) {

        continue;

      }

      $pattern['pattern'] = str_replace( '/' , '\/' , $pattern['pattern'] );

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      if( preg_match( $pattern_str , $password ) ) {

        $is_weak_password = true;
        break;

      }

    }

    if( $is_weak_password ) {

      return true;

    }

    if( ! empty( $_SERVER['SERVER_NAME'] ) ) {

      $server_name = $_SERVER['SERVER_NAME'];

      $weeek_domain_password = false;

      if( strpos( $server_name , '.' ) === false ) {

        $weeek_domain_password = $server_name;

      } else {

        $server_name_arr = explode( '.' , $server_name );

        array_pop( $server_name_arr );

        if( isset( $server_name_arr[1] ) ) {

          $weeek_domain_password = implode( '.' , $server_name_arr );

        } else {

          $weeek_domain_password = $server_name_arr[0];

        }

      }

      if( $weeek_domain_password ) {

        if( strpos( $password , $weeek_domain_password ) !== false ) {

          return true;

        }

      }

    }

    return false;

  }

  public static function is_blacklist_get_data( $get_data = false ) {

    if( empty( $get_data ) ) {

      return true;

    }

    $is_blacklist = false;

    $blacklist_get_data_name_list = MywpLockoutList::get_blacklist_get_data_name_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $blacklist_get_data_name_list as $word ) {

        if( (string) $get_data_key === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_value_list = MywpLockoutList::get_blacklist_get_data_value_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $blacklist_get_data_value_list as $word ) {

        if( is_array( $get_data_val ) ) {

          continue;

        }

        if( (string) $get_data_val === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_name_value_list = MywpLockoutList::get_blacklist_get_data_name_value_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $blacklist_get_data_name_value_list as $name => $value ) {

        if( (string) $get_data_key === (string) $name && (string) $get_data_val === (string) $value ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_name_pattern_list = MywpLockoutList::get_blacklist_get_data_name_pattern_list();

    foreach( $blacklist_get_data_name_pattern_list as $pattern ) {

      if( empty( $pattern['pattern'] ) && ! isset( $pattern['after_delimiter'] ) ) {

        continue;

      }

      $pattern['pattern'] = str_replace( '/' , '\/' , $pattern['pattern'] );

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      foreach( $get_data as $get_data_key => $post_data_val ) {

        if( preg_match( $pattern_str , $get_data_key ) ) {

          $is_blacklist = true;

          break;

        }

      }

      if( $is_blacklist ) {

        break;

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_find_value_list = MywpLockoutList::get_blacklist_get_data_find_value_list();

    $serialize_get_data = maybe_serialize( $get_data );

    foreach( $blacklist_get_data_find_value_list as $word ) {

      if( strpos( $serialize_get_data , $word ) !== false ) {

        $is_blacklist = true;

        break;

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    return false;

  }

  public static function is_blacklist_post_data( $post_data = false ) {

    if( empty( $post_data ) ) {

      return true;

    }

    $is_blacklist = false;

    $blacklist_post_data_name_list = MywpLockoutList::get_blacklist_post_data_name_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_post_data_name_list as $word ) {

        if( (string) $post_data_key === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_post_data_value_list = MywpLockoutList::get_blacklist_post_data_value_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_post_data_value_list as $word ) {

        if( is_array( $post_data_val ) ) {

          continue;

        }

        if( (string) $post_data_val === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_post_data_name_value_list = MywpLockoutList::get_blacklist_post_data_name_value_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_post_data_name_value_list as $name => $value ) {

        if( (string) $post_data_key === (string) $name && (string) $post_data_val === (string) $value ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_post_data_name_pattern_list = MywpLockoutList::get_blacklist_post_data_name_pattern_list();

    foreach( $blacklist_post_data_name_pattern_list as $pattern ) {

      if( empty( $pattern['pattern'] ) && ! isset( $pattern['after_delimiter'] ) ) {

        continue;

      }

      $pattern['pattern'] = str_replace( '/' , '\/' , $pattern['pattern'] );

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      foreach( $post_data as $post_data_key => $post_data_val ) {

        if( preg_match( $pattern_str , $post_data_key ) ) {

          $is_blacklist = true;
          break;

        }

      }

      if( $is_blacklist ) {

        break;

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_post_data_find_value_list = MywpLockoutList::get_blacklist_post_data_find_value_list();

    $serialize_post_data = maybe_serialize( $post_data );

    foreach( $blacklist_post_data_find_value_list as $word ) {

      if( strpos( $serialize_post_data , $word ) !== false ) {

        $is_blacklist = true;

        break;

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    return false;

  }

  public static function is_blacklist_file_data( $file_data = false ) {

    if( empty( $file_data ) ) {

      return true;

    }

    $is_blacklist = false;

    $blacklist_file_data_name_list = MywpLockoutList::get_blacklist_file_data_name_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      if( empty( $file_data_key ) ) {

        continue;

      }

      foreach( $blacklist_file_data_name_list as $word ) {

        if( (string) $file_data_key === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_file_data_value_list = MywpLockoutList::get_blacklist_file_data_value_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      foreach( $blacklist_file_data_value_list as $word ) {

        if( empty( $file_data_val['name'] ) ) {

          continue;

        }

        if( (string) $file_data_val['name'] === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_file_extension_list = MywpLockoutList::get_blacklist_file_extension_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      foreach( $blacklist_file_extension_list as $word ) {

        if( empty( $file_data_val['name'] ) ) {

          continue;

        }

        $file_path_info = pathinfo( $file_data_val['name'] );

        if( empty( $file_path_info['extension'] ) ) {

          continue;

        }

        if( (string) $file_path_info['extension'] === (string) $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    return false;

  }

  public static function is_blacklist_uri( $request_uri = false ) {

    if( empty( $request_uri ) ) {

      return true;

    }

    $is_blacklist = false;

    $blacklist_uri_find_list = MywpLockoutList::get_blacklist_uri_find_list();

    $request_uri = urldecode( $request_uri );

    foreach( $blacklist_uri_find_list as $word ) {

      if( strpos( $request_uri , $word ) !== false ) {

        $is_blacklist = true;

        break;

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    return false;

  }

}

endif;
