<?php
/**
 * Teksty demo: recenzje, strona rankingu, kategorie, katalog A–Z, strona główna i poradnik.
 * Treści oparte na danych kasyna z pól — tam, gdzie pola są puste, tekst pozostaje ogólny.
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Pomocnicze generatory bloków ---------- */

/**
 * Nagłówek bloku.
 *
 * @param string $t     Tekst.
 * @param int    $level Poziom.
 * @return string
 */
function ekd_h( $t, $level = 2 ) {
	$attr = 2 === $level ? '' : ' {"level":' . (int) $level . '}';
	return '<!-- wp:heading' . $attr . ' --><h' . $level . ' class="wp-block-heading">' . esc_html( $t ) . '</h' . $level . '><!-- /wp:heading -->' . "\n\n";
}

/**
 * Akapit (dozwolony prosty HTML: <strong>, <a>).
 *
 * @param string $t Tekst.
 * @return string
 */
function ekd_p( $t ) {
	return '<!-- wp:paragraph --><p>' . wp_kses_post( $t ) . '</p><!-- /wp:paragraph -->' . "\n\n";
}

/**
 * Lista.
 *
 * @param string[] $items   Pozycje.
 * @param bool     $ordered Numerowana.
 * @return string
 */
function ekd_list( $items, $ordered = false ) {
	$tag  = $ordered ? 'ol' : 'ul';
	$attr = $ordered ? ' {"ordered":true}' : '';
	$li   = '';
	foreach ( $items as $i ) {
		$li .= '<!-- wp:list-item --><li>' . wp_kses_post( $i ) . '</li><!-- /wp:list-item -->';
	}
	return '<!-- wp:list' . $attr . ' --><' . $tag . ' class="wp-block-list">' . $li . '</' . $tag . '><!-- /wp:list -->' . "\n\n";
}

/**
 * Tabela.
 *
 * @param string[]   $head Nagłówki.
 * @param string[][] $rows Wiersze.
 * @return string
 */
function ekd_table( $head, $rows ) {
	$h = '<thead><tr>' . implode( '', array_map( function ( $c ) { return '<th>' . esc_html( $c ) . '</th>'; }, $head ) ) . '</tr></thead>';
	$b = '<tbody>';
	foreach ( $rows as $r ) {
		$b .= '<tr>' . implode( '', array_map( function ( $c ) { return '<td>' . esc_html( $c ) . '</td>'; }, $r ) ) . '</tr>';
	}
	$b .= '</tbody>';
	return '<!-- wp:table --><figure class="wp-block-table"><table>' . $h . $b . '</table></figure><!-- /wp:table -->' . "\n\n";
}

/**
 * FAQ: lista bloków „Szczegóły”.
 *
 * @param array $qa Pary [pytanie, odpowiedź].
 * @return string
 */
function ekd_faq( $qa ) {
	$out = '';
	foreach ( $qa as $x ) {
		$out .= '<!-- wp:details --><details class="wp-block-details"><summary>' . esc_html( $x[0] ) . '</summary><!-- wp:paragraph --><p>' . esc_html( $x[1] ) . '</p><!-- /wp:paragraph --></details><!-- /wp:details -->' . "\n\n";
	}
	return $out;
}

/**
 * Zwykły HTML (do opisów kategorii — bez komentarzy bloków).
 *
 * @param string $html Treść z komentarzami bloków.
 * @return string
 */
function ekd_plain( $html ) {
	return trim( preg_replace( '/<!-- \/?wp:[^>]*-->/', '', $html ) );
}

/* ---------- Recenzja kasyna ---------- */

/**
 * Pełna recenzja na podstawie pól kasyna.
 *
 * @param string $name Nazwa.
 * @param array  $m    Pola (bez prefiksu _ek_).
 * @return string
 */
