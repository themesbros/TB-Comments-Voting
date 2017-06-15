<?php
/**
 * Shortcodes for the plguin.
 *
 * @package   TB Comments Voting
 * @version   1.0.0
 * @author    ThemesBros
 * @copyright Copyright (c) 2011 - 2017, ThemesBros
 */

/* If this file is called directly, abort. */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

# Register shortcodes.
add_action( 'init', 'tbcv_register_shortcodes' );

/**
 * Registers shortcodes for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function tbcv_register_shortcodes() {
	add_shortcode( 'tbcv_voting', 'tbcv_shortcode_voting' );
}

/**
 * Displays comment voting.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function tbcv_shortcode_voting() {

	$options = get_option( 'tbcv_settings' );
	if ( ! isset( $options['status'] ) ||  '' == $options['status'] || 'custom' !== $options['position'] ) {
		return;
	}

	global $comment;
	$comment_ID = $comment->comment_ID;

	$post_ID    = get_the_ID();
	$up_votes   = tbcv_get_comment_votes( $comment_ID, 'up' );
	$down_votes = tbcv_get_comment_votes( $comment_ID, 'down' );

	$html  = tbcv_get_vote_html( $post_ID, $comment_ID, $up_votes, 'up' );
	$html .= tbcv_get_vote_html( $post_ID, $comment_ID, $down_votes, 'down' );

	return $html;
}