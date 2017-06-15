<?php
/**
 * Plugin options.
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
 * Plugin admin class.
 *
 * @since 1.0.0
 */
class TB_Comment_Voting_Admin {

	/**
	 * Sets up needed actions for the admin to initialize.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	/**
	 * Adds link to admin menu under "Comments".
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'edit-comments.php',
			esc_html__( 'TB Comments Voting', 'tb-comments-voting' ),
			esc_html__( 'TB Comments Voting', 'tb-comments-voting' ),
			'manage_options',
			'tb_comments_voting',
			array( $this, 'display_options' )
		);
	}

	/**
	 * Initialize settings API to create plugin options.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_init() {

		register_setting( 'tbcv_options', 'tbcv_settings', array( $this, 'sanitize_data' ) );

		add_settings_section(
			'tbcv_section',
			esc_html__( 'TB Comments Voting Options', 'tb-comments-voting' ),
			array( $this, 'settings_section_display' ),
			'tbcv_options'
		);

		add_settings_field(
			'status',
			esc_html__( 'Status', 'tb-comments-voting' ),
			array( $this, 'display_status' ),
			'tbcv_options',
			'tbcv_section'
		);

		add_settings_field(
			'position',
			esc_html__( 'Position', 'tb-comments-voting' ),
			array( $this, 'display_position' ),
			'tbcv_options',
			'tbcv_section'
		);

	}

	/**
	 * Callback: displays section info.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_section_display() {
		printf( '<p>%s</p>', esc_html__( 'Customize plugin behaviour.', 'tb-comments-voting' ) );
	}

	/**
	 * Callback: displays checkbox.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function display_status() {
		$options = get_option( 'tbcv_settings' );
		?>
		<label for="status"><input type="checkbox" id="status" name="tbcv_settings[status]" <?php checked( $options['status'], 1 ); ?>> <?php esc_html_e( 'Check to enable', 'tb-comments-voting' ); ?></label>
		<?php
	}

	/**
	 * Callback: displays select.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function display_position() {
		$options = get_option( 'tbcv_settings' );
		?>
		<select name="tbcv_settings[position]">
			<option value="after" <?php selected(  $options["position"],  'after' ); ?>><?php esc_html_e( 'After comments',  'tb-comments-voting' ); ?></option>
			<option value="before" <?php selected( $options["position"], 'before' ); ?>><?php esc_html_e( 'Before comments', 'tb-comments-voting' ); ?></option>
			<option value="custom" <?php selected( $options["position"], 'custom' ); ?>><?php esc_html_e( 'Custom position', 'tb-comments-voting' ); ?></option>
		</select>
		<p><?php esc_html_e( 'If custom position chosen, you can place function in your comments template like this:', 'tb-comments-voting' ); ?>
		<br>
		<code>
			&lt;?php if ( function_exists( 'tbcv_shortcode_voting' ) ) { echo tbcv_shortcode_voting(); }; ?&gt;
		</code>
		<br> <?php esc_html_e( 'or', 'tb-comments-voting' ); ?> <br>
		<code>&lt;?php echo do_shortcode( '[tbcv_voting]' ); ?&gt;</code>
		</p>
	<?php
	}

	/**
	 * Callback: displays options page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function display_options() {
		?>
		<form action="options.php" method="POST">
			<?php
			settings_fields( 'tbcv_options' );
			do_settings_sections( 'tbcv_options' );
			submit_button();
			?>
		</form>
		<?php
	}

	/**
	 * Callback: checks and validates user submitted data.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function sanitize_data( $input ) {
		$output['status']   = isset( $input['status'] ) ? 1 : '';
		$output['position'] = sanitize_text_field( $input['position'] );
		return $output;
	}
}

new TB_Comment_Voting_Admin;