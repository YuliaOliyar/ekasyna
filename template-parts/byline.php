<?php
/**
 * Linia autora pod tytułem: avatar, autor, data aktualizacji i publikacji.
 *
 * @package ekasyna
 *
 * @var array $args { badge?: string }
 */

defined( 'ABSPATH' ) || exit;

$author_id = (int) get_the_author_meta( 'ID' );
$badge     = isset( $args['badge'] ) ? $args['badge'] : __( 'Fakty sprawdzone', 'ekasyna' );
?>
<div class="ek-byline">
	<?php echo get_avatar( $author_id, 88, '', '', array( 'class' => 'ek-byline__avatar' ) ); ?>
	<div class="ek-byline__text">
		<span><?php esc_html_e( 'Autor:', 'ekasyna' ); ?> <a href="#autor"><?php the_author(); ?></a></span>
		<span class="ek-byline__dates">
			<?php esc_html_e( 'Zaktualizowano:', 'ekasyna' ); ?>
			<time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?></time>
			<?php if ( get_the_modified_date( 'Ymd' ) !== get_the_date( 'Ymd' ) ) : ?>
				· <?php esc_html_e( 'Opublikowano:', 'ekasyna' ); ?> <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
			<?php endif; ?>
		</span>
	</div>
	<?php if ( $badge ) : ?>
		<span class="ek-verified"><?php echo ekasyna_icon( 'check', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $badge ); ?></span>
	<?php endif; ?>
</div>
