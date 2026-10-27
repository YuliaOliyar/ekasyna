#!/usr/bin/env python3
"""
Statyczny podgląd motywu dla GitHub Pages.

Pobiera strony z działającej lokalnie instalacji WordPress z aktywnym motywem
„ekasyna” i zapisuje je jako HTML do docs/demo/, z relatywnymi linkami,
kopiuje użyte pliki CSS/JS/SVG i tworzy docs/index.html z listą szablonów.

Użycie:
    python3 tools/build-preview.py --site http://localhost:8099 --wp /ścieżka/do/wordpress --urls urls.json

urls.json: lista [ścieżka, nazwa, grupa], np. ["/recenzje/lemon-casino/", "Recenzja: Lemon Casino", "Recenzje"].
"""
import argparse
import datetime
import html
import json
import os
import re
import shutil
import urllib.request
from urllib.error import HTTPError

MONTHS_GEN = {
    'January': 'stycznia', 'February': 'lutego', 'March': 'marca', 'April': 'kwietnia',
    'May': 'maja', 'June': 'czerwca', 'July': 'lipca', 'August': 'sierpnia',
    'September': 'września', 'October': 'października', 'November': 'listopada', 'December': 'grudnia',
}
MONTHS_NOM = {
    'January': 'styczeń', 'February': 'luty', 'March': 'marzec', 'April': 'kwiecień',
    'May': 'maj', 'June': 'czerwiec', 'July': 'lipiec', 'August': 'sierpień',
    'September': 'wrzesień', 'October': 'październik', 'November': 'listopad', 'December': 'grudzień',
}
MONTH_RE = '|'.join(MONTHS_GEN)


def fetch(url):
    try:
        with urllib.request.urlopen(url) as r:
            return r.read().decode('utf-8')
    except HTTPError as e:  # 404 też ma treść
        return e.read().decode('utf-8')


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--site', required=True)
    ap.add_argument('--wp', required=True)
    ap.add_argument('--urls', required=True)
    ap.add_argument('--out', default=os.path.join(os.path.dirname(__file__), '..', 'docs'))
    a = ap.parse_args()

    site = a.site.rstrip('/') + '/'
    out = os.path.abspath(a.out)
    demo = os.path.join(out, 'demo')
    if os.path.isdir(out):
        shutil.rmtree(out)
    os.makedirs(demo)

    pages = json.load(open(a.urls, encoding='utf-8'))
    assets = set()
    esc_site = site.replace('/', '\\/')

    for path, _name, _group in pages:
        if '?' in path:  # wariant strony (np. ?popup=1) — tylko wiersz w liście
            continue
        raw = fetch(site.rstrip('/') + path)
        segs = [s for s in path.strip('/').split('/') if s]
        prefix = '../' * len(segs) if segs else './'

        for m in re.finditer(re.escape(site) + r'((?:wp-content|wp-includes)/[^"\'?)\s]+)', raw):
            assets.add(m.group(1))

        h = raw
        # Niepotrzebne w podglądzie linki z <head>.
        h = re.sub(r'<link rel=[\'"](?:alternate|EditURI|https://api\.w\.org/|shortlink|wlwmanifest)[\'"][^>]*>\s*', '', h)
        h = re.sub(r'<link rel=[\'"]canonical[\'"][^>]*>\s*', '', h)
        # Filtry rankingu → strony kategorii.
        h = re.sub(r'href="[^"]*\?ek_kat=([a-z0-9-]+)#ranking"', lambda m: 'href="%skasyna/%s/"' % (site, m.group(1)), h)
        # Formularz wyszukiwania nie działa statycznie.
        h = h.replace('action="%s"' % site, 'action="#" onsubmit="return false"')
        # Adresy → relatywne.
        h = h.replace(site, prefix).replace(esc_site, prefix.replace('/', '\\/'))
        h = h.replace(site.rstrip('/'), prefix.rstrip('/') or '.')
        # Adresy od korzenia („/ranking-kasyn/”) → relatywne.
        h = re.sub(r'(href|src|action)="/(?!/)([^"]*)"', lambda m: '%s="%s%s"' % (m.group(1), prefix, m.group(2)), h)
        # Formularz newslettera nie wysyła danych w podglądzie.
        h = re.sub(r'action="[^"]*wp-admin/admin-post\.php"', 'action="#demo"', h)
        # Katalogi → index.html (działa też po otwarciu z dysku).
        h = re.sub(r'href="((?:\./|(?:\.\./)+)[^"#:]*?/)(#[^"]*)?"',
                   lambda m: 'href="%sindex.html%s"' % (m.group(1), m.group(2) or ''), h)
        h = re.sub(r'href="(\./|(?:\.\./)+)"', r'href="\1index.html"', h)
        # Polska data (lokalna instalacja bez tłumaczeń).
        h = re.sub(r'(\d{1,2}) (%s) (\d{4})' % MONTH_RE, lambda m: '%s %s %s' % (m.group(1), MONTHS_GEN[m.group(2)], m.group(3)), h)
        h = re.sub(r'· (%s)(?=\s*<)' % MONTH_RE, lambda m: '· ' + MONTHS_NOM[m.group(1)], h)
        h = h.replace('lang="en-US"', 'lang="pl-PL"')

        dest = os.path.join(demo, *segs, 'index.html')
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        open(dest, 'w', encoding='utf-8').write(h)

    for rel in sorted(assets):
        src = os.path.join(a.wp, rel)
        if os.path.isfile(src):
            dst = os.path.join(demo, rel)
            os.makedirs(os.path.dirname(dst), exist_ok=True)
            shutil.copy2(src, dst)

    # Lista szablonów.
    theme_css = 'demo/wp-content/themes/ekasyna/assets/css/theme.css'
    rows, last = [], None
    for path, name, group in pages:
        if group != last:
            rows.append('<tr class="grp"><th colspan="2" scope="rowgroup">%s</th></tr>' % html.escape(group))
            last = group
        base_path, _, query = path.partition('?')
        href = 'demo' + (base_path if base_path.endswith('/') else base_path + '/') + 'index.html' + ('?' + query if query else '')
        rows.append('<tr><td><a href="%s">%s</a></td><td>%s</td></tr>' % (html.escape(href), html.escape('demo' + path), html.escape(name)))
    chip = open(os.path.join(os.path.dirname(__file__), '..', 'assets', 'img', 'logo-mark.svg'), encoding='utf-8').read()
    chip = chip.replace('<svg ', '<svg aria-hidden="true" ', 1).replace('width="96" height="96"', 'width="42" height="42"')
    today = datetime.date.today().strftime('%d.%m.%Y')
    index = INDEX_TPL.format(css=theme_css, chip=chip, rows='\n'.join(rows), count=len(pages), date=today)
    open(os.path.join(out, 'index.html'), 'w', encoding='utf-8').write(index)
    open(os.path.join(out, '.nojekyll'), 'w').close()
    print('pages:', len(pages), 'assets:', len(assets), '→', out)


