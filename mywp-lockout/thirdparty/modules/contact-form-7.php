<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpThirdpartyAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpLockoutThirdpartyModuleContactForm7' ) ) :

final class MywpLockoutThirdpartyModuleContactForm7 extends MywpThirdpartyAbstractModule {

  protected static $id = 'contact-form-7';

  protected static $base_name = 'contact-form-7/wp-contact-form-7.php';

  protected static $name = 'Contact Form 7';

  protected static function after_init() {

    add_filter( 'mywp_controller_initial_data_' . 'lockout' , array( __CLASS__ , 'mywp_controller_initial_data' ) );

    add_filter( 'mywp_controller_default_data_' . 'lockout' , array( __CLASS__ , 'mywp_controller_default_data' ) );

  }

  public static function mywp_controller_initial_data( $initial_data ) {

    $initial_data['contact_form_7_validate_lockout'] = '';

    return $initial_data;

  }

  public static function mywp_controller_default_data( $default_data ) {

    $default_data['contact_form_7_validate_lockout'] = false;

    return $default_data;

  }

  public static function mywp_init() {

    add_filter( 'mywp_setting_post_data_format_'. 'lockout' . '_update' , array( __CLASS__ , 'mywp_setting_post_data_format_update' ) , 10 , 2 );

    add_action( 'mywp_setting_screen_advance_content_' . 'lockout' , array( __CLASS__ , 'mywp_setting_screen_advance_content' ) );

    add_action( 'mywp_wp_loaded' , array( __CLASS__ , 'mywp_wp_loaded' ) );

  }

  public static function mywp_setting_post_data_format_update( $formatted_data , $post_data ) {

    if( ! empty( $post_data['contact_form_7_validate_lockout'] ) ) {

      $formatted_data['contact_form_7_validate_lockout'] = true;

    }

    return $formatted_data;

  }

  public static function mywp_setting_screen_advance_content() {

    $setting_data = MywpLockoutApi::get_lockout_setting_data();

    ?>

    <h3 class="mywp-setting-screen-subtitle"><?php echo esc_html( __( 'Other' , 'mywp-lockout' ) ); ?></h3>

    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <?php echo esc_html( __( 'Contact form 7 form validate and lockout' , 'mywp-lockout' ) ); ?>
          </th>
          <td>
            <?php $checked = false; ?>
            <?php if( ! empty( $setting_data['contact_form_7_validate_lockout'] ) ) : ?>
              <?php $checked = $setting_data['contact_form_7_validate_lockout']; ?>
            <?php endif; ?>
            <label>
              <input type="checkbox" name="mywp[data][contact_form_7_validate_lockout]" class="contact_form_7_validate_lockout" id="contact_form_7_validate_lockout" value="1" <?php checked( $checked , 1 ); ?> />
              <?php echo esc_html( __( 'Activate' ) ); ?>
            </label>
          </td>
        </tr>
      </tbody>
    </table>

    <p>&nbsp;</p>

    <?php

  }