function ekasyna_demo_review_html( $name, $m ) {
	$g        = function ( $k ) use ( $m ) {
		return isset( $m[ $k ] ) && '' !== $m[ $k ] && '[' !== substr( $m[ $k ], 0, 1 ) ? $m[ $k ] : '';
	};
	$bonus    = trim( $g( 'bonus_pre' ) . ' ' . $g( 'bonus_amount' ) . ' ' . $g( 'bonus_post' ) );
	$type     = $g( 'bonus_type' ) ? mb_strtolower( $g( 'bonus_type' ) ) : 'bonus powitalny';
	$license  = $g( 'license' );
	$operator = $g( 'operator' );
	$founded  = $g( 'founded' );
	$games    = $g( 'games' );
	$payments = $g( 'payments' );
	$support  = $g( 'support' );
	$app      = $g( 'app' );
	$min      = $g( 'min_deposit' );

	$intro = $name . ' to kasyno online, które akceptuje graczy z Polski i pozwala grać w złotówkach.';
	if ( $founded || $operator || $license ) {
		$bits = array();
		if ( $founded ) {
			$bits[] = 'działa od ' . $founded . ' roku';
		}
		if ( $operator ) {
			$bits[] = 'jego operatorem jest ' . $operator;
		}
		if ( $license ) {
			$bits[] = 'posiada licencję ' . $license;
		}
		$intro .= ' Serwis ' . implode( ', ', $bits ) . '.';
	}
	$intro .= ' W recenzji opisujemy bonusy, wybór gier, metody płatności i to, na co zwrócić uwagę przed rejestracją.';

	$h  = ekd_p( $intro );
	$h .= ekd_h( 'Bonusy w ' . $name );
	$h .= ekd_p( $bonus
		? 'Główna oferta dla nowych graczy to ' . $type . ': <strong>' . esc_html( $bonus ) . '</strong>' . ( $min ? ', przy minimalnej wpłacie ' . esc_html( $min ) : '' ) . '. Pełną listę aktualnych ofert znajdziesz w bloku „Najlepsze bonusy” na górze tej strony.'
		: 'Aktualne oferty kasyna znajdziesz w bloku „Najlepsze bonusy” na górze tej strony.' );
	$h .= ekd_p( 'Zanim aktywujesz bonus, sprawdź w regulaminie trzy rzeczy:' );
	$h .= ekd_list(
		array(
			'<strong>wymagania obrotu</strong> — ile razy trzeba obrócić bonusem, zanim wypłacisz wygraną;',
			'<strong>maksymalny zakład</strong> podczas gry ze środkami bonusowymi;',
			'<strong>termin ważności</strong> bonusu i darmowych spinów.',
		)
	);
	$h .= ekd_h( 'Gry i dostawcy' );
	$h .= ekd_p( ( $games ? 'W lobby jest ' . esc_html( $games ) . ' gier' : 'Lobby obejmuje setki gier' ) . ': automaty wideo i klasyczne owocówki, gry stołowe, kasyno na żywo z prawdziwymi krupierami oraz gry typu crash. Większość slotów można uruchomić w trybie demo, bez wpłaty.' );
	$h .= ekd_p( 'Gry można filtrować według dostawcy, popularności i nowości. Listę producentów znajdziesz w tabeli „Informacje o kasynie”.' );
	$h .= ekd_h( 'Wpłaty i wypłaty' );
	$h .= ekd_p( ( $payments ? 'Dostępne metody wpłat: ' . esc_html( $payments ) . '.' : 'Kasyno obsługuje popularne w Polsce metody płatności.' ) . ' Przed pierwszą wypłatą trzeba przejść weryfikację tożsamości (KYC) — przygotuj dowód osobisty lub paszport oraz potwierdzenie adresu.' );
	$h .= ekd_p( 'Najszybciej środki trafiają na e-portfele i w kryptowalutach. Wypłata na kartę bankową może potrwać do kilku dni roboczych. Wypłatę zleca się zwykle tą samą metodą, którą wpłacano.' );
	$h .= ekd_h( 'Rejestracja krok po kroku' );
	$h .= ekd_list(
		array(
			'Przejdź na stronę kasyna przyciskiem „Odbierz bonus”.',
			'Kliknij „Zarejestruj się” i podaj e-mail, hasło oraz walutę konta (PLN).',
			'Potwierdź, że masz ukończone 18 lat, i zaakceptuj regulamin.',
			'Potwierdź adres e-mail lub numer telefonu.',
			'Wybierz bonus powitalny i dokonaj pierwszej wpłaty.',
		),
		true
	);
	$h .= ekd_h( 'Wersja mobilna' );
	$h .= ekd_p( 'Strona działa w przeglądarce telefonu bez instalowania aplikacji, a interfejs dopasowuje się do ekranu.' . ( $app ? ' Dostępna jest też aplikacja: ' . esc_html( $app ) . '.' : '' ) );
	$h .= ekd_h( 'Obsługa klienta' );
	$h .= ekd_p( $support ? 'Wsparcie: ' . esc_html( $support ) . '. Przed kontaktem warto zajrzeć do sekcji FAQ kasyna — odpowiada na większość pytań o bonusy i wypłaty.' : 'Pomoc jest dostępna przez czat na żywo i e-mail. Przed kontaktem warto zajrzeć do sekcji FAQ kasyna.' );
	$h .= ekd_h( 'Bezpieczeństwo i licencja' );
	$h .= ekd_p( ( $license ? 'Kasyno działa na licencji ' . esc_html( $license ) . '.' : 'Przed rejestracją sprawdź licencję kasyna w stopce jego strony.' ) . ' Jedynym kasynem online z polską licencją jest Total Casino — pozostałe serwisy działają na licencjach zagranicznych. Połączenie ze stroną jest szyfrowane, a dane graczy chronione.' );
	$h .= ekd_h( 'Czy warto grać w ' . $name . '? Podsumowanie' );
	$h .= ekd_p( $name . ' to propozycja dla graczy, którzy szukają ' . ( $bonus ? 'oferty „' . esc_html( $bonus ) . '” na start' : 'atrakcyjnego bonusu na start' ) . ', dużego wyboru gier i płatności w złotówkach. Pamiętaj, aby przed aktywacją bonusu przeczytać regulamin, ustalić budżet i grać odpowiedzialnie.' );
	$h .= ekd_h( 'FAQ — ' . $name );
	$h .= ekd_faq(
		array(
			array( 'Czy ' . $name . ' akceptuje graczy z Polski?', 'Tak, można założyć konto w złotówkach i korzystać ze strony po polsku.' ),
			array( 'Jaki bonus czeka na nowych graczy?', $bonus ? 'Na start: ' . $bonus . '. Szczegóły i pozostałe oferty znajdziesz na górze recenzji.' : 'Aktualne oferty znajdziesz na górze recenzji.' ),
			array( 'Ile trwa wypłata?', 'E-portfele i kryptowaluty zwykle kilka godzin, karta bankowa do kilku dni. Weryfikacja konta przed pierwszą wypłatą przyspiesza proces.' ),
			array( 'Czy można grać na telefonie?', 'Tak, strona działa w przeglądarce mobilnej.' . ( $app ? ' Jest też aplikacja (' . $app . ').' : '' ) ),
		)
	);
	return $h;
}

