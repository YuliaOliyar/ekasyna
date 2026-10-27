<?php
/**
 * Pełny ranking (archiwum kasyn) i kategorie kasyn — podium + wiersze.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

$term = is_tax( 'kategoria-kasyna' ) ? get_queried_object() : null;
?>
<section class="ek-band ek-band--top">
	<div class="ek-container ek-page-hero">
		<span class="ek-kicker"><?php esc_html_e( 'Ranking', 'ekasyna' ); ?></span>
		<h1 class="ek-h1 ek-h1--page"><?php echo esc_html( $term ? $term->name : __( 'Wszystkie kasyna online', 'ekasyna' ) ); ?></h1>
		<?php if ( $term && $term->description ) : ?>
			<p class="ek-lead"><?php echo esc_html( wp_strip_all_tags( $term->description ) ); ?></p>
		<?php endif; ?>
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
get_footer();
