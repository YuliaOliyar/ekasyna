<?php
/**
 * Blok autora z datami (publikacja, aktualizacja, test, kto sprawdził).
 *
 * @package ekasyna
 *
 * @var array $args { label?: string, test_date?: string }
 */

defined( 'ABSPATH' ) || exit;

$author_id = (int) get_the_author_meta( 'ID' );
$position  = get_user_meta( $author_id, 'ekasyna_position', true );
$topics    = array_filter( array_map( 'trim', explode( ',', (string) get_user_meta( $author_id, 'ekasyna_topics', true ) ) ) );
$bio       = get_the_author_meta( 'description' );
$checked   = get_post_meta( get_the_ID(), '_ek_checked_by', true );
$label     = isset( $args['label'] ) ? $args['label'] : __( 'O autorze', 'ekasyna' );
$test_date = isset( $args['test_date'] ) ? $args['test_date'] : '';
?>
<section id="autor" class="ek-author" aria-label="<?php echo esc_attr( $label ); ?>">
	<div class="ek-author__main">
		<?php echo get_avatar( $author_id, 192, '', '', array( 'class' => 'ek-author__photo' ) ); ?>
		<div class="ek-author__info">
			<span class="ek-kicker"><?php echo esc_html( $label ); ?></span>
			<a class="ek-author__name" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a>
			<?php if ( $position ) : ?>
				<span class="ek-author__position"><?php echo esc_html( $position ); ?></span>
			<?php endif; ?>
			<?php if ( $bio ) : ?>
				<p class="ek-author__bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
			<?php if ( $topics ) : ?>
				<ul class="ek-author__topics">
					<?php foreach ( $topics as $topic ) : ?>
						<li><?php echo esc_html( $topic ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<a class="ek-btn ek-btn--outline ek-btn--sm" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php esc_html_e( 'Wszystkie artykuły', 'ekasyna' ); ?></a>
	</div>
	<dl class="ek-author__dates">
		<div><dt><?php esc_html_e( 'Opublikowano', 'ekasyna' ); ?></dt><dd><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></time></dd></div>
		<div><dt><?php esc_html_e( 'Ostatnia aktualizacja', 'ekasyna' ); ?></dt><dd><time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></time></dd></div>
		<?php if ( $test_date ) : ?>
			<div><dt><?php esc_html_e( 'Data testu', 'ekasyna' ); ?></dt><dd><time datetime="<?php echo esc_attr( $test_date ); ?>"><?php echo esc_html( mysql2date( 'd.m.Y', $test_date ) ); ?></time></dd></div>
		<?php endif; ?>
		<?php if ( $checked ) : ?>
			<div><dt><?php esc_html_e( 'Fakty sprawdził', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $checked ); ?></dd></div>
		<?php endif; ?>
	</dl>
</section>
