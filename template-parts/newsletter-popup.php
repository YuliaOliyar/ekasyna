<?php
/**
 * Pop-up newslettera. Ustawienia: Dostosuj → Motyw e-kasyna → Pop-up newslettera.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

if ( ! ekasyna_mod( 'ekasyna_nl_on' ) ) {
	return;
}

$perks    = array_filter( array_map( 'trim', preg_split( '/\r\n|\n/', (string) ekasyna_mod( 'ekasyna_nl_perks' ) ) ) );
$external = ekasyna_mod( 'ekasyna_nl_action' );
$field    = ekasyna_mod( 'ekasyna_nl_field' ) ? ekasyna_mod( 'ekasyna_nl_field' ) : 'EMAIL';
$action   = $external ? $external : admin_url( 'admin-post.php' );
$privacy  = get_privacy_policy_url();
?>
<div class="ek-popup ek-popup--nl" id="ek-newsletter" hidden
	data-ek-newsletter
	data-delay="<?php echo esc_attr( (int) ekasyna_mod( 'ekasyna_nl_delay' ) ); ?>"
	data-days="<?php echo esc_attr( (int) ekasyna_mod( 'ekasyna_nl_days' ) ); ?>"
	data-exit="<?php echo ekasyna_mod( 'ekasyna_nl_exit' ) ? '1' : '0'; ?>">
	<div class="ek-popup__backdrop" data-ek-popup-close></div>
	<div class="ek-popup__box ek-nl" role="dialog" aria-modal="true" aria-labelledby="ek-nl-title">
		<button type="button" class="ek-popup__close ek-popup__close--light" data-ek-popup-close aria-label="<?php esc_attr_e( 'Zamknij', 'ekasyna' ); ?>"><?php echo ekasyna_icon( 'close', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

		<div class="ek-nl__art" aria-hidden="true">
			<span class="ek-popup__handle"></span>
			<span class="ek-nl__glow"></span>
			<span class="ek-nl__card ek-nl__card--a"><b>A</b><svg viewBox="0 0 10 10"><path d="M5 0L10 5 5 10 0 5z" fill="#C8102E"/></svg><b>A</b></span>
			<span class="ek-nl__card ek-nl__card--b">7</span>
			<span class="ek-nl__chip"><?php echo ekasyna_chip_svg( 190 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="ek-nl__tagline"><?php esc_html_e( 'Bonusy · Turnieje · Jackpoty', 'ekasyna' ); ?></span>
		</div>

		<div class="ek-nl__main">
			<span class="ek-popup__kicker"><?php esc_html_e( 'Newsletter', 'ekasyna' ); ?> <?php bloginfo( 'name' ); ?></span>
			<p class="ek-nl__title" id="ek-nl-title"><?php echo esc_html( ekasyna_mod( 'ekasyna_nl_title' ) ); ?></p>
			<?php if ( $perks ) : ?>
				<ul class="ek-nl__perks">
					<?php foreach ( $perks as $perk ) : ?>
						<li><span class="ek-nl__check"><?php echo ekasyna_icon( 'check', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $perk ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<form class="ek-nl__form" method="post" action="<?php echo esc_url( $action ); ?>" data-ek-nl-form<?php echo $external ? ' data-external="1" target="_blank"' : ''; ?> novalidate>
				<label class="ek-nl__label" for="ek-nl-email"><?php esc_html_e( 'Subskrybuj — bez spamu', 'ekasyna' ); ?></label>
				<div class="ek-nl__row">
					<input id="ek-nl-email" class="ek-nl__input" type="email" name="<?php echo esc_attr( $external ? $field : 'ek_email' ); ?>" required autocomplete="email" placeholder="<?php esc_attr_e( 'Twój adres e-mail', 'ekasyna' ); ?>">
					<button class="ek-nl__submit" type="submit"><?php esc_html_e( 'Zapisz się', 'ekasyna' ); ?></button>
				</div>
				<label class="ek-nl__consent">
					<input type="checkbox" name="ek_consent" value="1" required>
					<span>
						<?php echo esc_html( ekasyna_mod( 'ekasyna_nl_consent' ) ); ?>
						<?php if ( $privacy ) : ?>
							<a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Polityka prywatności', 'ekasyna' ); ?></a>
						<?php endif; ?>
					</span>
				</label>
				<?php if ( ! $external ) : ?>
					<input type="hidden" name="action" value="ekasyna_subscribe">
					<?php wp_nonce_field( 'ekasyna_subscribe', 'ek_nl_nonce', false ); ?>
					<input type="text" name="ek_website" value="" tabindex="-1" autocomplete="off" class="ek-hp" aria-hidden="true">
				<?php endif; ?>
				<p class="ek-nl__error" data-ek-nl-error role="alert" hidden></p>
			</form>

			<div class="ek-nl__done" data-ek-nl-done hidden>
				<span class="ek-nl__done-icon"><?php echo ekasyna_icon( 'check', 22, '#FFFFFF' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<div>
					<strong><?php esc_html_e( 'Dziękujemy za zapis!', 'ekasyna' ); ?></strong>
					<span><?php esc_html_e( 'Pierwsze wiadomości wkrótce trafią do Twojej skrzynki.', 'ekasyna' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>
