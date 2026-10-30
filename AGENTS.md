# Bosnian Thrift Shop - Project Guidance

## Project

Bosnian Thrift Shop is a second-hand marketplace prototype with a static frontend and a PHP/MySQL data-access layer. It includes a shop, product details and reviews, account pages, and a seller listing form. The frontend is not connected to the backend.

## Technology Stack

- HTML, CSS, and browser JavaScript
- Bootstrap 5.2.3 and the Start Bootstrap SB Admin template as the visual foundation
- jQuery and SPApp for hash-based loading of views
- Chart.js and Simple-DataTables for dashboard examples
- XAMPP/Apache for local preview

## Architecture

- `frontend/index.html` is the SPA shell: shared navigation, stylesheet/script imports, footer, and one `<section>` per SPApp route.
- `frontend/views/` contains route-loaded HTML fragments. Add a matching section to `index.html` and route in `frontend/js/router.js` for each new view.
- `frontend/js/` contains routing and small frontend behaviors. Keep UI logic browser-only unless backend functionality is explicitly requested.
- `frontend/assets/css/styles.css` is the Bootstrap template stylesheet. Put project-specific presentation in `frontend/assets/css/shop.css`.
- `backend/` contains the PHP database configuration and DAO layer. The current backend has no HTTP API or frontend integration.
- `database/web_project.sql` defines and seeds the MySQL `web_project` database; note that importing it drops an existing database with that name.
- `backend/rest/dao/BaseDao.php` provides the shared PDO connection and reusable query helpers. Do not modify it without an explicit request; each entity DAO extends it directly and uses its table's actual primary-key name.
- `backend/rest/dao/` contains CRUD DAOs for users, categories, items, item images, orders, and reviews, plus `test.php`, a database-backed example that prints DAO return values and removes its temporary records.
- `backend/config/config.php` is a local ignored file. Configure connection values there or through `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` environment variables; never commit local credentials.
- `docs/AI_LOG.md` records milestone-level AI usage, prompts, mistakes, and corrections.

## Conventions

- Use lowercase, kebab-case filenames for views and assets (for example, `add-item.html`); preserve `profil.html` as the requested profile view filename.
- Keep route names and shell section IDs identical. The route's `load` value must match a file in `frontend/views/`.
- Use Bootstrap utility and component classes first; add narrowly scoped project classes instead of changing template-wide styles.
- Use semantic headings, explicit form labels, appropriate input types, and accessible names for icon-only controls.
- Keep product/account forms honest about frontend-only behavior. Do not imply a listing was published or data was submitted to a server.
- Never store, display, or log passwords in browser storage. The profile demo may store only non-sensitive registration details locally.
- Preserve existing user changes and avoid unrelated edits.

## Running and Validation

- Preview with XAMPP/Apache at `http://localhost/web_project/frontend/index.html`, or open `frontend/index.html` directly in a browser.
- To view DAO output, import `database/web_project.sql` into a disposable local MySQL database, configure `backend/config/config.php`, and run `php backend/rest/dao/test.php`. The script prints CRUD/query results (with password fields redacted) and removes its temporary records; it does not assert expected values. The SQL script drops and recreates `web_project`; protect any data you need before importing.
- There is no configured frontend build or test suite. For UI changes, verify the affected SPApp route and its navigation in a browser.