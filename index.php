<?php
/**
 * Lista wpisów (blog, archiwa, wyniki wyszukiwania).
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="ek-band ek-band--top">
	<div class="ek-container ek-page-hero">
		<h1 class="ek-h1 ek-h1--page">
			<?php
			if ( is_search() ) {
				/* translators: %s fraza */
				printf( esc_html__( 'Wyniki wyszukiwania: %s', 'ekasyna' ), esc_html( get_search_query() ) );
			} elseif ( is_archive() ) {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			} elseif ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				esc_html_e( 'Poradniki', 'ekasyna' );
			}
			?>
		</h1>
		<?php if ( is_archive() && get_the_archive_description() ) : ?>
			<div class="ek-lead"><?php echo wp_kses_post( get_the_archive_description() ); ?></div>
		<?php endif; ?>
	</div>
</section>

<div class="ek-container ek-section">
	<?php if ( have_posts() ) : ?>
		<div class="ek-posts">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'ek-postcard' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="ek-postcard__img" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large' ); ?></a>
					<?php endif; ?>
					<div class="ek-postcard__body">
						<span class="ek-postcard__date"><?php esc_html_e( 'Zaktualizowano:', 'ekasyna' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></time></span>
						<h2 class="ek-postcard__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="ek-pagination">
			<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
		</div>
	<?php else : ?>
		<p class="ek-empty"><?php esc_html_e( 'Nic nie znaleziono.', 'ekasyna' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
