<?php
/**
 * Spis treści — domyślnie zwinięty (rozwija się po kliknięciu).
 * Wariant boczny na szerokim ekranie JS otwiera automatycznie.
 *
 * @package ekasyna
 *
 * @var array $args { items: array, inline?: bool }
 */

defined( 'ABSPATH' ) || exit;

$items  = isset( $args['items'] ) ? $args['items'] : array();
$inline = ! empty( $args['inline'] );
if ( ! $items ) {
	return;
}
?>
<nav class="ek-toc<?php echo $inline ? ' ek-toc--inline' : ' ek-toc--side'; ?>" aria-label="<?php esc_attr_e( 'Spis treści', 'ekasyna' ); ?>" data-ek-toc>
	<details class="ek-toc__details">
		<summary class="ek-toc__title">
			<span><?php esc_html_e( 'Spis treści', 'ekasyna' ); ?> <span class="ek-toc__count">(<?php echo (int) count( $items ); ?>)</span></span>
			<svg class="ek-toc__chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"/></svg>
		</summary>
		<ol>
			<?php foreach ( $items as $item ) : ?>
				<li><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</details>
</nav>
