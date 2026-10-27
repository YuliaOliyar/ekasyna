<?php
/**
 * Komentarze.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="komentarze" class="ek-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="ek-h2 ek-h2--sm">
			<?php
			/* translators: %d liczba komentarzy */
			printf( esc_html( _n( '%d komentarz', '%d komentarzy', get_comments_number(), 'ekasyna' ) ), (int) get_comments_number() );
			?>
		</h2>
		<ol class="ek-comments__list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 44 ) ); ?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php comment_form( array( 'class_submit' => 'ek-btn ek-btn--red' ) ); ?>
</section>
