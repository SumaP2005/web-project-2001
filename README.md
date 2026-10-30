# Bosnian Thrift Shop

Bosnian Thrift Shop is a second-hand marketplace prototype. The frontend is static and browser-only; the PHP backend currently provides a database connection and a DAO layer, but no HTTP API or frontend-to-backend integration.

## Run locally

With this repository in XAMPP's `htdocs` folder, start Apache and open:

`http://localhost/web_project/frontend/index.html`

You can also open `frontend/index.html` directly in a browser. Account details are stored only in the current browser, and item listings and reviews are not submitted to the backend.

## Project structure

- `frontend/index.html` - application shell and navigation
- `frontend/views/` - shop, account, dashboard, and other routed pages
- `frontend/assets/` and `frontend/js/` - styles, images, and browser scripts
- `backend/` - PHP database configuration and DAO layer
- `database/web_project.sql` - MySQL schema and sample seed data
- `docs/AI_LOG.md` - AI usage notes for Milestones 1 and 2

## Milestone 2: Database and DAOs

The MySQL schema and sample data are in [`database/web_project.sql`](database/web_project.sql). Import this script into a local MySQL development instance to create and seed the `web_project` database. **The script begins by dropping the existing `web_project` database; back up any data you need before importing it.**

Set database connection values in `backend/config/config.php` or the `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` environment variables. The local configuration file is ignored by Git; copy [`backend/config/config.example.php`](backend/config/config.example.php) as a starting point and adjust it for your MySQL setup. The configured database account must have access to `web_project`.

The DAOs in `backend/rest/dao/` provide prepared-statement CRUD operations for users, categories, items, item images, orders, and reviews. Each entity DAO extends `BaseDao` directly and communicates with the database only; HTTP endpoints and application validation belong outside this layer.

After importing the schema and configuring the connection, run the integration test from the repository root:

```sh
php backend/rest/dao/test.php
```

The script prints the return values from the six DAOs' CRUD and entity-specific read methods, then removes the linked temporary records. Password fields are redacted in printed results. It is an output example, not an assertion-based test. Database errors are reported by PHP/PDO rather than hidden.

## Database design

### Entity-Relationship Overview & Photo Support
1. **Users (`1` to `N`) Items**: A user (seller/admin) can list multiple thrift items, but each item belongs to a single seller.
2. **Categories (`1` to `N`) Items**: A category encompasses many thrift items, while each item belongs to one category.
3. **Items (`1` to `N`) Item Images**: An item can have multiple photos uploaded (stored via external URLs/paths), with an `is_primary` toggle to designate the main listing thumbnail.
4. **Users (`1` to `N`) Orders**: A buyer can place multiple orders over time.
5. **Items (`1` to `N`) Orders**: Unique thrift inventory items link directly to their corresponding sales order.
6. **Users (`1` to `N`) Reviews**: Users can leave multiple reviews and ratings, each mapped to a specific product.

### Schema Summary
* **`users`**: Manages accounts, credentials, and access permissions (`user`, `admin`, `seller`).
* **`categories`**: Organizes inventory into structured groups.
* **`items`**: Tracks product titles, descriptions, pricing, and live availability status (`available`/`sold`).
* **`item_images`**: Stores references/paths to product photographs, enabling multi-image listings.
* **`orders`**: Keeps a log of financial transactions and buyer history.
* **`reviews`**: Stores scoring feedback (1-5) and buyer commentary.

<img src="docs/images/erd.png" alt="ER Diagram" width="500">