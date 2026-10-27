<?php
/**
 * Blok „Graj odpowiedzialnie”.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

$links = array();
foreach ( array( 1, 2 ) as $n ) {
	$label = ekasyna_mod( "ekasyna_rg_{$n}_label" );
	$url   = ekasyna_mod( "ekasyna_rg_{$n}_url" );
	if ( $label && $url ) {
		$links[] = array( $label, $url );
	}
}
?>
<section id="odpowiedzialna-gra" class="ek-container ek-section-sm">
	<div class="ek-rg">
		<span class="ek-rg__badge" aria-hidden="true">18+</span>
		<div class="ek-rg__text">
			<h2><?php echo esc_html( ekasyna_mod( 'ekasyna_rg_title' ) ); ?></h2>
			<p><?php echo esc_html( ekasyna_mod( 'ekasyna_rg_text' ) ); ?></p>
		</div>
		<?php if ( $links ) : ?>
			<div class="ek-rg__links">
				<?php foreach ( $links as $l ) : ?>
					<a class="ek-btn ek-btn--white" href="<?php echo esc_url( $l[1] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $l[0] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
