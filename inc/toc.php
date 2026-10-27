<?php
/**
 * Spis treści: kotwice w nagłówkach H2 i lista dla paska bocznego.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dodaje id do nagłówków H2 bez id.
 *
 * @param string $content Treść.
 * @return string
 */
function ekasyna_heading_ids( $content ) {
	if ( ! is_singular() || ! in_the_loop() ) {
		return $content;
	}
	$used = array();
	return preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		function ( $m ) use ( &$used ) {
			if ( preg_match( '/\sid=["\']/', $m[1] ) ) {
				return $m[0];
			}
			$slug = sanitize_title( wp_strip_all_tags( $m[2] ) );
			$slug = $slug ? $slug : 'sekcja';
			$base = $slug;
			$n    = 2;
			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $n++;
			}
			$used[ $slug ] = true;
			return '<h2' . $m[1] . ' id="' . esc_attr( $slug ) . '">' . $m[2] . '</h2>';
		},
		$content
	);
}
add_filter( 'the_content', 'ekasyna_heading_ids', 20 );

/**
 * Lista H2 z gotowej (przefiltrowanej) treści.
 *
 * @param string $html Treść po the_content.
 * @return array[] Lista [id, text].
 */
function ekasyna_toc_items( $html ) {
	$items = array();
	if ( preg_match_all( '#<h2[^>]*\sid=["\']([^"\']+)["\'][^>]*>(.*?)</h2>#is', $html, $m, PREG_SET_ORDER ) ) {
		foreach ( $m as $h ) {
			$items[] = array(
				'id'   => $h[1],
				'text' => wp_strip_all_tags( $h[2] ),
			);
		}
	}
	return $items;
}

/**
 * Czy pokazać spis treści dla wpisu.
 *
 * @param array $items Pozycje.
 * @param int   $post_id ID.
 * @return bool
 */
function ekasyna_show_toc( $items, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return count( $items ) >= 2 && 'off' !== get_post_meta( $post_id, '_ek_toc', true );
}
