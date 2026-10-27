<?php
/**
 * Karta kasyna — podium TOP 3.
 *
 * @package ekasyna
 *
 * @var array $args { casino: array, pos: int }
 */

defined( 'ABSPATH' ) || exit;

$c   = $args['casino'];
$pos = (int) $args['pos'];
?>
<li class="ek-pcard ek-pcard--<?php echo (int) $pos; ?>">
	<span class="ek-tag ek-tag--score"><?php echo ekasyna_icon( 'star', 14, '#F2C94C' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $c['score'] ); ?>/10</span><span class="screen-reader-text"><?php esc_html_e( 'ocena redakcji', 'ekasyna' ); ?></span></span>
	<span class="ek-tag ek-tag--rank">TOP <?php echo (int) $pos; ?></span>

	<div class="ek-pcard__head">
		<div class="ek-pcard__brand">
			<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a class="ek-pcard__name" href="<?php echo esc_url( $c['review_url'] ); ?>"><?php echo esc_html( $c['name'] ); ?></a>
		</div>
		<?php get_template_part( 'template-parts/casino/bonus', null, array( 'casino' => $c ) ); ?>
	</div>

	<div class="ek-pcard__body">
		<?php if ( $c['highlight'] ) : ?>
			<p class="ek-highlight"><?php echo ekasyna_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $c['highlight'] ); ?></span></p>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/casino/facts', null, array( 'casino' => $c ) ); ?>

		<a class="ek-pill" <?php echo ekasyna_play_attrs( $c ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<span><?php esc_html_e( 'Przejdź do kasyna', 'ekasyna' ); ?></span>
			<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</a>
		<a class="ek-reviewlink" href="<?php echo esc_url( $c['review_url'] ); ?>"><?php printf( /* translators: %s nazwa kasyna */ esc_html__( 'Opinie o %s', 'ekasyna' ), esc_html( $c['name'] ) ); ?></a>
		<span class="ek-terms"><?php echo esc_html( $c['bonus_terms'] ? $c['bonus_terms'] : __( '18+ · Obowiązuje regulamin bonusu', 'ekasyna' ) ); ?></span>
	</div>
</li>
