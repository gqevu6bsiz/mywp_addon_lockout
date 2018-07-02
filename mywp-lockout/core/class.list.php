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
      '@dm',
      '{nigoL}',

      '1{nigoL}',
      '123{nigoL}',
      '123123a',
      '12qwaszx',
      '12345q',
      '1234qwer',
      '123qwe',
      '13pass',
      '1qaz',
      '1q2w3e4r',
      '1,12E 11',
      '67i5jjjt',

      'a12345678',
      'a123powerx-*',
      'aaaaaa',
      'admin',
      'abc',
      'alessandra',
      'alexandru',
      'ali',
      'andromeda',
      'apple',
      'arif',
      'asdasd',
      'asdqwe123',

      'banana',
      'blogadmin',
      'blogging',
      'brunarice',
      'business',

      'changeme',
      'cherry',
      'chelsea',
      'chocolate',
      'chopper',
      'cliente',
      'computer',
      'counter',
      'csaaihex',

      'dave',
      'demo',
      'dragon',

      'editor',
      'elena',
      'emre',

      'first',
      'F*uckYou',

      'guest',
      'gimboroot',

      'hannah',
      'hello',
      'henkondernemer',
      'hunter',

      'iloveyou',
      'index',
      'ines',
      'iyfm',

      'jason',
      'jerome',
      'jesse',

      'K98pWjiSq',
      'karine',
      'kiki',

      'letmein',
      'lfplhfgthvf',
      'logitech89',

      'magda',
      'mercedes',
      'maria',
      'marketing',
      'marta',
      'master',
      'matrix',
      'mercury',
      'michelle',
      'migration',
      'monkey',

      'NULL',

      'oliver',
      'oshibe',

      'p@ssw0rd',
      'P@ssword',
      'pa$$word',
      'paradise',
      'parisdenoia',
      'parkerspin',
      'pass',
      'passw0rd',
      'password',
      'philipp',
      'prince',

      'q1q1q1',
      'q1w2e3',
      'q1w2e3r4',
      'q1w2e3r4t5',
      'qaz',
      'qq123',
      'qwe',
      'qwsxza',

      'm@rcos',
      'merlin',
      'mexeltech',
      'mihai',
      'miranda',

      'realmadrid',
      'redaktion',
      'ricsky789..',
      'root',

      'sadmin',
      'secret',
      'shadow',
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
      'smallcountry',
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
      'support',
      'stalker',
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
      'tony',
      'trinity',
      'trustno',
      'trustno1',
      'tucker',
      'tweety',
      'twitter',
      'tybnoq',

      'unicornio',
      'universidad',
      'unknown',
      'user',

      'vagina',
      'valentina',
      'valeverga',
      'vendor',
      'veracruz',
      'veronica',
      'victoria',
      'vijay',
      'viking',
      'vincent',
      'virus',
      'voodoo',
      'voyager',

      'warrior',
      'wachtwoord',
      'web',
      'west',
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
      'wpadmin',
      'wpmafia',
      'writer',
      'writing',

      'xanadu',
      'xavier',
      'ximena',
      'ximenita',
      'xmagico',
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

    $server_name = $_SERVER['SERVER_NAME'];

    if( ! empty( $server_name ) ) {

      if( strpos( $server_name , '.' ) === false ) {

        $weak_password_list[] = $server_name;

      } else {

        $server_name_arr = explode( '.' , $server_name );

        $weak_password_list[] = $server_name_arr[0];

      }

    }

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

      $patterns[] = array(
        'pattern' => sprintf( '^[0-9]+%s$' , $word ),
        'after_delimiter' => 'i',
      );

      $patterns[] = array(
        'pattern' => sprintf( '^[0-9]+%s[0-9]+$' , $word ),
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

      '/__/__/wp-config_php',

      'testwork',
      'spanskys_filecontent',

      'url../../../../wp-config.php',

    );

    $blacklist_get_data_name_list = apply_filters( 'mywp_lockout_blacklist_get_data_name_list' , $blacklist_get_data_name_list );

    return $blacklist_get_data_name_list;

  }

  public static function get_blacklist_get_data_value_list() {

    $blacklist_get_data_value_list = array(

      '../wp-config.php',
      '../../wp-config.php',
      '../../../wp-config.php',
      '../../../../wp-config.php',
      '/boot.ini\0',
      '/boot.ini%0',
      '/etc',
      '/etc/passwd',
      '/etc/passwd\0',
      '/etc/passwd%0',
      '/windows/win.ini\0',
      '/windows/win.ini%0',
      '/winnt/win.ini\0',
      '/winnt/win.ini%0',
      '<script>alert(/openvas-xss-test/)</script>',

      "die('===!'.'===');",
      "die(\'===!\'.\'===\');",
      "die(\\\\\'===!\\\\\'.\\\\\'===\\\\\');",
      "die(\'z!a\'.\'x\');",
      "die(\\\\\'z!a\\\\\'.\\\\\'x\\\\\');",

      'wp-config.php',

    );

    $blacklist_get_data_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_value_list' , $blacklist_get_data_value_list );

    return $blacklist_get_data_value_list;

  }

  public static function get_blacklist_get_data_name_value_list() {

    $blacklist_get_data_name_value_list = array(

      '_wrapper_format' => 'drupal_ajax',

      'author' => '0',

      'Command' => 'GetFolders',

      'dataType' => 'ApplyChanges',
      'DD' => 'ASNIAOUFMOMZJIBD',

      'font' => '/../../../../caches/bakup/default/0<<.sql',

      'gf_page' => 'upload',

      'iIDcat' => '\'',

      'login' => 'x2a5xB',
      'login' => 'cmd',
      'login' => 'safe',
      'lol' => '1',

      'mod' => 'publisher',
      'mstshash' => 'Administr',

      'page' => '/etc/passwd',

      'ref' => 'marketopia',

      '_wrapper_format' => 'drupal_ajax',

      'yt' => 'echo',

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
      'die(md5(34563));',
      "die(\'z!a\'.\'x\');",
      "die(\\\\\'z!a\\\\\'.\\\\\'x\\\\\');",

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

  public static function get_blacklist_file_data_name_list() {

    $blacklist_file_data_name_list = array(
    );

    $blacklist_file_data_name_list = apply_filters( 'mywp_lockout_blacklist_file_data_name_list' , $blacklist_file_data_name_list );

    return $blacklist_file_data_name_list;

  }

  public static function get_blacklist_file_data_value_list() {

    $blacklist_file_data_value_list = array(

      'bp.phtml',

      'cb.php.php',

      'db.php',

    );

    $blacklist_file_data_value_list = apply_filters( 'mywp_lockout_blacklist_file_data_value_list' , $blacklist_file_data_value_list );

    return $blacklist_file_data_value_list;

  }

}

endif;
