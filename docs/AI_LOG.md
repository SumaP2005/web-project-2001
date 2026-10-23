# AI Development Log

## Milestone 1 - Static Frontend

**Scope:** Build and refine the static Bosnian Thrift Shop frontend using the Bootstrap-based Start Bootstrap SB Admin template. The milestone includes hash-routed pages, shop category filters and product presentation, a multi-photo item carousel and reviews, account/profile forms, and a seller listing form. It does not include server-side persistence.

**AI tools used:** Copilot SDK in VS Code; workspace file search and inspection; targeted file edits; integrated browser/Playwright checks to inspect routes, form controls, and rendered UI.

**Important prompts (summarized):**

- Add the SPApp `<main>` and its routed sections to `index.html`.
- Fix content that was not visible and was covered by the side navigation; make the dashboard charts render.
- Add product star reviews and hover styling, and create an item detail/review page.
- Change the site name to Bosnian Thrift Shop.
- Add a profile reachable from the account menu, remove Activity Log, add a seller item form, and document the project and AI work.
- Move category choices from the navbar to filter buttons in the shop, with an All option, and replace the item detail photo with a three-photo carousel.

**AI mistakes and corrections:**

1. **The first routed-content change added only the empty SPApp sections.** The view remained blank because the local jQuery, SPApp, and router scripts were not all loaded in the shell. The missing dependencies were found by inspecting the rendered page and script imports; the scripts were added in dependency order.
2. **The first chart setup expected canvases to exist when the chart scripts ran.** SPApp inserts view markup asynchronously, so the chart scripts received no canvas and the chart panels stayed empty. Browser inspection confirmed the chart instances were missing. Initialization was moved into chart-view `onReady` callbacks and the chart helpers now target the active view.
3. **The first item-detail route had no matching shell section.** SPApp routes load into a section with the corresponding ID, so navigating to the item route produced an empty view. A browser click-through exposed the missing target; the `item` section was added and the route was re-tested.