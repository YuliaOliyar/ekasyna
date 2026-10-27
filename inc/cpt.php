<?php
/**
 * Typ wpisu „Kasyno” i taksonomia kategorii (filtry rankingu).
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rejestracja CPT i taksonomii.
 */
function ekasyna_register_types() {
	register_post_type(
		'kasyno',
		array(
			'labels'        => array(
				'name'          => __( 'Kasyna', 'ekasyna' ),
				'singular_name' => __( 'Kasyno', 'ekasyna' ),
				'add_new_item'  => __( 'Dodaj kasyno', 'ekasyna' ),
				'edit_item'     => __( 'Edytuj kasyno', 'ekasyna' ),
				'all_items'     => __( 'Wszystkie kasyna', 'ekasyna' ),
				'menu_name'     => __( 'Kasyna', 'ekasyna' ),
			),
			'public'        => true,
			'has_archive'   => 'ranking-kasyn',
			'rewrite'       => array( 'slug' => 'recenzje', 'with_front' => false ),
			'menu_icon'     => 'dashicons-star-filled',
			'menu_position' => 5,
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'kategoria-kasyna',
		'kasyno',
		array(
			'labels'            => array(
				'name'          => __( 'Kategorie kasyn', 'ekasyna' ),
				'singular_name' => __( 'Kategoria kasyna', 'ekasyna' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'kasyna' ),
		)
	);
}
add_action( 'init', 'ekasyna_register_types' );

/**
 * Domyślne kategorie (filtry) przy aktywacji motywu.
 */
function ekasyna_activate() {
	ekasyna_register_types();

	$defaults = array(
		'bez-depozytu'   => array( 'Bonus bez depozytu', 'Darmowe środki lub spiny za samą rejestrację.', '0 zł' ),
		'darmowe-spiny'  => array( 'Darmowe spiny', 'Pakiety od 20 do 100 obrotów na popularnych slotach.', 'FS' ),
		'blik'           => array( 'Kasyno z BLIK', 'Szybkie wpłaty kodem z aplikacji bankowej.', 'BLIK' ),
		'min-depozyt'    => array( 'Minimalny depozyt', 'Kasyna z wpłatą od 1, 5, 10 i 20 zł.', '1 zł' ),
		'nowe'           => array( 'Nowe kasyna', 'Świeże marki i ich startowe oferty.', 'NEW' ),
		'krypto'         => array( 'Krypto kasyno', 'Wpłaty i wypłaty w kryptowalutach.', '₿' ),
	);

	foreach ( $defaults as $slug => $data ) {
		if ( term_exists( $slug, 'kategoria-kasyna' ) ) {
			continue;
		}
		$term = wp_insert_term( $data[0], 'kategoria-kasyna', array( 'slug' => $slug, 'description' => $data[1] ) );
		if ( ! is_wp_error( $term ) ) {
			update_term_meta( $term['term_id'], 'ekasyna_badge', $data[2] );
		}
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ekasyna_activate' );

/**
 * Pole „Plakietka” w kategorii (krótki tekst w kafelku, np. „BLIK”).
 *
 * @param WP_Term|string $term Termin lub slug taksonomii (przy dodawaniu).
 */
function ekasyna_term_badge_field( $term ) {
	$value = is_object( $term ) ? get_term_meta( $term->term_id, 'ekasyna_badge', true ) : '';
	wp_nonce_field( 'ekasyna_term_badge', 'ekasyna_term_badge_nonce' );
	if ( is_object( $term ) ) {
		?>
		<tr class="form-field">
			<th scope="row"><label for="ekasyna_badge"><?php esc_html_e( 'Plakietka', 'ekasyna' ); ?></label></th>
			<td><input name="ekasyna_badge" id="ekasyna_badge" type="text" value="<?php echo esc_attr( $value ); ?>" maxlength="5">
			<p class="description"><?php esc_html_e( 'Do 5 znaków, pokazywana w kafelku „Czego szukasz?”.', 'ekasyna' ); ?></p></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="ekasyna_content"><?php esc_html_e( 'Tekst pod listą kasyn', 'ekasyna' ); ?></label></th>
			<td><textarea name="ekasyna_content" id="ekasyna_content" rows="14" class="large-text code"><?php echo esc_textarea( get_term_meta( $term->term_id, 'ekasyna_content', true ) ); ?></textarea>
			<p class="description"><?php esc_html_e( 'HTML: nagłówki <h2>/<h3>, akapity, listy, tabele, <details> (FAQ). Z nagłówków H2 tworzy się spis treści.', 'ekasyna' ); ?></p></td>
		</tr>
		<?php
	} else {
		?>
		<div class="form-field">
			<label for="ekasyna_badge"><?php esc_html_e( 'Plakietka', 'ekasyna' ); ?></label>
			<input name="ekasyna_badge" id="ekasyna_badge" type="text" value="" maxlength="5">
		</div>
		<?php
	}
}
add_action( 'kategoria-kasyna_add_form_fields', 'ekasyna_term_badge_field' );
add_action( 'kategoria-kasyna_edit_form_fields', 'ekasyna_term_badge_field' );

/**
 * Zapis plakietki.
 *
 * @param int $term_id ID terminu.
 */
function ekasyna_save_term_badge( $term_id ) {
	if ( ! isset( $_POST['ekasyna_term_badge_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ekasyna_term_badge_nonce'] ) ), 'ekasyna_term_badge' ) ) {
		return;
	}
	if ( isset( $_POST['ekasyna_content'] ) ) {
		update_term_meta( $term_id, 'ekasyna_content', wp_kses_post( wp_unslash( $_POST['ekasyna_content'] ) ) );
	}
	if ( isset( $_POST['ekasyna_badge'] ) ) {
		update_term_meta( $term_id, 'ekasyna_badge', mb_substr( sanitize_text_field( wp_unslash( $_POST['ekasyna_badge'] ) ), 0, 5 ) );
	}
}
add_action( 'created_kategoria-kasyna', 'ekasyna_save_term_badge' );
add_action( 'edited_kategoria-kasyna', 'ekasyna_save_term_badge' );

/**
 * Archiwum i kategorie kasyn sortowane wg oceny.
 *
 * @param WP_Query $q Zapytanie.
 */
function ekasyna_sort_archives( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_post_type_archive( 'kasyno' ) || $q->is_tax( 'kategoria-kasyna' ) ) {
		$q->set( 'meta_key', '_ek_rating' );
		$q->set( 'orderby', array( 'meta_value_num' => 'DESC', 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		$q->set( 'posts_per_page', 50 );
	}
}
add_action( 'pre_get_posts', 'ekasyna_sort_archives' );
