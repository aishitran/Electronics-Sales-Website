# View Relationships Summary

## Auth Views

### app/views/auth/login.php
1. Lines 1-75 -> `login()` in `AccountController` -> `loginUser()` in `AccountModel`
2. Lines 1-75 -> `showLogin()` in `AccountController` -> No model function

### app/views/auth/signup.php
1. Lines 1-86 -> `signup()` in `AccountController` -> `registerUser()` in `AccountModel`
2. Lines 1-86 -> `showSignup()` in `AccountController` -> No model function

### app/views/auth/account_info.php
1. Lines 1-108 -> `showAccountInfo()` in `AccountController` -> No model function
2. Lines 1-108 -> `updateAccountInfo()` in `AccountController` -> `updateUserInfo()` in `AccountModel`

## Cart Views

### app/views/cart/cart.php
1. Lines 1-383 -> `showCart()` in `ProductController` -> `getCartByUserId()` in `ProductModel`
2. Lines 1-383 -> `updateCartQuantity()` in `ProductController` -> `updateCartQuantity()` in `ProductModel`
3. Lines 1-383 -> `removeFromCart()` in `ProductController` -> `removeFromCart()` in `ProductModel`

### app/views/cart/checkout.php
1. Lines 1-96 -> `showCheckout()` in `OrderController` -> `getOrderById()` in `OrderModel`
2. Lines 1-96 -> `createOrder()` in `OrderController` -> `addOrder()` in `OrderModel`
3. Lines 1-96 -> `confirmPayment()` in `OrderController` -> `updateOrderStatus()` in `OrderModel`

## Home Views

### app/views/home/home.php
1. Lines 1-290 -> `listProducts()` in `ProductController` -> `getAllProducts()` in `ProductModel`
2. Lines 1-290 -> `viewCategory()` in `CategoryController` -> `getProductsByCategory()` in `CategoryModel`

## Order Views

### app/views/order/order_history.php
1. Lines 1-82 -> `orderHistory()` in `OrderController` -> `getOrdersByUserId()` in `OrderModel`

### app/views/order/order_status.php
1. Lines 1-92 -> `showOrderStatus()` in `OrderController` -> `getOrderById()` in `OrderModel`
2. Lines 1-92 -> `showOrderStatus()` in `OrderController` -> `getOrderDetails()` in `OrderModel`

## Product Views

### app/views/product/product_view.php
1. Lines 1-50 -> `viewProduct()` in `ProductController` -> `getProductById()` in `ProductModel`
2. Lines 1-50 -> `addToCart()` in `ProductController` -> `addToCart()` in `ProductModel`

### app/views/product/category_view.php
1. Lines 1-147 -> `viewCategory()` in `CategoryController` -> `getProductsByCategory()` in `CategoryModel`
2. Lines 1-147 -> `viewCategory()` in `CategoryController` -> `getAllCategories()` in `CategoryModel`

### app/views/product/all_product_view.php
1. Lines 1-138 -> `viewAllProduct()` in `ProductController` -> `getAllProducts()` in `ProductModel`

### app/views/product/search_results.php
1. Lines 1-84 -> `searchProducts()` in `ProductController` -> `searchProducts()` in `ProductModel`

## Admin Views

### app/views/admin/admin_panel.php
1. Lines 1-227 -> `adminPanel()` in `ProductController` -> `getAllProducts()` in `ProductModel`
2. Lines 1-227 -> `adminPanel()` in `ProductController` -> `getAllCategories()` in `CategoryModel`
3. Lines 1-227 -> `adminPanel()` in `ProductController` -> `getAllOrders()` in `OrderModel`

### app/views/admin/product_create.php
1. Lines 1-43 -> `createProduct()` in `ProductController` -> `addProduct()` in `ProductModel`

### app/views/admin/product_edit.php
1. Lines 1-49 -> `editProduct()` in `ProductController` -> `updateProduct()` in `ProductModel`

### app/views/admin/category_create.php
1. Lines 1-15 -> `create()` in `CategoryController` -> `createCategory()` in `CategoryModel`

### app/views/admin/category_edit.php
1. Lines 1-15 -> `edit()` in `CategoryController` -> `updateCategory()` in `CategoryModel`

### app/views/admin/order_edit.php
1. Lines 1-20 -> `edit()` in `OrderController` -> `updateOrder()` in `OrderModel`

## Layout Views

### app/views/layout/header.php
1. Lines 1-173 -> Used by all controllers for navigation
2. Lines 1-173 -> `getAllCategories()` in `CategoryModel`
3. Lines 1-173 -> `getCartByUserId()` in `ProductModel`

### app/views/layout/footer.php
1. Lines 1-57 -> Used by all controllers for common footer 