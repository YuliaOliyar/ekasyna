<?php
/**
 * Spis treści (pasek boczny).
 *
 * @package ekasyna
 *
 * @var array $args { items: array }
 */

defined( 'ABSPATH' ) || exit;

$items  = isset( $args['items'] ) ? $args['items'] : array();
$inline = ! empty( $args['inline'] );
if ( ! $items ) {
	return;
}
?>
<nav class="ek-toc<?php echo $inline ? ' ek-toc--inline' : ''; ?>" aria-label="<?php esc_attr_e( 'Spis treści', 'ekasyna' ); ?>" data-ek-toc>
	<span class="ek-toc__title"><?php esc_html_e( 'Spis treści', 'ekasyna' ); ?></span>
	<ol>
		<?php foreach ( $items as $item ) : ?>
			<li><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>
		<?php endforeach; ?>
	</ol>
</nav>
