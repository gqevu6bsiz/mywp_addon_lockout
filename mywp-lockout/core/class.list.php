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

  public static function get_denylist_action_value_list() {

    $denylist_action_value_list = array(

      'ae-sync-user',
      'async_litespeed',

      'br-aapf-setup',

      'captainform_new_users_connect',
      'cs_employer_ajax_profile',
      'cs_reset_pass',

      'door123',
      'download_from_files_617_fileupload',

      'eael_get_token',
      'epsilon_dashboard_ajax_callback',
      'epsilon_framework_ajax_action',
      'epsilon_framework_ajax_action',
      'extensive_vc_init_shortcode_pagination',

      'flatsome_load_instagram',
      'fusion_form_update_view',

      'htmega_ajax_register',

      'inpost_gallery_get_gallery',
      'iws_gff_fetch_states',
      'iva_bh_import_ajax_action',
      'iva_bh_ajax_action',
      'iva_bhl_ajax_action',

      'jb-upload-company-logo',
      'jsticket_ajax',

      'kb_process_advanced_form_submit',
      'kiwi_social_share_get_option',
      'kiwi_social_share_set_option',

      'lfb_upload_form',
      'likebtn_prx',
      'listingo_temp_uploader',
      'lp_cc_addons_actions',

      'me_upload_demo',
      'mk_file_folder_manager',
      'mk_check_filemanager_php_syntax',

      'nf_ajax_submit',

      'p3dlite_handle_upload',
      'porte_plume_previsu',
      'pp_ajax_signup',
      'ppom_upload_file',
      'printerInfo',

      'rms_ping_from_the_universe',

      'sb_fav_ad',
      'sb_login_user_with_otp',
      'shopp_upload_file',
      'showbiz_ajax_action',
      'sif_upload_file',
      'startklar_drop_zone_upload_process',
      'stm_listing_profile_edit',
      'stm_lms_register',

      'tb_optin_subscribe',
      'td_ajax_fb_login_get_credentials',
      'td_ajax_fb_login_user',
      'td_ajax_search',
      'td_ajax_update_panel',
      'td_mod_register',
      'tdb_user_form_on_submit',
      'theplus_google_ajax_register',
      'thim_login_ajax',
      'thim_update_theme_mods',
      'tve_login_submit',

      'ufaq_search',
      'um_fileupload',
      'um_remove_file',
      'uploadFontIcon',

      'vibebp_register_user',

      'wc_nf_submit',
      'wccs_upload_file_func',
      'wcfm_login_popup_submit',
      'welcome_screen_ajax_callback',
      'wp_ajax_rsvp-form',
      'wpfm_save_file_data',
      'wpgdprc_process_action',
      'wpim_manage_settings',
      'wpjobportal_ajax_popup_action',
      'wplms_register_user',
      'wps_membership_csv_file_upload',
      'wpsm_responsive_coming_soon',
      'wpsp_upload_attachment',
      'wpr_addons_upload_file',
      'wpuf_file_upload',
      'wpw_auto_poster_map_wordpress_post_type',

    );

    $denylist_action_value_list = apply_filters( 'mywp_lockout_denylist_action_value_list' , $denylist_action_value_list );

    return $denylist_action_value_list;

  }

  public static function get_denylist_search_value_list() {

    $denylist_search_value_list = array(

      '😜',
      '🤤',
      '🤔',
      '😍',
      '😓',
      '😏',
      '🤮',
      '😞',
      '🤐',
      '🤨',
      '😎',
      '🐾',
      '🐧',
      '🧞',
      '🧵',
      '🧳',
      '🧉',
      '⭐',
      '🔆',
      '🕞',
      '🚼',
      '🚺',
      '⬛',
      '🩸',
      '📥',
      '🕥',
      '🍓',
      '☕',
      '🧷',
      '🟤',
      '☢',
      '💈',
      '🥉',
      '💒',
      '🧚',
      '📗',
      '⛴',
      '✏',
      '🔤',
      '🍚',
      '🔇',
      '🖼',
      '🧸',
      '🦹',
      '📹',
      '🥕',
      '🦎',
      '🚶',
      '⌛',
      '🆖',
      '🦘',
      '🕊',
      '🏘',
      '🔦',
      '☀',
      '👈',
      '▛',
      '▟',
      '➥',
      '⇛',
      '➽',
      '➤',
      '➠',
      '⏩',
      '⮆',
      '▶',
      '🡄',
      '⇚',
      '🢪',
      '⬅',
      '🠰',
      '⮘',
      '⏪',
      '⮄',
      '◀',

      '【✔️推荐',
      '【✔️推薦',
      '【✔️官網',
      '【✔️官网',

      '黑卡',
      '撲克',
      '麻将',
      '麻將',
      '賭場',
      '赌博',
      '賽馬',
      '賭馬',
      '赌球',
      '博彩',
      '賭王',
      '赌场',
      '賠率',
      '直播',
      '转盘',
      '彩票',
      '老虎機',
      '老虎机',
      '娛樂城',
      '娱乐城',
      '百家乐',
      '百家樂',
      '輪盤的',
      '投注網',
      '扑克牌',
      '競彩網',
      '決勝21點',
      '德州扑克',
      '福利彩票',
      '现金娱乐',
      '澳门博彩业',
      '拉斯维加斯',
      '拉斯維加斯',
      '网上娱乐场',
      '網絡博彩遊戲',

      'คาสิโนออน์',
      'เว็บสล็อต',

    );

    $denylist_search_value_list = apply_filters( 'mywp_lockout_denylist_search_value_list' , $denylist_search_value_list );

    return $denylist_search_value_list;

  }

  public static function get_denylist_get_data_name_list() {

    $denylist_get_data_name_list = array(

      '_escaped_fragment_',
      '/__/__/wp-config_php',
      '-Menu-',
      '$fallback_url',

      'a__foo_var/owa/?',
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
      'doors-test-article',

      'GOTMLS_scan',

      'https://ya.ru',

      'lgkfghdfh',

      'mla_stream_file',

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
      '${system(cat',
      '%{233*233}',

      '1name=alex',

      'alert(xss)>',
      'ajax/render/widget_php',
      'app/config/parameters.yml',
      'AsAjfkel!@',
      'Attacker',

      'call_user_func_array',
      'com_fabrik',
      'com_jdownloads',
      'config/frontend_dev.php',
      'config/services.yaml',

      'dupl.txt',

      'ELISQLREPORTS-settings',
      'EnigmaCyberSecurity',
      'EWD-UFAQ-Options',
      'EWD_UFAQ_UpdateOptions',
      'exif.SwitchDetailMode',

      'getJSONExportTable',

      'initiationready',

      'M;O=D',
      'mec_fes_form',
      'module/newsletters/news',

      'named.conf',

      'OpenMarket/Xcelerate/Admin/WebReferences',
      'ovalxnmai.php',

      'qlaoxk',

      'rdfgdfgdfg',
      'reads.html',

      'semalt.com',
      'servicesshdisable',
      'set_db_option',
      'setNewIconShare',
      'sssspaincett3',

      'testActivation',
      'tf2rghf.jpg',

      'user/password',

      'var_dump',

      'wp-fixer.php',

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
      'fuck' => 'you',

      'gf_page' => 'upload',

      'iIDcat' => '\'',

      'key' => 'testActivated',

      'login' => 'x2a5xB',
      'lol' => '1',

      'mod' => 'publisher',
      'mstshash' => 'Administr',

      'userfile' => 'config.xml',

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

      '\\x5Cpasswd',

      ',concat(',
      ',concat_ws(',
      ',(SELECT/',
      ',unhex(',
      '\'alert(',
      '\' AnD',
      ';\\\'',
      '; AND 1=',
      ';_exec_master_dbo_xp_dirtree_',
      ';alert(',
      ';base64,',
      //';echo ',
      '<!>usin prompt(',
      '<!--[if ',
      '<?php ',
      '<?xml version=',
      '<body+x=\\\'',
      '<script>',
      '><script',
      '/><svg/',
      '<svg/onload=confirm(',
      '/bin/sh',
      '/configuration.php',
      '/db.php',
      '/DBMS_PIPE.RECEIVE_MESSAGE(',
      '/etc/shadow',
      '/hosts',
      '/tmp/ohhellohttpserver',
      '/usr/bin/id',
      '{customerDownloadableProducts { ',
      '{endif-A}',
      '{{interactsh-url}}',
      '../../etc',
      '../../tmp',
      '../../usr',
      '../../var',
      '.Dockerfile',
      '.my.cnf',
      '=alert(',
      '=php://input',
      '--exec=`',
      '(select_load_file(',
      ') and 1=',
      ') OR ',
      ') or(1=',
      '${{<%[}}%',
      '${jndi',
      '${script:javascript',
      '@DEFAULT_MEMBER_ACCESS',
      '@evil.corp',
      '@GrabResolver(',
      '@1337.com',
      '||(select_extractvalue(',
      '|rev\0.zip',
      '??????????',
      '?jdyscene=chat',
      ' ORDER BY ',
      ' print chr(',

      'allow_url_fopen',
      'app/etc/local.xm',
      'auto_prepend_file=php://input',

      'base64_decode(',
      'blackjack',
      'boot.ini',

      'config_db.php',
      'curl ',
      'chmod 777',

      'die(',
      'download exam materials',
      'drupal_aja',

      'echo(',
      'echo${IFS}',
      'echo voip\\',
      'etc/passwd',
      'etc\\\\passwd',
      'eval(',

      'fb_pdf\\\'',
      'fgdfgdf',
      'file_get_contents(',
      'file_put_contents(',
      'fn_sqlvarbasetostr(',
      'fromCharCode(',
      'from%20wp_users%20where',

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
      'kostenlosen Download zu erhalten',

      'localconfig.xml',
      'logicielreferencement.com',

      'macau slot',
      'monogooglelinux.com',
      'multimon.cgi',

      'nd_options_option_value',
      'newdumpspdf.com',
      'newmax.click',

      'Öffnen Sie die Webseite',

      'phpinfo',
      'phpunit.xsd',
      'poker atlas',
      'poker festival',
      'print(',

      'QQQQQQQ',

      'rdfgdfgdfg',

      'select unhex',
      'shell_exec',
      'SELECT ',
      'select ',
      'select*',
      'SELECT%',
      'select(',
      'seven card stud',
      'slavepoker',

      'TomcatBypass/Command',

      'union+select+',
      'union all select',
      ' unhex(',
      'updatexml(',
      'uploadpictureBase64.html',

      'WAITFOR DELAY ',
      'wget ',
      '`wget',
      'win.ini',
      'Windows/system.ini',
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

      'ger3imopdfm0sdf',

      'ibk_restore_migrate_action',
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

      '88slotgame.com',

      'androxgh0st',

      'biagiotti_membership_check_facebook_user',

      'cHJpbnQobWQ1KDMyNDIzNCkpOw==',

      'die(pi()*42);',
      'die(md5(34563));',
      'die(\'z!a\'.\'x\');',
      'die(\\\\\'z!a\\\\\'.\\\\\'x\\\\\');',
      'doctreat_award_temp_file_uploader',

      'echo(344444443+1);',

      'get_currentRef',

      'Hoping to connect re texting',

      'loginchallengeresponse1requestbody',

      'onlinecasino',
      'onlinepoker',

      'print(md5(11111));',

      'workreap_temp_uploader',
      'wp-zzz',

      '바카라',

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

    $denylist_post_data_find_value_list = array(

      '; exec master.dbo.xp_dirtree',
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

      '-1 union select',

      '234234234234',

      'Altcoins ',
      'anarchy99',
      'Antibiotics Buy Online',

      'BACCARAT EVO',
      'Best of Ali',
      'bettop88.com',
      'bettop888.com',
      'bp_caver_avatar_handle_upload',
      'Buy quality dofollow links from us',

      'Casino Consumers',
      'Casino online',
      'Casino Player',
      'cialis ',

      'die(',
      'delta 8 CBD',

      'finasteride ',
      'fromCharCode',

      'GameSlot',

      'h-t-t-p-s(:)',
      'hererfees.txt',
      'Here is a list of 18 free traffic sources ',

      'I am sending you my intimate photos',

      'information_schema',

      'janc0xsec',

      'levitra ',
      'location.href',

      'Make a 10,000% return',
      'maeng da gold',
      'movecasinoin.com',

      'Negative SEO Services',

      'priligy',

      ' shell_exec',
      'SLOT ONLINE ',
      'slotbet',
      'slotcomment.com',
      'SLOTPG',
      'slotsonline',

      'trazodone oral suspension',

      'UNION SELECT ',

      'wp_ajax_try_2020_v2',

      'zithromax ',

      '메이저카지노',
      '온라인바카라',
      '카지노사이트',

      'займы без процентов',

    );

    $denylist_post_data_find_value_list = apply_filters( 'mywp_lockout_denylist_post_data_find_value_list' , $denylist_post_data_find_value_list );

    return $denylist_post_data_find_value_list;

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
      'RxRpytrd.ph$p',
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

      '</script>',
      '<script>',
      '<x>',
      '}}).then',
      '{jndi:',
      ').getMethod(',
      '@eval',
      '@die',
      '="prompt(',
      '=sysdate(',
      '=wget ',
      '=8\'\'&',
      ';curl ',
      'Set-Cookie:param=',

      '22c\'abikn',

      'java.lang.Runtime',
      'javax.script.ScriptEngineManager',
      'JMXConfigurator',

      'LanDhcpServerRpm',

      'onerror=alert(',
      'org.jenkinsci.plugins.github.config.GitHubTokenCredentialsCreator/createTokenByPassword',

      'Program Files',

      'Set-Cookie:CRLFInjection',

      '/TomcatBypass/Command/Base64/',

      'union select ',

      'WanDynamicIpCfgRpm',

    );

    $denylist_uri_find_list = apply_filters( 'mywp_lockout_denylist_uri_find_list' , $denylist_uri_find_list );

    return $denylist_uri_find_list;

  }

}

endif;
