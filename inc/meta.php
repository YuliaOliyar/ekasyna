<?php
/**
 * Pola kasyna (meta box) i pola strony/wpisu.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definicje pól kasyna, pogrupowane.
 *
 * @return array
 */
function ekasyna_casino_fields() {
	return array(
		__( 'Ocena i wyróżnienia', 'ekasyna' ) => array(
			'rating'      => array( __( 'Ocena (0–10)', 'ekasyna' ), 'number', __( 'Np. 9.6. Na tej podstawie układa się ranking.', 'ekasyna' ) ),
			'highlight'   => array( __( 'Etykieta redakcji', 'ekasyna' ), 'text', __( 'Linia z ✓, np. „Doskonała reputacja”.', 'ekasyna' ) ),
			'badges'      => array( __( 'Plakietki', 'ekasyna' ), 'text', __( 'Po przecinku, np. „Nowość, BLIK”.', 'ekasyna' ) ),
			'brand_color' => array( __( 'Kolor marki (HEX)', 'ekasyna' ), 'color', __( 'Tło logo, gdy brak obrazka.', 'ekasyna' ) ),
		),
		__( 'Bonus', 'ekasyna' )               => array(
			'bonus_type'   => array( __( 'Rodzaj bonusu', 'ekasyna' ), 'text', __( 'Np. „Bonus powitalny”.', 'ekasyna' ) ),
			'bonus_pre'    => array( __( 'Bonus — linia 1', 'ekasyna' ), 'text', __( 'Np. „100% do”.', 'ekasyna' ) ),
			'bonus_amount' => array( __( 'Bonus — kwota (duża)', 'ekasyna' ), 'text', __( 'Np. „2 250 zł”.', 'ekasyna' ) ),
			'bonus_post'   => array( __( 'Bonus — linia 3', 'ekasyna' ), 'text', __( 'Np. „+ 200 DS”.', 'ekasyna' ) ),
			'min_deposit'  => array( __( 'Min. depozyt', 'ekasyna' ), 'text', '' ),
			'wager'        => array( __( 'Obrót (wager)', 'ekasyna' ), 'text', __( 'Np. „x35”.', 'ekasyna' ) ),
			'promo_code'   => array( __( 'Kod bonusowy', 'ekasyna' ), 'text', '' ),
			'bonus_terms'  => array( __( 'Krótki dopisek o warunkach', 'ekasyna' ), 'text', __( 'Domyślnie: „18+ · Obowiązuje regulamin bonusu”.', 'ekasyna' ) ),
			'bonuses'      => array( __( 'Wszystkie bonusy (recenzja)', 'ekasyna' ), 'textarea', __( 'Jeden bonus w linii, pola rozdzielone „|”: Tytuł | Rodzaj | Min. depozyt | Obrót | Kod | Link. Puste pola zostaw puste, np. „100% do 2000 zł + 75 FS | 1. depozyt | 40 zł | x35 | | ”. Pusty link = link partnerski kasyna.', 'ekasyna' ) ),
		),
		__( 'Fakty', 'ekasyna' )               => array(
			'license'    => array( __( 'Licencja — regulator', 'ekasyna' ), 'text', __( 'Np. „Curaçao”.', 'ekasyna' ) ),
			'license_no' => array( __( 'Numer licencji', 'ekasyna' ), 'text', __( 'Np. „OGL/2024/1111/0488”.', 'ekasyna' ) ),
			'payout'     => array( __( 'Czas wypłat', 'ekasyna' ), 'text', __( 'Np. „Do 24 h”.', 'ekasyna' ) ),
			'feature'    => array( __( 'Osobliwości', 'ekasyna' ), 'text', __( 'Krótki wyróżnik, np. „BLIK”.', 'ekasyna' ) ),
			'founded'    => array( __( 'Rok założenia', 'ekasyna' ), 'text', '' ),
			'operator'   => array( __( 'Właściciel (operator)', 'ekasyna' ), 'text', '' ),
			'website'    => array( __( 'Strona (wyświetlany adres)', 'ekasyna' ), 'text', __( 'Np. „slottyway.com/pl”. Link prowadzi do linku partnerskiego.', 'ekasyna' ) ),
			'support'    => array( __( 'Wsparcie', 'ekasyna' ), 'text', __( 'Np. „24/7 czat, e-mail, PL”.', 'ekasyna' ) ),
			'games'      => array( __( 'Liczba gier', 'ekasyna' ), 'text', __( 'Np. „5 400+”.', 'ekasyna' ) ),
			'currency'   => array( __( 'Waluta', 'ekasyna' ), 'text', __( 'Domyślnie PLN.', 'ekasyna' ) ),
			'app'        => array( __( 'Aplikacja mobilna', 'ekasyna' ), 'text', __( 'Np. „Android, iOS”.', 'ekasyna' ) ),
			'payments'    => array( __( 'Wpłaty', 'ekasyna' ), 'textarea', __( 'Po przecinku: „BLIK, Przelewy24, Skrill”.', 'ekasyna' ) ),
			'withdrawals' => array( __( 'Wypłaty', 'ekasyna' ), 'textarea', __( 'Po przecinku.', 'ekasyna' ) ),
			'providers'   => array( __( 'Producenci gier', 'ekasyna' ), 'textarea', __( 'Po przecinku: „NetEnt, Pragmatic Play, …”. Ponad 12 zwija się do „+N więcej”.', 'ekasyna' ) ),
		),
		__( 'Recenzja', 'ekasyna' )            => array(
			'pros'       => array( __( 'Plusy (każdy w nowej linii)', 'ekasyna' ), 'textarea', '' ),
			'cons'       => array( __( 'Minusy (każdy w nowej linii)', 'ekasyna' ), 'textarea', '' ),
			'test_date'  => array( __( 'Data testu', 'ekasyna' ), 'date', '' ),
			'checked_by' => array( __( 'Fakty sprawdził', 'ekasyna' ), 'text', '' ),
		),
		__( 'Link', 'ekasyna' )                => array(
			'affiliate_url' => array( __( 'Link do kasyna (partnerski)', 'ekasyna' ), 'url', __( 'Otwiera się w nowej karcie z rel="nofollow sponsored".', 'ekasyna' ) ),
		),
	);
}

