# Crank E-Commerce System - Setup & User Guide

## 1. Local Setup Instructions
Run these commands in your terminal to import and configure the project on your local machine.

1. `git clone https://github.com/Dean5w6/crank-ecommerce.git`
2. `cd crank-ecommerce`
3. `composer install`
4. `npm install && npm run build`
5. `cp .env.example .env`
6. Open `.env` and configure your database (e.g., `DB_DATABASE=crank`) and Mailtrap credentials.
7. `php artisan key:generate`
8. `php artisan migrate:fresh --seed`
9. `php artisan storage:link`
10. `php artisan serve`

## 2. Default Test Credentials
The database seeder automatically creates the following accounts for testing:

* **Admin Account:** `admin@crank.com` | Password: `password`
* **Customer Accounts:** `customer1@example.com` through `customer9@example.com` | Password: `password`

## 3. How to Use the System (Feature Guide)

### Storefront & Customer Features
* **Catalog & Filters:** Navigate to the Catalog page. You can filter products by Category, Brand, and Price range. 
* **Scout Search:** Use the search bar on the catalog to dynamically find products. 
* **Checkout:** Add items to your cart and proceed to checkout. You must be logged in (and email verified) to complete a transaction.
* **Customer Dashboard:** Log in as a customer to view past transactions. Click "View Details" to see specific items purchased.
* **Product Reviews:** After completing a checkout, visit the purchased product's page. A "Write a Review" form will appear. Profanity is automatically filtered.

### Admin Features
Log in as `admin@crank.com` to access the Admin Dashboard.

* **Dashboard & Charts:** View the yearly sales bar chart and the product distribution pie chart.
* **Product Management (CRUD):** Use the interactive Datatable to search and sort products. You can add new products, upload multiple photos (or a single photo), and set main images.
* **Excel Import:** Bulk-add products by uploading a `.xlsx` or `.csv` file on the Products page.
  * **File Formatting:** Row 1 must contain exactly these headers (lowercase): `name`, `description`, `price`, `stock`, `category_id`, `brand_id`.
  * **Crucial Note:** The `category_id` and `brand_id` columns must contain numeric IDs (e.g., `1`, `2`) corresponding to existing categories and brands in the system, NOT text names like "Trek" or "Mountain Bikes".
* **User Management:** Use the Users Datatable to view all registered accounts. Click "Edit User" to change a customer's role to Admin, or toggle their status to Inactive (preventing them from logging in).
* **Transaction & Email Receipts:** View customer orders. When you update a transaction status (e.g., to "Completed"), the system automatically emails the customer a PDF receipt using Mailtrap.
* **Review Moderation:** Access the Reviews datatable to monitor all product feedback and delete inappropriate comments.
