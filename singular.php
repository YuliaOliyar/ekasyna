<?php
/**
 * Wpis i strona: tytuł z linią autora, spis treści, treść, blok autora.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	$toc     = ekasyna_toc_items( $content );
	$has_toc = ekasyna_show_toc( $toc );
	$cats    = is_single() ? get_the_category() : array();
	?>
	<section class="ek-band ek-band--top">
		<div class="ek-container ek-page-hero">
			<?php if ( $cats ) : ?>
				<a class="ek-kicker" href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
			<?php endif; ?>
			<h1 class="ek-h1 ek-h1--page"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="ek-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/byline' ); ?>
		</div>
	</section>

	<div class="ek-container ek-section ek-article-layout<?php echo $has_toc ? '' : ' no-toc'; ?>">
		<?php if ( $has_toc ) : ?>
			<aside class="ek-article-layout__side"><?php get_template_part( 'template-parts/toc', null, array( 'items' => $toc ) ); ?></aside>
		<?php endif; ?>
		<article <?php post_class( 'ek-article' ); ?>>
			<?php if ( has_post_thumbnail() && is_single() ) : ?>
				<figure class="ek-article__cover"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="ek-prose">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php get_template_part( 'template-parts/author-box' ); ?>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>
	</div>
	<?php
endwhile;

get_footer();