  private static function get_require_field_key() {

    $field_key = MywpLockoutForm::get_field_key( 'cf7_require_field_key' );

    if( empty( $field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$field_key' , $called_text );

      return false;

    }

    return $field_key;

  }

  private static function get_dummy_field_key() {

    $field_key = MywpLockoutForm::get_field_key( 'cf7_dummy_field_key' );

    if( empty( $field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$field_key' , $called_text );

      return false;

    }

    return $field_key;

  }

  public static function mywp_wp_loaded() {

    if( is_user_logged_in() ) {

      return false;

    }

    $setting_data = MywpLockoutApi::get_lockout_setting_data();

    if( empty( $setting_data['contact_form_7_validate_lockout'] ) ) {

      return false;

    }

    add_filter( 'wpcf7_form_elements' , array( __CLASS__ , 'wpcf7_form_elements' ) , 1000 );

    add_filter( 'wpcf7_validate' , array( __CLASS__ , 'wpcf7_validate' ) , 1000 , 2 );

    add_filter( 'wpcf7_spam' , array( __CLASS__ , 'wpcf7_spam' ) , 1000 , 2 );

  }

  public static function wpcf7_form_elements( $form_elements ) {

    $require_field_key = self::get_require_field_key();

    if( empty( $require_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$require_field_key' , $called_text );

      return $form_elements;

    }

    $dummy_field_key = self::get_dummy_field_key();

    if( empty( $dummy_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$dummy_field_key' , $called_text );

      return $form_elements;

    }

    $html = '';

    $html .= '<p style="display: none;">';

    $html .= sprintf( '<label> %s' , esc_html( __( 'Require field' , 'mywp-lockout' ) ) );

    $html .= sprintf( '<span class="wpcf7-form-control-wrap" data-name="%1$s"><input class="wpcf7-form-control wpcf7-text" type="text" name="%1$s" value="" /></span>' , esc_attr( $require_field_key ) );

    $html .= '</label>';

    $html .= '</p>';

    $html .= '<p style="display: none;">';

    $html .= sprintf( '<label> %s' , esc_html( __( 'Dummy field' , 'mywp-lockout' ) ) );

    $html .= sprintf( '<span class="wpcf7-form-control-wrap" data-name="%1$s"><input class="wpcf7-form-control wpcf7-text" type="text" name="%1$s" value="" /></span>' , esc_attr( $dummy_field_key ) );

    $html .= '</label>';

    $html .= '</p>';

    $html .= MywpLockoutForm::get_require_field_script( $require_field_key );

    $form_elements .= $html;

    return $form_elements;

  }

  public static function wpcf7_validate( $result , $tags ) {

    $require_field_key = self::get_require_field_key();

    if( empty( $require_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$require_field_key' , $called_text );

      return $result;

    }

    $dummy_field_key = self::get_dummy_field_key();

    if( empty( $dummy_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$dummy_field_key' , $called_text );

      return $result;

    }

    if( ! isset( $_POST[ $require_field_key ] ) ) {

      $result->invalidate( '' , __( 'There are no required fields. Please submit your contact using the official contact form.' , 'mywp-lockout' ) );

      return $result;

    }

    if( ! MywpLockoutForm::is_validate_check_nonce( $_POST[ $require_field_key ] , $require_field_key ) ) {

      $result->invalidate( '' , __( 'There are no required fields. Please submit your contact using the official contact form.' , 'mywp-lockout' ) );

      return $result;

    }

    if( ! isset( $_POST[ $dummy_field_key ] ) ) {

      $result->invalidate( '' , __( 'There are no required fields. Please submit your contact using the official contact form.' , 'mywp-lockout' ) );

      return $result;

    }

    return $result;

  }

  public static function wpcf7_spam( $spam , $WPCF7_Submission ) {

    $dummy_field_key = self::get_dummy_field_key();

    if( empty( $dummy_field_key ) ) {

      $called_text = sprintf( '%s::%s()' , __CLASS__ , __FUNCTION__ );

      MywpHelper::error_not_found_message( '$dummy_field_key' , $called_text );

      return $spam;

    }

    if( $spam ) {

      return $spam;

    }

    if( ! isset( $_POST[ $dummy_field_key ] ) ) {

      return $spam;

    }

    if( empty( $_POST[ $dummy_field_key ] ) ) {

      return $spam;

    }

    $spam = true;

    $WPCF7_ContactForm = $WPCF7_Submission->get_contact_form();

    $args = array( 'reason' => sprintf( 'Dummy field input on Contact form 7 [%s]' , $WPCF7_ContactForm->id() ) );

    MywpControllerModuleLockout::set_lockout_remote_data( $args );

    MywpControllerModuleLockout::do_lockedout();

    return $spam;

  }

}

MywpLockoutThirdpartyModuleContactForm7::init();

endif;