/**
 * Meta boxy.
 */
function ekasyna_add_meta_boxes() {
	add_meta_box( 'ekasyna_casino', __( 'Dane kasyna', 'ekasyna' ), 'ekasyna_casino_box', 'kasyno', 'normal', 'high' );
	add_meta_box( 'ekasyna_article', __( 'Artykuł — e-kasyna', 'ekasyna' ), 'ekasyna_article_box', array( 'post', 'page' ), 'side' );
}
add_action( 'add_meta_boxes', 'ekasyna_add_meta_boxes' );

/**
 * Formularz pól kasyna.
 *
 * @param WP_Post $post Wpis.
 */
function ekasyna_casino_box( $post ) {
	wp_nonce_field( 'ekasyna_save_meta', 'ekasyna_meta_nonce' );
	echo '<style>.ek-mb h4{margin:18px 0 8px;font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:#646970}.ek-mb .ek-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px 18px}.ek-mb label{display:block;font-weight:600;margin-bottom:4px}.ek-mb input,.ek-mb textarea{width:100%}.ek-mb .ek-wide{grid-column:1/-1}.ek-mb .description{margin-top:3px}</style>';
	echo '<div class="ek-mb">';
	foreach ( ekasyna_casino_fields() as $group => $fields ) {
		echo '<h4>' . esc_html( $group ) . '</h4><div class="ek-grid">';
		foreach ( $fields as $key => $f ) {
			list( $label, $type, $help ) = $f;
			$name  = '_ek_' . $key;
			$value = get_post_meta( $post->ID, $name, true );
			$wide  = in_array( $type, array( 'textarea', 'url' ), true ) || 'payments' === $key ? ' ek-wide' : '';
			echo '<p class="ek-field' . esc_attr( $wide ) . '"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label>';
			switch ( $type ) {
				case 'textarea':
					echo '<textarea rows="4" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
					break;
				case 'number':
					echo '<input type="number" step="0.1" min="0" max="10" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
					break;
				case 'color':
					echo '<input type="text" pattern="#[0-9a-fA-F]{6}" placeholder="#15171C" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
					break;
				case 'url':
					echo '<input type="url" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
					break;
				case 'date':
					echo '<input type="date" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
					break;
				default:
					echo '<input type="text" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			}
			if ( $help ) {
				echo '<span class="description">' . esc_html( $help ) . '</span>';
			}
			echo '</p>';
		}
		echo '</div>';
	}
	echo '<p class="description">' . esc_html__( 'Logo kasyna = obrazek wyróżniający (najlepiej 340×168 px, PNG/WebP).', 'ekasyna' ) . '</p>';
	echo '</div>';
}

/**
 * Pola artykułu (wpis/strona): kto sprawdził fakty, czy pokazać spis treści.
 *
 * @param WP_Post $post Wpis.
 */
function ekasyna_article_box( $post ) {
	wp_nonce_field( 'ekasyna_save_meta', 'ekasyna_meta_nonce' );
	$checked = get_post_meta( $post->ID, '_ek_checked_by', true );
	$toc     = get_post_meta( $post->ID, '_ek_toc', true );
	?>
	<p><label for="_ek_checked_by"><strong><?php esc_html_e( 'Fakty sprawdził', 'ekasyna' ); ?></strong></label><br>
	<input type="text" class="widefat" id="_ek_checked_by" name="_ek_checked_by" value="<?php echo esc_attr( $checked ); ?>"></p>
	<p><label><input type="checkbox" name="_ek_toc" value="off" <?php checked( $toc, 'off' ); ?>> <?php esc_html_e( 'Ukryj spis treści', 'ekasyna' ); ?></label></p>
	<?php
}

/**
 * Zapis pól.
 *
 * @param int $post_id ID wpisu.
 */
function ekasyna_save_meta( $post_id ) {
	if ( ! isset( $_POST['ekasyna_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ekasyna_meta_nonce'] ) ), 'ekasyna_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'kasyno' === get_post_type( $post_id ) ) {
		foreach ( ekasyna_casino_fields() as $fields ) {
			foreach ( $fields as $key => $f ) {
				$name = '_ek_' . $key;
				if ( ! isset( $_POST[ $name ] ) ) {
					continue;
				}
				$raw = wp_unslash( $_POST[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				switch ( $f[1] ) {
					case 'textarea':
						$val = sanitize_textarea_field( $raw );
						break;
					case 'number':
						$val = '' === $raw ? '' : (string) max( 0, min( 10, round( (float) str_replace( ',', '.', $raw ), 1 ) ) );
						break;
					case 'color':
						$val = (string) sanitize_hex_color( $raw );
						break;
					case 'url':
						$val = esc_url_raw( $raw );
						break;
					default:
						$val = sanitize_text_field( $raw );
				}
				update_post_meta( $post_id, $name, $val );
			}
		}
		return;
	}

	if ( isset( $_POST['_ek_checked_by'] ) ) {
		update_post_meta( $post_id, '_ek_checked_by', sanitize_text_field( wp_unslash( $_POST['_ek_checked_by'] ) ) );
	}
	update_post_meta( $post_id, '_ek_toc', isset( $_POST['_ek_toc'] ) ? 'off' : '' );
}
add_action( 'save_post', 'ekasyna_save_meta' );
