<?php
/**
 * Ranking kasyn: zapytanie, render i shortcode [ekasyna_ranking].
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parametr filtra w URL (?ek_kat=blik).
 *
 * @param array $vars Zmienne zapytania.
 * @return array
 */
function ekasyna_query_vars( $vars ) {
	$vars[] = 'ek_kat';
	return $vars;
}
add_filter( 'query_vars', 'ekasyna_query_vars' );

/**
 * Kasyna do rankingu.
 *
 * @param int    $limit     Liczba.
 * @param string $kategoria Slug kategorii.
 * @return WP_Post[]
 */
function ekasyna_get_ranking( $limit = 10, $kategoria = '' ) {
	$args = array(
		'post_type'           => 'kasyno',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $limit,
		'meta_key'            => '_ek_rating', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'             => array( 'meta_value_num' => 'DESC', 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( $kategoria ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'kategoria-kasyna',
				'field'    => 'slug',
				'terms'    => sanitize_title( $kategoria ),
			),
		);
	}
	return get_posts( $args );
}

/**
 * HTML rankingu.
 *
 * @param array $atts {limit, podium, filters, kategoria, title, more}.
 * @return string
 */
function ekasyna_render_ranking( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'limit'     => (int) get_theme_mod( 'ekasyna_ranking_limit', 10 ),
			'podium'    => 3,
			'filters'   => 1,
			'kategoria' => '',
			'title'     => __( 'TOP kasyna online', 'ekasyna' ),
			'more'      => 1,
		),
		$atts,
		'ekasyna_ranking'
	);

	$active = $atts['kategoria'];
	if ( (int) $atts['filters'] ) {
		$from_url = get_query_var( 'ek_kat' );
		if ( $from_url ) {
			$active = sanitize_title( $from_url );
		}
	}

	$posts = ekasyna_get_ranking( $atts['limit'], $active );

	ob_start();
	?>
	<section id="ranking" class="ek-ranking" aria-labelledby="ek-ranking-title">
		<div class="ek-ranking__head">
			<div>
				<h2 id="ek-ranking-title" class="ek-h2"><?php echo esc_html( $atts['title'] ); ?></h2>
				<p class="ek-muted"><?php esc_html_e( 'Kolejność wg oceny redakcji. Linki mogą być partnerskie.', 'ekasyna' ); ?></p>
			</div>
			<?php
			if ( (int) $atts['filters'] ) :
				$terms = get_terms( array( 'taxonomy' => 'kategoria-kasyna', 'hide_empty' => true ) );
				if ( ! is_wp_error( $terms ) && $terms ) :
					$base = remove_query_arg( 'ek_kat' );
					?>
					<nav class="ek-chips" aria-label="<?php esc_attr_e( 'Filtry rankingu', 'ekasyna' ); ?>">
						<a class="ek-chip<?php echo $active ? '' : ' is-active'; ?>" href="<?php echo esc_url( $base . '#ranking' ); ?>" <?php echo $active ? '' : 'aria-current="true"'; ?>><?php esc_html_e( 'Wszystkie', 'ekasyna' ); ?></a>
						<?php foreach ( $terms as $t ) : ?>
							<a class="ek-chip<?php echo $active === $t->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'ek_kat', $t->slug, $base ) . '#ranking' ); ?>" <?php echo $active === $t->slug ? 'aria-current="true"' : ''; ?>><?php echo esc_html( $t->name ); ?></a>
						<?php endforeach; ?>
					</nav>
					<?php
				endif;
			endif;
			?>
		</div>

		<?php if ( ! $posts ) : ?>
			<p class="ek-empty"><?php esc_html_e( 'Brak kasyn w tej kategorii.', 'ekasyna' ); ?></p>
		<?php else : ?>
			<?php
			$podium = array_slice( $posts, 0, (int) $atts['podium'] );
			$rest   = array_slice( $posts, (int) $atts['podium'] );
			?>
			<?php if ( $podium ) : ?>
				<ol class="ek-podium" role="list">
					<?php
					foreach ( $podium as $i => $p ) {
						get_template_part( 'template-parts/casino/podium', null, array( 'casino' => ekasyna_casino( $p ), 'pos' => $i + 1 ) );
					}
					?>
				</ol>
			<?php endif; ?>
			<?php if ( $rest ) : ?>
				<ol class="ek-rows" role="list" start="<?php echo (int) $atts['podium'] + 1; ?>">
					<?php
					foreach ( $rest as $i => $p ) {
						get_template_part( 'template-parts/casino/row', null, array( 'casino' => ekasyna_casino( $p ), 'pos' => $i + 1 + (int) $atts['podium'] ) );
					}
					?>
				</ol>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( (int) $atts['more'] ) : ?>
			<div class="ek-ranking__more">
				<a class="ek-btn ek-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'kasyno' ) ); ?>"><?php esc_html_e( 'Pokaż wszystkie kasyna', 'ekasyna' ); ?></a>
			</div>
		<?php endif; ?>
		<p class="ek-fineprint"><?php esc_html_e( '18+ · Bonusy podlegają regulaminom operatorów. Sprawdź wymagania obrotu przed aktywacją.', 'ekasyna' ); ?></p>
	</section>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ekasyna_ranking', 'ekasyna_render_ranking' );
