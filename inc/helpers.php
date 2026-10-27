<?php
/**
 * Funkcje pomocnicze: ikony, logo, dane kasyna.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG ikon używanych w motywie.
 *
 * @param string $name  Nazwa ikony.
 * @param int    $size  Rozmiar w px.
 * @param string $color Kolor obrysu/wypełnienia.
 * @return string
 */
function ekasyna_icon( $name, $size = 18, $color = 'currentColor' ) {
	$paths = array(
		'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'up'     => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'check'  => '<path d="M5 12l5 5 9-10"/>',
		'minus'  => '<path d="M6 12h12"/>',
		'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'  => '<path d="M6 6l12 12M18 6L6 18"/>',
		'shield' => '<path d="M12 3l7 3v5c0 4.5-3 8.3-7 10-4-1.7-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/>',
		'info'   => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>',
	);

	if ( 'star' === $name ) {
		return sprintf(
			'<svg class="ek-icon" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="%2$s" aria-hidden="true" focusable="false"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.2 6.5L12 17.4l-5.9 3.1 1.2-6.5L2.5 9.4l6.6-.9z"/></svg>',
			(int) $size,
			esc_attr( $color )
		);
	}

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="ek-icon" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="%2$s" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		(int) $size,
		esc_attr( $color ),
		$paths[ $name ] // Statyczne ścieżki zdefiniowane powyżej.
	);
}

/**
 * Znak (żeton kasynowy z literą „e”).
 *
 * @param int    $size    Rozmiar.
 * @param string $variant 'color' | 'mono'.
 * @return string
 */
function ekasyna_chip_svg( $size = 40, $variant = 'color' ) {
	$ring  = 'mono' === $variant ? '#FFFFFF' : '#C8102E';
	$notch = 'mono' === $variant ? '#C8102E' : '#FFFFFF';
	$inner = 'mono' === $variant ? '#C8102E' : '#A30D25';
	$rects = '';
	for ( $k = 0; $k < 360; $k += 45 ) {
		$rects .= '<rect x="21.5" y="1" width="5" height="7" rx="1.2" transform="rotate(' . $k . ' 24 24)"/>';
	}
	return sprintf(
		'<svg class="ek-mark" width="%1$d" height="%1$d" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><circle cx="24" cy="24" r="23" fill="%2$s"/><g fill="%3$s">%4$s</g><circle cx="24" cy="24" r="15.5" fill="%5$s"/><circle cx="24" cy="24" r="15.5" fill="none" stroke="#FFFFFF" stroke-opacity=".55" stroke-width="1.4" stroke-dasharray="2.2 2.6"/><path fill="#FFFFFF" fill-rule="evenodd" d="M24.6 33.4c-5 0-8.3-3.3-8.3-8.4 0-5 3.4-8.6 8.1-8.6 4.8 0 7.6 3.3 7.6 8v1.3H20.8c.3 2.6 1.9 4.1 4.2 4.1 1.6 0 2.8-.7 3.4-2h3.6c-.8 3.3-3.6 5.6-7.4 5.6zm-3.7-10.2h7.1c-.2-2.2-1.5-3.6-3.5-3.6s-3.3 1.4-3.6 3.6z"/></svg>',
		(int) $size,
		$ring,
		$notch,
		$rects,
		$inner
	);
}

/**
 * Logo tekstowe z romb-karo zamiast myślnika.
 *
 * @param string $context 'light' | 'dark'.
 * @return string
 */
function ekasyna_wordmark( $context = 'light' ) {
	$diamond = 'dark' === $context ? '#F2B8C1' : '#C8102E';
	return '<span class="ek-wordmark">e<svg class="ek-wordmark__diamond" viewBox="0 0 10 10" aria-hidden="true" focusable="false"><path d="M5 0L10 5 5 10 0 5z" fill="' . esc_attr( $diamond ) . '"/></svg>kasyna<span class="ek-wordmark__tld">.com</span></span>';
}

/**
 * Pełne logo (logo z Customizera albo znak + nazwa).
 *
 * @param string $context 'light' | 'dark'.
 */
