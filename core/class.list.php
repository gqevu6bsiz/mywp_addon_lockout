<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutList' ) ) :

final class MywpLockoutList {

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
      'admin_lin',
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

  public static function get_denylist_get_data_name_list() {

    $denylist_get_data_name_list = array(

      '_escaped_fragment_',
      '/__/__/wp-config_php',
      '-Menu-',
      '$fallback_url',

      'aam-media',
      'abc11',
      'alg_wc_ev_verify_email',
      'asavsdvds',

      'bataboom',

      'cGluZw==',
      'config_prefer_imagemagick',
      'cperpage',

      'daksldlkdsadas',
      'do_reset_wordpress',

      'GOTMLS_scan',

      'https://ya.ru',

      'lgkfghdfh',

      'semalt_com',
      'smt_download_export_file',
      'spanskys_filecontent',
      'swp_debug',
      'sysCmd',

      'testingfsoc',
      'testwork',

      'url../../../../wp-config.php',

      'wdbgppb',
      'wppcp-security-settings-page',
      'wppcp-settings',

      'x_uv_o_debug',
      'xxnew2018_url1',
      'xxxxxxxxxxxx_loads',

      'yp_remote_get',

      'zzz',

    );

    $denylist_get_data_name_list = apply_filters( 'mywp_lockout_denylist_get_data_name_list' , $denylist_get_data_name_list );

    return $denylist_get_data_name_list;

  }

  public static function get_denylist_get_data_value_list() {

    $denylist_get_data_value_list = array(

      '/etc',
      '/imam.zoneh.php',
      ';wget',
      '../index.php',
      '../WEB-IN',
      '../WEB-INF',
      '_ning_upload_image',
      '{{$smarty.version}}',
      '{{3 *\\\\\\\'4726531\\\\\\\'}}',
      '${2334-1}',
      '%{233*233}',

      '1name=alex',

      'ae-sync-user',
      'alert(xss)>',
      'ajax/render/widget_php',
      'AsAjfkel!@',
      'Attacker',

      'br-aapf-setup',

      'call_user_func_array',
      'captainform_new_users_connect',
      'com_fabrik',
      'com_jdownloads',

      'dupl.txt',

      'epsilon_dashboard_ajax_callback',
      'epsilon_framework_ajax_action',
      'EWD-UFAQ-Options',
      'EWD_UFAQ_UpdateOptions',
      'exif.SwitchDetailMode',

      'getJSONExportTable',

      'https://ya.ru',

      'initiationready',

      'lfb_upload_form',
      'lp_cc_addons_actions',

      'M;O=D',
      'mk_check_filemanager_php_syntax',
      'mk_file_folder_manager',
      'module/newsletters/news',

      'named.conf',

      'ppom_upload_file',
      'printerInfo',

      'qlaoxk',

      'rdfgdfgdfg',
      'reads.html',
      'rms_ping_from_the_universe',

      'semalt.com',
      'servicesshdisable',
      'set_db_option',
      'setNewIconShare',
      'sif_upload_file',
      'sssspaincett3',
      'stm_listing_profile_edit',

      'testActivation',
      'tf2rghf.jpg',

      'ufaq_search',
      'um_fileupload',
      'uploadFontIcon',
      'user/password',

      'var_dump',

      'wccs_upload_file_func',
      'wcfm_login_popup_submit',
      'welcome_screen_ajax_callback',
      'wp-fixer.php',
      'wpfm_save_file_data',
      'wpim_manage_settings',
      'wpsm_responsive_coming_soon',
      'wpuf_file_upload',
      'www.exp2redir2.com',

      'yuzo-related-post',

    );

    $denylist_get_data_value_list = apply_filters( 'mywp_lockout_denylist_get_data_value_list' , $denylist_get_data_value_list );

    return $denylist_get_data_value_list;

  }

  public static function get_denylist_get_data_name_value_list() {

    $denylist_get_data_name_value_list = array(

      'Command' => 'GetFolders',
      'cs_email' => '1@1',

      'dataType' => 'ApplyChanges',
      'DD' => 'ASNIAOUFMOMZJIBD',

      'email' => 'yourmail@gmail.com',
      'enpl' => 'OEhIJw==',

      'font' => '/../../../../caches/bakup/default/0<<.sql',

      'gf_page' => 'upload',

      'iIDcat' => '\'',

      'key' => 'testActivated',

      'login' => 'x2a5xB',
      'lol' => '1',

      'mod' => 'publisher',
      'mstshash' => 'Administr',

      'x' => '',
      'XDEBUG_SESSION_START' => 'phpstorm',

      'yt' => 'echo',

    );

    $denylist_get_data_name_value_list = apply_filters( 'mywp_lockout_denylist_get_data_name_value_list' , $denylist_get_data_name_value_list );

    return $denylist_get_data_name_value_list;

  }

