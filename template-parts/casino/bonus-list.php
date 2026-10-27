<?php
/**
 * „Najlepsze bonusy {kasyno}” — siatka kart z wszystkimi bonusami kasyna (recenzja).
 *
 * @package ekasyna
 *
 * @var array $args { casino: array }
 */

defined( 'ABSPATH' ) || exit;

$c = $args['casino'];
if ( empty( $c['bonuses'] ) ) {
	return;
}
?>
<section class="ek-bonuses" aria-labelledby="ek-bonuses-title">
	<div class="ek-bonuses__head">
		<h2 id="ek-bonuses-title" class="ek-h2 ek-h2--sm">
			<?php
			/* translators: %s nazwa kasyna */
			printf( esc_html__( 'Najlepsze bonusy %s', 'ekasyna' ), esc_html( $c['name'] ) );
			?>
		</h2>
		<span class="ek-bonuses__count">
			<?php
			/* translators: %d liczba ofert */
			printf( esc_html( _n( '%d oferta', '%d ofert', count( $c['bonuses'] ), 'ekasyna' ) ), (int) count( $c['bonuses'] ) );
			?>
		</span>
	</div>
	<ul class="ek-bonuses__grid" role="list">
		<?php foreach ( $c['bonuses'] as $i => $b ) : ?>
			<?php
			$url      = $b['url'] ? $b['url'] : $c['play_url'];
			$external = $b['url'] ? true : $c['is_external'];
			?>
			<li class="ek-bcard<?php echo 0 === $i ? ' ek-bcard--first' : ''; ?>">
				<div class="ek-bcard__top">
					<?php echo ekasyna_casino_logo( $c, 'ek-casino-logo ek-casino-logo--xs' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $b['type'] ) : ?>
						<span class="ek-bcard__type"><?php echo esc_html( $b['type'] ); ?></span>
					<?php endif; ?>
				</div>
				<p class="ek-bcard__title"><?php echo esc_html( $b['title'] ); ?></p>
				<?php if ( $b['min'] || $b['wager'] ) : ?>
					<dl class="ek-bcard__meta">
						<?php if ( $b['min'] ) : ?>
							<div><dt><?php esc_html_e( 'Min. depozyt', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $b['min'] ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $b['wager'] ) : ?>
							<div><dt><?php esc_html_e( 'Obrót', 'ekasyna' ); ?></dt><dd><?php echo esc_html( $b['wager'] ); ?></dd></div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>
				<?php if ( $b['code'] ) : ?>
					<button type="button" class="ek-code" data-ek-copy="<?php echo esc_attr( $b['code'] ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s kod */ __( 'Skopiuj kod %s', 'ekasyna' ), $b['code'] ) ); ?>">
						<span class="ek-code__label"><?php esc_html_e( 'Kod:', 'ekasyna' ); ?></span>
						<strong><?php echo esc_html( $b['code'] ); ?></strong>
						<span class="ek-code__action" data-ek-copy-label><?php esc_html_e( 'Kopiuj', 'ekasyna' ); ?></span>
					</button>
				<?php endif; ?>
				<a class="ek-pill ek-pill--sm" href="<?php echo esc_url( $url ); ?>"<?php echo $external ? ' target="_blank" rel="nofollow sponsored noopener"' : ''; ?>>
					<span><?php esc_html_e( 'Odbierz bonus', 'ekasyna' ); ?></span>
					<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<p class="ek-fineprint"><?php esc_html_e( '18+ · Warunki bonusów mogą się zmienić — sprawdź regulamin w kasynie przed aktywacją.', 'ekasyna' ); ?></p>
</section>
