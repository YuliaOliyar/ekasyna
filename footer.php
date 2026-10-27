<?php
/**
 * Stopka.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;
?>
<?php get_template_part( 'template-parts/responsible' ); ?>
</main>

<footer class="ek-footer">
	<div class="ek-container">
		<div class="ek-footer__cols">
			<div class="ek-footer__about">
				<?php ekasyna_the_logo( 'dark' ); ?>
				<p><?php echo esc_html( ekasyna_mod( 'ekasyna_footer_text' ) ); ?></p>
			</div>
			<?php
			foreach ( array( 'footer_1', 'footer_2', 'footer_3' ) as $loc ) :
				if ( ! has_nav_menu( $loc ) ) {
					continue;
				}
				$locations = get_nav_menu_locations();
				$menu      = wp_get_nav_menu_object( $locations[ $loc ] );
				?>
				<nav class="ek-footer__col" aria-label="<?php echo esc_attr( $menu ? $menu->name : $loc ); ?>">
					<?php if ( $menu ) : ?>
						<span class="ek-footer__title"><?php echo esc_html( $menu->name ); ?></span>
					<?php endif; ?>
					<?php wp_nav_menu( array( 'theme_location' => $loc, 'container' => false, 'menu_class' => 'ek-footer__list', 'depth' => 1 ) ); ?>
				</nav>
			<?php endforeach; ?>
		</div>
		<div class="ek-footer__bottom">
			<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> · <?php esc_html_e( 'Tylko dla osób pełnoletnich (18+)', 'ekasyna' ); ?></span>
			<?php if ( is_active_sidebar( 'ekasyna-footer-badges' ) ) : ?>
				<div class="ek-footer__badges"><?php dynamic_sidebar( 'ekasyna-footer-badges' ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</footer>

<div class="ek-floating" data-ek-floating>
	<a class="ek-totop" href="#" data-ek-totop aria-label="<?php esc_attr_e( 'Przewiń do góry', 'ekasyna' ); ?>"><?php echo ekasyna_icon( 'up', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php
	$pick = null;
	if ( ekasyna_mod( 'ekasyna_sticky_bar' ) ) {
		$pick = is_singular( 'kasyno' ) ? ekasyna_casino( get_queried_object_id() ) : ekasyna_pick();
	}
	if ( $pick ) :
		$bonus_line = trim( $pick['bonus_pre'] . ' ' . $pick['bonus_amt'] . ' ' . $pick['bonus_post'] );
		?>
		<div class="ek-stickybar" role="complementary" aria-label="<?php esc_attr_e( 'Bonus', 'ekasyna' ); ?>">
			<div class="ek-stickybar__info">
				<?php echo ekasyna_casino_logo( $pick, 'ek-casino-logo ek-casino-logo--xs' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="ek-stickybar__text">
					<strong><?php echo esc_html( $bonus_line ? $bonus_line : $pick['bonus_type'] ); ?></strong>
					<span><?php echo esc_html( $pick['name'] ); ?> · <?php echo esc_html( $pick['bonus_type'] ); ?> · 18+</span>
				</div>
			</div>
			<a class="ek-pill ek-stickybar__cta" <?php echo ekasyna_play_attrs( $pick ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<span><?php esc_html_e( 'Odbierz bonus', 'ekasyna' ); ?></span>
				<span class="ek-pill__icon"><?php echo ekasyna_icon( 'arrow', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>
	<?php endif; ?>
</div>

<?php get_template_part( 'template-parts/popup' ); ?>
<?php get_template_part( 'template-parts/newsletter-popup' ); ?>

<?php wp_footer(); ?>
</body>
</html>
