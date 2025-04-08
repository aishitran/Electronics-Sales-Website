<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .quantity-btn:hover {
            background-color: #e9ecef;
            color: #333;
            text-decoration: none;
        }
        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
        }
        .thumbnail-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
        }
        .loading {
            opacity: 0.5;
            pointer-events: none;
        }
        /* Toast notification styles */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .toast {
            min-width: 250px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .toast-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background-color: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .toast-body {
            padding: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Giỏ Hàng</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (empty($cartItems)): ?>
            <p class="text-center">Giỏ hàng của bạn đang trống.</p>
            <a href="/index.php" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <?php 
                    $total = 0;
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr data-product-id="<?= $item['id'] ?>">
                            <td>
                                <?php if (!empty($item['image']) && file_exists('public/' . $item['image'])): ?>
                                    <img src="/public/<?= htmlspecialchars($item['image']) ?>" class="thumbnail-image" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> VND</td>
                            <td>
                                <div class="quantity-control">
                                    <button class="quantity-btn decrease-btn" data-product-id="<?= $item['id'] ?>">-</button>
                                    <span class="quantity-input" data-product-id="<?= $item['id'] ?>"><?= htmlspecialchars($item['quantity']) ?></span>
                                    <button class="quantity-btn increase-btn" data-product-id="<?= $item['id'] ?>">+</button>
                                </div>
                            </td>
                            <td class="subtotal" data-product-id="<?= $item['id'] ?>"><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-btn" data-product-id="<?= $item['id'] ?>">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tổng Cộng:</strong></td>
                        <td id="total-amount"><?= number_format($total, 0, ',', '.') ?> VND</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <a href="/index.php" class="btn btn-secondary">Tiếp Tục Mua Sắm</a>
            <a href="/index.php?action=createOrder" class="btn btn-primary float-end">Thanh Toán</a>
        <?php endif; ?>
    </div>

    <!-- Toast container for notifications -->
    <div class="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Keep track of notification counts and active toasts
            const notificationCounts = {};
            const activeToasts = {};
            
            // Function to update cart quantity via AJAX
            function updateCartQuantity(productId, change) {
                // Show loading state
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                row.classList.add('loading');
                
                // Make AJAX request
                fetch(`/index.php?action=updateCartQuantity&id=${productId}&change=${change}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Remove loading state
                    row.classList.remove('loading');
                    
                    if (data.success) {
                        // Update quantity display
                        const quantityElement = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                        quantityElement.textContent = data.quantity;
                        
                        // Update subtotal
                        const subtotalElement = document.querySelector(`.subtotal[data-product-id="${productId}"]`);
                        subtotalElement.textContent = data.subtotal + ' VND';
                        
                        // Update total
                        document.getElementById('total-amount').textContent = data.total + ' VND';
                        
                        // Update cart count in header
                        updateHeaderCartCount(data.cartCount);
                        
                        // If quantity is 0, remove the row
                        if (data.quantity <= 0) {
                            row.remove();
                            
                            // Check if cart is empty
                            const remainingRows = document.querySelectorAll('#cart-items tr[data-product-id]');
                            if (remainingRows.length === 0) {
                                location.reload(); // Reload to show empty cart message
                            }
                        }
                        
                        // Show success message
                        showToast('success', data.message);
                    } else {
                        // Show error message
                        showToast('danger', data.message);
                    }
                })
                .catch(error => {
                    // Remove loading state
                    row.classList.remove('loading');
                    
                    // Show error message
                    showToast('danger', 'Có lỗi xảy ra khi cập nhật giỏ hàng.');
                    console.error('Error:', error);
                });
            }
            
            // Function to show toast notifications
            function showToast(type, message) {
                const toastContainer = document.querySelector('.toast-container');
                
                // Check if this message has been shown before
                if (notificationCounts[message]) {
                    notificationCounts[message]++;
                } else {
                    notificationCounts[message] = 1;
                }
                
                // Check if there's already an active toast for this message
                if (activeToasts[message]) {
                    // Update the existing toast
                    const existingToast = activeToasts[message].element;
                    const countText = notificationCounts[message] > 1 ? ` (${notificationCounts[message]})` : '';
                    
                    // Update the toast content
                    existingToast.querySelector('.toast-body').textContent = `${message}${countText}`;
                    
                    // Reset the toast timer
                    activeToasts[message].toast.dispose();
                    activeToasts[message].toast = new bootstrap.Toast(existingToast, {
                        autohide: true,
                        delay: 3000
                    });
                    activeToasts[message].toast.show();
                    
                    return;
                }
                
                // Create toast element
                const toastElement = document.createElement('div');
                toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
                toastElement.setAttribute('role', 'alert');
                toastElement.setAttribute('aria-live', 'assertive');
                toastElement.setAttribute('aria-atomic', 'true');
                
                // Add count indicator if this is a repeated message
                const countText = notificationCounts[message] > 1 ? ` (${notificationCounts[message]})` : '';
                
                toastElement.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}${countText}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                
                // Add to container
                toastContainer.appendChild(toastElement);
                
                // Initialize Bootstrap toast
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });
                
                // Store the toast reference
                activeToasts[message] = {
                    element: toastElement,
                    toast: toast
                };
                
                // Show the toast
                toast.show();
                
                // Remove from DOM after it's hidden
                toastElement.addEventListener('hidden.bs.toast', function() {
                    toastElement.remove();
                    delete activeToasts[message];
                });
            }
            
            // Function to update cart count in header
            function updateHeaderCartCount(count) {
                const cartBadge = document.querySelector('.navbar .fa-shopping-cart + .badge');
                if (cartBadge) {
                    cartBadge.textContent = count;
                    
                    // Add animation effect
                    cartBadge.classList.add('animate__animated', 'animate__pulse');
                    setTimeout(() => {
                        cartBadge.classList.remove('animate__animated', 'animate__pulse');
                    }, 1000);
                }
            }
            
            // Function to remove item from cart
            function removeFromCart(productId) {
                // Show loading state
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                row.classList.add('loading');
                
                // Make AJAX request
                fetch(`/index.php?action=removeFromCart&id=${productId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Remove loading state
                    row.classList.remove('loading');
                    
                    if (data.success) {
                        // Remove the row
                        row.remove();
                        
                        // Update total
                        document.getElementById('total-amount').textContent = data.total + ' VND';
                        
                        // Update cart count in header
                        updateHeaderCartCount(data.cartCount);
                        
                        // Check if cart is empty
                        const remainingRows = document.querySelectorAll('#cart-items tr[data-product-id]');
                        if (remainingRows.length === 0) {
                            location.reload(); // Reload to show empty cart message
                        }
                        
                        // Show success message
                        showToast('success', data.message);
                    } else {
                        // Show error message
                        showToast('danger', data.message);
                    }
                })
                .catch(error => {
                    // Remove loading state
                    row.classList.remove('loading');
                    
                    // Show error message
                    showToast('danger', 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
                    console.error('Error:', error);
                });
            }
            
            // Add event listeners for increase/decrease buttons
            document.querySelectorAll('.increase-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    updateCartQuantity(productId, 1);
                });
            });
            
            document.querySelectorAll('.decrease-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    updateCartQuantity(productId, -1);
                });
            });
            
            // Add event listeners for remove buttons
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    removeFromCart(productId);
                });
            });
        });
    </script>

</body>
</html>