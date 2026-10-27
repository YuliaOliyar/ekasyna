<?php
/**
 * e-kasyna — funkcje motywu.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

define( 'EKASYNA_VERSION', '1.0.0' );
define( 'EKASYNA_DIR', get_template_directory() );
define( 'EKASYNA_URI', get_template_directory_uri() );

require_once EKASYNA_DIR . '/inc/helpers.php';
require_once EKASYNA_DIR . '/inc/cpt.php';
require_once EKASYNA_DIR . '/inc/meta.php';
require_once EKASYNA_DIR . '/inc/ranking.php';
require_once EKASYNA_DIR . '/inc/toc.php';
require_once EKASYNA_DIR . '/inc/customizer.php';
require_once EKASYNA_DIR . '/inc/schema.php';
require_once EKASYNA_DIR . '/inc/az.php';
require_once EKASYNA_DIR . '/inc/newsletter.php';
require_once EKASYNA_DIR . '/inc/demo-content.php';
require_once EKASYNA_DIR . '/inc/demo.php';

/**
 * Ustawienia motywu.
 */
function ekasyna_setup() {
	load_theme_textdomain( 'ekasyna', EKASYNA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style(
		array(
			'assets/css/editor.css',
			str_replace( ',', '%2C', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Manrope:wght@400;600;700&display=swap' ),
		)
	);

	add_image_size( 'ekasyna-logo', 340, 168, false );

	register_nav_menus(
		array(
			'primary'  => __( 'Menu główne', 'ekasyna' ),
			'footer_1' => __( 'Stopka — kolumna 1', 'ekasyna' ),
			'footer_2' => __( 'Stopka — kolumna 2', 'ekasyna' ),
			'footer_3' => __( 'Stopka — kolumna 3', 'ekasyna' ),
		)
	);
}
add_action( 'after_setup_theme', 'ekasyna_setup' );

/**
 * Obszar widżetów w stopce (logotypy DMCA, organizacji pomocowych itp.).
 */
function ekasyna_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'Stopka — logotypy', 'ekasyna' ),
			'id'            => 'ekasyna-footer-badges',
			'before_widget' => '<div class="ek-footer__badge">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="screen-reader-text">',
			'after_title'   => '</span>',
		)
	);
}
add_action( 'widgets_init', 'ekasyna_widgets' );

/**
 * Skrypty i style.
 */
function ekasyna_assets() {
	wp_enqueue_style(
		'ekasyna-fonts',
		'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Manrope:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'ekasyna-theme', EKASYNA_URI . '/assets/css/theme.css', array(), EKASYNA_VERSION );
	wp_enqueue_script( 'ekasyna-theme', EKASYNA_URI . '/assets/js/theme.js', array(), EKASYNA_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'ekasyna_assets' );

/**
 * Preconnect do Google Fonts.
 *
 * @param array  $urls          URL-e.
 * @param string $relation_type Typ relacji.
 * @return array
 */
function ekasyna_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		$urls[] = 'https://fonts.googleapis.com';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ekasyna_resource_hints', 10, 2 );

/**
 * Favicon z motywu, jeśli w Customizerze nie ustawiono ikony witryny.
 */
function ekasyna_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" type="image/svg+xml" href="%s">' . "\n", esc_url( EKASYNA_URI . '/assets/img/favicon.svg' ) );
	echo '<meta name="theme-color" content="#15171C">' . "\n";
}
add_action( 'wp_head', 'ekasyna_favicon', 5 );

/**
 * Szerokość treści.
 */
function ekasyna_content_width() {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'ekasyna_content_width', 0 );

/**
 * Pole „Stanowisko” w profilu użytkownika (do bloku autora).
 *
 * @param WP_User $user Użytkownik.
 */
function ekasyna_user_fields( $user ) {
	?>
	<h2><?php esc_html_e( 'Blok autora (e-kasyna)', 'ekasyna' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="ekasyna_position"><?php esc_html_e( 'Stanowisko', 'ekasyna' ); ?></label></th>
			<td>
				<input type="text" name="ekasyna_position" id="ekasyna_position" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'ekasyna_position', true ) ); ?>">
				<p class="description"><?php esc_html_e( 'Np. „Redaktor naczelny”. Biografia pochodzi z pola „Informacje biograficzne”.', 'ekasyna' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="ekasyna_topics"><?php esc_html_e( 'Tematy (po przecinku)', 'ekasyna' ); ?></label></th>
			<td><input type="text" name="ekasyna_topics" id="ekasyna_topics" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'ekasyna_topics', true ) ); ?>"></td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'ekasyna_user_fields' );
add_action( 'edit_user_profile', 'ekasyna_user_fields' );

/**
 * Zapis pól profilu.
 *
 * @param int $user_id ID użytkownika.
 */
function ekasyna_save_user_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}
	check_admin_referer( 'update-user_' . $user_id );
	if ( isset( $_POST['ekasyna_position'] ) ) {
		update_user_meta( $user_id, 'ekasyna_position', sanitize_text_field( wp_unslash( $_POST['ekasyna_position'] ) ) );
	}
	if ( isset( $_POST['ekasyna_topics'] ) ) {
		update_user_meta( $user_id, 'ekasyna_topics', sanitize_text_field( wp_unslash( $_POST['ekasyna_topics'] ) ) );
	}
}
add_action( 'personal_options_update', 'ekasyna_save_user_fields' );
add_action( 'edit_user_profile_update', 'ekasyna_save_user_fields' );
