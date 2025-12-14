<?php

if ( !defined('ABSPATH' ) )
    exit();

add_filter( 'trp_machine_translation_engines', 'trp_openrouter_add_engine', 10 );
function trp_openrouter_add_engine( $engines ){
    $engines[] = array( 'value' => 'openrouter', 'label' => __( 'OpenRouter', 'translatepress-multilingual' ) );

    return $engines;
}
add_action( 'trp_machine_translation_extra_settings_middle', 'trp_openrouter_add_settings' );

function trp_openrouter_add_settings( $mt_settings ){
    $trp                = TRP_Translate_Press::get_trp_instance();
    $machine_translator = $trp->get_component( 'machine_translator' );

    $translation_engine = isset( $mt_settings['translation-engine'] ) ? $mt_settings['translation-engine'] : '';
    $api_key = isset( $mt_settings['openrouter-api-key'] ) ? $mt_settings['openrouter-api-key'] : '';

    // Check for API errors only if $translation_engine is OpenRouter.
    if ( 'openrouter' === $translation_engine ) {
        // $api_check = $machine_translator->check_api_key_validity();
    }

    // Check for errors.
    $error_message = '';
    $show_errors   = false;
    if ( isset( $api_check ) && true === $api_check['error'] ) {
        $error_message = $api_check['message'];
        $show_errors    = true;
    }

    $text_input_classes = array(
        'trp-text-input',
    );
    if ( $show_errors && 'openrouter' === $translation_engine ) {
        $text_input_classes[] = 'trp-text-input-error';
    }
    ?>

    <div class="trp-engine trp-automatic-translation-engine__container" id="openrouter">
        <span class="trp-primary-text-bold"><?php esc_html_e( 'OpenRouter API Key', 'translatepress-multilingual' ); ?> </span>

        <div class="trp-automatic-translation-api-key-container">
            <input type="text" id="trp-openrouter-api-key" placeholder="<?php esc_html_e( 'Add your API Key here...', 'translatepress-multilingual' ); ?>" class="<?php echo esc_html( implode( ' ', $text_input_classes ) ); ?>" name="trp_machine_translation_settings[openrouter-api-key]" value="<?php if( !empty( $mt_settings['openrouter-api-key'] ) ) echo esc_attr( $mt_settings['openrouter-api-key']);?>"/>
            <?php
            // Only show errors if OpenRouter is active.
            if ( 'openrouter' === $translation_engine && function_exists( 'trp_output_svg' ) ) {
                $machine_translator->automatic_translation_svg_output( $show_errors );
            }
            ?>
        </div>

        <?php
        if ( $show_errors && 'openrouter' === $translation_engine ) {
            ?>
            <span class="trp-error-inline trp-settings-error-text">
                <?php echo wp_kses_post( $error_message ); ?>
            </span>
            <?php
        }
        ?>

        <span class="trp-description-text">
            <?php echo wp_kses( __( 'Enter your OpenRouter API Key.', 'translatepress-multilingual' ), [ 'a' => [ 'href' => [], 'title' => [], 'target' => [] ], 'strong' => [] ] ); ?>
        </span>
    </div>

    <?php
}

add_filter( 'trp_machine_translation_sanitize_settings', 'trp_openrouter_sanitize_settings' );
function trp_openrouter_sanitize_settings( $mt_settings ){
    if( !empty( $mt_settings['openrouter-api-key'] ) )
        $mt_settings['openrouter-api-key'] = sanitize_text_field( $mt_settings['openrouter-api-key']  );

    return $mt_settings;
}
