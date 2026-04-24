This project is a RESTful API built with Laravel that simulates a multi-vendor product and inventory management system.

Vendors can manage their products, while users (guests) can browse products and place orders. The system ensures proper inventory handling, data consistency, and follows backend best practices.
Setup Instructions
1. Clone Repository
git clone https://github.com/abbey112/losoapi.git
cd losoapi
2. Install Dependencies
composer install
3. Configure Environment
cp .env.example .env

Update your database credentials in .env:

DB_DATABASE=losoapi
DB_USERNAME=root
DB_PASSWORD=
4. Generate Application Key
php artisan key:generate
5. Start Development Server
php artisan serve

API will be available at:

http://127.0.0.1:8000/api
 Authentication
Authentication is handled using Laravel Sanctum.
Register
POST /api/auth/register
Request Body:

{
  "name": "Vendor 1",
  "email": "vendor@example.com",
  "password": "password"
}

Login

POST /api/auth/login

Response:

{
  "token": "your-access-token"
}

Use token in headers:

Authorization: Bearer {token}
Product Endpoints
Public Routes
Get All Active Products

GET /api/products

Get Single Product

GET /api/products/{id}

Search Products

GET /api/search?query=shoe
Vendor Routes (Authenticated)
Create Product

POST /api/products

{
  "name": "Sneakers",
  "description": "Comfortable shoes",
  "price": 15000,
  "stock_quantity": 10
}
Update Product

PUT /api/products/{id}

Delete Product
DELETE /api/products/{id}
Get Vendor Products

GET /api/vendor
Order Endpoint
Place Order
POST /api/order

{
  "product_id": 1,
  "quantity": 2
}
Design Decisions
Laravel Sanctum was used for lightweight token-based authentication
Service Layer Pattern was implemented to separate business logic from controllers
Database Transactions ensure atomic operations during order placement
Pagination is applied to product listing for scalability
RESTful API design with proper HTTP status codes
Assumptions
Users in the system act as vendors
Orders are simplified and do not require full user accounts
Payment processing is not included (out of scope)
Product status determines visibility to the public
