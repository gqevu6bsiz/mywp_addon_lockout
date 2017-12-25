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

    if( is_multisite() ) {

      $setting_menus[ self::$menu ] = array(
        'menu_title' => __( 'Lockout' , 'mywp-lockout' ),
        'multiple_screens' => false,
        'network' => true,
      );

    } else {

      $setting_menus[ self::$menu ] = array(
        'menu_title' => __( 'Lockout' , 'mywp-lockout' ),
        'multiple_screens' => false,
      );

    }

    return $setting_menus;

  }

  public static function mywp_setting_screens( $setting_screens ) {

    if( is_multisite() ) {

      $setting_screens[ self::$id ] = array(
        'title' => __( 'Lockout' , 'mywp-lockout' ),
        'menu' => self::$menu,
        'controller' => 'lockout',
        'use_advance' => false,
      );

    } else {

      $setting_screens[ self::$id ] = array(
        'title' => __( 'Lockout' , 'mywp-lockout' ),
        'menu' => self::$menu,
        'controller' => 'lockout',
        'use_advance' => false,
      );

    }

    return $setting_screens;

  }

  public static function mywp_ajax() {

    if( ! MywpApi::is_manager() ) {

      return false;

    }

    add_action( 'wp_ajax_' . MywpSetting::get_ajax_action_name( self::$id , 'check_latest' ) , array( __CLASS__ , 'check_latest' ) );

  }

  public static function check_latest() {

    $action_name = MywpSetting::get_ajax_action_name( self::$id , 'check_latest' );

    if( empty( $_POST[ $action_name ] ) ) {

      return false;

    }

    check_ajax_referer( $action_name , $action_name );

    delete_site_transient( 'lockout_updater' );
    delete_site_transient( 'lockout_updater_remote' );

    $is_latest = MywpControllerModuleLockoutUpdater::is_latest();

    if( is_wp_error( $is_latest ) ) {

      wp_send_json_error( array( 'error' => $is_latest->get_error_message() ) );

    }

    if( ! $is_latest ) {

      wp_send_json_success( array( 'is_latest' => 0 ) );

    } else {

      wp_send_json_success( array( 'is_latest' => 1 ) );

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
            <?php _e( 'Specific also GET data lockout' , 'mywp-lockout' ); ?>
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
            <?php _e( 'Specific also POST data lockout' , 'mywp-lockout' ); ?>
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
            <?php _e( 'Email me whenever' ); ?>
          </th>
          <td>
            <label>
              <?php $checked = false; ?>
              <?php if( ! empty( $setting_data['send_mail'] ) ) : ?>
                <?php $checked = true; ?>
              <?php endif; ?>
              <input type="checkbox" name="mywp[data][send_mail]" class="send_mail" id="send_mail" value="1" <?php checked( $checked , true ); ?> />
              <?php _e( 'Sending email when Locked out.' , 'mywp-lockout' ); ?>
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
                  <?php _e( 'Sending with Input data.' , 'mywp-lockout' ); ?>
                </label>
              </p>
              <p>
                <label>
                  <?php $checked = false; ?>
                  <?php if( ! empty( $setting_data['send_email_with_server'] ) ) : ?>
                    <?php $checked = true; ?>
                  <?php endif; ?>
                  <input type="checkbox" name="mywp[data][send_email_with_server]" class="send_email_with_server" id="send_email_with_server" value="1" <?php checked( $checked , true ); ?> />
                  <?php _e( 'Sending with Server data.' , 'mywp-lockout' ); ?>
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

    $latest = MywpControllerModuleLockoutUpdater::get_latest();

    if( ! empty( $latest ) or is_wp_error( $latest ) ) {

      $latest = false;

    }

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
    <h3><?php _e( 'Plugin info' , 'mywp-lockout' ); ?></h3>
    <table class="form-table <?php echo esc_attr( $class_have_latest ); ?>" id="version-check-table">
      <tbody>
        <tr>
          <th><?php printf( __( 'Version %s' ) , '' ); ?></th>
          <td>
            <code><?php echo MYWP_LOCKOUT_VERSION; ?></code>
          </td>
        </tr>
        <tr>
          <th><?php _e( 'Latest' ); ?></th>
          <td>
            <code><?php echo MywpControllerModuleLockoutUpdater::get_latest(); ?></code>
            <br />
            <button type="button" id="check-latest-version" class="button button-secondary check-latest"><span class="dashicons dashicons-update"></span> <?php _e( 'Check again latest version' , 'mywp-lockout' ); ?></button>
            <span class="spinner"></span>
          </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>
            <a href="<?php echo esc_url( $plugin_info['github'] ); ?>" target="_blank" class="button button-primary link-latest">
              <span class="dashicons dashicons-admin-plugins"></span> <?php _e( 'Get latest version' , 'mywp-lockout' ); ?>
            </a>
            &nbsp;
            <a href="<?php echo esc_url( $plugin_info['document_url'] ); ?>" class="button button-secondary" target="_blank">
              <span class="dashicons dashicons-book"></span> <?php _e( 'Document' , 'mywp-lockout' ); ?>
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

        alert( '<?php _e( 'An error has occurred. Please reload the page and try again.' ); ?>' );

        $version_check_table.removeClass('checking');

        return false;

      }

      if( ! xhr.success ) {

        alert( xhr.data.error );

        $version_check_table.removeClass('checking');

        return false;

      }

      if( xhr.data.is_latest ) {

        alert( '<?php _e( 'Using a latest version.' , 'mywp-lockout' ); ?>' );

        $version_check_table.removeClass('checking');

        return false;

      }

      location.reload();

      return true;

    }).fail( function( xhr ) {

      alert( '<?php _e( 'An error has occurred. Please reload the page and try again.' ); ?>' );

      $version_check_table.removeClass('checking');

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

    if( ! empty( $formatted_data['specific_get_lockout'] ) ) {

      $new_formatted_data['specific_get_lockout'] = true;

    }

    if( ! empty( $formatted_data['specific_post_lockout'] ) ) {

      $new_formatted_data['specific_post_lockout'] = true;

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
