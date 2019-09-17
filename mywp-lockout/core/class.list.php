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
      sprintf( '[login]%s' , date( 'Y' ) ),

      '0x1999',
      '1{nigoL}',
      '123{nigoL}',
      '123123a',
      '12qwaszx',
      '12345q',
      '1234qwer',
      '123qwe',
      '13pass',
      '1qaz',
      '1q2w',
      '1q2w3e',
      '1q2w3e4r',
      '1q2w3e4r5t',
      '1,12E 11',
      '67i5jjjt',

      'a12345678',
      'a123powerx-*',
      'aaaaaa',
      'admin',
      'admin@123',
      'adminadmin',
      'administrador',
      'administrator',
      'abc',
      'akushare',
      'alessandra',
      'alex',
      'alexandru',
      'ali',
      'andromeda',
      'apple',
      'arif',
      'asdasd',
      'asdqwe123',
      'author',

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
      'chris',
      'cliente',
      'computer',
      'counter',
      'csaaihex',

      'daniel',
      'dave',
      'david',
      'developer',
      'demo',
      'demouser',
      'dragon',

      'editor',
      'elena',
      'emre',

      'first',
      'F*uckYou',

      'gfhjkm',
      'gimboroot',
      'guest',

      'hanhqcled',
      'hannah',
      'hasbuzz',
      'hello',
      'henkondernemer',
      'hunter',

      'iloveyou',
      'index',
      'indoxploit',
      'info',
      'ines',
      'iyfm',

      'jason',
      'jerome',
      'jesse',
      'john',

      'K98pWjiSq',
      'karine',
      'kiki',

      'letmein',
      'lfplhfgthvf',
      'logitech89',
      'love',

      'm@rcos',
      'magda',
      'mercedes',
      'merlin',
      'mexeltech',
      'maria',
      'marketing',
      'marta',
      'master',
      'matrix',
      'mercury',
      'michelle',
      'migration',
      'mihai',
      'miranda',
      'monkey',
      'myname',


      'NULL',

      'oliver',
      'operator',
      'oshibe',

      'p@$$w0rd',
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
      'preview',
      'prince',
      'prova',
      'prueba',

      'q1q1q1',
      'q1w2e3',
      'q1w2e3r4',
      'q1w2e3r4t5',
      'qaz',
      'qq123',
      'qwe',
      'qweqwe',
      'qwsxza',
      'qwertyuiop',

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
      'ssadmin',
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
      'temp',
      'temp!',
      'test',
      'teste',
      'tester',
      'testing',
      'testtest',
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

      'ultimate',
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
      'webmaster',
      'west',
      'westside',
      'whatever',
      'white',
      'wibowo',
      'william',
      'willow',
      'winston',
      'wiesenhof',
      'wolf',
      'women',
      'work',
      'wpadmin',
      'wpengine',
      'wpmafia',
      'writer',
      'writing',
      'www',

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

      '_escaped_fragment_',

      '/__/__/wp-config_php',

      'abc11',

      'bataboom',

      'config_prefer_imagemagick',
      'cperpage',

      'spanskys_filecontent',
      'swp_debug',

      'testwork',

      'url../../../../wp-config.php',

      'xxxxxxxxxxxx_loads',

      'yp_remote_get',

      'zzz',

    );

    $blacklist_get_data_name_list = apply_filters( 'mywp_lockout_blacklist_get_data_name_list' , $blacklist_get_data_name_list );

    return $blacklist_get_data_name_list;

  }

  public static function get_blacklist_get_data_value_list() {

    $blacklist_get_data_value_list = array(

      '/etc',

      'Attacker',

      'com_fabrik',
      'com_jdownloads',

      'mk_check_filemanager_php_syntax',
      'mk_file_folder_manager',
      'module/newsletters/news',

      'ppom_upload_file',
      'printerInfo',

      'testActivation',

      'um_fileupload',
      'user/password',

    );

    $blacklist_get_data_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_value_list' , $blacklist_get_data_value_list );

    return $blacklist_get_data_value_list;

  }

  public static function get_blacklist_get_data_name_value_list() {

    $blacklist_get_data_name_value_list = array(

      'Command' => 'GetFolders',

      'dataType' => 'ApplyChanges',
      'DD' => 'ASNIAOUFMOMZJIBD',

      'email' => 'yourmail@gmail.com',

      'font' => '/../../../../caches/bakup/default/0<<.sql',

      'gf_page' => 'upload',

      'iIDcat' => '\'',

      'key' => 'testActivated',

      'login' => 'x2a5xB',
      'lol' => '1',

      'mod' => 'publisher',
      'mstshash' => 'Administr',

      'ref' => 'marketopia',

      'x' => '',

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

  public static function get_blacklist_get_data_find_value_list() {

    $blacklist_get_data_find_value_list = array(

      '<script>',
      '/db.php',
      '/configuration.php',

      'base64_decode(',
      'boot.ini',

      'curl ',
      'chmod 777',

      'die(',
      '@DEFAULT_MEMBER_ACCESS',
      'drupal_aja',

      'echo(',
      'etc/passwd',
      'eval(',

      'getInputStream()',

      'HelloThinkPHP',
      'HttpServletResponse',

      'shell_exec',

      'wget ',
      'windows/win.ini',
      'winnt/win.ini',
      'wp-config.php',

    );

    $blacklist_get_data_find_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_find_value_list' , $blacklist_get_data_find_value_list );

    return $blacklist_get_data_find_value_list;

  }

  public static function get_blacklist_post_data_name_list() {

    $blacklist_post_data_name_list = array(

      'cp_set_user',

      'fuckyou',

      'itongtong',

      'pwidget',

      'spanskys_filecontent',

      'theendding',

      'x0x0',
      'xxeerr',
      'xxxxxxxxxxxx_loads',

    );

    $blacklist_post_data_name_list = apply_filters( 'mywp_lockout_blacklist_post_data_name_list' , $blacklist_post_data_name_list );

    return $blacklist_post_data_name_list;

  }

  public static function get_blacklist_post_data_value_list() {

    $blacklist_post_data_value_list = array(

      'cHJpbnQobWQ1KDMyNDIzNCkpOw==',
      'cs_employer_ajax_profile',
      'cs_reset_pass',

      'die(pi()*42);',
      'die(md5(34563));',
      'die(\'z!a\'.\'x\');',
      'die(\\\\\'z!a\\\\\'.\\\\\'x\\\\\');',

      'echo(344444443+1);',

      'kiwi_social_share_set_option',

      'print(md5(11111));',

      'td_ajax_update_panel',
      'td_mod_register',
      'thim_update_theme_mods',

      'wpgdprc_process_action',

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

  public static function get_blacklist_post_data_find_value_list() {

    $blacklist_get_data_find_value_list = array(

      ';eval',
      '.php#.jpg',


      'die(',
      'fromCharCode',

      'location.href',

    );

    $blacklist_get_data_find_value_list = apply_filters( 'mywp_lockout_blacklist_get_data_find_value_list' , $blacklist_get_data_find_value_list );

    return $blacklist_get_data_find_value_list;

  }

  public static function get_blacklist_file_data_name_list() {

    $blacklist_file_data_name_list = array(
      '301_bulk_redirects',
    );

    $blacklist_file_data_name_list = apply_filters( 'mywp_lockout_blacklist_file_data_name_list' , $blacklist_file_data_name_list );

    return $blacklist_file_data_name_list;

  }

  public static function get_blacklist_file_data_value_list() {

    $blacklist_file_data_value_list = array(
    );

    $blacklist_file_data_value_list = apply_filters( 'mywp_lockout_blacklist_file_data_value_list' , $blacklist_file_data_value_list );

    return $blacklist_file_data_value_list;

  }

  public static function get_blacklist_file_extension_list() {

    $blacklist_file_extension_list = array(

      'cgi',

      'php',
      'phtml',

      'sh',

    );

    $blacklist_file_extension_list = apply_filters( 'mywp_lockout_blacklist_file_extension_list' , $blacklist_file_extension_list );

    return $blacklist_file_extension_list;

  }

  public static function get_blacklist_uri_find_list() {

    $blacklist_uri_find_list = array(

      '<script>',
      '</script>',
      '@eval',

    );

    $blacklist_uri_find_list = apply_filters( 'mywp_lockout_blacklist_uri_find_list' , $blacklist_uri_find_list );

    return $blacklist_uri_find_list;

  }

}

endif;
