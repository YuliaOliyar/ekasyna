<?php
/**
 * Nagłówek.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ek-skip" href="#ek-main"><?php esc_html_e( 'Przejdź do treści', 'ekasyna' ); ?></a>

<div class="ek-topbar">
	<div class="ek-container ek-topbar__inner">
		<div class="ek-topbar__left">
			<span class="ek-18">18+</span>
			<span><?php echo esc_html( ekasyna_mod( 'ekasyna_topbar_text' ) ); ?></span>
			<a href="#odpowiedzialna-gra"><?php esc_html_e( 'Gdzie szukać pomocy', 'ekasyna' ); ?></a>
		</div>
		<?php if ( is_singular() ) : ?>
			<span class="ek-topbar__date"><?php esc_html_e( 'Zaktualizowano:', 'ekasyna' ); ?> <?php echo esc_html( ekasyna_date( 'd.m.Y' ) ); ?></span>
		<?php endif; ?>
	</div>
</div>

<header class="ek-header">
	<div class="ek-container ek-header__inner">
		<?php ekasyna_the_logo( 'light' ); ?>

		<nav id="ek-nav" class="ek-nav" aria-label="<?php esc_attr_e( 'Menu główne', 'ekasyna' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ek-nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'ekasyna_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="ek-header__actions">
			<button class="ek-iconbtn" type="button" data-ek-toggle="search" aria-controls="ek-search" aria-expanded="false" aria-label="<?php esc_attr_e( 'Szukaj', 'ekasyna' ); ?>"><?php echo ekasyna_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			<a class="ek-btn ek-btn--dark ek-header__cta" href="<?php echo esc_url( is_front_page() ? '#ranking' : get_post_type_archive_link( 'kasyno' ) ); ?>"><?php esc_html_e( 'Ranking TOP 10', 'ekasyna' ); ?></a>
			<button class="ek-iconbtn ek-iconbtn--dark ek-burger" type="button" data-ek-toggle="nav" aria-controls="ek-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'ekasyna' ); ?>"><?php echo ekasyna_icon( 'menu', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
	</div>
	<div id="ek-search" class="ek-search" hidden>
		<div class="ek-container"><?php get_search_form(); ?></div>
	</div>
</header>

<main id="ek-main" class="ek-main">
