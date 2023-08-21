<?php
/**
* Plugin Name: DevKom Plugin
* Description: This is test plugin for Devkom company
* Version: 1.0
* Author: RubyWave
**/

add_filter('attachment_fields_to_edit', 'edit_media_nsfw_option', 11, 2 );
add_filter('attachment_fields_to_save', 'save_media_nsfw_option', 11, 2 );
add_filter( 'render_block', 'add_nsfw_warning', 10, 2 );


/**
 * Add field to set NSFW flag for image
 */
function edit_media_nsfw_option( $form_fields, $post ) {
    $checkbox_field = (bool) get_post_meta($post->ID, '_if_nsfw', true);
    $form_fields['if_nsfw'] = array( 
        'label' => esc_html__( 'Set if image is NSFW', 'devkom-plugin' ) ,
        'input' => 'html',
        'html' => '<input type="checkbox" id="attachments-'.$post->ID.'-_if_nsfw" name="attachments['.$post->ID.'][if_nsfw]" value="1"'.($checkbox_field ? ' checked="checked"' : '').' /> ',
        'value' => $checkbox_field
    );
    return $form_fields;
}

/**
 * Save nsfw flag
 */

function save_media_nsfw_option( $post, $attachment ) {
    update_post_meta( $post['ID'], '_if_nsfw', $attachment['if_nsfw'] );
    return $post;
}


/**
 * Display warning above image, if image is flaged as NSFW
 */
function add_nsfw_warning( $block_content, $block ) {
    
    $imageID = isset($block['attrs']['id']) ? $block['attrs']['id'] : false;
    if(!$imageID) return $block_content;
    $ifNSFW = (bool) get_post_meta($imageID, '_if_nsfw', true);


	if ( $block['blockName'] === 'core/image' && $ifNSFW) {
		return esc_html__( 'WARNING: Image below is NSFW', 'devkom-plugin' ) . $block_content;
	}

	return $block_content;

}