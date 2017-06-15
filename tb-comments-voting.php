<?php
/**
 * Plugin Name: TB Comments Voting
 * Plugin URI: http://www.themesbros.com/
 * Description: Likes and dislikes for WordPress comments.
 * Author: ThemesBros
 * AuthorURI: http://www.themesbros.com/
 * Version: 1.0.0
 * Domain Path: /languages
 * Text Domain: tbcv
 *
 * @package    	TB Comments Voting
 * @author 		ThemesBros.com
 * @copyright   Copyright (c) 2011-2017, ThemesBros
*/

/* If this file is called directly, abort. */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Plugin loader class.
 *
 * @since 1.0.0
 */
class TB_Comments_Voting {

	/**
	 * Plugin status (enabled or disabled).
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var bool
	 */
	protected $status = false;

	/**
	 * Plugin URL.
	 *
	 * @since  	1.0.0
	 * @access  protected
	 * @var 	string
	 */
	protected $plugin_url;

	/**
	 * Sets up needed actions/filters for the plugin to initialize.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$options          = get_option( 'tbcv_settings' );
		$this->status     = isset( $options['status'] ) ? $options['status'] : '';
		$this->plugin_url = plugin_dir_url( __FILE__ );

		add_action( 'plugins_loaded', array( $this, 'i18n'   	 ), 3 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );

		if ( $this->status ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue'    ) 		  );
			add_filter( 'comment_text', 	  array( $this, 'add_voting' ), 10, 2 );
		}
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {
		load_plugin_textdomain( 'tb-comments-voting', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		require_once 'inc/options.php';
		require_once 'inc/ajax.php';
		require_once 'inc/functions.php';
		require_once 'inc/shortcodes.php';
	}

	/**
	 * Load styles and scripts needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		# Load scripts only on singular pages.
		if ( ! is_singular() ) {
			return;
		}

		# Use minified files if SCRIPT_DEBUG is off.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		# Register & load voting script.
		wp_register_script(
			'tb-comments-voting',
			$this->plugin_url . "assets/js/comments-voting{$suffix}.js",
			array( 'jquery' ),
			null,
			true
		);

		wp_enqueue_script( 'tb-comments-voting' );

		# Pass tbcv object to JS.
		wp_localize_script( 'tb-comments-voting', 'tbcv', array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'tbcv-nonce' )
		) );

		# Register & load Font Awesome.
		wp_register_style( 'font-awesome', $this->plugin_url . "assets/css/font-awesome{$suffix}.css" );
		wp_enqueue_style( 'font-awesome' );

		# Register & load plugin style.
		wp_register_style( 'tb-comments-voting', $this->plugin_url . "assets/css/style{$suffix}.css" );
		wp_enqueue_style( 'tb-comments-voting' );

	}

	/**
	 * Adds voting to the comment.
	 *
	 * @since  1.0.0
	 * @param string $text
	 * @param object $comment
	 */
	public function add_voting( $text, $comment ) {

		if ( ! $comment->comment_approved ) {
			return $text;
		}

		$options = get_option( 'tbcv_settings' );
		$position = isset( $options['position'] ) ? $options['position'] : 'after';

		if ( 'custom' === $position ) {
			return $text;
		}

		ob_start();

			$post_ID    = get_the_ID();
			$comment_ID = $comment->comment_ID;
			$up_votes   = tbcv_get_comment_votes( $comment_ID, 'up' );
			$down_votes = tbcv_get_comment_votes( $comment_ID, 'down' );

			$html  = '<div class="tbcv-votes">';
			$html .= tbcv_get_vote_html( $post_ID, $comment_ID, $up_votes, 'up' );
			$html .= tbcv_get_vote_html( $post_ID, $comment_ID, $down_votes, 'down' );
			$html .= '</div>';
			echo apply_filters( 'tbcv_vote_html', $html );

			$voting_html = ob_get_contents();

		ob_end_clean();

		$output = ( 'after' == $position ) ? $text . $voting_html : $voting_html . $text;

		return $output;
	}

}

new TB_Comments_Voting;