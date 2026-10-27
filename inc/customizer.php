<?php
/**
 * Ustawienia w Customizerze: hero, wybór redakcji, pasek 18+, pomoc.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Domyślne wartości.
 *
 * @return array
 */
function ekasyna_defaults() {
	return array(
		'ekasyna_topbar_text'   => 'Graj odpowiedzialnie. Hazard może uzależniać.',
		'ekasyna_hero_tag_1'    => 'Kasyna dla graczy z Polski',
		'ekasyna_hero_tag_2'    => 'Wpłaty w PLN · BLIK',
		'ekasyna_hero_title'    => 'Kasyna online w Polsce:',
		'ekasyna_hero_accent'   => 'ranking 2026',
		'ekasyna_hero_text'     => 'Rejestrujemy się, wpłacamy i próbujemy wypłacić wygrane, zanim kasyno trafi do rankingu. Porównaj bonusy, metody płatności i czas wypłaty w jednym miejscu.',
		'ekasyna_stat_1_num'    => '',
		'ekasyna_stat_1_label'  => 'kasyn w rankingu',
		'ekasyna_stat_2_num'    => '19',
		'ekasyna_stat_2_label'  => 'metod płatności w bazie',
		'ekasyna_stat_3_num'    => '',
		'ekasyna_stat_3_label'  => 'testowych wypłat',
		'ekasyna_pick'          => 0,
		'ekasyna_pick_label'    => 'Wybór redakcji',
		'ekasyna_ranking_limit' => 10,
		'ekasyna_rg_title'      => 'Graj odpowiedzialnie',
		'ekasyna_rg_text'       => 'Ustal limit wpłat i czasu gry przed rozpoczęciem. Jeśli hazard przestaje być rozrywką, skorzystaj z pomocy.',
		'ekasyna_rg_1_label'    => 'Anonimowi Hazardziści',
		'ekasyna_rg_1_url'      => 'http://www.anonimowihazardzisci.org/',
		'ekasyna_rg_2_label'    => 'Gambling Therapy (PL)',
		'ekasyna_rg_2_url'      => 'https://www.gamblingtherapy.org/pl/',
		'ekasyna_footer_text'   => 'Niezależne recenzje i ranking kasyn online dla graczy z Polski. Treści mają charakter informacyjny.',
		'ekasyna_sticky_bar'    => 1,
	);
}

/**
 * Wartość ustawienia z domyślną.
 *
 * @param string $key Klucz.
 * @return mixed
 */
