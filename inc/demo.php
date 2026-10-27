<?php
/**
 * Import przykładowych kasyn (Wygląd → Dane demo e-kasyna).
 * Dane pochodzą z obecnego rankingu; pola, których nie znamy, zostają puste do uzupełnienia.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Przykładowe kasyna.
 *
 * @return array
 */
function ekasyna_demo_data() {
	return array(
		array(
			'title' => 'Lemon Casino',
			'terms' => array( 'nowe', 'krypto' ),
			'meta'  => array(
				'rating'       => '9.6',
				'highlight'    => 'Doskonała reputacja',
				'badges'       => 'Najlepszy bonus, Szybkie wypłaty',
				'brand_color'  => '#B8940F',
				'bonus_type'   => 'Bonus powitalny',
				'bonus_pre'    => '400% bonus',
				'bonus_amount' => '250 zł',
				'bonus_post'   => 'za wpłatę 50 zł',
				'min_deposit'  => '50 zł',
				'license'      => 'Curaçao',
				'license_no'   => 'OGL/2024/1111/0488',
				'feature'      => '5 400+ gier',
				'founded'      => '2021',
				'operator'     => 'Orange Entertainment B.V.',
				'games'        => '5 400+',
				'currency'     => 'PLN',
				'app'          => 'Tak',
				'bonuses'      => "400% bonus: wpłać 50 zł, graj za 250 zł | Bonus powitalny | 50 zł | | |",
				'website'      => '[adres strony]',
				'support'      => '[kanały wsparcia]',
				'payments'     => '[wpłaty — po przecinku]',
				'providers'    => '[producenci — po przecinku]',
				'pros'         => "Przejrzysty interfejs i kolorowy design\nPromocje i turnieje\nGry na żywo z krupierem\nPłatności kryptowalutami\nAplikacja mobilna",
			),
			'content' => '<!-- wp:paragraph --><p>Lemon Casino działa od 2021 roku, jego operatorem jest Orange Entertainment B.V. z licencją Curaçao. W lobby jest ponad 5 400 gier, w tym kasyno na żywo i Aviator.</p><!-- /wp:paragraph -->',
		),
		array( 'title' => 'Slotuna Casino', 'terms' => array( 'nowe', 'blik' ), 'meta' => array( 'rating' => '9.4', 'highlight' => 'Nowość', 'badges' => 'Nowość, BLIK', 'brand_color' => '#2D2A6E', 'bonus_type' => 'Pakiet powitalny', 'bonus_pre' => '100% do', 'bonus_amount' => '2 250 zł', 'bonus_post' => '+ 200 DS + Bonus Crab', 'feature' => 'BLIK' ) ),
		array( 'title' => 'Wild Chance', 'terms' => array( 'nowe' ), 'meta' => array( 'rating' => '9.4', 'highlight' => 'Duży pakiet', 'brand_color' => '#0F5132', 'bonus_pre' => '280% do', 'bonus_amount' => '13 800 zł', 'bonus_post' => '+ 800 DS + 30 monet' ) ),
		array( 'title' => 'Fireball Casino', 'terms' => array( 'krypto', 'darmowe-spiny' ), 'meta' => array( 'rating' => '9.4', 'highlight' => 'Dużo spinów', 'badges' => 'Krypto', 'brand_color' => '#E2531B', 'bonus_pre' => '100% do', 'bonus_amount' => '5 000 zł', 'bonus_post' => '+ 5 000 FS', 'feature' => 'Krypto' ) ),
		array( 'title' => 'Bison Casino', 'terms' => array( 'bez-depozytu', 'darmowe-spiny' ), 'meta' => array( 'rating' => '9.2', 'highlight' => 'Bez depozytu', 'brand_color' => '#5B3A1E', 'bonus_type' => 'Bonus bez depozytu', 'bonus_pre' => 'Bez depozytu', 'bonus_amount' => '60 spinów', 'bonus_post' => 'za rejestrację' ) ),
		array( 'title' => 'Malina Casino', 'terms' => array( 'blik', 'min-depozyt' ), 'meta' => array( 'rating' => '9.2', 'highlight' => 'Niski depozyt', 'badges' => 'BLIK', 'brand_color' => '#B0124F', 'bonus_pre' => '100% do', 'bonus_amount' => '2 250 zł', 'bonus_post' => '+ 200 FS', 'feature' => 'BLIK' ) ),
		array( 'title' => 'Lunubet', 'terms' => array( 'nowe' ), 'meta' => array( 'rating' => '9.2', 'highlight' => 'Nowość', 'brand_color' => '#1B3A5C', 'bonus_type' => 'Pakiet powitalny', 'bonus_pre' => '300% do', 'bonus_amount' => '6 750 zł', 'bonus_post' => '+ 300 DS' ) ),
		array( 'title' => 'Hitnspin Casino', 'terms' => array( 'bez-depozytu', 'krypto' ), 'meta' => array( 'rating' => '9.2', 'highlight' => 'Bez depozytu', 'badges' => 'Krypto', 'brand_color' => '#6A1B9A', 'bonus_type' => 'Bonus bez depozytu', 'bonus_pre' => 'Bez depozytu', 'bonus_amount' => '50 FS', 'bonus_post' => 'w Big Bass Splash', 'founded' => '2023', 'operator' => 'TwiceDice B.V.', 'license' => 'Curaçao Gaming Control Board', 'support' => '24/7 czat na żywo, e-mail', 'games' => '1 063', 'min_deposit' => '20–50 zł', 'app' => 'Android (APK)', 'payments' => 'Przelew bankowy, Sofort, Przelewy24, MiFinity, Binance Pay, Bitcoin, Tether', 'withdrawals' => 'Ta sama metoda co wpłata', 'bonuses' => "50 FS Big Bass Splash | Bonus bez depozytu | | | |\n100% do 2000 zł + 75 FS w Gates of Olympus Super Scatter | 1. depozyt | 40 zł | | |\n120% do 2000 zł + 125 FS | Alternatywa — 60 min po rejestracji | | | |\n100% do 2000 zł + 50 FS w Joker Stoker | 2. depozyt | 40 zł | | |\n120% do 2000 zł + 75 FS w Coin Win 2: Hold The Spin | 3. depozyt | 40 zł | | |\n375 FS | 1. depozyt | 60 zł | | |\n375 FS + 50% bonus do 1000 zł | Alternatywa — 60 min po rejestracji | | | |" ) ),
	);
}

