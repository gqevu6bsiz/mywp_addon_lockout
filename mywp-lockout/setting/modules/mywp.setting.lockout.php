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
      'use_advance' => false,
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

    $action_name = MywpSetting::get_ajax_action_name( self::$id , 'check_latest' );

    if( empty( $_POST[ $action_name ] ) ) {

      return false;

    }

    check_ajax_referer( $action_name , $action_name );

    if( is_multisite() ) {

      if( ! MywpLockoutApi::is_network_manager() ) {

        return false;

      }

    } else {

      if( ! MywpLockoutApi::is_manager() ) {

        return false;

      }

    }

    delete_site_transient( 'mywp_lockout_updater' );
    delete_site_transient( 'mywp_lockout_updater_remote' );

    $is_latest = MywpControllerModuleLockoutUpdater::is_latest();

    if( is_wp_error( $is_latest ) ) {

      wp_send_json_error( array( 'error' => $is_latest->get_error_message() ) );

    }

    if( ! $is_latest ) {

      wp_send_json_success( array( 'is_latest' => 0 ) );

    } else {

      wp_send_json_success( array( 'is_latest' => 1 , 'message' => sprintf( '<p>%s</p>' , '<span class="dashicons dashicons-yes"></span> ' . __( 'Using a latest version.' , 'mywp-lockout' ) ) ) );

    }

  }

  public static function mywp_current_setting_screen_content() {

    $setting_data = self::get_setting_data();

    ?>
    <h3 class="mywp-setting-screen-subtitle"><?php _e( 'Settings' ); ?></h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <?php _e( 'Lockout time' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $val = false; ?>
            <?php if( ! empty( $setting_data['expire_timeout'] ) ) : ?>
              <?php $val = $setting_data['expire_timeout']; ?>
            <?php endif; ?>
            <input type="number" name="mywp[data][expire_timeout]" class="small-text expire_timeout" id="expire_timeout" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( '10' ); ?>" /> <?php _e( 'Minute' ); ?>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Weak password lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['weak_password_lockout'] ) ) : ?>
              <?php $checked = $setting_data['weak_password_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][weak_password_lockout]" class="weak_password_lockout" id="weak_password_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Specific GET data lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['specific_get_lockout'] ) ) : ?>
              <?php $checked = $setting_data['specific_get_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][specific_get_lockout]" class="specific_get_lockout" id="specific_get_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Specific POST data lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['specific_post_lockout'] ) ) : ?>
              <?php $checked = $setting_data['specific_post_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][specific_post_lockout]" class="specific_post_lockout" id="specific_post_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Specific FILES data lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['specific_file_lockout'] ) ) : ?>
              <?php $checked = $setting_data['specific_file_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][specific_file_lockout]" class="specific_file_lockout" id="specific_file_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Specific URI lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['specific_uri_lockout'] ) ) : ?>
              <?php $checked = $setting_data['specific_uri_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][specific_uri_lockout]" class="specific_uri_lockout" id="specific_uri_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Unknown plugins/themes access lockout' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['unknown_plugin_theme_lockout'] ) ) : ?>
              <?php $checked = $setting_data['unknown_plugin_theme_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][unknown_plugin_theme_lockout]" class="unknown_plugin_theme_lockout" id="unknown_plugin_theme_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Weak password validate' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['week_password_validate'] ) ) : ?>
              <?php $checked = $setting_data['week_password_validate']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][week_password_validate]" class="week_password_validate" id="week_password_validate" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Activate' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Already lockout early action' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['already_early_lockout'] ) ) : ?>
              <?php $checked = $setting_data['already_early_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][already_early_lockout]" class="already_early_lockout" id="already_early_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php _e( 'Early action lockout' , 'mywp-lockout' ); ?>
            </label>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Slow response' , 'mywp-lockout' ); ?>
          </th>
          <td>
            <?php $number = 0; ?>
            <?php if( ! empty( $setting_data['sleep_lockout'] ) ) : ?>
              <?php $number = intval( $setting_data['sleep_lockout'] ); ?>
            <?php endif; ?>
            <label>
              <input type="number" name="mywp[data][sleep_lockout]" class="sleep_lockout small-text" id="sleep_lockout" value="<?php echo esc_attr( $number ); ?>" />
              <?php _e( 'Seconds' , 'mywp-lockout' ); ?>
            </label>
            <code>
              <?php _e( 'Max seconds' , 'mywp-lockout' ); ?>: <?php printf( __( '%s seconds' ) , MywpLockoutApi::get_max_lockout_seconds() ); ?>
            </code>
            <p class="mywp-description">
              <span class="dashicons dashicons-lightbulb"></span>
              <?php _e( 'Max seconds is setting to max_execution_time on php.ini.' , 'mywp-lockout' ); ?>
            </p>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Email me whenever' ); ?>
          </th>
          <td>
            <label>
              <?php $checked = false; ?>
              <?php if( ! empty( $setting_data['send_mail'] ) ) : ?>
                <?php $checked = true; ?>
              <?php endif; ?>
              <input type="checkbox" name="mywp[data][send_mail]" class="send_mail" id="send_mail" value="1" <?php checked( $checked , true ); ?> />
              <?php _e( 'Send email when Locked out' , 'mywp-lockout' ); ?>
            </label>
            <div id="send-mail-option">
              <p>
                <?php _e( 'From' , 'mywp-lockout' ); ?>:
                <code><?php echo MywpLockoutApi::get_send_from_email(); ?></code>
              </p>
              <p>
                <?php _e( 'To' , 'mywp-lockout' ); ?>:
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
                  <?php _e( 'Send with Input data' , 'mywp-lockout' ); ?>
                </label>
              </p>
              <p>
                <label>
                  <?php $checked = false; ?>
                  <?php if( ! empty( $setting_data['send_email_with_server'] ) ) : ?>
                    <?php $checked = true; ?>
                  <?php endif; ?>
                  <input type="checkbox" name="mywp[data][send_email_with_server]" class="send_email_with_server" id="send_email_with_server" value="1" <?php checked( $checked , true ); ?> />
                  <?php _e( 'Send with Server data' , 'mywp-lockout' ); ?>
                </label>
              </p>
            </div>
          </td>
        </tr>
        <tr>
          <th>
            <?php _e( 'Lockout page' , 'mywp-lockout' ); ?>
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

    $have_latest = false;

    if( ! is_wp_error( $is_latest ) && ! $is_latest ) {

      $have_latest = MywpControllerModuleLockoutUpdater::get_latest();

    }

    $plugin_info = MywpLockoutApi::plugin_info();

    $class_have_latest = '';

    if( $have_latest ) {

      $class_have_latest = 'have-latest';

    }

    ?>
    <p>&nbsp;</p>
    <h3><?php _e( 'Plugin info' , 'my-wp' ); ?></h3>
    <table class="form-table <?php echo esc_attr( $class_have_latest ); ?>" id="version-check-table">
      <tbody>
        <tr>
          <th><?php printf( __( 'Version %s' ) , '' ); ?></th>
          <td>
            <code><?php echo MYWP_LOCKOUT_VERSION; ?></code>
            <a href="<?php echo esc_url( $plugin_info['github'] ); ?>" target="_blank" class="button button-primary link-latest"><?php printf( __( 'Get Version %s' ) , $have_latest ); ?></a>
            <p class="already-latest"><span class="dashicons dashicons-yes"></span> <?php _e( 'Using a latest version.' , 'mywp-lockout' ); ?></p>
            <br />
          </td>
        </tr>
        <tr>
          <th><?php _e( 'Check latest' , 'mywp-lockout' ); ?></th>
          <td>
            <button type="button" id="check-latest-version" class="button button-secondary check-latest"><span class="dashicons dashicons-update"></span> <?php _e( 'Check latest version' , 'mywp-lockout' ); ?></button>
            <span class="spinner"></span>
            <div id="check-latest-result"></div>
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
    #send-mail-option {
      display: none;
      padding: 20px;
      margin: 20px 0 0 0;
      background: #fff;
    }
    #send-mail-option.active {
      display: block;
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

      var is_send_mail_checked = $('#send_mail').prop('checked');

      if( is_send_mail_checked ) {

        send_mail_option( true );

      }

      function send_mail_option( is_true = false ) {

        var $send_email_option = $('#send-mail-option');

        if( is_true ) {

          $send_email_option.addClass('active');

        } else {

          $send_email_option.removeClass('active');

        }

      }

      $('#send_mail').on('click', function() {

        send_mail_option( $(this).prop('checked') );

      });

      $('#check-latest-version').on('click', function() {

        var $version_check_table = $(this).parent().parent().parent().parent();

        $version_check_table.addClass('checking');

        PostData = {
          action: '<?php echo MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ); ?>',
          <?php echo MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ); ?>: '<?php echo wp_create_nonce( MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) ); ?>'
        };

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: PostData
        }).done( function( xhr ) {

          if( typeof xhr !== 'object' || xhr.success === undefined ) {

            $version_check_table.removeClass('checking');

            alert( '<?php _e( 'An error has occurred. Please reload the page and try again.' ); ?>' );

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

          alert( '<?php _e( 'An error has occurred. Please reload the page and try again.' ); ?>' );

          return false;

        });

      });

    });
    </script>
    <?php

  }

  public static function mywp_current_setting_post_data_format_update( $formatted_data ) {

    $mywp_model = self::get_model();

    if( empty( $mywp_model ) ) {

      return $formatted_data;

    }

    $new_formatted_data = $mywp_model->get_initial_data();

    $new_formatted_data['advance'] = $formatted_data['advance'];

    if( ! empty( $formatted_data['expire_timeout'] ) ) {

      $new_formatted_data['expire_timeout'] = intval( $formatted_data['expire_timeout'] );

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

    if( ! empty( $formatted_data['already_early_lockout'] ) ) {

      $new_formatted_data['already_early_lockout'] = true;

    }

    if( ! empty( $formatted_data['sleep_lockout'] ) ) {

      $new_formatted_data['sleep_lockout'] = intval( $formatted_data['sleep_lockout'] );

    }

    if( ! empty( $formatted_data['send_mail'] ) ) {

      $new_formatted_data['send_mail'] = true;

    }

    if( ! empty( $formatted_data['send_to_email'] ) ) {

      $formatted_data['send_to_email'] = preg_replace( '/\s+/' , '' , $formatted_data['send_to_email'] );
      $formatted_data['send_to_email'] = strip_tags( $formatted_data['send_to_email'] );

      if( strpos( $formatted_data['send_to_email'] , ',' ) === false ) {

        if( is_email( $formatted_data['send_to_email'] ) ) {

          $new_formatted_data['send_to_email'] = $formatted_data['send_to_email'];

        }

      } else {

        $emails = explode( ',' , $formatted_data['send_to_email'] );

        foreach( $emails as $key => $email ) {

          if( ! is_email( $email ) ) {

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

      $new_formatted_data['lockout_page'] = wp_unslash( $formatted_data['lockout_page'] );

    }

    return $new_formatted_data;

  }

}

MywpSettingScreenLockout::init();

endif;