function ekasyna_the_logo( $context = 'light' ) {
	if ( 'light' === $context && has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="ek-logo ek-logo--%1$s" href="%2$s" rel="home" aria-label="%3$s">%4$s%5$s</a>',
		esc_attr( $context ),
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		ekasyna_chip_svg( 'dark' === $context ? 36 : 42 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		ekasyna_wordmark( $context ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}

/**
 * Pobiera pole meta kasyna.
 *
 * @param int    $post_id ID wpisu.
 * @param string $key     Klucz bez prefiksu.
 * @param mixed  $default Domyślna wartość.
 * @return mixed
 */
function ekasyna_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_ek_' . $key, true );
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Inicjały z nazwy kasyna (gdy brak logo).
 *
 * @param string $name Nazwa.
 * @return string
 */
function ekasyna_initials( $name ) {
	$name  = trim( preg_replace( '/\b(casino|kasyno)\b/i', '', $name ) );
	$parts = preg_split( '/\s+/', $name );
	if ( count( $parts ) > 1 ) {
		$out = mb_substr( $parts[0], 0, 1 ) . mb_substr( $parts[1], 0, 1 );
	} else {
		$out = mb_substr( $name, 0, 2 );
	}
	return mb_strtoupper( $out );
}

/**
 * Wszystkie dane kasyna w jednej tablicy dla szablonów.
 *
 * @param int|WP_Post $post Wpis kasyna.
 * @return array
 */
function ekasyna_casino( $post ) {
	$post = get_post( $post );
	$id   = $post->ID;

	$rating = (float) str_replace( ',', '.', ekasyna_meta( $id, 'rating', 0 ) );
	$rating = max( 0, min( 10, $rating ) );

	$badges = array_filter( array_map( 'trim', explode( ',', (string) ekasyna_meta( $id, 'badges' ) ) ) );
	$aff    = ekasyna_meta( $id, 'affiliate_url' );

	return array(
		'id'          => $id,
		'name'        => get_the_title( $id ),
		'review_url'  => get_permalink( $id ),
		'play_url'    => $aff ? $aff : get_permalink( $id ),
		'is_external' => (bool) $aff,
		'rating'      => $rating,
		'score'       => number_format_i18n( $rating, 1 ),
		'bonus_pre'   => ekasyna_meta( $id, 'bonus_pre' ),
		'bonus_amt'   => ekasyna_meta( $id, 'bonus_amount' ),
		'bonus_post'  => ekasyna_meta( $id, 'bonus_post' ),
		'bonus_type'  => ekasyna_meta( $id, 'bonus_type', __( 'Bonus powitalny', 'ekasyna' ) ),
		'bonus_terms' => ekasyna_meta( $id, 'bonus_terms' ),
		'wager'       => ekasyna_meta( $id, 'wager' ),
		'min_deposit' => ekasyna_meta( $id, 'min_deposit' ),
		'promo_code'  => ekasyna_meta( $id, 'promo_code' ),
		'license'     => ekasyna_meta( $id, 'license', '—' ),
		'license_no'  => ekasyna_meta( $id, 'license_no' ),
		'payout'      => ekasyna_meta( $id, 'payout', '—' ),
		'feature'     => ekasyna_meta( $id, 'feature', '—' ),
		'highlight'   => ekasyna_meta( $id, 'highlight' ),
		'badges'      => $badges,
		'color'       => sanitize_hex_color( ekasyna_meta( $id, 'brand_color', '#15171C' ) ) ?: '#15171C',
		'initials'    => ekasyna_initials( get_the_title( $id ) ),
		'founded'     => ekasyna_meta( $id, 'founded' ),
		'operator'    => ekasyna_meta( $id, 'operator' ),
		'games'       => ekasyna_meta( $id, 'games' ),
		'currency'    => ekasyna_meta( $id, 'currency', 'PLN' ),
		'app'         => ekasyna_meta( $id, 'app' ),
		'payments'    => array_values( array_filter( array_map( 'trim', explode( ',', (string) ekasyna_meta( $id, 'payments' ) ) ) ) ),
		'withdrawals' => array_values( array_filter( array_map( 'trim', explode( ',', (string) ekasyna_meta( $id, 'withdrawals' ) ) ) ) ),
		'providers'   => array_values( array_filter( array_map( 'trim', explode( ',', (string) ekasyna_meta( $id, 'providers' ) ) ) ) ),
		'website'     => ekasyna_meta( $id, 'website' ),
		'support'     => ekasyna_meta( $id, 'support' ),
		'bonuses'     => ekasyna_parse_bonuses( (string) ekasyna_meta( $id, 'bonuses' ) ),
		'pros'        => array_filter( array_map( 'trim', preg_split( '/\r\n|\n/', (string) ekasyna_meta( $id, 'pros' ) ) ) ),
		'cons'        => array_filter( array_map( 'trim', preg_split( '/\r\n|\n/', (string) ekasyna_meta( $id, 'cons' ) ) ) ),
		'test_date'   => ekasyna_meta( $id, 'test_date' ),
		'checked_by'  => ekasyna_meta( $id, 'checked_by' ),
	);
}

/**
 * Logo kasyna: obrazek wyróżniający albo kafelek z inicjałami w kolorze marki.
 *
 * @param array  $c     Dane kasyna.
 * @param string $class Klasa CSS.
 * @return string
 */
function ekasyna_casino_logo( $c, $class = 'ek-casino-logo' ) {
	if ( has_post_thumbnail( $c['id'] ) ) {
		return '<span class="' . esc_attr( $class ) . ' has-image" style="--brand:' . esc_attr( $c['color'] ) . '">' .
			get_the_post_thumbnail( $c['id'], 'ekasyna-logo', array( 'alt' => sprintf( /* translators: %s nazwa kasyna */ __( 'Logo %s', 'ekasyna' ), $c['name'] ), 'loading' => 'lazy' ) ) .
			'</span>';
	}
	return '<span class="' . esc_attr( $class ) . '" style="--brand:' . esc_attr( $c['color'] ) . '" role="img" aria-label="' . esc_attr( sprintf( /* translators: %s nazwa kasyna */ __( 'Logo %s', 'ekasyna' ), $c['name'] ) ) . '">' . esc_html( $c['initials'] ) . '</span>';
}

/**
 * Atrybuty linku „Przejdź do kasyna”.
 *
 * @param array $c Dane kasyna.
 * @return string
 */
function ekasyna_play_attrs( $c ) {
	$attrs = 'href="' . esc_url( $c['play_url'] ) . '"';
	if ( $c['is_external'] ) {
		$attrs .= ' target="_blank" rel="nofollow sponsored noopener"';
	}
	return $attrs;
}

/**
 * Data w formacie polskim.
 *
 * @param string $format Format.
 * @param int    $post   ID wpisu.
 * @param bool   $modified Data modyfikacji.
 * @return string
 */
function ekasyna_date( $format = 'j F Y', $post = null, $modified = true ) {
	return $modified ? get_the_modified_date( $format, $post ) : get_the_date( $format, $post );
}

/**
 * Menu awaryjne, gdy nie przypisano menu „Menu główne”.
 */
function ekasyna_menu_fallback() {
	echo '<ul class="ek-nav__list">';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'kasyno' ) ) . '">' . esc_html__( 'Kasyna', 'ekasyna' ) . '</a></li>';
	$terms = get_terms( array( 'taxonomy' => 'kategoria-kasyna', 'hide_empty' => false, 'number' => 4 ) );
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			echo '<li><a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a></li>';
		}
	}
	echo '</ul>';
}

/**
 * Parsuje listę bonusów z pola tekstowego (linia = bonus, pola rozdzielone „|”).
 *
 * @param string $raw Treść pola.
 * @return array[]
 */
function ekasyna_parse_bonuses( $raw ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$f     = array_map( 'trim', explode( '|', $line ) );
		$f     = array_pad( $f, 6, '' );
		$out[] = array(
			'title' => $f[0],
			'type'  => $f[1],
			'min'   => $f[2],
			'wager' => $f[3],
			'code'  => $f[4],
			'url'   => $f[5],
		);
	}
	return array_values( array_filter( $out, function ( $b ) {
		return '' !== $b['title'];
	} ) );
}
