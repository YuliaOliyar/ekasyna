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
	$pick = ekasyna_mod( 'ekasyna_sticky_bar' ) ? ekasyna_pick() : null;
	if ( $pick && ! is_singular( 'kasyno' ) ) :
		?>
		<div class="ek-stickybar">
			<?php echo ekasyna_casino_logo( $pick, 'ek-casino-logo ek-casino-logo--xs' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="ek-stickybar__text">
				<strong><?php echo esc_html( trim( $pick['bonus_pre'] . ' ' . $pick['bonus_amt'] ) ); ?></strong>
				<span><?php echo esc_html( $pick['name'] ); ?> · 18+</span>
			</div>
			<a class="ek-btn ek-btn--red ek-btn--sm" <?php echo ekasyna_play_attrs( $pick ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Odbierz', 'ekasyna' ); ?></a>
		</div>
	<?php endif; ?>
</div>

<?php get_template_part( 'template-parts/popup' ); ?>
<?php get_template_part( 'template-parts/newsletter-popup' ); ?>

<?php wp_footer(); ?>
</body>
</html>
