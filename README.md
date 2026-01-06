---

# Laravel Multi-Tenant POS API

This project provides RESTful API endpoints for managing products, orders, and reports in a multi-tenant POS system. Authentication is handled via **Laravel Sanctum**, and tenant isolation is supported.

---

## Base URL

```
http://your-domain.com/api/
```

---

## Authentication

All endpoints (except `/api/token`) require authentication using **Laravel Sanctum**.

* Use the `/api/token` endpoint to retrieve an API token:

### Get API Token

```
GET /api/token
```

**Response Example:**

```json
{
  "token": "your-api-token-here",
  "tenant_uuid": "your-api-tenant-uuid-here"
}
```

Use this token in the `Authorization` header for all authenticated requests:

```
Authorization: Bearer your-api-token-here
X-Tenant-ID : your-api-tenant-uuid-here
Accept:application/json
Content-Type:application/json
```

---

## Middleware

1. `auth:sanctum` – Ensures the user is authenticated.
2. `tenant` – Ensures requests are scoped to the correct tenant.
3. `throttle:api` – Rate-limits requests to prevent abuse (applied to report endpoints).

---

## API Endpoints

### Products

CRUD operations for products.

```
Route::apiResource('products', ProductController::class)
```

| Method | Endpoint       | Description          |
| ------ | -------------- | -------------------- |
| GET    | /products      | List all products    |
| POST   | /products      | Create a new product |
| GET    | /products/{id} | Get product details  |
| PUT    | /products/{id} | Update a product     |
| DELETE | /products/{id} | Delete a product     |

---

### Orders

CRUD operations for orders.

```
Route::apiResource('orders', OrderController::class)
```

| Method | Endpoint     | Description        |
| ------ | ------------ | ------------------ |
| POST   | /orders      | Create a new order |
| PUT    | /orders/{id} | Cancel an order    |

---

### Reports

Rate-limited endpoints for reporting.

```
Route::middleware(['throttle:api'])->group(...)
```

| Method | Endpoint              | Description                          |
| ------ | --------------------- | ------------------------------------ |
| GET    | /reports/daily        | Get daily sales report               |
| GET    | /reports/top-products | Get top-selling products             |
| GET    | /reports/low-stock    | Get products with low stock quantity |

---

## Notes

* All endpoints are **tenant-specific**; make sure `tenant` middleware is applied.
* Rate-limiting is applied to report endpoints only.
* Use proper authentication for all protected routes.
* Make sure your `ProductController`, `OrderController`, `ReportController`, and `TokenController` are implemented according to your business logic.

---
