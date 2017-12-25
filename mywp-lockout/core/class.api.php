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

    $data = $mywp_controller['model']->get_data();

    if( empty( $data['lockout_page'] ) ) {

      return $lockout_page;

    }

    return $data['lockout_page'];

  }

  public static function is_weak_password( $password = false ) {

    if( empty( $password ) ) {

      return true;

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

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      if( preg_match( $pattern_str , $password ) ) {

        $is_weak_password = true;
        break;

      }

    }

    if( $is_weak_password ) {

      return true;

    }

    return false;

  }

  public static function is_blacklist_get_data( $get_data = false ) {

    if( empty( $get_data ) ) {

      return true;

    }

    $is_blacklist = false;

    $blacklist_get_data_name_list = MywpLockoutList::get_blacklist_get_data_name_list();

    foreach( $get_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_get_data_name_list as $word ) {

        if( $post_data_key == $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_value_list = MywpLockoutList::get_blacklist_get_data_value_list();

    foreach( $get_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_get_data_value_list as $word ) {

        if( $post_data_val == $word ) {

          $is_blacklist = true;

          break;

        }

      }

    }

    if( $is_blacklist ) {

      return true;

    }

    $blacklist_get_data_name_value_list = MywpLockoutList::get_blacklist_get_data_name_value_list();

    foreach( $get_data as $post_data_key => $post_data_val ) {

      foreach( $blacklist_get_data_name_value_list as $name => $value ) {

        if( $post_data_key == $name && $post_data_val == $value ) {

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

      $pattern_str = sprintf( '/%s/%s' , $pattern['pattern'] , $pattern['after_delimiter'] );

      foreach( $get_data as $post_data_key => $post_data_val ) {

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

        if( $post_data_key == $word ) {

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

        if( $post_data_val == $word ) {

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

        if( $post_data_key == $name && $post_data_val == $value ) {

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

    return false;

  }

}

endif;
