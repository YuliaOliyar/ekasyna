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
	return ekasyna_add_heading_ids( $content );
}
add_filter( 'the_content', 'ekasyna_heading_ids', 20 );

/**
 * Nadaje id nagłówkom H2 w dowolnym HTML (np. treść pod rankingiem, opis kategorii).
 *
 * @param string $content HTML.
 * @return string
 */
function ekasyna_add_heading_ids( $content ) {
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

/**
 * Blok artykułu: spis treści (pasek boczny) + treść + opcjonalnie blok autora.
 * Używany pod rankingiem i w kategoriach kasyn.
 *
 * @param string       $html   Gotowy HTML treści.
 * @param WP_Post|null $source Wpis/strona, z której pochodzi treść (dla bloku autora).
 */
function ekasyna_article_block( $html, $source = null ) {
	if ( '' === trim( wp_strip_all_tags( $html ) ) ) {
		return;
	}
	$html    = ekasyna_add_heading_ids( $html );
	$toc     = ekasyna_toc_items( $html );
	$has_toc = count( $toc ) >= 2;
	?>
	<div class="ek-container ek-section ek-article-layout<?php echo $has_toc ? '' : ' no-toc'; ?>">
		<?php if ( $has_toc ) : ?>
			<aside class="ek-article-layout__side"><?php get_template_part( 'template-parts/toc', null, array( 'items' => $toc ) ); ?></aside>
		<?php endif; ?>
		<article class="ek-article">
			<div class="ek-prose"><?php echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php
			if ( $source instanceof WP_Post ) {
				$GLOBALS['post'] = $source; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $source );
				get_template_part( 'template-parts/author-box' );
				wp_reset_postdata();
			}
			?>
		</article>
	</div>
	<?php
}
