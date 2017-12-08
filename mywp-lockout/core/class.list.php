<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutList' ) ) :

final class MywpLockoutList {

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

  public static function get_weak_password_list() {

    $weak_password_list = array(

      '!qaz',
      '!qaz@wsx',
      '!qaz1qaz',
      '!qazxsw',

      'admin',
      'abc',

      'changeme',
      'chopper',

      'letmein',

      'migration',

      'parisdenoia',
      'pass',
      'password',
      'p@ssw0rd',

      'qwe',

      'realmadrid',

      'share',
      'shaved',
      'shit',
      'shopmanager',
      'shopmanager@',
      'sierra',
      'sinegra',
      'sister',
      'slayer',
      'slipknot',
      'slut',
      'smokey',
      'snoopy',
      'snowfall',
      'soccer',
      'sonrisa',
      'sony',
      'sophie',
      'soto',
      'soyhermosa',
      'spanky',
      'spider',
      'spirit',
      'sqlexec',
      'squirt',
      'srinivas',
      'star',
      'stars',
      'startrek',
      'steven',
      'student',
      'stupid',
      'success',
      'sudoku',
      'summer',
      'sunshine',
      'super',
      'superman',
      'superuser',
      'supervisor',
      'surfer',
      'swimming',

      'taylor',
      'teens',
      'tekila',
      'telefono',
      'temp!',
      'test',
      'tester',
      'testing',
      'temp',
      'temporary',
      'tennis',
      'tequiero',
      'teresa',
      'thebest',
      'theman',
      'therock',
      'thomas',
      'thunder',
      'tiffany',
      'tiger',
      'tigers',
      'tigger',
      'time',
      'timosha',
      'tinkerbell',
      'titimaman',
      'tits',
      'tivoli',
      'tomcat',
      'trinity',
      'trustno',
      'tucker',
      'tweety',
      'twitter',
      'tybnoq',

      'unicornio',
      'universidad',
      'unknown',

      'vagina',
      'valentina',
      'valeverga',
      'veracruz',
      'veronica',
      'victoria',
      'viking',
      'virus',
      'voodoo',
      'voyager',

      'warrior',
      'web',
      'westside',
      'whatever',
      'white',
      'william',
      'willow',
      'winston',
      'wiesenhof',
      'wolf',
      'women',
      'work',
      'writer',
      'writing',

      'xanadu',
      'xavier',
      'ximena',
      'ximenita',
      'xxx',
      'xxxx',
      'xxxxxx',
      'xxxxxxxx',

      'yankees',
      'yeshua',
      'young',
      'ysrmma',

      'zapato',
      'zxczxc',
      'zxcvbnm',
      'zzzzz',

    );

    $weak_password_list = apply_filters( 'mywp_lockout_weak_password_list' , $weak_password_list );

    return $weak_password_list;

  }

  public static function get_weak_password_pattern_list() {

    $patterns = array();

    $patterns[] = array(
      'pattern' => '^[0-9]+$',
      'after_delimiter' => '',
    );

    $weak_password_list = self::get_weak_password_list();

    foreach( $weak_password_list as $word ) {

      $patterns[] = array(
        'pattern' => sprintf( '^%s' , $word ),
        'after_delimiter' => 'i',
      );

      $patterns[] = array(
        'pattern' => sprintf( '^%s[0-9]+$' , $word ),
        'after_delimiter' => 'i',
      );

    }

    $default = array(
      'pattern' => '',
      'after_delimiter' => '',
    );

    $weak_password_pattern_list = array();

    foreach( $patterns as $pattern ) {

      $weak_password_pattern_list[] = wp_parse_args( $pattern , $default );

    }

    $weak_password_pattern_list = apply_filters( 'mywp_lockout_weak_password_pattern_list' , $weak_password_pattern_list );

    return $weak_password_pattern_list;

  }

  public static function get_blacklist_get_data_name_list() {

    $blacklist_get_data_name_list = array(

      'testwork',

    );

    $blacklist_get_data_name_list = apply_filters( 'mywp_lockout_blacklist_get_data_name_list' , $blacklist_get_data_name_list );

    return $blacklist_get_data_name_list;

  }

