<?php
/**
 * Pełny ranking (archiwum kasyn) i kategorie kasyn — podium + wiersze + tekst pod listą.
 *
 * Tekst pełnego rankingu: strona wybrana w Customizerze (Motyw e-kasyna → Ranking → „Strona z tekstem…”).
 * Tekst kategorii: pole „Tekst pod listą kasyn” w edycji kategorii; opis kategorii = lead pod H1.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

$term     = is_tax( 'kategoria-kasyna' ) ? get_queried_object() : null;
$page_id  = $term ? 0 : (int) ekasyna_mod( 'ekasyna_archive_page' );
$source   = $page_id ? get_post( $page_id ) : null;
$source   = ( $source && 'publish' === $source->post_status ) ? $source : null;
$title    = $term ? $term->name : ( $source ? get_the_title( $source ) : __( 'Ranking kasyn online', 'ekasyna' ) );
$lead     = $term ? wp_strip_all_tags( $term->description ) : ( $source && $source->post_excerpt ? $source->post_excerpt : '' );
$bottom   = '';
if ( $term ) {
	$bottom = (string) get_term_meta( $term->term_id, 'ekasyna_content', true );
	$bottom = $bottom ? wp_kses_post( wpautop( $bottom ) ) : '';
} elseif ( $source ) {
	$bottom = apply_filters( 'the_content', $source->post_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}
?>
<section class="ek-band ek-band--top">
	<div class="ek-container ek-page-hero">
		<nav class="ek-breadcrumbs" aria-label="<?php esc_attr_e( 'Okruszki', 'ekasyna' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'ekasyna' ); ?></a>
			<?php if ( $term ) : ?>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'kasyno' ) ); ?>"><?php esc_html_e( 'Ranking', 'ekasyna' ); ?></a>
			<?php endif; ?>
			<span aria-hidden="true">/</span>
			<span aria-current="page"><?php echo esc_html( $title ); ?></span>
		</nav>
		<h1 class="ek-h1 ek-h1--page"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $lead ) : ?>
			<p class="ek-lead"><?php echo esc_html( $lead ); ?></p>
		<?php endif; ?>
		<?php
		if ( $source ) {
			$GLOBALS['post'] = $source; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			setup_postdata( $source );
			get_template_part( 'template-parts/byline' );
			wp_reset_postdata();
		}
		?>
	</div>
</section>

<div class="ek-container ek-section">
	<?php if ( have_posts() ) : ?>
		<?php
		$i      = 0;
		$podium = array();
		$rest   = array();
		while ( have_posts() ) {
			the_post();
			++$i;
			if ( $i <= 3 ) {
				$podium[] = get_post();
			} else {
				$rest[] = get_post();
			}
		}
		wp_reset_postdata();
		?>
		<ol class="ek-podium" role="list">
			<?php
			foreach ( $podium as $n => $p ) {
				get_template_part( 'template-parts/casino/podium', null, array( 'casino' => ekasyna_casino( $p ), 'pos' => $n + 1 ) );
			}
			?>
		</ol>
		<?php if ( $rest ) : ?>
			<ol class="ek-rows" role="list" start="4">
				<?php
				foreach ( $rest as $n => $p ) {
					get_template_part( 'template-parts/casino/row', null, array( 'casino' => ekasyna_casino( $p ), 'pos' => $n + 4 ) );
				}
				?>
			</ol>
		<?php endif; ?>
		<p class="ek-fineprint"><?php esc_html_e( '18+ · Bonusy podlegają regulaminom operatorów. Sprawdź wymagania obrotu przed aktywacją.', 'ekasyna' ); ?></p>
	<?php else : ?>
		<p class="ek-empty"><?php esc_html_e( 'Brak kasyn w tej kategorii.', 'ekasyna' ); ?></p>
	<?php endif; ?>
</div>

<?php
ekasyna_article_block( $bottom, $source );

get_footer();
