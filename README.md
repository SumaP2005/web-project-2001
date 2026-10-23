# Bosnian Thrift Shop

Bosnian Thrift Shop is a static frontend prototype for a second-hand marketplace. Browse thrifted products, view item details and reviews, manage a profile, and explore the seller listing form.

## Run locally

With this repository in XAMPP's `htdocs` folder, start Apache and open:

`http://localhost/web_project/frontend/index.html`

You can also open `frontend/index.html` directly in a browser. The app is currently a frontend-only prototype: account details are stored only in the current browser, and item listings and reviews are not submitted to a server.

## Project structure

- `frontend/index.html` - application shell and navigation
- `frontend/views/` - shop, account, dashboard, and other routed pages
- `frontend/assets/` and `frontend/js/` - styles, images, and browser scripts
- `backend/` - reserved for future backend work
- `docs/AI_LOG.md` - AI usage and Milestone 1 notes, including the proposed database schema

## Database, ERD
The **Bosnian Thrift Shop** database is designed with 6 core relational entities to support user role management, catalog categorization, item management with multiple photo galleries, purchasing, and user feedback.

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