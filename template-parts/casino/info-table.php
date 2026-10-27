<?php
/**
 * „Informacje o kasynie” — tabela w dwóch kolumnach: etykieta → wartość.
 * Długie listy (producenci, wpłaty, wypłaty) zwijają się do 12 pozycji + „więcej”.
 *
 * @package ekasyna
 *
 * @var array $args { casino: array }
 */

defined( 'ABSPATH' ) || exit;

$c = $args['casino'];

$license = trim( ( '—' !== $c['license'] ? $c['license'] : '' ) . ( $c['license_no'] ? ' · ' . $c['license_no'] : '' ) );

$rows = array(
	array( __( 'Kasyno', 'ekasyna' ), $c['name'] ),
	array( __( 'Strona', 'ekasyna' ), $c['website'], 'site' ),
	array( __( 'Licencja', 'ekasyna' ), $license ),
	array( __( 'Właściciel', 'ekasyna' ), $c['operator'] ),
	array( __( 'Rok', 'ekasyna' ), $c['founded'] ),
	array( __( 'Wsparcie', 'ekasyna' ), $c['support'] ),
	array( __( 'Liczba gier', 'ekasyna' ), $c['games'] ),
	array( __( 'Waluta', 'ekasyna' ), $c['currency'] ),
	array( __( 'Min. depozyt', 'ekasyna' ), $c['min_deposit'] ),
	array( __( 'Czas wypłat', 'ekasyna' ), '—' !== $c['payout'] ? $c['payout'] : '' ),
	array( __( 'Aplikacja mobilna', 'ekasyna' ), $c['app'] ),
	array( '', '' ),
	array( __( 'Producenci', 'ekasyna' ), $c['providers'], 'list' ),
	array( __( 'Wpłaty', 'ekasyna' ), $c['payments'], 'list' ),
	array( __( 'Wypłaty', 'ekasyna' ), $c['withdrawals'], 'list' ),
);

// Usuń puste wiersze (separator też, jeśli nie ma list).
$rows = array_values(
	array_filter(
		$rows,
		function ( $r ) {
			return '' === $r[0] || ! empty( $r[1] );
		}
	)
);
$has_lists = (bool) array_filter(
	$rows,
	function ( $r ) {
		return isset( $r[2] ) && 'list' === $r[2];
	}
);
if ( ! $has_lists ) {
	$rows = array_filter(
		$rows,
		function ( $r ) {
			return '' !== $r[0];
		}
	);
}
if ( count( $rows ) < 2 ) {
	return;
}
$limit = 12;
?>
<section class="ek-info" aria-labelledby="ek-info-title">
	<h2 id="ek-info-title" class="ek-h2 ek-h2--sm"><?php esc_html_e( 'Informacje o kasynie', 'ekasyna' ); ?></h2>
	<dl class="ek-info__table">
		<?php foreach ( $rows as $r ) : ?>
			<?php if ( '' === $r[0] ) : ?>
				<div class="ek-info__break" aria-hidden="true"></div>
				<?php continue; ?>
			<?php endif; ?>
			<?php $type = isset( $r[2] ) ? $r[2] : 'text'; ?>
			<div class="ek-info__row<?php echo 'list' === $type ? ' ek-info__row--list' : ''; ?>">
				<dt><?php echo esc_html( $r[0] ); ?>:</dt>
				<dd>
					<?php if ( 'site' === $type ) : ?>
						<a <?php echo ekasyna_play_attrs( $c ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $r[1] ); ?> <?php echo ekasyna_icon( 'arrow', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php elseif ( 'list' === $type ) : ?>
						<?php
						$items = $r[1];
						$first = array_slice( $items, 0, $limit );
						$more  = array_slice( $items, $limit );
						echo esc_html( implode( ', ', $first ) );
						if ( $more ) :
							?>
							<details class="ek-info__more">
								<summary>
									<?php
									/* translators: %d liczba */
									printf( esc_html__( '+%d więcej', 'ekasyna' ), (int) count( $more ) );
									?>
								</summary>
								<span><?php echo esc_html( ', ' . implode( ', ', $more ) ); ?></span>
							</details>
						<?php endif; ?>
					<?php else : ?>
						<?php echo esc_html( $r[1] ); ?>
					<?php endif; ?>
				</dd>
			</div>
		<?php endforeach; ?>
	</dl>
</section>
