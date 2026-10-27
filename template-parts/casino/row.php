<?php
/**
 * Wiersz kasyna — miejsca 4+.
 *
 * @package ekasyna
 *
 * @var array $args { casino: array, pos: int }
 */

defined( 'ABSPATH' ) || exit;

$c   = $args['casino'];
$pos = (int) $args['pos'];
?>
<li class="ek-row">
	<span class="ek-tag ek-tag--score"><?php echo ekasyna_icon( 'star', 13, '#F2C94C' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $c['score'] ); ?>/10</span><span class="screen-reader-text"><?php esc_html_e( 'ocena redakcji', 'ekasyna' ); ?></span></span>
	<span class="ek-tag ek-tag--rank ek-tag--light">TOP <?php echo (int) $pos; ?></span>

	<div class="ek-row__brand">
		<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--md' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div>
			<a class="ek-row__name" href="<?php echo esc_url( $c['review_url'] ); ?>"><?php echo esc_html( $c['name'] ); ?></a>
			<?php if ( $c['highlight'] ) : ?>
				<p class="ek-highlight ek-highlight--sm"><?php echo ekasyna_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $c['highlight'] ); ?></span></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="ek-row__bonus">
		<?php get_template_part( 'template-parts/casino/bonus', null, array( 'casino' => $c ) ); ?>
	</div>

	<div class="ek-row__facts">
		<?php get_template_part( 'template-parts/casino/facts', null, array( 'casino' => $c ) ); ?>
	</div>

	<div class="ek-row__cta">
		<a class="ek-pill ek-pill--sm" <?php echo ekasyna_play_attrs( $c ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<span><?php esc_html_e( 'Przejdź do kasyna', 'ekasyna' ); ?></span>
			<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</a>
		<a class="ek-reviewlink ek-reviewlink--sm" href="<?php echo esc_url( $c['review_url'] ); ?>"><?php printf( /* translators: %s nazwa kasyna */ esc_html__( 'Opinie o %s', 'ekasyna' ), esc_html( $c['name'] ) ); ?></a>
	</div>
</li>
