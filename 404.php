<?php
/**
 * 404.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="ek-container ek-section ek-404">
	<?php echo ekasyna_chip_svg( 96 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<h1 class="ek-h1 ek-h1--page"><?php esc_html_e( 'Ta strona nie istnieje', 'ekasyna' ); ?></h1>
	<p class="ek-lead"><?php esc_html_e( 'Możliwe, że adres się zmienił. Wróć do rankingu albo skorzystaj z wyszukiwarki.', 'ekasyna' ); ?></p>
	<?php get_search_form(); ?>
	<a class="ek-btn ek-btn--red ek-btn--lg" href="<?php echo esc_url( home_url( '/#ranking' ) ); ?>"><?php esc_html_e( 'Ranking kasyn', 'ekasyna' ); ?></a>
</div>
<?php
get_footer();
