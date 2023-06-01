<?php
/**
 * Shortcode [cmkk-form]
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Shortcode to create a form with which people can register as participants.
 *
 * @since 1.0.0
 *
 * @param array  $atts    The attributes (parameters) of the shorcode.
 * @param string $content The content bracketed by the shortcode.
 *
 * @return string The output generated by the shortcode.
 */

function shortcode_form( $atts, $content = null )
{
    /** Process passed parameters */

    $default_atts  = array(
        'event_id' => '',
    );

    extract( shortcode_atts( $default_atts, $atts ) );

    if( empty( $event_id ) ) :      // move to output?
        return '';
    endif;


    /** Process form if already submitted */

    $code          = 0;
    $user_lastname = '';
    $user_firstname = '';
    $user_email    = '';

    if( isset( $_POST['action'] ) ) :

        if( true === isset( $_POST['user_lastname'] ) ) :
            $user_lastname = trim( $_POST['user_lastname'] );
        endif;

        if( true === isset( $_POST['user_firstname'] ) ) :
            $user_firstname = trim(  $_POST['user_firstname'] );
        endif;

        if( true === isset( $_POST['user_email'] ) ) :
            $user_email = trim( strtolower( $_POST['user_email'] ) );
        endif;

        $code = add_user( $event_id, $user_lastname, $user_firstname, $user_email );
    endif;


    /** Output the shortcode */

    ob_start();

    if( 0 !== $code ) :
        display_notice( $code );
    endif;
    ?>
    <form class="cmkk-form" method="post" action="">
        <table>
            <tr>
                <th><?php echo __( 'Your first name', 'cm-theme-addon-ticketpool'); ?></th>
                <td>
                    <input id="firstname" name="user_firstname" type="text" value="<?php echo $user_firstname; ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo __( 'Your last name', 'cm-theme-addon-ticketpool'); ?></th>
                <td>
                    <input id="lastname" name="user_lastname" type="text"value="<?php echo $user_lastname; ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo __( 'Your email address', 'cm-theme-addon-ticketpool'); ?></th>
                <td>
                    <input id="email" name="user_email" type="email"value="<?php echo $user_email; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="gdpr">
                    <input type="checkbox" id="gdpr" name="gdpr">
                    <label for="gdpr"><?php echo sprintf(
                        __( 'I agree that my data will be stored and processed for the purpose of contacting me. More information on this in the %1$s.', 'cm-theme-addon-ticketpool' ),
                        sprintf(
                            '<a href="%1$s">%2$s</a>',
                            get_privacy_policy_url(),
                            __( 'privacy policy', 'cm-theme-addon-ticketpool' )
                        )
                    );
                    ?></label>
                </td>
            </tr>
            <tr>
                <th>
                    <div class="wp-block-button">
                        <button id="cmkk-submit" type="submit" class="wp-block-button__link" name="action" value="add" disabled="disabled"><?php echo __( 'Request participation', 'cm-theme-addon-ticketpool' );?></button>
                    </div>
                </th>
                <td></td>
            </tr>
        </table>
        <input id="event-id" name="event-id" type="hidden" value="<?php echo $event_id; ?>">
    </form>
    <?php
    $output_buffer .= ob_get_contents();
    ob_end_clean();
    return $output_buffer;
}

add_shortcode( 'cmkk-form', __NAMESPACE__ . '\shortcode_form' );
