<?php
/**
 * Plugin functions.
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

/**
 * Retrieves comment votes from comments meta table by comment ID.
 *
 * @param  int 		$comment_ID
 * @param  string 	$type
 * @return void
 */
function tbcv_get_comment_votes( $comment_ID, $type = 'up' ) {
	$count = absint( get_comment_meta( $comment_ID, "tbcv_vote_{$type}", true ) );
	if ( 'down' == $type ) {
		$count = $count > 0 ? sprintf( '-%d', $count ) : $count;
	}
	return $count;
}

/**
 * Set's vote count for particular comment.
 *
 * @param  int    $comment_ID
 * @param  string $type
 * @param  int    $newNumber
 * @return void
 */
function tbcv_set_comment_votes( $comment_ID, $type = 'up', $newNumber ) {
	update_comment_meta( $comment_ID, "tbcv_vote_{$type}", absint( $newNumber ) );
}

/**
 * Generates vote up / down buttons.
 *
 * @param  int $post_ID
 * @param  int $comment_ID
 * @param  int $vote_count
 * @param  string $type
 * @return string
 */
function tbcv_get_vote_html( $post_ID, $comment_ID, $vote_count, $type ) {
	return sprintf( '<a class="tb-comment-vote tb-vote-%4$s" data-postid="%d" data-id="%d" data-type="%4$s" data-readonly="false" href="#"><span>%d</span><i class="fa fa-thumbs-%4$s"></i></a>', $post_ID, $comment_ID, $vote_count, $type );
}

/**
 * Get the visitor's IP address.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function tbcv_get_IP() {
	$client = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote = $_SERVER['REMOTE_ADDR'];

	if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
		$ip = $client;
	} elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}

	return $ip;
}