<?php
/**
 * AJAX callbacks.
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

/* AJAX actions for comment voting. */
add_action( 'wp_ajax_tbcv_comment_vote', 'tbcv_comment_vote' );
add_action( 'wp_ajax_nopriv_tbcv_comment_vote', 'tbcv_comment_vote' );

/**
 * AJAX callback - saves voting data and returns vote number.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function tbcv_comment_vote() {

	check_ajax_referer( 'tbcv-nonce', 'security' );

	$post_id  = absint( $_POST['postid'] );
	$id       = absint( $_POST['id'] );
	$type     = esc_attr( $_POST['type'] );
	$ip       = tbcv_get_IP();
 	$ip_cache = get_transient( "tbcv_ip_cache_{$post_id}" );

 	/* Check if user has already voted on comment, and end terminate script if it is. */
	if ( isset( $ip_cache[$id] ) && in_array( $ip, $ip_cache[$id] ) ) {
		wp_die();
	}

	/* Check if there are saved IP's for this comment, if not - add current IP to it. */
	if ( ! isset( $ip_cache[$id] ) ) {
		$ip_cache[$id] = array();
	}

	array_push(	$ip_cache[$id], $ip );
	set_transient( "tbcv_ip_cache_{$post_id}", $ip_cache, 60 * 60 * 24 * 7 );

	$votes = absint( get_comment_meta( $id, "tbcv_vote_{$type}", true ) );
	$votes++;

	update_comment_meta( $id, "tbcv_vote_{$type}", $votes );

	echo $votes;

	wp_die();
}