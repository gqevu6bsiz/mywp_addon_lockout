<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutApi' ) ) :

final class MywpLockoutApi {

  public static function plugin_info() {

    $plugin_info = array(
      'admin_url' => admin_url( 'admin.php?page=mywp_add_on_lockout' ),
      'document_url' => 'https://mywpcustomize.com/add_ons/my-wp-add-on-lockout',
      'website_url' => 'https://mywpcustomize.com/',
      'github' => 'https://github.com/gqevu6bsiz/mywp_addon_lockout',
      'github_raw' => 'https://raw.githubusercontent.com/gqevu6bsiz/mywp_addon_lockout/',
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

  public static function is_denylist_get_data( $get_data = false ) {

    if( empty( $get_data ) ) {

      return true;

    }

    $is_denylist = false;

    $denylist_get_data_name_list = MywpLockoutList::get_denylist_get_data_name_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $denylist_get_data_name_list as $word ) {

        if( (string) $get_data_key === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_get_data_value_list = MywpLockoutList::get_denylist_get_data_value_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $denylist_get_data_value_list as $word ) {

        if( is_array( $get_data_val ) ) {

          continue;

        }

        if( (string) $get_data_val === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_get_data_name_value_list = MywpLockoutList::get_denylist_get_data_name_value_list();

    foreach( $get_data as $get_data_key => $get_data_val ) {

      foreach( $denylist_get_data_name_value_list as $name => $value ) {

        if( (string) $get_data_key === (string) $name && (string) $get_data_val === (string) $value ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_get_data_name_pattern_list = MywpLockoutList::get_denylist_get_data_name_pattern_list();

    foreach( $denylist_get_data_name_pattern_list as $pattern ) {

      if( empty( $pattern['pattern'] ) && ! isset( $pattern['after_delimiter'] ) ) {

        continue;

      }

      $pattern['pattern'] = str_replace( '/' , '\/' , $pattern['pattern'] );

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      foreach( $get_data as $get_data_key => $post_data_val ) {

        if( preg_match( $pattern_str , $get_data_key ) ) {

          $is_denylist = true;

          break;

        }

      }

      if( $is_denylist ) {

        break;

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_get_data_find_value_list = MywpLockoutList::get_denylist_get_data_find_value_list();

    $serialize_get_data = maybe_serialize( $get_data );

    foreach( $denylist_get_data_find_value_list as $word ) {

      if( strpos( $serialize_get_data , $word ) !== false ) {

        $is_denylist = true;

        break;

      }

    }

    if( $is_denylist ) {

      return true;

    }

    return false;

  }

  public static function is_denylist_post_data( $post_data = false ) {

    if( empty( $post_data ) ) {

      return true;

    }

    $is_denylist = false;

    $denylist_post_data_name_list = MywpLockoutList::get_denylist_post_data_name_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $denylist_post_data_name_list as $word ) {

        if( (string) $post_data_key === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_post_data_value_list = MywpLockoutList::get_denylist_post_data_value_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $denylist_post_data_value_list as $word ) {

        if( ! is_array( $post_data_val ) ) {

          if( (string) $post_data_val === (string) $word ) {

            $is_denylist = true;

            break;

          }

        } else {

          foreach( $post_data_val as $post_data_val_k => $post_data_val_v ) {

            if( is_array( $post_data_val_v ) ) {

              continue;

            }

            if( (string) $post_data_val_v === (string) $word ) {

              $is_denylist = true;

              break;

            }

          }

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_post_data_name_value_list = MywpLockoutList::get_denylist_post_data_name_value_list();

    foreach( $post_data as $post_data_key => $post_data_val ) {

      foreach( $denylist_post_data_name_value_list as $name => $value ) {

        if( (string) $post_data_key === (string) $name && (string) $post_data_val === (string) $value ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_post_data_name_pattern_list = MywpLockoutList::get_denylist_post_data_name_pattern_list();

    foreach( $denylist_post_data_name_pattern_list as $pattern ) {

      if( empty( $pattern['pattern'] ) && ! isset( $pattern['after_delimiter'] ) ) {

        continue;

      }

      $pattern['pattern'] = str_replace( '/' , '\/' , $pattern['pattern'] );

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      foreach( $post_data as $post_data_key => $post_data_val ) {

        if( preg_match( $pattern_str , $post_data_key ) ) {

          $is_denylist = true;
          break;

        }

      }

      if( $is_denylist ) {

        break;

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_post_data_find_value_list = MywpLockoutList::get_denylist_post_data_find_value_list();

    $serialize_post_data = maybe_serialize( $post_data );

    foreach( $denylist_post_data_find_value_list as $word ) {

      if( strpos( $serialize_post_data , $word ) !== false ) {

        $is_denylist = true;

        break;

      }

    }

    if( $is_denylist ) {

      return true;

    }

    return false;

  }

  public static function is_denylist_file_data( $file_data = false ) {

    if( empty( $file_data ) ) {

      return true;

    }

    $is_denylist = false;

    $denylist_file_data_name_list = MywpLockoutList::get_denylist_file_data_name_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      if( empty( $file_data_key ) ) {

        continue;

      }

      foreach( $denylist_file_data_name_list as $word ) {

        if( (string) $file_data_key === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_file_data_value_list = MywpLockoutList::get_denylist_file_data_value_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      foreach( $denylist_file_data_value_list as $word ) {

        if( empty( $file_data_val['name'] ) ) {

          continue;

        }

        if( is_array( $file_data_val['name'] ) ) {

          continue;

        }

        if( (string) $file_data_val['name'] === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    $denylist_file_extension_list = MywpLockoutList::get_denylist_file_extension_list();

    foreach( $file_data as $file_data_key => $file_data_val ) {

      foreach( $denylist_file_extension_list as $word ) {

        if( empty( $file_data_val['name'] ) or is_array( $file_data_val['name'] ) ) {

          continue;

        }

        $file_path_info = pathinfo( $file_data_val['name'] );

        if( empty( $file_path_info['extension'] ) ) {

          continue;

        }

        if( (string) $file_path_info['extension'] === (string) $word ) {

          $is_denylist = true;

          break;

        }

      }

    }

    if( $is_denylist ) {

      return true;

    }

    return false;

  }

  public static function is_denylist_uri( $request_uri = false ) {

    if( empty( $request_uri ) ) {

      return true;

    }

    $is_denylist = false;

    $denylist_uri_find_list = MywpLockoutList::get_denylist_uri_find_list();

    $request_uri = urldecode( $request_uri );

    foreach( $denylist_uri_find_list as $word ) {

      if( strpos( $request_uri , $word ) !== false ) {

        $is_denylist = true;

        break;

      }

    }

    if( $is_denylist ) {

      return true;

    }

    return false;

  }

  public static function get_login() {

    $login = array( 'name' => '' , 'password' => '' );

    if( ! empty( $_POST['log'] ) ) {

      $login['name'] = $_POST['log'];

    }

    if( ! empty( $_POST['pwd'] ) ) {

      $login['password'] = $_POST['pwd'];

    }

    $login = apply_filters( 'mywp_lockout_get_login' , $login );

    return $login;

  }

  public static function mywp_lockout_get_login_thirdparty( $login ) {

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

  public static function get_max_lockout_seconds() {

    $max_lockout_seconds = -1;

    if( function_exists( 'ini_get' ) ) {

      $max_lockout_seconds = ini_get( 'max_execution_time' );

    }

    if( ! empty( $max_lockout_seconds ) && $max_lockout_seconds > 0 ) {

      $max_lockout_seconds = intval( $max_lockout_seconds );

    }

    return $max_lockout_seconds;

  }

  public static function get_mask_fields() {

    $mask_fields = array( 'password' );

    $mask_fields = apply_filters( 'mywp_lockout_get_mask_fields' , $mask_fields );

    return $mask_fields;

  }

}

endif;