function ekasyna_mod( $key ) {
	$d = ekasyna_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/**
 * Rejestracja Customizera.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function ekasyna_customize( $wp_customize ) {
	$d = ekasyna_defaults();

	$wp_customize->add_panel( 'ekasyna', array( 'title' => __( 'Motyw e-kasyna', 'ekasyna' ), 'priority' => 30 ) );

	$sections = array(
		'ekasyna_hero'    => __( 'Strona główna — hero', 'ekasyna' ),
		'ekasyna_ranking' => __( 'Ranking i wybór redakcji', 'ekasyna' ),
		'ekasyna_rg'      => __( 'Pasek 18+ i odpowiedzialna gra', 'ekasyna' ),
		'ekasyna_footer'  => __( 'Stopka', 'ekasyna' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'ekasyna' ) );
	}

	$text = array(
		'ekasyna_hero'   => array(
			'ekasyna_hero_tag_1'   => __( 'Etykieta 1', 'ekasyna' ),
			'ekasyna_hero_tag_2'   => __( 'Etykieta 2', 'ekasyna' ),
			'ekasyna_hero_title'   => __( 'Tytuł H1', 'ekasyna' ),
			'ekasyna_hero_accent'  => __( 'Tytuł — wyróżniona część', 'ekasyna' ),
			'ekasyna_hero_text'    => __( 'Lead', 'ekasyna' ),
			'ekasyna_stat_1_num'   => __( 'Liczba 1 (puste = liczba kasyn)', 'ekasyna' ),
			'ekasyna_stat_1_label' => __( 'Opis 1', 'ekasyna' ),
			'ekasyna_stat_2_num'   => __( 'Liczba 2', 'ekasyna' ),
			'ekasyna_stat_2_label' => __( 'Opis 2', 'ekasyna' ),
			'ekasyna_stat_3_num'   => __( 'Liczba 3 (puste = ukryj)', 'ekasyna' ),
			'ekasyna_stat_3_label' => __( 'Opis 3', 'ekasyna' ),
		),
		'ekasyna_ranking' => array(
			'ekasyna_pick_label' => __( 'Nagłówek karty wyboru redakcji', 'ekasyna' ),
		),
		'ekasyna_rg'     => array(
			'ekasyna_topbar_text' => __( 'Tekst paska 18+', 'ekasyna' ),
			'ekasyna_rg_title'    => __( 'Tytuł bloku', 'ekasyna' ),
			'ekasyna_rg_text'     => __( 'Tekst bloku', 'ekasyna' ),
			'ekasyna_rg_1_label'  => __( 'Link 1 — tekst', 'ekasyna' ),
			'ekasyna_rg_1_url'    => __( 'Link 1 — URL', 'ekasyna' ),
			'ekasyna_rg_2_label'  => __( 'Link 2 — tekst', 'ekasyna' ),
			'ekasyna_rg_2_url'    => __( 'Link 2 — URL', 'ekasyna' ),
		),
		'ekasyna_footer' => array(
			'ekasyna_footer_text' => __( 'Opis w stopce', 'ekasyna' ),
		),
	);

	foreach ( $text as $section => $controls ) {
		foreach ( $controls as $key => $label ) {
			$is_url  = substr( $key, -4 ) === '_url';
			$is_long = in_array( $key, array( 'ekasyna_hero_text', 'ekasyna_rg_text', 'ekasyna_footer_text' ), true );
			$wp_customize->add_setting(
				$key,
				array(
					'default'           => $d[ $key ],
					'sanitize_callback' => $is_url ? 'esc_url_raw' : ( $is_long ? 'sanitize_textarea_field' : 'sanitize_text_field' ),
				)
			);
			$wp_customize->add_control(
				$key,
				array(
					'label'   => $label,
					'section' => $section,
					'type'    => $is_url ? 'url' : ( $is_long ? 'textarea' : 'text' ),
				)
			);
		}
	}

	// Wybór redakcji.
	$choices = array( 0 => __( '— pierwsze z rankingu —', 'ekasyna' ) );
	foreach ( get_posts( array( 'post_type' => 'kasyno', 'posts_per_page' => 100, 'orderby' => 'title', 'order' => 'ASC' ) ) as $p ) {
		$choices[ $p->ID ] = $p->post_title;
	}
	$wp_customize->add_setting( 'ekasyna_pick', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_pick', array( 'label' => __( 'Kasyno — wybór redakcji (hero i pasek mobilny)', 'ekasyna' ), 'section' => 'ekasyna_ranking', 'type' => 'select', 'choices' => $choices ) );

	$wp_customize->add_setting( 'ekasyna_ranking_limit', array( 'default' => 10, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_ranking_limit', array( 'label' => __( 'Liczba kasyn w rankingu na stronie głównej', 'ekasyna' ), 'section' => 'ekasyna_ranking', 'type' => 'number', 'input_attrs' => array( 'min' => 3, 'max' => 50 ) ) );

	$wp_customize->add_setting( 'ekasyna_sticky_bar', array( 'default' => 1, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_sticky_bar', array( 'label' => __( 'Przyklejony pasek bonusu na telefonie', 'ekasyna' ), 'section' => 'ekasyna_ranking', 'type' => 'checkbox' ) );
}
add_action( 'customize_register', 'ekasyna_customize' );

/**
 * Kasyno „wybór redakcji”.
 *
 * @return array|null Dane kasyna.
 */
function ekasyna_pick() {
	$id = (int) ekasyna_mod( 'ekasyna_pick' );
	if ( $id && 'kasyno' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
		return ekasyna_casino( $id );
	}
	$top = ekasyna_get_ranking( 1 );
	return $top ? ekasyna_casino( $top[0] ) : null;
}
