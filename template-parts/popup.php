<?php
/**
 * Pop-up „Twój bonus”. Ustawienia: Dostosuj → Motyw e-kasyna → Pop-up bonusu.
 * Na komputerze — okno na środku, na telefonie — panel od dołu.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

if ( ! ekasyna_mod( 'ekasyna_popup_on' ) ) {
	return;
}
if ( 'home' === ekasyna_mod( 'ekasyna_popup_where' ) && ! is_front_page() ) {
	return;
}

$pid = (int) ekasyna_mod( 'ekasyna_popup_casino' );
$c   = ( $pid && 'kasyno' === get_post_type( $pid ) && 'publish' === get_post_status( $pid ) ) ? ekasyna_casino( $pid ) : ekasyna_pick();
if ( ! $c ) {
	return;
}

$title = ekasyna_mod( 'ekasyna_popup_title' );
$line1 = ekasyna_mod( 'ekasyna_popup_amount' );
$line2 = ekasyna_mod( 'ekasyna_popup_sub' );
if ( ! $line1 ) {
	$line1 = trim( $c['bonus_pre'] . ' ' . $c['bonus_amt'] );
	$line2 = $line2 ? $line2 : $c['bonus_post'];
}
?>
<div class="ek-popup" id="ek-popup" hidden
	data-ek-popup
	data-delay="<?php echo esc_attr( (int) ekasyna_mod( 'ekasyna_popup_delay' ) ); ?>"
	data-days="<?php echo esc_attr( (int) ekasyna_mod( 'ekasyna_popup_days' ) ); ?>">
	<div class="ek-popup__backdrop" data-ek-popup-close></div>
	<div class="ek-popup__box" role="dialog" aria-modal="true" aria-labelledby="ek-popup-title">
		<?php if ( $c['rating'] > 0 ) : ?>
			<span class="ek-tag ek-tag--score"><?php echo ekasyna_icon( 'star', 14, '#F2C94C' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $c['score'] ); ?>/10</span>
		<?php endif; ?>
		<button type="button" class="ek-popup__close" data-ek-popup-close aria-label="<?php esc_attr_e( 'Zamknij', 'ekasyna' ); ?>"><?php echo ekasyna_icon( 'close', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

		<div class="ek-popup__head">
			<span class="ek-popup__handle" aria-hidden="true"></span>
			<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--popup' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="ek-popup__text">
				<span class="ek-popup__kicker"><?php echo esc_html( $title ); ?></span>
				<p class="ek-popup__amount" id="ek-popup-title"><?php echo esc_html( $line1 ); ?></p>
				<?php if ( $line2 ) : ?>
					<span class="ek-popup__sub"><?php echo esc_html( $line2 ); ?></span>
				<?php endif; ?>
				<span class="ek-popup__casino"><?php echo esc_html( $c['name'] ); ?> · <?php echo esc_html( $c['bonus_type'] ); ?></span>
			</div>
		</div>

		<div class="ek-popup__body">
			<?php if ( $c['highlight'] ) : ?>
				<p class="ek-highlight"><?php echo ekasyna_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $c['highlight'] ); ?></span></p>
			<?php endif; ?>
			<a class="ek-pill ek-popup__cta" <?php echo ekasyna_play_attrs( $c ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-ek-popup-accept>
				<span><?php esc_html_e( 'Odbierz bonus', 'ekasyna' ); ?></span>
				<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
			<div class="ek-popup__row">
				<a class="ek-reviewlink" href="<?php echo esc_url( $c['review_url'] ); ?>"><?php printf( /* translators: %s nazwa kasyna */ esc_html__( 'Opinie o %s', 'ekasyna' ), esc_html( $c['name'] ) ); ?></a>
				<button type="button" class="ek-popup__no" data-ek-popup-close><?php esc_html_e( 'Nie, dziękuję', 'ekasyna' ); ?></button>
			</div>
			<p class="ek-popup__terms"><?php esc_html_e( '18+ · Obowiązuje regulamin bonusu i wymagania obrotu. Graj odpowiedzialnie.', 'ekasyna' ); ?></p>
		</div>
	</div>
</div>
