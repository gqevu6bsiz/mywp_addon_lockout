<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpLockoutIncompatible' ) ) :

final class MywpLockoutIncompatible {

  public static function init() {

    add_action( 'admin_notices' , array( __CLASS__ , 'admin_notices' ) );

    add_action( 'network_admin_notices' , array( __CLASS__ , 'admin_notices' ) );

  }

  public static function admin_notices() {

    if( is_multisite() ) {

      if( ! MywpLockoutApi::is_network_manager() ) {

        return false;

      }

    } else {

      if( ! MywpLockoutApi::is_manager() ) {

        return false;

      }

    }

    $message = sprintf( __( 'Sorry, %s is <strong>Incompatible</strong> with your version of My WP Customize. Require version  %s.' , 'mywp-lockout' ) , MYWP_LOCKOUT_NAME , '1.28.0' );

    ?>

    <div class="error">
      <p>
        <?php echo wp_kses_post( $message ); ?>
      </p>
    </div>

    <?php

  }

}

endif;
