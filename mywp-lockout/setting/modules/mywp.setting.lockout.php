<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpAbstractSettingModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpSettingScreenLockout' ) ) :

final class MywpSettingScreenLockout extends MywpAbstractSettingModule {

  static protected $id = 'lockout';

  static protected $priority = 50;

  static private $menu = 'add_on_lockout';

  public static function mywp_setting_menus( $setting_menus ) {

    $setting_menus[ self::$menu ] = array(
      'menu_title' => __( 'Lockout' , 'mywp-lockout' ),
      'multiple_screens' => false,
    );

    if( is_multisite() ) {

      $setting_menus[ self::$menu ]['network'] = true;

    }

    return $setting_menus;

  }

  public static function mywp_setting_screens( $setting_screens ) {

    $plugin_info = MywpLockoutApi::plugin_info();

    $setting_screens[ self::$id ] = array(
      'title' => __( 'Lockout' , 'mywp-lockout' ),
      'menu' => self::$menu,
      'controller' => 'lockout',
      'use_advance' => true,
      'document_url' => $plugin_info['document_url'],
    );

    return $setting_screens;

  }

  public static function mywp_ajax_manager() {

    if( is_multisite() ) {

      if( ! MywpLockoutApi::is_network_manager() ) {

        return false;

      }

    }

    add_action( 'wp_ajax_' . MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) , array( __CLASS__ , 'check_latest' ) );

  }

  public static function check_latest() {

    $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

    $action_name = MywpSetting::get_ajax_action_name( self::$id , 'check_latest' );

    if( ! isset( $_POST[ $action_name ] ) ) {

      return false;

    }

    check_ajax_referer( $action_name , $action_name );

    delete_site_transient( 'mywp_lockout_updater' );

    delete_site_transient( 'mywp_lockout_updater_remote' );

    $is_latest = MywpControllerModuleLockoutUpdater::is_latest();

    if( is_wp_error( $is_latest ) ) {

      wp_send_json_error( array( 'error' => $is_latest->get_error_message() ) );

    }

    $results = array(
      'is_latest' => 0,
      'message' => '',
    );

    if( ! $is_latest ) {

      wp_send_json_success( $results );

    } else {

      $results['is_latest'] = 1;

      $results['message'] = sprintf( '<p><span class="dashicons dashicons-yes"></span> %s</p>' , esc_html( __( 'Using a latest version.' , 'mywp-lockout' ) ) );

      wp_send_json_success( $results );

    }

  }

  public static function mywp_current_setting_screen_content() {

    $setting_data = self::get_setting_data();

    $lockout_targets = array(
      'weak_password_lockout' => array(
        'title' => __( 'Weak password when login' , 'mywp-lockout' ),
      ),
      'specific_get_lockout' => array(
        'title' => __( 'Specific get($_GET) request' , 'mywp-lockout' ),
      ),
      'specific_post_lockout' => array(
        'title' => __( 'Specific post($_POST) request' , 'mywp-lockout' ),
      ),
      'specific_file_lockout' => array(
        'title' => __( 'Specific upload file($_FILES) request' , 'mywp-lockout' ),
      ),
      'specific_uri_lockout' => array(
        'title' => __( 'Specific URI' , 'mywp-lockout' ),
      ),
      'unknown_plugin_theme_lockout' => array(
        'title' => __( 'Unknown plugins/themes access lockout' , 'mywp-lockout' ),
      ),
    );

    $ip_gets = array(
      'x_forwarded_for' => 'HTTP_X_FORWARDED_FOR',
      'cf_connecting_ip' => 'HTTP_CF_CONNECTING_IP',
    );

    ?>
    <h3 class="mywp-setting-screen-subtitle">
      <?php echo esc_html( __( 'Settings' ) ); ?>
    </h3>

    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <?php echo esc_html( __( 'Lockout time' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php $val = false; ?>

            <?php if( ! empty( $setting_data['expire_timeout'] ) ) : ?>

              <?php $val = $setting_data['expire_timeout']; ?>

            <?php endif; ?>
            <input type="number" name="mywp[data][expire_timeout]" class="small-text expire_timeout" id="expire_timeout" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( '10' ); ?>" min="0" />
            <?php echo esc_html( __( 'Minute' ) ); ?><br />
            <p class="mywp-description">
              <span class="dashicons dashicons-lightbulb"></span>
              <?php echo esc_html( __( 'If set to 0 minute or blank, the time limit lockout will not be applied.' , 'mywp-lockout' ) ); ?>
            </p>
            <div id="already-early-lockout" class="lockout-time-option-fields">
              <p>
                <?php $checked = false; ?>

                <?php if( ! empty( $setting_data['already_early_lockout'] ) ) : ?>

                  <?php $checked = $setting_data['already_early_lockout']; ?>

                <?php endif; ?>
                <label>
                  <input type="checkbox" name="mywp[data][already_early_lockout]" class="already_early_lockout" id="already_early_lockout" value="1" <?php checked( $checked , 1 ); ?> />
                  <?php echo esc_html( __( 'Lockout at an early action hook(mywp_setup_theme)' , 'mywp-lockout' ) ); ?>
                </label>
              </p>
            </div>
            <div id="ip-get" class="lockout-time-option-fields">
              <p>
                <?php echo esc_html( __( 'How to get an IP address' , 'mywp-lockout' ) ); ?>:
                <select name="mywp[data][ip_get]" class="ip_get small-text" id="ip_get">
                  <option value=""><?php echo esc_html( 'REMOTE_ADDR' ); ?></option>
                  <?php foreach( $ip_gets as $ip_get_key => $ip_get_label ) : ?>

                    <option value="<?php echo esc_attr( $ip_get_key ); ?>" <?php selected( $ip_get_key , $setting_data['ip_get'] ); ?>>
                      <?php echo esc_html( $ip_get_label ); ?>
                    </option>

                  <?php endforeach; ?>

                </select>
              </p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <p>&nbsp;</p>

    <h3 class="mywp-setting-screen-subtitle">
      <?php echo esc_html( __( 'Lockout Targets' , 'mywp-lockout' ) ); ?>
    </h3>

    <table class="form-table">
      <tbody>

        <?php foreach( $lockout_targets as $lockout_target_id => $lockout_target ) : ?>

          <tr>
            <th>
              <?php echo esc_html( $lockout_target['title'] ); ?>
            </th>
            <td>
              <?php $checked = false; ?>

              <?php if( ! empty( $setting_data[ $lockout_target_id ] ) ) : ?>

                <?php $checked = $setting_data[ $lockout_target_id ]; ?>

              <?php endif; ?>
              <label>
                <input type="checkbox" name="mywp[data][<?php echo esc_attr( $lockout_target_id ); ?>]" class="<?php echo esc_attr( $lockout_target_id ); ?>" id="<?php echo esc_attr( $lockout_target_id ); ?>" value="1" <?php checked( $checked , 1 ); ?> />
                <?php echo esc_html( __( 'Lockout' , 'mywp-lockout' ) ); ?>
              </label>
            </td>
          </tr>

        <?php endforeach; ?>

      </tbody>
    </table>

    <p>&nbsp;</p>

    <h3 class="mywp-setting-screen-subtitle">
      <?php echo esc_html( __( 'Other' , 'mywp-lockout' ) ); ?>
    </h3>

    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <?php echo esc_html( __( 'Weak password validate when account register' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php $checked = false; ?>

            <?php if( ! empty( $setting_data['week_password_validate'] ) ) : ?>

              <?php $checked = $setting_data['week_password_validate']; ?>

            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][week_password_validate]" class="week_password_validate" id="week_password_validate" value="1" <?php checked( $checked , 1 ); ?> />
              <?php echo esc_html( __( 'Activate' ) ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php echo esc_html( __( 'Comment form validate and lockout' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php $checked = false; ?>

            <?php if( ! empty( $setting_data['comment_validate_lockout'] ) ) : ?>

              <?php $checked = $setting_data['comment_validate_lockout']; ?>

            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][comment_validate_lockout]" class="comment_validate_lockout" id="comment_validate_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php echo esc_html( __( 'Activate' ) ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php echo esc_html( __( 'Slow response' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php $number = 0; ?>

            <?php if( ! empty( $setting_data['sleep_lockout'] ) ) : ?>

              <?php $number = (int) $setting_data['sleep_lockout']; ?>

            <?php endif; ?>
            <label>
              <input type="number" name="mywp[data][sleep_lockout]" class="sleep_lockout small-text" id="sleep_lockout" value="<?php echo esc_attr( $number ); ?>" min="0" />
              <?php echo esc_html( __( 'Seconds' , 'mywp-lockout' ) ); ?>
            </label>
            <code>
              <?php echo esc_html( __( 'Max seconds' , 'mywp-lockout' ) ); ?>:
              <?php echo esc_html( sprintf( __( '%s seconds' ) , MywpLockoutApi::get_max_lockout_seconds() ) ); ?>
            </code>
            <br />
            <p class="mywp-description">
              <span class="dashicons dashicons-lightbulb"></span>
              <?php echo esc_html( __( 'Max seconds is setting to max_execution_time on php.ini.' , 'mywp-lockout' ) ); ?>
            </p>
          </td>
        </tr>
        <tr>
          <th>
            <?php echo esc_html( __( 'Email me whenever' ) ); ?>
          </th>
          <td>
            <label>
              <?php $checked = false; ?>

              <?php if( ! empty( $setting_data['send_mail'] ) ) : ?>

                <?php $checked = true; ?>

              <?php endif; ?>
              <input type="checkbox" name="mywp[data][send_mail]" class="send_mail" id="send_mail" value="1" <?php checked( $checked , true ); ?> />
              <?php echo esc_html( __( 'Send an email when locked out' , 'mywp-lockout' ) ); ?>
            </label>
            <div id="send-mail-option" class="send-mail-option-fields">
              <p>
                <?php echo esc_html( __( 'From' , 'mywp-lockout' ) ); ?>:
                <code><?php echo esc_html( MywpLockoutApi::get_send_from_email() ); ?></code>
              </p>
              <p>
                <?php echo esc_html( __( 'To' , 'mywp-lockout' ) ); ?>:
                <?php $val = false; ?>

                <?php if( ! empty( $setting_data['send_to_email'] ) ) : ?>

                  <?php $val = $setting_data['send_to_email']; ?>

                <?php endif; ?>
                <input type="text" name="mywp[data][send_to_email]" class="regular-text send_to_email" id="send_to_email" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( 'example@example.com,two@example.com...' ); ?>" />
              </p>
              <p>
                <label>
                  <?php $checked = false; ?>

                  <?php if( ! empty( $setting_data['send_email_with_input'] ) ) : ?>

                    <?php $checked = true; ?>

                  <?php endif; ?>
                  <input type="checkbox" name="mywp[data][send_email_with_input]" class="send_email_with_input" id="send_email_with_input" value="1" <?php checked( $checked , true ); ?> />
                  <?php echo esc_html( __( 'Send with Input data' , 'mywp-lockout' ) ); ?>
                </label>
              </p>
              <p>
                <label>
                  <?php $checked = false; ?>

                  <?php if( ! empty( $setting_data['send_email_with_server'] ) ) : ?>

                    <?php $checked = true; ?>

                  <?php endif; ?>
                  <input type="checkbox" name="mywp[data][send_email_with_server]" class="send_email_with_server" id="send_email_with_server" value="1" <?php checked( $checked , true ); ?> />
                  <?php echo esc_html( __( 'Send with Server data' , 'mywp-lockout' ) ); ?>
                </label>
              </p>
            </div>
          </td>
        </tr>
        <tr>
          <th>
            <?php echo esc_html( __( 'Lockout page' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php wp_editor( $setting_data['lockout_page'] , 'lockout_page' , array( 'textarea_name' => 'mywp[data][lockout_page]' , 'textarea_rows' => 5 ) ); ?>
          </td>
        </tr>
      </tbody>
    </table>

    <p>&nbsp;</p>

    <?php

  }

  public static function mywp_current_setting_screen_after_footer() {

    $is_latest = MywpControllerModuleLockoutUpdater::is_latest();

    $latest_version = false;

    if( ! is_wp_error( $is_latest ) ) {

      if( ! $is_latest ) {

        $latest_version = MywpControllerModuleLockoutUpdater::get_latest();

      }

    }

    $plugin_info = MywpLockoutApi::plugin_info();

    $class_latest_version = '';

    if( $latest_version ) {

      $class_latest_version = 'have-latest';

    }

    ?>

    <p>&nbsp;</p>

    <h3>
      <?php echo esc_html( __( 'Plugin info' , 'my-wp' ) ); ?>
    </h3>

    <table class="form-table <?php echo esc_attr( $class_latest_version ); ?>" id="version-check-table">
      <tbody>
        <tr>
          <th><?php echo esc_html( sprintf( __( 'Version %s' ) , '' ) ); ?></th>
          <td>
            <code><?php echo esc_html( MYWP_LOCKOUT_VERSION ); ?></code>
            <a href="<?php echo esc_url( $plugin_info['github'] ); ?>" target="_blank" class="button button-primary link-latest"><?php echo esc_html( sprintf( __( 'Get Version %s' ) , $latest_version )); ?></a>
            <p class="already-latest">
              <span class="dashicons dashicons-yes"></span>
              <?php echo esc_html( __( 'Using a latest version.' , 'mywp-lockout' ) ); ?>
            </p>
          </td>
        </tr>
        <tr>
          <th><?php echo esc_html( __( 'Check latest' , 'mywp-lockout' ) ); ?></th>
          <td>
            <button type="button" id="check-latest-version" class="button button-secondary check-latest">
              <span class="dashicons dashicons-update"></span>
              <?php echo esc_html( __( 'Check latest version' , 'mywp-lockout' ) ); ?>
            </button>
            <span class="spinner"></span>
            <div id="check-latest-result"></div>
          </td>
        </tr>
        <tr>
          <th><?php echo esc_html( __( 'Documents' , 'my-wp' ) ); ?></th>
          <td>
            <a href="<?php echo esc_url( $plugin_info['document_url'] ); ?>" class="button button-secondary" target="_blank">
              <span class="dashicons dashicons-book"></span>
              <?php echo esc_html( __( 'Documents' , 'my-wp' ) ); ?>
            </a>
          </td>
        </tr>
      </tbody>
    </table>

    <p>&nbsp;</p>

    <?php

  }

  public static function mywp_current_admin_print_footer_scripts() {

    ?>
    <style>
    .lockout-time-option-fields,
    .send-mail-option-fields {
      padding: 20px;
      margin: 20px 0 0 0;
      background: #fff;
      opacity: 0.3;
    }
    .lockout-time-option-fields.active,
    .send-mail-option-fields.active {
      opacity: 1;
    }
    #version-check-table .spinner {
      visibility: hidden;
    }
    #version-check-table.checking .spinner {
      visibility: visible;
    }
    #version-check-table .link-latest {
      margin-left: 12px;
      display: none;
    }
    #version-check-table .already-latest {
      display: inline-block;
    }
    #version-check-table .check-latest {
    }
    #version-check-table.have-latest .link-latest {
      display: inline-block;
    }
    #version-check-table.have-latest .already-latest {
      display: none;
    }
    </style>
    <script>
    jQuery(document).ready(function($){

      let $expire_timeout = $('#expire_timeout');

      let expire_timeout_min = $expire_timeout.val();

      let $lockout_time_option_fields = $('.lockout-time-option-fields');

      let $send_mail = $('#send_mail');

      let is_send_mail_checked = $send_mail.prop('checked');

      let $send_mail_option_fields = $('.send-mail-option-fields');

      if( expire_timeout_min !== '' && expire_timeout_min > 0 ) {

        lockout_time_option_fields( true );

      }

      function lockout_time_option_fields( is_true = false ) {

        if( is_true ) {

          $lockout_time_option_fields.each( function( index, el ) {

            $(el).addClass('active');

          });

        } else {

          $lockout_time_option_fields.each( function( index, el ) {

            $(el).removeClass('active');

          });

        }

      }

      $expire_timeout.on('change', function() {

        if( $(this).val() === '' || $(this).val() < 1 ) {

          lockout_time_option_fields();

        } else {

          lockout_time_option_fields( true );

        }

      });

      if( is_send_mail_checked ) {

        send_mail_option_fields( true );

      }

      function send_mail_option_fields( is_true = false ) {

        if( is_true ) {

          $send_mail_option_fields.addClass('active');

        } else {

          $send_mail_option_fields.removeClass('active');

        }

      }

      $send_mail.on('click', function() {

        send_mail_option_fields( $(this).prop('checked') );

      });

      $('#check-latest-version').on('click', function() {

        let $version_check_table = $(this).parent().parent().parent().parent();

        $version_check_table.addClass('checking');

        let PostData = {
          action: '<?php echo esc_js( MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) ); ?>',
          <?php echo esc_js( MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) ); ?>: '<?php echo esc_js( wp_create_nonce( MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) ) ); ?>'
        };

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: PostData
        }).done( function( xhr ) {

          if( typeof xhr !== 'object' || xhr.success === undefined ) {

            $version_check_table.removeClass('checking');

            alert( mywp_admin_setting.unknown_error_reload_page );

            return false;

          }

          if( ! xhr.success ) {

            $version_check_table.removeClass('checking');

            alert( xhr.data.error );

            return false;

          }

          if( xhr.data.is_latest ) {

            $('#check-latest-result').html( xhr.data.message );

            $version_check_table.removeClass('checking');

            return false;

          }

          location.reload();

          return true;

        }).fail( function( xhr ) {

          $version_check_table.removeClass('checking');

          alert( mywp_admin_setting.unknown_error_reload_page );

          return false;

        });

      });

    });
    </script>
    <?php

  }

  public static function mywp_current_setting_post_data_format_update( $formatted_data ) {

    $called_text = sprintf( '%s::%s( $formatted_data )' , __CLASS__ , __FUNCTION__ );

    $mywp_model = self::get_model();

    if( empty( $mywp_model ) ) {

      return $formatted_data;

    }

    $new_formatted_data = $mywp_model->get_initial_data();

    $new_formatted_data['advance'] = $formatted_data['advance'];

    if( ! empty( $formatted_data['expire_timeout'] ) ) {

      $new_formatted_data['expire_timeout'] = MywpHelper::sanitize_number( $formatted_data['expire_timeout'] );

    }

    if( ! empty( $formatted_data['already_early_lockout'] ) ) {

      $new_formatted_data['already_early_lockout'] = true;

    }

    if( ! empty( $formatted_data['ip_get'] ) ) {

      $new_formatted_data['ip_get'] = MywpHelper::sanitize_text( $formatted_data['ip_get'] );

    }

    if( ! empty( $formatted_data['sleep_lockout'] ) ) {

      $new_formatted_data['sleep_lockout'] = MywpHelper::sanitize_number( $formatted_data['sleep_lockout'] );

    }

    if( ! empty( $formatted_data['weak_password_lockout'] ) ) {

      $new_formatted_data['weak_password_lockout'] = true;

    }

    if( ! empty( $formatted_data['specific_get_lockout'] ) ) {

      $new_formatted_data['specific_get_lockout'] = true;

    }

    if( ! empty( $formatted_data['specific_post_lockout'] ) ) {

      $new_formatted_data['specific_post_lockout'] = true;

    }

    if( ! empty( $formatted_data['specific_file_lockout'] ) ) {

      $new_formatted_data['specific_file_lockout'] = true;

    }

    if( ! empty( $formatted_data['specific_uri_lockout'] ) ) {

      $new_formatted_data['specific_uri_lockout'] = true;

    }

    if( ! empty( $formatted_data['unknown_plugin_theme_lockout'] ) ) {

      $new_formatted_data['unknown_plugin_theme_lockout'] = true;

    }

    if( ! empty( $formatted_data['week_password_validate'] ) ) {

      $new_formatted_data['week_password_validate'] = true;

    }

    if( ! empty( $formatted_data['comment_validate_lockout'] ) ) {

      $new_formatted_data['comment_validate_lockout'] = true;

    }

    if( ! empty( $formatted_data['send_mail'] ) ) {

      $new_formatted_data['send_mail'] = true;

    }

    if( ! empty( $formatted_data['send_to_email'] ) ) {

      $send_to_email = preg_replace( '/\s+/' , '' , $formatted_data['send_to_email'] );

      if( strpos( $send_to_email , ',' ) === false ) {

        $new_formatted_data['send_to_email'] = MywpHelper::sanitize_email( $send_to_email );

      } else {

        $emails = explode( ',' , $send_to_email );

        foreach( $emails as $key => $email ) {

          $email = MywpHelper::sanitize_email( $email );

          if( empty( $email ) ) {

            unset( $emails[ $key ] );

          }

        }

        if( ! empty( $emails ) ) {

          $new_formatted_data['send_to_email'] = implode( ',' , $emails );

        }

      }

    }

    if( ! empty( $formatted_data['send_email_with_input'] ) ) {

      $new_formatted_data['send_email_with_input'] = true;

    }

    if( ! empty( $formatted_data['send_email_with_server'] ) ) {

      $new_formatted_data['send_email_with_server'] = true;

    }

    if( ! empty( $formatted_data['lockout_page'] ) ) {

      $new_formatted_data['lockout_page'] = wp_kses_post( $formatted_data['lockout_page'] );

    }

    return $new_formatted_data;

  }

}

MywpSettingScreenLockout::init();

endif;