INDEX_TPL = '''<!doctype html>
<html lang="pl-PL">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Szablony motywu e-kasyna</title>
<meta name="description" content="Lista szablonów motywu WordPress e-kasyna — podgląd statyczny.">
<meta name="robots" content="noindex">
<link rel="icon" type="image/svg+xml" href="demo/wp-content/themes/ekasyna/assets/img/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Manrope:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{css}">
<style>
.ek-tpl {{ width: 100%; border-collapse: collapse; background: #fff; border: 1px solid var(--ek-line); border-radius: 16px; overflow: hidden; font-size: 15px; }}
.ek-tpl th, .ek-tpl td {{ padding: 12px 18px; text-align: left; border-top: 1px solid var(--ek-line-2); }}
.ek-tpl thead th {{ background: var(--ek-surface-2); font-size: 13px; color: var(--ek-muted); border-top: 0; }}
.ek-tpl .grp th {{ background: var(--ek-bg); font-family: var(--ek-font-display); font-weight: 800; font-size: 16px; color: var(--ek-red-dark); }}
.ek-tpl td:first-child {{ font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 13px; }}
.ek-tpl a {{ color: var(--ek-ink); font-weight: 600; }}
.ek-tplwrap {{ overflow-x: auto; }}
</style>
</head>
<body>
<div class="ek-topbar"><div class="ek-container ek-topbar__inner"><div class="ek-topbar__left"><span class="ek-18">18+</span><span>Podgląd statyczny motywu WordPress</span></div><span class="ek-topbar__date">Stan na {date}</span></div></div>
<header class="ek-header"><div class="ek-container ek-header__inner">
<a class="ek-logo" href="demo/index.html">{chip}<span class="ek-wordmark">e<svg class="ek-wordmark__diamond" viewBox="0 0 10 10" aria-hidden="true"><path d="M5 0L10 5 5 10 0 5z" fill="#C8102E"/></svg>kasyna<span class="ek-wordmark__tld">.com</span></span></a>
<div class="ek-header__actions"><a class="ek-btn ek-btn--dark" href="demo/index.html">Otwórz stronę główną</a></div>
</div></header>
<main class="ek-main">
<section class="ek-band ek-band--top"><div class="ek-container ek-page-hero">
<nav class="ek-breadcrumbs" aria-label="Okruszki"><span aria-current="page">1. Szablony</span></nav>
<h1 class="ek-h1 ek-h1--page">Szablony motywu e-kasyna</h1>
<p class="ek-lead">Lista {count} stron wygenerowanych z motywu WordPress na danych demo. Kliknij adres, aby otworzyć podgląd szablonu.</p>
</div></section>
<div class="ek-container ek-section"><div class="ek-tplwrap">
<table class="ek-tpl">
<thead><tr><th scope="col">Adres</th><th scope="col">Szablon / strona</th></tr></thead>
<tbody>
{rows}
</tbody>
</table>
</div>
<p class="ek-fineprint">Wyszukiwarka i komentarze nie działają w podglądzie statycznym — to funkcje WordPressa.</p>
</div>
</main>
<footer class="ek-footer"><div class="ek-container"><div class="ek-footer__bottom" style="margin-top:0;border-top:0;padding-top:0">
<span>© e-kasyna.com · Tylko dla osób pełnoletnich (18+)</span><span>Motyw: github.com/YuliaOliyar/ekasyna</span>
</div></div></footer>
</body>
</html>
'''

if __name__ == '__main__':
    main()
