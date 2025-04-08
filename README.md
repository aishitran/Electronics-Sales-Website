# Electronics Sales Website

## Development Server
```bash
php -S localhost:8000
```

## Unused Functions and Views

### Potentially Unused Functions

#### In ProductModel.php:
- `getUserById()` - Appears to be misplaced in ProductModel, should be in UserModel
- `updateUserInfo()` - Appears to be misplaced in ProductModel, should be in UserModel
- `registerUser()` - Appears to be misplaced in ProductModel, should be in UserModel
- `loginUser()` - Appears to be misplaced in ProductModel, should be in UserModel

#### In OrderController.php:
- `deleteOrder()` - Duplicate function, appears twice (lines 99 and 204)

#### In ProductController.php:
- `adminPanel()` - May be unused if admin functionality is handled elsewhere
- `createProduct()` - May be unused if product creation is handled through a different interface

### Code Organization Issues

1. User-related functions are incorrectly placed in ProductModel.php
2. Duplicate function definitions in OrderController.php
3. Some admin-related functions may be redundant or misplaced

### Recommendations

1. Move user-related functions from ProductModel.php to a dedicated UserModel.php
2. Remove duplicate deleteOrder() function in OrderController.php
3. Review and potentially consolidate admin-related functions
4. Consider implementing proper dependency injection for database connections
5. Review and potentially remove unused admin panel functions if they're handled elsewhere

Note: This analysis is based on static code review. Some functions may be used through dynamic calls or AJAX requests not visible in the static analysis.

## Potentially Unused or Inaccessible Views

### Admin Views
- `admin_products.php` - Appears to be redundant as product management is handled through `admin_panel.php`
- `admin_orders.php` - May be redundant as order management is also handled through `admin_panel.php`

### Product Views
- `contact.php` - No direct route found in the main routing system, may be inaccessible
- `search_results.php` - Search functionality may not be fully implemented as there's no search form in the main navigation

### Order Views
- `order_status.php` - May be partially implemented as the status update functionality in OrderController appears incomplete

### Authentication Views
- `account_info.php` - Some user-related functions are misplaced in ProductModel, which may affect the functionality of this view

### Recommendations for Views
1. Consolidate admin views into a single admin panel interface
2. Implement proper routing for the contact page
3. Complete the search functionality implementation
4. Move user-related functions to appropriate models
5. Review and potentially remove redundant admin views
6. Ensure all views have proper access controls and error handling

Note: This analysis is based on static code review. Some views may be accessed through dynamic routes or AJAX requests not visible in the static analysis.