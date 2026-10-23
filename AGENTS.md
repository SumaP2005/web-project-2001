# Bosnian Thrift Shop - Project Guidance

## Project

Bosnian Thrift Shop is a static frontend prototype for a second-hand marketplace. It includes a shop, product details and reviews, account pages, and a seller listing form. The current milestone has no application backend or implemented database.

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
- `backend/` is reserved for future server-side code; it is currently only a placeholder.
- `docs/AI_LOG.md` records milestone-level AI usage, prompts, mistakes, and corrections. The proposed database structure is documented there and is not implemented.

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
- There is no configured build or test suite. For UI changes, verify the affected SPApp route and its navigation in a browser.