/* ---------- Strona „Ranking kasyn” ---------- */

/**
 * Treść pod pełnym rankingiem.
 *
 * @return string
 */
function ekasyna_demo_ranking_html() {
	$h  = ekd_h( 'Jak powstaje ranking kasyn online' );
	$h .= ekd_p( 'Każde kasyno w rankingu oceniamy według tych samych kryteriów. Redaktorzy zakładają konto, aktywują bonus, wpłacają środki i zlecają wypłatę — dopiero wtedy wystawiamy ocenę w skali 0–10. Ranking aktualizujemy co miesiąc.' );
	$h .= ekd_table(
		array( 'Kryterium', 'Co sprawdzamy' ),
		array(
			array( 'Licencja', 'Regulator i numer licencji w jego rejestrze' ),
			array( 'Bonusy', 'Czy wymagania obrotu da się realnie spełnić' ),
			array( 'Gry', 'Liczba tytułów, kasyno na żywo, wersje demo' ),
			array( 'Płatności', 'PLN, BLIK, limity i czas wypłaty testowej' ),
			array( 'Opinie graczy', 'Skargi na wypłaty i ich autentyczność' ),
			array( 'Obsługa', 'Czas odpowiedzi czatu i jakość pomocy' ),
		)
	);
	$h .= ekd_h( 'Sytuacja prawna kasyn online w Polsce' );
	$h .= ekd_p( 'Rynek gier hazardowych w Polsce reguluje ustawa z 19 listopada 2009 r., znowelizowana w 2016 r. Jedynym licencjonowanym kasynem online w kraju jest Total Casino, prowadzone przez Totalizator Sportowy. Kasyna z rankingu działają na licencjach zagranicznych.' );
	$h .= ekd_h( 'Jak wybrać kasyno z rankingu' );
	$h .= ekd_list(
		array(
			'<strong>Określ, czego szukasz</strong> — bonusu bez depozytu, płatności BLIK, niskiej wpłaty czy kasyna na żywo. Użyj kategorii nad listą.',
			'<strong>Porównaj warunki bonusu</strong>, a nie tylko jego wysokość — liczy się obrót i limit wypłaty.',
			'<strong>Sprawdź metody wypłaty</strong> i czas realizacji.',
			'<strong>Przeczytaj recenzję</strong> — znajdziesz w niej plusy, minusy i pełną listę bonusów.',
		),
		true
	);
	$h .= ekd_h( 'Najczęstsze pytania' );
	$h .= ekd_faq(
		array(
			array( 'Jak często aktualizujecie ranking?', 'Co miesiąc oraz zawsze, gdy kasyno zmienia warunki bonusów lub pojawiają się skargi na wypłaty.' ),
			array( 'Dlaczego kasyno spadło w rankingu?', 'Najczęściej z powodu gorszych warunków bonusu, wydłużonych wypłat albo nowych skarg graczy.' ),
			array( 'Czy linki w rankingu są partnerskie?', 'Tak, część linków jest partnerska. Nie wpływa to na ocenę — kolejność wynika z kryteriów opisanych wyżej.' ),
		)
	);
	return $h;
}

