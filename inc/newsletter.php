<?php
/**
 * Newsletter: zapis z pop-upu do WordPressa (Newsletter → Subskrybenci, eksport CSV)
 * albo wysyłka do zewnętrznego serwisu (adres formularza w Customizerze).
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Typ wpisu dla subskrybentów (niepubliczny).
 */
function ekasyna_register_subscribers() {
	register_post_type(
		'ek_subscriber',
		array(
			'labels'              => array(
				'name'          => __( 'Subskrybenci', 'ekasyna' ),
				'singular_name' => __( 'Subskrybent', 'ekasyna' ),
				'menu_name'     => __( 'Newsletter', 'ekasyna' ),
				'all_items'     => __( 'Subskrybenci', 'ekasyna' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 26,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'ekasyna_register_subscribers' );

/**
 * Zapis z formularza.
 */
function ekasyna_handle_subscribe() {
	$ajax  = ! empty( $_POST['ek_ajax'] );
	$back  = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$fail  = function ( $msg ) use ( $ajax, $back ) {
		if ( $ajax ) {
			wp_send_json_error( array( 'message' => $msg ), 400 );
		}
		wp_safe_redirect( add_query_arg( 'ek_nl', 'error', $back ) );
		exit;
	};

	if ( ! isset( $_POST['ek_nl_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ek_nl_nonce'] ) ), 'ekasyna_subscribe' ) ) {
		$fail( __( 'Sesja wygasła. Odśwież stronę i spróbuj ponownie.', 'ekasyna' ) );
	}
	if ( ! empty( $_POST['ek_website'] ) ) { // Pułapka na boty.
		$fail( __( 'Błąd formularza.', 'ekasyna' ) );
	}
	$email = isset( $_POST['ek_email'] ) ? sanitize_email( wp_unslash( $_POST['ek_email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		$fail( __( 'Podaj poprawny adres e-mail.', 'ekasyna' ) );
	}
	if ( empty( $_POST['ek_consent'] ) ) {
		$fail( __( 'Zaznacz zgodę, aby się zapisać.', 'ekasyna' ) );
	}

	$exists = get_posts( array( 'post_type' => 'ek_subscriber', 'title' => $email, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( ! $exists ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'ek_subscriber',
				'post_status' => 'private',
				'post_title'  => $email,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_ek_consent_at', current_time( 'mysql' ) );
			update_post_meta( $id, '_ek_consent_text', sanitize_text_field( wp_strip_all_tags( ekasyna_mod( 'ekasyna_nl_consent' ) ) ) );
			update_post_meta( $id, '_ek_source', esc_url_raw( $back ) );
			do_action( 'ekasyna_new_subscriber', $email, $id );
		}
	}

	if ( $ajax ) {
		wp_send_json_success( array( 'message' => __( 'Dziękujemy za zapis!', 'ekasyna' ) ) );
	}
	wp_safe_redirect( add_query_arg( 'ek_nl', 'ok', $back ) );
	exit;
}
add_action( 'admin_post_nopriv_ekasyna_subscribe', 'ekasyna_handle_subscribe' );
add_action( 'admin_post_ekasyna_subscribe', 'ekasyna_handle_subscribe' );

/**
 * Kolumny listy subskrybentów.
 *
 * @param array $cols Kolumny.
 * @return array
 */
function ekasyna_subscriber_columns( $cols ) {
	return array(
		'cb'         => $cols['cb'],
		'title'      => __( 'E-mail', 'ekasyna' ),
		'ek_consent' => __( 'Zgoda', 'ekasyna' ),
		'ek_source'  => __( 'Strona zapisu', 'ekasyna' ),
	);
}
add_filter( 'manage_ek_subscriber_posts_columns', 'ekasyna_subscriber_columns' );

/**
 * Wartości kolumn.
 *
 * @param string $col Kolumna.
 * @param int    $id  ID.
 */
function ekasyna_subscriber_column( $col, $id ) {
	if ( 'ek_consent' === $col ) {
		echo esc_html( get_post_meta( $id, '_ek_consent_at', true ) );
	} elseif ( 'ek_source' === $col ) {
		$src = get_post_meta( $id, '_ek_source', true );
		echo $src ? '<a href="' . esc_url( $src ) . '">' . esc_html( wp_parse_url( $src, PHP_URL_PATH ) ) . '</a>' : '—';
	}
}
add_action( 'manage_ek_subscriber_posts_custom_column', 'ekasyna_subscriber_column', 10, 2 );

/**
 * Strona eksportu CSV.
 */
function ekasyna_subscriber_export_menu() {
	add_submenu_page( 'edit.php?post_type=ek_subscriber', __( 'Eksport CSV', 'ekasyna' ), __( 'Eksport CSV', 'ekasyna' ), 'manage_options', 'ekasyna-nl-export', 'ekasyna_subscriber_export_page' );
}
add_action( 'admin_menu', 'ekasyna_subscriber_export_menu' );

/**
 * Widok eksportu.
 */
function ekasyna_subscriber_export_page() {
	$count = wp_count_posts( 'ek_subscriber' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Eksport subskrybentów', 'ekasyna' ); ?></h1>
		<p><?php printf( /* translators: %d liczba */ esc_html__( 'Subskrybentów: %d.', 'ekasyna' ), (int) ( $count->private ?? 0 ) ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="ekasyna_nl_csv">
			<?php wp_nonce_field( 'ekasyna_nl_csv' ); ?>
			<?php submit_button( __( 'Pobierz CSV', 'ekasyna' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Pobranie CSV.
 */
function ekasyna_subscriber_csv() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Brak uprawnień.', 'ekasyna' ) );
	}
	check_admin_referer( 'ekasyna_nl_csv' );
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=subskrybenci-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'email', 'consent_at', 'source' ) );
	foreach ( get_posts( array( 'post_type' => 'ek_subscriber', 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $p ) {
		fputcsv( $out, array( $p->post_title, get_post_meta( $p->ID, '_ek_consent_at', true ), get_post_meta( $p->ID, '_ek_source', true ) ) );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	exit;
}
add_action( 'admin_post_ekasyna_nl_csv', 'ekasyna_subscriber_csv' );
