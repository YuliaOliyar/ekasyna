<?php
/**
 * Trzy kolumny: Licencja | Czas wypłat | Osobliwości.
 *
 * @package ekasyna
 *
 * @var array $args { casino: array }
 */

defined( 'ABSPATH' ) || exit;

$c = $args['casino'];
?>
<dl class="ek-facts3">
	<div>
		<dt><?php echo ekasyna_icon( 'shield', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Licencja', 'ekasyna' ); ?></dt>
		<dd><?php echo esc_html( $c['license'] ); ?>
			<?php if ( $c['license_no'] ) : ?>
				<small><?php echo str_replace( '/', '/<wbr>', esc_html( $c['license_no'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small>
			<?php endif; ?>
		</dd>
	</div>
	<div>
		<dt><?php esc_html_e( 'Czas wypłat', 'ekasyna' ); ?></dt>
		<dd><?php echo esc_html( $c['payout'] ); ?></dd>
	</div>
	<div>
		<dt><?php esc_html_e( 'Osobliwości', 'ekasyna' ); ?></dt>
		<dd><?php echo esc_html( $c['feature'] ); ?></dd>
	</div>
</dl>
