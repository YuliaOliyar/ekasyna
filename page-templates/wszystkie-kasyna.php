<?php
/**
 * Template Name: Wszystkie kasyna (A–Z)
 * Template Post Type: page
 *
 * Katalog wszystkich kasyn alfabetycznie. Treść strony (np. tekst SEO, FAQ) wyświetla się pod katalogiem.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	?>
	<section class="ek-band ek-band--top">
		<div class="ek-container ek-page-hero">
			<nav class="ek-breadcrumbs" aria-label="<?php esc_attr_e( 'Okruszki', 'ekasyna' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'ekasyna' ); ?></a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php the_title(); ?></span>
			</nav>
			<h1 class="ek-h1 ek-h1--page"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="ek-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/byline' ); ?>
		</div>
	</section>

	<div class="ek-container ek-section">
		<?php echo ekasyna_render_az(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php if ( trim( wp_strip_all_tags( $content ) ) ) : ?>
		<div class="ek-container ek-section ek-article-layout no-toc">
			<article <?php post_class( 'ek-article' ); ?>>
				<div class="ek-prose"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php get_template_part( 'template-parts/author-box' ); ?>
			</article>
		</div>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