/**
 * Tworzy przykładowe kasyna (pomija istniejące o tym samym tytule).
 *
 * @return int Liczba utworzonych.
 */
function ekasyna_import_demo() {
	ekasyna_activate();
	$created = 0;
	foreach ( ekasyna_demo_data() as $item ) {
		$exists = get_posts(
			array(
				'post_type'      => 'kasyno',
				'title'          => $item['title'],
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $exists ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'kasyno',
				'post_status'  => 'publish',
				'post_title'   => $item['title'],
				'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
				'post_content' => isset( $item['content'] ) ? $item['content'] : '',
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		foreach ( $item['meta'] as $k => $v ) {
			update_post_meta( $id, '_ek_' . $k, $v );
		}
		wp_set_object_terms( $id, $item['terms'], 'kategoria-kasyna' );
		++$created;
	}
	// Strona „Wszystkie kasyna online” z szablonem A–Z.
	if ( ! get_page_by_path( 'wszystkie-kasyna-online' ) ) {
		$page = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Wszystkie kasyna online',
				'post_name'   => 'wszystkie-kasyna-online',
				'post_author' => get_current_user_id() ? get_current_user_id() : 1,
				'post_excerpt' => 'Pełna lista kasyn online, które opisaliśmy — alfabetycznie. Kliknij nazwę, aby przeczytać recenzję.',
			)
		);
		if ( $page && ! is_wp_error( $page ) ) {
			update_post_meta( $page, '_wp_page_template', 'page-templates/wszystkie-kasyna.php' );
		}
	}

	return $created;
}

/**
 * Strona w menu Wygląd.
 */
function ekasyna_demo_menu() {
	add_theme_page( __( 'Dane demo e-kasyna', 'ekasyna' ), __( 'Dane demo e-kasyna', 'ekasyna' ), 'manage_options', 'ekasyna-demo', 'ekasyna_demo_page' );
}
add_action( 'admin_menu', 'ekasyna_demo_menu' );

/**
 * Widok strony importu.
 */
function ekasyna_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = null;
	if ( isset( $_POST['ekasyna_demo'] ) && check_admin_referer( 'ekasyna_demo' ) ) {
		$done = ekasyna_import_demo();
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Dane demo e-kasyna', 'ekasyna' ); ?></h1>
		<?php if ( null !== $done ) : ?>
			<div class="notice notice-success"><p><?php printf( /* translators: %d liczba */ esc_html__( 'Utworzono kasyn: %d.', 'ekasyna' ), (int) $done ); ?></p></div>
		<?php endif; ?>
		<p><?php esc_html_e( 'Tworzy kategorie rankingu, 8 przykładowych kasyn z bonusami i ocenami oraz stronę „Wszystkie kasyna online” (katalog A–Z). Istniejące kasyna o tym samym tytule są pomijane.', 'ekasyna' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'ekasyna_demo' ); ?>
			<?php submit_button( __( 'Importuj dane demo', 'ekasyna' ), 'primary', 'ekasyna_demo' ); ?>
		</form>
	</div>
	<?php
}
