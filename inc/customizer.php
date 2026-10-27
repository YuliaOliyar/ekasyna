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
		'ekasyna_archive_page'  => 0,
		'ekasyna_popup_on'      => 0,
		'ekasyna_popup_casino'  => 0,
		'ekasyna_popup_title'   => 'Twój bonus',
		'ekasyna_popup_amount'  => '',
		'ekasyna_popup_sub'     => '',
		'ekasyna_popup_delay'   => 8,
		'ekasyna_popup_days'    => 1,
		'ekasyna_popup_where'   => 'all',
		'ekasyna_nl_on'         => 0,
		'ekasyna_nl_title'      => 'Zapisz się do naszego newslettera i bonusów',
		'ekasyna_nl_perks'      => "Nowe loterie i turnieje\nNowe bonusy kasynowe\nEkskluzywne bonusy tylko dla subskrybentów\nInformacje o jackpotach",
		'ekasyna_nl_consent'    => 'Mam ukończone 18 lat i zgadzam się na otrzymywanie newslettera. Zgodę mogę wycofać w każdej chwili.',
		'ekasyna_nl_delay'      => 25,
		'ekasyna_nl_exit'       => 1,
		'ekasyna_nl_days'       => 7,
		'ekasyna_nl_action'     => '',
		'ekasyna_nl_field'      => 'EMAIL',
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

	// Pop-up bonusu.
	$wp_customize->add_section( 'ekasyna_popup', array( 'title' => __( 'Pop-up bonusu', 'ekasyna' ), 'panel' => 'ekasyna' ) );
	$wp_customize->add_setting( 'ekasyna_popup_on', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_popup_on', array( 'label' => __( 'Włącz pop-up', 'ekasyna' ), 'section' => 'ekasyna_popup', 'type' => 'checkbox' ) );
	$wp_customize->add_setting( 'ekasyna_popup_casino', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_popup_casino', array( 'label' => __( 'Kasyno', 'ekasyna' ), 'section' => 'ekasyna_popup', 'type' => 'select', 'choices' => $choices ) );
	foreach ( array(
		'ekasyna_popup_title'  => __( 'Nagłówek (np. „Twój bonus”)', 'ekasyna' ),
		'ekasyna_popup_amount' => __( 'Bonus — duży tekst (puste = z pól kasyna)', 'ekasyna' ),
		'ekasyna_popup_sub'    => __( 'Bonus — dopisek (np. „na Gates of Olympus”)', 'ekasyna' ),
	) as $key => $label ) {
		$wp_customize->add_setting( $key, array( 'default' => $d[ $key ], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'ekasyna_popup', 'type' => 'text' ) );
	}
	$wp_customize->add_setting( 'ekasyna_popup_delay', array( 'default' => 8, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_popup_delay', array( 'label' => __( 'Pokaż po (sekundach)', 'ekasyna' ), 'section' => 'ekasyna_popup', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 120 ) ) );
	$wp_customize->add_setting( 'ekasyna_popup_days', array( 'default' => 1, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_popup_days', array( 'label' => __( 'Po zamknięciu nie pokazuj przez (dni)', 'ekasyna' ), 'description' => __( '0 = przy każdej wizycie.', 'ekasyna' ), 'section' => 'ekasyna_popup', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 365 ) ) );
	$wp_customize->add_setting( 'ekasyna_popup_where', array( 'default' => 'all', 'sanitize_callback' => 'sanitize_key' ) );
	$wp_customize->add_control( 'ekasyna_popup_where', array( 'label' => __( 'Gdzie pokazywać', 'ekasyna' ), 'section' => 'ekasyna_popup', 'type' => 'radio', 'choices' => array( 'all' => __( 'Na wszystkich stronach', 'ekasyna' ), 'home' => __( 'Tylko na stronie głównej', 'ekasyna' ) ) ) );

	// Pop-up newslettera.
	$wp_customize->add_section( 'ekasyna_nl', array( 'title' => __( 'Pop-up newslettera', 'ekasyna' ), 'panel' => 'ekasyna', 'description' => __( 'Zapisy trafiają do menu „Newsletter” (eksport CSV). Aby wysyłać je do Mailchimp/Brevo/SendPulse, wklej adres action formularza tego serwisu.', 'ekasyna' ) ) );
	$wp_customize->add_setting( 'ekasyna_nl_on', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_nl_on', array( 'label' => __( 'Włącz pop-up newslettera', 'ekasyna' ), 'section' => 'ekasyna_nl', 'type' => 'checkbox' ) );
	foreach ( array(
		'ekasyna_nl_title'   => array( __( 'Tytuł', 'ekasyna' ), 'text', 'sanitize_text_field' ),
		'ekasyna_nl_perks'   => array( __( 'Korzyści (każda w nowej linii)', 'ekasyna' ), 'textarea', 'sanitize_textarea_field' ),
		'ekasyna_nl_consent' => array( __( 'Tekst zgody (RODO, 18+)', 'ekasyna' ), 'textarea', 'sanitize_textarea_field' ),
		'ekasyna_nl_action'  => array( __( 'Zewnętrzny formularz — adres action (opcjonalnie)', 'ekasyna' ), 'url', 'esc_url_raw' ),
		'ekasyna_nl_field'   => array( __( 'Zewnętrzny formularz — nazwa pola e-mail', 'ekasyna' ), 'text', 'sanitize_key' ),
	) as $key => $cfg ) {
		$wp_customize->add_setting( $key, array( 'default' => $d[ $key ], 'sanitize_callback' => $cfg[2] ) );
		$wp_customize->add_control( $key, array( 'label' => $cfg[0], 'section' => 'ekasyna_nl', 'type' => $cfg[1] ) );
	}
	$wp_customize->add_setting( 'ekasyna_nl_exit', array( 'default' => 1, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_nl_exit', array( 'label' => __( 'Pokaż, gdy kursor opuszcza stronę (komputer)', 'ekasyna' ), 'section' => 'ekasyna_nl', 'type' => 'checkbox' ) );
	$wp_customize->add_setting( 'ekasyna_nl_delay', array( 'default' => 25, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_nl_delay', array( 'label' => __( 'Albo pokaż po (sekundach); 0 = tylko przy wyjściu', 'ekasyna' ), 'section' => 'ekasyna_nl', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 600 ) ) );
	$wp_customize->add_setting( 'ekasyna_nl_days', array( 'default' => 7, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_nl_days', array( 'label' => __( 'Po zamknięciu nie pokazuj przez (dni)', 'ekasyna' ), 'section' => 'ekasyna_nl', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'max' => 365 ) ) );

	$wp_customize->add_setting( 'ekasyna_archive_page', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'ekasyna_archive_page', array( 'label' => __( 'Strona z tekstem dla „Pełnego rankingu” (/ranking-kasyn/)', 'ekasyna' ), 'description' => __( 'Wypis strony = lead pod H1, treść = artykuł pod listą (ze spisem treści i autorem).', 'ekasyna' ), 'section' => 'ekasyna_ranking', 'type' => 'dropdown-pages', 'allow_addition' => true ) );

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