/* ---------- Kategorie ---------- */

/**
 * Teksty kategorii (zwykły HTML).
 *
 * @return array slug => html
 */
function ekasyna_demo_category_html() {
	$t = array();

	$t['bez-depozytu'] = ekd_h( 'Czym jest bonus bez depozytu' )
		. ekd_p( 'Bonus bez depozytu to środki lub darmowe spiny, które kasyno przyznaje za samą rejestrację — bez wpłacania pieniędzy. To dobry sposób, aby sprawdzić kasyno i jego gry bez ryzyka.' )
		. ekd_h( 'Warunki, na które trzeba uważać' )
		. ekd_list( array( 'wymagania obrotu są zwykle wyższe niż przy bonusach od depozytu;', 'maksymalna wygrana z bonusu jest ograniczona;', 'bonus często trzeba wykorzystać w ciągu kilku dni;', 'do wypłaty wymagana jest weryfikacja konta.' ) )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Czy bonus bez depozytu jest naprawdę darmowy?', 'Tak, ale wygraną można wypłacić dopiero po spełnieniu warunków obrotu.' ), array( 'Czy mogę odebrać kilka takich bonusów?', 'W każdym kasynie tylko jeden na osobę, ale możesz skorzystać z ofert różnych kasyn.' ) ) );

	$t['darmowe-spiny'] = ekd_h( 'Darmowe spiny — jak działają' )
		. ekd_p( 'Darmowe spiny (FS, DS) to obroty na wybranym automacie, za które nie płacisz. Kasyna dają je w pakietach od 20 do kilkuset spinów — za rejestrację, w pakiecie powitalnym lub w cotygodniowych promocjach.' )
		. ekd_h( 'Na co zwrócić uwagę' )
		. ekd_list( array( 'na jakim slocie można wykorzystać spiny;', 'jaka jest wartość jednego spinu;', 'czy wygrane ze spinów mają wymagania obrotu;', 'w jakim czasie trzeba je wykorzystać.' ) )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Czy wygrane z darmowych spinów można wypłacić?', 'Tak, po spełnieniu warunków obrotu opisanych w regulaminie promocji.' ) ) );

	$t['blik'] = ekd_h( 'Wpłaty BLIK w kasynie online' )
		. ekd_p( 'BLIK to najpopularniejsza metoda płatności w Polsce: wpłatę potwierdzasz 6-cyfrowym kodem z aplikacji bankowej. Środki trafiają na konto w kasynie w kilka sekund, a nie musisz podawać danych karty.' )
		. ekd_h( 'Jak wpłacić przez BLIK' )
		. ekd_list( array( 'W kasie kasyna wybierz BLIK.', 'Wpisz kwotę wpłaty.', 'Wygeneruj kod w aplikacji banku i wpisz go na stronie.', 'Potwierdź płatność w aplikacji.' ), true )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Czy BLIK-iem można wypłacić wygraną?', 'Zwykle nie — wypłaty realizuje się przelewem, e-portfelem lub w kryptowalutach.' ) ) );

	$t['min-depozyt'] = ekd_h( 'Kasyna z niskim depozytem' )
		. ekd_p( 'W tych kasynach zaczniesz grać za 1, 5, 10 lub 20 zł. To dobre rozwiązanie, jeśli chcesz sprawdzić serwis przy minimalnym budżecie.' )
		. ekd_p( 'Pamiętaj, że bonus powitalny często wymaga wyższej wpłaty niż minimalny depozyt — sprawdź to w recenzji.' )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Jaki jest najniższy możliwy depozyt?', 'W niektórych kasynach 1 zł, ale najczęściej 10–20 zł, zależnie od metody płatności.' ) ) );

	$t['nowe'] = ekd_h( 'Nowe kasyna online' )
		. ekd_p( 'Nowe kasyna walczą o graczy, dlatego często mają hojniejsze pakiety powitalne i nowoczesne strony. Zanim się zarejestrujesz, sprawdź licencję, operatora i opinie o wypłatach — przy młodych markach to szczególnie ważne.' )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Czy nowe kasyna są bezpieczne?', 'Te z rankingu mają licencję i przeszły nasz test wpłaty oraz wypłaty.' ) ) );

	$t['krypto'] = ekd_h( 'Kasyna z kryptowalutami' )
		. ekd_p( 'Kryptokasyna przyjmują wpłaty i realizują wypłaty w Bitcoinie, Tetherze i innych walutach cyfrowych. Zaletą są szybkie wypłaty i brak pośredników.' )
		. ekd_p( 'Kurs kryptowalut się zmienia — wartość wygranej w złotówkach może być inna w dniu wypłaty niż w dniu gry.' )
		. ekd_h( 'FAQ' )
		. ekd_faq( array( array( 'Czy mogę wpłacić krypto i wypłacić w PLN?', 'Zależy od kasyna — zwykle wypłata idzie tą samą metodą co wpłata.' ) ) );

	return array_map( 'ekd_plain', $t );
}

