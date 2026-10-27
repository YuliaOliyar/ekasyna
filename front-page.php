<?php
/**
 * Strona główna: hero → ranking → kategorie → jak oceniamy → artykuł strony (ze spisem treści i autorem).
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pick       = ekasyna_pick();
$count      = wp_count_posts( 'kasyno' );
$stat_1_num = ekasyna_mod( 'ekasyna_stat_1_num' );
$stats      = array(
	array( $stat_1_num ? $stat_1_num : (string) ( $count ? (int) $count->publish : 0 ), ekasyna_mod( 'ekasyna_stat_1_label' ) ),
	array( ekasyna_mod( 'ekasyna_stat_2_num' ), ekasyna_mod( 'ekasyna_stat_2_label' ) ),
	array( ekasyna_mod( 'ekasyna_stat_3_num' ), ekasyna_mod( 'ekasyna_stat_3_label' ) ),
);
$is_page    = 'page' === get_option( 'show_on_front' ) && have_posts();
?>

<section class="ek-container ek-hero">
	<div class="ek-hero__main">
		<div class="ek-tags">
			<?php foreach ( array( 'ekasyna_hero_tag_1', 'ekasyna_hero_tag_2' ) as $k ) : ?>
				<?php if ( ekasyna_mod( $k ) ) : ?>
					<span class="ek-tagpill"><?php echo esc_html( ekasyna_mod( $k ) ); ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<h1 class="ek-h1"><?php echo esc_html( ekasyna_mod( 'ekasyna_hero_title' ) ); ?> <span class="ek-accent"><?php echo esc_html( ekasyna_mod( 'ekasyna_hero_accent' ) ); ?></span></h1>
		<p class="ek-lead"><?php echo esc_html( ekasyna_mod( 'ekasyna_hero_text' ) ); ?></p>
		<?php
		if ( $is_page ) {
			the_post();
			get_template_part( 'template-parts/byline' );
			rewind_posts();
		}
		?>
		<div class="ek-hero__actions">
			<a class="ek-btn ek-btn--red ek-btn--lg" href="#ranking"><?php esc_html_e( 'Zobacz ranking', 'ekasyna' ); ?> <?php echo ekasyna_icon( 'arrow', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<a class="ek-btn ek-btn--outline ek-btn--lg" href="#jak-oceniamy"><?php esc_html_e( 'Jak oceniamy kasyna', 'ekasyna' ); ?></a>
		</div>
		<dl class="ek-stats">
			<?php foreach ( $stats as $s ) : ?>
				<?php if ( '' !== (string) $s[0] ) : ?>
					<div><dt><?php echo esc_html( $s[1] ); ?></dt><dd><?php echo esc_html( $s[0] ); ?></dd></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</dl>
	</div>

	<?php if ( $pick ) : ?>
		<aside class="ek-pick" aria-label="<?php echo esc_attr( ekasyna_mod( 'ekasyna_pick_label' ) ); ?>">
			<div class="ek-pick__top">
				<span class="ek-kicker ek-kicker--pink"><?php echo esc_html( ekasyna_mod( 'ekasyna_pick_label' ) ); ?> · <?php echo esc_html( wp_date( 'F' ) ); ?></span>
				<span class="ek-pick__score"><?php echo ekasyna_icon( 'star', 13, '#F2C94C' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $pick['score'] ); ?>/10</span>
			</div>
			<div class="ek-pick__brand">
				<?php echo ekasyna_casino_logo( $pick, 'ek-casino-logo ek-casino-logo--md' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div>
					<span class="ek-pick__name"><?php echo esc_html( $pick['name'] ); ?></span>
					<span class="ek-pick__meta"><?php echo esc_html( trim( implode( ' · ', array_filter( array( $pick['license'] && '—' !== $pick['license'] ? sprintf( /* translators: %s regulator */ __( 'Licencja %s', 'ekasyna' ), $pick['license'] ) : '', $pick['founded'] ? sprintf( /* translators: %s rok */ __( 'od %s', 'ekasyna' ), $pick['founded'] ) : '' ) ) ) ) ); ?></span>
				</div>
			</div>
			<div class="ek-pick__bonus">
				<span class="ek-pick__type"><?php echo esc_html( $pick['bonus_type'] ); ?></span>
				<?php get_template_part( 'template-parts/casino/bonus', null, array( 'casino' => $pick ) ); ?>
			</div>
			<div class="ek-pick__actions">
				<a class="ek-btn ek-btn--red" <?php echo ekasyna_play_attrs( $pick ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Odbierz bonus', 'ekasyna' ); ?></a>
				<a class="ek-btn ek-btn--ghost" href="<?php echo esc_url( $pick['review_url'] ); ?>"><?php esc_html_e( 'Recenzja', 'ekasyna' ); ?></a>
			</div>
			<span class="ek-terms ek-terms--dark"><?php esc_html_e( '18+ · Obowiązuje regulamin bonusu i wymagania obrotu.', 'ekasyna' ); ?></span>
		</aside>
	<?php endif; ?>
</section>

<div class="ek-container">
	<?php echo ekasyna_render_ranking(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>

<?php
$cats = get_terms( array( 'taxonomy' => 'kategoria-kasyna', 'hide_empty' => false, 'parent' => 0 ) );
if ( ! is_wp_error( $cats ) && $cats ) :
	?>
	<section class="ek-band">
		<div class="ek-container ek-section">
			<h2 class="ek-h2"><?php esc_html_e( 'Czego szukasz?', 'ekasyna' ); ?></h2>
			<div class="ek-cats">
				<?php foreach ( $cats as $t ) : ?>
					<?php $badge = get_term_meta( $t->term_id, 'ekasyna_badge', true ); ?>
					<a class="ek-cat" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
						<span class="ek-cat__badge"><?php echo esc_html( $badge ? $badge : mb_substr( $t->name, 0, 2 ) ); ?></span>
						<span class="ek-cat__title"><?php echo esc_html( $t->name ); ?></span>
						<?php if ( $t->description ) : ?>
							<span class="ek-cat__desc"><?php echo esc_html( wp_strip_all_tags( $t->description ) ); ?></span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section id="jak-oceniamy" class="ek-dark">
	<div class="ek-container ek-section">
		<div class="ek-dark__head">
			<h2 class="ek-h2"><?php esc_html_e( 'Jak oceniamy kasyna — 6 kryteriów', 'ekasyna' ); ?></h2>
			<p><?php esc_html_e( 'Kasyno, które nie wypłaca lub zmienia warunki po fakcie, trafia na czarną listę i znika z rankingu.', 'ekasyna' ); ?></p>
		</div>
		<ol class="ek-criteria">
			<?php
			$criteria = array(
				array( __( 'Licencja', 'ekasyna' ), __( 'Sprawdzamy regulatora i numer licencji w jego rejestrze.', 'ekasyna' ) ),
				array( __( 'Bonusy', 'ekasyna' ), __( 'Liczymy, czy wymagania obrotu da się realnie spełnić.', 'ekasyna' ) ),
				array( __( 'Gry', 'ekasyna' ), __( 'Liczba tytułów, kasyno na żywo, wersje demo.', 'ekasyna' ) ),
				array( __( 'Opinie graczy', 'ekasyna' ), __( 'Weryfikujemy skargi na wypłaty i ich autentyczność.', 'ekasyna' ) ),
				array( __( 'Dostawcy', 'ekasyna' ), __( 'Licencjonowani producenci gier i audyt RTP.', 'ekasyna' ) ),
				array( __( 'Płatności', 'ekasyna' ), __( 'PLN, BLIK, limity i czas wypłaty testowej.', 'ekasyna' ) ),
			);
			foreach ( $criteria as $i => $cr ) :
				?>
				<li>
					<span class="ek-criteria__no"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $cr[0] ); ?></h3>
					<p><?php echo esc_html( $cr[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php
if ( $is_page ) :
	while ( have_posts() ) :
		the_post();
		$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$toc     = ekasyna_toc_items( $content );
		?>
		<div class="ek-container ek-section ek-article-layout<?php echo ekasyna_show_toc( $toc ) ? '' : ' no-toc'; ?>">
			<?php if ( ekasyna_show_toc( $toc ) ) : ?>
				<aside class="ek-article-layout__side"><?php get_template_part( 'template-parts/toc', null, array( 'items' => $toc ) ); ?></aside>
			<?php endif; ?>
			<article <?php post_class( 'ek-article' ); ?>>
				<div class="ek-prose">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php get_template_part( 'template-parts/author-box' ); ?>
			</article>
		</div>
		<?php
	endwhile;
endif;

get_footer();