  public static function get_denylist_get_data_name_pattern_list() {

    $patterns = array();

    $denylist_get_data_name_list = self::get_denylist_get_data_name_list();

    foreach( $denylist_get_data_name_list as $word ) {

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

    $denylist_get_data_name_pattern_list = array();

    foreach( $patterns as $pattern ) {

      $denylist_get_data_name_pattern_list[] = wp_parse_args( $pattern , $default );

    }

    $denylist_get_data_name_pattern_list = apply_filters( 'mywp_lockout_denylist_get_data_name_pattern_list' , $denylist_get_data_name_pattern_list );

    return $denylist_get_data_name_pattern_list;

  }

  public static function get_denylist_get_data_find_value_list() {

    $denylist_get_data_find_value_list = array(

      ';\\\'',
      ';alert(',
      '<script>',
      '<?xml version=',
      '><script',
      '/bin/sh',
      '/configuration.php',
      '/db.php',
      '/etc/shadow',
      ' and ',
      '\' AnD',
      '/hosts',
      '{endif-A}',
      '.my.cnf',
      '=alert(/',
      '%2522%2529%2520',
      '@DEFAULT_MEMBER_ACCESS',
      '@evil.corp',
      '@1337.com',

      'allow_url_fopen',
      'app/etc/local.xm',

      'base64_decode(',
      'boot.ini',

      'config_db.php',
      'curl ',
      'chmod 777',

      'die(',
      'drupal_aja',

      'echo(',
      'echo voip\\',
      'etc/passwd',
      'etc\\\\passwd',
      'eval(',

      'fb_pdf\\\'',
      'fgdfgdf',
      'file_get_contents(',
      'file_put_contents(',
      'fromCharCode(',

      'getInputStream()',

      'hellorconfig',
      'HelloThinkPHP',
      'HttpServletResponse',

      'iblock/04f/32/accesson.txt',
      'invokefunction',
      'iva_bh_ajax_action',

      'java.lang.Runtime',
      'javascript:alert(',

      'kjhgfdkjhgfd',

      'logicielreferencement.com',

      'monogooglelinux.com',

      'nd_options_option_value',

      ' ORDER BY ',

      'phpinfo',
      'phpunit.xsd',
      'print(',

      'QQQQQQQ',

      'rdfgdfgdfg',

      'select unhex',
      'shell_exec',
      'SELECT ',
      'select ',
      'SELECT%',

      'union+select+',
      'union all select',
      ' unhex(',
      'uploadpictureBase64.html',

      'WAITFOR DELAY ',
      'wget ',
      '`wget',
      'win.ini',
      'wp-config.php',

      'ymwears.cn',

    );

    $denylist_get_data_find_value_list = apply_filters( 'mywp_lockout_denylist_get_data_find_value_list' , $denylist_get_data_find_value_list );

    return $denylist_get_data_find_value_list;

  }

  public static function get_denylist_post_data_name_list() {

    $denylist_post_data_name_list = array(

      '$config',
      '_ning_upload_image',

      'asavsdvds',

      'cp_set_user',

      'fack',
      'fuckyou',

      'itongtong',

      'pwidget',

      'search_pinger',
      'spanskys_filecontent',

      'theendding',

      'wuev_form_type',

      'x0x0',
      'xxeerr',
      'xxxxxxxxxxxx_loads',

    );

    $denylist_post_data_name_list = apply_filters( 'mywp_lockout_denylist_post_data_name_list' , $denylist_post_data_name_list );

    return $denylist_post_data_name_list;

  }

  public static function get_denylist_post_data_value_list() {

    $denylist_post_data_value_list = array(

      'androxgh0st',

      'cHJpbnQobWQ1KDMyNDIzNCkpOw==',
      'cs_employer_ajax_profile',
      'cs_reset_pass',

      'die(pi()*42);',
      'die(md5(34563));',
      'die(\'z!a\'.\'x\');',
      'die(\\\\\'z!a\\\\\'.\\\\\'x\\\\\');',

      'echo(344444443+1);',
      'epsilon_framework_ajax_action',

      'get_currentRef',

      'Hoping to connect re texting',

      'kiwi_social_share_get_option',
      'kiwi_social_share_set_option',

      'loginchallengeresponse1requestbody',
      'lp_cc_addons_actions',

      'mk_file_folder_manager',

      'pp_ajax_signup',
      'print(md5(11111));',

      'rms_ping_from_the_universe',

      'td_ajax_search',
      'td_ajax_update_panel',
      'td_mod_register',
      'theplus_google_ajax_register',
      'thim_login_ajax',
      'thim_update_theme_mods',

      'um_fileupload',
      'um_remove_file',

      'wp-zzz',
      'wpgdprc_process_action',
      'wpsp_upload_attachment',

    );

    $denylist_post_data_value_list = apply_filters( 'mywp_lockout_denylist_post_data_value_list' , $denylist_post_data_value_list );

    return $denylist_post_data_value_list;

  }

  public static function get_denylist_post_data_name_value_list() {

    $denylist_post_data_name_value_list = array(

      'pf' => 'hrehnrhknvor',

    );

    $denylist_post_data_name_value_list = apply_filters( 'mywp_lockout_denylist_post_data_name_value_list' , $denylist_post_data_name_value_list );

    return $denylist_post_data_name_value_list;

  }

  public static function get_denylist_post_data_name_pattern_list() {

    $patterns = array();

    $denylist_post_data_name_list = self::get_denylist_post_data_name_list();

    foreach( $denylist_post_data_name_list as $word ) {

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

    $denylist_post_data_name_pattern_list = array();

    foreach( $patterns as $pattern ) {

      $denylist_post_data_name_pattern_list[] = wp_parse_args( $pattern , $default );

    }

    $denylist_post_data_name_pattern_list = apply_filters( 'mywp_lockout_denylist_post_data_name_pattern_list' , $denylist_post_data_name_pattern_list );

    return $denylist_post_data_name_pattern_list;

  }

  public static function get_denylist_post_data_find_value_list() {

    $denylist_get_data_find_value_list = array(

      ';eval',
      '.php#.jpg',
      '[nd_booking_option_value]',
      '[nd_donations_option_value]',
      '[nd_donations_value_import_settings]',
      '[nd_learning_option_value]',
      '[nd_options_option_value]',
      '[nd_stats_value_import_settings]',
      '[nd_stats_option_value]',
      '[nd_travel_option_value]',
      ' -e /bin/sh',
      '>>>>>>>>>>',

      '234234234234',

      'Altcoins ',
      'anarchy99',

      'Best of Ali',
      'Buy quality dofollow links from us',

      'Casino Consumers',
      'cialis ',

      'die(',
      'delta 8 CBD',

      'finasteride ',
      'fromCharCode',

      'hererfees.txt',
      'Here is a list of 18 free traffic sources ',

      'information_schema',

      'janc0xsec',

      'levitra ',
      'location.href',

      'Make a 10,000% return',
      'maeng da gold',

      'Negative SEO Services',

      'priligy',

      ' shell_exec',

      'trazodone oral suspension',

      'UNION SELECT ',

      'wp_ajax_try_2020_v2',

      'zithromax ',

    );

    $denylist_get_data_find_value_list = apply_filters( 'mywp_lockout_denylist_get_data_find_value_list' , $denylist_get_data_find_value_list );

    return $denylist_get_data_find_value_list;

  }

  public static function get_denylist_file_data_name_list() {

    $denylist_file_data_name_list = array(
      '301_bulk_redirects',
    );

    $denylist_file_data_name_list = apply_filters( 'mywp_lockout_denylist_file_data_name_list' , $denylist_file_data_name_list );

    return $denylist_file_data_name_list;

  }

  public static function get_denylist_file_data_value_list() {

    $denylist_file_data_value_list = array(
    );

    $denylist_file_data_value_list = apply_filters( 'mywp_lockout_denylist_file_data_value_list' , $denylist_file_data_value_list );

    return $denylist_file_data_value_list;

  }

  public static function get_denylist_file_extension_list() {

    $denylist_file_extension_list = array(

      'cgi',

      'php',
      'phtml',

      'sh',

    );

    $denylist_file_extension_list = apply_filters( 'mywp_lockout_denylist_file_extension_list' , $denylist_file_extension_list );

    return $denylist_file_extension_list;

  }

  public static function get_denylist_uri_find_list() {

    $denylist_uri_find_list = array(

      '<script>',
      '</script>',
      '}}).then',
      '@eval',
      '@die',

      '22c\'abikn',

    );

    $denylist_uri_find_list = apply_filters( 'mywp_lockout_denylist_uri_find_list' , $denylist_uri_find_list );

    return $denylist_uri_find_list;

  }

}

endif;
