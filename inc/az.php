<?php
/**
 * Katalog A–Z wszystkich kasyn: indeks liter, wyszukiwarka, lista pogrupowana alfabetycznie.
 * Shortcode: [ekasyna_az].
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Litera grupy dla nazwy (cyfry → „0-9”, polskie znaki → litera bazowa).
 *
 * @param string $name Nazwa.
 * @return string
 */
function ekasyna_az_letter( $name ) {
	$first = mb_strtoupper( mb_substr( trim( $name ), 0, 1 ) );
	$first = strtoupper( remove_accents( $first ) );
	if ( preg_match( '/[0-9]/', $first ) ) {
		return '0-9';
	}
	return preg_match( '/[A-Z]/', $first ) ? $first : '#';
}

/**
 * HTML katalogu.
 *
 * @param array $atts { search: 1|0, scores: 1|0 }.
 * @return string
 */
function ekasyna_render_az( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'search' => 1,
			'scores' => 1,
		),
		$atts,
		'ekasyna_az'
	);

	$posts = get_posts(
		array(
			'post_type'      => 'kasyno',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	$groups = array();
	foreach ( $posts as $p ) {
		$groups[ ekasyna_az_letter( $p->post_title ) ][] = $p;
	}

	$letters = array_merge( array( '0-9' ), range( 'A', 'Z' ) );
	if ( isset( $groups['#'] ) ) {
		$letters[] = '#';
	}

	ob_start();
	?>
	<div class="ek-az" data-ek-az>
		<div class="ek-az__bar">
			<nav class="ek-az__index" aria-label="<?php esc_attr_e( 'Indeks alfabetyczny', 'ekasyna' ); ?>">
				<?php foreach ( $letters as $l ) : ?>
					<?php if ( isset( $groups[ $l ] ) ) : ?>
						<a href="#litera-<?php echo esc_attr( sanitize_title( '0-9' === $l ? '0-9' : ( '#' === $l ? 'inne' : $l ) ) ); ?>"><?php echo esc_html( $l ); ?></a>
					<?php else : ?>
						<span aria-hidden="true"><?php echo esc_html( $l ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
			<?php if ( (int) $atts['search'] ) : ?>
				<label class="ek-az__search">
					<span class="screen-reader-text"><?php esc_html_e( 'Szukaj kasyna', 'ekasyna' ); ?></span>
					<?php echo ekasyna_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<input type="search" placeholder="<?php esc_attr_e( 'Szukaj kasyna…', 'ekasyna' ); ?>" data-ek-az-search autocomplete="off">
				</label>
			<?php endif; ?>
		</div>

		<p class="ek-az__count" data-ek-az-count aria-live="polite">
			<?php
			/* translators: %d liczba kasyn */
			printf( esc_html( _n( '%d kasyno w katalogu', '%d kasyn w katalogu', count( $posts ), 'ekasyna' ) ), (int) count( $posts ) );
			?>
		</p>

		<?php if ( ! $posts ) : ?>
			<p class="ek-empty"><?php esc_html_e( 'Brak kasyn.', 'ekasyna' ); ?></p>
		<?php endif; ?>

		<?php foreach ( $letters as $l ) : ?>
			<?php
			if ( empty( $groups[ $l ] ) ) {
				continue;
			}
			$anchor = sanitize_title( '0-9' === $l ? '0-9' : ( '#' === $l ? 'inne' : $l ) );
			?>
			<section class="ek-az__group" id="litera-<?php echo esc_attr( $anchor ); ?>" data-ek-az-group>
				<h2 class="ek-az__letter"><?php echo esc_html( $l ); ?></h2>
				<ul class="ek-az__list">
					<?php foreach ( $groups[ $l ] as $p ) : ?>
						<?php $c = ekasyna_casino( $p ); ?>
						<li data-ek-az-item data-name="<?php echo esc_attr( mb_strtolower( remove_accents( $c['name'] ) ) ); ?>">
							<a class="ek-az__item" href="<?php echo esc_url( $c['review_url'] ); ?>">
								<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="ek-az__name"><?php echo esc_html( $c['name'] ); ?></span>
								<?php if ( (int) $atts['scores'] && $c['rating'] > 0 ) : ?>
									<span class="ek-az__score"><?php echo ekasyna_icon( 'star', 12, '#E0A100' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $c['score'] ); ?></span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endforeach; ?>

		<p class="ek-empty" data-ek-az-empty hidden><?php esc_html_e( 'Nie znaleziono kasyna o tej nazwie.', 'ekasyna' ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ekasyna_az', 'ekasyna_render_az' );