/* ---------- Katalog A–Z ---------- */

/**
 * Treść pod katalogiem A–Z.
 *
 * @return string
 */
function ekasyna_demo_az_html() {
	$h  = ekd_h( 'Jak korzystać z katalogu' );
	$h .= ekd_p( 'W katalogu znajdziesz wszystkie kasyna, które opisaliśmy — również te spoza aktualnego rankingu TOP. Kliknij literę, aby przejść do kasyn na nią, albo wpisz nazwę w wyszukiwarkę.' );
	$h .= ekd_p( 'Przy kasynach z aktualną oceną redakcji widać notę w skali 0–10. Pełne porównanie bonusów znajdziesz w <a href="' . esc_url( get_post_type_archive_link( 'kasyno' ) ) . '">rankingu kasyn</a>.' );
	$h .= ekd_h( 'Dlaczego niektórych kasyn nie ma w rankingu' );
	$h .= ekd_p( 'Ranking pokazuje kasyna, które w ostatnim teście spełniły nasze kryteria. Kasyna z gorszymi warunkami bonusów, dłuższymi wypłatami lub skargami graczy zostają w katalogu z recenzją, ale bez miejsca w rankingu.' );
	$h .= ekd_h( 'Najczęstsze pytania' );
	$h .= ekd_faq(
		array(
			array( 'Jak często dodajecie nowe kasyna?', 'Nowe recenzje pojawiają się co tydzień, a katalog aktualizuje się automatycznie.' ),
			array( 'Nie widzę kasyna, którego szukam.', 'Napisz do nas przez stronę kontaktową — sprawdzimy je i dodamy recenzję.' ),
		)
	);
	return $h;
}

/* ---------- Strona główna ---------- */

/**
 * Artykuł strony głównej.
 *
 * @return string
 */
