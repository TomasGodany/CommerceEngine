# Commerce Engine (CE)

Commerce Engine je moderná headless commerce platforma postavená na Laravel frameworku. Poskytuje kompletné API pre vlastné e-shopy, mobilné aplikácie a B2B portály a administráciu ako centrálne miesto na správu všetkých obchodných procesov.

## Obsah administrácie

Aktuálne je pripravená autentifikácia a základ pre správu produktov (PIM). Ostatné moduly (WMS, OMS, CRM, cenníky, dokumenty, integrácie, analytika) sa budú postupne dopĺňať.

### Dashboard
- Prehľad počtu produktov, kategórií, značiek a používateľov

### Prihlásenie a používatelia
- Registrácia a prihlásenie priamo v administrácii (session-based, bez samostatného API)
- Role používateľov: `admin`, `manager`, `customer`
- Middleware pre kontrolu oprávnení podľa role (`EnsureUserHasRole`)

### Správa produktov (PIM)
- Produkty a varianty (`Product`, `ProductVariant`)
- Kategórie (`Category`)
- Značky a výrobcovia (`Brand`)
- REST API endpointy pre produkty, kategórie a značky (`routes/api.php`)

### Pripravované moduly
- Skladové hospodárstvo (WMS)
- Správa objednávok (OMS)
- CRM
- Cenníky a akcie
- Dokumenty (faktúry, dodacie listy, PDF exporty)
- Integrácie (platobné brány, dopravcovia, ERP)
- Analytika
- Rozšírené nastavenia systému

## Technológie

- PHP 8.4, Laravel 13
- SQLite (predvolená databáza pre vývoj)
- Blade + Tailwind CSS 4 (frontend administrácie)
- Vite (bundlovanie assetov)
- PHPUnit (testy), Laravel Pint (code style)

## Inštalácia a spustenie

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
```

Spustenie vývojového servera:

```bash
php artisan serve
```

Aplikácia bude dostupná na `http://127.0.0.1:8000`. Pre vývoj frontend assetov spustite súbežne:

```bash
npm run dev
```

Prípadne pre produkčný build:

```bash
npm run build
```

## Prihlásenie

Po nasadení seederov je k dispozícii testovací administrátorský účet:

- e-mail: `admin@commerce-engine.test`
- heslo: `password`

Nový používateľ sa môže zaregistrovať cez `/register` (dostane rolu `customer`) a prihlásiť cez `/login`. Po prihlásení je presmerovaný na chránený `/dashboard`.

## Testovanie

```bash
php artisan test
```

## Code style

```bash
vendor/bin/pint --dirty --format agent
```