  public static function get_blacklist_get_data_value_list() {

    $blacklist_get_data_value_list = array();

    $blacklist_get_data_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_value_list' , $blacklist_get_data_value_list );

    return $blacklist_get_data_value_list;

  }

  public static function get_blacklist_get_data_name_value_list() {

    $blacklist_get_data_name_value_list = array(

      'login' => 'x2a5xB',
      'login' => 'cmd',
      'login' => 'safe',

      'author' => '3',

    );

    $blacklist_get_data_name_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_name_value_list' , $blacklist_get_data_name_value_list );

    return $blacklist_get_data_name_value_list;

  }

  public static function get_blacklist_get_data_name_pattern_list() {

    $patterns = array();

    $blacklist_get_data_name_list = self::get_blacklist_get_data_name_list();

    foreach( $blacklist_get_data_name_list as $word ) {

      $patterns[] = array(
        'pattern' => sprintf( '^%s' , $word ),
        'after_delimiter' => 'i',
      );

      $patterns[] = array(
        'pattern' => sprintf( '^%s[0-9]+$' , $word ),
        'after_delimiter' => 'i',
      );

    }

    $default = array(
      'pattern' => '',
      'after_delimiter' => '',
    );

    $blacklist_get_data_name_pattern_list = array();

    foreach( $patterns as $pattern ) {

      $blacklist_get_data_name_pattern_list[] = wp_parse_args( $pattern , $default );

    }

    $blacklist_get_data_name_pattern_list = apply_filters( 'mywp_lockout_blacklist_get_data_name_pattern_list' , $blacklist_get_data_name_pattern_list );

    return $blacklist_get_data_name_pattern_list;

  }

  public static function get_blacklist_post_data_name_list() {

    $blacklist_post_data_name_list = array(

      'fuckyou',

      'itongtong',

      'pwidget',

      'spanskys_filecontent',

      'theendding',

      'x0x0',
      'xxeerr',

    );

    $blacklist_post_data_name_list = apply_filters( 'mywp_lockout_blacklist_post_data_name_list' , $blacklist_post_data_name_list );

    return $blacklist_post_data_name_list;

  }

  public static function get_blacklist_post_data_value_list() {

    $blacklist_post_data_value_list = array(

      'die(pi()*42);',

      'print(md5(11111));',

    );

    $blacklist_post_data_value_list = apply_filters( 'mywp_lockout_blacklist_post_data_value_list' , $blacklist_post_data_value_list );

    return $blacklist_post_data_value_list;

  }

  public static function get_blacklist_post_data_name_value_list() {

    $blacklist_post_data_name_value_list = array();

    $blacklist_post_data_name_value_list = apply_filters( 'mywp_lockout_blacklist_post_data_name_value_list' , $blacklist_post_data_name_value_list );

    return $blacklist_post_data_name_value_list;

  }

  public static function get_blacklist_post_data_name_pattern_list() {

    $patterns = array();

    $blacklist_post_data_name_list = self::get_blacklist_post_data_name_list();

    foreach( $blacklist_post_data_name_list as $word ) {

      $patterns[] = array(
        'pattern' => sprintf( '^%s' , $word ),
        'after_delimiter' => 'i',
      );

      $patterns[] = array(
        'pattern' => sprintf( '^%s[0-9]+$' , $word ),
        'after_delimiter' => 'i',
      );

    }

    $default = array(
      'pattern' => '',
      'after_delimiter' => '',
    );

    $blacklist_post_data_name_pattern_list = array();

    foreach( $patterns as $pattern ) {

      $blacklist_post_data_name_pattern_list[] = wp_parse_args( $pattern , $default );

    }

    $blacklist_post_data_name_pattern_list = apply_filters( 'mywp_lockout_blacklist_post_data_name_pattern_list' , $blacklist_post_data_name_pattern_list );

    return $blacklist_post_data_name_pattern_list;

  }

}

endif;