function ekasyna_demo_home_html() {
	$h  = ekd_h( 'Sytuacja prawna kasyn online w Polsce' );
	$h .= ekd_p( 'Rynek gier hazardowych w Polsce reguluje ustawa z 19 listopada 2009 r., znowelizowana w grudniu 2016 r. Jedynym licencjonowanym kasynem online w kraju jest Total Casino, prowadzone przez Totalizator Sportowy. Do szarej strefy należą kasyna z licencjami wydanymi poza Polską — wiele z nich oferuje obsługę po polsku i konta w złotówkach.' );
	$h .= ekd_h( 'Jak wybrać dobre kasyno online' );
	$h .= ekd_list(
		array(
			'<strong>Licencja</strong> — numer licencji powinien być widoczny w stopce i możliwy do sprawdzenia u regulatora.',
			'<strong>Bonusy</strong> — liczy się nie wysokość, ale wymagania obrotu i limit wypłaty z bonusu.',
			'<strong>Gry</strong> — renomowani dostawcy i wersje demo to dobry znak.',
			'<strong>Opinie graczy</strong> — zwróć uwagę zwłaszcza na skargi dotyczące wypłat.',
			'<strong>Płatności</strong> — BLIK, przelewy w PLN i e-portfele z szybką wypłatą.',
		),
		true
	);
	$h .= ekd_h( 'Rodzaje bonusów kasynowych' );
	$h .= ekd_table(
		array( 'Rodzaj bonusu', 'Na czym polega' ),
		array(
			array( 'Darmowe spiny', 'Obroty na wybranych slotach, zwykle w pakietach 20–100' ),
			array( 'Bez depozytu', 'Środki lub spiny za samą rejestrację' ),
			array( 'Pakiet powitalny', 'Bonusy od kilku pierwszych wpłat' ),
			array( 'Cashback', 'Zwrot części strat, tygodniowo lub miesięcznie' ),
			array( 'Program VIP', 'Poziomy lojalnościowe z dodatkowymi nagrodami' ),
		)
	);
	$h .= ekd_h( 'Automaty do gry i RTP' );
	$h .= ekd_p( 'W kasynach z naszej listy znajdziesz od 5 000 do 7 000 automatów. Przed grą sprawdź RTP, czyli teoretyczny procent zwrotu: średnia to około 95%, a wartości powyżej 96% uchodzą za wysokie. Liczy się też zmienność — przy niskiej wygrane są częste, ale małe, przy wysokiej rzadsze i większe.' );
	$h .= ekd_h( 'Wypłata wygranych' );
	$h .= ekd_p( 'Najszybciej trafiają do Ciebie wypłaty na e-portfele i w kryptowalutach — zwykle w ciągu kilku godzin. Na kartę bankową trzeba czasem poczekać do 3 dni. Wypłatę przyspieszysz, jeśli wcześniej przejdziesz weryfikację, spełnisz warunki obrotu bonusem i wybierzesz tę samą metodę, którą wpłacałeś.' );
	$h .= ekd_h( 'Najczęstsze pytania' );
	$h .= ekd_faq(
		array(
			array( 'Które kasyna online są legalne w Polsce?', 'Jedynym kasynem online z polską licencją jest Total Casino, prowadzone przez Totalizator Sportowy. Licencje wydaje Ministerstwo Finansów.' ),
			array( 'Czy mogę grać w złotówkach?', 'Tak, większość kasyn z rankingu prowadzi konta w PLN, więc nie tracisz na przewalutowaniu.' ),
			array( 'Jak szybko wypłacę wygraną?', 'E-portfele i kryptowaluty zwykle w ciągu godzin, karty bankowe do 3 dni. Wcześniejsza weryfikacja KYC skraca ten czas.' ),
			array( 'Czym jest bonus bez depozytu?', 'To środki lub darmowe spiny przyznawane za rejestrację, bez wpłaty. Zwykle mają wymagania obrotu.' ),
		)
	);
	return $h;
}

/* ---------- Poradnik ---------- */

/**
 * Poradnik „Jak wypłacić wygraną z kasyna”.
 *
 * @return string
 */
