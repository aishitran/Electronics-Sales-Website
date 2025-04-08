# Bootstrap Implementation in Electronics Sales Website

## Overview
This document outlines how Bootstrap 5.3.0 is implemented in the Electronics Sales Website project.

## Bootstrap Integration

### CDN Links
```html
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

## Bootstrap Components Usage

### 1. Grid System
The project utilizes Bootstrap's responsive grid system for layout management:

```html
<div class="container mt-4 px-3">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar content -->
        </div>
        <div class="col-md-9">
            <!-- Main content -->
        </div>
    </div>
</div>
```

### 2. Navigation Components

#### Navbar
```html
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Navigation content -->
    </div>
</nav>
```

#### Breadcrumb Navigation
```html
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/index.php">Trang chủ</a></li>
        <li class="breadcrumb-item active">Current Page</li>
    </ol>
</nav>
```

### 3. Form Components

#### Form Controls
```html
<div class="form-group">
    <input type="text" class="form-control" placeholder="Search...">
</div>
```

#### Select Dropdowns
```html
<select class="form-select" id="sortSelect">
    <option value="">Mặc định</option>
    <option value="name_asc">Tên A-Z</option>
    <option value="price_asc">Giá thấp đến cao</option>
</select>
```

### 4. Alert Components
```html
<div class="alert alert-success">
    <!-- Success message -->
</div>
<div class="alert alert-danger">
    <!-- Error message -->
</div>
```

### 5. Card Components
Used for product displays and information cards:
```html
<div class="card">
    <img class="card-img-top" src="...">
    <div class="card-body">
        <h5 class="card-title">Product Name</h5>
        <p class="card-text">Product Description</p>
    </div>
</div>
```

## Custom Styling with Bootstrap

### Custom Classes
The project extends Bootstrap's default styles while maintaining its design patterns:

```css
.sidebar {
    background-color: #2E1A16;
    padding: 10px;
    border-radius: 5px;
    min-height: 350px;
}

.navbar-top {
    background-color: #2E1A16;
    padding: 10px 0;
}

.category-bar {
    position: relative;
    top: -150px;
    max-width: 50%;
    margin: 4px auto;
    padding: 15px 10px;
    background-color: #2E1A16;
    border-radius: 10px;
}
```

## Bootstrap Icons Usage

The project extensively uses Bootstrap Icons for visual elements:

```html
<i class="bi bi-list"></i> <!-- List icon -->
<i class="bi bi-funnel"></i> <!-- Filter icon -->
<i class="bi bi-cart"></i> <!-- Cart icon -->
<i class="bi bi-person"></i> <!-- User icon -->
```

## Responsive Design

The website implements Bootstrap's responsive design principles:

1. Mobile-first approach
2. Responsive breakpoints
3. Fluid containers
4. Responsive navigation
5. Responsive images and media

## Utility Classes

Commonly used Bootstrap utility classes:

- Spacing: `mt-4`, `mb-3`, `px-3`, `py-2`
- Flexbox: `d-flex`, `justify-content-center`, `align-items-center`
- Text: `text-center`, `text-uppercase`
- Colors: `text-white`, `bg-primary`
- Borders: `rounded`, `border`
- Shadows: `shadow-sm`

## Best Practices

1. Consistent use of Bootstrap classes across components
2. Custom styling extends rather than overrides Bootstrap defaults
3. Responsive design considerations in all layouts
4. Proper use of Bootstrap's JavaScript components
5. Integration with custom CSS for specific styling needs 