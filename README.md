# e-kasyna — тема WordPress

Тема для сайта-рейтинга онлайн-казино для игроков из Польши (e-kasyna.com). Весь интерфейс на польском.

![screenshot](screenshot.png)

## Что внутри

- **Рейтинг казино.** Подиум TOP-3 (золото, серебро, бронза) и строки для мест 4+. В каждой карточке:
  - угловые ярлыки «★ 9.6/10» и «TOP N»;
  - бонус в три строки;
  - метка редакции с галочкой;
  - таблица «Licencja | Czas wypłat | Osobliwości» с номером лицензии;
  - кнопка «Przejdź do kasyna» и ссылка «Opinie o …».
- **Фильтры рейтинга** по категориям (`?ek_kat=blik`). Работают на сервере, без JS, ссылки индексируемые.
- **Тип записи «Kasyno»** (`/recenzje/…`) со всеми полями обзора. Полный рейтинг — `/ranking-kasyn/`, категории — `/kasyna/{slug}/`.
- **Страница обзора:**
  - хлебные крошки и оценка;
  - блок «Najlepsze bonusy {kasyno}» — сетка карточек всех бонусов казино: тип, условия, промокод с кнопкой «Kopiuj», «Odbierz bonus»;
  - таблица «Informacje o kasynie» в две колонки (Kasyno, Strona, Licencja, Właściciel, Rok, Wsparcie, Producenci, Wpłaty, Wypłaty…); длинные списки сворачиваются в «+N więcej»;
  - плюсы и минусы, карточка бонуса и похожие казино в сайдбаре.
- **Страница «Wszystkie kasyna online»** (шаблон «Wszystkie kasyna (A–Z)» или шорткод `[ekasyna_az]`): алфавитный указатель 0-9 / A–Z с закреплением при прокрутке, поиск по названию, все казино сгруппированы по буквам, у каждого логотип и оценка.
- **Автор и даты:**
  - строка автора под заголовком с датой обновления;
  - карточка автора внизу: опубликовано, обновлено, дата теста, кто проверил факты;
  - поля «Stanowisko» и «Tematy» в профиле пользователя.
- **Оглавление:** строится автоматически из H2 и подсвечивает текущий раздел.
- **FAQ:** стилизованный стандартный блок «Szczegóły» (core/details).
- **Schema.org JSON-LD:** `Review` с `reviewRating` для обзоров, `Article` с `dateModified` и автором для записей и страниц.
- **Ответственная игра:** полоса 18+ сверху и блок «Graj odpowiedzialnie» над футером. Ссылки на помощь задаются в кастомайзере.
- **Мобильная версия:** закреплённая плашка бонуса «выбора редакции» и кнопка «наверх».
- **Логотип:** знак-фишка с буквой «e» и вордмарк e◆kasyna.com, фавикон SVG.

## Установка

1. Скопируйте папку в `wp-content/themes/ekasyna` (или загрузите zip через «Wygląd → Motywy → Dodaj nowy»).
2. Активируйте тему. Автоматически создадутся 6 категорий рейтинга: bez-depozytu, darmowe-spiny, blik, min-depozyt, nowe, krypto.
3. **Wygląd → Dane demo e-kasyna → Importuj.** Добавит 8 казино из текущего рейтинга и страницу «Wszystkie kasyna online» с каталогом A–Z. Необязательный шаг.
4. **Ustawienia → Bezpośrednie odnośniki** → «Nazwa wpisu» (`/%postname%/`), затем «Zapisz».
5. **Ustawienia → Czytanie** → «Strona główna wyświetla: statyczną stronę». Текст этой страницы (SEO-статья, FAQ) выводится на главной под рейтингом, вместе с оглавлением и автором.
6. **Wygląd → Menu.** Назначьте меню на места «Menu główne» и «Stopka — kolumna 1–3».
7. **Wygląd → Dostosuj → Motyw e-kasyna:**
   - hero;
   - выбор редакции;
   - количество казино в рейтинге;
   - тексты 18+ и ссылки на помощь;
   - текст футера.

## Поля казино

Поля находятся в метабоксе «Dane kasyna» при редактировании казино. Логотип казино — это изображение записи («Obrazek wyróżniający»), оптимально 340×168. Если логотипа нет, выводится плитка с инициалами в цвете бренда.

| Группа | Поля |
|---|---|
| Ocena | ocena 0–10 (сортировка рейтинга), etykieta redakcji, plakietki, kolor marki |
| Bonus | rodzaj, linia 1 / kwota / linia 3, min. depozyt, obrót, kod, dopisek o warunkach, **wszystkie bonusy** (список для обзора) |
| Fakty | licencja (regulator + numer), czas wypłat, osobliwości, rok, właściciel, strona, wsparcie, gry, waluta, aplikacja, wpłaty, wypłaty, producenci |
| Recenzja | plusy, minusy, data testu, fakty sprawdził |
| Link | link partnerski: открывается в новой вкладке с `rel="nofollow sponsored noopener"` |

> В рейтинг попадают только казино с заполненным полем «Ocena». В каталог A–Z попадают все опубликованные казино.

### Несколько бонусов в обзоре

Поле «Wszystkie bonusy»: одна строка — один бонус, поля разделены `|`:

```
Tytuł | Rodzaj | Min. depozyt | Obrót | Kod | Link
50 FS Big Bass Splash | Bonus bez depozytu | | x40 | |
100% do 2000 zł + 75 FS | 1. depozyt | 40 zł | x35 | WELCOME |
```

Пустые поля оставляйте пустыми. Если ссылка не указана, используется партнёрская ссылка казино. Первая карточка выделяется золотой рамкой.

## Шорткод

Рейтинг можно вставить в любую страницу или запись:

```
[ekasyna_ranking limit="10" podium="3" filters="1" kategoria="" title="TOP kasyna online" more="1"]
[ekasyna_az search="1" scores="1"]
```

Пример: `[ekasyna_ranking kategoria="blik" filters="0" limit="5" title="Kasyna z BLIK"]`.

## SEO-плагины

Если Yoast или Rank Math уже выводят свою schema, отключите разметку темы в дочерней теме:

```php
add_filter( 'ekasyna_schema', '__return_false' );
```

## Структура

```
ekasyna/
├── style.css                 заголовок темы
├── functions.php             настройка, ассеты, поля профиля
├── inc/
│   ├── helpers.php           иконки, логотип, ekasyna_casino()
│   ├── cpt.php               CPT „kasyno”, таксономия, сортировка
│   ├── meta.php              метабоксы
│   ├── ranking.php           запрос рейтинга, шорткод
│   ├── az.php                каталог A–Z, шорткод [ekasyna_az]
│   ├── toc.php               якоря H2 и оглавление
│   ├── customizer.php        настройки темы
│   ├── schema.php            JSON-LD
│   └── demo.php              импорт демо-данных
├── template-parts/
│   ├── casino/podium.php, row.php, bonus.php, facts.php,
│   │   bonus-list.php, info-table.php
│   ├── byline.php, author-box.php, toc.php, responsible.php
├── front-page.php, single-kasyno.php, archive-kasyno.php,
├── taxonomy-kategoria-kasyna.php, singular.php, index.php,
├── page-templates/wszystkie-kasyna.php
├── comments.php, 404.php, header.php, footer.php
└── assets/ css/theme.css, css/editor.css, js/theme.js, img/*.svg
```

## Требования

WordPress 6.2+, PHP 7.4+. Шрифты Bricolage Grotesque и Manrope подключаются с Google Fonts (latin-ext, польские символы).

## Лицензия

GPL-2.0-or-later.