function ekasyna_demo_article_html() {
	$h  = ekd_p( 'Wypłata wygranej z kasyna online zwykle trwa od kilku minut do kilku dni. Ile dokładnie — zależy od metody płatności, weryfikacji konta i tego, czy grałeś z bonusem. Poniżej wyjaśniamy cały proces krok po kroku.' );
	$h .= ekd_h( 'Weryfikacja konta (KYC)' );
	$h .= ekd_p( 'Przed pierwszą wypłatą kasyno poprosi o potwierdzenie tożsamości. Najczęściej potrzebne są:' );
	$h .= ekd_list( array( 'dowód osobisty, paszport lub prawo jazdy;', 'potwierdzenie adresu — rachunek lub wyciąg z ostatnich 3 miesięcy;', 'czasem zdjęcie z dokumentem (selfie).' ) );
	$h .= ekd_p( 'Weryfikacja trwa zwykle od kilku godzin do 3 dni. Najlepiej przejść ją od razu po rejestracji.' );
	$h .= ekd_h( 'Metody wypłaty i czas realizacji' );
	$h .= ekd_table(
		array( 'Metoda', 'Typowy czas wypłaty' ),
		array(
			array( 'Kryptowaluty', 'Od kilku minut' ),
			array( 'E-portfele (Skrill, MiFinity)', 'Do 24 h' ),
			array( 'Przelew bankowy', '1–3 dni robocze' ),
			array( 'Karta Visa / Mastercard', '2–3 dni robocze' ),
		)
	);
	$h .= ekd_h( 'Jak przyspieszyć wypłatę' );
	$h .= ekd_list(
		array(
			'Przejdź weryfikację przed pierwszą wypłatą.',
			'Spełnij warunki obrotu bonusem — niespełnione blokują wypłatę.',
			'Wypłacaj tą samą metodą, którą wpłacałeś.',
			'Sprawdź minimalną i maksymalną kwotę wypłaty.',
		),
		true
	);
	$h .= ekd_h( 'Najczęstsze pytania' );
	$h .= ekd_faq(
		array(
			array( 'Dlaczego kasyno wstrzymało wypłatę?', 'Najczęściej z powodu niezakończonej weryfikacji albo niespełnionych warunków bonusu.' ),
			array( 'Czy od wygranej trzeba zapłacić podatek?', 'To zależy od Twojej sytuacji — skonsultuj się z doradcą podatkowym.' ),
		)
	);
	return $h;
}

/**
 * Wypełnia treści demo: recenzje (puste lub z flagą $overwrite), strony, kategorie, poradnik.
 *
 * @param bool $overwrite Nadpisz istniejące treści.
 */
