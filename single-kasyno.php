<?php
/**
 * Recenzja kasyna.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$c       = ekasyna_casino( get_the_ID() );
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	$pos     = 0;
	foreach ( ekasyna_get_ranking( 50 ) as $i => $p ) {
		if ( $p->ID === $c['id'] ) {
			$pos = $i + 1;
			break;
		}
	}
	?>
	<section class="ek-band ek-band--top">
		<div class="ek-container ek-review-hero">
			<nav class="ek-breadcrumbs" aria-label="<?php esc_attr_e( 'Okruszki', 'ekasyna' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'ekasyna' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'kasyno' ) ); ?>"><?php esc_html_e( 'Recenzje kasyn', 'ekasyna' ); ?></a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php the_title(); ?></span>
			</nav>
			<div class="ek-review-hero__row">
				<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--xl' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="ek-review-hero__title">
					<div class="ek-tags">
						<?php if ( $c['highlight'] ) : ?>
							<span class="ek-tagpill ek-tagpill--green"><?php echo esc_html( $c['highlight'] ); ?></span>
						<?php endif; ?>
						<?php if ( $pos ) : ?>
							<span class="ek-tagpill"><?php printf( /* translators: %d miejsce */ esc_html__( '#%d w rankingu', 'ekasyna' ), (int) $pos ); ?></span>
						<?php endif; ?>
						<?php foreach ( $c['badges'] as $b ) : ?>
							<span class="ek-tagpill"><?php echo esc_html( $b ); ?></span>
						<?php endforeach; ?>
					</div>
					<h1 class="ek-h1 ek-h1--review"><?php printf( /* translators: %s nazwa kasyna */ esc_html__( '%s — recenzja i opinie', 'ekasyna' ), esc_html( get_the_title() ) ); ?></h1>
					<?php get_template_part( 'template-parts/byline', null, array( 'badge' => __( 'Przetestowane przez redakcję', 'ekasyna' ) ) ); ?>
				</div>
				<?php if ( $c['rating'] > 0 ) : ?>
					<div class="ek-scorebox">
						<span><?php esc_html_e( 'Ocena redakcji', 'ekasyna' ); ?></span>
						<strong><?php echo esc_html( $c['score'] ); ?><small>/ 10</small></strong>
						<span class="ek-meter"><span style="width:<?php echo esc_attr( round( $c['rating'] * 10 ) ); ?>%"></span></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( $c['bonuses'] ) : ?>
		<div class="ek-container ek-bonuses-wrap">
			<?php get_template_part( 'template-parts/casino/bonus-list', null, array( 'casino' => $c ) ); ?>
		</div>
	<?php endif; ?>

	<div class="ek-container ek-section ek-review-layout">
		<article <?php post_class( 'ek-review' ); ?>>
			<?php get_template_part( 'template-parts/casino/info-table', null, array( 'casino' => $c ) ); ?>

			<?php if ( $c['pros'] || $c['cons'] ) : ?>
				<div class="ek-proscons">
					<?php if ( $c['pros'] ) : ?>
						<section class="ek-proscons__box ek-proscons__box--pros">
							<h3><?php esc_html_e( 'Plusy', 'ekasyna' ); ?></h3>
							<ul>
								<?php foreach ( $c['pros'] as $item ) : ?>
									<li><?php echo ekasyna_icon( 'check', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $item ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>
					<?php if ( $c['cons'] ) : ?>
						<section class="ek-proscons__box ek-proscons__box--cons">
							<h3><?php esc_html_e( 'Minusy', 'ekasyna' ); ?></h3>
							<ul>
								<?php foreach ( $c['cons'] as $item ) : ?>
									<li><?php echo ekasyna_icon( 'minus', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $item ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			$toc = ekasyna_toc_items( $content );
			if ( ekasyna_show_toc( $toc ) ) {
				get_template_part( 'template-parts/toc', null, array( 'items' => $toc, 'inline' => true ) );
			}
			?>

			<div class="ek-prose">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php get_template_part( 'template-parts/author-box', null, array( 'label' => __( 'Autor recenzji', 'ekasyna' ), 'test_date' => $c['test_date'] ) ); ?>
		</article>

		<aside class="ek-review-layout__side">
			<div class="ek-bonuscard">
				<span class="ek-kicker ek-kicker--pink"><?php echo esc_html( $c['bonus_type'] ); ?></span>
				<?php get_template_part( 'template-parts/casino/bonus', null, array( 'casino' => $c ) ); ?>
				<dl class="ek-bonuscard__list">
					<?php if ( $c['min_deposit'] ) : ?>
						<div><dt><?php esc_html_e( 'Min. depozyt', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $c['min_deposit'] ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $c['wager'] ) : ?>
						<div><dt><?php esc_html_e( 'Obrót', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $c['wager'] ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $c['promo_code'] ) : ?>
						<div><dt><?php esc_html_e( 'Kod bonusowy', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $c['promo_code'] ); ?></dd></div>
					<?php endif; ?>
				</dl>
				<a class="ek-pill" <?php echo ekasyna_play_attrs( $c ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<span><?php esc_html_e( 'Odbierz bonus', 'ekasyna' ); ?></span>
					<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<span class="ek-terms ek-terms--dark"><?php esc_html_e( '18+ · Obowiązuje regulamin operatora. Link partnerski.', 'ekasyna' ); ?></span>
			</div>

			<?php
			$similar = array_filter(
				ekasyna_get_ranking( 4 ),
				function ( $p ) use ( $c ) {
					return $p->ID !== $c['id'];
				}
			);
			if ( $similar ) :
				?>
				<div class="ek-similar">
					<span class="ek-similar__title"><?php esc_html_e( 'Podobne kasyna', 'ekasyna' ); ?></span>
					<ul>
						<?php foreach ( array_slice( $similar, 0, 3 ) as $p ) : ?>
							<?php $s = ekasyna_casino( $p ); ?>
							<li><a href="<?php echo esc_url( $s['review_url'] ); ?>"><span><?php echo esc_html( $s['name'] ); ?></span><strong><?php echo esc_html( $s['score'] ); ?></strong></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</aside>
	</div>
	<?php
endwhile;

get_footer();
