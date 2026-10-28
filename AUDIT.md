# Local repair and verification — 28 October 2026

## Repairs

- Corrected URLs for shared styles, fonts, images, scripts, print layouts, chart assets, and empty-state illustrations under the public document root.
- Published built-in module assets and corrected their script/download URLs.
- Repaired the media migration's prefixed metadata lookup, made it work with MySQL and SQLite, and made retries preserve existing columns and constraints.
- Added responsive login and setup layouts. Setup action buttons remain visible on small screens while fields scroll.
- Removed the debug toolbar from the running Docker app, fixed the setup component warning, and added script cache-busting after frontend builds.
- Corrected currency selection, money formatting, rounding access, and preservation of currency during exchange-rate operations. These changes affect browser calculations and presentation; existing accounting records were not rewritten.
- Used the installed math library's browser distribution and added a small-batch frontend build command to reduce build memory requirements.
- Restored the web-app manifest and service worker under the public document root.
- Prepared writable cache directories on container startup. PHP dependencies now use a Docker volume, refreshed from the workspace when the Composer lock file changes, to avoid repeated Windows bind-mount reads.

## Verification

- Full backend feature suite: **215 tests, 641 assertions passed** using an isolated SQLite in-memory database. Results: `storage/logs/feature-results.xml` and `storage/logs/feature-tests.log`.
- Unit suite: **12 tests, 99 assertions passed**, including layout asset and manifest checks.
- Additional screen smoke test: **16 routes, 32 assertions passed**, covering banking, customers, vendors, items, invoices, bills, reports, settings, and setup.
- Browser calculation regression checks: **7 tests passed** for currency selection, money formatting/conversion, and quantity expressions.
- Every frontend bundle rebuilt successfully with `npm run dev:small`.
- Docker MySQL migrations are complete. Retrying the repaired media migration against existing tables succeeded.
- Chart, module-script, and service-worker URLs returned HTTP 200.
- Company settings, invoice empty state, and setup were visually checked using isolated test-data previews. Mobile checks used a 390-pixel viewport. The final setup preview had no console warnings or horizontal overflow, and its Save button remained visible.
- Login HTTP response samples improved from **17.20 seconds** before the dependency-volume change to **3.16 seconds** on the first request and **2.58 seconds** on a subsequent request. These are local samples, not a load benchmark.

## Running locally

Open http://127.0.0.1:8002. Run `docker compose up -d` to start the app, database, and phpMyAdmin.
Company onboarding choices remain for the user to complete. Paid integrations and external mail delivery require their own account configuration and were not exercised against live services.

Temporary public test-data previews were removed after visual verification. Screenshot evidence remains in `storage/logs/company-ui-fixed.png`, `storage/logs/wizard-desktop-fixed.png`, and `storage/logs/wizard-mobile-fixed.png`.