function ekasyna_demo_apply_content( $overwrite = false ) {
	$author = get_current_user_id() ? get_current_user_id() : 1;

	// Recenzje.
	foreach ( get_posts( array( 'post_type' => 'kasyno', 'posts_per_page' => -1, 'post_status' => 'publish' ) ) as $p ) {
		$short = mb_strlen( wp_strip_all_tags( $p->post_content ) ) < 600;
		if ( ! $overwrite && ! $short ) {
			continue;
		}
		$meta = array();
		foreach ( array( 'bonus_pre', 'bonus_amount', 'bonus_post', 'bonus_type', 'license', 'operator', 'founded', 'games', 'payments', 'support', 'app', 'min_deposit' ) as $k ) {
			$meta[ $k ] = (string) get_post_meta( $p->ID, '_ek_' . $k, true );
		}
		wp_update_post( array( 'ID' => $p->ID, 'post_content' => ekasyna_demo_review_html( $p->post_title, $meta ) ) );
	}

	// Plusy i minusy, jeśli puste (ogólne, prawdziwe dla kasyn z licencją zagraniczną).
	foreach ( get_posts( array( 'post_type' => 'kasyno', 'posts_per_page' => -1, 'post_status' => 'publish' ) ) as $p ) {
		$m = function ( $k ) use ( $p ) {
			$v = (string) get_post_meta( $p->ID, '_ek_' . $k, true );
			return '[' === substr( $v, 0, 1 ) ? '' : $v;
		};
		foreach ( array( 'website', 'support', 'payments', 'providers', 'withdrawals' ) as $k ) {
			if ( '[' === substr( (string) get_post_meta( $p->ID, '_ek_' . $k, true ), 0, 1 ) ) {
				delete_post_meta( $p->ID, '_ek_' . $k );
			}
		}
		if ( ! $m( 'pros' ) ) {
			$pros = array( 'Konto i płatności w złotówkach' );
			if ( $m( 'bonus_amount' ) ) {
				$pros[] = 'Bonus na start: ' . trim( $m( 'bonus_pre' ) . ' ' . $m( 'bonus_amount' ) . ' ' . $m( 'bonus_post' ) );
			}
			if ( $m( 'games' ) ) {
				$pros[] = $m( 'games' ) . ' gier, w tym kasyno na żywo';
			}
			if ( $m( 'app' ) ) {
				$pros[] = 'Aplikacja mobilna: ' . $m( 'app' );
			}
			$pros[] = 'Strona dostosowana do telefonów';
			update_post_meta( $p->ID, '_ek_pros', implode( "\n", $pros ) );
		}
		if ( ! $m( 'cons' ) ) {
			$license = $m( 'license' );
			$cons    = array(
				'Brak polskiej licencji — kasyno działa na licencji ' . ( $license ? $license : 'zagranicznej' ),
				'Bonusy mają wymagania obrotu — sprawdź regulamin przed aktywacją',
			);
			update_post_meta( $p->ID, '_ek_cons', implode( "\n", $cons ) );
		}
	}

	// Pop-up bonusu (Fireball Casino).
	if ( ! get_theme_mod( 'ekasyna_popup_casino' ) ) {
		$fb = get_posts( array( 'post_type' => 'kasyno', 'title' => 'Fireball Casino', 'posts_per_page' => 1 ) );
		if ( $fb ) {
			set_theme_mod( 'ekasyna_popup_on', 1 );
			set_theme_mod( 'ekasyna_popup_casino', $fb[0]->ID );
			set_theme_mod( 'ekasyna_popup_amount', '40 darmowych spinów' );
			set_theme_mod( 'ekasyna_popup_sub', 'na Gates of Olympus' );
		}
	}

	// Pop-up newslettera.
	if ( '' === (string) get_theme_mod( 'ekasyna_nl_on', '' ) ) {
		set_theme_mod( 'ekasyna_nl_on', 1 );
	}

	// Strona tekstu rankingu.
	$rank = get_page_by_path( 'ranking-kasyn-tekst' );
	if ( ! $rank ) {
		$rank_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'Ranking kasyn online 2026',
				'post_name'    => 'ranking-kasyn-tekst',
				'post_author'  => $author,
				'post_excerpt' => 'Wszystkie kasyna z naszego rankingu w jednym miejscu — z oceną redakcji, bonusem, licencją i czasem wypłaty. Kolejność wg oceny 0–10.',
				'post_content' => ekasyna_demo_ranking_html(),
			)
		);
	} else {
		$rank_id = $rank->ID;
		if ( $overwrite ) {
			wp_update_post( array( 'ID' => $rank_id, 'post_content' => ekasyna_demo_ranking_html() ) );
		}
	}
	if ( $rank_id && ! get_theme_mod( 'ekasyna_archive_page' ) ) {
		set_theme_mod( 'ekasyna_archive_page', (int) $rank_id );
	}

	// Kategorie.
	foreach ( ekasyna_demo_category_html() as $slug => $html ) {
		$t = get_term_by( 'slug', $slug, 'kategoria-kasyna' );
		if ( $t && ( $overwrite || ! get_term_meta( $t->term_id, 'ekasyna_content', true ) ) ) {
			update_term_meta( $t->term_id, 'ekasyna_content', $html );
		}
	}

	// Katalog A–Z.
	$az = get_page_by_path( 'wszystkie-kasyna-online' );
	if ( $az && ( $overwrite || '' === trim( $az->post_content ) ) ) {
		wp_update_post( array( 'ID' => $az->ID, 'post_content' => ekasyna_demo_az_html() ) );
	}

	// Strona główna (tylko jeśli witryna pokazuje wpisy).
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$home = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'Kasyna online w Polsce',
				'post_author'  => $author,
				'post_content' => ekasyna_demo_home_html(),
			)
		);
		if ( $home && ! is_wp_error( $home ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
		}
	} elseif ( $overwrite && get_option( 'page_on_front' ) ) {
		wp_update_post( array( 'ID' => (int) get_option( 'page_on_front' ), 'post_content' => ekasyna_demo_home_html() ) );
	}

	// Poradnik.
	$article = get_posts( array( 'post_type' => 'post', 'title' => 'Jak wypłacić wygraną z kasyna', 'posts_per_page' => 1, 'post_status' => 'any' ) );
	if ( ! $article ) {
		wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => 'Jak wypłacić wygraną z kasyna',
				'post_author'  => $author,
				'post_excerpt' => 'Weryfikacja, metody wypłaty, czas realizacji i sposoby, aby dostać pieniądze szybciej.',
				'post_content' => ekasyna_demo_article_html(),
			)
		);
	} elseif ( $overwrite ) {
		wp_update_post( array( 'ID' => $article[0]->ID, 'post_content' => ekasyna_demo_article_html(), 'post_excerpt' => 'Weryfikacja, metody wypłaty, czas realizacji i sposoby, aby dostać pieniądze szybciej.' ) );
	}
}
